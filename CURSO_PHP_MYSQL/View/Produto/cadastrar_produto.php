<?php
session_start();
// Opcional: Redirecionar se o usuário não estiver logado ou não for admin
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: index.php');
//     exit();
// }

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Controller/produto_controller.php';

$mensagem = '';
if (isset($_POST['cadastrar_produto'])) {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];

    $produtoController = new ProdutoController();
    if ($produtoController->cadastrarProduto($descricao, $valor, $categoria, $quantidade)) {
        $mensagem = "Produto cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar produto. Verifique os dados.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página de formulário para cadastrar um novo produto.
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


    <main class="container mx-auto bg-white p-8 rounded-lg shadow-xl flex-grow flex items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-8">Cadastrar Novo Produto</h1>

            <?php if (!empty($mensagem)): ?>
                <p class="text-center mb-4 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $mensagem; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="descricao" class="block text-gray-700 text-sm font-semibold mb-2">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Nome ou descrição do produto..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="valor" class="block text-gray-700 text-sm font-semibold mb-2">Valor (R$):</label>
                    <input type="number" step="0.01" id="valor" name="valor" placeholder="Ex: 99.99"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="categoria" class="block text-gray-700 text-sm font-semibold mb-2">Categoria:</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Ex: Eletrônicos, Roupas, Livros..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="quantidade" class="block text-gray-700 text-sm font-semibold mb-2">Quantidade em Estoque:</label>
                    <input type="number" id="quantidade" name="quantidade" placeholder="Ex: 100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                    <button type="submit" name="cadastrar_produto"
                            class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Cadastrar Produto
                    </button>
                    <a href="listagem_produtos.php"
                       class="w-full text-center bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Voltar para Listagem
                    </a>
                </div>
            </form>
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