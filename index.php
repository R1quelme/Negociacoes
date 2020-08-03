<?php
require_once 'conexoes/conexao.php';
require_once 'conexoes/login.php';

if (!is_logado()) {
    header("location: user-login.php");
    die;
}
echo "<p class='pequeno'>";
if (empty($_SESSION['nome_dividas'])) {
    echo "<a href='user-login.php'>Entrar</a>";
} else {
    echo "Olá, " . $_SESSION['nome_dividas'] . " | ";
    echo "<a href='user-logout.php'>Sair</a>";
}
echo "</p>";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http -equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<style>
body{
    margin: 0;
    padding: 0;
    font-family: sans-serif;
    background: url(Fundo.png) no-repeat;
    background-size: cover;
}
</style>

<body id="body">
    <div class="table responsive">
        <div class="container">
            <table id="tabela_dividas"><br>
                <?php
                if (is_admin()) { ?>
                    <h2>Gerar dividas</h2>
                    <p><button type="button" class="btn btn-danger" onclick="abriModalDividas()">
                            Gerar Divida
                        </button></p>
                    <thead>
                        <tr>
                            <th scope="col" data-field="id_cad" data-visible="false"></th>
                            <th scope="col" data-field="divida" data-checkbox="true"></th>
                            <th scope="col" data-field="nome">Nome</th>
                            <th scope="col" data-field="cpf">CPF ou CNPJ</th>
                            <th scope="col" data-field="tipo_negocio">tipo de negocio</th>
                        </tr>
                    </thead>
                <?php } else { ?>
                    <h2>Dividas</h2>
                    <p><button type="button" class="btn btn-danger" onclick="abriModalDividas()">
                            Negociar dividas
                        </button></p>
                    <thead>
                        <tr>
                            <th scope="col" data-field="id_dividas" data-visible="false"></th>
                            <th scope="col" data-field="divida" data-checkbox="true"></th>
                            <th scope="col" data-field="nome">Nome</th>
                            <th scope="col" data-field="tipo_divida">Tipo de dividas</th>
                            <th scope="col" data-field="valor">Valor</th>
                            <th scope="col" data-field="status">Status</th>
                        </tr>
                    </thead>
                <?php
                }
                ?>

            </table>
        </div>
        <!-- <iframe src="./rodape.html"allowfullscreen style="height: 27%; width: 100%;"></iframe> -->
        <!-- para nao dar conflitos dos links fiz esse iframe para conseguir por o footer -->
    </div>
    <?php require_once 'footer-white.html' ?>


    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
    <script src="jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
    <script src="tata-master/dist/tata.js"></script>





    <?php
    if (is_admin()) {
    ?>
        <div class="modal fade" id="dividas-gerar" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Gerar divida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form_ke" onsubmit="return gerarDivida(event)">
                        <div class="modal-body">
                            <div class="form_group">
                                <label for="tipo_divida">Tipo da divida</label>
                                <input type="text" name="tipo_divida" id="tipo_divida" class="form-control" required>
                            </div><br>
                            <div class="form_group">
                                <label for="valor">Valor da divida</label>
                                <input type="number" name="valor" id="valor" class="form-control" required>
                            </div>
                            <hr>
                            <h5>Clientes a receber dívida</h5>
                            <div class="table responsive">
                                <div class="container">
                                    <table id="tabela_cliente">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="id">Id</th>
                                                <th scope="col" data-field="nome">Nome</th>
                                                <th scope="col" data-field="cpf">CPF</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Gerar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="modal fade" id="dividas-gerar" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Negociar divida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form onsubmit="return negociarDividas(event)">
                        <div class="modal-body">
                            <div class="form_group">
                                <label for="valorTotal_negociar">Valor total</label>
                                <input type="text" name="valorTotal_negociar" data-valor-divida="" id="valorTotal_negociar" class="form-control" readonly=“true” required />
                            </div><br>
                            <div class="form_group" id="vd">
                                <label for="valor_entrada">Valor da entrada</label>
                                <input onblur="calcula()" ontype="number" name="valor_entrada" id="valor_entrada" class="form-control" required />
                            </div>
                            <br>
                            <div class="form_group">
                                <label for="valor_negociar">Numero de parcelas</label>
                                <select type="number" name="valor_negociar" id="valor_negociar" class="form-control">

                                </select>
                            </div>
                            <hr>
                            <h5>Dividas a serem negociadas</h5>
                            <div class="table responsive">
                                <div class="container">
                                    <table id="tabela_cliente">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="valor">Valor</th>
                                                <th scope="col" data-field="status">Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Gerar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

</body>

</html>


<script>
    $('#valorTotal_negociar').mask('000.000.000.000.000,00', {
        reverse: true
    });


    // ====================================
    // -------------Mensagens--------------
    // ====================================

    function alertaMensagem(texto, sucesso = true) {
        if (sucesso) {
            tata.success(texto, '')
        } else {
            tata.warn(texto, '')
        }
    }

    // ====================================
    // ------------Busca table-------------
    // ====================================

    function buscarSolicitacao() {
        $.ajax({
            url: "busca_solicitacao.php",
            success: function(result) {

                $('#tabela_dividas').bootstrapTable('destroy')
                $('#tabela_dividas').bootstrapTable({
                    data: JSON.parse(result)
                })
                $('#tabela_dividas').bootstrapTable('refreshOptions', {
                    classes: "table"
                })
            }
        })
    }

    buscarSolicitacao()

    // ====================================
    // ------------Abrir modal-------------
    // ====================================

    function abriModalDividas() {
        let arrayDivida = $('#tabela_dividas').bootstrapTable('getData')
        let arrayParaTabelaDivida = [];
        let valor = 0;

        for (let i = 0; i < arrayDivida.length; i++) {
            if (arrayDivida[i]['divida'] == true) {
                if (arrayDivida[i]['status'] == 'Negociado') {
                    continue
                } //Se o status for negociado vai passar pro próximo, ou seja, só vao aparecer os com status de pendente
                valor += parseInt(arrayDivida[i]['valor'])
                arrayParaTabelaDivida.push({
                    id_dividas: arrayDivida[i]['id_dividas'],
                    tipo_divida: arrayDivida[i]['tipo_divida'],
                    valor: arrayDivida[i]['valor'],
                    status: arrayDivida[i]['status'],
                    id: arrayDivida[i]['id_cad'],
                    nome: arrayDivida[i]['nome'],
                    cpf: arrayDivida[i]['cpf']
                })
            }
        }

        $('#valorTotal_negociar').attr('data-valor-divida', valor)
        $('#valorTotal_negociar').val(valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }))


        $('#tabela_cliente').bootstrapTable('destroy')
        $('#tabela_cliente').bootstrapTable({
            data: arrayParaTabelaDivida
        })
        if ($('#tabela_cliente').bootstrapTable('getData') == 0) {
            return
        }//se a tabela estiver vazia, o modal de negociar dividas nao vai abrir, o return faz um retorno vazio
        
        $('#vd').attr('hidden', false)
        let entrada = $('#valorTotal_negociar').attr('data-valor-divida') * 0.1

        if (valor <= 100) {
            avista()
        } else {
            $('#valor_entrada').val(formataDinheiro(entrada))
            //entrada.toFixed(2)
            parcelas()
        }

        $('#tabela_cliente').bootstrapTable('refreshOptions', {
            classes: "table"
        })

        $('#dividas-gerar').modal('show')
    }

    // ====================================
    // -----------Gerar Divida-------------
    // ====================================

    function gerarDivida(event) {
        event.preventDefault()
        let id_cad = []
        $('#tabela_cliente').bootstrapTable('getData').forEach(cliente => {
            id_cad.push(cliente.id)
        })

        if (id_cad != "") {
            $.ajax({
                url: "ajax/gerarDivida.php",
                method: "POST",
                data: {
                    id_cad: id_cad,
                    tipo_divida: $("#tipo_divida").val(),
                    valor: $("#valor").val(),
                },
                success: function(dados) {
                    dados = JSON.parse(dados)
                    if (dados.status == "sucesso") {
                        buscarSolicitacao()
                        $('#dividas-gerar').modal('hide')
                        alertaMensagem('Divida registrada com sucesso')
                    } else {
                        alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                    }
                },
                error: function() {
                    alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                }
            })
        } else {
            alertaMensagem('Selecione alguma divida', false);
        }
    }

    // ====================================
    // ----------Negociar dividas----------
    // ====================================

    function negociarDividas(event) {
        event.preventDefault()
        let id_dividas = []
        let status = []

        $('#tabela_cliente').bootstrapTable('getData').forEach(cliente => {
            id_dividas.push(cliente.id_dividas)
        })
        $('#tabela_cliente').bootstrapTable('getData').forEach(client => {
            status.push(client.status)
        })

        calcula();

        if (id_dividas != "") {
            if (status == "Pendente") {
                $.ajax({
                    url: "ajax/negociarDivida.php",
                    method: "POST",
                    data: {
                        id_dividas: id_dividas
                    },
                    success: function(dados) {
                        dados = JSON.parse(dados)
                        if (dados.status == "sucesso") {
                            buscarSolicitacao()
                            $('#dividas-gerar').modal('hide')
                            alertaMensagem('Divida registrada com sucesso')
                        } else {
                            alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                        }
                    },
                    error: function() {
                        alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                    }
                })
            } else {
                alertaMensagem('Divida já negociada', false);
            }
        } else {
            alertaMensagem('Selecione alguma divida', false);
        }
    }

   
    // ===========================================
    // Calcula se o valor da entrada segue a regra
    // ===========================================

    function calcula() {

        let conta = $('#valorTotal_negociar').attr('data-valor-divida') * 0.1;

        if ($('#mensagemAppend').length > 0) {
            if (conta <= retiraMascaraDinheiro($('#valor_entrada').val())) {
                $('#mensagemAppend').remove()
            }
        } else {
            if (conta <= retiraMascaraDinheiro($('#valor_entrada').val())) {
                parcelas()
            } else {
                $('#vd').append(`<p style="color:red; font-weight: 600;" id='mensagemAppend'>Entrada de no mínimo 10%</p>`)
            }
        }
    }

    //--------------formatar dinheiro--------------
    function formataDinheiro(n) {
        return n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }

    function retiraMascaraDinheiro(n) {
        return n.replace('.', '').replace(',', '.');
    }
    //---------------------------------------------

    // ====================================
    // ---função da options das parcelas---
    // ====================================

    function parcelas() {
        if ($('#valor_negociar option').length > 0) {
            $('#valor_negociar option').remove()
        }
        let parcelas = $('#valorTotal_negociar').attr('data-valor-divida') - retiraMascaraDinheiro($('#valor_entrada').val())
        let resultado = parcelas / 100

        let arredondado = Math.ceil(resultado)

        for(let i = 1; i <= arredondado; i++){
            let valores = parcelas / i
            $('#valor_negociar').append($('<option>',{
                value: [i],
                text: [i] + 'x de R$ ' + formataDinheiro(valores)
                // (Math.round(valores * 100) / 100).toFixed(2)
            }))
        }
    }

    function avista() {
        if ($('#valor_negociar option').length > 0) {
            $('#valor_negociar option').remove()
        }

        $('#vd').attr('hidden', true)
        $('#valor_negociar').append($('<option>', {
            value: 1,
            text: 'A vista'
        }));
    }
</script>