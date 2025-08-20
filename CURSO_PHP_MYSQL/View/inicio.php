<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início - ECOMMERCE SENAC</title>
    <!--
        Autor: Caio Henrique Mota Cuadra
        Data: 20/08/2025
        Versão: 1.0
        Descrição: Página inicial básica para um projeto de e-commerce.
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
<body class="bg-gray-100 text-gray-800">

    <!-- Navegação (Header) -->
    <header class="bg-white shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-blue-600">SENAC Shop</a>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Início</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Produtos</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Categorias</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Contato</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Login</a></li>
                    <li><a href="#" class="hover:text-blue-600 font-semibold">Carrinho</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Seção de Destaque (Hero Section) -->
    <section class="bg-blue-600 text-white py-20 text-center">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Bem-vindo à SENAC Shop!</h1>
            <p class="text-xl md:text-2xl mb-8">Encontre os melhores produtos com os melhores preços.</p>
            <a href="#" class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold text-lg hover:bg-gray-200 transition duration-300">
                Explorar Produtos
            </a>
        </div>
    </section>

    <!-- Seção de Produtos em Destaque -->
    <section class="container mx-auto py-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Produtos em Destaque</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Cartão de Produto Exemplo 1 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Produto+1" alt="Produto 1" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-semibold mb-2">Nome do Produto 1</h3>
                <p class="text-gray-600 mb-4">Breve descrição do produto em destaque.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-blue-600">R$ 99,99</span>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>

            <!-- Cartão de Produto Exemplo 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Produto+2" alt="Produto 2" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-semibold mb-2">Nome do Produto 2</h3>
                <p class="text-gray-600 mb-4">Breve descrição do produto em destaque.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-blue-600">R$ 149,90</span>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>

            <!-- Cartão de Produto Exemplo 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Produto+3" alt="Produto 3" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-semibold mb-2">Nome do Produto 3</h3>
                <p class="text-gray-600 mb-4">Breve descrição do produto em destaque.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-blue-600">R$ 75,00</span>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>

            <!-- Cartão de Produto Exemplo 4 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                <img src="https://placehold.co/400x300/e2e8f0/64748b?text=Produto+4" alt="Produto 4" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-semibold mb-2">Nome do Produto 4</h3>
                <p class="text-gray-600 mb-4">Breve descrição do produto em destaque.</p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-blue-600">R$ 199,99</span>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                        Adicionar ao Carrinho
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé (Footer) -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 SENAC Shop. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
