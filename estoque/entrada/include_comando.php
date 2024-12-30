<?php

include_once("../../helpers.php");

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"] ?? '';
$botao_2 = $_POST["botao_2"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"] ?? '';
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"] ?? '');
$data_final_br = $_POST["data_final_busca"] ?? '';
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"] ?? '');

if ($botao == "BUSCAR") {
	$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"] ?? '';
	$nome_fornecedor = $_POST["nome_fornecedor"] ?? '';
	$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
} else {
	$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"] ?? '';
	$nome_fornecedor = $_POST["nome_fornecedor"] ?? '';
	$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
}
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if (empty($data_inicial_br) or empty($data_final_br)) {
	$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;
} else {
	$data_inicial_br = $_POST["data_inicial_busca"] ?? '';
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"] ?? '');
	$data_final_br = $_POST["data_final_busca"] ?? '';
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"] ?? '');
}

$mysql_filtro_data = "estoque.data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL") {
	$mysql_fornecedor = "estoque.fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";
} else {
	$mysql_fornecedor = "estoque.fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;
}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL") {
	$mysql_cod_produto = "estoque.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";
} else {
	$mysql_cod_produto = "estoque.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;
}


$mysql_filial = "estoque.filial='$filial_usuario'";

$mysql_status = "estoque.estado_registro='ATIVO'";

$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include("../../includes/conecta_bd.php");


$busca_romaneio = mysqli_query(
	$conexao,
	"SELECT
	estoque.codigo,
	estoque.numero_romaneio,
	estoque.fornecedor,
	estoque.data,
	estoque.produto,
	estoque.peso_inicial,
	estoque.peso_final,
	estoque.desconto_sacaria,
	estoque.desconto,
	estoque.quantidade,
	estoque.unidade,
	estoque.tipo_sacaria,
	estoque.movimentacao,
	estoque.placa_veiculo,
	estoque.motorista,
	estoque.observacao,
	estoque.usuario_cadastro,
	estoque.hora_cadastro,
	estoque.data_cadastro,
	estoque.usuario_alteracao,
	estoque.hora_alteracao,
	estoque.data_alteracao,
	estoque.filial,
	estoque.estado_registro,
	estoque.quantidade_prevista,
	estoque.quantidade_sacaria,
	estoque.numero_compra,
	estoque.motorista_cpf,
	estoque.num_romaneio_manual,
	estoque.filial_origem,
	estoque.quant_volume_sacas,
	estoque.cod_produto,
	estoque.usuario_exclusao,
	estoque.hora_exclusao,
	estoque.data_exclusao,
	cadastro_pessoa.nome,
	select_tipo_sacaria.descricao,
	select_tipo_sacaria.peso
FROM
	estoque,
	cadastro_pessoa,
	select_tipo_sacaria
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto) AND
	estoque.fornecedor=cadastro_pessoa.codigo AND
	estoque.tipo_sacaria=select_tipo_sacaria.codigo
ORDER BY
	estoque.codigo"
);


$busca_produto_distinct = mysqli_query(
	$conexao,
	"SELECT
	estoque.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(estoque.quantidade)
FROM
	estoque,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto) AND
	estoque.cod_produto=cadastro_produto.codigo
GROUP BY
	estoque.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem
ORDER BY
cadastro_produto.codigo"
);


$soma_romaneio = mysqli_fetch_row(mysqli_query(
	$conexao,
	"SELECT
	SUM(quantidade)
FROM
	estoque
WHERE
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto"
));


include("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_romaneio = mysqli_num_rows($busca_romaneio);
$linha_produto_distinct = mysqli_num_rows($busca_produto_distinct);

if ($soma_romaneio[0] > 0) {
	$soma_romaneio_print = "Total: " . number_format($soma_romaneio[0], 2, ",", ".") . " KG";
} else {
	$soma_romaneio_print = '';
}

// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if (!empty($nome_fornecedor)) {
	$msg = "Fornecedor: <b>$nome_fornecedor</b>";
} else {
	$msg = '';
}

// ================================================================================================================
