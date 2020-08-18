<?php
require_once '../conexoes/conexao.php';
require_once '../conexoes/login.php';

$nome = $_POST['nome'];
$cpf= $_POST['cpf'];
$pessoa = $_POST['pessoa'];
$tipo_negocio= $_POST['tipo_negocio'];
$senha = $_POST['senha'];

$hash = gerarHash($senha);

if($q = "SELECT id_cad FROM cadastros WHERE nome = '$nome'"){
    $busca = $conexao->query($q);
    if(!$busca){
        echo "Falha ao acessar o banco";
    } else{
        if($busca->num_rows > 0){
            $message = [];
            $message['status'] = 'ERRO ao cadastrar, favor tente novamente:';
            echo json_encode($message);
            die;
        } else{
            $q = "INSERT 
            INTO 
            `dividas`.`cadastros`
                (`nome`, `cpf`, `Pessoa`, `tipo_negocio`, `senha`)
            VALUES 
                ('$nome', '$cpf', '$pessoa', '$tipo_negocio', '$hash');
            ";
        }
    }
}

$res = mysqli_query($conexao, $q);
$message = [];
if (!$res) {
    $message['status'] = 'ERRO ao cadastrar, favor tente novamente:' . mysqli_error($conexao);
    // echo
    echo json_encode($message);
} else {
    $message['status'] = "sucesso";
    echo json_encode($message);
}