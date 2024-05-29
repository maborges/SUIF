<?php
// ====== BUSCA COOKIES ==========================================================================================
if(isset($_COOKIE["u_suif"]))
	$username = $_COOKIE["u_suif"];
if(isset($_COOKIE["s_suif"]))
	$senha_usuario = $_COOKIE["s_suif"];
if(isset($_COOKIE["n_suif"]))
	$nome_usuario = $_COOKIE["n_suif"];
if(isset($_COOKIE["filial_suif"]))
	$filial_usuario = $_COOKIE["filial_suif"];
if(isset($_COOKIE["nome_filial"]))
	$nome_filial = $_COOKIE["nome_filial"];
if(isset($_COOKIE["u_sankhya"]))
	$idUserSankhya = $_COOKIE["u_sankhya"];

// ===============================================================================================================


// ====== VERIFICA SE OS COOKIES USUARIO E SENHA EXISTEM =========================================================
if(!(empty($username) or empty($senha_usuario)))
{
	include ("includes/conecta_bd.php");
	$resultado = mysqli_query($conexao, "SELECT senha FROM usuarios WHERE username='$username' AND estado_registro='ATIVO'");
	include ("includes/desconecta_bd.php");
	
	$suif_aux = mysqli_fetch_row($resultado);
	if(mysqli_num_rows($resultado)==1)
	{
		if($senha_usuario != $suif_aux[0])
		{
			header ("Location: $servidor/$diretorio_servidor/logout.php");
			exit;
		}
	}
	else
	{
		header ("Location: $servidor/$diretorio_servidor/logout.php");
		exit;
	}

}

else
{
		header ("Location: $servidor/$diretorio_servidor/logout.php");
		exit;
}
// ===============================================================================================================
?>