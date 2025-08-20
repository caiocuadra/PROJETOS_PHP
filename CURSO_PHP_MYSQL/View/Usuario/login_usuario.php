<div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acesse sua conta</h2>
        <form method="post" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">E-mail:</label>
                <input type="text" id="email" name="email" placeholder="E-mail..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="senha" class="block text-gray-700 text-sm font-semibold mb-2">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Senha..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 mt-6">
                <button type="submit" name="logar"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Entrar!
                </button>
                <button type="submit" name="cadastro"
                        class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition ease-in-out duration-150">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>