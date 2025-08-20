<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Produtos - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página para exibir a listagem de produtos com opções de edição e exclusão.
    -->
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 p-4">

    <!-- Navegação (Header) -->
    <header class="bg-white shadow-md p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-600">SENAC Shop</a>
            <nav>
                <ul class="flex space-x-4 items-center">
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

    <main class="container mx-auto bg-white p-8 rounded-lg shadow-xl">
        <h1 class="text-3xl font-bold text-center mb-8">Nossos Produtos</h1>

        <?php
        $path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
        require_once $path . '/Controller/produto_controller.php';

        $produtoController = new ProdutoController();
        $mensagem_produto = '';

        // Lógica de exclusão de produto
        if (isset($_GET['excluir_produto_id'])) {
            $idExcluir = (int)$_GET['excluir_produto_id'];
            if ($produtoController->excluirProduto($idExcluir)) {
                $mensagem_produto = "Produto excluído com sucesso!";
            } else {
                $mensagem_produto = "Erro ao excluir produto.";
            }
        }
        
        // Lógica para adicionar ao carrinho (se houver formulário submit)
        if (isset($_POST['adicionar_carrinho']) && isset($_SESSION['usuario_id'])) {
            $idProduto = (int)$_POST['produto_id'];
            $quantidade = (int)$_POST['quantidade'] > 0 ? (int)$_POST['quantidade'] : 1; // Garante qte mínima de 1
            $idUsuario = $_SESSION['usuario_id'];

            if ($produtoController->adicionarProdutoAoCarrinho($idUsuario, $idProduto, $quantidade)) {
                $mensagem_produto = "Produto adicionado ao carrinho!";
            } else {
                $mensagem_produto = "Erro ao adicionar produto ao carrinho.";
            }
        }


        $produtos = $produtoController->listarProdutos();
        ?>

        <?php if (!empty($mensagem_produto)): ?>
            <p class="text-center mb-4 <?php echo (strpos($mensagem_produto, 'sucesso') !== false || strpos($mensagem_produto, 'adicionado') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $mensagem_produto; ?>
            </p>
        <?php endif; ?>

        <!-- Botão para cadastrar novo produto -->
        <div class="mb-8 text-right">
            <a href="cadastrar_produto.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-300 font-semibold">
                Cadastrar Novo Produto
            </a>
        </div>

        <?php if (!empty($produtos)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($produtos as $produto): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($produto['ID']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($produto['DESCRICAO']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ <?php echo number_format($produto['VALOR'], 2, ',', '.'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($produto['CATEGORIA']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($produto['QUANTIDADE']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <a href="editar_produto.php?id=<?php echo htmlspecialchars($produto['ID']); ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                    <a href="listagem_produtos.php?excluir_produto_id=<?php echo htmlspecialchars($produto['ID']); ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                                    <?php if (isset($_SESSION['usuario_id'])): ?>
                                        <form action="listagem_produtos.php" method="post" class="inline-block ml-4">
                                            <input type="hidden" name="produto_id" value="<?php echo htmlspecialchars($produto['ID']); ?>">
                                            <input type="hidden" name="quantidade" value="1"> <!-- Pode ser um campo para o usuário inserir a quantidade -->
                                            <button type="submit" name="adicionar_carrinho" class="bg-green-500 text-white px-3 py-1 rounded-md text-xs hover:bg-green-600 transition duration-300">
                                                Add Carrinho
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600 text-lg">Nenhum produto cadastrado ainda.</p>
        <?php endif; ?>
    </main>

    <!-- Rodapé (Footer) -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 SENAC Shop. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
