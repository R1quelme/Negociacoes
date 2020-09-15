<?php
require_once 'conexoes/conexao.php';
require_once 'conexoes/login.php';

if (!is_logado()) {
    header("location: user-login.php");
    die;
} 
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
            background: url(assets1/Fundo.png) no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        #aparecer {
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('http://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
        }

        /* enquanto estiver carregando, o scroll da página estará desativado */
        body.loading {
            overflow: hidden;
        }

        /* a partir do momento em que o body estiver com a classe loading,  o modal aparecerá */
        body.loading .modal {
            display: block;
        }
    </style>

    <body id="body">
        <?php
        if (empty($_SESSION['nome_dividas'])) {
        ?>
            <a href='user-login.php'>Entrar</a>
        <?php
        } else {
        ?>
            <div style="margin-bottom: 29px;" class='d-block p-2 bg-dark text-white'>
                Olá, <?= $_SESSION['nome_dividas'] ?> |
                <a href='user-logout.php'>Sair</a>
            </div>
        <?php
        }
        ?>
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


        <!-- Modais -->
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
                        <form onsubmit="return gerarDivida(event)">
                            <div class="modal-body">
                                <label for="valorTotal_negociar">Valor a negociar</label><br>
                                <div class="input-group">
                                    <input type="text" name="valorTotal_negociar" data-valor-divida="" id="valorTotal_negociar" class="form-control" aria-label="Text input with dropdown button" readonly required />
                                    <div id="apareceAvista">
                                        <div class="input-group-append">
                                            <select class="btn btn-outline-secondary dropdown-toggle" onchange="formaDePagamento()" onclick="formaDePagamento()" name="pagamento" id="pagamento">
                                                <option value="" hidden>Forma de pagamento</option>
                                                <option value="vista">A vista</option>
                                                <option value="parcelado">Parcelado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="form_group" id="vd">
                                    <label for="valor_entrada">Valor da entrada</label>
                                    <input onblur="calcula()" ontype="number" name="valor_entrada" id="valor_entrada" class="form-control" required />
                                </div><br>
                                <div id="vp">
                                <p font-weight: 600; id='totalAppend'>Valor total a parcelar: <span id="id_entrada"></span> </p>
                                </div>
                                <div class="form_group">
                                    <label for="valor_negociar">Numero de parcelas</label>
                                    <select type="number" name="valor_negociar" id="valor_negociar" class="form-control"></select><br>
                                </div>
                                <div id="vj">
                                <p font-weight: 600; id='totalAppend'>Valor total com juros: <span id="id_total"></span> </p>
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
        <div class="modal"></div>
    </body>
</html>
<script src="script.js"></script>