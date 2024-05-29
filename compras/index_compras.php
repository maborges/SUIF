<?php
include ("../includes/config.php");
include ("../includes/conecta_bd.php");
include ("../includes/valida_cookies.php");
$pagina = "index_compras";
$titulo = "Compras";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$mes_atual = date("m", time());
$ano_atual = date("Y", time());
$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$filial = $filial_usuario;


$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO'");
$linha_produto = mysqli_num_rows ($busca_produto);
// ================================================================================================================


// ====== RELATORIO CONSOLIDADO ===================================================================================
$botao = $_POST["botao"];
$relatorio_conso = $_POST["relatorio_conso"];

if ($botao == "CONSOLIDADO")
{$editar = mysqli_query ($conexao, "UPDATE filiais SET compras_relat_conso='$relatorio_conso' WHERE descricao='$filial'");
$filial_config[30] = $relatorio_conso;}
// ================================================================================================================


// ================================================================================================================
if ($filial_config[30] == "S")
{$mysql_filial = "filial IS NOT NULL";}
else	
{$mysql_filial = "filial='$filial_usuario'";}
// ================================================================================================================


// ================================================================================================================
include ("../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body>


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../includes/submenu_compras_compras.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">




<!-- ============================================================================================================= -->
<div style="width:auto; height:560px; border:1px solid transparent; margin:auto">





<!-- ====== GRAFICO COMPRAS ANUAL GERAL ========================================================================== -->
<div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Compras em <?php echo "$ano_atual"; ?></div>
    </div>

	<?php
	// CAFÉ CONILON ================================================================================================
	$b_cafe = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='2'");
	$achou_cafe = mysqli_num_rows ($b_cafe);
	$quant_ano_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
	$quant_ano_cafe_print = number_format($quant_ano_cafe[0],0,",",".") . " SC";

	// PIMENTA DO REINO ============================================================================================
	$b_pimenta = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
	$achou_pimenta = mysqli_num_rows ($b_pimenta);
	$quant_ano_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='3'"));
	$quant_ano_pimenta_print = number_format($quant_ano_pimenta[0],0,",",".") . " KG";

	// CACAU =======================================================================================================
	$b_cacau = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='4'");
	$achou_cacau = mysqli_num_rows ($b_cacau);
	$quant_ano_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
	$quant_ano_cacau_print = number_format($quant_ano_cacau[0],0,",",".") . " KG";

	// PIMENTA MADURA ============================================================================================
	$b_pimenta_ma = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
	$achou_pimenta_ma = mysqli_num_rows ($b_pimenta_ma);
	$quant_ano_pimenta_ma = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='13'"));
	$quant_ano_pimenta_ma_print = number_format($quant_ano_pimenta_ma[0],0,",",".") . " KG";


	$total_quant = (($quant_ano_cafe[0] * 60) + $quant_ano_pimenta[0] + $quant_ano_cacau[0]);
	$cafe_porcento = number_format(((($quant_ano_cafe[0] * 60) / $total_quant) * 100),2,",",".") . "%";
	$pimenta_porcento = number_format((($quant_ano_pimenta[0] / $total_quant) * 100),2,",",".") . "%";
	$cacau_porcento = number_format((($quant_ano_cacau[0] / $total_quant) * 100),2,",",".") . "%";
	$pimenta_ma_porcento = number_format((($quant_ano_pimenta_ma[0] / $total_quant) * 100),2,",",".") . "%";

	$coluna_100 = 130;
	ceil($total_quant);

	$coluna_cafe = ($coluna_100 * ceil($cafe_porcento)) / 100;
	$coluna_cafe = $coluna_cafe . "px";
	$margin_top_cafe = ($coluna_100 - $coluna_cafe) + 10;
	$margin_top_cafe = $margin_top_cafe . "px";

	$coluna_pimenta = ($coluna_100 * ceil($pimenta_porcento)) / 100;
	$coluna_pimenta = $coluna_pimenta . "px";
	$margin_top_pimenta = ($coluna_100 - $coluna_pimenta) + 10;
	$margin_top_pimenta = $margin_top_pimenta . "px";

	$coluna_cacau = ($coluna_100 * ceil($cacau_porcento)) / 100;
	$coluna_cacau = $coluna_cacau . "px";
	$margin_top_cacau = ($coluna_100 - $coluna_cacau) + 10;
	$margin_top_cacau = $margin_top_cacau . "px";

	$coluna_pimenta_ma = ($coluna_100 * ceil($pimenta_ma_porcento)) / 100;
	$coluna_pimenta_ma = $coluna_pimenta_ma . "px";
	$margin_top_pimenta_ma = ($coluna_100 - $coluna_pimenta_ma) + 10;
	$margin_top_pimenta_ma = $margin_top_pimenta_ma . "px";


	if ($achou_cafe == 1 and $achou_pimenta == 1 and $achou_cacau == 1)
	{echo "
	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Caf&eacute; Conilon'>
   		<div style='width:60px; height:$coluna_cafe; margin-left:15px; margin-top:$margin_top_cafe; float:left; background-color:#556B2F'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $cafe_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_cafe_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'>
	    </div>
    </div>

	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Pimenta do Reino'>
   		<div style='width:60px; height:$coluna_pimenta; margin-left:15px; margin-top:$margin_top_pimenta; float:left; background-color:#800000'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $pimenta_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_pimenta_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'>
	    </div>
    </div>

	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Cacau'>
   		<div style='width:60px; height:$coluna_cacau; margin-left:15px; margin-top:$margin_top_cacau; float:left; background-color:#D2691E'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $cacau_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_cacau_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='height:30px'>
	    </div>
    </div>";}

	elseif ($achou_cafe == 1 and $achou_pimenta == 1 and $achou_pimenta_ma == 1)
	{echo "
	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Caf&eacute; Conilon'>
   		<div style='width:60px; height:$coluna_cafe; margin-left:15px; margin-top:$margin_top_cafe; float:left; background-color:#556B2F'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $cafe_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_cafe_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'>
	    </div>
    </div>

	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Pimenta do Reino'>
   		<div style='width:60px; height:$coluna_pimenta; margin-left:15px; margin-top:$margin_top_pimenta; float:left; background-color:#800000'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $pimenta_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_pimenta_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'>
	    </div>
    </div>

	<div style='width:90px; height:220px; margin-left:5px; margin-top:0px; float:left' title='Pimenta Madura'>
   		<div style='width:60px; height:$coluna_cacau; margin-left:15px; margin-top:$margin_top_cacau; float:left; background-color:#D2691E'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $pimenta_ma_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_pimenta_ma_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'>
	    </div>
    </div>";}

	
	else
	{echo "
	<div style='width:90px; height:220px; margin-left:37px; margin-top:0px; float:left' title='Caf&eacute; Conilon'>
   		<div style='width:60px; height:$coluna_cafe; margin-left:15px; margin-top:$margin_top_cafe; float:left; background-color:#556B2F'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $cafe_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_cafe_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'>
	    </div>
    </div>

	<div style='width:90px; height:220px; margin-left:37px; margin-top:0px; float:left' title='Pimenta do Reino'>
   		<div style='width:60px; height:$coluna_pimenta; margin-left:15px; margin-top:$margin_top_pimenta; float:left; background-color:#800000'>
	    </div>
  		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:14px; color:#003466'>
        $pimenta_porcento
	    </div>
   		<div style='width:90px; height:15px; margin-left:0px; margin-top:5px; float:left; text-align:center; font-size:9px; color:#003466'>
		$quant_ano_pimenta_print
	    </div>
   		<div style='width:90px; height:40px; margin-left:0px; margin-top:0px; float:left; text-align:center'>
        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'>
	    </div>
    </div>";}
	?>

</div>
<!-- ============================================================================================================= -->
















<!-- ====== GRAFICO COMPRAS DO ANO =============================================================================== -->
<div style="width:960px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:920px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Compras em <?php echo "$ano_atual"; ?></div>
    </div>


	<img src="grafico_compras.php" />



</div>
<!-- ============================================================================================================= -->








<!-- ====== COMPRAS DO MÊS ======================================================================================= -->
<div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Total de compras do mês atual</div>
    </div>

    <div style="width:253px; height:15px; margin-left:20px; margin-top:10px; text-align:center; float:left; font-size:13px; color:#003466">
    <b><?php echo $meses [$mes_atual-1]; ?></b></div>

    <div style="width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999">
    </div>


	<?php
	// CAFÉ CONILON ================================================================================================
	$total_mes_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));
	$quant_mes_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='2'"));

	$total_mes_cafe_print = "R$ " . number_format($total_mes_cafe[0],2,",",".");
	$quant_mes_cafe_print = number_format($quant_mes_cafe[0],0,",",".") . " SC";

	if ($quant_mes_cafe[0] >= 1)
	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $quant_mes_cafe_print</div>
	
    <div style='width:126px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#009900'>
    </div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#999'>
    $total_mes_cafe_print</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}



	// PIMENTA DO REINO ============================================================================================
	$total_mes_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='3'"));
	$quant_mes_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='3'"));

	$total_mes_pimenta_print = "R$ " . number_format($total_mes_pimenta[0],2,",",".");
	$quant_mes_pimenta_print = number_format($quant_mes_pimenta[0],0,",",".") . " KG";

	if ($quant_mes_pimenta[0] >= 1)
	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $quant_mes_pimenta_print</div>
	
    <div style='width:126px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#009900'>
    </div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#999'>
    $total_mes_pimenta_print</div>

	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}



	// CACAU =======================================================================================================
	$total_mes_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));
	$quant_mes_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='4'"));

	$total_mes_cacau_print = "R$ " . number_format($total_mes_cacau[0],2,",",".");
	$quant_mes_cacau_print = number_format($quant_mes_cacau[0],0,",",".") . " KG";

	if ($quant_mes_cacau[0] >= 1)
	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Cacau:</div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $quant_mes_cacau_print</div>

    <div style='width:126px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#009900'>
    </div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#999'>
    $total_mes_cacau_print</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}


	// PIMENTA MADURA ============================================================================================
	$total_mes_pimenta_ma = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='13'"));
	$quant_mes_pimenta_ma = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE YEAR(data_compra)='$ano_atual' AND MONTH(data_compra)='$mes_atual' AND $mysql_filial AND estado_registro='ATIVO' AND movimentacao='COMPRA' AND cod_produto='13'"));

	$total_mes_pimenta_ma_print = "R$ " . number_format($total_mes_pimenta_ma[0],2,",",".");
	$quant_mes_pimenta_ma_print = number_format($quant_mes_pimenta_ma[0],0,",",".") . " KG";

	if ($quant_mes_pimenta_ma[0] >= 1)
	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta Madura:</div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $quant_mes_pimenta_ma_print</div>
	
    <div style='width:126px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#009900'>
    </div>

    <div style='width:126px; height:20px; margin-left:1px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#999'>
    $total_mes_pimenta_ma_print</div>

	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}
	?>

</div>
<!-- ============================================================================================================= -->












<!-- ====== SALDO ARMAZENADO ===================================================================================== -->
<div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Saldo de Armazenado</div>
    </div>

    <div style="width:253px; height:10px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
    </div>

	<?php
	// CAFÉ CONILON ================================================================================================
	$saldo_cre_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='2' AND saldo>'0'"));
	$saldo_cre_cafe_print = number_format($saldo_cre_cafe[0],2,",",".") . " SC";

	$saldo_dev_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='2' AND saldo<'0'"));
	$saldo_dev_cafe_print = number_format($saldo_dev_cafe[0],2,",",".") . " SC";

	if ($saldo_dev_cafe[0] < 0 or $saldo_cre_cafe[0] > 0)
	{echo "
    <div style='width:116px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    C</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $saldo_cre_cafe_print</div>
	
    <div style='width:116px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:12px; color:#009900'>
    </div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    D</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    $saldo_dev_cafe_print</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}
	?>


	<?php
	// PIMENTA DO REINO ============================================================================================
	$saldo_cre_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='3' AND saldo>'0'"));
	$saldo_cre_pimenta_print = number_format($saldo_cre_pimenta[0],2,",",".") . " KG";

	$saldo_dev_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='3' AND saldo<'0'"));
	$saldo_dev_pimenta_print = number_format($saldo_dev_pimenta[0],2,",",".") . " KG";

	if ($saldo_dev_pimenta[0] < 0 or $saldo_cre_pimenta[0] > 0)
	{echo "
    <div style='width:116px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    C</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $saldo_cre_pimenta_print</div>
	
    <div style='width:116px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:12px; color:#009900'>
    </div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    D</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    $saldo_dev_pimenta_print</div>

	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}
	?>


	<?php
	// CACAU =======================================================================================================
	$saldo_cre_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='4' AND saldo>'0'"));
	$saldo_cre_cacau_print = number_format($saldo_cre_cacau[0],2,",",".") . " KG";

	$saldo_dev_cacau = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND cod_produto='4' AND saldo<'0'"));
	$saldo_dev_cacau_print = number_format($saldo_dev_cacau[0],2,",",".") . " KG";

	if ($saldo_dev_cacau[0] < 0 or $saldo_cre_cacau[0] > 0)
	{echo "
    <div style='width:116px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Cacau:</div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    C</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $saldo_cre_cacau_print</div>
	
    <div style='width:116px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:12px; color:#009900'>
    </div>

    <div style='width:20px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    D</div>

    <div style='width:116px; height:20px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:12px; color:#FF0000'>
    $saldo_dev_cacau_print</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}
	?>



</div>
<!-- ============================================================================================================= -->










<!-- ====== CONTRATOS ============================================================================================ -->
<div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Contratos</div>
    </div>

    <div style="width:253px; height:15px; margin-left:20px; margin-top:10px; text-align:center; float:left; font-size:13px; color:#003466">
    <b>Contratos Futuros</b></div>

	<?php
	// CAFÉ CONILON ================================================================================================
	$futuro_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND $mysql_filial AND cod_produto='2' AND situacao_contrato='EM_ABERTO'"));
	$futuro_cafe_print = number_format($futuro_cafe[0],2,",",".") . " SC";

	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:126px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $futuro_cafe_print</div>";}



	// PIMENTA DO REINO ============================================================================================
	$futuro_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND $mysql_filial AND cod_produto='3' AND situacao_contrato='EM_ABERTO'"));
	$futuro_pimenta_print = number_format($futuro_pimenta[0],2,",",".") . " KG";

	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:126px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $futuro_pimenta_print</div>";}
	?>

	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
    </div>

    <div style="width:253px; height:15px; margin-left:20px; margin-top:10px; text-align:center; float:left; font-size:13px; color:#003466">
    <b>Contratos Tratados</b></div>

	<?php
	// CAFÉ CONILON ================================================================================================
	$tratado_cafe = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND $mysql_filial AND cod_produto='2' AND situacao_contrato='EM_ABERTO'"));
	$tratado_cafe_print = number_format($tratado_cafe[0],2,",",".") . " SC";

	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:126px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $tratado_cafe_print</div>";}



	// PIMENTA DO REINO ============================================================================================
	$tratado_pimenta = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND $mysql_filial AND cod_produto='3' AND situacao_contrato='EM_ABERTO'"));
	$tratado_pimenta_print = number_format($tratado_pimenta[0],2,",",".") . " KG";

	{echo "
    <div style='width:126px; height:20px; margin-left:20px; margin-top:15px; text-align:left; float:left; font-size:12px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:126px; height:20px; margin-left:0px; margin-top:15px; text-align:right; float:left; font-size:12px; color:#0000FF'>
    $tratado_pimenta_print</div>";}
	?>



</div>
<!-- ============================================================================================================= -->







<!-- ====== PREÇO DE COMPRA ====================================================================================== -->
<div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
	<div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
    <div style="margin-top:4px; font-size:14px; color:#FFF">Pre&ccedil;o de compra atual</div>
    </div>

    <div style="width:253px; height:5px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
    </div>


	<?php
	// CAFÉ CONILON ================================================================================================
	$busca_cafe = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='2'");
	$linha_cafe = mysqli_num_rows ($busca_cafe);
	$aux_cafe = mysqli_fetch_row($busca_cafe);
	$preco_maximo_cafe = "R$ " . number_format($aux_cafe[21],2,",",".");
	$nome_imagem_cafe = $aux_cafe[28];
	
	$usuario_cafe_w = $aux_cafe[16];
	if ($usuario_cafe_w == "")
	{$dados_cafe_w = "";}
	else
	{
	$data_cafe_w = date('d/m/Y', strtotime($aux_cafe[18]));
	$hora_cafe_w = $aux_cafe[17];
	$dados_cafe_w = "$usuario_cafe_w $data_cafe_w $hora_cafe_w";
	}

	if ($linha_cafe == 1)
	{echo "
    <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    <img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_cafe.png' style='height:30px'></div>

    <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:100px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466' title='&Uacute;ltima atualiza&ccedil;&atilde;o: $dados_cafe_w'>
    $preco_maximo_cafe</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}


	// PIMENTA DO REINO ============================================================================================
	$busca_pimenta = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
	$linha_pimenta = mysqli_num_rows ($busca_pimenta);
	$aux_pimenta = mysqli_fetch_row($busca_pimenta);
	$preco_maximo_pimenta = "R$ " . number_format($aux_pimenta[21],2,",",".");
	$nome_imagem_pimenta = $aux_pimenta[28];

	$usuario_pimenta_w = $aux_pimenta[16];
	if ($usuario_pimenta_w == "")
	{$dados_pimenta_w = "";}
	else
	{
	$data_pimenta_w = date('d/m/Y', strtotime($aux_pimenta[18]));
	$hora_pimenta_w = $aux_pimenta[17];
	$dados_pimenta_w = "$usuario_pimenta_w $data_pimenta_w $hora_pimenta_w";
	}

	if ($linha_pimenta == 1)
	{echo "
    <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    <img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_pimenta.png' style='height:30px'></div>

    <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:100px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466' title='&Uacute;ltima atualiza&ccedil;&atilde;o: $dados_pimenta_w'>
    $preco_maximo_pimenta</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}


	// CACAU =======================================================================================================
	$busca_cacau = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='4'");
	$linha_cacau = mysqli_num_rows ($busca_cacau);
	$aux_cacau = mysqli_fetch_row($busca_cacau);
	$preco_maximo_cacau = "R$ " . number_format($aux_cacau[21],2,",",".");
	$nome_imagem_cacau = $aux_cacau[28];

	$usuario_cacau_w = $aux_cacau[16];
	if ($usuario_cacau_w == "")
	{$dados_cacau_w = "";}
	else
	{
	$data_cacau_w = date('d/m/Y', strtotime($aux_cacau[18]));
	$hora_cacau_w = $aux_cacau[17];
	$dados_cacau_w = "$usuario_cacau_w $data_cacau_w $hora_cacau_w";
	}

	if ($linha_cacau == 1)
	{echo "
    <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    <img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_cacau.png' style='height:30px'></div>

    <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
    Cacau:</div>

    <div style='width:100px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466' title='&Uacute;ltima atualiza&ccedil;&atilde;o: $dados_cacau_w'>
    $preco_maximo_cacau</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}

/*

	// PIMENTA MADURA ============================================================================================
	$busca_pimenta_ma = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
	$linha_pimenta_ma = mysqli_num_rows ($busca_pimenta_ma);
	$aux_pimenta_ma = mysqli_fetch_row($busca_pimenta_ma);
	$preco_maximo_pimenta_ma = "R$ " . number_format($aux_pimenta_ma[21],2,",",".");
	$nome_imagem_pimenta_ma = $aux_pimenta_ma[28];

	$usuario_pimenta_ma_w = $aux_pimenta_ma[16];
	if ($usuario_pimenta_ma_w == "")
	{$dados_pimenta_ma_w = "";}
	else
	{
	$data_pimenta_ma_w = date('d/m/Y', strtotime($aux_pimenta_ma[18]));
	$hora_pimenta_ma_w = $aux_pimenta_ma[17];
	$dados_pimenta_ma_w = "$usuario_pimenta_ma_w $data_pimenta_ma_w $hora_pimenta_ma_w";
	}

	if ($linha_pimenta_ma == 1)
	{echo "
    <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    <img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_pimenta_ma.png' style='height:30px'></div>

    <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta Madura:</div>

    <div style='width:100px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466' title='&Uacute;ltima atualiza&ccedil;&atilde;o: $dados_pimenta_ma_w'>
    $preco_maximo_pimenta_ma</div>
	
	<div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
	</div>";}
*/

	?>

    <div class="link_4" style="width:120px; height:5px; margin-left:110px; margin-top:0px; float:left; font-size:10px; color:#999">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/produtos/preco_compra.php" style="color:#999">Alterar Pre&ccedil;o</a>
    </div>


</div>
<!-- ============================================================================================================= -->








</div>
<!-- ============================================================================================================= -->










<!-- ============================================================================================================= -->
<div style="width:auto; height:40px; border:1px solid transparent; margin:auto">
	<div class="form_rotulo" style="width:140px; height:20px; float:left; margin-left:40px; margin-top:4px; color:#999">
	Relat&oacute;rio Consolidado:
    </div>
	<div style="width:100px; height:25px; float:left; border:1px solid transparent">
    <form name="relat_consolidado" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/index_compras.php" method="post" />
    <input type="hidden" name="botao" value="CONSOLIDADO" />
    
    <select name="relatorio_conso" class="form_select" onchange="document.relat_consolidado.submit()" style="width:60px; height:20px; font-size:12px; color:#999" />
    <?php
    if ($filial_config[30] == "S")
    {echo "
	<option value='S' selected='selected'>SIM</option>
	<option value='N'>N&Atilde;O</option>";}
    else
    {echo "
	<option value='S'>SIM</option>
	<option value='N' selected='selected'>N&Atilde;O</option>";}
    ?>
    </select>
    </div>
</div>
<!-- ============================================================================================================= -->



<?php
// SetFileFormat("png");

#Indicamos o título do gráfico e o título dos dados no eixo X e Y do mesmo
$grafico->SetTitle("Gráfico de exemplo");
$grafico->SetXTitle("Eixo X");
$grafico->SetYTitle("Eixo Y");


#Definimos os dados do gráfico
$dados = array(
		array('Janeiro', 10),
		array('Fevereiro', 5),
		array('Março', 4),
		array('Abril', 8),
		array('Maio', 7),
		array('Junho', 5),
);

$grafico->SetDataValues($dados);

#Exibimos o gráfico
$grafico->DrawGraph();


?>



</div>
<!-- ====== FIM DIV CT =========================================================================================== -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../includes/desconecta_bd.php"); ?>
</body>
</html>