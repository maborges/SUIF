<?php 

echo realpath(__DIR__) . '<br>';
echo $_SERVER['REQUEST_URI'] . '<br>';
echo realpath(__DIR__) . '<br>';
echo $_SERVER['DOCUMENT_ROOT'] . '<br>';

if (function_exists('simplexml_load_string')) {
    echo "O m�dulo SimpleXML est� habilitado.";
} else {
    echo "O m�dulo SimpleXML n�o est� habilitado.";
}


phpinfo(); 
?>