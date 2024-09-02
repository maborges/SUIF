<?php
// XML codificado em Base64
$xmlBase64 = "PHNhbWw+CiAgPG5hbWVzPgogICA8bmFtZT5BbGFybSBPZiBXYXk8L25hbWU+CiAgPC9uYW1lcwogIDxhZGRyPgogICA8ZW1haWw+YWxhcm1Ab3BlbmFtZS5jb208L2VtYWlsPgogIDwvYWRkcj4KICA8L3NhbWw+";

// Decodificar a string Base64 para recuperar o XML original
$xmlString = base64_decode($xmlBase64);

// Criar um novo objeto DOMDocument
$dom = new DOMDocument();

$xml = <<<XML
<form name="test"></form>
XML;

// Carregar o XML
$dom->loadXML($xml);

// Converter o XML em um array
$xmlArray = domNodeToArray($dom->documentElement);

// Exibir o array resultante
print_r($xmlArray);

// Função para converter um nó DOM em um array recursivamente
function domNodeToArray($node) {
    $output = array();

    switch ($node->nodeType) {
        case XML_CDATA_SECTION_NODE:
        case XML_TEXT_NODE:
            $output = trim($node->textContent);
            break;
        case XML_ELEMENT_NODE:
            for ($i = 0, $len = $node->childNodes->length; $i < $len; ++$i) {
                $child = $node->childNodes->item($i);
                $output[$child->nodeName] = domNodeToArray($child);
            }
            if ($node->attributes->length && !is_array($output)) { 
                $output = array('@content' => $output); 
            }
            if ($node->attributes->length) {
                $a = array();
                foreach ($node->attributes as $attrName => $attrNode) {
                    $a[$attrName] = (string) $attrNode->value;
                }
                $output['@attributes'] = $a;
            }
            break;
    }

    return $output;
}
?>
