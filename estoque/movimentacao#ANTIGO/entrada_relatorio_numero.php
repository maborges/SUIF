<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'entrada_relatorio_numero';
$titulo = 'Estoque - Relat&oacute;rio de Entrada';
$modulo = 'estoque';
$menu = 'movimentacao';

include ('../../includes/head.php'); 
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




// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$mes_atras = date ('Y-m-d', strtotime('-30 days'));

$filial = $filial_usuario;

$mostra_cancelada = $_POST["mostra_cancelada"];		
$botao = $_POST["botao"];
$num_romaneio_aux = $_POST["num_romaneio_aux"];

if ($botao == "1")
{
	$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);
}
else
{
	$data_inicial_aux = $data_hoje_aux;
	$data_inicial = $data_hoje;
	$data_final_aux = $data_hoje_aux;
	$data_final = $data_hoje;
}


if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "TODOS";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}



// TODOS  ==========================================================================================
if ($monstra_situacao == "TODOS")
{	
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	

	$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND filial='$filial'"));
	$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
	
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CAFE' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");

	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='PIMENTA' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
	
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CACAU' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
	
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CRAVO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
}


// PRE_ROMANEIO  ==========================================================================================
elseif ($monstra_situacao == "PRE_ROMANEIO")
{
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);

	
	$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial'"));
	$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
	
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CAFE' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
	
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='PIMENTA' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
	
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CACAU' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
	
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CRAVO' AND situacao_romaneio='PRE_ROMANEIO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
}


// EM_ABERTO  ==========================================================================================
elseif ($monstra_situacao == "EM_ABERTO")
{
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='EM_ABERTO' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	

	$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='EM_ABERTO' AND filial='$filial'"));
	$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
	
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CAFE' AND situacao_romaneio='EM_ABERTO' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
	
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='PIMENTA' AND situacao_romaneio='EM_ABERTO' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
	
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CACAU' AND situacao_romaneio='EM_ABERTO' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
	
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CRAVO' AND situacao_romaneio='EM_ABERTO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
}


// FECHADO  ==========================================================================================
elseif ($monstra_situacao == "FECHADO")
{
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='FECHADO' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	

	$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND situacao_romaneio='FECHADO' AND filial='$filial'"));
	$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
	
	$soma_quant_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CAFE' AND situacao_romaneio='FECHADO' AND filial='$filial'"));
	$quant_cafe_print = number_format($soma_quant_cafe[0],2,",",".");
	
	$soma_quant_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='PIMENTA' AND situacao_romaneio='FECHADO' AND filial='$filial'"));
	$quant_pimenta_print = number_format($soma_quant_pimenta[0],2,",",".");
	
	$soma_quant_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CACAU' AND situacao_romaneio='FECHADO' AND filial='$filial'"));
	$quant_cacau_print = number_format($soma_quant_cacau[0],2,",",".");
	
	$soma_quant_cravo = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$num_romaneio_aux' AND produto='CRAVO' AND situacao_romaneio='FECHADO' AND filial='$filial'"));
	$quant_cravo_print = number_format($soma_quant_cravo[0],2,",",".");
}



else
{}
?>


<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>

<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:300px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:295px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Romaneios de Entrada</b> - Por N&uacute;mero
	</div>
	
	<div id="centro" style="height:25px; width:22px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:80px; float:left; height:20px; color:#0000FF; border:0px solid #999; text-align:right">
		<div id="geral" style="width:75px; height:8px; float:left; border:0px solid #999"></div>
		<i><!-- Filtrar por:&#160;--></i></div>

		<div id="centro" style="width:500px; float:left; height:20px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:495px; height:8px; float:left; border:0px solid #999"></div>
<!--
		<div id="menu_atalho">

			<div id="geral" style="margin-right:20px; margin-left:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_periodo.php">&#8226; Data</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_periodo.php">&#8226; Per&iacute;odo</a></div>			
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_periodo.php">&#8226; Produto</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_periodo.php">&#8226; Fornecedor</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<form action="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_numero.php" method="post" />
			<input type='hidden' name='botao' value='numero' />
			&#8226; N&ordm; Romaneio <input type="text" name="num_romaneio_aux" maxlength="15" id="ok" style="color:#0000FF; width:45px; font-size:9px;" title="Buscar por n&uacute;mero de romaneio" />
			</form>
			</div>


		</div>
-->
		</div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right; margin-left:20px">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/entrada_relatorio_numero.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>N&ordm; Romaneio:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:6px; float:left; border:0px solid #999"></div>
		<input type="text" name="num_romaneio_aux" maxlength="15" id="ok" style="color:#0000FF; width:90px" title="Buscar por n&uacute;mero de romaneio" value="<?php echo"$num_romaneio_aux"; ?>" />
		</div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_romaneio >= 1)
	{echo"
	<!--
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='index_contas_pagar'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='botao_1' value='$botao_1'>
	<input type='hidden' name='botao_2' value='$botao_2'>	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>
	-->";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_romaneio == 1)
	{echo"<i><b>$linha_romaneio</b> Romaneio</i>";}
	elseif ($linha_romaneio == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_romaneio</b> Romaneios</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_romaneio >= 1)
	{echo"TOTAL DE ENTRADA: <b>$soma_romaneio_print Kg</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->
<?php
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


?>







<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_romaneio == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_romaneio == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='80px' align='center' bgcolor='#006699'>Data</td>
<td width='350px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='116px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='95px' align='center' bgcolor='#006699'>Situa&ccedil;&atilde;o</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Peso</td>
<td width='54px' align='center' bgcolor='#006699'>NF</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$fornecedor = $aux_romaneio[2];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$tipo = $aux_romaneio[5];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],2,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],2,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],2,",",".");
$tipo_sacaria = $aux_romaneio[12];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao = $aux_romaneio[18];
$quantidade_prevista = $aux_romaneio[27];


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
	{$unidade_print = "Sc";}
	elseif ($unidade == "KG")
	{$unidade_print = "Kg";}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_romaneio == "PRE_ROMANEIO")
	{$situacao_print = "Pr&eacute;-Romaneio";}
	elseif ($situacao_romaneio == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_romaneio == "FECHADO")
	{$situacao_print = "Fechado";}
	else
	{$situacao_print = "-";}



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




// RELATORIO =========================
	if ($situacao_romaneio == "EM_ABERTO")
	{echo "<tr style='color:#333' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
	{echo "<tr style='color:#009900' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	else
	{echo "<tr style='color:#00F' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	
	echo "
	<td width='80px' align='left'>&#160;$data_print</td>";
	if ($situacao == "ENTRADA_DIRETA")
	{echo "<td width='350px' align='left'>&#160;$fornecedor_print (BP*)</td>";}
	else
	{echo "<td width='350px' align='left'>&#160;$fornecedor_print</td>";}	
	echo "
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='116px' align='center'>$quantidade_print Kg</td>
	<td width='95px' align='center'>$situacao_print</td>
	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/estoque/movimentacao/romaneio_e_visualizar.php' method='post'>
	<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
	<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
	</td>";


	if ($situacao_romaneio == "FECHADO" and $permissao[16] == 'S' /*and (strtotime($data) > strtotime($mes_atras))*/)
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/entrada_editar.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_editar.png' border='0' /></form>	
		</td>";
	}
	
	elseif ($situacao_romaneio == "EM_ABERTO" and $permissao[16] == 'S' /*and (strtotime($data) > strtotime($mes_atras))*/)
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/entrada_editar_2.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_editar.png' border='0' /></form>	
		</td>";
	}
	
	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}


	if ($situacao_romaneio == "FECHADO" or $permissao[17] == 'N')
		{echo "<td width='54px' align='center'></td>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
		{
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/entrada_cadastro_inicial.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='botao' value='RELATORIO'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_balanca.png' border='0' /></form>	
		</td>";
		}
	else
		{
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/entrada_cadastro_final.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='botao' value='RELATORIO'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_balanca.png' border='0' /></form>	
		</td>";
		}
	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal/nota_fiscal.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/recibo.png' border='0' /></form>	
		</td>";

	echo "</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_romaneio == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum romaneio encontrado.</i></div>";}
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