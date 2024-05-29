<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "relatorio_cheques";
$titulo = "Relat&oacute;rio de Cheques";
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
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<?php

// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$num_cheque = $_POST["num_cheque"];
$num_cheque_2 = $_POST["num_cheque_2"];
$codigo_pagamento = $_POST["codigo_pagamento"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$monstra_ted = $_POST["monstra_ted"];
$data_pagamento = Helpers::ConverteData($_POST["data_pagamento"]);
$valor_pagamento = Helpers::ConverteValor($_POST["valor_pagamento"]);
$banco_cheque = $_POST["banco_cheque"];
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());

// ==================================================================================================================================================
if ($botao == "editar")
{
	$editar_cheque = mysqli_query ($conexao, "UPDATE cheques SET data_pagamento='$data_pagamento', valor='$valor_pagamento', banco_cheque='$banco_cheque' WHERE numero_cheque='$num_cheque_2'");
}
else
{}



// ==================================================================================================================================================
if ($botao == "baixar")
{$baixar = mysqli_query ($conexao, "UPDATE cheques SET comp_cheque='S' WHERE codigo='$codigo_pagamento'");}
else
{}

// ==================================================================================================================================================
if ($botao == "estornar")
{$baixar = mysqli_query ($conexao, "UPDATE cheques SET comp_cheque='N' WHERE codigo='$codigo_pagamento'");}
else
{}


// ==================================================================================================================================================
if ($botao == "excluir")
{$baixar = mysqli_query ($conexao, "UPDATE cheques SET estado_registro='EXCLUIDO' WHERE codigo='$codigo_pagamento'");}
else
{}




// ==================================================================================================================================================
if ($botao == "numero")
{
$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
$linha_pagamento = mysqli_num_rows ($busca_pagamento);

// SOMAS CHEQUES  ==========================================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
}



// ==================================================================================================================================================
elseif ($botao == "editar")
{
$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque_2' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
$linha_pagamento = mysqli_num_rows ($busca_pagamento);

// SOMAS CHEQUES  ==========================================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque_2' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
}


// ==================================================================================================================================================
elseif ($botao == "excluir" and $data_inicial_aux == "")
{
$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
$linha_pagamento = mysqli_num_rows ($busca_pagamento);

// SOMAS CHEQUES  ==========================================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND numero_cheque='$num_cheque' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
}



else
{
	if ($monstra_situacao == "todas")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' 
	AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	
	// SOMAS CHEQUES  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND 
	data_pagamento<='$data_final' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}



	elseif ($monstra_situacao == "aberto")
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND comp_cheque='N' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	
	// SOMAS CHEQUES  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND comp_cheque='N' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}



	elseif ($monstra_situacao == "pagas")
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM cheques WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' 
	AND comp_cheque='S' AND forma_pagamento='CHEQUE' AND filial='$filial' ORDER BY data_pagamento");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	
	// SOMAS CHEQUES  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND 
	data_pagamento<='$data_final' AND comp_cheque='S' AND forma_pagamento='CHEQUE' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	
	else
	{}

}	
	
	
	// SOMAS CHEQUES BANCO DO BRASIL  ==========================================================================================
	$soma_ch_bb = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND forma_pagamento='CHEQUE' AND banco_cheque='BANCO DO BRASIL' AND comp_cheque='N' AND filial='$filial'"));
	$soma_ch_bb_print = number_format($soma_ch_bb[0],2,",",".");

	// SOMAS CHEQUES BANESTES  ==========================================================================================
	$soma_ch_banestes = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND forma_pagamento='CHEQUE' AND banco_cheque='BANESTES' AND comp_cheque='N' AND filial='$filial'"));
	$soma_ch_banestes_print = number_format($soma_ch_banestes[0],2,",",".");

	// SOMAS CHEQUES SICOOB  ==========================================================================================
	$soma_ch_sicoob = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM cheques WHERE estado_registro!='EXCLUIDO' AND forma_pagamento='CHEQUE' AND banco_cheque='SICOOB' AND comp_cheque='N' AND filial='$filial'"));
	$soma_ch_sicoob_print = number_format($soma_ch_sicoob[0],2,",",".");


// ==================================================================================================================================================


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
	<div id="centro" style="height:25px; width:350px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:345px; height:8px; float:left; border:0px solid #999"></div>
	<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/relatorio_cheques.php" method="post" />
	<input type='hidden' name='botao' value='numero' />

	&#160;&#160;&#8226; <b>Relat&oacute;rio de Cheques</b>&#160;&#160;&#160;&#160;&#160;&#160; <i style="font-size:11px">N&ordm;: </i>
	<input type="text" name="num_cheque" id="ok" style="color:#0000FF; width:60px" value="<?php echo"$num_cheque"; ?>" />
	</form>
	
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:120px; float:left; height:20px; color:#0000FF; border:0px solid #999; text-align:right">
		<div id="geral" style="width:115px; height:8px; float:left; border:0px solid #999"></div>
		<i><!-- Outras buscas:&#160; --></i></div>

		<div id="centro" style="width:475px; float:left; height:20px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:470px; height:8px; float:left; border:0px solid #999"></div>
		<div id="menu_atalho">		
<!--
			<div id="geral" style="margin-right:20px; margin-left:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_data.php">&#8226; Data</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_periodo.php">&#8226; Per&iacute;odo</a></div>			
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_produto.php">&#8226; Produto</a></div>

			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_vendedor.php">&#8226; Vendedor</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_numero.php">&#8226; N&ordm; Compra</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="http://www.suif.com.br/<?php // echo"$diretorio_servidor"; ?>/compras/produtos/relatorio_filial.php">&#8226; Filial</a></div>
-->
		</div>
		</div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/relatorio_cheques.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>

		
		<div id="centro" style="width:70px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:65px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todos</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todos</i>";}
			?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			?>
		</div>
		
		<div id="centro" style="width:110px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:105px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "pagas")
			{echo "<input type='radio' name='monstra_situacao' value='pagas' checked='checked' /><i>Compensados</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagas' /><i>Compensados</i>";}
			?>
		</div>

		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			/*
			if ($monstra_ted == "TED")
			{echo "<input type='checkbox' name='monstra_ted' value='TED' checked='checked'><i>TED</i>";}
			else
			{echo "<input type='checkbox' name='monstra_ted' value='TED'><i>TED</i>";}
			*/
			?>
		</div>


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
	if ($linha_pagamento >= 1)
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
	if ($linha_pagamento == 1)
	{echo"<i><b>$linha_pagamento</b> Cheque</i>";}
	elseif ($linha_pagamento == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_pagamento</b> Cheques</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_pagamento >= 1)
	{echo"TOTAL: <b>R$ $soma_pagamentos_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->



<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:150px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
	Cheques a compensar:
	</div>

	<div id="centro" style="width:300px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	echo"Sicoob: <b>R$ $soma_ch_sicoob_print</b>";
	?>
	</div>
	
	<div id="centro" style="width:300px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	echo"Banco do Brasil: <b>R$ $soma_ch_bb_print</b>";
	?>
	</div>

	<div id="centro" style="width:300px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	echo"Banestes: <b>R$ $soma_ch_banestes_print</b>";
	?>
	</div>
</div>
<!-- ====================================================================================== -->




<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_pagamento == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_pagamento == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='290px' align='center' bgcolor='#006699'>Favorecido</td>
<td width='75px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='135px' align='center' bgcolor='#006699'>Banco</td>
<td width='130px' align='center' bgcolor='#006699'>N&ordm; Cheque</td>
<td width='95px' align='center' bgcolor='#006699'>Valor</td>
<td width='54px' align='center' bgcolor='#006699'>Baixar</td>
<td width='54px' align='center' bgcolor='#006699'>Estornar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
<td width='54px' align='center' bgcolor='#006699'>2&ordf; Via</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($w=1 ; $w<=$linha_pagamento ; $w++)
{
	$aux_favorecido = mysqli_fetch_row($busca_pagamento);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$cheque_comp = $aux_favorecido[19];
	
	
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
	$aux_f2 = mysqli_fetch_row($busca_favorecido_2);
	
	$codigo_pessoa_2 = $aux_f2[1];
	$banco_2 = $aux_f2[2];
	$agencia_2 = $aux_f2[3];
	$conta_2 = $aux_f2[4];
	$tipo_conta_2 = $aux_f2[5];
	$conta_conjunta = $aux_f2[15];
	
	$busca_banco_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
	$aux_b2 = mysqli_fetch_row($busca_banco_2);
	$banco_print_2 = $aux_b2[3];
	
	if ($tipo_conta_2 == "corrente")
	{$tipo_conta_print_2 = "C/C";}
	elseif ($tipo_conta_2 == "poupanca")
	{$tipo_conta_print_2 = "P";}
	else
	{$tipo_conta_print_2 = "C.";}
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_p2[1];
	$tipo_pessoa_2 = $aux_p2[2];
		if ($tipo_pessoa_2 == "pf" or $tipo_pessoa_2 == "PF")
		{$cpf_cnpj_2 = $aux_p2[3];}
		else
		{$cpf_cnpj_2 = $aux_p2[4];}
		
	$valor_pagamento_print_2 = number_format($aux_favorecido[5],2,",",".");

// FORMA DE PAGAMENTO =========================
	if ($aux_favorecido[3] == "DINHEIRO")
	{$forma_pagamento_2 = "Dinheiro";}
	elseif ($aux_favorecido[3] == "CHEQUE")
	{$forma_pagamento_2 = "Cheque";}
	elseif ($aux_favorecido[3] == "TED")
	{$forma_pagamento_2 = "Transfer&ecirc;ncia";}
	else
	{$forma_pagamento_2 = "-";}
	
// DADOS BANCARIOS =========================
	if ($aux_favorecido[3] == "CHEQUE")
	{$dados_bancarios_2 = " $aux_favorecido[6]";}
	elseif ($aux_favorecido[3] == "TED")
	{$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";}
	elseif ($aux_favorecido[3] == "DINHEIRO")
	{$dados_bancarios_2 = "";}
	else
	{$dados_bancarios_2 = "-";}



// RELATORIO =========================
	if ($cheque_comp != "S")
	{echo "<tr style='color:#333' title=''>";}
	else
	{echo "<tr style='color:#00F' title=''>";}

	echo "
	<td width='70px' align='left'>&#160;$data_pagamento_print_2</td>
	<td width='290px' align='left'>&#160;$nome_favorecido_2</td>
	<td width='75px' align='center'>$forma_pagamento_2</td>
	<td width='135px' align='center'>$aux_favorecido[6]</td>
	<td width='130px' align='center'>$aux_favorecido[18]</td>
	<td width='95px' align='right'>$valor_pagamento_print_2&#160;</td>";



	if ($cheque_comp != "S")
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/relatorio_cheques.php' method='post'>
	<input type='hidden' name='codigo_pagamento' value='$aux_favorecido[0]'>
	<input type='hidden' name='num_cheque' value='$aux_favorecido[18]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='baixar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='monstra_ted' value='$monstra_ted'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/ok.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}

	
	if ($cheque_comp == "S")
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/relatorio_cheques.php' method='post'>
	<input type='hidden' name='codigo_pagamento' value='$aux_favorecido[0]'>
	<input type='hidden' name='num_cheque' value='$aux_favorecido[18]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='estornar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='monstra_ted' value='$monstra_ted'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}


	echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/relatorio_cheques.php' method='post'>
	<input type='hidden' name='codigo_pagamento' value='$aux_favorecido[0]'>
	<input type='hidden' name='num_cheque' value='$aux_favorecido[18]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='excluir'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='monstra_ted' value='$monstra_ted'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' /></form>	
	</td>";

	
	echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/compras/copia_cheque.php' method='post' target='_blank'>
	<input type='hidden' name='num_cheque' value='$aux_favorecido[18]'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' /></form>	
	</td>";
	
	
	
	
	echo "
	</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_pagamento == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum cheque encontrado.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:40px; width:1080px; border:0px solid #000; margin:auto">
	<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/relatorio_cheques.php" method="post" />
	<input type='hidden' name='botao' value='editar' />

	&#160;&#160;&#160;&#160;<i style="font-size:11px">Editar cheque -  </i>

	&#160;&#160;&#160;&#160;<i style="font-size:11px">N&ordm;: </i>
	<input type="text" name="num_cheque_2" style="color:#0000FF; width:60px" />

	&#160;&#160;&#160;&#160; <i style="font-size:11px">Data: </i>
	<input type='text' name='data_pagamento' maxlength='10' onkeypress='mascara(this,data)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:90px; font-size:12px' />

	&#160;&#160;&#160;&#160;<i style="font-size:11px">Valor: </i>
	<input type='text' name='valor_pagamento' maxlength='15' onkeypress='mascara(this,mvalor)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:90px; font-size:12px' />

	&#160;&#160;&#160;&#160;<i style="font-size:11px">Banco: </i>
	<select name="banco_cheque" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:128px; height:20px; font-size:11px" />
	<option></option>
	<option value='BANCO DO BRASIL'>BANCO DO BRASIL</option>
	<option value='BANESTES'>BANESTES</option>
	<option value='SICOOB'>SICOOB</option>
	</select>


	&#160;&#160;&#160;&#160;
	<input type="submit" value="Salvar" />
	</form>

</div>

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