<?php

/*
$serv = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];

$erro = 1;
$msg  = "";


require("../includes/config.php");
include("../includes/conecta_bd.php");
require("../sankhya/Sankhya.php");
*/
include("../helpers.php"); 

$data = Helpers::ConverteData('02/06/2024');
echo $data;
/*
$string = '<?xml version="1.0" encoding="utf-8"?>
<serviceResponse serviceName="CACSP.incluirAlterarCabecalhoNota" status="0" pendingPrinting="false" transactionId="6F8F6B7B4D754E7E153B69F3AD8B0E11"><statusMessage><![CDATA[T1JBLTIwMTAxOiBPIHBhcmNlaXJvIGRldmUgY29udGVyIHZhbG9yIHbhbGlkbyBlIGRpZi4gZGUg
emVyby4gUGFyYSBvcC4gZGUgY29tcHJhLCBwZWQuIGRlIGNvbXByYSBlIGRldi4gZGUgY29tcHJh
LCBvIHBhcmMuIGRldmUgc2VyIHVtIGZvcm5lY2Vkb3IuIErhIHBhcmEgb3AuIGRlIHZlbmRhLCBw
ZWQuIGRlIHZlbmRhIGUgZGV2LiBkZSB2ZW5kYSwgbyBwYXJjLiBkZXZlIHNlciB1bSBjbGllbnRl
Lk5vdGEgZGUgTnJvINpuaWNvOiA1NzkyMQpPUkEtMDY1MTI6IGVtICJTQU5LSFlBLlRSR19VUERf
VEdGQ0FCIiwgbGluZSA5NzkKT1JBLTA0MDg4OiBlcnJvIGR1cmFudGUgYSBleGVjdefjbyBkbyBn
YXRpbGhvICdTQU5LSFlBLlRSR19VUERfVEdGQ0FCJw==
]]></statusMessage></serviceResponse>';
*/
//$string = str_replace('"', "'", $string);

//print_r($string);
/*
$xml = simplexml_load_string($string);
if (!$xml) {
    print_r('erro');
} else {
    print_r('erro');
//    var_dump($xml['status'], $xml['status']);
}


$vlrDecimal = 145.1245991;
$vlrDecimal = number_format(round($vlrDecimal, 4), 3, '.', '');
echo $vlrDecimal;
*/

/*
$idCompra = 235356;
$qtde = 3.54;
$idParceiroNota = 202413868;
*/

/*
$result = Sankhya::faturaPedidoCompra($idCompra, $idParceiroNota, $qtde);

print_r($result['rows']['notas']['nota']['$']);        

*/

/*
$idPedido = 52332;
 $result = Sankhya::confirmaPedidoCompra($idPedido);
 echo "<br>";
 var_dump('teste >>>>>>', $result);     


$numNota = 52332;

$result = Sankhya::alteraCabecalhoNota($numNota, $idParceiroNota, $idParceiroNota);

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";


print_r($result);        

*/
/*

$numPedido = 52408;

$result = Sankhya::cancelaPedidoCompra($numPedido);

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";


print_r($result);        


*/


// $datahora = "20012023 11:30:36";
// $dataHoraTipoOperacao = DateTime::createFromFormat("dmY H:i:s",$datahora)->format('d/m/Y H:i:s');
// var_dump($dataHoraTipoOperacao)

/*
if ($cidade['rowsCount'] == 0) {
    $erro = 1;
    $msg  = "<div style='color:#FF0000'>Cidade XXXXXX não cadastrada no estado XX no SIUF</div>";
} else if ($cidade['rowsCount'] > 1) {
    $erro = 1;
    $msg  = "<div style='color:#FF0000'>Existe mais de uma cidade no SUIF cadastrada com o nome XXXXX para o estado XX</div>";
}

var_dump($cidade);

echo $erro . '<br>';
echo $msg . '<br>';
*/


?>

