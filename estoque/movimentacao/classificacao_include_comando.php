<?php
include ("../../helpers.php");

// ====== RECEBE POST ==============================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);

$cod_produto_busca = $_POST["cod_produto_busca"];
$fornecedor_pesq = $_POST["fornecedor_pesq"];
$filial_armazem_pesq = $_POST["filial_armazem_pesq"];
$filial_origem_pesq = $_POST["filial_origem_pesq"];
$filial = $filial_usuario;
$classificacao_romaneio_pesq = $_POST["classificacao_romaneio_pesq"];
$botao = $_POST["botao"];
$botao_class = $_POST["botao_class"];
$numero_romaneio_form = $_POST["numero_romaneio_form"];
$quantidade_desconto_form = Helpers::ConvertePeso($_POST["quantidade_desconto_form"], 'N');
$quantidade_desconto_aux = $_POST["quantidade_desconto_form"];



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



$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";


if ($quantidade_desconto_form == "")
{$quantidade_desconto = 0;}
else
{$quantidade_desconto = $quantidade_desconto_form;}


if ($cod_produto_busca == "" or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}


if ($fornecedor_pesq == "" or $fornecedor_pesq == "GERAL")
	{$mysql_fornecedor = "fornecedor IS NOT NULL";
	$fornecedor_pesq = "GERAL";}
else
	{$mysql_fornecedor = "fornecedor='$fornecedor_form'";
	$fornecedor_form = $_POST["fornecedor_form"];}


if ($filial_armazem_pesq == "" or $filial_armazem_pesq == "GERAL")
	{$mysql_filial_armazem = "filial IS NOT NULL";
	$filial_armazem_pesq = "GERAL";}
else
	{$mysql_filial_armazem = "filial='$filial_armazem_pesq'";
	$filial_armazem_pesq = $_POST["filial_armazem_pesq"];}



if ($filial_origem_pesq == "" or $filial_origem_pesq == "GERAL")
	{$mysql_filial_origem = "(filial_origem IS NOT NULL OR filial_origem IS NULL OR filial_origem='')";
	$filial_origem_pesq = "GERAL";}
else
	{$mysql_filial_origem = "filial_origem='$filial_origem_pesq'";
	$filial_origem_pesq = $_POST["filial_origem_pesq"];}



if ($classificacao_romaneio_pesq == "" or $classificacao_romaneio_pesq == "GERAL")
	{$mysql_classi_romaneio_pesq = "(classificacao IS NOT NULL OR classificacao IS NULL OR classificacao='')";
	$classificacao_romaneio_pesq = "GERAL";}
elseif ($classificacao_romaneio_pesq == "SIM")
	{$mysql_classi_romaneio_pesq = "classificacao='SIM'";
	$classificacao_romaneio_pesq = "SIM";}
else
	{$mysql_classi_romaneio_pesq = "(classificacao!='SIM' OR classificacao IS NULL OR classificacao='')";
	$classificacao_romaneio_pesq = "NAO";}
// ================================================================================================================


// =================================================================================================================
if ($botao_class == "SIM")
{
$alterar = mysqli_query ($conexao, "UPDATE estoque SET classificacao='SIM', quant_quebra_previsto='$quantidade_desconto' WHERE numero_romaneio='$numero_romaneio_form'");
}
else
{}
// =================================================================================================================


// =================================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND situacao_romaneio='FECHADO' AND $mysql_filtro_data AND $mysql_cod_produto AND $mysql_fornecedor AND $mysql_filial_armazem AND $mysql_filial_origem AND $mysql_classi_romaneio_pesq"));
$soma_romaneio_print = number_format($soma_romaneio[0],0,",",".");
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
$busca_forne = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_pesq' AND estado_registro!='EXCLUIDO'");
$aux_forne = mysqli_fetch_row($busca_forne);
$linhas_forne = mysqli_num_rows ($busca_forne);

if ($fornecedor_form == "")
{$forne_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_forne == 0)
{$forne_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$forne_print = $aux_forne[1];}
// =================================================================================================================
?>