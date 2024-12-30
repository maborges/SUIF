<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "pagamentos_periodo_impressao";
$titulo = "Pagamentos";
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
$forma_pgto_busca = $_POST["forma_pgto_busca"];
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

$mysql_filtro_data = "data_pagamento BETWEEN '$data_inicial_busca' AND '$data_final_busca'";
if ($data_inicial_busca == $data_final_busca)
{$periodo_print = " | Data: " . $data_inicial_br;}
else
{$periodo_print = " | Pe&iacute;odo: " . $data_inicial_br . " at&eacute; " . $data_final_br;}


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "codigo_pessoa IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "codigo_pessoa='$fornecedor_pesquisa'";
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


if (empty($forma_pgto_busca) or $forma_pgto_busca == "GERAL")
	{$mysql_forma_pgto = "forma_pagamento IS NOT NULL";
	$forma_pgto_busca = "GERAL";
	$forma_pgto_print = "";}
else
	{$mysql_forma_pgto = "forma_pagamento='$forma_pgto_busca'";
	$forma_pgto_busca = $forma_pgto_busca;
	$forma_pgto_print = " | Forma de Pagamento: " . $forma_pgto_busca;}


if (empty($status_pgto_busca) or $status_pgto_busca == "GERAL")
	{$mysql_status_pgto = "situacao_pagamento IS NOT NULL";
	$status_pgto_busca = "GERAL";}
else
	{$mysql_status_pgto = "situacao_pagamento='$status_pgto_busca'";
	$status_pgto_busca = $status_pgto_busca;}


if ($ordenar_busca == "BANCO")
	{$mysql_ordenar_busca = "nome_banco, favorecido_print";}
elseif ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "favorecido_print";}
else
	{$mysql_ordenar_busca = "codigo";}


$mysql_status = "estado_registro='ATIVO'";

// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");

$busca_pgto = mysqli_query ($conexao, 
"SELECT 
	codigo,
	codigo_compra,
	codigo_favorecido,
	forma_pagamento,
	data_pagamento,
	valor,
	banco_cheque,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	estado_registro,
	situacao_pagamento,
	filial,
	codigo_pessoa,
	numero_cheque,
	banco_ted,
	origem_pgto,
	codigo_fornecedor,
	produto,
	favorecido_print,
	cod_produto,
	agencia,
	num_conta,
	tipo_conta,
	nome_banco,
	cpf_cnpj
FROM 
	favorecidos_pgto
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_forma_pgto AND
	$mysql_status_pgto
ORDER BY 
	$mysql_ordenar_busca");


$soma_pgto = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor) 
FROM 
	favorecidos_pgto 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_forma_pgto AND
	$mysql_status_pgto"));


if ($forma_pgto_busca == "CHEQUE")
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, banco_cheque, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY banco_cheque
ORDER BY banco_cheque");
}
elseif ($forma_pgto_busca == "TED")
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, nome_banco, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY banco_ted
ORDER BY nome_banco");
}
else
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, forma_pagamento, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY forma_pagamento
ORDER BY forma_pagamento");
}

include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_pgto = mysqli_num_rows ($busca_pgto);
$linha_banco_distinct = mysqli_num_rows ($busca_banco_distinct);

if ($soma_pgto[0] > 0)
{$soma_pgto_print = "TOTAL: <b>R$ " . number_format($soma_pgto[0],2,",",".") . "</b>";}
// ================================================================================================================


// ================================================================================================================
//$numero_divs = 1;
//$numero_divs = $linha_produto_distinct;
$numero_divs = ceil($linha_banco_distinct / 3);
$altura_div = ($numero_divs * 32) . "px";
// ================================================================================================================


// ===============================================================================================================
if ($linha_pgto == 1)
{$print_quant_reg = "$linha_pgto PAGAMENTO";}
elseif ($linha_pgto > 1)
{$print_quant_reg = "$linha_pgto PAGAMENTOS";}
else
{$print_quant_reg = "";}
// ===============================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Favorecido: <b>$nome_fornecedor</b>";}
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
$limite_registros = 23;
$totalizadores = $numero_divs + 1; // Total geral de cada produto no final da p�gina
$numero_paginas = ceil(($linha_pgto + $totalizadores) / $limite_registros);


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
	$filial_print $periodo_print $forma_pgto_print
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
	<div style='width:250px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Favorecido</div></div>
	<div style='width:70px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>N&ordm; Compra</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Forma de PGTO</div></div>
	<div style='width:160px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Dados Banc&aacute;rios</div></div>
	<div style='width:75px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Valor</div></div>
	<div style='width:20px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>PG</div></div>
</div>";


// ====== FUN��O FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_pgto = mysqli_fetch_row($busca_pgto);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_pgto[0];
$codigo_compra_w = $aux_pgto[1];
$codigo_favorecido_w = $aux_pgto[2];
$forma_pagamento_w = $aux_pgto[3];
$data_pagamento_w = $aux_pgto[4];
$valor_w = $aux_pgto[5];
$banco_cheque_w = $aux_pgto[6];
$observacao_w = $aux_pgto[7];
$usuario_cadastro_w = $aux_pgto[8];
$hora_cadastro_w = $aux_pgto[9];
$data_cadastro_w = $aux_pgto[10];
$estado_registro_w = $aux_pgto[11];
$situacao_pagamento_w = $aux_pgto[12];
$filial_w = $aux_pgto[13];
$codigo_pessoa_w = $aux_pgto[14];
$numero_cheque_w = $aux_pgto[15];
$banco_ted_w = $aux_pgto[16];
$origem_pgto_w = $aux_pgto[17];
$codigo_fornecedor_w = $aux_pgto[18];
$produto_w = $aux_pgto[19];
$favorecido_print = $aux_pgto[20];
$cod_produto_w = $aux_pgto[21];
$agencia_w = $aux_pgto[22];
$num_conta_w = $aux_pgto[23];
$tipo_conta_w = $aux_pgto[24];
$nome_banco_w = $aux_pgto[25];
$cpf_cnpj_w = $aux_pgto[26];


$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_w));
$valor_print = number_format($valor_w,2,",",".");


if($situacao_pagamento_w == "PAGO")
{$situacao_pagamento_print = "<b>&#10004;</b>";}
elseif($situacao_pagamento_w == "EM_ABERTO")
{$situacao_pagamento_print = "";}
else
{$situacao_pagamento_print = "";}


if($tipo_conta_w == "corrente")
{$tipo_conta_aux = "C/C";}
elseif($tipo_conta_w == "poupanca")
{$tipo_conta_aux = "C/P";}
else
{$tipo_conta_aux = "";}


if($banco_cheque_w == "SICOOB")
{$banco_cheque_aux = "Sicoob";}
elseif($banco_cheque_w == "BANCO DO BRASIL")
{$banco_cheque_aux = "Banco do Brasil";}
elseif($banco_cheque_w == "BANESTES")
{$banco_cheque_aux = "Banestes";}
else
{$banco_cheque_aux = "";}


if($origem_pgto_w == "SOLICITACAO")
{$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";}
else
{$origem_pgto_print = "COMPRA";
$codigo_compra_print = $codigo_compra_w;}


if($forma_pagamento_w == "TED")
{$forma_pagamento_print = "Transfer&ecirc;ncia";
$nome_banco_print = $nome_banco_w;
$agencia_print = $agencia_w;
$num_conta_print = $num_conta_w;
$tipo_conta_print = $tipo_conta_aux;
$dados_bancarios_print = "AG: " . $agencia_print . " " . $tipo_conta_print . ": " . $num_conta_print;}
elseif($forma_pagamento_w == "CHEQUE")
{$forma_pagamento_print = "Cheque";
$nome_banco_print = $banco_cheque_aux;
$agencia_print = "";
$num_conta_print = $numero_cheque_w;
$tipo_conta_print = "";
$dados_bancarios_print = "N&ordm; Cheque: " . $numero_cheque_w;}
else
{$forma_pagamento_print = $forma_pagamento_w;
$nome_banco_print = "";
$agencia_print = "";
$num_conta_print = "";
$tipo_conta_print = "";
$dados_bancarios_print = "";}


$conta_caracter = strlen($cpf_cnpj_w);
if ($conta_caracter == 14)
{$cpf_cnpj_print = "CPF: " . $cpf_cnpj_w;}
elseif ($conta_caracter > 14)
{$cpf_cnpj_print = "CNPJ: " . $cpf_cnpj_w;}
else
{$cpf_cnpj_print = "";}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if (empty($id_w))
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:750px; height:32px; border:1px solid #FFF; margin-top:4px; float:left; color:#000; font-size:10px'>

		<div style='width:65px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px'>$data_pgto_print</div>
		</div>
		
		<div style='width:250px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='width:240px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$favorecido_print</div>
		<div style='width:240px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$cpf_cnpj_print</div>
		</div>
		
		<div style='width:70px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$codigo_compra_print</div></div>

		<div style='width:80px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$forma_pagamento_print</div></div>

		<div style='width:160px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
		<div style='width:154px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$nome_banco_print</div>
		<div style='width:154px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$dados_bancarios_print</div>		
		</div>
		
		<div style='width:75px; height:30px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-right:6px; overflow:hidden; margin-top:2px'><b>$valor_print</b></div></div>

		<div style='width:20px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
		<div style='height:11px; margin-left:0px; margin-top:8px'>$situacao_pagamento_print</div></div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_pgto == 0)
{echo "
<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
<div style='width:705px; height:17px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
<i>Nenhum pagamento encontrado.</i></div>
</div>";}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{$vazio = $contador_vazio - $totalizadores + $numero_divs;}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
		if ($v == 1)
		{echo "<div style='width:750px; height:32px; border:1px solid #FFF; margin-top:1px; float:left; color:#000; font-size:11px'>
				<div style='width:200px; height:18px; margin-right:35px; margin-top:8px; float:right; text-align:right'>$soma_pgto_print</div></div>";}
		
		elseif ($sc <= $linha_banco_distinct and $v == 2)
		{
			echo "<div style='width:750px; height:$altura_div; border:1px solid #FFF; float:left; color:#000; font-size:11px'>";
			
			for ($sc=1 ; $sc<=$linha_banco_distinct ; $sc++)
			{

			$aux_banco_distinct = mysqli_fetch_row($busca_banco_distinct);
			
			$banco_ted_b = "(" . $aux_banco_distinct[0] . ")";
			$nome_banco_b = $aux_banco_distinct[1];
			$soma_banco_b = $aux_banco_distinct[2];
			
			$soma_banco_print = "R$ " . number_format($soma_banco_b,2,",",".");
			
			if($nome_banco_b == "TED")
			{$nome_banco_b = "TRANSFER&Ecirc;NCIAS";}
			elseif($nome_banco_b == "CHEQUE")
			{$nome_banco_b = "CHEQUES";}
			else
			{$nome_banco_b = $nome_banco_b;}


			echo "
				<div style='width:225px; height:26px; border:1px solid #000; margin-right:20px; margin-top:4px; float:left'>
					<div style='width:130px; height:14px; margin-left:5px; margin-top:6px; float:left'><b>$nome_banco_b</b></div>
					<div style='width:85px; height:14px; margin-left:0px; margin-top:6px; float:left'>$soma_banco_print</div>
				</div>";
			
			}
			echo "</div>";
		
		}
		
		else
		{echo "<div style='width:750px; height:32px; border:1px solid #FFF; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>";}
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