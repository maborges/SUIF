<?php
include ("includes/config.php");
	
$filial = $_POST["filial"];
if($filial == "")
{
	header ("Location: $servidor/$diretorio_servidor/index.php");
}
else
{
	// ====== BUSCA TABELA FILIAIS ==========================================================================
	include ("includes/conecta_bd.php");
	$busca_tabela_filial = mysqli_query ($conexao, "SELECT apelido FROM filiais WHERE descricao='$filial' ORDER BY codigo");
	include ("includes/desconecta_bd.php");
	// ===============================================================================================================
		
	$aux_tabela_filial = mysqli_fetch_row($busca_tabela_filial);
	
	$nome_filial = $aux_tabela_filial[0];

	setcookie ("filial_suif", $filial, time()+43200);
	setcookie ("nome_filial", $nome_filial, time()+43200);

	header ("Location: $servidor/$diretorio_servidor/index.php");
}
?>