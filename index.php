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
    echo "<div class='d-block p-2 bg-dark text-white' style='position: absolute;'>";
    echo "Olá, " . $_SESSION['nome_dividas'] . " | ";
    echo "<a href='user-logout.php'>Sair</a>";
    echo "</div>";
}
echo "</p>";
echo "<br>";
echo "<br>";
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
    body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background: url(Fundo.png) no-repeat;
        background-size: cover;
    }

    #aparecer {
        display: none;
    }

    #load {
        position: fixed;
        display: block;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<body id="body" onload="loading()">
    <div id="load"></div>
    <div class="table responsive">
        <div class="container">
            <?php
            if (is_admin()) { ?>
                <h2>Gerar dividas</h2>
                <p><button type="button" class="btn btn-danger" onclick="abriModalGerarDividas()">
                        Gerar Divida
                    </button></p>
                <table id="tabela_dividas"><br>
                    <thead>
                        <tr>
                            <th scope="col" data-field="id_cad" data-visible="false"></th>
                            <th scope="col" data-field="divida" data-checkbox="true"></th>
                            <th scope="col" data-field="nome">Nome</th>
                            <th scope="col" data-field="cpf">CPF</th>
                            <th scope="col" data-field="tipo_negocio">Tipo de negocio</th>
                        </tr>
                    </thead>
                </table>
            <?php } else { ?>
                <h2>Dividas</h2>
                <p><button type="button" class="btn btn-danger" onclick="abriModalNegociarDividas()">
                        Negociar dividas
                    </button></p>
                <table id="tabela_dividas" data-detail-view="true"><br>
                    <thead>
                        <tr>
                            <th scope="col" data-field="id_emissor" data-visible="false"></th>
                            <th scope="col" data-field="nome">Nome</th>
                            <th scope="col" data-field="cpf">CNPJ</th>
                            <th scope="col" data-field="tipo_negocio">Tipo de negocio</th>
                        </tr>
                    </thead>
                </table>

            <?php
            }
            ?>

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
                                <input ontype="number" name="valor" id="valor" class="form-control" required>
                            </div><br>
                            <div class="form_group">
                                <label for="vencimento">Vencimento: </label>
                                <input type="date" name="vencimento" id="vencimento" class="form-control" required>
                            </div><br>
                            <div class="form_group">
                                <label for="tipo_multa">Tipo da multa: </label>
                                <select name="tipo_multa" id="tipo_multa" onchange="campoPorcMulta()" class="form-control">
                                    <option value="">Selecione uma resposta</option>
                                    <option value="val">Valor</option>
                                    <option value="porc">Porcentagem</option>
                                </select>
                            </div><br>
                            <div id="aparecerMulta">
                                <div class="form_group" id="vm">
                                    <label for="valorMultaPorc">Porcentagem da multa: </label>
                                    <input ontype="number" onblur="calculaPorcentagem()" name="valorMultaPorc" id="valorMultaPorc" class="form-control">
                                </div><br>
                            </div>
                            <div class="form_group" id="vm">
                                <label for="valorMulta">Valor da multa: </label>
                                <input onblur="calculaPorcentagem()" ontype="number" name="valorMulta" id="valorMulta" class="form-control">
                            </div><br>
                            <div class="form-group">
                                <label for="juros">Vai ter Juros? </label>
                                <select name="juros" id="juros" class="form-control" onchange="validaJuros()" required>
                                    <option value="sim">Sim</option>
                                    <option value="nao">Não</option>
                                </select><br>
                            </div>
                            <div id="aparecer">
                                <div class="form_group">
                                    <label for="tipo_juros">Tipo dos juros: </label>
                                    <select name="tipo_juros" id="tipo_juros" onchange="mascara_tipoJuros()" class="form-control">
                                        <option value="">Selecione uma resposta</option>
                                        <option value="val">Valor</option>
                                        <option value="porc">Porcentagem</option>
                                    </select>
                                </div><br>
                                <div class="form_group">
                                    <label for="valorJuros">Valor ou porcentagem dos juros: </label>
                                    <input ontype="number" name="valorJuros" id="valorJuros" class="form-control">
                                </div><br>
                                <div class="form-group">
                                    <label for="cobranca">Juros vai ser: </label>
                                    <select name="cobranca" id="cobranca" class="form-control">
                                        <option value="">Selecione uma resposta</option>
                                        <option value="N" hidden>Não há</option>
                                        <option value="D">Diario</option>
                                        <option value="M">Mensal</option>
                                        <option value="A">Anual</option>
                                    </select>
                                </div>
                            </div>


                            <hr>
                            <h5>Clientes a receber dívida</h5>
                            <div class="table responsive">
                                <div class="container">
                                    <table id="tabela_cliente">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="id">#</th>
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
                            <label for="valorTotal_negociar">Valor a negociar</label><br>
                            <div class="input-group">
                                <input type="text" name="valorTotal_negociar" data-valor-divida="" id="valorTotal_negociar" class="form-control" aria-label="Text input with dropdown button" readonly required />
                                <div class="input-group-append">
                                    <select class="btn btn-outline-secondary dropdown-toggle" onchange="formaDePagamento()" onclick="formaDePagamento()" name="pagamento" id="pagamento">
                                        <option value="" hidden>Forma de pagamento</option>
                                        <option value="vista">A vista</option>
                                        <option value="parcelado">Parcelado</option>
                                    </select>
                                </div>
                            </div><br>
                            <div class="form_group" id="vd">
                                <label for="valor_entrada">Valor da entrada</label>
                                <input onblur="calcula()" ontype="number" name="valor_entrada" id="valor_entrada" class="form-control" required />
                            </div>
                            <br>
                            <div class="form_group">
                                <label for="valor_negociar">Numero de parcelas</label>
                                <select type="number" name="valor_negociar" id="valor_negociar" class="form-control"></select>
                            </div>
                            <hr>
                            <h5>Dividas a serem negociadas</h5>
                            <div class="table responsive">
                                <div class="container">
                                    <table id="tabela_cliente">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="valor_inicial">Valor principal</th>
                                                <th scope="col" data-field="valor_total">Valor a negociar</th>
                                                <!-- <th scope="col" data-field="status">Status</th> -->
                                                <th scope="col" data-field="tipo_divida">Tipo da divida</th>
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
    // var i = setInterval(function () {
    
    // clearInterval(i);
  
    // O código desejado é apenas isto:
    // document.getElementById("loading").style.display = "none";
    // document.getElementById("conteudo").style.display = "block";
    // });

    function loading(){
     $('#load').css('display','none');
    }

    $('#valorTotal_negociar').mask('000.000.000.000.000,00', {
        reverse: true
    });

    $('#valor_entrada').mask('000.000.000.000.000,00', {
        reverse: true
    });

    $('#valor').mask('000.000.000.000.000,00', {
        reverse: true
    });

    $('#valorMulta').mask('000.000.000.000.000,00', {
        reverse: true
    });

    $("#valorMultaPorc").mask('000,00%', {
        reverse: true
    });

    $("#valorMulta").mask('000.000.000.000.000,00', {
        reverse: true
    })

    function mascara_tipoJuros() {
        if ($('#tipo_juros').val() === "val") {
            $("#valorJuros").mask('000.000.000.000.000,00', {
                reverse: true
            })
        } else if ($('#tipo_juros').val() === "porc") {
            $("#valorJuros").mask('000,00%', {
                reverse: true
            })
        }
    }
    mascara_tipoJuros()


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
            data: {
                tipo: 'busca_solicitacao'
            },
            success: function(result) {

                $('#tabela_dividas').bootstrapTable('destroy')
                $('#tabela_dividas').bootstrapTable({
                    data: JSON.parse(result),
                    onExpandRow: function(index, row, $detail) {
                        // console.log({
                        //     index,
                        //     row,
                        //     $detail
                        // })
                        dividasEmitidasEmissor(row.id_emissor, $detail)
                    }
                })
                $('#tabela_dividas').bootstrapTable('refreshOptions', {
                    classes: "table"
                })
            }
        })
    }

    function dividasEmitidasEmissor(id_emissor, subtable) {
        $.ajax({
            url: "busca_solicitacao.php",
            method: "GET",
            data: {
                id_emissor: id_emissor,
                tipo: 'dividas_emissor'
            },
            success: function(result) {
                // console.log("sucesso")
                criaTabela(subtable, JSON.parse(result))
            }
        })
    }
    var indice_table = 0;

    function criaTabela(table, dadostable) {
        // console.log(table)
        table = table.html(`
            <table class="tabela_clientes" id='table_${indice_table}'>
                <thead>
                    <tr>
                        <th scope="col" data-field="id_dividas" data-visible="false"></th>
                        <th scope="col" data-field="divida" data-checkbox="true"></th>
                        <th scope="col" data-field="tipo_divida">Tipo de dividas</th>
                        <th scope="col" data-field="vencimento">Vencimento</th> 
                        <th scope="col" data-field="valor">Valor</th>
                        <th scope="col" data-field="juros">Juros</th>
                        <th scope="col" data-field="cobranca">Cobrança</th>
                        <th scope="col" data-field="valorMulta">Multa</th>
                        <th scope="col" data-field="valor_total">Valor total</th>
                        <th scope="col" data-field="status">Status</th>
                    </tr>
                </thead>
            </table>
        `).find('table')
        // <div id="loading" align="center" style="display: block">
        //     <img src="http://media.giphy.com/media/FwviSlrsfa4aA/giphy.gif" style="width:150px;height:150px;"/>
        // </div>
        // $('#tabela_dividas tr.detail-view').html('<table></table>').find('table')
        table.bootstrapTable({
            data: dadostable,
            classes: "table"
        })
        indice_table++
    }

    buscarSolicitacao()

    // ====================================
    // ---------Abrir modal Gerar----------
    // ====================================

    function abriModalGerarDividas() {
        let arrayDivida = $('#tabela_dividas').bootstrapTable('getData')
        let arrayParaTabelaDivida = [];

        for (let i = 0; i < arrayDivida.length; i++) {
            if (arrayDivida[i]['divida'] == true) {
                arrayParaTabelaDivida.push({
                    id: arrayDivida[i]['id_cad'],
                    nome: arrayDivida[i]['nome'],
                    cpf: arrayDivida[i]['cpf']
                })
            }
        }

        $('#tabela_cliente').bootstrapTable('destroy')
        $('#tabela_cliente').bootstrapTable({
            data: arrayParaTabelaDivida
        })
        if ($('#tabela_cliente').bootstrapTable('getData') == 0) {
            return
        } //se a tabela estiver vazia, o modal de negociar dividas nao vai abrir, o return faz um retorno vazio

        $('#tabela_cliente').bootstrapTable('refreshOptions', {
            classes: "table"
        })

        $('#dividas-gerar').modal('show')
    }

    // ====================================
    // --------Abrir modal negociar--------
    // ====================================

    function abriModalNegociarDividas() {
        const tabelas = $('.tabela_clientes')
        let arrayParaTabelaDivida = [];
        let valor = 0;

        for (let j = 0; j < tabelas.length; j++) {
            const arrayDivida = $(`#${tabelas[j].id}`).bootstrapTable('getData')
            for (let i = 0; i < arrayDivida.length; i++) {
                if (arrayDivida[i]['divida'] == true) {
                    if (arrayDivida[i]['status'] == 'Negociado') {
                        continue
                    }
                    //  Se o status for negociado vai passar pro próximo, ou seja, só vao aparecer os com status de pendente
                    valor += retiraMascaraDinheiro(arrayDivida[i]['valor_total'])
                    arrayParaTabelaDivida.push({
                        id_dividas: arrayDivida[i]['id_dividas'],
                        tipo_divida: arrayDivida[i]['tipo_divida'],
                        valor_total: arrayDivida[i]['valor_total'],
                        valor_inicial: arrayDivida[i]['valor'],
                        status: arrayDivida[i]['status'],
                    })
                }
            }
        }

        $('#valorTotal_negociar').attr('data-valor-divida', valor)
        $('#valorTotal_negociar').val(formataDinheiro(valor))


        $('#tabela_cliente').bootstrapTable('destroy')
        $('#tabela_cliente').bootstrapTable({
            data: arrayParaTabelaDivida
        })
        if ($('#tabela_cliente').bootstrapTable('getData') == 0) {
            return
        } //se a tabela estiver vazia, o modal de negociar dividas nao vai abrir, o return faz um retorno vazio

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
                    valor: retiraMascaraDinheiro($("#valor").val()),
                    vencimento: $("#vencimento").val(),
                    valorJuros: retiraMascaraDinheiro($("#valorJuros").val()),
                    cobranca: $("#cobranca").val(),
                    valorMulta: retiraMascaraDinheiro($("#valorMulta").val()),
                    tipo_juros: $("#tipo_juros").val()
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

        calcula()

        if ($('#mensagemAppend').length > 0 == false) {
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
            alertaMensagem('Entrada de no mínimo 10%', false);
        }
    }


    // ===========================================
    // Calcula se o valor da entrada segue a regra
    // ===========================================

    function calcula() {
        if(retiraMascaraDinheiro($('#valor_entrada').val()) <= retiraMascaraDinheiro($('#valorTotal_negociar').val())){
            // function negative(number) { 
            // if(number.match(/^0\d+$/)){
            // retiraMascaraDinheiro($('#valor_entrada').val())
            $('#valor_entrada').val(formataDinheiro(retiraMascaraDinheiro($('#valor_entrada').val())))
            // }
            // }
            // negative(retiraMascaraDinheiro($('#valor_entrada').val()))

            const conta = Number((retiraMascaraDinheiro($('#valorTotal_negociar').val()) * 0.1).toFixed(2));

            if ($('#mensagemAppend').length > 0) {
                if (conta <= Number(retiraMascaraDinheiro($('#valor_entrada').val()).toFixed(2))) {
                    $('#mensagemAppend').remove()
                }
            } else {
                if (conta <= Number(retiraMascaraDinheiro($('#valor_entrada').val()).toFixed(2))) {
                    parcelas()
                } else {
                    $('#vd').append(`<p style="color:red; font-weight: 600;" id='mensagemAppend'>Entrada de no mínimo 10%</p>`)
                }
            }
        } else {
            alertaMensagem('Valor de entrada maior que o valor total', false);
            $('#valor_entrada').val(formataDinheiro(retiraMascaraDinheiro($('#valorTotal_negociar').val()) * 0.1))
        }
    }

    //--------------formatar dinheiro--------------
    function formataDinheiro(n) {
        return n.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
    }

    function retiraMascaraDinheiro(n) {
        return Number(n.replace('.', '').replace(',', '.').replace('%', ''));
    }
    //---------------------------------------------

    // ====================================
    // ---função da options das parcelas---
    // ====================================

    function parcelas() {
        if ($('#valor_negociar option').length > 0) {
            $('#valor_negociar option').remove()
        }
        if (retiraMascaraDinheiro($('#valorTotal_negociar').val()) == retiraMascaraDinheiro($('#valor_entrada').val())) {
            avista()
            return
        }
        let parcelas = $('#valorTotal_negociar').attr('data-valor-divida') - retiraMascaraDinheiro($('#valor_entrada').val())
        let resultado = parcelas
        let arredondado = Math.ceil(resultado)

        for (let i = 1; i <= arredondado; i++) {
            // for (let j = 0.50; j <= [i]; j += 0.20) {
            let valores = parcelas / i
            if (valores < 50) return
            $('#valor_negociar').append($('<option>', {
                value: [i],
                text: [i] + 'x de R$ ' + formataDinheiro(valores) 
                // + 'com juros de: ' + [j] + '%'
                // (Math.round(valores * 100) / 100).toFixed(2)
            }))
            // }
        }
    }

    function avista() {
        if ($('#valor_negociar option').length > 0) {
            $('#valor_negociar option').remove()
        }
        $("#pagamento").val($('option:contains("A vista")').val());
        $('#vd').attr('hidden', true)
        $('#valor_negociar').append($('<option>', {
            value: 1,
            text: 'A vista'
        }));
    }

    // ==========================================
    // função dos checkeds do modal gerar dividas
    // ==========================================
    function validaJuros() {
        if ($('#juros').val() == "sim") {
            document.getElementById("aparecer").setAttribute('style', 'display: block')
        } else {
            document.getElementById("aparecer").setAttribute('style', 'display: none')
            // $("#tipo_juros").val("val");
            // $("#valorJuros").val("0");
            // $("#cobranca").val("N");
            // $("#valorMulta").val("0");
            $("#tipo_juros").val("");
            $("#valorJuros").val("");
            $("#cobranca").val("");
        }
    }
    validaJuros()

    function campoPorcMulta() {
        if ($('#tipo_multa').val() == "") {
            document.getElementById("aparecerMulta").setAttribute('style', 'display: none')
        } else if ($('#tipo_multa').val() == "val") {
            document.getElementById("aparecerMulta").setAttribute('style', 'display: none')
        } else if ($('#tipo_multa').val() == "porc") {
            document.getElementById("aparecerMulta").setAttribute('style', 'display: block')
        }
    }
    campoPorcMulta()

    function calculaPorcentagem() {
        let valorDivisao = retiraMascaraDinheiro($('#valorMultaPorc').val())
        if ($('#tipo_multa').val() == "porc") {
            let conta = (retiraMascaraDinheiro($('#valor').val()) * valorDivisao / 100);
            $('#valorMulta').val(formataDinheiro(conta))
        }
    }
    calculaPorcentagem()

    function formaDePagamento(){
        if($('#pagamento').val() == 'vista'){
            avista()
        } else if($('#pagamento').val() == 'parcelado'){
            parcelas();
            $('#vd').attr('hidden', false)
            $('#valor_entrada').val(formataDinheiro(retiraMascaraDinheiro($('#valorTotal_negociar').val()) * 0.1))
        }
    }
</script>