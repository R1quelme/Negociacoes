<?php
require_once 'conexoes/conexao.php';
require_once 'conexoes/login.php';

if (is_logado()) {
    header("Location: index.php");
    exit;
}

function formulario($msg, $nome = "")
{
    echo '
    <form class="form-signin" action="user-login.php" method="post">
      <img class="mb-4" src="assets1/pdividas.png" alt="" width="92" height="72">

      <h1 class="h3 mb-3 font-weight-normal">Faça login</h1>

      <label for="usuario" class="sr-only">Usuário</label>
      <input type="text" name="usuario" id="usuario" class="form-control" value="' . $nome . '" placeholder="Usuário" required autofocus>

      <label for="senha" class="sr-only">Senha</label>
      <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
      ' . $msg . ' 
      <div class="checkbox mb-3">
        <label>
            <div class="bottom-text">Não tem uma conta? <a href="#" data-toggle="modal" style="color: black" data-target="#criarCadastro">Cadastre-se</a></div>
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      <p class="mt-5 mb-3 text-muted">&copy; Matheus Riquelme - 2020</p>
    </form>';
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets1/style7.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Dividas</title>
</head>

<body class="text-center">
    <?php
    $nome = ($_POST['usuario']) ?? null;
    $senha = $_POST['senha'] ?? null;

    // echo "<pre>";
    // print_r($_POST);

    if (is_null($nome) || is_null($senha)) {
        formulario('');
    } else {
        $q = "SELECT id_cad, cpf, nome, senha, tipo_negocio, pessoa FROM cadastros WHERE nome = '$nome'";
        $busca = $conexao->query($q);
        if (!$busca) {
            echo "Falha ao acessar o banco";
        } else {
            if ($busca->num_rows > 0) {
                $registro = $busca->fetch_object();
                // echo "<pre>";
                // print_r($registro);
                // exit;
                if (testarHash($senha, $registro->senha)) {
                    header("location: index.php");
                    $_SESSION['nome_dividas'] = $registro->nome;
                    $_SESSION['pessoa_dividas'] = $registro->pessoa;
                    $_SESSION['id_cad_dividas'] = $registro->id_cad;
                } else {
                    formulario(msg_erro('Senha Inválida'));
                }
            } else {
                echo "Falha ao buscar";
            }
        }
    }
    //print_r($_SESSION);
    ?>

    <!-- Modal -->
    <div align="left">
        <div class="modal fade" id="criarCadastro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Criar cadastro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form_cadastro" onsubmit="return salvarCadastro(event)">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="nome_log">Nome</label>
                                            <input type="text" name="nome_log" id="nome_log" class="form-control" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="pessoa">Pessoa</label>
                                            <select name="pessoa" id="pessoa" class="form-control" onchange="mascara()" required>
                                                <option value="PF">PF</option>
                                                <option value="PJ">PJ</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="cpf">CPF ou CNPJ</label>
                                            <input ontype="number" name="cpf" id="cpf" class="form-control" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipo_negocio">Tipo de negocio</label>
                                            <input type="text" name="tipo_negocio" id="tipo_negocio" class="form-control" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="senha_log">Senha</label>
                                            <input type="password" name="senha_log" id="senha_log" class="form-control" placeholder="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="senha2">Confirmar senha</label>
                                            <input type="password" name="senha2" id="senha2" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary">Criar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
    <script src="tata-master/dist/tata.js"></script>
</body>

<script type="text/javascript">
    function mascara() {
        if ($('#pessoa').val() === "PJ") {
            $("#cpf").mask("00.000.000/0000-00", {
                reverse: true
            })
        } else if ($('#pessoa').val() === "PF") {
            $("#cpf").mask("000.000.000-00", {
                reverse: true
            })
        }
    }
    mascara()
</script>

<script>
    function alertaMensagem(texto, sucesso = true) {
        if (sucesso) {
            tata.success(texto, '')
        } else {
            tata.error(texto, '')
        }
    }

    function salvarCadastro(event) {
        event.preventDefault()
        if ($("#senha_log").val() === $("#senha2").val()) {
            $.ajax({
                url: "ajax/criarCadastro.php",
                method: "POST",
                data: {
                    nome: $("#nome_log").val(),
                    pessoa: $("#pessoa").val(),
                    cpf: $("#cpf").val(),
                    tipo_negocio: $("#tipo_negocio").val(),
                    senha: $("#senha_log").val()
                },
                success: function(dados) {
                    dados = JSON.parse(dados)
                    if (dados.status == "sucesso") {
                        $('#criarCadastro').modal('hide')
                        alertaMensagem('Cadastro realizado com sucesso')
                        $('.modal-backdrop').remove();
                    } else {
                        alertaMensagem('Erro ao cadastrar, favor contatar o suporte', false)
                    }
                },
                error: function() {
                    alertaMensagem('Erro ao cadastrar, favor contatar o suporte aha', false)
                }

            })
        } else {
            alertaMensagem('Erro ao cadastrar, suas senhas nao coincidem', false)
        }
    }

    // function modalGerarDivida(id_cad){
    //     <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    //         <div class="modal-dialog" role="document">
    //             <div class="modal-content">
    //             <div class="modal-header">
    //                 <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
    //                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    //                 <span aria-hidden="true">&times;</span>
    //                 </button>
    //             </div>
    //             <div class="modal-body">
    //                 <label for="tipo_negocio">Tipo de negocio</label>
    //                 <input type="text" name="tipo_negocio" id="tipo_negocio" class="form-control" placeholder="" required></input>
    //             </div>
    //             <div>
    //             </div>
    //             <div class="modal-footer">
    //                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    //                 <button type="button" class="btn btn-primary">Understood</button>
    //             </div>
    //             </div>
    //         </div>
    //     </div>
    // } 
</script>