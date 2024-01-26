<?php
include ("includes/config.php"); 

setcookie("u_suif");
setcookie("s_suif");
setcookie("n_suif");
setcookie("filial_suif");
setcookie ("nome_filial");

include ("includes/desconecta_bd.php");

header ("Location: $servidor/$diretorio_servidor/index_login.php");
?>