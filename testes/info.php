<?php 

if (function_exists('simplexml_load_string')) {
    echo "O módulo SimpleXML está habilitado.";
} else {
    echo "O módulo SimpleXML não está habilitado.";
}


phpinfo(); 
?>