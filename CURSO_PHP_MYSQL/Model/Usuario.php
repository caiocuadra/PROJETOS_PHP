<?php

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $endereco;

    // Construtor
    public function __construct($id = null, $nome = null, $email = null, $senha = null, $endereco = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->endereco = $endereco;
    }

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getEndereco() { return $this->endereco; }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = $senha; }
    public function setEndereco($endereco) { $this->endereco = $endereco; }

    /**
     * Tenta autenticar um usuário no banco de dados.
     * @return array|null Dados do usuário se autenticado com sucesso, ou null caso contrário.
     */
    public function Login() {
        $servidor = "localhost";
        $usuario_bd = "root";
        $senha_bd = "";
        $nome_banco = "ecommerce";

        $conexao = new mysqli($servidor, $usuario_bd, $senha_bd, $nome_banco);

        if ($conexao->connect_error) {
            // Em ambiente de produção, logar o erro em vez de die()
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        // Usar prepared statements para prevenir SQL Injection
        $sql = "SELECT ID, NOME, EMAIL, SENHA, ENDERECO FROM Usuarios WHERE EMAIL = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows >= 1) {
            $usuarioData = $resultado->fetch_assoc();
            // Em um sistema real, você usaria password_verify($this->senha, $usuarioData['SENHA'])
            if ($this->senha === $usuarioData['SENHA']) { // Comparação simples para fins de exemplo
                $conexao->close();
                return $usuarioData;
            }
        }
        $conexao->close();
        return null; // Autenticação falhou
    }

    /**
     * Cadastra um novo usuário no banco de dados.
     * @return bool True se o cadastro for bem-sucedido, false caso contrário.
     */
    public function Cadastrar() {
        $servidor = "localhost";
        $usuario_bd = "root";
        $senha_bd = "";
        $nome_banco = "ecommerce";

        $conexao = new mysqli($servidor, $usuario_bd, $senha_bd, $nome_banco);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        // Em um sistema real, você faria password_hash($this->senha, PASSWORD_DEFAULT) para segurança
        $sql = "INSERT INTO Usuarios (NOME, EMAIL, SENHA, ENDERECO) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssss", $this->nome, $this->email, $this->senha, $this->endereco);

        $sucesso = $stmt->execute();

        $stmt->close();
        $conexao->close();

        return $sucesso;
    }

    /**
     * Busca um usuário pelo ID no banco de dados.
     * @param int $id O ID do usuário a ser buscado.
     * @return array|null Um array associativo com os dados do usuário ou null se não encontrado.
     */
    public function getUsuarioById($id) {
        $servidor = "localhost";
        $usuario_bd = "root";
        $senha_bd = "";
        $nome_banco = "ecommerce";

        $conexao = new mysqli($servidor, $usuario_bd, $senha_bd, $nome_banco);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        $sql = "SELECT * FROM Usuarios WHERE ID = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        $stmt->close();
        $conexao->close();

        return $usuario;
    }

    /**
     * Edita as informações de um usuário no banco de dados.
     * @param int $id O ID do usuário a ser editado.
     * @param string $nome O novo nome do usuário.
     * @param string $email O novo email do usuário.
     * @param string $senha A nova senha do usuário (já hash, se aplicável).
     * @param string $endereco O novo endereço do usuário.
     * @return bool True se a edição for bem-sucedida, false caso contrário.
     */
    public function editar($id, $nome, $email, $senha, $endereco) {
        $servidor = "localhost";
        $usuario_bd = "root";
        $senha_bd = "";
        $nome_banco = "ecommerce";

        $conexao = new mysqli($servidor, $usuario_bd, $senha_bd, $nome_banco);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        $sql = "UPDATE Usuarios SET NOME = ?, EMAIL = ?, SENHA = ?, ENDERECO = ? WHERE ID = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $senha, $endereco, $id);

        $sucesso = $stmt->execute();

        $stmt->close();
        $conexao->close();

        return $sucesso;
    }
}

?>
