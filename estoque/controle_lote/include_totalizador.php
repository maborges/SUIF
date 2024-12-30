<?php
$numero_divs = ceil($linha_produto_distinct / 4);
$alt_div = $numero_divs * 37;
$altura_div = $alt_div . "px";

echo "<div style='height:$altura_div; width:1250px; border:0px solid #000; margin:auto'>";


for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo_armazenado) FROM cadastro_lote WHERE estado_registro!='EXCLUIDO' AND cod_armazem='$cod_armazem_form' AND filial='$filial' AND cod_produto='$aux_bp_geral[0]'"));
$soma_quant_aux = $soma_quant_produto[0];
$aux_bp_geral_aux = $aux_bp_geral[0];

if ($aux_bp_geral_aux == "2")
{$soma_quant_aux_2 = ($soma_quant_aux / 60);
$soma_quant_print = number_format($soma_quant_aux_2,0,",",".") . " Sc";}
else
{$soma_quant_print = number_format($soma_quant_aux,0,",",".") . " Kg";}

	if ($soma_quant_produto[0] == 0)
	{echo "";}
	else
	{
	echo "
	<div style='height:35px; width:285px; border:0px solid #000; float:left; margin-left:25px'>
	<div class='total' style='height:26px; width:280px; margin-top:6px'>
		<div class='total_nome' style='width:140px; height:15px; border:0px solid #999'><b>$aux_bp_geral[22]</b></div>
		<div class='total_valor' style='width:120px; height:15px; border:0px solid #999'>$soma_quant_print</div>
	</div>
	</div>";
	}

}


echo "</div>";
?>