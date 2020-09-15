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
});

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
        success: function (result) {

            $('#tabela_dividas').bootstrapTable('destroy')
            $('#tabela_dividas').bootstrapTable({
                data: JSON.parse(result),
                onExpandRow: function (index, row, $detail) {
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

// function GeraParcelasDivida(){
//     $.ajax({
//         url: "gerarDivida.php",
//         data: {

//         }
//     })
// }

function dividasEmitidasEmissor(id_emissor, subtable) {
    $.ajax({
        url: "busca_solicitacao.php",
        method: "GET",
        data: {
            id_emissor: id_emissor,
            tipo: 'dividas_emissor'
        },
        beforeSend: function () {
            $("body").addClass("loading");
        },
        success: function (result) {
            $("body").removeClass("loading");
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
        // if(retiraMascaraDinheiro($('#valorTotal_negociar').val()) <= 100){
        document.getElementById("apareceAvista").setAttribute('style', 'display: none')
    } else {
        $('#valor_entrada').val(formataDinheiro(entrada))
        //entrada.toFixed(2)
        parcelas()
        document.getElementById("apareceAvista").setAttribute('style', 'display: block')
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

    const objetoParaDivida = {
        id_cad: id_cad,
        tipo_divida: $("#tipo_divida").val(),
        valor: retiraMascaraDinheiro($("#valor").val()),
        vencimento: $("#vencimento").val(),
        valorJuros: retiraMascaraDinheiro($("#valorJuros").val()),
        cobranca: $("#cobranca").val(),
        valorMulta: retiraMascaraDinheiro($("#valorMulta").val()),
        tipo_juros: $("#tipo_juros").val()
    }

    if (id_cad != "") {
        $.ajax({
            url: "ajax/gerarDivida.php",
            method: "POST",
            data: objetoParaDivida,
            success: function (dados) {
                dados = JSON.parse(dados)
                if (dados.status == "sucesso") {
                    buscarSolicitacao()
                    $('#dividas-gerar').modal('hide')
                    alertaMensagem('Divida registrada com sucesso')
                } else {
                    alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                }
            },
            error: function () {
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

    if ($('#mensagemErro').length > 0 == false) {
        if ($('#mensagemAppend').length > 0 == false) {
            $.ajax({
                url: "ajax/negociarDivida.php",
                method: "POST",
                data: {
                    id_dividas: id_dividas
                },
                success: function (dados) {
                    dados = JSON.parse(dados)
                    if (dados.status == "sucesso") {
                        buscarSolicitacao()
                        $('#dividas-gerar').modal('hide')
                        alertaMensagem('Divida registrada com sucesso')
                    } else {
                        alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                    }
                },
                error: function () {
                    alertaMensagem('Erro ao registrar divida, favor contatar o suporte', false)
                }
            })
        } else {
            alertaMensagem('Entrada de no mínimo 10%', false);
        }
    } else {
        alertaMensagem('Valor de entrada não é válido', false);
    }
}


// ===========================================
// Calcula se o valor da entrada segue a regra
// ===========================================

function calcula() {
    if (retiraMascaraDinheiro($('#valor_entrada').val()) <= retiraMascaraDinheiro($('#valorTotal_negociar').val())) {
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

        let valorEntradaValido = retiraMascaraDinheiro($('#valorTotal_negociar').val()) - retiraMascaraDinheiro($('#valor_entrada').val())
        if ($('#mensagemErro').length > 0) {
            if (valorEntradaValido > 50) {
                $('#mensagemErro').remove()
            }
        } else {
            if (valorEntradaValido < 50) {
                $('#vd').append(`<p style="color:red; font-weight: 600;" id='mensagemErro'>Valor não é válido</p>`)
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

let arraySobraParcelas = []
function parcelas() {

    if ($('#valor_negociar option').length > 0) {
        $('#valor_negociar option').remove()
    }
    if (retiraMascaraDinheiro($('#valorTotal_negociar').val()) == retiraMascaraDinheiro($('#valor_entrada').val())) {
        avista()
        return
    }
    // let valorTotal = retiraMascaraDinheiro($('#valorTotal_negociar').val())
    // let valor = valorTotal + 
    // $('#id_total').html('R$ ' + formataDinheiro(valor))

    let totalNegociarComJuros = (Number($('#valorTotal_negociar').attr('data-valor-divida'))) + Number($('#valorTotal_negociar').attr('data-valor-divida')) * 0.025
    let totalNegociar = totalNegociarComJuros - retiraMascaraDinheiro($('#valor_entrada').val())
    let arredondado = Number(totalNegociar.toFixed(2))
    let frase = ''
    $('#id_entrada').html('R$ ' + formataDinheiro(arredondado))

    document.getElementById("vj").setAttribute('style', 'display: none')
    document.getElementById("vp").setAttribute('style', 'display: block')

    arraySobraParcelas = []
    for (let parcelas = 2; parcelas <= arredondado; parcelas++) {
        let valores = 0
        let porcentagem = 0
        // let resultado = totalNegociar / parcelas
        // let resultadoSimplificado = Number(resultado.toFixed)


        if (parcelas <= 6) {
            valores = totalNegociar / parcelas
            frase = 'sem juros '
        } else {
            arredondaParcelas = Math.ceil((parcelas - 6) / 3)
            porcentagem = arredondaParcelas * .012
            resultadoJurosParcelas = totalNegociar + (totalNegociar * porcentagem)
            valores = resultadoJurosParcelas / parcelas
            arrumarPorcentagem = porcentagem * 100
            frase = `com juros (${arrumarPorcentagem.toFixed(2)}%)`
        }

        function validaParcelas() {
            if (totalNegociar < 5000) {
                // fafa = resultado - resultadoSimplificado
                // if(parcelas == 12){
                //     valores + fafa
                // }
                if (parcelas > 12) return true
            } else if (totalNegociar < 10000) {
                if (parcelas > 24) return true
            } else {
                if (parcelas > 36) return true
            }
        }
        if (validaParcelas() == true) return

        // if(valores < 49.99) return 
        $('#valor_negociar').append($('<option>', {
            value: [parcelas],
            text: `${parcelas}x ${frase} R$ ${formataDinheiro(valores)}`
        }))
        console.log({
            totalNegociar,
            parcelas,
            'PARCELAS': (totalNegociar / parcelas) * parcelas
        });
        const valorDivergenteParcela = ((((totalNegociar / parcelas) * parcelas)).toFixed(2) - (totalNegociar).toFixed(2))
  
        arraySobraParcelas.push({ [parcelas]: valorDivergenteParcela })
    }
}

function avista() {
    let valorTotal = retiraMascaraDinheiro($('#valorTotal_negociar').val())

    let valor = (valorTotal * 0.03) + valorTotal
    document.getElementById("vj").setAttribute('style', 'display: block')
    document.getElementById("vp").setAttribute('style', 'display: none')
    // $('#id_entrada').html('R$ ' + arredondado)
    $('#id_total').html('R$ ' + formataDinheiro(valor))
    if ($('#valor_negociar option').length > 0) {
        $('#valor_negociar option').remove()
    }
    $("#pagamento").val($('option:contains("A vista")').val())
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

function formaDePagamento() {
    if ($('#pagamento').val() == 'vista') {
        avista()
    } else if ($('#pagamento').val() == 'parcelado') {
        parcelas()
        $('#vd').attr('hidden', false)
        $('#valor_entrada').val(formataDinheiro(retiraMascaraDinheiro($('#valorTotal_negociar').val()) * 0.1))
    }
}
