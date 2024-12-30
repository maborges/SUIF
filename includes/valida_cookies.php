<?php
include("conecta_bd.php"); 

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
if(isset($_COOKIE['idFilialArmazenagem']))
	$idFilialArmazenagem = $_COOKEI['idFilialArmazenagem'];


// ===============================================================================================================


// ------- PROVISÓRIO (duplicidade de variáveis) -----------------------------------------------------------------
$nome_usuario_print = $username;
// ---------------------------------------------------------------------------------------------------------------


// ====== VERIFICA SE OS COOKIES USUARIO E SENHA EXISTEM =========================================================
if(empty($username) or empty($senha_usuario))
{
	header ("Location: $servidor/$diretorio_servidor/logout.php");
	exit;
}
// ===============================================================================================================


// ====== BUSCA CONFIGURAÇÕES DO SISTEMA =========================================================================
$busca_config = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$config = mysqli_fetch_row($busca_config);
$estoqueMascara = $config[30];
// ===============================================================================================================


// ====== BUSCA PERMISSÕES DE USUÁRIOS ===========================================================================
$busca_permissao = mysqli_query ($conexao, "SELECT * FROM usuarios_permissoes WHERE username='$username'");
$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================


// ====== BUSCA CONFIGURAÇÕES DE FILIAL ==========================================================================
$busca_filial_config = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial_usuario'");
$filial_config = mysqli_fetch_row($busca_filial_config);
// ===============================================================================================================

?>