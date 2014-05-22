<?php

header('Content-Type: application/json; charset=utf-8');

if (!$_GET['cep']) {
  die(json_encode(array('erro' => 1, 'msg' => 'CEP vazio')));
}

$ch = curl_init('http://m.correios.com.br/movel/buscaCepConfirma.do');
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query(array('cepEntrada' => $_GET['cep'], 'tipoCep'=>", 'cepTemp'=>", 'metodo'=>'buscarCep')));
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_ENCODING, 'UTF-8');
$html = curl_exec ($ch);

$doc = new DOMDocument();
$doc->loadHTML($html);
$doc->preserveWhiteSpace = false;
$spans = $doc->getElementsByTagName('span');

$dicionario = array('logadouro', 'bairro', 'cidade');
$i=0;
foreach ($spans as $span) {
  if ($span->getAttribute('class') == 'respostadestaque') {
    $resultados[$dicionario[$i]] = trim(preg_replace('/\s{2,}/',' ', $span->nodeValue));
    $i++;
    if ($i==3) break;
  }
}
      
if (!$resultados['logadouro'] && !$resultados['bairro']) {
  die(json_encode(array('erro' => 1, 'msg' => 'CEP não encontrado ou Serviço dos correios offline')));
}

$str = explode('/', $resultados['cidade']); // Cidade /UF
$resultados['cidade'] = trim($str[0]); // cidade
$i++;
$resultados['estado'] = trim($str[1]); // estado

echo json_encode($resultados);