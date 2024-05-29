<?php

use Aivec\ArrayToXml\ArrayToXml;

include_once("ArrayToXml.php");

// JSON de exemplo
$json = '{"pessoas":[{"nome":"João","idade":30},{"nome":"Maria","idade":25}]}';

// Decodifica o JSON em um array associativo
$data = json_decode($json, true);

$numNota = 51878;
$idParceiro = 1567;
$idParceiroNota = 1567;

echo "<h4> p1 </h4>";
/*
$body = array(
    "NOTA" => array(
        "CABECALHO" => array(
            "NUNOTA" => $numNota,
            "AD_CODPARCFICHA" => $idParceiro,
            "AD_CODPARCNOTA" => $idParceiroNota
        )
    )
);
*/

$body = array(
    'NOTA' => array()
);

$showXML = ArrayToXml::convert($body);
print($showXML);
echo "<h4> p2 </h4>";

die();

// Cria um novo documento XML
$dom = new DOMDocument('1.0', 'utf-8');

// Cria o elemento raiz
$root = $dom->createElement('pessoas');
$dom->appendChild($root);

// Adiciona elementos filho ao elemento raiz
foreach ($data['pessoas'] as $pessoa) {
    $pessoaElement = $dom->createElement('pessoa');

    foreach ($pessoa as $key => $value) {
        $childElement = $dom->createElement($key, $value);
        $pessoaElement->appendChild($childElement);
    }

    $root->appendChild($pessoaElement);
}

// Define o cabeçalho do XML
header('Content-type: text/xml');

// Exibe o XML gerado
echo $dom->saveXML();
