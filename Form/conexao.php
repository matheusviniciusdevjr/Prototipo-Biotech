<?php

date_default_timezone_set('America/Sao_Paulo'); 

$servidor = "localhost";
$usuario = "root";
$senha = "";      
$database = "biotech"; 


$conn = new mysqli($servidor, $usuario, $senha, $database);


if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


$conn->query("SET time_zone = '-03:00'"); 

?>