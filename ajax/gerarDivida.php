<?php
require_once '../conexoes/conexao.php';
require_once '../conexoes/login.php';

$id_cad = $_POST['id_cad'];
$id_emissor = $_SESSION['id_cad_dividas'];
$tipo_divida = $_POST['tipo_divida'];
$valor = $_POST['valor'];

// echo "<pre>";
// print_r($id_cad);
// die;

$message = [];

for ($i = 0; $i < count($id_cad); $i++) {
    $q = "INSERT 
        INTO `dividas`.`divida`
            (`id_cad`, `id_emissor`, `tipo_divida`, `valor`)
        VALUES 
            ('$id_cad[$i]', '$id_emissor', '$tipo_divida', '$valor');
    ";
    $res = mysqli_query($conexao, $q);

    if (!$res) {
        $message['status'] = 'ERRO ao registrar divida, tente novamente:' . mysqli_error($conexao);
        echo json_encode($message);
        exit;
    }
}
$message['status'] = "sucesso";
echo json_encode($message);
