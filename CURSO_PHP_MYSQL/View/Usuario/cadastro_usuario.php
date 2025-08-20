<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página de formulário de cadastro de usuário.
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
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Cadastre-se</h2>
        <form action="" method="post" class="space-y-4">
            <div>
                <label for="nome" class="block text-gray-700 text-sm font-semibold mb-2">Nome Completo:</label>
                <input type="text" id="nome" name="nome" placeholder="Seu nome completo..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="Seu melhor e-mail..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="senha" class="block text-gray-700 text-sm font-semibold mb-2">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="endereco" class="block text-gray-700 text-sm font-semibold mb-2">Endereço:</label>
                <input type="text" id="endereco" name="endereco" placeholder="Seu endereço completo..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                <button type="submit" name="cadastrar"
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Cadastrar
                </button>
                <a href="/CURSO_PHP_MYSQL/" class="w-full text-center bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Voltar ao Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
    $path = $_SERVER['DOCUMENT_ROOT'].'/CURSO_PHP_MYSQL';
    include_once($path.'/Controller/usuario_controller.php');

    if(isset($_POST['cadastrar'])){
    $usuarioController = new UsuarioController();
    $objUsuario = new Usuario();
    $objUsuario->setNome($_POST['nome']);
    $objUsuario->setSenha($_POST['senha']);
    $objUsuario->setEmail($_POST['email']);
    $objUsuario->setEndereco($_POST['endereco']);
    $usuarioController->autenticar($objUsuario->getEmail(), $objUsuario->getSenha());

    $resposta = $usuarioController->cadastrarUsuario($objUsuario->getNome(), $objUsuario->getEmail(), $objUsuario->getSenha(), $objUsuario->getEndereco());

    if($resposta == "Sucesso"){
        header('Location: https://localhost/CURSO_PHP_MYSQL/View/Produto/listagem_produtos.php');
    }else{
        echo $resposta;
    }
    }
?>
