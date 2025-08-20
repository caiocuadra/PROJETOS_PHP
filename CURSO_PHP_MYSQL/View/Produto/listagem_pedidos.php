<?php
session_start();

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Controller/PedidoController.php';

$pedidos = [];
$mensagem = '';
$usuario_id = null;

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php'); // Redireciona para o login se não estiver logado
    exit();
}
$usuario_id = $_SESSION['usuario_id'];

$pedidoController = new PedidoController();
// Para usuários normais, liste apenas seus próprios pedidos
// Para admins, você pode remover o parâmetro $usuario_id ou ter uma lógica de permissão
$pedidos = $pedidoController->listarPedidos($usuario_id);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - ECOMMERCE SENAC</title>
    <!--
        Autor: [Seu Nome Completo, ex: Caio Henrique Mota Cuadra]
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página para exibir a listagem de pedidos do usuário.
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
                <ul class="flex space-x-4 items-center">
                    <li><a href="index.php" class="hover:text-blue-600 font-semibold">Início</a></li>
                    <li><a href="listagem_produtos.php" class="hover:text-blue-600 font-semibold">Produtos</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Categorias</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Contato</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="editar_usuario.php" class="hover:text-blue-600 font-semibold bg-gray-200 py-1 px-3 rounded-md">Editar Perfil</a></li>
                        <li><a href="logout.php" class="hover:text-red-600 font-semibold">Sair</a></li>
                    <?php else: ?>
                        <li><a href="index.php" class="hover:text-blue-600 font-semibold">Login</a></li>
                    <?php endif; ?>
                    <li><a href="carrinho.php" class="hover:text-blue-600 font-semibold">Carrinho</a></li>
                    <li><a href="listagem_pedidos.php" class="text-blue-600 font-semibold">Meus Pedidos</a></li> <!-- Link ativo -->
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto bg-white p-8 rounded-lg shadow-xl flex-grow">
        <h1 class="text-3xl font-bold text-center mb-8">Meus Pedidos</h1>

        <?php if (!empty($mensagem)): ?>
            <p class="text-center mb-4 <?php echo (strpos($mensagem, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($pedidos)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">ID Pedido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data do Pedido</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($pedido['ID']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($pedido['NOME_USUARIO']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['DATA_PEDIDO']))); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">R$ <?php echo number_format($pedido['VALOR_TOTAL'], 2, ',', '.'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($pedido['STATUS']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600 text-lg">Você não realizou nenhum pedido ainda.</p>
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
