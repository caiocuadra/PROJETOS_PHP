<?php

$path = $_SERVER['DOCUMENT_ROOT'] . '/CURSO_PHP_MYSQL';
require_once $path . '/Model/Produto.php';
require_once $path . '/Model/Carrinho.php'; // Inclui o Model do Carrinho

class ProdutoController {

    /**
     * Cadastra um novo produto no sistema.
     * @param string $descricao Descrição do produto.
     * @param float $valor Valor do produto.
     * @param string $categoria Categoria do produto.
     * @param int $quantidade Quantidade disponível.
     * @return bool True se o cadastro for bem-sucedido, false caso contrário.
     */
    public function cadastrarProduto($descricao, $valor, $categoria, $quantidade) {
        // Validações básicas (pode ser expandido)
        if (empty($descricao) || empty($valor) || empty($categoria) || empty($quantidade)) {
            return false; // Campos obrigatórios
        }
        if (!is_numeric($valor) || $valor <= 0) {
            return false; // Valor inválido
        }
        if (!is_numeric($quantidade) || $quantidade < 0) {
            return false; // Quantidade inválida
        }

        $objProduto = new Produto();
        $objProduto->setDescricao($descricao);
        $objProduto->setValor($valor);
        $objProduto->setCategoria($categoria);
        $objProduto->setQuantidade($quantidade);

        return $objProduto->cadastrar();
    }

    /**
     * Lista todos os produtos.
     * @return array Um array de produtos.
     */
    public function listarProdutos() {
        $objProduto = new Produto();
        return $objProduto->listar();
    }

    /**
     * Busca um produto pelo ID para exibição na tela de edição.
     * @param int $id O ID do produto a ser buscado.
     * @return array|null Dados do produto ou null se não encontrado.
     */
    public function getProduto($id) {
        $objProduto = new Produto();
        return $objProduto->getProdutoById($id);
    }

    /**
     * Edita as informações de um produto.
     * @param int $id O ID do produto.
     * @param string $descricao Nova descrição.
     * @param float $valor Novo valor.
     * @param string $categoria Nova categoria.
     * @param int $quantidade Nova quantidade.
     * @return bool True se a edição for bem-sucedida, false caso contrário.
     */
    public function editarProduto($id, $descricao, $valor, $categoria, $quantidade) {
        // Validações básicas (pode ser expandido)
        if (empty($descricao) || empty($valor) || empty($categoria) || empty($quantidade)) {
            return false;
        }
        if (!is_numeric($valor) || $valor <= 0) {
            return false;
        }
        if (!is_numeric($quantidade) || $quantidade < 0) {
            return false;
        }

        $objProduto = new Produto();
        return $objProduto->editar($id, $descricao, $valor, $categoria, $quantidade);
    }

    /**
     * Exclui um produto do banco de dados.
     * @param int $id O ID do produto a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluirProduto($id) {
        $objProduto = new Produto();
        return $objProduto->excluir($id);
    }

    /**
     * Adiciona um produto ao carrinho do usuário logado.
     * @param int $idUsuario ID do usuário.
     * @param int $idProduto ID do produto a ser adicionado.
     * @param int $quantidade Quantidade do produto.
     * @return bool True se adicionado com sucesso, false caso contrário.
     */
    public function adicionarProdutoAoCarrinho($idUsuario, $idProduto, $quantidade) {
        if (!is_numeric($quantidade) || $quantidade <= 0) {
            return false; // Quantidade inválida
        }
        $objCarrinho = new Carrinho();
        // Você precisaria de um método no Model Carrinho para adicionar/atualizar
        // Por exemplo: $objCarrinho->adicionarOuAtualizar($idUsuario, $idProduto, $quantidade);
        // Para este exemplo, vamos chamar um método fictício.
        return $objCarrinho->adicionar($idUsuario, $idProduto, $quantidade);
    }

    /**
     * Exclui um produto específico do carrinho.
     * @param int $idItemCarrinho O ID do item na tabela CARRINHOS a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function excluirProdutoDoCarrinho($idItemCarrinho) {
        $objCarrinho = new Carrinho();
        $sucesso = $objCarrinho->excluir($idItemCarrinho);
        return $sucesso;
    }

    /**
     * Atualiza a quantidade de um item no carrinho.
     * @param int $idItemCarrinho ID do item do carrinho.
     * @param int $novaQuantidade A nova quantidade.
     * @return bool True se atualizado, false caso contrário.
     */
    public function atualizarQuantidadeCarrinho($idItemCarrinho, $novaQuantidade) {
        if (!is_numeric($novaQuantidade) || $novaQuantidade < 0) {
            return false; // Quantidade inválida
        }
        $objCarrinho = new Carrinho();
        // Assumindo que Carrinho.php terá um método para isso
        return $objCarrinho->atualizarQuantidade($idItemCarrinho, $novaQuantidade);
    }
}

?>