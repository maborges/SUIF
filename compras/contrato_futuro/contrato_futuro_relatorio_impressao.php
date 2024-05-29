<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include_once("../../helpers.php");
$pagina = 'contrato_futuro_relatorio_impressao';
$titulo = 'Relat&oacute;rio - Contratos Futuros';
$modulo = 'compras';
$menu = 'contratos';
// ================================================================================================================


$data_atual = date('d/m/Y', time());
$hora_atual = date('G:i:s', time());
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$botao_mae = $_POST["botao_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$situacao_contrato = $_POST["situacao_contrato"];
$filtro_data = $_POST["filtro_data"];

if ($botao_mae == "BUSCAR")
	{$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);}
else
	{$data_inicial_aux = $data_hoje_aux;
	$data_inicial = $data_hoje;
	$data_final_aux = $data_hoje_aux;
	$data_final = $data_hoje;}


if ($situacao_contrato == "" or $situacao_contrato == "GERAL")
	{$mysql_situacao_contrato = "situacao_contrato IS NOT NULL";
	$situacao_contrato = "GERAL";}
else
	{$mysql_situacao_contrato = "situacao_contrato='$situacao_contrato'";
	$situacao_contrato = $_POST["situacao_contrato"];}


if ($filtro_data == "" or $filtro_data == "DATA_VENCIMENTO")
	{$mysql_filtro_data = "vencimento BETWEEN '$data_inicial' AND '$data_final'";
	$filtro_data = "DATA_VENCIMENTO";}
else
	{$mysql_filtro_data = "$filtro_data BETWEEN '$data_inicial' AND '$data_final'";
	$filtro_data = $_POST["filtro_data"];}
	

if ($fornecedor_form == "" or $fornecedor_form == "GERAL")
	{$mysql_fornecedor = "produtor IS NOT NULL";
	$fornecedor_form = "GERAL";}
else
	{$mysql_fornecedor = "produtor='$fornecedor_form'";
	$fornecedor_form = $_POST["fornecedor_form"];}


if ($cod_produto_form == "" or $cod_produto_form == "TODOS")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_form = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_form'";
	$cod_produto_form = $_POST["cod_produto_form"];}
// ============================================================================================================================


// ===== BUSCA CONTRATOS =============================================================================================
if ($botao_mae != "BUSCAR" and $pagina_mae == "index_contrato_futuro")
{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' 
	AND filial='$filial' ORDER BY vencimento, nome_produtor");
}

elseif ($botao_mae != "BUSCAR" and $pagina_mae == "relatorio_fornecedor")
{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' 
	AND filial='$filial' AND $mysql_fornecedor ORDER BY vencimento, nome_produtor");
}

else
{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto ORDER BY vencimento, nome_produtor");
}


$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
// =============================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =================================================================================================================


// ====== BUSCA PRODUTO FORM ==============================================================================
$busca_prod = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_prod = mysqli_fetch_row($busca_prod);
$linhas_prod = mysqli_num_rows ($busca_prod);

$prod_print = $aux_prod[1];
// ======================================================================================================


// ====== BUSCA FORNECEDOR FORM ==============================================================================
$busca_forne = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_form' AND estado_registro!='EXCLUIDO'");
$aux_forne = mysqli_fetch_row($busca_forne);
$linhas_forne = mysqli_num_rows ($busca_forne);

if ($fornecedor_form == "")
{$forne_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_forne == 0)
{$forne_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$forne_print = $aux_forne[1];}
// =================================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_cont_futuro == 0)
{$print_quant_reg = "";}
elseif ($linha_cont_futuro == 1)
{$print_quant_reg = "$linha_cont_futuro CONTRATO";}
else
{$print_quant_reg = "$linha_cont_futuro CONTRATOS";}

if ($fornecedor_form == "" or $fornecedor_form == "GERAL")
{$print_fornecedor = "";}
else
{$print_fornecedor = "$aux_forne[1]";}

if ($botao_mae != "BUSCAR" and ($pagina_mae == "index_contrato_futuro" or $pagina_mae == "relatorio_fornecedor"))
{$print_periodo = "";}
else
{$print_periodo = "PER&Iacute;ODO: $data_inicial_aux AT&Eacute; $data_final_aux";}
// ==================================================================================================================


// ==================================================================================================================
include ('../../includes/head_impressao.php');
?>

<!-- ====== T�TULO DA P�GINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== IN�CIO ================================================================================================ -->
<body onLoad="imprimir()">

<div id="centro" style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecer� em cada p�gina de impress�o #######
// #######           � importante sempre testar antes para ver quantas linhas s�o necess�rias             					 #######
// #################################################################################################################################
$limite_registros = 48;
$numero_paginas = ceil($linha_cont_futuro / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "
<div id='centro' style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:710px; height:80px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:200px; height:40px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='160px' /></div>

	<div style='width:315px; height:40px; border:0px solid #000; font-size:16px; float:left' align='center'>
	<div style='margin-top:5px'>RELAT&Oacute;RIO DE CONTRATOS FUTUROS<br /></div></div>

	<div style='width:190px; height:40px; border:0px solid #000; font-size:11px; float:left' align='right'>
	<div style='margin-top:5px'>$data_atual<br />$hora_atual</div></div>

<!-- ====================== -->
	<div style='width:200px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'></div>

	<div style='width:315px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$prod_print</div></div>

	<div style='width:190px; height:18px; border:0px solid #000; font-size:11px; float:left' align='right'></div>

<!-- ====================== -->
	<div style='width:200px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>$print_periodo</div>

	<div style='width:315px; height:16px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$print_fornecedor</div></div>

	<div style='width:190px; height:16px; border:0px solid #000; font-size:11px; float:left' align='right'>$print_quant_reg</div>

</div>



<!-- =================================================================================================================== -->

<div style='width:710px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:40px; float:left'>

<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:63px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Data</div></div>
	<div style='width:204px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Fornecedor</div></div>
	<div style='width:48px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>N&ordm;</div></div>
	<div style='width:63px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Vencimento</div></div>
	<div style='width:100px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Produto</div></div>
	<div style='width:46px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Valor Un.</div></div>
	<div style='width:84px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Quant. Adquirida</div></div>
	<div style='width:84px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Quant. Entregar</div></div>
</div>";



for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_contrato = mysqli_fetch_row($busca_cont_futuro);

// ====== DADOS DO CONTRATO ============================================================================
$fornecedor_w = $aux_contrato[1];
$cod_produto_w = $aux_contrato[31];
$data_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[2]));
$produto_w = $aux_contrato[3];
$quantidade_w = $aux_contrato[4];
$quantidade_adquirida_w = number_format($aux_contrato[5],2,",",".");
$unidade_w = $aux_contrato[6];
$cod_unidade_w = $aux_contrato[32];
$tipo_w = $aux_contrato[26];
$cod_tipo_w = $aux_contrato[33];
$desc_produto_w = $aux_contrato[7];
$venc_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[8]));
$fiador_1_w = $aux_contrato[9];
$fiador_2_w = $aux_contrato[10];
$observacao_w = $aux_contrato[11];
$estado_registro_w = $aux_contrato[12];
$quantidade_fracao_w = $aux_contrato[13];
$porcentagem_juros_w = $aux_contrato[14];
$situacao_contrato_w = $aux_contrato[15];
$quantidade_a_entregar_w = number_format($aux_contrato[16],2,",",".");
$numero_contrato_w = $aux_contrato[17];
$usuario_cadastro_w = $aux_contrato[18];
$hora_cadastro_w = $aux_contrato[19];
$data_cadastro_w = $aux_contrato[20];
$filial_w = $aux_contrato[24];
$preco_produto_w = number_format($aux_contrato[27],2,",",".");
$porc_juros_print_w = number_format($aux_contrato[14],0,",",".") . "%";

if ($situacao_contrato_w == "PAGO")
{$pg_print = "PG";}
else
{$pg_print = "";}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print_w = $aux_bp[1];
$produto_print_2_w = $aux_bp[22];
$produto_apelido_w = $aux_bp[20];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_w' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print_w = $aux_pessoa[1];
$codigo_pessoa_w = $aux_pessoa[35];
$cidade_fornecedor_w = $aux_pessoa[10];
$estado_fornecedor_w = $aux_pessoa[12];
$telefone_fornecedor_w = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj_w = $aux_pessoa[3];}
else
{$cpf_cnpj_w = $aux_pessoa[4];}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if ($aux_contrato[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:9px'>

		<div style='width:63px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='margin-left:7px'>$data_contrato_print_w</div></div>
		
		<div style='width:204px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print_w</div></div>
		
		<div style='width:48px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$numero_contrato_w</div>
		
		<div style='width:63px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$venc_contrato_print_w</div>
		
		<div style='width:100px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$produto_print_w</div>

		<div style='width:46px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$preco_produto_w</div>

		<div style='width:84px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:7px'>$quantidade_adquirida_w $unidade_w</div></div>
		
		<div style='width:84px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:7px'>$quantidade_a_entregar_w $unidade_w</div></div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_cont_futuro == 0)
{echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
		<div style='width:705px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		<i>Nenhum contrato encontrado.</i></div>
	</div>";}
else
{}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{
	echo "
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'>
	TOTAL DE CONTRATOS FUTUROS
	</div>";

for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);


	if ($botao_mae != "BUSCAR" and $pagina_mae == "index_contrato_futuro")
	{
		$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO'"));
		$soma_adquirido = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_adquirida) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
		AND filial='$filial' AND cod_produto='$aux_bp_geral[0]' AND situacao_contrato='EM_ABERTO'"));
	}

	elseif ($botao_mae != "BUSCAR" and $pagina_mae == "relatorio_fornecedor")
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
	{}
	else
	{
	$juros = ($soma_futuros[0] - $soma_adquirido[0]);
	$juros_porcento = ($juros / $soma_adquirido[0]) * 100;
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");
	$soma_adquirido_print = number_format($soma_adquirido[0],2,",",".");
	$juros_print = number_format($juros,2,",",".");
	$juros_porc_print = number_format($juros_porcento,0,",",".") . "%";
	
	$linhas_t_aux = $linhas_t_aux + 1;
	
	
	echo "
	<div style='width:570px; height:27px; border:0px solid #999; margin-top:5px; margin-left:74px; float:left; background-color:#FFF; font-size:11px'>
	<div style='height:26px; width:565px; border:1px solid #000; border-radius:3px; background-color:#FFF; margin-left:0px'>
		<div style='width:120px; color:#000; border:0px solid #000; float:left; margin-left:8px; margin-top:6px'>
		<b>$aux_bp_geral[22]</b></div>
		
		<div style='width:95px; color:#000; border:0px solid #000; float:left; margin-left:8px; margin-top:6px; text-align:right'>
		Quant. Adquirida:</div>
		<div style='width:95px; color:#000; border:0px solid #000; float:left; margin-left:8px; margin-top:6px; text-align:left'>
		<b>$soma_adquirido_print</b> $aux_bp_geral[26]</div>
		
		<div style='width:95px; color:#000; border:0px solid #000; float:left; margin-left:8px; margin-top:6px; text-align:right'>
		Quant. Entregar:</div>
		<div style='width:95px; color:#000; border:0px solid #000; float:left; margin-left:8px; margin-top:6px; text-align:left'>
		<b>$soma_futuros_print</b> $aux_bp_geral[26]</div>
	</div>
	</div>";
	}

}
$linhas_totalizador = 2 + ($linhas_t_aux * 2);
$vazio = $contador_vazio - $linhas_totalizador;
}

else
{}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
echo "<div style='width:708px; height:15px; border:1px solid #FFF; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>";
	}
}

else
{}



echo "</div>";
// ========================================================================================================






echo "
<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:10px; border:0px solid #000; margin-left:40px; margin-top:20px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>FILIAL: $filial</div>

	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:right' align='right'>
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