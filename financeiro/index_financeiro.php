<?php
include ("../includes/config.php"); 
include ("../includes/valida_cookies.php");
$pagina = "index_financeiro";
$titulo = "Financeiro";
$modulo = "financeiro";
$menu = "contas_pagar";
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
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../includes/menu_financeiro.php"); ?>
</div>

<div class="submenu">
<?php include ("../includes/submenu_financeiro_contas_pagar.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:400px; width:930px; border:0px solid #000; margin:auto">

<?php
/*
$mysql_filtro_data = "data_compra BETWEEN '2021-07-01' AND '2021-12-31'";


include ("../includes/conecta_bd.php");

$busca_compra = mysqli_query ($conexao, 
"SELECT 
	codigo,
	numero_compra,
	data_compra,
	valor_total,
	estado_registro,
	situacao_pagamento,
	total_pago,
	saldo_pagar
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	estado_registro='ATIVO' AND
	movimentacao='COMPRA'
ORDER BY 
	codigo");

include ("../includes/conecta_bd.php");

$linha_compra = mysqli_num_rows ($busca_compra);


for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_compra[0];
$numero_compra_w = $aux_compra[1];
$data_compra_w = $aux_compra[2];
$valor_total_w = $aux_compra[3];
$estado_registro_w = $aux_compra[4];
$situacao_pagamento_w = $aux_compra[5];
$total_pago_w = $aux_compra[6];
$saldo_pagar_w = $aux_compra[7];

if ($situacao_pagamento_w == "PAGO")
{
	include ("../includes/conecta_bd.php");
	$editar_compra = mysqli_query ($conexao, 
	"UPDATE
		compras
	SET
		total_pago='$valor_total_w',
		saldo_pagar='0'
	WHERE
		numero_compra='$numero_compra_w'");
	include ("../includes/desconecta_bd.php");
	
	$soma_alteracoes = $soma_alteracoes + 1;
}



}


echo "Total de Compras: $linha_compra </br> Total de Alterações: $soma_alteracoes";
*/
?>



</div><!-- 1º centro -->
</div><!-- centro_geral -->





<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../includes/desconecta_bd.php"); ?>
</body>
</html>