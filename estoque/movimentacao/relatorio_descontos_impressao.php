<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'classificacao_relatorio_impressao';
$titulo = 'Romaneios - Classifica&ccedil;&atilde;o de Qualidade';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ================================================================================================================
include ('classificacao_include_comando.php'); 
// ================================================================================================================


// ==================================================================================================================
include ('../../includes/head_impressao.php');
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
<body onLoad="imprimir()">

<div id="centro" style="width:770px; border:0px solid #F00">

<?php
echo "
<div id='centro' style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:710px; height:80px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:200px; height:40px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='160px' /></div>

	<div style='width:315px; height:40px; border:0px solid #000; font-size:12px; float:left' align='center'>
	<div style='margin-top:5px'>DESCONTOS PREVISTOS E REALIZADOS<br /></div></div>

	<div style='width:190px; height:40px; border:0px solid #000; font-size:11px; float:left' align='right'>
	<div style='margin-top:5px'>$data_atual<br />$hora_atual</div></div>

<!-- ====================== -->
	<div style='width:200px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'></div>

	<div style='width:315px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$prod_print</div></div>

	<div style='width:190px; height:18px; border:0px solid #000; font-size:11px; float:left' align='right'></div>

<!-- ====================== -->
	<div style='width:200px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>$print_periodo</div>

	<div style='width:315px; height:16px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$print_fornecedor</div></div>

	<div style='width:190px; height:16px; border:0px solid #000; font-size:11px; float:left' align='right'>$print_quant_reg</div>

</div>



<!-- =================================================================================================================== -->

<div style='width:710px; height:850px; border:0px solid #00E; margin-top:2px; margin-left:40px; float:left'>";




// ====== TOTALIZADOR =====================================================================================
echo "
<div style='width:708px; height:60px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>
<div style='width:708px; height:60px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'>
QUANTIDADE TOTAL: <b>$soma_romaneio_print Kg</b>
</div>


<div style='width:708px; height:40px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:left'>
Filial Armazenagem: <b>$filial_armazem_pesq</b>
</div>

<div style='width:708px; height:40px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:left'>
Filial Origem: <b>$filial_origem_pesq</b>
</div>";



for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_distinct);

// ====== BUSCA PRODUTO ======================================================================================
$busca_prod_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$aux_bp_geral[0]' AND estado_registro!='EXCLUIDO'");
$aux_prod_2 = mysqli_fetch_row($busca_prod_2);

$prod_print_2 = $aux_prod_2[22];
// ==============================================================================================================

$soma_quant_liq = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));

$soma_quant_previsto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quant_quebra_previsto) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));

$soma_quant_realizado = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quant_quebra_realizado) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));

$soma_quant_liq_print = number_format($soma_quant_liq[0],0,",",".");
$soma_quant_previsto_print = number_format($soma_quant_previsto[0],0,",",".");
$soma_quant_realizado_print = number_format($soma_quant_realizado[0],0,",",".");
$dif_prev_real = ($soma_quant_realizado[0] - $soma_quant_previsto[0]);
$dif_prev_real_print = number_format($dif_prev_real,0,",",".");

$linhas_t_aux = $linhas_t_aux + 1;
	
echo "
<div style='width:680px; height:27px; border:0px solid #999; margin-top:5px; margin-left:10px; float:left; background-color:#FFF; font-size:10px'>
<div style='height:26px; width:670px; border:1px solid #000; border-radius:3px; background-color:#FFF; margin-left:0px'>
	<div style='width:120px; color:#000; border:0px solid #000; float:left; margin-left:10px; margin-top:6px'>
	<b>$prod_print_2</b></div>
	
	<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:5px; margin-top:6px; text-align:left'>
	Peso L&iacute;q. $soma_quant_liq_print Kg</div>

	<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:5px; margin-top:6px; text-align:left'>
	Desc. Realizado: $soma_quant_realizado_print Kg</div>

	<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:5px; margin-top:6px; text-align:left'>
	Desc. Previsto: $soma_quant_previsto_print Kg</div>

	<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:5px; margin-top:6px; text-align:left'>
	Difen&ccedil;a: $dif_prev_real_print Kg</div>
	
</div>
</div>";


}
// ========================================================================================================




echo "</div>";
// ========================================================================================================






echo "


<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:10px; border:0px solid #000; margin-left:40px; margin-top:20px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>FILIAL: $filial</div>

	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:right' align='right'>
	P&Aacute;GINA 1</div>
</div>
<!-- =============================================================================================== -->



<!-- =============================================================================================== -->";
echo "</div>"; // quebra de página

?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->