<?php

$str = '<?xml version="1.0" encoding="utf-8"?>
<serviceResponse serviceName="CACSP.incluirAlterarCabecalhoNota" status="0" pendingPrinting="false" transactionId="9FDD072B67348C09B5025D57428D43C9"><tsError tsErrorCode="CORE_E01846" tsErrorLevel="ERROR" /><statusMessage><![CDATA[RGF0YSBkZSBGYXR1cmFtZW50byBkZXZlIHNlciBtYWlvciBvdSBpZ3VhbCBhIGRhdGEgZGUgTmVn
b2NpYefjby4=
    ]
]></statusMessage></serviceResponse>
';

// echo $str;
$str = str_replace('"', "'", $str); 
$str = str_replace(array("\r", "\n"),'', $str);     

$xml = simplexml_load_string($str, 'SimpleXMLElement', LIBXML_NOCDATA);
$msg = base64_decode($xml->statusMessage);

echo   "{$xml['serviceName']} <br>
status:<<< {$xml['status']} >>> <br>
{$xml['pendingPrinting']} <br>
{$xml['transactionId']} <br>
{$xml->tsError["tsErrorCode"]} <br>
{$xml->tsError["tsErrorLevel"]} <br>
$msg <br>
      
##################<><><><>#########
<br>
<br>
";

echo "status: <<{$xml['status']}>> <br>";
echo "status: <<$xml->status>> <br>";

if ("{$xml['status']}" == '0')  {
    echo 'status igual erro';
} else {
    echo 'status não erro';
}


// var_dump($xml['status']);
// var_dump($xml['tsError']);

// var_dump($xml);

// echo base64_decode($str, true);



?>