<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "ficha_produtor_impressao";
$titulo = "Ficha do Produtor";
$modulo = "compras";
$menu = "ficha_produtor";
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
function ConverteData($data_x){
	if (strstr($data_x, "/"))
	{
	$d = explode ("/", $data_x);
	$rstData = "$d[2]-$d[1]-$d[0]";
	return $rstData;
	}
}
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);

$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $_POST["filial_busca"];
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "compras.data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";
if ($data_inicial_busca == $data_final_busca)
{$periodo_print = "Data: " . $data_inicial_br;}
else
{$periodo_print = "Pe&iacute;odo: " . $data_inicial_br . " at&eacute; " . $data_final_br;}

$dia_atras = date('Y-m-d', strtotime('-1 days', strtotime($data_inicial_busca)));

if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "compras.fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "compras.fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "compras.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "compras.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($cod_tipo_busca) or $cod_tipo_busca == "GERAL")
	{$mysql_cod_tipo = "compras.cod_tipo IS NOT NULL";
	$cod_tipo_busca = "GERAL";}
else
	{$mysql_cod_tipo = "compras.cod_tipo='$cod_tipo_busca'";
	$cod_tipo_busca = $cod_tipo_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "compras.filial IS NOT NULL";
	$filial_busca = "GERAL";
	$filial_print = "Filial: TODAS";}
else
	{$mysql_filial = "compras.filial='$filial_busca'";
	$filial_busca = $filial_busca;
	$filial_print = "Filial: " . $filial_busca;}


$mysql_status = "compras.estado_registro='ATIVO'";
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
	data_pagamento,
	movimentacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	fornecedor_print,
	forma_entrega,
	usuario_exclusao,
	hora_exclusao,
	data_exclusao,
	numero_romaneio
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
ORDER BY 
	codigo");


$busca_tipo_distinct = mysqli_query ($conexao, 
"SELECT
	compras.cod_produto,
	compras.cod_tipo,
	compras.tipo
FROM 
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
GROUP BY
	compras.cod_tipo
ORDER BY
	compras.cod_tipo");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$linha_tipo_distinct = mysqli_num_rows ($busca_tipo_distinct);
// ================================================================================================================


// ================================================================================================================
//$numero_divs = ceil($linha_banco_distinct / 3);
//$numero_divs = 1;
$numero_divs = $linha_tipo_distinct;
$altura_div = ($numero_divs * 17) . "px";
// ================================================================================================================


// ===============================================================================================================
/*
if ($linha_compra == 1)
{$print_quant_reg = "$linha_compra COMPRA";}
elseif ($linha_compra > 1)
{$print_quant_reg = "$linha_compra COMPRAS";}
else
{$print_quant_reg = "";}
*/
// ===============================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
include ("../../includes/conecta_bd.php");
$busca_produto = mysqli_query ($conexao,
"SELECT
	descricao,
	produto_print,
	unidade_print,
	nome_imagem
FROM
	cadastro_produto
WHERE
	codigo='$cod_produto_busca'");
include ("../../includes/desconecta_bd.php");

$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[0];
$produto_print_2 = $aux_bp[1];
$unidade_produto = $aux_bp[2];
$nome_imagem_produto = $aux_bp[3];
if ($nome_imagem_produto == "")
{$link_imagem_produto = "";}
else
{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:75px'>";}
// ======================================================================================================


// ====== SALDO DO FORNECEDOR ===========================================================================
include ("../../includes/conecta_bd.php");
$soma_quant_entrada = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));

$soma_quant_saida = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
include ("../../includes/desconecta_bd.php");

// CALCULA SALDO GERAL POR FORNECEDOR
$saldo_geral = ($soma_quant_entrada[0] - $soma_quant_saida[0]);
$saldo_geral_print = number_format($saldo_geral,2,",",".");
// ======================================================================================================


// ====== SOMA SALDO ANTERIOR  ===========================================================================
include ("../../includes/conecta_bd.php");
$soma_ant_entrada = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	data_compra<='$dia_atras' AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));

$soma_ant_saida = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	data_compra<='$dia_atras' AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
include ("../../includes/desconecta_bd.php");

// CALCULA SALDO ANTERIOR
$saldo_ant = ($soma_ant_entrada[0] - $soma_ant_saida[0]);
$saldo_ant_print = number_format($saldo_ant,2,",",".");
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
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
$limite_registros = 40;
$totalizadores = $numero_divs + 2; // Total geral de cada produto no final da página
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
	<div style='width:400px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>
	<b>$nome_fornecedor</b>
	</div>

	<div style='width:350px; height:16px; border:0px solid #000; font-size:11px; float:right' align='right'>
	$periodo_print | Saldo Anterior: $saldo_ant_print $unidade_produto
	</div>
<!-- ====================== -->

</div>
<!-- =================================================================================================================== -->



<!-- =================================================================================================================== -->
<div style='width:755px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:10px; float:left'>

<div style='width:750px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:65px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Data</div></div>
	<div style='width:120px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Movimenta&ccedil;&atilde;o</div></div>
	<div style='width:60px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>N&ordm; Compra</div></div>
	<div style='width:110px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produto</div></div>
	<div style='width:110px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Tipo</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Pre&ccedil;o Unit&aacute;rio</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Quantidade</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Saldo</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
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
$total_geral_w = $aux_compra[7];
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$data_pagamento_w = $aux_compra[11];
$movimentacao_w = $aux_compra[12];
$usuario_cadastro_w = $aux_compra[13];
$hora_cadastro_w = $aux_compra[14];
$data_cadastro_w = $aux_compra[15];
$usuario_alteracao_w = $aux_compra[16];
$hora_alteracao_w = $aux_compra[17];
$data_alteracao_w = $aux_compra[18];
$estado_registro_w = $aux_compra[19];
$filial_w = $aux_compra[20];
$fornecedor_print_w = $aux_compra[21];
$forma_entrega_w = $aux_compra[22];
$usuario_exclusao_w = $aux_compra[23];
$hora_exclusao_w = $aux_compra[24];
$data_exclusao_w = $aux_compra[25];
$numero_romaneio_w = $aux_compra[26];


$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".");
$data_pagamento_print = date('d/m/Y', strtotime($data_pagamento_w));

if ($preco_unitario_w == 0)
{$preco_unitario_print = "";}
else
{$preco_unitario_print = number_format($preco_unitario_w,2,",",".");}

if ($total_geral_w == 0)
{$total_geral_print = "";}
else
{$total_geral_print = "R$ " . number_format($total_geral_w,2,",",".");}


if ($movimentacao_w == "COMPRA")
{$movimentacao_print = "COMPRA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "ENTRADA")
{$movimentacao_print = "ENTRADA $numero_romaneio_w";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "TRANSFERENCIA_ENTRADA")
{$movimentacao_print = "ENTRADA TRANSFER&Ecirc;NCIA";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/transferencias/transferencia_visualizar";}

elseif ($movimentacao_w == "ENTRADA_FUTURO")
{$movimentacao_print = "ENTRADA CONTRATO FUTURO";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "TRANSFERENCIA_SAIDA")
{$movimentacao_print = "SA&Iacute;DA TRANSFER&Ecirc;NCIA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/transferencias/transferencia_visualizar";}

elseif ($movimentacao_w == "SAIDA_FUTURO")
{$movimentacao_print = "SA&Iacute;DA PGTO CONTRATO FUTURO";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "SAIDA")
{$movimentacao_print = "SA&Iacute;DA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

else
{$movimentacao_print = $movimentacao_w;}



// ====== CALCULO SALDO ATUAL  =========================================================================
if ($tipo_movimentacao == "SAIDA")
{$saldo_atual = $saldo_ant - $quantidade_w;}
else
{$saldo_atual = $saldo_ant + $quantidade_w;}
$saldo_atual_print = number_format($saldo_atual,2,",",".");
$saldo_ant = $saldo_atual;
// =====================================================================================================





// ====== RELATORIO =====================================================================================
	if (empty($id_w))
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>

		<div style='width:65px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px'>$data_compra_print</div>
		</div>
		
		<div style='width:120px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$movimentacao_print</div>
		</div>

		<div style='width:60px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$numero_compra_w</div>
		</div>

		<div style='width:110px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$produto_print_w</div>
		</div>
		
		<div style='width:110px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$tipo_w</div>
		</div>

		<div style='width:80px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$preco_unitario_print</div>
		</div>

		<div style='width:80px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'><b>$quantidade_print</b> $unidade_w</div>
		</div>
		
		<div style='width:80px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$saldo_atual_print $unidade_produto</div>
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
<i>Nenhuma movimenta&ccedil;&atilde;o encontrada nesse per&iacute;odo</i></div>
</div>";}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{$vazio = $contador_vazio - $totalizadores + $linha_tipo_distinct;}
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

		elseif ($sc <= $linha_tipo_distinct and $v == 2)
		{
			for ($sc=1 ; $sc<=$linha_tipo_distinct ; $sc++)
			{
			$aux_bp_distinct = mysqli_fetch_row($busca_tipo_distinct);
			
			$cod_produto_t = $aux_bp_distinct[0];
			$cod_tipo_t = $aux_bp_distinct[1];
			$tipo_print_t = $aux_bp_distinct[2];
			$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>";
			
			
			include ("../../includes/conecta_bd.php");
			
			$soma_tipo_entrada = mysqli_fetch_row(mysqli_query ($conexao,
			"SELECT
				SUM(quantidade)
			FROM
				compras
			WHERE
				$mysql_filial AND
				$mysql_status AND
				$mysql_fornecedor AND
				$mysql_cod_produto AND
				cod_tipo='$cod_tipo_t' AND
				(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));
			
			$soma_tipo_saida = mysqli_fetch_row(mysqli_query ($conexao,
			"SELECT
				SUM(quantidade)
			FROM
				compras
			WHERE
				$mysql_filial AND
				$mysql_status AND
				$mysql_fornecedor AND
				$mysql_cod_produto AND
				cod_tipo='$cod_tipo_t' AND
				(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
			
			include ("../../includes/desconecta_bd.php");
			
			$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
			$saldo_tipo_print = number_format($saldo_tipo,2,",",".");
			
			if ($saldo_tipo != 0)
			{echo "
			<div style='width:450px; height:17px; border:1px solid #000; margin-top:4px; float:left; color:#000; font-size:10px'>
			
				<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>$produto_print</div>
				</div>
	
				<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>$tipo_print_t</div>
				</div>

				<div style='width:100px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>$saldo_tipo_print $unidade_produto</div>
				</div>

			</div>";}
			}
		}
		
		elseif ($v == 3)
		{
		echo "
		<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>
		
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'></div>
			</div>

			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>SALDO GERAL: </div>
			</div>

			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$saldo_geral_print $unidade_produto</b></div>
			</div>

			<div style='width:180px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'></div>
			</div>
		</div>";
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
	
	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:left' align='center'>$filial_print</div>

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