<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "saldo_armazenado_sint_impressao";
$titulo = "Relat&oacute;rio de Saldo de Armazenado";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ======= RECEBE POST ============================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$filial = $filial_usuario;

$cod_produto_busca = $_POST["cod_produto_busca"];
$saldo_busca = $_POST["saldo_busca"];
$ordenar_busca = $_POST["ordenar_busca"];
$filial_busca = $_POST["filial_busca"];


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "saldo_armazenado.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "saldo_armazenado.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if ($saldo_busca == "DEVEDOR")
	{$mysql_saldo_busca = "saldo_armazenado.saldo <= 0";
	$saldo_busca = $saldo_busca;}
elseif ($saldo_busca == "CREDOR")
	{$mysql_saldo_busca = "saldo_armazenado.saldo > 0";
	$saldo_busca = $saldo_busca;}
else
	{$mysql_saldo_busca = "saldo_armazenado.saldo IS NOT NULL";
	$saldo_busca = "GERAL";}


if ($ordenar_busca == "SALDO_MAIOR")
	{$mysql_ordenar_busca = "saldo_armazenado.saldo DESC";
	$ordenar_busca = $ordenar_busca;}
elseif ($ordenar_busca == "SALDO_MENOR")
	{$mysql_ordenar_busca = "saldo_armazenado.saldo ASC";
	$ordenar_busca = $ordenar_busca;}
else
	{$mysql_ordenar_busca = "saldo_armazenado.fornecedor_print";
	$ordenar_busca = "PRODUTOR";}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "saldo_armazenado.filial IS NOT NULL";
	$filial_busca = "GERAL";
	$filial_print = "Filial: TODAS";}
else
	{$mysql_filial = "saldo_armazenado.filial='$filial_busca'";
	$filial_busca = $filial_busca;
	$filial_print = "Filial: " . $filial_busca;}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_saldo = mysqli_query ($conexao,
"SELECT
	cod_fornecedor,
	fornecedor_print,
	cod_produto,
	produto_print,
	unidade_print,
	saldo
FROM
	saldo_armazenado
WHERE
	$mysql_filial AND
	$mysql_cod_produto AND
	$mysql_saldo_busca
ORDER BY
	$mysql_ordenar_busca");


$busca_produto_distinct = mysqli_query ($conexao,
"SELECT DISTINCT
	saldo_armazenado.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem
FROM
	saldo_armazenado,
	cadastro_produto
WHERE
	($mysql_filial AND
	$mysql_cod_produto AND
	$mysql_saldo_busca) AND
	saldo_armazenado.cod_produto=cadastro_produto.codigo
ORDER BY
	saldo_armazenado.cod_produto");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_saldo = mysqli_num_rows ($busca_saldo);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
// ================================================================================================================


// ================================================================================================================
//$numero_divs = ceil($linha_banco_distinct / 3);
//$numero_divs = 1;
$numero_divs = $linha_produto_distinct;
$altura_div = ($numero_divs * 17) . "px";
// ================================================================================================================


// =======================================================================================================
include ("../../includes/head_impressao.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onLoad="imprimir()">

<div style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             					 #######
// #################################################################################################################################
$limite_registros = 38;
$totalizadores = $numero_divs + 1; // Total geral de cada produto no final da página
$numero_paginas = 1;


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{

echo "
<div style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:755px; height:90px; border:0px solid #D85; float:left; margin-top:15px; margin-left:10px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:150px; height:68px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' height='68px' />
	</div>

	<div style='width:400px; height:68px; border:0px solid #000; font-size:16px; float:left' align='center'>
	<div style='margin-top:25px'>$titulo</div>
	</div>

	<div style='width:150px; height:68px; border:0px solid #000; font-size:11px; float:right' align='right'>
	<div style='margin-top:25px'>$data_hoje_br<br />$hora_br</div>
	</div>
<!-- ====================== -->


<!-- ====================== -->
	<div style='width:552px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>
	$filial_print
	</div>

	<div style='width:150px; height:16px; border:0px solid #000; font-size:11px; float:right' align='right'>
	</div>
<!-- ====================== -->

</div>



<!-- =================================================================================================================== -->

<div style='width:755px; height:890px; border:0px solid #00E; margin-top:2px; margin-left:10px; float:left'>";






// ========================================================================================================
for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

$cod_produto_t = $aux_bp_distinct[0];
$produto_print_t = $aux_bp_distinct[1];
$unidade_print_t = $aux_bp_distinct[2];
$nome_imagem_produto_t = $aux_bp_distinct[3];

// ===========================================================================================================
include ("../../includes/conecta_bd.php");
	
$soma_total_credor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND $mysql_cod_produto AND saldo > 0 AND cod_produto='$cod_produto_t'"));

$soma_total_devedor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND $mysql_cod_produto AND saldo <= 0 AND cod_produto='$cod_produto_t'"));

include ("../../includes/desconecta_bd.php");
// ===========================================================================================================


// ===========================================================================================================
$total_credor_print = number_format($soma_total_credor[0],2,",",".") . " $unidade_print_t";
$total_devedor_print = number_format($soma_total_devedor[0],2,",",".") . " $unidade_print_t";

echo "
<div style='width:750px; height:17px; border:1px solid #000; margin-top:4px; float:left; color:#000; font-size:10px'>

	<div style='width:220px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$produto_print_t</b></div>
	</div>

	<div style='width:220px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
		<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>TOTAL CREDOR: $total_credor_print</div>
	</div>

	<div style='width:220px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
		<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>TOTAL DEVEDOR: $total_devedor_print</div>
	</div>
</div>";
}
// ========================================================================================================



echo "</div>";
// ========================================================================================================






echo "
<!-- =============================================================================================== -->
<div style='width:755px; height:10px; border:0px solid #000; margin-left:10px; margin-top:20px; float:left' align='center'>
<div style='width:755px; height:5px; border-bottom:2px solid #999; margin-left:0px; float:left'></div>
</div>


<!-- =============================================================================================== -->
<div style='width:755px; height:15px; border:0px solid #f85; float:left; margin-left:10px; font-size:17px' align='center'>
	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:left' align='center'></div>

	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:right' align='right'>
	P&Aacute;GINA $x_principal/$numero_paginas</div>
</div>
<!-- =============================================================================================== -->



<!-- =============================================================================================== -->";
echo "</div>"; // quebra de página
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->