<?php

// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include_once("../../helpers.php");
$pagina = 'extrato_ficha_impressao';
$titulo = 'Movimenta&ccedil;&atilde;o Ficha do Produtor';
$modulo = 'compras';
$menu = 'ficha_produtor';
// ================================================================================================================


//Direciona o sistema para a p�gina de MANUTEN��O
header ("Location: $servidor/$diretorio_servidor/pagina_manutencao.php");

// ====== RECEBE POST ==============================================================================================
$hoje = date('Y-m-d', time());
$data_hoje = date('d/m/Y', time());
$mes_atras = date ('d/m/Y', strtotime('-60 days')); // 2 m�ses atras

$filial = $filial_usuario;
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$botao = $_POST["botao"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$dia_atras = date('Y-m-d', strtotime('-1 days', strtotime($data_inicial)));
$mostra_cancelada = $_POST["mostra_cancelada"];

if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf")
{$cpf_cnpj = "CPF: " . $aux_forn[3];}
elseif ($aux_forn[2] == "pj")
{$cpf_cnpj = "CNPJ: " . $aux_forn[4];}
else
{$cpf_cnpj = "";}

if ($linhas_fornecedor == 0)
{$cid_uf_fornecedor = "";}
else
{$cid_uf_fornecedor = $cidade_fornecedor . "-" . $estado_fornecedor;}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
$un_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA E SOMA COMPRAS ========================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' ORDER BY data_compra");
$linha_compra = mysqli_num_rows ($busca_compra);
	
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' "));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// ======================================================================================================


// ====== SALDO PRODUTOR =================================================================================
// ------ SOMA QUANTIDADE DE ENTRADA (PER�ODO) --------------------------------------------------------------------
$soma_quant_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_e_print = number_format($soma_quant_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SA�DA (PER�ODO) -----------------------------------------------------------------------
$soma_quant_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_s_print = number_format($soma_quant_produto_s[0],2,",",".");

// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SA�DA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0],2,",",".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto,2,",",".");
// ======================================================================================================


// ================================================================================================================
include ('../../includes/head_impressao.php');
?>


<!-- ==================================   T � T U L O   D A   P � G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N � C I O   =============================================== -->
<body onLoad="imprimir()">

<div id="centro" style="width:745px; border:0px solid #F00">

<?php
/*
// =================================================================================================================
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	

// SOMA COMPRAS  ==========================================================================================
	$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' "));
	$soma_compras_print = number_format($soma_compras[0],2,",",".");

// SOMA SALDO ANTERIOR  ==========================================================================================
	$soma_ant_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra<='$dia_atras' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
	$soma_ant_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra<='$dia_atras' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
	$saldo_ant = ($soma_ant_e[0] - $soma_ant_s[0]);
	$saldo_ant_print = number_format($saldo_ant,2,",",".");



// SOMA CAFE  ==========================================================================================
	$soma_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CAFE' "));
	$soma_cafe_print = number_format($soma_compra_cafe[0],2,",",".");

	$soma_quant_cafe_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CAFE' "));
	$quant_cafe_e_print = number_format($soma_quant_cafe_e[0],2,",",".");
	$soma_quant_cafe_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CAFE' "));
	$quant_cafe_s_print = number_format($soma_quant_cafe_s[0],2,",",".");

	$soma_quant_total_cafe_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CAFE' "));
	$quant_cafe_total_e_print = number_format($soma_quant_total_cafe_e[0],2,",",".");
	$soma_quant_total_cafe_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CAFE' "));
	$quant_cafe_total_s_print = number_format($soma_quant_total_cafe_s[0],2,",",".");
	$saldo_geral_cafe = ($soma_quant_total_cafe_e[0] - $soma_quant_total_cafe_s[0]);
	$saldo_geral_cafe_print = number_format($saldo_geral_cafe,2,",",".");

		if ($soma_quant_cafe_e[0] <= 0)
		{$media_cafe_print = "0,00";}
		else
		{$media_cafe = ($soma_compra_cafe[0] / $soma_quant_cafe_e[0]);
		$media_cafe_print = number_format($media_cafe,2,",",".");}
	

// SOMAS PIMENTA  ==========================================================================================
	$soma_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='PIMENTA' "));
	$soma_pimenta_print = number_format($soma_compra_pimenta[0],2,",",".");

	$soma_quant_pimenta_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='PIMENTA' "));
	$quant_pimenta_e_print = number_format($soma_quant_pimenta_e[0],2,",",".");
	$soma_quant_pimenta_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='PIMENTA' "));
	$quant_pimenta_s_print = number_format($soma_quant_pimenta_s[0],2,",",".");

	$soma_quant_total_pimenta_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='PIMENTA' "));
	$quant_pimenta_total_e_print = number_format($soma_quant_total_pimenta_e[0],2,",",".");
	$soma_quant_total_pimenta_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='PIMENTA' "));
	$quant_pimenta_total_s_print = number_format($soma_quant_total_pimenta_s[0],2,",",".");
	$saldo_geral_pimenta = ($soma_quant_total_pimenta_e[0] - $soma_quant_total_pimenta_s[0]);
	$saldo_geral_pimenta_print = number_format($saldo_geral_pimenta,2,",",".");

		if ($soma_quant_pimenta_e[0] <= 0)
		{$media_pimenta_print = "0,00";}
		else
		{$media_pimenta = ($soma_compra_pimenta[0] / $soma_quant_pimenta_e[0]);
		$media_pimenta_print = number_format($media_pimenta,2,",",".");}


// SOMAS CACAU  ==========================================================================================
	$soma_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CACAU' "));
	$soma_cacau_print = number_format($soma_compra_cacau[0],2,",",".");

	$soma_quant_cacau_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CACAU' "));
	$quant_cacau_e_print = number_format($soma_quant_cacau_e[0],2,",",".");
	$soma_quant_cacau_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CACAU' "));
	$quant_cacau_s_print = number_format($soma_quant_cacau_s[0],2,",",".");

	$soma_quant_total_cacau_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CACAU' "));
	$quant_cacau_total_e_print = number_format($soma_quant_total_cacau_e[0],2,",",".");
	$soma_quant_total_cacau_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CACAU' "));
	$quant_cacau_total_s_print = number_format($soma_quant_total_cacau_s[0],2,",",".");
	$saldo_geral_cacau = ($soma_quant_total_cacau_e[0] - $soma_quant_total_cacau_s[0]);
	$saldo_geral_cacau_print = number_format($saldo_geral_cacau,2,",",".");

		if ($soma_quant_cacau_e[0] <= 0)
		{$media_cacau_print = "0,00";}
		else
		{$media_cacau = ($soma_compra_cacau[0] / $soma_quant_cacau_e[0]);
		$media_cacau_print = number_format($media_cacau,2,",",".");}


// SOMAS CRAVO  ==========================================================================================
	$soma_compra_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CRAVO' "));
	$soma_cravo_print = number_format($soma_compra_cravo[0],2,",",".");

	$soma_quant_cravo_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CRAVO' "));
	$quant_cravo_e_print = number_format($soma_quant_cravo_e[0],2,",",".");
	$soma_quant_cravo_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CRAVO' "));
	$quant_cravo_s_print = number_format($soma_quant_cravo_s[0],2,",",".");

	$soma_quant_total_cravo_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CRAVO' "));
	$quant_cravo_total_e_print = number_format($soma_quant_total_cravo_e[0],2,",",".");
	$soma_quant_total_cravo_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='CRAVO' "));
	$quant_cravo_total_s_print = number_format($soma_quant_total_cravo_s[0],2,",",".");
	$saldo_geral_cravo = ($soma_quant_total_cravo_e[0] - $soma_quant_total_cravo_s[0]);
	$saldo_geral_cravo_print = number_format($saldo_geral_cravo,2,",",".");

		if ($soma_quant_cravo_e[0] <= 0)
		{$media_cravo_print = "0,00";}
		else
		{$media_cravo = ($soma_compra_cravo[0] / $soma_quant_cravo_e[0]);
		$media_cravo_print = number_format($media_cravo,2,",",".");}



// SOMAS RESIDUO_CACAU  ==========================================================================================
	$soma_compra_residuo_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='RESIDUO_CACAU' "));
	$soma_residuo_cacau_print = number_format($soma_compra_residuo_cacau[0],2,",",".");

	$soma_quant_residuo_cacau_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='RESIDUO_CACAU' "));
	$quant_residuo_cacau_e_print = number_format($soma_quant_residuo_cacau_e[0],2,",",".");
	$soma_quant_residuo_cacau_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='RESIDUO_CACAU' "));
	$quant_residuo_cacau_s_print = number_format($soma_quant_residuo_cacau_s[0],2,",",".");

	$soma_quant_total_residuo_cacau_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='RESIDUO_CACAU' "));
	$quant_residuo_cacau_total_e_print = number_format($soma_quant_total_residuo_cacau_e[0],2,",",".");
	$soma_quant_total_residuo_cacau_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND produto='RESIDUO_CACAU' "));
	$quant_residuo_cacau_total_s_print = number_format($soma_quant_total_residuo_cacau_s[0],2,",",".");
	$saldo_geral_residuo_cacau = ($soma_quant_total_residuo_cacau_e[0] - $soma_quant_total_residuo_cacau_s[0]);
	$saldo_geral_residuo_cacau_print = number_format($saldo_geral_residuo_cacau,2,",",".");

		if ($soma_quant_residuo_cacau_e[0] <= 0)
		{$media_residuo_cacau_print = "0,00";}
		else
		{$media_residuo_cacau = ($soma_compra_residuo_cacau[0] / $soma_quant_residuo_cacau_e[0]);
		$media_residuo_cacau_print = number_format($media_residuo_cacau,2,",",".");}



// ===========================================================================================================
// ===========================================================================================================



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa_e = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa_e = mysqli_num_rows ($busca_pessoa_e);
	for ($e=1 ; $e<=$linha_pessoa_e ; $e++)
	{
	$aux_pessoa_e = mysqli_fetch_row($busca_pessoa_e);
	$produtor_print = $aux_pessoa_e[1];
		if ($aux_pessoa_e[2] == "pf")
		{$cpf_cnpj_aux = $aux_pessoa_e[3];}
		else
		{$cpf_cnpj_aux = $aux_pessoa_e[4];}
	}



// BUSCA CONTROLE DE TALAO  ==========================================================================================
$busca_talao = mysqli_query ($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$fornecedor' AND devolvido='N' AND filial='$filial' ORDER BY codigo");
$linha_talao = mysqli_num_rows ($busca_talao);

// BUSCA CONTRATO FUTURO  ==========================================================================================
$busca_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO' AND filial='$filial' ORDER BY codigo");
$linha_futuro = mysqli_num_rows ($busca_futuro);

// BUSCA CONTRATO TRATADO  ==========================================================================================
$busca_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO' AND filial='$filial' ORDER BY codigo");
$linha_tratado = mysqli_num_rows ($busca_tratado);
// ========================================================================================================



*/



	

// ##############################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecer� em cada p�gina de impress�o #######
// #######           � importante sempre testar antes para ver quantas linhas s�o necess�rias             #######
// ############################################################################################################## 
/*
$limite_registros = 44;
$numero_paginas = ceil($linha_compra / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "<div id='centro' style='width:740px; height:1065px; border:0px solid #000; page-break-after:always'>";
	

*/


echo "
<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	FICHA DO PRODUTOR - EXTRATO DE MOVIMENTA&Ccedil;&Otilde;ES<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$produto_print_2</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>

<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:5px; margin-left:10px; font-size:12px' align='center'>

	<div id='centro' style='width:500px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Nome:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$produtor_print</div>
	</div>

	<div id='centro' style='width:215px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>CPF/CNPJ:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$cpf_cnpj_aux</div>
	</div>

	<div id='centro' style='width:500px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Cidade:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$aux_pessoa_e[10] - $aux_pessoa_e[12]</div>
	</div>

	<div id='centro' style='width:215px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Telefone:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$aux_pessoa_e[14]</div>
	</div>

</div>




<!-- =================================================================================================================== -->

<div id='centro' style='width:680px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	if ($monstra_situacao == "")
	{echo "<i>Per&iacute;odo: <b>GERAL</b></i>";}
	else
	{echo "<i>Per&iacute;odo: <b>$data_inicial_aux</b> at&eacute; <b>$data_final_aux</b></i>";}
	
	echo "
	
	</div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_compra == 1)
	{echo"<i>$linha_compra Registro</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i>$linha_compra Registros</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Data</i></div>
	
	<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Movimenta&ccedil;&atilde;o</i></div>
	
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>N&ordm;</i></div>
	
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Produto</i></div>

	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Tipo</i></div>
	
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Quantidade</i></div>
	
	<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Pre&ccedil;o Un</i></div>
	
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Saldo</i></div>
	
</div>

<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>
	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'></div>
	<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
	background-color:#FFF; text-transform:uppercase;'>
	&#160;&#160;<i>SALDO ANTERIOR</i></div>
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'></div>
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>";
	echo"<i>$saldo_ant_print&#160;</i>";
	echo"
	</div>
</div>";







for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$num_compra_print = $aux_compra[1];
$produto = $aux_compra[3];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$movimentacao = $aux_compra[16];
$observacao = $aux_compra[13];
$numero_romaneio = $aux_compra[28];
$desc_quant = number_format($aux_compra[29],2,",",".");
$numero_transferencia = $aux_compra[30];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];

/*
// PRODUTO PRINT  ==========================================================================================
$busca_produto_print = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND apelido='$produto' ORDER BY codigo");
$linha_produto_print = mysqli_num_rows ($busca_produto_print);
	for ($p=1 ; $p<=$linha_produto_print ; $p++)
	{
		$aux_produto_print = mysqli_fetch_row($busca_produto_print);
		if ($linha_produto_print == 0)
		{$produto_print = "-";}
		else
		{$produto_print = $aux_produto_print[22];}
	}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{	
		if ($quantidade <= 1)
			{$unidade_print = "Sc";}
		else	
		{$unidade_print = "Sc";}
	}
	elseif ($unidade == "KG")
	{
		if ($quantidade <= 1)
		{$unidade_print = "Kg";}
		else	
		{$unidade_print = "Kg";}
	}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUA��O PRINT  ==========================================================================================
	if ($situacao == "ENTREGUE")
	{$situacao_print = "ENTREGUE";}
	elseif ($situacao == "A_ENTREGAR")
	{$situacao_print = "A ENTREGAR";}
	elseif ($situacao == "ARMAZENADO")
	{$situacao_print = "ARMAZENADO";}
	else
	{$situacao_print = "-";}
*/

// MOVIMENTACAO PRINT  ==========================================================================================
	if ($movimentacao == "COMPRA")
	{$movimentacao_print = "Compra";}
	elseif ($movimentacao == "ENTRADA")
	{$movimentacao_print = "Entrada";}
	elseif ($movimentacao == "TRANSFERENCIA_ENTRADA")
	{$movimentacao_print = "Transfer&ecirc;ncia - Entrada";}
	elseif ($movimentacao == "ENTRADA_FUTURO")
	{$movimentacao_print = "Entrada - Contrato Futuro";}
	elseif ($movimentacao == "TRANSFERENCIA_SAIDA")
	{$movimentacao_print = "Transfer&ecirc;ncia - Sa&iacute;da";}
	elseif ($movimentacao == "SAIDA_FUTURO")
	{$movimentacao_print = "Sa&iacute;da - Pgto Contrato Futuro";}
	elseif ($movimentacao == "SAIDA")
	{$movimentacao_print = "Sa&iacute;da";}
	else
	{$movimentacao_print = "-";}


// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "pf")
		{$cpf_cnpj = $aux_pessoa[3];}
		else
		{$cpf_cnpj = $aux_pessoa[4];}
	}


// BUSCA PAGAMENTO  ==========================================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");


// CALCULO SALDO A PAGAR  ==========================================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");
// =============================================================
// ============================================================================================================


// CALCULO SALDO QUANTIDADE  ==========================================================================================
if ($movimentacao == "COMPRA" or $movimentacao == "TRANSFERENCIA_SAIDA" or $movimentacao == "SAIDA" or $movimentacao == "SAIDA_FUTURO")
{$saldo_quant = $saldo_ant - $quantidade;}
else
{$saldo_quant = $saldo_ant + $quantidade;}
$saldo_quant_print = number_format($saldo_quant,2,",",".");
$saldo_ant = $saldo_quant;
// =============================================================
// ============================================================================================================


	if ($aux_compra[0] == "")
	{
	echo "
	<div id='centro' style='width:675px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
		&#160;&#160;$data_compra_print</div>
		
		<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		&#160;&#160;$movimentacao_print</div>
		
		<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$num_compra_print</div>
		
		<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$produto_print</div>

		<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$tipo</div>
		
		<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$quantidade_print $unidade_print</div>
		
		<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		$preco_unitario_print&#160;</div>
		
		<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		$saldo_quant_print&#160;</div>
		
	</div>";
	}

// =====================================
}



// =============================
//$x = ($x + $limite_registros);
// =============================




if ($linha_compra == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='785px' height='15px' align='left'>&#160;&#160;<i>Nenhum registro encontrado.</i></td></tr>";}






echo "


</div>

<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:60px; border:0px solid #000; margin-left:10px; float:left; border-radius:7px;' align='center'>";


	if ($soma_compra_cafe[0] > 0)
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b></b>	
		</div>
		<div id='centro' style='height:15px; width:200px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Entradas: $quant_cafe_e_print Sacas
		</div>
		<div id='centro' style='height:15px; width:250px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Compras / Transfer&ecirc;ncias: $quant_cafe_s_print Sacas
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		Saldo Atual: $saldo_geral_cafe_print Sacas
		</div>
	</div>";}
	
	if ($soma_compra_pimenta[0] > 0)
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b></b>	
		</div>
		<div id='centro' style='height:15px; width:200px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Entradas: $quant_pimenta_e_print Kg
		</div>
		<div id='centro' style='height:15px; width:250px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Compras / Transfer&ecirc;ncias: $quant_pimenta_s_print Kg
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		Saldo Atual: $saldo_geral_pimenta_print Kg
		</div>
	</div>";}

	if ($soma_compra_cacau[0] > 0)
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b></b>	
		</div>
		<div id='centro' style='height:15px; width:200px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Entradas: $quant_cacau_e_print Kg
		</div>
		<div id='centro' style='height:15px; width:250px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Compras / Transfer&ecirc;ncias: $quant_cacau_s_print Kg
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		Saldo Atual: $saldo_geral_cacau_print Kg
		</div>
	</div>";}

	if ($soma_compra_cravo[0] > 0)
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b></b>	
		</div>
		<div id='centro' style='height:15px; width:200px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Entradas: $quant_cravo_e_print Kg
		</div>
		<div id='centro' style='height:15px; width:250px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Compras / Transfer&ecirc;ncias: $quant_cravo_s_print Kg
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		Saldo Atual: $saldo_geral_cravo_print Kg
		</div>
	</div>";}



	echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b></b>	
		</div>
		<div id='centro' style='height:15px; width:300px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		Total de Compras: R$ $soma_compras_print
		</div>
		<div id='centro' style='height:15px; width:150px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		
		</div>
	</div>";





echo "
</div>


<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #f85; float:left; margin-left:10px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>
	SUIF - GRANCAF&Eacute;</div>
	<div id='centro' style='width:330px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'></div>

	<div id='centro' style='width:200px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
	$nome_usuario_print</div>
</div>
<!-- =============================================================================================== -->

<!-- ####################################################################### -->";
/*
echo "</div>"; // quebra de p�gina
} // fim do primeiro "FOR"
*/
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->