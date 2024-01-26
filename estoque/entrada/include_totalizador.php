<?php
$numero_divs = ceil($linha_produto_distinct / 3);
$altura_div = ($numero_divs * 50) . "px";

echo "<div style='height:$altura_div; width:1450px; border:1px solid transparent; margin:auto'>";


for ($sc = 1; $sc <= $linha_produto_distinct; $sc++) {
	$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

	$cod_produto_t = $aux_bp_distinct[0];
	$produto_print_t = $aux_bp_distinct[1];
	//$unidade_print_t = $aux_bp_distinct[2];
	$unidade_print_t = "KG";
	$nome_imagem_produto_t = $aux_bp_distinct[3];
	$soma_quantidade_geral = $aux_bp_distinct[4];

	if (empty($nome_imagem_produto_t)) {
		$link_img_produto_t = "";
	} else {
		$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";
	}

	$soma_quantidade_print = number_format($soma_quantidade_geral, 2, ",", ".") . " $unidade_print_t";


	echo "
<div style='height:50px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:20px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:150px; height:20px; border:0px solid #999; font-size:12px; margin-top:14px'>$soma_quantidade_print</div>
</div>
</div>";
}

echo "</div>";
