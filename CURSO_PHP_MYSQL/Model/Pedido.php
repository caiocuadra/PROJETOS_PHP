<?php

// Certifique-se de que os Models necessários estão incluídos para acessar os métodos de listagem/atualização
$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Model/Carrinho.php';
require_once $path . '/Model/Produto.php'; // Pode ser útil para validar estoque, etc.

class Pedido {
    private $id;
    private $usuario_id;
    private $data_pedido;
    private $valor_total;
    private $status;

    public function __construct($id = null, $usuario_id = null, $data_pedido = null, $valor_total = null, $status = null) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->data_pedido = $data_pedido;
        $this->valor_total = $valor_total;
        $this->status = $status;
    }

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getDataPedido() { return $this->data_pedido; }
    public function getValorTotal() { return $this->valor_total; }
    public function getStatus() { return $this->status; }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
    public function setDataPedido($data_pedido) { $this->data_pedido = $data_pedido; }
    public function setValorTotal($valor_total) { $this->valor_total = $valor_total; }
    public function setStatus($status) { $this->status = $status; }

    /**
     * @return mysqli A conexão com o banco de dados.
     * @throws Exception Se houver um erro na conexão.
     */
    private function conectar() {
        $servidor = "localhost";
        $usuario_bd = "root";
        $senha_bd = "";
        $nome_banco = "ecommerce";

        $conexao = new mysqli($servidor, $usuario_bd, $senha_bd, $nome_banco);
        if ($conexao->connect_error) {
            throw new Exception("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }
        return $conexao;
    }

    /**
     * Finaliza a compra criando um novo pedido e movendo os itens do carrinho para Pedido_Produtos.
     * @param int $idUsuario O ID do usuário que está finalizando a compra.
     * @return int|false O ID do pedido criado em caso de sucesso, ou false em caso de falha.
     */
    public function finalizarCompra($idUsuario) {
        $conexao = null;
        try {
            $conexao = $this->conectar();
            $conexao->begin_transaction(); // Inicia uma transação

            $objCarrinho = new Carrinho();
            $itensCarrinho = $objCarrinho->listarItensCarrinho($idUsuario);

            if (empty($itensCarrinho)) {
                throw new Exception("Carrinho vazio. Não é possível finalizar a compra.");
            }

            $valorTotalPedido = 0;
            foreach ($itensCarrinho as $item) {
                $valorTotalPedido += ($item['VALOR'] * $item['QUANTIDADE']);
            }

            // 1. Criar o Pedido principal
            $dataAtual = date('Y-m-d H:i:s');
            $statusInicial = 'Pendente'; // Pode ser 'Processando', 'Novo', etc.

            $sqlPedido = "INSERT INTO Pedidos (USUARIO_ID, DATA_PEDIDO, VALOR, STATUS) VALUES (?, ?, ?, ?)";
            $stmtPedido = $conexao->prepare($sqlPedido);

            if (!$stmtPedido->execute(array( $idUsuario, $dataAtual, $valorTotalPedido, $statusInicial))) {
                throw new Exception("Erro ao criar o pedido principal: " . $stmtPedido->error);
            }
            $pedidoId = $conexao->insert_id; // Pega o ID do pedido recém-criado
            $stmtPedido->close();

            // 2. Mover itens do carrinho para a tabela Pedido_Produtos
            // Assumimos que você tem uma tabela PEDIDO_PRODUTOS com colunas:
            // ID, PEDIDO_ID (FK), PRODUTO_ID (FK), QUANTIDADE, VALOR_UNITARIO
            $sqlPedidoProduto = "INSERT INTO Pedido_Produtos (PEDIDO_ID, PRODUTO_ID, QUANTIDADE, VALOR_UNITARIO) VALUES (?, ?, ?, ?)";
            $stmtPedidoProduto = $conexao->prepare($sqlPedidoProduto);

            foreach ($itensCarrinho as $item) {
                $produtoId = $item['produto_id'];
                $quantidade = $item['QUANTIDADE'];
                $valorUnitario = $item['VALOR'];

                $stmtPedidoProduto->bind_param("iiid", $pedidoId, $produtoId, $quantidade, $valorUnitario);
                if (!$stmtPedidoProduto->execute()) {
                    throw new Exception("Erro ao adicionar produto ao pedido: " . $stmtPedidoProduto->error);
                }
            }
            $stmtPedidoProduto->close();

            // 3. Limpar o carrinho do usuário
            $sqlLimparCarrinho = "DELETE FROM Carrinhos WHERE ID_USUARIO = ?";
            $stmtLimparCarrinho = $conexao->prepare($sqlLimparCarrinho);
            $stmtLimparCarrinho->bind_param("i", $idUsuario);
            if (!$stmtLimparCarrinho->execute()) {
                throw new Exception("Erro ao limpar o carrinho: " . $stmtLimparCarrinho->error);
            }
            $stmtLimparCarrinho->close();

            $conexao->commit(); // Confirma a transação
            $conexao->close();
            return $pedidoId;

        } catch (Exception $e) {
            if ($conexao) {
                $conexao->rollback(); // Reverte a transação em caso de erro
                $conexao->close();
            }
            error_log("Erro ao finalizar compra: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista os pedidos de um usuário ou todos os pedidos (para admin).
     * @param int|null $idUsuario O ID do usuário (opcional). Se null, lista todos.
     * @return array Um array de arrays associativos, representando os pedidos.
     */
    public function listarPedidos($idUsuario = null) {
        $pedidos = [];
        try {
            $conexao = $this->conectar();
            $sql = "SELECT p.ID, p.USUARIO_ID, u.NOME AS NOME_USUARIO, p.DATA_PEDIDO, p.VALOR AS VALOR_TOTAL, p.STATUS 
                    FROM Pedidos p
                    JOIN Usuarios u ON p.USUARIO_ID = u.ID";
            $params = [];
            $types = "";

            if ($idUsuario !== null) {
                $sql .= " WHERE p.USUARIO_ID = ?";
                $params[] = $idUsuario;
                $types .= "i";
            }
            $sql .= " ORDER BY p.DATA_PEDIDO DESC";

            $stmt = $conexao->prepare($sql);
            if ($idUsuario !== null) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($pedido = $resultado->fetch_assoc()) {
                $pedidos[] = $pedido;
            }
            $stmt->close();
            $conexao->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $pedidos;
    }
}

?>
