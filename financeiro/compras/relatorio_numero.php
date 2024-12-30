<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../includes/desconecta_bd.php");
include ("../../helpers.php");

$pagina = "relatorio_numero";
$titulo = "Relat&oacute;rio de Compras (Financeiro)";
$menu = "contas_pagar";
$modulo = "financeiro";

include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<?php

// =================================================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$num_compra_aux = $_POST["num_compra_aux"];

$mostra_cancelada = $_POST["mostra_cancelada"];		
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}


include ("../../includes/conecta_bd.php");

$busca_compra = mysqli_query ($conexao, "SELECT numero_compra, produto, data_compra, quantidade, preco_unitario, valor_total, unidade, observacao, usuario_cadastro, hora_cadastro, data_cadastro, fornecedor_print FROM compras WHERE numero_compra='$num_compra_aux' AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

include ("../../includes/desconecta_bd.php");
// =================================================================================================================



/*
if ($monstra_situacao == "todas")
{	
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND filial='$filial' ORDER BY codigo");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS COMPRAS  ==========================================================================================
	$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND filial='$filial'"));
	$soma_compras_print = number_format($soma_compras[0],2,",",".");
	
	$soma_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='$filial'"));
	$soma_cafe_print = number_format($soma_compra_cafe[0],2,",",".");
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
		if ($soma_quant_cafe[0] <= 0)
		{$media_cafe_print = "0,00";}
		else
		{$media_cafe = ($soma_compra_cafe[0] / $soma_quant_cafe[0]);
		$media_cafe_print = number_format($media_cafe,2,",",".");}
	
	$soma_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='$filial'"));
	$soma_pimenta_print = number_format($soma_compra_pimenta[0],2,",",".");
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
		if ($soma_quant_pimenta[0] <= 0)
		{$media_pimenta_print = "0,00";}
		else
		{$media_pimenta = ($soma_compra_pimenta[0] / $soma_quant_pimenta[0]);
		$media_pimenta_print = number_format($media_pimenta,2,",",".");}
	
	$soma_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND filial='$filial'"));
	$soma_cacau_print = number_format($soma_compra_cacau[0],2,",",".");
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
		if ($soma_quant_cacau[0] <= 0)
		{$media_cacau_print = "0,00";}
		else
		{$media_cacau = ($soma_compra_cacau[0] / $soma_quant_cacau[0]);
		$media_cacau_print = number_format($media_cacau,2,",",".");}
	
	$soma_compra_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND filial='$filial'"));
	$soma_cravo_print = number_format($soma_compra_cravo[0],2,",",".");
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
		if ($soma_quant_cravo[0] <= 0)
		{$media_cravo_print = "0,00";}
		else
		{$media_cravo = ($soma_compra_cravo[0] / $soma_quant_cravo[0]);
		$media_cravo_print = number_format($media_cravo,2,",",".");}
}

elseif ($monstra_situacao == "aberto")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND situacao_pagamento='EM_ABERTO' AND situacao_pagamento='EM_ABERTO' AND filial='$filial' ORDER BY codigo");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS COMPRAS  ==========================================================================================
	$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_compras_print = number_format($soma_compras[0],2,",",".");
	
	$soma_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_cafe_print = number_format($soma_compra_cafe[0],2,",",".");
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
		if ($soma_quant_cafe[0] <= 0)
		{$media_cafe_print = "0,00";}
		else
		{$media_cafe = ($soma_compra_cafe[0] / $soma_quant_cafe[0]);
		$media_cafe_print = number_format($media_cafe,2,",",".");}
	
	$soma_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_pimenta_print = number_format($soma_compra_pimenta[0],2,",",".");
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
		if ($soma_quant_pimenta[0] <= 0)
		{$media_pimenta_print = "0,00";}
		else
		{$media_pimenta = ($soma_compra_pimenta[0] / $soma_quant_pimenta[0]);
		$media_pimenta_print = number_format($media_pimenta,2,",",".");}
	
	$soma_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_cacau_print = number_format($soma_compra_cacau[0],2,",",".");
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
		if ($soma_quant_cacau[0] <= 0)
		{$media_cacau_print = "0,00";}
		else
		{$media_cacau = ($soma_compra_cacau[0] / $soma_quant_cacau[0]);
		$media_cacau_print = number_format($media_cacau,2,",",".");}
	
	$soma_compra_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_cravo_print = number_format($soma_compra_cravo[0],2,",",".");
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
		if ($soma_quant_cravo[0] <= 0)
		{$media_cravo_print = "0,00";}
		else
		{$media_cravo = ($soma_compra_cravo[0] / $soma_quant_cravo[0]);
		$media_cravo_print = number_format($media_cravo,2,",",".");}
}

elseif ($monstra_situacao == "pagas")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND situacao_pagamento='PAGO' AND situacao_pagamento='PAGO' AND filial='$filial' ORDER BY codigo");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS COMPRAS  ==========================================================================================
	$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_compras_print = number_format($soma_compras[0],2,",",".");
	
	$soma_compra_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_cafe_print = number_format($soma_compra_cafe[0],2,",",".");
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CAFE' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
		if ($soma_quant_cafe[0] <= 0)
		{$media_cafe_print = "0,00";}
		else
		{$media_cafe = ($soma_compra_cafe[0] / $soma_quant_cafe[0]);
		$media_cafe_print = number_format($media_cafe,2,",",".");}
	
	$soma_compra_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_pimenta_print = number_format($soma_compra_pimenta[0],2,",",".");
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='PIMENTA' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
		if ($soma_quant_pimenta[0] <= 0)
		{$media_pimenta_print = "0,00";}
		else
		{$media_pimenta = ($soma_compra_pimenta[0] / $soma_quant_pimenta[0]);
		$media_pimenta_print = number_format($media_pimenta,2,",",".");}
	
	$soma_compra_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_cacau_print = number_format($soma_compra_cacau[0],2,",",".");
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CACAU' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
		if ($soma_quant_cacau[0] <= 0)
		{$media_cacau_print = "0,00";}
		else
		{$media_cacau = ($soma_compra_cacau[0] / $soma_quant_cacau[0]);
		$media_cacau_print = number_format($media_cacau,2,",",".");}
	
	$soma_compra_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_cravo_print = number_format($soma_compra_cravo[0],2,",",".");
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$num_compra_aux' AND movimentacao='COMPRA' AND produto='CRAVO' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
		if ($soma_quant_cravo[0] <= 0)
		{$media_cravo_print = "0,00";}
		else
		{$media_cravo = ($soma_compra_cravo[0] / $soma_quant_cravo[0]);
		$media_cravo_print = number_format($media_cravo,2,",",".");}
}

else
{}

*/
?>


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_financeiro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_financeiro_contas_pagar.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:250px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:245px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Compras</b> - Por N&uacute;mero
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:120px; float:left; height:20px; color:#0000FF; border:0px solid #999; text-align:right">
		<div id="geral" style="width:115px; height:8px; float:left; border:0px solid #999"></div>
		<!-- <i>Outros relat&oacute;rios:&#160;</i>--></div>

		<div id="centro" style="width:475px; float:left; height:20px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:470px; height:8px; float:left; border:0px solid #999"></div>
		<div id="menu_atalho">
		<!--		
			<div id="geral" style="margin-right:20px; margin-left:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_data.php">&#8226; Data</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_periodo.php">&#8226; Per&iacute;odo</a></div>			
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_produto.php">&#8226; Produto</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_numero.php">&#8226; N&uacute;mero</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_saldo.php">&#8226; Saldo dos Produtores</a></div>
		-->
		</div>
		</div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:80px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:75px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/compras/relatorio_numero.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>N&uacute;mero:&#160;</i></div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="num_compra_aux" maxlength="15" id="ok" style="color:#0000FF; width:90px" />
		</div>

<!--		
		<div id="centro" style="width:65px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:60px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todas</i>";}
			*/?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			*/?>
		</div>
		
		<div id="centro" style="width:65px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:60px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "pagas")
			{echo "<input type='radio' name='monstra_situacao' value='pagas' checked='checked' /><i>Pagas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagas' /><i>Pagas</i>";}
			*/?>
		</div>
-->
		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_compra >= 1)
	{echo"
	<!--
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='index_contas_pagar'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='botao_1' value='$botao_1'>
	<input type='hidden' name='botao_2' value='$botao_2'>	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='20px' /></form>
	-->";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->
<?php
/*
if ($soma_compra_cafe[0] == 0)
{echo "";}
else
{echo "
<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
	<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
		<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#009900'>
		<b>Caf&eacute; Conilon</b>	
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Quant. comprada: $quant_cafe_print Sacas
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Valor total: R$ $soma_cafe_print
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Pre&ccedil;o m&eacute;dia por saca: R$ $media_cafe_print
		</div>
	</div>
</div>
<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
";}

if ($soma_compra_pimenta[0] == 0)
{echo "";}
else
{echo "
<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
	<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
		<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left;; font-size:11px; color:#009900'>
		<b>Pimenta do Reino</b>	
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Quant. comprada: $quant_pimenta_print Kg
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Valor total: R$ $soma_pimenta_print
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Pre&ccedil;o m&eacute;dia por Kg: R$ $media_pimenta_print
		</div>
	</div>
</div>
<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
";}

if ($soma_compra_cacau[0] == 0)
{echo "";}
else
{echo "
<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
	<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
		<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left;; font-size:11px; color:#009900'>
		<b>Cacau</b>	
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Quant. comprada: $quant_cacau_print Kg
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Valor total: R$ $soma_cacau_print
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Pre&ccedil;o m&eacute;dia por Kg: R$ $media_cacau_print
		</div>
	</div>
</div>
<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
";}

if ($soma_compra_cravo[0] == 0)
{echo "";}
else
{echo "
<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
	<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
		<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left;; font-size:11px; color:#009900'>
		<b>Cravo da &Iacute;ndia</b>	
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Quant. comprada: $quant_cravo_print Kg
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Valor total: R$ $soma_cravo_print
		</div>
		<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
		Pre&ccedil;o m&eacute;dia por Kg: R$ $media_cravo_print
		</div>
	</div>
</div>
<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
";}
*/

?>







<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_compra == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_compra == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='80px' align='center' bgcolor='#006699'>Data</td>
<td width='325px' align='center' bgcolor='#006699'>Vendedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='85px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='85px' align='center' bgcolor='#006699'>Pre&ccedil;o Un</td>
<td width='95px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='64px' align='center' bgcolor='#006699'>Via Saldo</td>
<td width='64px' align='center' bgcolor='#006699'>Via Fin.</td>
<td width='64px' align='center' bgcolor='#006699'>Pgto</td>
<!--<td width='54px' align='center' bgcolor='#006699'></td> -->
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$num_compra_print = $aux_compra[0];
$produto_print = $aux_compra[1];
$data_compra = $aux_compra[2];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[2]));
$quantidade = $aux_compra[3];
$quantidade_print = number_format($aux_compra[3],2,",",".");
$preco_unitario = $aux_compra[4];
$preco_unitario_print = number_format($aux_compra[4],2,",",".");
$valor_total = $aux_compra[5];
$valor_total_print = number_format($aux_compra[5],2,",",".");
$unidade_print = $aux_compra[6];
$observacao = $aux_compra[7];
$fornecedor_print = $aux_compra[11];

$usuario_cadastro = $aux_compra[8];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[10]));
$hora_cadastro = $aux_compra[9];






/*

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

// CALCULO QUANTIDADE A ENTREGAR  ==========================================================================================
$quant_entregar = $saldo_a_pagar_aux / $preco_unitario;
$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
$quant_entregar_print = number_format($quant_entregar_aux,2,",",".");
*/



// RELATORIO =========================
/*	if ($soma_pagamentos[0] < $valor_total)
	{echo "<tr style='color:#333' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";}
	else
*/
	echo "<tr style='color:#00F' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";
	
	echo "
	<td width='80px' align='left'>&#160;$data_compra_print</td>
	<td width='325px' align='left'>&#160;$fornecedor_print</td>
	<td width='60px' align='center'>$num_compra_print</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='85px' align='center'>$quantidade_print $unidade_print</td>
	<td width='85px' align='right'>$preco_unitario_print&#160;</td>
	<td width='95px' align='right'>$valor_total_print&#160;</td>
	
	<td width='64px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/compras/compra_impressao_2.php' method='post' target='_blank' />
	<input type='hidden' name='numero_compra' value='$num_compra_print'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' /></form>	
	</td>

	<td width='64px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/compras/compra_impressao.php' method='post' target='_blank' />
	<input type='hidden' name='numero_compra' value='$num_compra_print'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' /></form>	
	</td>";


	
	echo "
	<td width='64px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post' />
	<input type='hidden' name='numero_compra' value='$num_compra_print'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao_relatorio' value='financeiro'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/financeiro_1.png' height='20px' /></form>	
	</td>";

	echo "
	<!-- <td width='64px' align='center'></td> -->
	
	</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_compra == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma compra encontrada.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>