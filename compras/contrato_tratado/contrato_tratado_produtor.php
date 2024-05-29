<?php
	include ('../../includes/config.php');
	include ('../../includes/head.php'); 
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	include_once("../../helpers.php");

	$pagina = 'contrato_tratado_produtor';
	$menu = 'contratos';
	$titulo = 'Contratos Tratados';
	$modulo = 'compras';

// ====== CONTADOR CÓDIGO COMPRA =======================================================================
/*
$busca_numero_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$numero_compra = mysqli_result ($busca_numero_compra, 0 , "contador_numero_compra");
$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
*/
// ======================================================================================================




?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<?php

// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$codigo_contrato = $_POST["codigo_contrato"];
$numero_contrato = $_POST["numero_contrato"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$botao = $_POST["botao"];
$monstra_situacao = $_POST["monstra_situacao"];

$produtor = $_POST["produtor"];
$produto = $_POST["produto"];
$produto_list = $_POST["produto"];
$data_contrato = $_POST["data_contrato"];
$quantidade_adquirida = $_POST["quantidade_adquirida"];
$quantidade_a_entregar = $_POST["quantidade_a_entregar"];
$unidade = $_POST["unidade"];
$tipo = $_POST["tipo"];
$obs = $_POST["obs"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());

//$monstra_ted = $_POST["monstra_ted"];	

// ==================================================================================================================================================
if ($botao == "baixar")
{
$obs_aux = "N&ordm; Contrato: " . $numero_contrato . " - " . $obs;
$baixar = mysqli_query ($conexao, "UPDATE contrato_tratado SET situacao_contrato='PAGO' WHERE codigo='$codigo_contrato'");
}

else
{}

// ==================================================================================================================================================
if ($botao == "estornar")
{
$estornar = mysqli_query ($conexao, "UPDATE contrato_tratado SET situacao_contrato='EM_ABERTO' WHERE codigo='$codigo_contrato'");
}

else
{}

// ==================================================================================================================================================
if ($botao == "excluir")
{
$excluir = mysqli_query ($conexao, "UPDATE contrato_tratado SET estado_registro='EXCLUIDO' WHERE codigo='$codigo_contrato'");
}

else
{}


// ==================================================================================================================================================
if ($botao == "tirar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_tratado SET pendencia='NAO' WHERE codigo='$codigo_contrato'");
}
elseif ($botao == "colocar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_tratado SET pendencia='SIM' WHERE codigo='$codigo_contrato'");
}
else
{}



// ==================================================================================================================================================
	if ($monstra_situacao == "todos")
	{	
	$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, codigo");
	$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);

	// SOMAS CONTRATOS CAFE  ==========================================================================================
	$soma_tratados_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CAFE' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_cafe_print = number_format($soma_tratados_cafe[0],2,",",".");

	// SOMAS CONTRATOS PIMENTA  ==========================================================================================
	$soma_tratados_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='PIMENTA' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_pimenta_print = number_format($soma_tratados_pimenta[0],2,",",".");

	// SOMAS CONTRATOS CACAU  ==========================================================================================
	$soma_tratados_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CACAU' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_cacau_print = number_format($soma_tratados_cacau[0],2,",",".");

	// SOMAS CONTRATOS CRAVO  ==========================================================================================
	$soma_tratados_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CRAVO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_cravo_print = number_format($soma_tratados_cravo[0],2,",",".");
	}



	elseif ($monstra_situacao == "aberto")
	{	
	$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='EM_ABERTO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, codigo");
	$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
	
	// SOMAS CONTRATOS CAFE  ==========================================================================================
	$soma_tratados_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CAFE' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_cafe_print = number_format($soma_tratados_cafe[0],2,",",".");

	// SOMAS CONTRATOS PIMENTA  ==========================================================================================
	$soma_tratados_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='PIMENTA' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_pimenta_print = number_format($soma_tratados_pimenta[0],2,",",".");

	// SOMAS CONTRATOS CACAU  ==========================================================================================
	$soma_tratados_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CACAU' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_cacau_print = number_format($soma_tratados_cacau[0],2,",",".");

	// SOMAS CONTRATOS CRAVO  ==========================================================================================
	$soma_tratados_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CRAVO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_cravo_print = number_format($soma_tratados_cravo[0],2,",",".");
	}




	elseif ($monstra_situacao == "pagos")
	{
	$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='PAGO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, codigo");
	$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
	
	// SOMAS CONTRATOS CAFE  ==========================================================================================
	$soma_tratados_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CAFE' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='PAGO'"));
	$soma_tratados_cafe_print = number_format($soma_tratados_cafe[0],2,",",".");

	// SOMAS CONTRATOS PIMENTA  ==========================================================================================
	$soma_tratados_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='PIMENTA' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='PAGO'"));
	$soma_tratados_pimenta_print = number_format($soma_tratados_pimenta[0],2,",",".");

	// SOMAS CONTRATOS CACAU  ==========================================================================================
	$soma_tratados_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CACAU' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='PAGO'"));
	$soma_tratados_cacau_print = number_format($soma_tratados_cacau[0],2,",",".");

	// SOMAS CONTRATOS CRAVO  ==========================================================================================
	$soma_tratados_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND produto='CRAVO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'AND situacao_contrato='PAGO'"));
	$soma_tratados_cravo_print = number_format($soma_tratados_cravo[0],2,",",".");
	}
	

	elseif ($monstra_situacao == "produtor")
	{
	$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND produtor='$produtor' AND filial='$filial' ORDER BY data, codigo");
	$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
	
	// SOMAS CONTRATOS CAFE  ==========================================================================================
	$soma_tratados_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produtor='$produtor' AND produto='CAFE'"));
	$soma_tratados_cafe_print = number_format($soma_tratados_cafe[0],2,",",".");

	// SOMAS CONTRATOS PIMENTA  ==========================================================================================
	$soma_tratados_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produtor='$produtor' AND produto='PIMENTA'"));
	$soma_tratados_pimenta_print = number_format($soma_tratados_pimenta[0],2,",",".");

	// SOMAS CONTRATOS CACAU  ==========================================================================================
	$soma_tratados_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produtor='$produtor' AND produto='CACAU'"));
	$soma_tratados_cacau_print = number_format($soma_tratados_cacau[0],2,",",".");

	// SOMAS CONTRATOS CRAVO  ==========================================================================================
	$soma_tratados_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produtor='$produtor' AND produto='CRAVO'"));
	$soma_tratados_cravo_print = number_format($soma_tratados_cravo[0],2,",",".");
	}


	
	else
	{
	$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' ORDER BY data, codigo");
	$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
	
	// SOMAS CONTRATOS CAFE  ==========================================================================================
	$soma_tratados_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='CAFE'"));
	$soma_tratados_cafe_print = number_format($soma_tratados_cafe[0],2,",",".");

	// SOMAS CONTRATOS PIMENTA  ==========================================================================================
	$soma_tratados_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='PIMENTA'"));
	$soma_tratados_pimenta_print = number_format($soma_tratados_pimenta[0],2,",",".");

	// SOMAS CONTRATOS CACAU  ==========================================================================================
	$soma_tratados_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='CACAU'"));
	$soma_tratados_cacau_print = number_format($soma_tratados_cacau[0],2,",",".");

	// SOMAS CONTRATOS CRAVO  ==========================================================================================
	$soma_tratados_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='CRAVO'"));
	$soma_tratados_cravo_print = number_format($soma_tratados_cravo[0],2,",",".");
	}
	
	
/*
}
*/
// ==================================================================================================================================================
?>


<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:250px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:245px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Contratos Tratados</b>
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
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/index_contrato_tratado.php" method="post" />
		<input type='hidden' name='botao' value='busca' />
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
			if ($monstra_situacao == "todos")
			{echo "<input type='radio' name='monstra_situacao' value='todos' checked='checked' /><i>Todos</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todos' /><i>Todos</i>";}
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
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "pagos")
			{echo "<input type='radio' name='monstra_situacao' value='pagos' checked='checked' /><i>Pagos</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagos' /><i>Pagos</i>";}
			?>
		</div>
<!--
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
-->

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" style="float:left" />
		</form>
		</div>
	
		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/contrato_tratado_cadastro.php">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/novo.jpg" border="0" style="float:left" />
		</a>
		</div>	
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:7px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:24px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:20px; margin-left:10px; border:0px solid #999">
	<?php 

	if ($linha_cont_tratado >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina' />
	<input type='hidden' name='botao' value='imprimir' />
	<input type='hidden' name='data_inicial' value='$data_inicial_aux' />
	<input type='hidden' name='data_final' value='$data_final_aux' />
	<input type='hidden' name='monstra_situacao' value='produtor' />
	<input type='hidden' name='produtor' value='$produtor' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>";}
	else
	{echo"";}

	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:20px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php 
	if ($linha_cont_tratado == 1)
	{echo"<i><b>$linha_cont_tratado</b> Contrato</i>";}
	elseif ($linha_cont_tratado == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_cont_tratado</b> Contratos</i>";}
	?>
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:24px; width:1080px; border:0px solid #000; margin:auto">

	<?php
   	if ($linha_cont_tratado >= 1)
		{echo"
		<div id='centro' style='width:190px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#666; text-align:left'>
		<b>Total de Contratos Futuros:</b>
		</div>";}
	else
		{}
        
	if ($soma_tratados_cafe[0] >= 1)
		{echo "
		<div id='centro' style='width:220px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#003466; text-align:left'>
		Caf&eacute; Conilon: <b>$soma_tratados_cafe_print</b> Sacas
		</div>";}
	else
		{echo "";}

	if ($soma_tratados_pimenta[0] >= 1)
		{echo "
		<div id='centro' style='width:220px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#003466; text-align:left'>
		Pimenta do Reino: <b>$soma_tratados_pimenta_print</b> Kg
		</div>";}
	else
		{echo "";}

	if ($soma_tratados_cacau[0] >= 1)
		{echo "
		<div id='centro' style='width:220px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#003466; text-align:left'>
		Cacau: <b>$soma_tratados_cacau_print</b> Kg
		</div>";}
	else
		{echo "";}

	if ($soma_tratados_cravo[0] >= 1)
		{echo "
		<div id='centro' style='width:220px; float:left; height:20px; border:0px solid #999; font-size:11px; color:#003466; text-align:left'>
		Cravo da &Iacute;ndia: <b>$soma_tratados_cravo_print</b> Kg
		</div>";}
	else
		{echo "";}
	?>

	
</div>
<!-- ====================================================================================== -->



<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_cont_tratado == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php

if ($linha_cont_tratado == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='275px' align='center' bgcolor='#006699'>Produtor</td>
<td width='65px' align='center' bgcolor='#006699'>N&ordm; Contrato</td>
<td width='70px' align='center' bgcolor='#006699'>Vencimento</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='82px' align='center' bgcolor='#006699'>Quant. Adquirida</td>
<td width='82px' align='center' bgcolor='#006699'>Quant. Entregar</td>
<td width='50px' align='center' bgcolor='#006699'>Unidade</td>
<td width='54px' align='center' bgcolor='#006699'>2&ordf; Via</td>
<td width='54px' align='center' bgcolor='#006699'>Baixar</td>
<td width='54px' align='center' bgcolor='#006699'>Estornar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}


?>


<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php

for ($w=1 ; $w<=$linha_cont_tratado ; $w++)
{
	$aux_contrato = mysqli_fetch_row($busca_cont_tratado);

// DADOS DO CONTRATO =========================
	$produtor = $aux_contrato[1];
	$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
	$produto = $aux_contrato[3];
	$quantidade = $aux_contrato[4];
	$quantidade_adquirida = $aux_contrato[5];
	$unidade = $aux_contrato[6];
	$descricao_produto = $aux_contrato[7];
	$vencimento_contrato_print = date('d/m/Y', strtotime($aux_contrato[8]));
	$fiador_1 = $aux_contrato[9];
	$fiador_2 = $aux_contrato[10];
	$observacao = $aux_contrato[11];
	$estado_registro = $aux_contrato[12];
	$quantidade_fracao = $aux_contrato[13];
	$porcentagem_juros = $aux_contrato[14];
	$situacao_contrato = $aux_contrato[15];
	$quantidade_a_entregar = $aux_contrato[16];
	$numero_contrato = $aux_contrato[17];
	$usuario_cadastro = $aux_contrato[18];
	$hora_cadastro = $aux_contrato[19];
	$data_cadastro = $aux_contrato[20];
	$filial = $aux_contrato[24];


// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$produtor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "pf")
		{$cpf_cnpj = "CPF: " . $aux_pessoa[3];}
		else
		{$cpf_cnpj = "CNPJ: " . $aux_pessoa[4];}
	}

// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_contrato == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_contrato == "PAGO")
	{$situacao_print = "Liquidado";}
	else
	{$situacao_print = "-";}




// RELATORIO =========================
	if ($situacao_contrato == "EM_ABERTO")
	{echo "<tr style='color:#00F' title='N&ordm; Contrato: $numero_contrato&#013;Observa&ccedil;&atilde;o: $observacao'>";}
	else
	{echo "<tr style='color:#333' title='N&ordm; Contrato: $numero_contrato&#013;Observa&ccedil;&atilde;o: $observacao'>";}

	echo "
	<td width='70px' align='left'>&#160;$data_contrato_print</td>
	<td width='275px' align='left'>&#160;$fornecedor_print</td>
	<td width='65px' align='center'>$numero_contrato</td>
	<td width='70px' align='center'>$vencimento_contrato_print</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='82px' align='center'>$quantidade_adquirida</td>
	<td width='82px' align='center'>$quantidade_a_entregar</td>
	<td width='50px' align='center'>$unidade</td>";

	if ($permissao[47] == 'S')
	{echo "	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_impressao.php' method='post' target='_blank'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='recibo'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/recibo.png' border='0' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}
	
	if ($situacao_contrato == "EM_ABERTO" and $permissao[48] == 'S')
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_produtor.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='baixar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='produtor' value='$produtor'>
	<input type='hidden' name='produto' value='$produto'>
	<input type='hidden' name='data_contrato' value='$aux_contrato[2]'>
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida'>
	<input type='hidden' name='quantidade_a_entregar' value='$quantidade_a_entregar'>
	<input type='hidden' name='unidade' value='$unidade'>
	<input type='hidden' name='obs' value='$observacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}


	if ($situacao_contrato == "PAGO" and $permissao[49] == 'S')
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_produtor.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='estornar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' border='0' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}

	
	if ($permissao[50] == 'S')
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_produtor.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='excluir'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' border='0' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}

	
	echo "
	</tr>";

}


// =================================================================================================================

?>
</table>

<?php

if ($linha_cont_tratado == 0 and $botao == "busca")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum contrato encontrado.</i></div>";}
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

<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>