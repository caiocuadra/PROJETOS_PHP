<?php

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Model/Usuario.php'; // Inclui o arquivo do Model Usuario

class UsuarioController {

    /**
     * Tenta autenticar um usuário no sistema.
     * @param string $email O email do usuário.
     * @param string $senha A senha do usuário.
     * @return array|null Os dados do usuário se a autenticação for bem-sucedida, ou null caso contrário.
     */
    public function autenticar($email, $senha) {
        // Primeiro, validar o formato do email e senha
        if (!validaEmail($email) || !validaSenha($senha)) {
            return null; // Formato inválido
        }

        $objUsuario = new Usuario();
        $objUsuario->setEmail($email);
        $objUsuario->setSenha($senha); // Lembre-se que em produção, a senha deveria ser hash

        $usuarioLogado = $objUsuario->Login(); // Chama o método Login do Model

        return $usuarioLogado; // Retorna os dados do usuário ou null
    }

    /**
     * Cadastra um novo usuário.
     * @param string $nome Nome do usuário.
     * @param string $email Email do usuário.
     * @param string $senha Senha do usuário.
     * @param string $endereco Endereço do usuário.
     * @return bool True se o cadastro for bem-sucedido, false caso contrário.
     */
    public function cadastrarUsuario($nome, $email, $senha, $endereco) {
        // Validações antes de tentar cadastrar
        if (!validaNome($nome) || !validaEmail($email) || !validaSenha($senha)) {
            // Poderia retornar uma mensagem de erro específica aqui
            return false;
        }

        $objUsuario = new Usuario();
        $objUsuario->setNome($nome);
        $objUsuario->setEmail($email);
        $objUsuario->setSenha($senha); // Em um sistema real, use password_hash()
        $objUsuario->setEndereco($endereco);

        return $objUsuario->Cadastrar(); // Chama o método Cadastrar do Model
    }

    /**
     * Busca um usuário pelo ID para exibição na tela de edição.
     * @param int $id O ID do usuário a ser buscado.
     * @return array|null Um array associativo com os dados do usuário ou null se não encontrado.
     */
    public function getUsuario($id) {
        $objUsuario = new Usuario();
        return $objUsuario->getUsuarioById($id);
    }

    /**
     * Processa a edição de um usuário.
     * @param int $id O ID do usuário a ser editado.
     * @param string $nome O novo nome do usuário.
     * @param string $email O novo email do usuário.
     * @param string $senha A nova senha do usuário (já hash, se aplicável).
     * @param string $endereco O novo endereço do usuário.
     * @return bool True se a edição for bem-sucedida, false caso contrário.
     */
    public function editarUsuario($id, $nome, $email, $senha, $endereco) {
        // Validações antes de editar
        if (!validaNome($nome) || !validaEmail($email) || !validaSenha($senha)) {
            return false;
        }

        $objUsuario = new Usuario();
        $sucesso = $objUsuario->editar($id, $nome, $email, $senha, $endereco);
        return $sucesso;
    }
}

// --- Funções de validação de formato (movidas para cá) ---
// Idealmente, estas estariam em um arquivo de utilitários ou uma classe de validação.

function validaEmail($email){
    if (empty($email)) {
        // Para exibir a mensagem na view, você pode retornar a mensagem e exibi-la
        // return "O e-mail é obrigatório.";
        return false;
    } elseif (strlen($email) > 100) {
        return false;
    }
    // Adicione uma validação de formato de e-mail mais robusta
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validaNome($nome){
    if (empty($nome)) {
        return false;
    } elseif (strlen($nome) > 100) {
        return false;
    }
    return true;
}

function validaSenha($senha){
    if (empty($senha)) {
        return false;
    } elseif (strlen($senha) > 100) { // Pode adicionar requisitos de segurança aqui (mín. caracteres, etc.)
        return false;
    }
    return true;
}

?>
