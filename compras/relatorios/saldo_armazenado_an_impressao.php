<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "saldo_armazenado_an_impressao";
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


// ===============================================================================================================
if ($linha_saldo == 1)
{$print_quant_reg = "$linha_saldo REGISTRO";}
elseif ($linha_saldo > 1)
{$print_quant_reg = "$linha_saldo REGISTROS";}
else
{$print_quant_reg = "";}
// ===============================================================================================================


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
$numero_paginas = ceil(($linha_saldo + $totalizadores) / $limite_registros);


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
	$print_quant_reg
	</div>
<!-- ====================== -->

</div>



<!-- =================================================================================================================== -->

<div style='width:755px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:10px; float:left'>

<div style='width:750px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:60px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>C&oacute;digo</div></div>
	<div style='width:310px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produtor</div></div>
	<div style='width:200px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produto</div></div>
	<div style='width:160px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Saldo</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_saldo = mysqli_fetch_row($busca_saldo);


// ====== DADOS DO CADASTRO ============================================================================
$cod_fornecedor_w = $aux_saldo[0];
$nome_pessoa_w = $aux_saldo[1];
$cod_produto_w = $aux_saldo[2];
$produto_print_w = $aux_saldo[3];
$unidade_print_w = $aux_saldo[4];
$saldo_w = $aux_saldo[5];
$saldo_print = number_format($aux_saldo[5],2,",",".");
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if ($aux_saldo[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:11px'>

		<div style='width:60px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:6px; margin-top:2px'>$cod_fornecedor_w</div></div>
		
		<div style='width:310px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:6px; margin-top:2px;  overflow:hidden'>$nome_pessoa_w</div></div>
		
		<div style='width:200px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$produto_print_w</div></div>
		
		<div style='width:160px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:6px; margin-top:2px; overflow:hidden'><b>$saldo_print $unidade_print_w</b></div></div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_saldo == 0)
{echo "
<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
<div style='width:705px; height:17px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
<i>Nenhum saldo encontrado.</i></div>
</div>";}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{$vazio = $contador_vazio - $totalizadores + $linha_produto_distinct;}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
		if ($v == 1)
		{
		echo "<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>
		<div style='width:200px; height:15px; margin-right:35px; margin-top:2px; float:right; text-align:right'></div></div>";
		}
		
		elseif ($sc <= $linha_produto_distinct and $v == 2)
		{
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
		
		}

		
		else
		{echo "<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'></div>";}
	}
	
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