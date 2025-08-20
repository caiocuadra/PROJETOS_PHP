<?php
    Class Conexao {
        public function getConexao(){
            $host = 'localhost';
            $bd = 'ecommerce';
            $usuariobd = 'root';
            $senhabd = '';

            $conexao = new mysqli($host, $usuariobd, $senhabd, $bd);

            if(!$conexao) die ("Erro de conexão com localhost, o seguinte erro ocorreu -> ".mysqli_error( $conexao));

            return $conexao;
        }
    }
?>