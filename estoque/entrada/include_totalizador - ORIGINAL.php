<?php
$numero_divs = ceil($linha_produto_distinct / 4);
$alt_div = $numero_divs * 37;
$altura_div = $alt_div . "px";

echo "<div style='height:$altura_div; width:1250px; border:0px solid #000; margin:auto'>";


for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor 
AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$soma_quant_print = number_format($soma_quant_produto[0],0,",",".");

	if ($soma_quant_produto[0] == 0)
	{echo "";}
	else
	{
	echo "
	<div style='height:35px; width:285px; border:0px solid #000; float:left; margin-left:25px'>
	<div class='total' style='height:26px; width:280px; margin-top:6px'>
		<div class='total_nome' style='width:140px; height:15px; border:0px solid #999'><b>$aux_bp_geral[22]</b></div>
		<div class='total_valor' style='width:120px; height:15px; border:0px solid #999'>$soma_quant_print Kg</div>
	</div>
	</div>";
	}

}


echo "</div>";
?>