<?php
//config credenciais
$server = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'salas';

//conexao com banco de dados
$conn = new mysqli($server, $usuario, $senha, $banco);

//verifica a conexao
if($conn->connect_error){
    die("Falha ao se comunicar com o banco de dados:".$conn->connect_error);
}

?>