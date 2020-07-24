<?php
require_once '../conexoes/conexao.php';
require_once '../conexoes/login.php';

$id_dividas = $_POST['id_dividas'];

// echo "<pre>";
// print_r($id_dividas);
// die;

$message = [];

for ($i = 0; $i < count($id_dividas); $i++) {
    $q = "UPDATE `dividas`.`divida` 
    SET 
        `status` = 'Negociado'
    WHERE
        `id_dividas` = '$id_dividas[$i]';
    ";
    
    $res = mysqli_query($conexao, $q);

    if (!$res) {
        $message['status'] = 'ERRO ao negociar divida, tente novamente:' . mysqli_error($conexao);
        echo json_encode($message);
        exit;
    }
}
$message['status'] = "sucesso";
echo json_encode($message);
