<?php
session_start(); // Inicia a sessão

require_once 'UsuarioController.php'; // Inclui o Controller do Usuário

$usuario = null;
$mensagem = '';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php'); // Redireciona para o login se não estiver logado
    exit();
}

$usuarioController = new UsuarioController();
$usuario_id = $_SESSION['usuario_id'];

// Carrega os dados do usuário para preencher o formulário
$usuario = $usuarioController->getUsuario($usuario_id);

if (!$usuario) {
    $mensagem = "Usuário não encontrado!";
}

// Processa a submissão do formulário de edição
if (isset($_POST['editar_usuario'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; // Em um sistema real, você faria um hash da senha aqui
    $endereco = $_POST['endereco'];

    if ($usuarioController->editarUsuario($usuario_id, $nome, $email, $senha, $endereco)) {
        $mensagem = "Dados atualizados com sucesso!";
        // Atualiza os dados na variável $usuario para refletir as mudanças no formulário
        $usuario = $usuarioController->getUsuario($usuario_id);
    } else {
        $mensagem = "Erro ao atualizar os dados. Tente novamente.";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página para edição do perfil do usuário.
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
<body class="bg-gray-100 text-gray-800 p-4 min-h-screen flex flex-col">

    <!-- Navegação (Header) -->
    <header class="bg-white shadow-md p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-600">SENAC Shop</a>
            <nav>
                <ul class="flex space-x-4 items-center">
                    <li><a href="index.php" class="hover:text-blue-600 font-semibold">Início</a></li>
                    <li><a href="listagem_produtos.php" class="hover:text-blue-600 font-semibold">Produtos</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Categorias</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Contato</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="editar_usuario.php" class="text-blue-600 font-semibold bg-gray-200 py-1 px-3 rounded-md">Editar Perfil</a></li> <!-- Link ativo -->
                        <li><a href="logout.php" class="hover:text-red-600 font-semibold">Sair</a></li>
                    <?php else: ?>
                        <li><a href="index.php" class="hover:text-blue-600 font-semibold">Login</a></li>
                    <?php endif; ?>
                    <li><a href="carrinho.php" class="hover:text-blue-600 font-semibold">Carrinho</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto bg-white p-8 rounded-lg shadow-xl flex-grow flex items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-8">Editar Meu Perfil</h1>

            <?php if (!empty($mensagem)): ?>
                <p class="text-center mb-4 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $mensagem; ?>
                </p>
            <?php endif; ?>

            <?php if ($usuario): ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="nome" class="block text-gray-700 text-sm font-semibold mb-2">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo..."
                           value="<?php echo htmlspecialchars($usuario['NOME'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="Seu melhor e-mail..."
                           value="<?php echo htmlspecialchars($usuario['EMAIL'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="senha" class="block text-gray-700 text-sm font-semibold mb-2">Nova Senha (deixe em branco para não alterar):</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma nova senha..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="endereco" class="block text-gray-700 text-sm font-semibold mb-2">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Seu endereço completo..."
                           value="<?php echo htmlspecialchars($usuario['ENDERECO'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                    <button type="submit" name="editar_usuario"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Salvar Alterações
                    </button>
                    <a href="listagem_produtos.php"
                       class="w-full text-center bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Voltar
                    </a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </main>

    <!-- Rodapé (Footer) -->
    <footer class="bg-gray-800 text-white py-6 mt-12 w-full">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 SENAC Shop. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
