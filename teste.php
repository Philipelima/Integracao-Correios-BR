<?php


require_once("./vendor/autoload.php");

use App\Services\Correios;


$dadosConsultaFrete = [

    'servico'               =>  Correios::PAC,
    'cepOrigem'             =>  '47813282',
    'cepDestino'            =>  '05861900',
    'pesoEncomenda'         =>  1,
    'formatoEncomenda'      =>  Correios::CAIXA_PACOTE,
    'comprimentoEncomenda'  =>  15,
    'alturaEncomenda'       =>  15,
    'larguraEncomenda'      =>  15,
    'diametroEncomenda'     =>  15, 
    'maoPropria'            =>  true,
    'valorDeclarado'        =>  0,
    'avisoRecebimento'      =>  false
];

$calculoFrete = (new Correios())->consultarFrete($dadosConsultaFrete);

echo " <pre>"; 
 print_r($calculoFrete);
  echo "</pre>"; 
 exit;