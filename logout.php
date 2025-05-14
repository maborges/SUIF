<?php
include ("includes/config.php"); 

setcookie("u_suif");
setcookie("u_sankhya");
setcookie("s_suif");
setcookie("n_suif");
setcookie("filial_suif");
setcookie("nome_filial");

$_SESSION = []; // Limpa todas as variáveis de sessão

include ("includes/desconecta_bd.php");

header ("Location: $servidor/$diretorio_servidor/index_login.php");
?>