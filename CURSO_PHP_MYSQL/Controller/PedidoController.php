<?php

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Model/Pedido.php';

class PedidoController {

    /**
     * Finaliza a compra de um usuário, criando um pedido e movendo os itens do carrinho.
     * @param int $idUsuario O ID do usuário.
     * @return bool True se a compra foi finalizada com sucesso, false caso contrário.
     */
    public function finalizarCompra($idUsuario) {
        $objPedido = new Pedido();
        $pedidoId = $objPedido->finalizarCompra($idUsuario);
        return $pedidoId !== false;
    }

    /**
     * Lista os pedidos. Pode listar todos (para admin) ou de um usuário específico.
     * @param int|null $idUsuario ID do usuário (opcional).
     * @return array Um array de pedidos.
     */
    public function listarPedidos($idUsuario = null) {
        $objPedido = new Pedido();
        return $objPedido->listarPedidos($idUsuario);
    }
    
    // Métodos para edição e exclusão de pedidos podem ser adicionados aqui
    // Ex: public function atualizarStatusPedido($pedidoId, $novoStatus) { ... }
    // Ex: public function excluirPedido($pedidoId) { ... }
}

?>
