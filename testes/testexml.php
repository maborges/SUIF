<?php
// require 'autoload.php';


if (function_exists('simplexml_load_string')) {
    echo "O modulo SimpleXML esta habilitado. <br>";
} else {
    echo "O modulo SimpleXML nao esta habilitado. <br>";
}


$xml = "<?xml version='1.0' encoding='UTF-8' ?>
<form name='test'></form>";

try {
    echo "dentro <br>";
    $simplexml = simplexml_load_string($xml) or die('erro na conversão');
    echo "fora <br> ";
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}
var_dump($simplexml['name']);
$reflector = new ReflectionObject($simplexml['name']);
$rprops = $reflector->getProperties();


$string = <<<XML
<?xml version='1.0'?>
<document>
    <cmd>login</cmd>
    <login>Richard</login>
</document>
XML;

$xml = simplexml_load_string($string);
print_r($xml);
$login = $xml->login;
print_r($login);
$login = (string) $xml->login;
print_r($login);
