<?php

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['cep'])) {
  die(json_encode(array('erro' => 1, 'msg' => 'CEP não definido')));
}

if (empty($_GET['cep'])) {
  die(json_encode(array('erro' => 1, 'msg' => 'CEP vazio')));
}

$cep = $_GET['cep'];


$urlCorreios = 'http://m.correios.com.br/movel/buscaCepConfirma.do';

$postFields = array(
    'cepEntrada' => $cep, 
    'tipoCep'=>'', 
    'cepTemp'=>'', 
    'metodo'=>'buscarCep'
);


if (extension_loaded('curl') && false) { //FIXME

    $ch = curl_init($urlCorreios);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_ENCODING, 'UTF-8');
    $html = curl_exec ($ch);
    
} else {
    $request = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($postFields)
        )
    );

    $context = stream_context_create($request);

    $html = file_get_contents($urlCorreios, false, $context);
}

if ($html === false) {
    die(json_encode(array('erro' => 1, 'msg' => 'Erro ao obter dados')));
}

$doc = new DOMDocument();

@$doc->loadHTML($html);

$xpath = new DomXPath($doc);

$nodes = $xpath->query("//span[@class='respostadestaque']");

$totRegistros = $nodes->length / 4;


if (0 == $totRegistros) {
    die(json_encode(array('erro' => 1, 'msg' => 'Sem registros (CEP não encontrado)')));
}

$arrResultado = array();
$arrInfo = array('logadouro', 'bairro', 'cidade', 'cep');

for ($registro = 0; $registro < $totRegistros; $registro++) {

    $arrResultado[$registro + 1] = array();
    
    foreach ($arrInfo as $index => $value) {
    
        $pos = ($registro * 4) + $index;
        
        $arrResultado[$registro + 1][$value] = preg_replace('/\s{2,}/', ' ', $nodes->item($pos)->nodeValue);
    }
}

echo json_encode($arrResultado);
