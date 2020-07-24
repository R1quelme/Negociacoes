<?php 

$conexao = new mysqli("localhost", "Riquelme_admin", "12345678", "dividas");

if($conexao->connect_errno) {
    echo "<p>Encontrei um erro $conexao->errno --> 
    $conexao->connect_error</p>";
    die();
}

// echo "<pre>";
// print_r($conexao);

$conexao->query("SET NAMES 'utf-8'");
$conexao->query("SET character_set_connection=utf8");
$conexao->query("SET character_set_client=utf8");
$conexao->query("SET character_set_results=utf8");
?>