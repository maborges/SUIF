<?php 

if (function_exists('simplexml_load_string')) {
    echo "O m�dulo SimpleXML est� habilitado.";
} else {
    echo "O m�dulo SimpleXML n�o est� habilitado.";
}


phpinfo(); 
?>