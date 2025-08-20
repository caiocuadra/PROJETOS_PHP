<?php
session_start();
// Opcional: Redirecionar se o usuário não estiver logado ou não for admin
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: index.php');
//     exit();
// }

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Controller/ProdutoController.php';

$produto = null;
$mensagem = '';
$produto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($produto_id > 0) {
    $produtoController = new ProdutoController();
    $produto = $produtoController->getProduto($produto_id);

    if (!$produto) {
        $mensagem = "Produto não encontrado!";
    }
} else {
    $mensagem = "ID do produto não fornecido.";
}

if (isset($_POST['editar_produto']) && $produto_id > 0) {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];

    $produtoController = new ProdutoController();
    if ($produtoController->editarProduto($produto_id, $descricao, $valor, $categoria, $quantidade)) {
        $mensagem = "Produto atualizado com sucesso!";
        // Atualiza os dados na variável $produto para refletir as mudanças no formulário
        $produto = $produtoController->getProduto($produto_id);
    } else {
        $mensagem = "Erro ao atualizar o produto. Verifique os dados.";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página para edição de um produto.
    -->
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:text-blue-600 font-semibold">Início</a></li>
                    <li><a href="listagem_produtos.php" class="text-blue-600 font-semibold">Produtos</a></li> <!-- Link ativo -->
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Categorias</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Contato</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="editar_usuario.php" class="hover:text-blue-600 font-semibold bg-gray-200 py-1 px-3 rounded-md">Editar Perfil</a></li>
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
            <h1 class="text-3xl font-bold text-center mb-8">Editar Produto</h1>

            <?php if (!empty($mensagem)): ?>
                <p class="text-center mb-4 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $mensagem; ?>
                </p>
            <?php endif; ?>

            <?php if ($produto): ?>
            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="descricao" class="block text-gray-700 text-sm font-semibold mb-2">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Nome ou descrição do produto..."
                           value="<?php echo htmlspecialchars($produto['DESCRICAO'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="valor" class="block text-gray-700 text-sm font-semibold mb-2">Valor (R$):</label>
                    <input type="number" step="0.01" id="valor" name="valor" placeholder="Ex: 99.99"
                           value="<?php echo htmlspecialchars($produto['VALOR'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="categoria" class="block text-gray-700 text-sm font-semibold mb-2">Categoria:</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Ex: Eletrônicos, Roupas, Livros..."
                           value="<?php echo htmlspecialchars($produto['CATEGORIA'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="quantidade" class="block text-gray-700 text-sm font-semibold mb-2">Quantidade em Estoque:</label>
                    <input type="number" id="quantidade" name="quantidade" placeholder="Ex: 100"
                           value="<?php echo htmlspecialchars($produto['QUANTIDADE'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                    <button type="submit" name="editar_produto"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Salvar Alterações
                    </button>
                    <a href="listagem_produtos.php"
                       class="w-full text-center bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Voltar para Listagem
                    </a>
                </div>
            </form>
            <?php else: ?>
                <p class="text-center text-red-600 text-lg"><?php echo $mensagem; ?></p>
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
