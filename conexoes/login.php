<?php 
session_start();

if(!isset($_SESSION['nome_dividas'])){
    $_SESSION['nome_dividas'] = "";
    $_SESSION['pessoa_dividas'] = "";
    $_SESSION['id_cad_dividas'] = "";
}

function logout(){
    unset($_SESSION['nome_dividas']);
    unset($_SESSION['pessoa_dividas']); 
    unset($_SESSION['id_cad_dividas']);
}

function is_logado(){
    if(empty($_SESSION['nome_dividas'])){
        return false;
    } else{
        return true;
    }
}

function is_admin(){
    $tipo = $_SESSION['pessoa_dividas'] ?? null;
    if(is_null($tipo)){
        return false;
    } else{
        if($tipo == 'PJ'){
            return true;
        } else{
            return false;
        }
    }
}

function cripto($senha){
    $cripto = '';
    for($pos = 0; $pos < strlen($senha); $pos++){
        $letra = ord($senha[$pos]) + 1;
        $cripto .=chr($letra);
    } 
    return $cripto;
}

function gerarHash($senha){
    $txt = cripto($senha);
    $hash = password_hash($txt, PASSWORD_DEFAULT);
    return $hash;
}

function testarhash($senha, $hash){
    $ok = password_verify(cripto($senha), $hash);
    return $ok;
}

function msg_aviso($m){
    $resp = "<div class='aviso'><i class='material-icons'>info</i> $m</div>";
    return $resp;
}

function msg_erro($m){
    $resp = "<div class='erro'><i class='material-icons'>error</i> $m</div>";
    return $resp;
}
