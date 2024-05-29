<?php
include ("../../helpers.php");


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$botao_mae = $_POST["botao_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$mes_atras_br = date ('d/m/Y', strtotime('-30 days'));
$filial = $filial_usuario;

$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$numero_romaneio_w = $_POST["numero_romaneio_w"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());

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

$mysql_filtro_data = "data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($situacao_romaneio_busca == "" or $situacao_romaneio_busca == "GERAL")
	{$mysql_situacao_romaneio = "situacao_romaneio IS NOT NULL";
	$situacao_romaneio_busca = "GERAL";}
else
	{$mysql_situacao_romaneio = "situacao_romaneio='$situacao_romaneio_busca'";
	$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];}


if ($forma_pesagem_busca == "" or $forma_pesagem_busca == "GERAL")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao='ENTRADA_DIRETA' OR situacao='BALANCA')";
	$forma_pesagem_busca = "GERAL";}
elseif ($forma_pesagem_busca == "BALANCA")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao!='ENTRADA_DIRETA')";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}
else
	{$mysql_forma_pesagem = "situacao='ENTRADA_DIRETA'";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}


if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
	{$mysql_fornecedor = "fornecedor IS NOT NULL";
	$fornecedor_busca = "GERAL";}
else
	{$mysql_fornecedor = "fornecedor='$fornecedor_busca'";
	$fornecedor_busca = $_POST["fornecedor_busca"];}


if ($cod_produto_busca == "" or $cod_produto_busca == "TODOS")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor AND $mysql_cod_produto 
AND filial='$filial' ORDER BY codigo DESC");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor AND $mysql_cod_produto 
AND filial='$filial' ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor 
AND $mysql_cod_produto AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  ===============================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ===============================================================================================================


// ====== BUSCA PRODUTO FORM ======================================================================================
$busca_prod = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_busca' AND estado_registro!='EXCLUIDO'");
$aux_prod = mysqli_fetch_row($busca_prod);
$linhas_prod = mysqli_num_rows ($busca_prod);

$prod_print = $aux_prod[1];
// ==============================================================================================================


// ====== BUSCA FORNECEDOR ===========================================================================================
$busca_forne = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_busca' AND estado_registro!='EXCLUIDO'");
$aux_forne = mysqli_fetch_row($busca_forne);
$linhas_forne = mysqli_num_rows ($busca_forne);

if ($fornecedor_busca == "")
{$forne_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_forne == 0)
{$forne_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$forne_print = $aux_forne[1];}
// =================================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_romaneio == 0)
{$print_quant_reg = "";}
elseif ($linha_romaneio == 1)
{$print_quant_reg = "$linha_romaneio ROMANEIO";}
else
{$print_quant_reg = "$linha_romaneio ROMANEIOS";}

if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
{$print_fornecedor = "";}
else
{$print_fornecedor = "$aux_forne[1]";}

/*
if ($botao_mae != "BUSCAR" and ($pagina_mae == "index_entrada" or $pagina_mae == "entrada_relatorio_fornecedor"))
{$print_periodo = "";}
else
{$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";}
*/
$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";
// ==================================================================================================================
?>