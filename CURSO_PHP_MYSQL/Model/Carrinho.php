<?php

class Carrinho {
    private $id;
    private $id_usuario;
    private $id_produto;
    private $quantidade;

    public function __construct($id = null, $id_usuario = null, $id_produto = null, $quantidade = null) {
        $this->id = $id;
        $this->id_usuario = $id_usuario;
        $this->id_produto = $id_produto;
        $this->quantidade = $quantidade;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getIdProduto() { return $this->id_produto; }
    public function getQuantidade() { return $this->quantidade; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setIdProduto($id_produto) { $this->id_produto = $id_produto; }
    public function setQuantidade($quantidade) { $this->quantidade = $quantidade; }

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
     * Adiciona um produto ao carrinho ou atualiza sua quantidade se já existir.
     * @param int $idUsuario ID do usuário.
     * @param int $idProduto ID do produto.
     * @param int $quantidade Quantidade a ser adicionada.
     * @return bool True se a operação for bem-sucedida, false caso contrário.
     */
    public function adicionar($idUsuario, $idProduto, $quantidade) {
        try {
            $conexao = $this->conectar();
            
            // Verifica se o item já existe no carrinho do usuário
            $sqlCheck = "SELECT ID, QUANTIDADE FROM Carrinhos WHERE ID_USUARIO = ? AND ID_PRODUTO = ?";
            $stmtCheck = $conexao->prepare($sqlCheck);
            $stmtCheck->bind_param("ii", $idUsuario, $idProduto);
            $stmtCheck->execute();
            $resultadoCheck = $stmtCheck->get_result();
            $itemExistente = $resultadoCheck->fetch_assoc();
            $stmtCheck->close();

            if ($itemExistente) {
                // Se o item existe, atualiza a quantidade
                $novaQuantidade = $itemExistente['QUANTIDADE'] + $quantidade;
                $sql = "UPDATE Carrinhos SET QUANTIDADE = ? WHERE ID = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ii", $novaQuantidade, $itemExistente['ID']);
            } else {
                // Se o item não existe, insere um novo
                $sql = "INSERT INTO Carrinhos (ID_USUARIO, ID_PRODUTO, QUANTIDADE) VALUES (?, ?, ?)";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("iii", $idUsuario, $idProduto, $quantidade);
            }

            $sucesso = $stmt->execute();
            $stmt->close();
            $conexao->close();
            return $sucesso;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza a quantidade de um item específico no carrinho.
     * @param int $idItemCarrinho ID do item na tabela Carrinhos.
     * @param int $novaQuantidade A nova quantidade.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizarQuantidade($idItemCarrinho, $novaQuantidade) {
        try {
            $conexao = $this->conectar();
            $sql = "UPDATE Carrinhos SET QUANTIDADE = ? WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ii", $novaQuantidade, $idItemCarrinho);
            $sucesso = $stmt->execute();
            $stmt->close();
            $conexao->close();
            return $sucesso;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um item do carrinho pelo ID.
     * @param int $id O ID do item do carrinho a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluir($id) {
        try {
            $conexao = $this->conectar();
            $sql = "DELETE FROM Carrinhos WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id);
            $sucesso = $stmt->execute();
            $stmt->close();
            $conexao->close();
            return $sucesso;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os itens do carrinho de um usuário.
     * @param int $id_usuario O ID do usuário.
     * @return array Um array de arrays associativos, onde cada sub-array representa um item do carrinho.
     */
    public function listarItensCarrinho($id_usuario) {
        $itensCarrinho = [];
        try {
            $conexao = $this->conectar();
            // JOIN com a tabela Produtos para obter detalhes do produto
            $sql = "SELECT c.ID AS item_carrinho_id, p.ID AS produto_id, p.DESCRICAO, p.VALOR, c.QUANTIDADE 
                    FROM Carrinhos c 
                    JOIN Produtos p ON c.ID_PRODUTO = p.ID 
                    WHERE c.ID_USUARIO = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($item = $resultado->fetch_assoc()) {
                $itensCarrinho[] = $item;
            }
            $stmt->close();
            $conexao->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $itensCarrinho;
    }
}

?>
