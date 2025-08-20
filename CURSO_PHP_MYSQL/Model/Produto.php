<?php
/*
 * Autor: Caio Henrique Mota Cuadra
 * Data: 20/08/2025
 */
$path = $_SERVER['DOCUMENT_ROOT'].'/CURSO_PHP_MYSQL';
include_once($path.'/Conexao.php');

class Produto {
    private $id;
    private $descricao;
    private $valor;
    private $categoria;
    private $quantidade;

    // Construtor
    public function __construct($id = null, $descricao = null, $valor = null, $categoria = null, $quantidade = null) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->valor = $valor;
        $this->categoria = $categoria;
        $this->quantidade = $quantidade;
    }

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getDescricao() { return $this->descricao; }
    public function getValor() { return $this->valor; }
    public function getCategoria() { return $this->categoria; }
    public function getQuantidade() { return $this->quantidade; }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setValor($valor) { $this->valor = $valor; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
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
     * Cadastra um novo produto no banco de dados.
     * @return bool True se o cadastro for bem-sucedido, false caso contrário.
     */
    public function cadastrar() {
        try {
            $conexao = $this->conectar();
            $sql = "INSERT INTO produtos (DESCRICAO, VALOR, CATEGORIA, QUANTIDADE) VALUES (?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            // "sdss" para string (descricao), double (valor), string (categoria), string (quantidade - embora seja numérico, se for TEXT no DB)
            // Se QUANTIDADE for INT/DOUBLE no DB, use "sdds" (string, double, double, string) ou "sdsi"
            $sucesso = $stmt->execute(array($this->descricao, $this->valor, $this->categoria, $this->quantidade));
            $stmt->close();
            $conexao->close();
            return $sucesso;
        } catch (Exception $e) {
            error_log($e->getMessage()); // Loga o erro
            return false;
        }
    }

    /**
     * Lista todos os produtos do banco de dados.
     * @return array Um array de arrays associativos, onde cada sub-array representa um produto.
     */
    public function listar() {
        $arrayProdutos = [];
        try {
            $conexao = $this->conectar();
            $sql = "SELECT * FROM Produtos";
            $resultado = $conexao->query($sql);

            if ($resultado->num_rows > 0) {
                while ($produto = $resultado->fetch_assoc()) {
                    $arrayProdutos[] = $produto;
                }
            }
            $conexao->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $arrayProdutos;
    }

    /**
     * Busca um produto pelo ID no banco de dados.
     * @param int $id O ID do produto a ser buscado.
     * @return array|null Um array associativo com os dados do produto ou null se não encontrado.
     */
    public function getProdutoById($id) {
        $produto = null;
        try {
            $conexao = $this->conectar();
            $sql = "SELECT * FROM Produtos WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $produto = $resultado->fetch_assoc();
            $stmt->close();
            $conexao->close();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $produto;
    }

    /**
     * Edita as informações de um produto no banco de dados.
     * @param int $id O ID do produto a ser editado.
     * @param string $descricao A nova descrição do produto.
     * @param float $valor O novo valor do produto.
     * @param string $categoria A nova categoria do produto.
     * @param int $quantidade A nova quantidade disponível do produto.
     * @return bool True se a edição for bem-sucedida, false caso contrário.
     */
    public function editar($id, $descricao, $valor, $categoria, $quantidade) {
        try {
            $conexao = $this->conectar();
            $sql = "UPDATE Produtos SET DESCRICAO = ?, VALOR = ?, CATEGORIA = ?, QUANTIDADE = ? WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sdsii", $descricao, $valor, $categoria, $quantidade, $id); // sdsii: string, double, string, int, int
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
     * Exclui um produto do banco de dados.
     * @param int $id O ID do produto a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluir($id) {
        try {
            $conexao = $this->conectar();
            $sql = "DELETE FROM Produtos WHERE ID = ?";
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
}

?>