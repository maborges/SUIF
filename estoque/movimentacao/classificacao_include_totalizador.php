<?php
$numero_divs = ceil($linha_produto_distinct / 4);
$alt_div = $numero_divs * 40;
$altura_div = $alt_div . "px";

echo "<div style='height:$altura_div; width:1250px; border:0px solid #000; margin:auto'>";


for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_distinct);

// ====== BUSCA PRODUTO ======================================================================================
$busca_prod_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$aux_bp_geral[0]' AND estado_registro!='EXCLUIDO'");
$aux_prod_2 = mysqli_fetch_row($busca_prod_2);

$cod_prod_2 = $aux_prod_2[0];
$prod_print_2 = $aux_prod_2[22];
// ==============================================================================================================

$soma_quant_liq = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));

$soma_quant_previsto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quant_quebra_previsto) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));

$soma_quant_realizado = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quant_quebra_realizado) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));
if ($cod_prod_2 == 2 or $cod_prod_2 == 10)
{$soma_quant_realizado_x = $soma_quant_realizado[0]*60;}
else
{$soma_quant_realizado_x = $soma_quant_realizado[0];}

$soma_quant_liq_print = number_format($soma_quant_liq[0],0,",",".");
$soma_quant_previsto_print = number_format($soma_quant_previsto[0],0,",",".");

// CORRIGIR ISSO
/*
if ($aux_bp_geral[0] == 3)
{$soma_quant_realizado_print = number_format(($soma_quant_realizado[0]*60),0,",",".");}
else
$soma_quant_realizado_print = number_format($soma_quant_realizado[0],0,",",".");
*/

$soma_quant_realizado_print = number_format($soma_quant_realizado_x,0,",",".");

$dif_prev_real = ($soma_quant_realizado_x - $soma_quant_previsto[0]);
$dif_prev_real_print = number_format($dif_prev_real,0,",",".");

/*
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq AND cod_produto='$aux_bp_geral[0]'"));
$soma_quant_print = number_format($soma_quant_produto[0],2,",",".");
*/
	echo "
	<div style='height:38px; width:285px; border:0px solid #000; float:left; margin-left:25px'>
	<div class='total' style='height:33px; width:280px; margin-top:0px' title='Peso total L&iacute;quido: $soma_quant_liq_print Kg'>
		<div class='total_nome' style='width:120px; height:15px; border:0px solid #999; font-size:10px; margin-top:2px'><b>$prod_print_2</b></div>
		<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:10px; margin-top:2px'>Desc. Realizado: $soma_quant_realizado_print Kg</div>
		<div class='total_nome' style='width:120px; height:15px; border:0px solid #999; font-size:10px; margin-top:0px; color:#444'>&ne; $dif_prev_real_print Kg</div>
		<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:10px; margin-top:0px'>Desc. Previsto: $soma_quant_previsto_print Kg</div>

	</div>
	</div>";

}


echo "</div>";
?>