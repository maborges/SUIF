<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_periodo_impressao";
$titulo = "Relat&oacute;rio de Entradas";
$modulo = "compras";
$menu = "relatorios";
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
$status_busca = $_POST["filial_busca"];
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
{$periodo_print = " | Data: " . $data_inicial_br;}
else
{$periodo_print = " | Pe&iacute;odo: " . $data_inicial_br . " at&eacute; " . $data_final_br;}


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

$mysql_movimentacao = "compras.movimentacao='ENTRADA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_entrada = mysqli_query ($conexao, 
"SELECT 
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	unidade,
	tipo,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	numero_romaneio,
	desconto_quantidade,
	usuario_exclusao,
	hora_exclusao,
	data_exclusao,
	cod_produto,
	fornecedor_print,
	quantidade_original_primaria
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
ORDER BY 
	codigo");


$busca_produto_distinct = mysqli_query ($conexao, 
"SELECT
	compras.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(compras.quantidade),
	SUM(compras.desconto_quantidade)
FROM 
	compras,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo) AND
	compras.cod_produto=cadastro_produto.codigo
GROUP BY
	compras.cod_produto
ORDER BY
	compras.cod_produto");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_entrada = mysqli_num_rows ($busca_entrada);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
// ================================================================================================================


// ================================================================================================================
//$numero_divs = ceil($linha_banco_distinct / 3);
//$numero_divs = 1;
$numero_divs = $linha_produto_distinct;
$altura_div = ($numero_divs * 17) . "px";
// ================================================================================================================


// ===============================================================================================================
if ($linha_entrada == 1)
{$print_quant_reg = "$linha_entrada ENTRADA";}
elseif ($linha_entrada > 1)
{$print_quant_reg = "$linha_entrada ENTRADAS";}
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
$numero_paginas = ceil(($linha_entrada + $totalizadores) / $limite_registros);


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
	<div style='width:60px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>N&ordm; Entrada</div></div>
	<div style='width:60px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>N&ordm; Romaneio</div></div>
	<div style='width:115px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Produto</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Desconto</div></div>
	<div style='width:100px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Quantidade</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_entrada = mysqli_fetch_row($busca_entrada);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_entrada[0];
$numero_compra_w = $aux_entrada[1];
$cod_fornecedor_w = $aux_entrada[2];
$produto_print_w = $aux_entrada[3];
$data_compra_w = $aux_entrada[4];
$quantidade_w = $aux_entrada[5];
$unidade_w = $aux_entrada[6];
$tipo_w = $aux_entrada[7];
$observacao_w = $aux_entrada[8];
$usuario_cadastro_w = $aux_entrada[9];
$hora_cadastro_w = $aux_entrada[10];
$data_cadastro_w = $aux_entrada[11];
$usuario_alteracao_w = $aux_entrada[12];
$hora_alteracao_w = $aux_entrada[13];
$data_alteracao_w = $aux_entrada[14];
$estado_registro_w = $aux_entrada[15];
$filial_w = $aux_entrada[16];
$numero_romaneio_w = $aux_entrada[17];
$desconto_quantidade_w = $aux_entrada[18];
$usuario_exclusao_w = $aux_entrada[19];
$hora_exclusao_w = $aux_entrada[20];
$data_exclusao_w = $aux_entrada[21];
$cod_produto_w = $aux_entrada[22];
$fornecedor_print_w = $aux_entrada[23];
$quantidade_original_primaria_w = $aux_entrada[24];


$quantidade_bruta = $quantidade_w + $desconto_quantidade_w;

$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " $unidade_w";
$desconto_quantidade_print = number_format($desconto_quantidade_w,2,",",".") . " $unidade_w";
$quantidade_bruta_print = number_format($quantidade_bruta,2,",",".") . " $unidade_w";
// ======================================================================================================


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
		
		<div style='width:240px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:5px; margin-top:2px; overflow:hidden'>$fornecedor_print_w</div>
		</div>
		
		<div style='width:60px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$numero_compra_w</div>
		</div>

		<div style='width:60px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$numero_romaneio_w</div>
		</div>

		<div style='width:115px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$produto_print_w</div>
		</div>

		<div style='width:80px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$desconto_quantidade_print</div>
		</div>
		
		<div style='width:100px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:5px; overflow:hidden; margin-top:2px'>$quantidade_print</div>
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



if ($linha_entrada == 0)
{echo "
<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
<div style='width:705px; height:17px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
<i>Nenhuma entrada encontrada.</i></div>
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
			$soma_quantidade_geral = $aux_bp_distinct[4];
			$soma_desconto_geral = $aux_bp_distinct[5];
			
			if (empty($nome_imagem_produto_t))
			{$link_img_produto_t = "";}
			else
			{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}
			
			
			$soma_quantidade_bruta = $soma_quantidade_geral + $soma_desconto_geral;
			
			$soma_quantidade_print = number_format($soma_quantidade_geral,2,",",".") . " $unidade_print_t";
			$soma_desconto_print = number_format($soma_desconto_geral,2,",",".") . " $unidade_print_t";
			$soma_quant_bruta_print = number_format($soma_quantidade_bruta,2,",",".") . " $unidade_print_t";

			echo "
			<div style='width:750px; height:17px; border:1px solid #000; margin-top:4px; float:left; color:#000; font-size:10px'>
			
				<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'><b>$produto_print_t</b></div>
				</div>
	
				<div style='width:180px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>QUANT. BRUTA: $soma_quant_bruta_print</div>
				</div>
	
				<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>DESCONTO: $soma_desconto_print</div>
				</div>
	
				<div style='width:180px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
					<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'>QUANT. L&Iacute;QUIDA: $soma_quantidade_print</div>
				</div>
			</div>";
			}
		}
		
		elseif ($v == 3)
		{
		echo "
		<div style='width:750px; height:17px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>
		
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'></div>
			</div>

			<div style='width:180px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'></div>
			</div>

			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF; margin-left:20px'>
				<div style='width:auto; height:11px; margin-left:5px; margin-top:2px; float:left'></div>
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