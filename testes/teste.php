<?php

require("../includes/config.php");
include("../includes/conecta_bd.php");
include('../includes/valida_cookies.php');
//require("../sankhya/Sankhya.php");
include("../helpers.php");

$sevidor = 'servidor';
$diretorio = "temp"

?>
<h1>
    <div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
    <div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
        <a href=<?= "$servidor/$diretorio_servidor/estoque/relatorios/romaneios_pendentes.php"?>>Integra Compras</a>
    </div>
</h1>