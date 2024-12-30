<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "relatorio_ultima_compra_impressao";
$titulo = "Relat&oacute;rio de Compras - &Uacute;ltimas Movimenta&ccedil;&otilde;es";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);

$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $_POST["filial_busca"];
$ordenar_busca = "NOME";
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";
if ($data_inicial_busca == $data_final_busca)
{$periodo_print = " | Data: " . $data_inicial_br;}
else
{$periodo_print = " | Pe&iacute;odo: " . $data_inicial_br . " at&eacute; " . $data_final_br;}


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "compras.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "filial IS NOT NULL";
	$filial_busca = "GERAL";
	$filial_print = "Filial: TODAS";}
else
	{$mysql_filial = "filial='$filial_busca'";
	$filial_busca = $filial_busca;
	$filial_print = "Filial: " . $filial_busca;}


if ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "fornecedor_print";}
else
	{$mysql_ordenar_busca = "data_compra, fornecedor_print";}


$mysql_status = "estado_registro='ATIVO'";

$mysql_movimentacao = "movimentacao='COMPRA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_compra = mysqli_query ($conexao, 
"SELECT 
	codigo,
	fornecedor,
	produto,
	MAX(data_compra),
	SUM(quantidade),
	unidade,
	estado_registro,
	filial,
	fornecedor_print
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto
GROUP BY
	fornecedor, cod_produto
ORDER BY 
	$mysql_ordenar_busca");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
// ================================================================================================================


// ================================================================================================================
//$numero_divs = ceil($linha_banco_distinct / 3);
//$numero_divs = 1;
$numero_divs = $linha_produto_distinct;
$altura_div = ($numero_divs * 17) . "px";
// ================================================================================================================


// ===============================================================================================================
if ($linha_compra == 1)
{$print_quant_reg = "$linha_compra PRODUTOR";}
elseif ($linha_compra > 1)
{$print_quant_reg = "$linha_compra PRODUTORES";}
else
{$print_quant_reg = "";}
// ===============================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
// ================================================================================================================


// =======================================================================================================
include ("../../includes/head_impressao.php");
?>

<!-- ====== T�TULO DA P�GINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== IN�CIO ================================================================================================ -->
<body onLoad="imprimir()">

<div style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecer� em cada p�gina de impress�o #######
// #######           � importante sempre testar antes para ver quantas linhas s�o necess�rias             					 #######
// #################################################################################################################################
$limite_registros = 40;
$totalizadores = $numero_divs; // Total geral de cada produto no final da p�gina
$numero_paginas = ceil(($linha_compra + $totalizadores) / $limite_registros);


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
	$filial_print $periodo_print
	</div>

	<div style='width:150px; height:16px; border:0px solid #000; font-size:11px; float:right' align='right'>
	$print_quant_reg
	</div>
<!-- ====================== -->

</div>
<!-- =================================================================================================================== -->



<!-- =================================================================================================================== -->
<div style='width:755px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:10px; float:left'>

<div style='width:750px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:240px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Fornecedor</div></div>
	<div style='width:90px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>&Uacute;ltima Compra</div></div>
	<div style='width:140px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produto</div></div>
	<div style='width:120px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Quantidade Total</div></div>
	<div style='width:140px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Filial</div></div>
</div>";


// ====== FUN��O FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_compra[0];
$cod_fornecedor_w = $aux_compra[1];
$produto_print_w = $aux_compra[2];
$data_compra_w = $aux_compra[3];
$quantidade_w = $aux_compra[4];
$unidade_w = $aux_compra[5];
$estado_registro_w = $aux_compra[6];
$filial_w = $aux_compra[7];
$fornecedor_print_w = $aux_compra[8];


$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " " . $unidade_w;
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if (empty($id_w))
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>

		<div style='width:240px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$fornecedor_print_w</div>
		</div>

		<div style='width:90px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px'>$data_compra_print</div>
		</div>

		<div style='width:140px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$produto_print_w</div>
		</div>

		<div style='width:120px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$quantidade_print</div>
		</div>

		<div style='width:140px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$filial_w</div>
		</div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_compra == 0)
{echo "
<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
<div style='width:705px; height:17px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
<i>Nenhuma compra encontrada.</i></div>
</div>";}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{$vazio = $contador_vazio;}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
		echo "<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'></div>";
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
echo "</div>"; // quebra de p�gina
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->