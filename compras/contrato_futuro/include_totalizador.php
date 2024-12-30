<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);


	if ($botao != "BUSCAR" and $pagina == "index_contrato_futuro")
	{
		$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO'"));
		$soma_adquirido = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_adquirida) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO'"));
	}

	elseif ($botao != "BUSCAR" and $pagina == "relatorio_fornecedor")
	{
		$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO' AND $mysql_fornecedor"));
		$soma_adquirido = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_adquirida) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO' AND $mysql_fornecedor"));
	}
	
	else
	{
		$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]'"));
		$soma_adquirido = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_adquirida) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]'"));
	}


	if ($soma_futuros[0] == 0 or $soma_adquirido[0] == 0)
	{echo "";}
	else
	{
	$juros = ($soma_futuros[0] - $soma_adquirido[0]);
	$juros_porcento = ($juros / $soma_adquirido[0]) * 100;
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");
	$soma_adquirido_print = number_format($soma_adquirido[0],2,",",".");
	$juros_print = number_format($juros,2,",",".");
	$juros_porc_print = number_format($juros_porcento,0,",",".") . "%";
	
	echo "
	<div id='centro' style='height:35px; width:1250px; border:0px solid #999; margin:auto; background-color:#FFF; font-size:11px'>
	<div style='height:26px; width:930px; border:1px solid #009900; border-radius:5px; background-color:#EEE; margin-left:0px'>
		<div style='width:180px; color:#009900; float:left; margin-left:8px; margin-top:5px; overflow:hidden'><b>$aux_bp_geral[22]</b></div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Quant. Adquirida:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; overflow:hidden'><b>$soma_adquirido_print</b> $aux_bp_geral[26]</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Quant. Entregar:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; overflow:hidden'><b>$soma_futuros_print</b> $aux_bp_geral[26]</div>
		<div style='width:70px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Juros:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; overflow:hidden'>$juros_print $aux_bp_geral[26] 
			<font title='M&eacute;dia de Juros'>($juros_porc_print)</font></div>
	</div>
	</div>";
	}

}
?>