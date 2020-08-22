<?php
require_once 'conexoes/conexao.php';
require_once 'conexoes/login.php';


if (is_admin()) {
    $q = "SELECT 
    id_cad, cpf, nome, tipo_negocio, pessoa
FROM
    cadastros
WHERE
    pessoa = 'PF'";

    $resultadoDaBuscaSolicitacoes = $conexao->query($q);

    $arraypararetorno = [];

    while ($registro = $resultadoDaBuscaSolicitacoes->fetch_object()) {
        $array = [];
        $array['id_cad'] = $registro->id_cad;
        $array['cpf'] = $registro->cpf;
        $array['nome'] = $registro->nome;
        $array['tipo_negocio'] = $registro->tipo_negocio;
        $arraypararetorno[] = $array;
    }

    echo json_encode($arraypararetorno);
} else if ($_GET['tipo'] == 'dividas_emissor') {
    $q = "SELECT 
	d.id_dividas,
	c.id_cad,
	d.tipo_divida,
	d.valor,
    d.vencimento,
	d.status,
    d.tipo_juros,
    d.juros,
    d.cobranca,
    d.multa
    -- if (d.tipo_juros = 'porc', (valor*juros)+multa, (valor+juros)+multa ) as total
    -- valor+(valor*juros)+multa as total
FROM
	divida AS d   
	JOIN
    cadastros AS c ON c.id_cad = d.id_cad
WHERE 
    c.id_cad = {$_SESSION['id_cad_dividas']} and d.id_emissor = {$_GET['id_emissor']}";

    //2020-10-04
    // echo $q;
    // echo date('Y-m-d');

    $resultadoBuscaDividas = $conexao->query($q);

    function calculaJuros($registro)
    {
        $database = date_create($registro->vencimento);
        $datadehoje = date_create();
        $resultado = date_diff($database, $datadehoje);
        echo date_interval_format($resultado, '- '. '%a');
    }
    
    $arraypararetorno = [];
    while ($registro = $resultadoBuscaDividas->fetch_object()) {
        $valor = $registro->valor;
        $juros = $registro->juros;
        $multa = $registro->multa;
        $array = [];
        $datetime = new DateTime($registro->vencimento);
        $datetimeformat = $datetime->format('d/m/Y');
        $array['id_dividas'] = $registro->id_dividas;
        // $array['nome'] = $registro->nome; 
        $array['tipo_divida'] = $registro->tipo_divida;
        $array['valor'] = number_format($valor, 2, ",", ".");
        $array['status'] = $registro->status;
        $array['vencimento'] = $datetimeformat;
        if (strtotime($registro->vencimento) > strtotime(date('Y-m-d'))) {
            $array['juros'] = number_format(0, 2, ",", ".");
            $array['valorMulta'] = number_format(0, 2, ",", ".");
            $array['valor_total'] = number_format($valor, 2, ",", ".");
        } else {
            $array['juros'] = number_format($juros, 2, ",", ".");
            $array['valorMulta'] = number_format($multa, 2, ",", ".");
        }
        $array['tipo_juros'] = $registro->tipo_juros;

        if($registro->cobranca == 'D'){
            $array['cobranca'] = "Diaria";
        } elseif($registro->cobranca == 'M'){
            $array['cobranca'] = "Mensal";
        } elseif($registro->cobranca == 'A'){
            $array['cobranca'] = "Anual";
        } else{
            $array['cobranca'] = "Nulo";
        }
        
        $arraypararetorno[] = $array;
        // calculaJuros($registro);
    }

    echo json_encode($arraypararetorno);
} else {
    $resultadoDaBuscaEmissores = $conexao->query("
            SELECT 
                d.id_emissor,c.nome,c.cpf,c.tipo_negocio
            FROM
                divida d
                    JOIN
                cadastros c ON c.id_cad = d.id_emissor
            WHERE
                c.Pessoa = 'PJ'
                and d.id_cad = {$_SESSION['id_cad_dividas']}
            group by c.cpf
            ");

    $arraypararetorno = [];

    while ($registro = $resultadoDaBuscaEmissores->fetch_object()) {
        $array = [];
        $array['id_emissor'] = $registro->id_emissor;
        $array['nome'] = $registro->nome;
        $array['cpf'] = $registro->cpf;
        $array['tipo_negocio'] = $registro->tipo_negocio;
        // $array['gerar'] = "<a class='btn btn-danger' onclick='modalGerarDivida(" . $registro->id_cad . ")' style='color: #fff !important;'>Gerar</a>";
        // $array['acao'] = "<a class='btn btn-info' onclick='modalConsultarDivida(" . $registro->id_cad . ")' style='color: #fff !important;'>Consultar</a>";
        $arraypararetorno[] = $array;
    }
    echo json_encode($arraypararetorno);
} 

// $q = "
// SELECT 
//     d.id_dividas,
//     c.id_cad,
//     c.nome,
//     d.tipo_divida,
//     d.valor,
//     d.status
// FROM
//     divida AS d   
//         JOIN
//     cadastros AS c ON c.id_cad = d.id_cad
// WHERE 
//     c.id_cad = " . $_SESSION['id_cad_dividas'];

// $resultadoDaBuscaSolicitacoes = $conexao->query($q);

// $arraypararetorno = [];

// while($registro = $resultadoDaBuscaSolicitacoes->fetch_object()){
//     $valor = $registro->valor;
//     $array = [];
//     $array['id_dividas'] = $registro->id_dividas;
//     // $array['nome'] = $registro->nome; 
//     $array['tipo_divida'] = $registro->tipo_divida;
//     $array['valor'] = number_format($valor,2,",",".");
//     $array['status'] = $registro->status;
//     // $array['gerada'] = $registro->;
//     // $array['acao'] = "<a class='btn btn-info' onclick='modalNegociar(" . $registro->id_cad . ")' style='color: #fff !important;'>Negociar</a>";
//     // $array['gerar'] = "<a class='btn btn-danger' onclick='modalGerarDivida(" . $registro->id_cad . ")' style='color: #fff !important;'>Gerar</a>";
//     // $array['acao'] = "<a class='btn btn-info' onclick='modalConsultarDivida(" . $registro->id_cad . ")' style='color: #fff !important;'>Consultar</a>";
//     $arraypararetorno[] = $array;
// }                     

// echo json_encode($arraypararetorno);

// $id = '';
// if (array_key_exists("id_cad", $_GET)) {
//     $id = $_GET['id_cad'];
//     $q .= " WHERE (id_cad=$id)";
// }
