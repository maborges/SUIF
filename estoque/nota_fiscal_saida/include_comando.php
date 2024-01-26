<?php
// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace(".", "", $valor_x);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ================================================================================================================


// ====== CONVERTE PESO ==========================================================================================
function ConvertePeso($peso_x){
	$peso_1 = str_replace(".", "", $peso_x);
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$botao_mae = $_POST["botao_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$mes_atras_br = date ('d/m/Y', strtotime('-30 days'));
$filial = $filial_usuario;

$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$seleciona_pessoa = $_POST["seleciona_pessoa"];


//if ($botao == "BUSCAR" or $botao_mae == "BUSCAR")
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

$mysql_filtro_data = "data_emissao BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($seleciona_pessoa == "ROMANEIO")
	{$mysql_fornecedor = "codigo_fornecedor_romaneio='$fornecedor_busca'";
	$fornecedor_busca = $_POST["fornecedor_busca"];}
elseif ($seleciona_pessoa == "NOTA_FISCAL")
	{$mysql_fornecedor = "codigo_fornecedor='$fornecedor_busca'";
	$fornecedor_busca = $_POST["fornecedor_busca"];}
else
	{$mysql_fornecedor = "codigo_fornecedor IS NOT NULL";
	$fornecedor_busca = "GERAL";}


if ($cod_produto_busca == "" or $cod_produto_busca == "TODOS")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}

// ================================================================================================================


// ====== BUSCA NOTA FISCAL ==========================================================================================
$busca_nf = mysqli_query ($conexao, "SELECT * FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND $mysql_fornecedor ORDER BY codigo");
$linha_nf = mysqli_num_rows ($busca_nf);
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