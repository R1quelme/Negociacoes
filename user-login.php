<?php
require_once 'conexoes/conexao.php';
require_once 'conexoes/login.php';

if (is_logado()) {
    header("Location: index.php");
    exit;
}

function formulario($msg, $nome = "")
{
    echo '<br><form action="user-login.php" method="post">
                <div class="container">
                <div class="corpo">
                    <div class="row">
                        <div class="col-md-3 col-md-offset-0">
                            <div class="form-group">
                                <label><b>Usuário</b></label>
                                <input type="text" name="usuario" id="usuario" class="form-control" value="' . $nome . '">
                            </div>
                            <div class="form-group">
                                <label><b>Senha</b></label>
                                <input type="password" name="senha" id="senha" class="form-control">
                                <a href="" data-toggle="modal" data-target="#criarCadastro">Criar novo cadastro</a>
                            </div>
                            ' . $msg . ' 
                            <input type="submit" class="btn btn-success btn-block" value="Entrar">
                        </div>
                    </div>
                </div>
            </div>
            </form>';
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Dividas</title>
</head>

<body><br>
    <div class="col-md-11 offset-md-4">
        <div id="corpo" class="container">
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
        </div>
    </div>


    <!-- Modal -->
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
                                        <select name="pessoa" id="pessoa" class="form-control" required>
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
                            <button type="submit" class="btn btn-success">Criar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <hr>
    <footer class="container">
        <p>Matheus Riquelme &copy; 2020 Sistema Segunda Igreja Batista</p>
    </footer>


    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
    <script src="tata-master/dist/tata.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
</body>

<script type="text/javascript">
    if ($('#pessoa').val() == "PF") {
        $("#cpf").mask("000.000.000-00", {
            reverse: true
        })
    } else if($('#pessoa').val() == "PJ"){
        $("#cpf").mask("00.000.000/0000-00", {
            reverse: true
        })
    }


    //     $("#cpf").keydown(function(){
    //     try {
    //         $("#cpf").unmask();
    //     } catch (e) {}

    //     var tamanho = $("#cpf").val().length;

    //     if(tamanho < 11){
    //         $("#cpf").mask("000.000.000-00");
    //     } else {
    //         $("#cpf").mask("99.999.999/9999-99");
    //     }                   
    // }); 
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
                    cpf: $("#cpf").val(),
                    tipo_negocio: $("#tipo_negocio").val(),
                    senha: $("#senha_log").val(),
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
                    alertaMensagem('Erro ao cadastrar, favor contatar o suporte', false)
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