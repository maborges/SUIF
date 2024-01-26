<?php
// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = ConverteData($_POST["data_final"]);
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$situacao_contrato = $_POST["situacao_contrato"];
$filtro_data = $_POST["filtro_data"];

$codigo_contrato_w = $_POST["codigo_contrato_w"];
$numero_contrato_w = $_POST["numero_contrato_w"];
$fornecedor_w = $_POST["fornecedor_w"];
$fornecedor_print_w = $_POST["fornecedor_print_w"];
$produto_w = $_POST["produto_w"];
$cod_produto_w = $_POST["cod_produto_w"];
$unidade_w = $_POST["unidade_w"];
$cod_unidade_w = $_POST["cod_unidade_w"];
$tipo_w = $_POST["tipo_w"];
$cod_tipo_w = $_POST["cod_tipo_w"];
$quantidade_a_entregar_w = $_POST["quantidade_a_entregar_w"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
$usuario_baixa = $nome_usuario_print;
$hora_baixa = date('G:i:s', time());
$data_baixa = date('Y/m/d', time());

if ($botao == "BUSCAR")
	{$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = ConverteData($_POST["data_final"]);}
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


// ======== BAIXA CONTRATO ====================================================================================================
if ($botao == "BAIXAR")
{
// ====== CONTADOR NÚMERO COMPRA ==========================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ========================================================================================================

$obs_aux = "N&ordm; Contrato: " . $numero_contrato_w;

$baixar = mysqli_query ($conexao, "UPDATE contrato_futuro SET situacao_contrato='PAGO', usuario_baixa='$usuario_baixa', data_baixa='$data_baixa', hora_baixa='$hora_baixa' WHERE codigo='$codigo_contrato_w'");

$baixar_ficha = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_transferencia, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_compra', '$fornecedor_w', '$produto_w', '$data_hoje', '$quantidade_a_entregar_w', '$unidade_w', '$tipo_w', '$obs_aux', 'SAIDA_FUTURO', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_contrato_w', '$cod_produto_w', '$cod_unidade_w', '$cod_tipo_w', '$fornecedor_print_w')");

// ===================================================
// ATUALIZA SALDO ====================================
$fornecedor = $fornecedor_w;
$cod_produto = $cod_produto_w;

include ('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_produtor - $quantidade_a_entregar_w;
include ('../../includes/atualisa_saldo_armaz.php');
// ===================================================
// ===================================================
}
else
{}
// ==============================================================================================================================


// ======== ESTORNA CONTRATO ====================================================================================================
if ($botao == "ESTORNAR")
{
$estornar = mysqli_query ($conexao, "UPDATE contrato_futuro SET situacao_contrato='EM_ABERTO' WHERE codigo='$codigo_contrato_w'");

$estornar_ficha = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO' WHERE movimentacao='SAIDA_FUTURO' 
AND numero_transferencia='$numero_contrato_w'");
// ==============================================================================================================================


// ===================================================
// ATUALIZA SALDO ====================================
$fornecedor = $fornecedor_w;
$cod_produto = $cod_produto_w;

include ('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_produtor + $quantidade_a_entregar_w;
include ('../../includes/atualisa_saldo_armaz.php');
// ===================================================
// ===================================================
}
else
{}
// =============================================================================================================================


// ======== TIRAR PENDENCIA DE CONTRATO ========================================================================================
/*
if ($botao == "tirar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_futuro SET pendencia='NAO' WHERE codigo='$codigo_contrato'");
}
elseif ($botao == "colocar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_futuro SET pendencia='SIM' WHERE codigo='$codigo_contrato'");
}
else
{}
*/
// =============================================================================================================================


// ===== BUSCA CONTRATOS =============================================================================================
if ($botao != "BUSCAR" and $pagina == "index_contrato_futuro")
{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' 
	AND filial='$filial' ORDER BY vencimento, nome_produtor");
}

elseif ($botao != "BUSCAR" and $pagina == "relatorio_fornecedor")
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



// ====== BUSCA FORNECEDOR ===================================================================================
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

?>