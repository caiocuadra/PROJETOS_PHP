<?php
session_start();

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Model/Carrinho.php';
require_once $path . '/Controller/produto_controller.php'; // Para usar o método de exclusão
require_once $path . '/Controller/PedidoController.php'; // Para finalizar compra

$itensCarrinho = [];
$mensagem = '';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php'); // Redireciona para o login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$objCarrinho = new Carrinho();
$produtoController = new ProdutoController(); // Instancia para operações de exclusão/atualização de carrinho
$pedidoController = new PedidoController(); // Instancia para finalizar compra

// Se um item foi enviado para exclusão
if (isset($_GET['excluir_item_id'])) {
    $idItemExcluir = (int)$_GET['excluir_item_id'];
    if ($produtoController->excluirProdutoDoCarrinho($idItemExcluir)) {
        $mensagem = "Item removido do carrinho com sucesso!";
    } else {
        $mensagem = "Erro ao remover o item do carrinho.";
    }
}

// Se a quantidade de um item foi atualizada
if (isset($_POST['atualizar_quantidade'])) {
    $idItemAtualizar = (int)$_POST['item_carrinho_id'];
    $novaQuantidade = (int)$_POST['nova_quantidade'];
    if ($novaQuantidade <= 0) {
        $mensagem = "A quantidade deve ser um número positivo. Use o botão Remover para zerar.";
    } elseif ($produtoController->atualizarQuantidadeCarrinho($idItemAtualizar, $novaQuantidade)) {
        $mensagem = "Quantidade atualizada com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar quantidade.";
    }
}

// Se a compra foi finalizada
if (isset($_POST['finalizar_compra'])) {
    if ($pedidoController->finalizarCompra($usuario_id)) {
        $mensagem = "Compra finalizada com sucesso! Seu pedido foi gerado.";
        // Opcional: Redirecionar para uma página de confirmação de pedido
        // header('Location: confirmacao_pedido.php?pedido_id=' . $pedido_id_gerado);
        // exit();
    } else {
        $mensagem = "Erro ao finalizar a compra. Tente novamente.";
    }
}


// Lista os itens do carrinho do usuário logado (após qualquer operação)
$itensCarrinho = $objCarrinho->listarItensCarrinho($usuario_id);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - ECOMMERCE SENAC</title>
    <!--
        Autor: [Seu Nome Completo, ex: Caio Henrique Mota Cuadra]
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página para exibir e gerenciar os itens do carrinho do usuário.
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


    <main class="container mx-auto bg-white p-8 rounded-lg shadow-xl flex-grow">
        <h1 class="text-3xl font-bold text-center mb-8">Meu Carrinho</h1>

        <?php if (!empty($mensagem)): ?>
            <p class="text-center mb-4 <?php echo (strpos($mensagem, 'sucesso') !== false || strpos($mensagem, 'finalizada') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($itensCarrinho)): ?>
            <div class="overflow-x-auto mb-8">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Produto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Unitário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $totalCarrinho = 0;
                        foreach ($itensCarrinho as $item) {
                            $subtotal = $item['VALOR'] * $item['QUANTIDADE'];
                            $totalCarrinho += $subtotal;
                            echo '<tr class="hover:bg-gray-50">';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">' . htmlspecialchars($item['DESCRICAO']) . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">';
                            // Formulário para atualizar quantidade
                            echo '<form action="carrinho.php" method="post" class="inline-flex items-center">';
                            echo '<input type="hidden" name="item_carrinho_id" value="' . htmlspecialchars($item['item_carrinho_id']) . '">';
                            echo '<input type="number" name="nova_quantidade" value="' . htmlspecialchars($item['QUANTIDADE']) . '" min="1" class="w-20 px-2 py-1 border border-gray-300 rounded-md text-sm text-center focus:outline-none focus:ring-1 focus:ring-blue-500">';
                            echo '<button type="submit" name="atualizar_quantidade" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded-md text-xs hover:bg-blue-600 transition duration-300">Atualizar</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ ' . number_format($item['VALOR'], 2, ',', '.') . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                            echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">';
                            echo '<a href="carrinho.php?excluir_item_id=' . htmlspecialchars($item['item_carrinho_id']) . '" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm(\'Tem certeza que deseja remover este item do carrinho?\');">Remover</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-lg font-bold">Total:</td>
                            <td colspan="2" class="px-6 py-4 text-left text-lg font-bold text-blue-600">R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <form action="carrinho.php" method="post">
                    <button type="submit" name="finalizar_compra" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition duration-300 font-semibold">
                        Finalizar Compra
                    </button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600 text-lg">Seu carrinho está vazio.</p>
            <div class="mt-8 text-center">
                <a href="listagem_produtos.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-300 font-semibold">
                    Continuar Comprando
                </a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Rodapé (Footer) -->
    <footer class="bg-gray-800 text-white py-6 mt-12 w-full">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 SENAC Shop. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
