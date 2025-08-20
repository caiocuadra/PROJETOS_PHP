<?php
session_start(); // Inicia a sessão no início do arquivo

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';

// Inclui os arquivos necessários no início do script
// Certifique-se que estes caminhos estão corretos em relação à raiz do seu projeto
require_once $path . '/Controller/usuario_controller.php';
require_once $path . '/Model/Usuario.php';

$usuarioController = new UsuarioController(); // Instancia o Controller
$mensagem_erro = ''; // Inicializa a variável para mensagens de erro

// Lógica para processar o formulário de login
if (isset($_POST['logar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Chama o método autenticar do Controller
    $usuario = $usuarioController->autenticar($email, $senha);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['ID']; // Armazena o ID do usuário na sessão
        header('Location: View/Produto/listagem_produtos.php'); // Redireciona para a listagem de produtos
        exit(); // Garante que o script pare após o redirecionamento
    } else {
        $mensagem_erro = "E-mail ou senha incorretos!";
    }
}

// Lógica para redirecionar ao formulário de cadastro
if (isset($_POST['cadastro'])) {
    // Ajuste o caminho conforme a localização real do seu arquivo cadastro.php
    // Exemplo: se cadastro.php estiver na raiz do projeto: header('Location: cadastro.php');
    // Exemplo: se cadastro_usuario.php estiver em View/Usuario/: header('Location: View/Usuario/cadastro_usuario.php');
    header('Location: cadastro.php'); // Use o caminho correto para sua página de cadastro
    exit();
}

// Importante: Nenhuma saída (HTML, espaços em branco) deve ocorrer antes deste ponto.
// Tudo o que está abaixo será a estrutura HTML da página, visível apenas se não houver redirecionamento.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECOMMERCE - ATIVIDADE - SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Essa é a página que contém o formulário de login.
    -->
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Definindo a fonte Inter globalmente */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acesse sua conta</h2>
        <form action="" method="post" class="space-y-4">
            <?php if (isset($mensagem_erro) && !empty($mensagem_erro)): ?>
                <p class="text-red-500 text-center mb-4"><?php echo $mensagem_erro; ?></p>
            <?php endif; ?>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="E-mail..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="senha" class="block text-gray-700 text-sm font-semibold mb-2">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Senha..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                <button type="submit" name="logar"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Entrar!
                </button>
                <button type="submit" name="cadastro" class="w-full text-center bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>
</body>
</html>