<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "contas_pagar_periodo_impressao";
$titulo = "Contas a Pagar";
$modulo = "financeiro";
$menu = "contas_pagar";
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
$status_pgto_busca = $_POST["status_pgto_busca"];
$filial_busca = $_POST["filial_busca"];
$ordenar_busca = $_POST["ordenar_busca"];
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
	{$mysql_cod_produto = "(cod_produto IS NOT NULL OR cod_produto IS NULL)";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "filial IS NOT NULL";
	$filial_busca = "GERAL";
	$filial_print = "Filial: TODAS";}
else
	{$mysql_filial = "filial='$filial_busca'";
	$filial_busca = $filial_busca;
	$filial_print = "Filial: " . $filial_busca;}


if (empty($status_pgto_busca) or $status_pgto_busca == "GERAL")
	{$mysql_status_pgto = "situacao_pagamento IS NOT NULL";
	$status_pgto_busca = "GERAL";}
else
	{$mysql_status_pgto = "situacao_pagamento='$status_pgto_busca'";
	$status_pgto_busca = $status_pgto_busca;}


if ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "fornecedor_print";}
else
	{$mysql_ordenar_busca = "codigo";}


$mysql_status = "estado_registro='ATIVO'";

$mysql_movimentacao = "movimentacao='COMPRA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");

$busca_compra = mysqli_query ($conexao, 
"SELECT 
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	preco_unitario,
	valor_total,
	unidade,
	tipo,
	observacao,
	situacao_pagamento,
	movimentacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	estado_registro,
	filial,
	cod_produto,
	fornecedor_print,
	total_pago,
	saldo_pagar
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto
ORDER BY 
	$mysql_ordenar_busca");


$soma_total_geral = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor_total) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto"));


$soma_total_pago = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(total_pago) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto"));


$soma_saldo_pagar = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(saldo_pagar) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto"));

include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_pago_print = "R$ " . number_format($soma_total_pago[0],2,",",".");
$soma_saldo_print = "R$ " . number_format($soma_saldo_pagar[0],2,",",".");
$soma_total_print = "R$ " . number_format($soma_total_geral[0],2,",",".");
// ================================================================================================================


// ================================================================================================================
//$numero_divs = ceil($linha_banco_distinct / 3);
//$numero_divs = $linha_produto_distinct;
$numero_divs = 1;
$altura_div = ($numero_divs * 17) . "px";
// ================================================================================================================


// ===============================================================================================================
if ($linha_compra == 1)
{$print_quant_reg = "$linha_compra COMPRA";}
elseif ($linha_compra > 1)
{$print_quant_reg = "$linha_compra COMPRAS";}
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
$totalizadores = $numero_divs + 2; // Total geral de cada produto no final da p�gina
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
	<div style='width:65px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Data</div></div>
	<div style='width:240px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Fornecedor</div></div>
	<div style='width:60px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>N&ordm; Compra</div></div>
	<div style='width:110px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produto</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Valor Pago</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Saldo a Pagar</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Valor Total</div></div>
</div>";


// ====== FUN��O FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_compra[0];
$numero_compra_w = $aux_compra[1];
$cod_fornecedor_w = $aux_compra[2];
$produto_print_w = $aux_compra[3];
$data_compra_w = $aux_compra[4];
$quantidade_w = $aux_compra[5];
$preco_unitario_w = $aux_compra[6];
$valor_total_w = $aux_compra[7];
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$situacao_pagamento_w = $aux_compra[11];
$movimentacao_w = $aux_compra[12];
$usuario_cadastro_w = $aux_compra[13];
$hora_cadastro_w = $aux_compra[14];
$data_cadastro_w = $aux_compra[15];
$estado_registro_w = $aux_compra[16];
$filial_w = $aux_compra[17];
$cod_produto_w = $aux_compra[18];
$fornecedor_print_w = $aux_compra[19];
$total_pago_w = $aux_compra[20];
$saldo_pagar_w = $aux_compra[21];


$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " " . $unidade_w;
$preco_unitario_print = number_format($preco_unitario_w,2,",",".");
$valor_total_print = "<b>" . number_format($valor_total_w,2,",",".") . "</b>";
$total_pago_print = number_format($total_pago_w,2,",",".");
$saldo_pagar_print = number_format($saldo_pagar_w,2,",",".");


if($situacao_pagamento_w == "PAGO")
{$situacao_pagamento_print = "PAGO";}
elseif($situacao_pagamento_w == "EM_ABERTO")
{$situacao_pagamento_print = "EM ABERTO";}
else
{$situacao_pagamento_print = "";}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if (empty($id_w))
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>

		<div style='width:65px; height:15px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px'>$data_compra_print</div>
		</div>
		
		<div style='width:240px; height:15px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$fornecedor_print_w</div>
		</div>
		
		<div style='width:60px; height:15px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$numero_compra_w</div>
		</div>

		<div style='width:110px; height:15px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$produto_print_w</div>
		</div>

		<div style='width:80px; height:15px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$total_pago_print</div>
		</div>
		
		<div style='width:80px; height:15px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$saldo_pagar_print</div>
		</div>

		<div style='width:80px; height:15px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$valor_total_print</div>
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
{$vazio = $contador_vazio - $totalizadores + $linha_produto_distinct;}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
		if ($v == 1)
		{echo "<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>
				<div style='width:200px; height:15px; margin-right:35px; margin-top:8px; float:right; text-align:right'></div></div>";}

		elseif ($v == 2)
		{echo "<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>

					<div style='width:230px; height:15px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
						<div style='width:100px; height:11px; margin-left:5px; margin-top:2px; float:left'>TOTAL PAGO</div>
						<div style='width:120px; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$soma_pago_print</b></div>
					</div>

					<div style='width:230px; height:15px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
						<div style='width:100px; height:11px; margin-left:5px; margin-top:2px; float:left'>SALDO A PAGAR</div>
						<div style='width:120px; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$soma_saldo_print</b></div>
					</div>

					<div style='width:230px; height:15px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
						<div style='width:100px; height:11px; margin-left:5px; margin-top:2px; float:left'>TOTAL GERAL</div>
						<div style='width:120px; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$soma_total_print</b></div>
					</div>

		</div>";}
		
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
echo "</div>"; // quebra de p�gina
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->