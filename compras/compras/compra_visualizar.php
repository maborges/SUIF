<?php
include("../../includes/config.php");
include("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "compra_visualizar";
//$titulo = "Confirma&ccedil;&atilde;o de Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);

$numero_compra = $_POST["numero_compra"];

$numero_compra_busca = $_POST["numero_compra_busca"] ?? '';
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"] ?? '';
$nome_fornecedor = $_POST["nome_fornecedor"] ?? '';
$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
$cod_tipo_busca = $_POST["cod_tipo_busca"] ?? '';
$filial_busca = $_POST["filial_busca"] ?? '';
$usuario_busca = $_POST["usuario_busca"] ?? '';
$movimentacao_busca = $_POST["movimentacao_busca"] ?? '';
$status_pgto_busca = $_POST["status_pgto_busca"] ?? '';
$ordenar_busca = $_POST["ordenar_busca"] ?? '';
$situacao_pagamento_w = $_POST['situacao_pagamento_w'] ?? '';
// ================================================================================================================

$msg = '';
$erro = 0;

// ====== BUSCA CONFIGURAÇÕES E PERMISSÕES ========================================================================
include("../../includes/conecta_bd.php");

$busca_config = mysqli_query(
	$conexao,
	"SELECT
	limite_dias_exclusao_reg,
	limite_dias_edi_compra
FROM
configuracoes"
);

$busca_permissao = mysqli_query(
	$conexao,
	"SELECT
	editar_compra,
	registro_excluir,
	registro_excluir_antigo,
	compras_pagamento
FROM
	usuarios_permissoes
WHERE
	username='$username'"
);

include("../../includes/desconecta_bd.php");

$config = mysqli_fetch_row($busca_config);
$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
include("../../includes/conecta_bd.php");

$busca_compra = mysqli_query(
	$conexao,
	"SELECT 
	compras.codigo,
	compras.numero_compra,
	compras.fornecedor,
	compras.produto,
	compras.data_compra,
	compras.quantidade,
	compras.preco_unitario,
	compras.valor_total,
	compras.unidade,
	compras.tipo,
	compras.observacao,
	compras.data_pagamento,
	compras.usuario_cadastro,
	compras.hora_cadastro,
	compras.data_cadastro,
	compras.usuario_alteracao,
	compras.hora_alteracao,
	compras.data_alteracao,
	compras.estado_registro,
	compras.filial,
	compras.fornecedor_print,
	compras.forma_entrega,
	compras.usuario_exclusao,
	compras.hora_exclusao,
	compras.data_exclusao,
	compras.umidade,
	compras.broca,
	compras.impureza,
	compras.desconto_quantidade,
	compras.motivo_alteracao_quantidade,
	compras.quantidade_original,
	compras.valor_total_original,
	compras.usuario_altera_quant,
	compras.data_altera_quant,
	compras.hora_altera_quant,
	compras.movimentacao,
	cadastro_pessoa.nome,
	cadastro_pessoa.tipo,
	cadastro_pessoa.cpf,
	cadastro_pessoa.cnpj,
	cadastro_pessoa.cidade,
	cadastro_pessoa.estado,
	cadastro_pessoa.telefone_1,
	cadastro_pessoa.codigo_pessoa,
	compras.id_pedido_sankhya,
	compras.tipo_compra,
	compras.modalidade_frete,
	filial_faturamento
FROM
	compras, cadastro_pessoa
WHERE
	compras.numero_compra='$numero_compra' AND
	compras.fornecedor=cadastro_pessoa.codigo"
);

include("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows($busca_compra);
$aux_compra = mysqli_fetch_row($busca_compra);
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
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
$usuario_cadastro_w = $aux_compra[12];
$hora_cadastro_w = $aux_compra[13];
$data_cadastro_w = $aux_compra[14];
$usuario_alteracao_w = $aux_compra[15];
$hora_alteracao_w = $aux_compra[16];
$data_alteracao_w = $aux_compra[17];
$estado_registro_w = $aux_compra[18];
$filial_w = $aux_compra[19];
$filial_faturamento_w = $aux_compra[47];
$fornecedor_print_w = $aux_compra[20];
$forma_entrega_w = $aux_compra[21];
$usuario_exclusao_w = $aux_compra[22];
$hora_exclusao_w = $aux_compra[23];
$data_exclusao_w = $aux_compra[24];
$umidade_w = $aux_compra[25];
$broca_w = $aux_compra[26];
$impureza_w = $aux_compra[27];
$desconto_quant_w = $aux_compra[28];
$motivo_ateracao_quant_w = $aux_compra[29];
$quantidade_original_w = $aux_compra[30];
$valor_total_original_w = $aux_compra[31];
$usuario_altera_quant_w = $aux_compra[32];
$data_altera_quant_w = $aux_compra[33];
$hora_altera_quant_w = $aux_compra[34];
$movimentacao_w = $aux_compra[35];
$pessoa_nome_w = $aux_compra[36];
$pessoa_tipo_w = $aux_compra[37];
$pessoa_cpf_w = $aux_compra[38];
$pessoa_cnpj_w = $aux_compra[39];
$pessoa_cidade_w = $aux_compra[40];
$pessoa_estado_w = $aux_compra[41];
$pessoa_telefone_w = $aux_compra[42];
$codigo_pessoa_w = $aux_compra[43];
$idPedidoSankhya_w = $aux_compra[44];
$tipoCompra = $aux_compra[45];
$tipoCompraText = $tipoCompra == 1 ? "NORMAL" : ($tipoCompra == 2 ? 'ARMAZENADO' : '');
$modalidadeFreteText  = $aux_compra[46] == 'CIF' ? "Frete Posto (CIF)" : ($aux_compra[46] == 'FOB' ? 'Frete Puxar (FOB)' : '');


if ($pessoa_tipo_w == "PF" or $pessoa_tipo_w == "pf") {
	$pessoa_cpf_cnpj = $pessoa_cpf_w;
} else {
	$pessoa_cpf_cnpj = $pessoa_cnpj_w;
}


$quantidade_print = number_format($quantidade_w, 2, ",", ".") . " " . $unidade_w;
$preco_unitario_print = "R$ " . number_format($preco_unitario_w, 2, ",", ".");
$total_geral_print = "R$ " . number_format($total_geral_w, 2, ",", ".");
$data_pagamento_print = date('d/m/Y', strtotime($data_pagamento_w));


if (!empty($linha_compra)) {
	$data_compra_print = date('d/m/Y', strtotime($data_compra_w));

	$cidade_uf = $pessoa_cidade_w . "-" . $pessoa_estado_w;
	$conta_caracter = strlen($cidade_uf);
	if ($conta_caracter <= 18) {
		$cidade_print = "<div style='font-size:12px; margin-left:5px; margin-top:6px; overflow:hidden'>$cidade_uf</div>";
	} else {
		$cidade_print = "<div style='font-size:9px; margin-left:5px; margin-top:2px; overflow:hidden'>$cidade_uf</div>";
	}
}

$desconto_quant_print = '';
$desconto_em_valor_print = '';
$quantidade_original_print = '';
$valor_total_original_print = '';

if (!empty($data_altera_quant_w)) {
	$data_altera_quant_print = date('d/m/Y', strtotime($data_altera_quant_w));
	$desconto_quant_print = number_format($desconto_quant_w, 2, ",", ".") . " " . $unidade_w;
	$quantidade_original_print = number_format($quantidade_original_w, 2, ",", ".") . " " . $unidade_w;
	$valor_total_original_print = "R$ " . number_format($valor_total_original_w, 2, ",", ".");
	$desconto_em_valor = ($desconto_quant_w * $preco_unitario_w);
	$desconto_em_valor_print = "R$ " . number_format($desconto_em_valor, 2, ",", ".");
}


if ($movimentacao_w == "COMPRA") {
	$movimentacao_print_m = "COMPRA";
	$movimentacao_print = "Compra";
} elseif ($movimentacao_w == "ENTRADA") {
	$movimentacao_print_m = "ENTRADA";
	$movimentacao_print = "Entrada";
} elseif ($movimentacao_w == "SAIDA") {
	$movimentacao_print_m = "SA&Iacute;DA";
	$movimentacao_print = "Sa&iacute;da";
} elseif ($movimentacao_w == "TRANSFERENCIA_ENTRADA" or $movimentacao_w == "TRANSFERENCIA_SAIDA") {
	$movimentacao_print_m = "TRANSFER&Ecirc;NCIA";
	$movimentacao_print = "Transfer&ecirc;ncia";
} else {
	$movimentacao_print_m = $movimentacao_w;
	$movimentacao_print = $movimentacao_w;
}

$titulo = "Confirma&ccedil;&atilde;o de " . $movimentacao_print;



if (!empty($usuario_cadastro_w)) {
	$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;
}

if (!empty($usuario_alteracao_w)) {
	$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;
}

if (!empty($usuario_exclusao_w)) {
	$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;
}
// ======================================================================================================


// ====== BUSCA PAGAMENTOS  ===========================================================================
include("../../includes/conecta_bd.php");

$busca_pgto = mysqli_query(
	$conexao,
	"SELECT 
			a.codigo,
			a.codigo_compra,
			a.codigo_favorecido,
			a.forma_pagamento,
			a.data_pagamento,
			a.valor,
			a.banco_cheque,
			a.observacao,
			a.usuario_cadastro,
			a.hora_cadastro,
			a.data_cadastro,
			a.estado_registro,
			a.situacao_pagamento,
			a.filial,
			a.codigo_pessoa,
			a.numero_cheque,
			a.banco_ted,
			a.origem_pgto,
			a.codigo_fornecedor,
			a.produto,
			a.favorecido_print,
			a.cod_produto,
			a.agencia,
			a.num_conta,
			a.tipo_conta,
			a.nome_banco,
			a.cpf_cnpj,
			a.id_pedido_sankhya,
			b.id_sankhya
		FROM 
			favorecidos_pgto a
			left outer join cadastro_favorecido b 
  	   	            on b.codigo = a.codigo_favorecido
		WHERE 
			a.codigo_compra='$numero_compra' AND
			a.estado_registro='ATIVO'
		ORDER BY 
			a.codigo"
);

$soma_pgto = mysqli_fetch_row(mysqli_query(
	$conexao,
	"SELECT 
			SUM(valor) 
		FROM 
			favorecidos_pgto 
		WHERE
			codigo_compra='$numero_compra' AND
			estado_registro='ATIVO'"
));

include("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_pgto = mysqli_num_rows($busca_pgto);

$saldo_a_pagar = $total_geral_w - $soma_pgto[0];
$total_pago_print = "R$ " . number_format($soma_pgto[0], 2, ",", ".");

$saldo_a_pagar_print = "R$ " . number_format($saldo_a_pagar, 2, ",", ".");
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ==========================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_compra_w);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));

if ($conta_dias < $config[1] or $permissao[0] == "S") {
	$pode_editar = "S";
} else {
	$pode_editar = "N";
}

// A linha abaixo está utilizando a variável $linha_pagamento que não é inicializada em logar algum dor programa
// A linha foi comentada e retirada a variável do IF
// if ($permissao[0] == "S" and $linha_pagamento == 0 and $pode_editar == "S" and $estado_registro_w == "ATIVO")
if ($permissao[0] == "S" and $pode_editar == "S" and $estado_registro_w == "ATIVO") {
	$permite_editar = "SIM";
} else {
	$permite_editar = "NAO";
}
// ========================================================================================================


// ====== BLOQUEIO PARA BAIXAR ============================================================================
//if ($permissao[3] == "S" and $estado_registro_w == "ATIVO")
if ($estado_registro_w == "ATIVO") {
	$permite_baixar = "SIM";
} else {
	$permite_baixar = "NAO";
}
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ===========================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_compra_w);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[0] or $permissao[2] == "S") {
	$pode_excluir = "S";
} else {
	$pode_excluir = "N";
}

// A linha abaixo está utilizando a variável $linha_pagamento que não é inicializada em logar algum dor programa
// A linha foi comentada e retirada a variável do IF
// if ($permissao[1] == "S" and $linha_pagamento == 0 and $pode_excluir == "S" and $estado_registro_w == "ATIVO")
if ($permissao[1] == "S" and $pode_excluir == "S" and $estado_registro_w == "ATIVO") {
	$permite_excluir = "SIM";
} else {
	$permite_excluir = "NAO";
}
// ========================================================================================================


// ====== MONTA MENSAGEM =================================================================================
if (empty($linha_compra)) {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>REGISTRO N&Atilde;O ENCONTRADO</div>";
}

if ($estado_registro_w == "EXCLUIDO") {
	$erro = 2;
	$msg = "<div style='color:#FF0000'>REGISTRO EXCLU&Iacute;DO</div>";
}
// ======================================================================================================


// ======================================================================================================
include("../../includes/head.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao_bootstrap.css" />


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo $titulo; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body onload="javascript:foco('ok');">


	<!-- ====== TOPO ================================================================================================== -->
	<div class="topo">
		<?php include("../../includes/topo.php"); ?>
	</div>


	<!-- ====== MENU ================================================================================================== -->
	<div class="menu">
		<?php include("../../includes/menu_compras.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_compras_compras.php"); ?>
	</div>

	<div class="container mt-3">
		<form action="#">
			<div class="d-flex justify-content-between titulo_form_1">
				<div><?= $titulo ?></div>
				<div>N&ordm; <?= $numero_compra ?></div>
			</div>

			<div class="d-flex justify-content-between mt-2">
				<div class="d-flex-col-9">
					<?php if ($msg) : ?>
						<div>
							<h6 class="text-danger">
								<?= $msg ?>
							</h6>
						</div>
					<?php endif; ?>
				</div>

				<div class="d-fex justify-content-end">
					<h6><?= $data_compra_print ?></h6>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="filialFaturamento">Filial de Faturamento:</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" name="filialFaturamento" value=<?= $filial_faturamento_w ?>>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0">Fornecedor</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" value='<?= $pessoa_nome_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Pedido Sankhya</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" value='<?= $idPedidoSankhya_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">CPF/CNPJ</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $pessoa_cpf_cnpj ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Cidade</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $cidade_uf ?>'>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-4 ml-0">
					<label class="col-form-label-sm mb-0">Produto</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" value='<?= $produto_print_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Tipo</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $tipo_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Quantidade</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $quantidade_print ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Preço Unitário</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $preco_unitario_print ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Valor Total</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" value='<?= $total_geral_print ?>'>
				</div>

			</div>

			<div class="form-row">
				<div class="form-group col-md-4 ml-0">
					<label class="col-form-label-sm mb-0">Forma de Entrega</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $forma_entrega_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Umidade</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $umidade_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Broca</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $broca_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Impureza</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $impureza_w ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Filial</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $filial_w ?>'>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Tipo da Compra</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $tipoCompraText ?>'>
				</div>

				<div class="form-group col-md-2 ml-0">
					<label class="col-form-label-sm mb-0">Modalidade do Frete</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $modalidadeFreteText ?>'>
				</div>

				<div class="form-group col-md-8 ml-0">
					<label class="col-form-label-sm mb-0">Observação</label>
					<input class="form-control form-control-sm" disabled type="text" value='<?= $observacao_w ?>'>
				</div>
			</div>

			<?php if ($desconto_quant_w > 0) : ?>
				<div class="form-row">
					<div class="form-group col-md-2 ml-0" title="<?= $desconto_em_valor_print ?>">
						<label class="col-form-label-sm mb-0 text-danger">Desconto de Quantidade</label>
						<input class="form-control form-control-sm font-weight-bold" disabled type="text" value='<?= $desconto_quant_print ?>'>
					</div>

					<div class="form-group col-md-2 ml-0">
						<label class="col-form-label-sm mb-0">Quantidade Original</label>
						<input class="form-control form-control-sm" disabled type="text" value='<?= $quantidade_original_print ?>'>
					</div>

					<div class="form-group col-md-2 ml-0">
						<label class="col-form-label-sm mb-0">Valor Original</label>
						<input class="form-control form-control-sm" disabled type="text" value='<?= $valor_total_original_print ?>'>
					</div>

					<div class="form-group col-md-6 ml-0">
						<label class="col-form-label-sm mb-0">Motivo</label>
						<input class="form-control form-control-sm" disabled type="text" value='<?= $motivo_ateracao_quant_w ?>'>
					</div>
				</div>
			<?php endif; ?>


		</form>

		<!-- 
				Botões de navegação da página
		-->

		<div class="row mt-4">
			<div class="col-md-2">
				<form action="<?= "$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae" ?>.php" method="post">
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='data_inicial_busca' value='<?= $data_inicial_br ?>'>
					<input type='hidden' name='data_final_busca' value='<?= $data_final_br ?>'>
					<input type='hidden' name='numero_compra' value='<?= $numero_compra ?>'>
					<input type='hidden' name='fornecedor_pesquisa' value='<?= $fornecedor_pesquisa ?>'>
					<input type='hidden' name='nome_fornecedor' value='<?= $nome_fornecedor ?>'>
					<input type='hidden' name='cod_produto_busca' value='<?= $cod_produto_busca ?>'>
					<input type='hidden' name='cod_tipo_busca' value='<?= $cod_tipo_busca ?>'>
					<input type='hidden' name='filial_busca' value='<?= $filial_busca ?>'>
					<input type='hidden' name='usuario_busca' value='<?= $usuario_busca ?>'>
					<input type='hidden' name='movimentacao_busca' value='<?= $movimentacao_busca ?>'>
					<input type='hidden' name='numero_compra_busca' value='<?= $numero_compra_busca ?>'>
					<input type='hidden' name='status_pgto_busca' value='<?= $status_pgto_busca ?>'>
					<input type='hidden' name='ordenar_busca' value='<?= $ordenar_busca ?>'>
					<button type='submit' class="btn btn-sm btn-secondary w-100">Voltar</button>
				</form>
			</div>

			<!-- 
				Os botões na versão anterior (Editar, Excluir e Pagamento) não estavam funcionais,
				sempre apareciam disabled, e por isso foram removidos nesta versão
			-->

			<div class="col-md-2">
				<form action="<?= "$servidor/$diretorio_servidor" ?>/compras/compras/compra_impressao_1.php" method="post" target='_blank'>
					<input type='hidden' name='numero_compra' value='<?= $numero_compra ?>'>
					<button type='submit' class="btn btn-sm btn-secondary w-100" <?= (empty($erro) and $movimentacao_w == "COMPRA") ? "" : "disabled" ?>>Imprimir 1 Via</button>
				</form>
			</div>


			<div class="col-md-2">
				<form action="<?= "$servidor/$diretorio_servidor" ?>/compras/compras/compra_impressao_2.php" method="post" target='_blank'>
					<input type='hidden' name='numero_compra' value='<?= $numero_compra ?>'>
					<button type='submit' class="btn btn-sm btn-secondary w-100" <?= (empty($erro) and $movimentacao_w == "COMPRA") ? "" : "disabled" ?>>Imprimir 2 Vias</button>
				</form>
			</div>

		</div>

		<!-- 
				Pagamentos
		-->

		<div class="d-flex justify-content-between mt-4 ">
			<div class="titulo_form_1">Pagamentos</div>
			<div>
				<h6>Total Pago: <?= $total_pago_print ?></h6>
			</div>
		</div>

		<div class="d-fex text-right">
			<h6>Saldo a Pagar:
				<span class="<?= $saldo_a_pagar <= 0  ? '' : "text-danger" ?>"><?= $saldo_a_pagar_print ?></span>
			</h6>
		</div>

		<div class="d-flex mt-4">
			<table class="table table-hover table-striped table-sm" style="display: flex-row">
				<thead>
					<th>Data</th>
					<th>Favorecido</th>
					<th>Forma de Pagamento</th>
					<th>Banco</th>
					<th>Agência</th>
					<th>Número</th>
					<th>Tipo de Conta</th>
					<th>Valor</th>
					<th>Sankhya</th>
				</thead>
				<tbody>
					<?php while ($aux_pgto = mysqli_fetch_row($busca_pgto)) : ?>

						<?php
						$id_z = $aux_pgto[0];
						$codigo_compra_z = $aux_pgto[1];
						$codigo_favorecido_z = $aux_pgto[2];
						$forma_pagamento_z = $aux_pgto[3];
						$data_pagamento_z = $aux_pgto[4];
						$valor_z = $aux_pgto[5];
						$banco_cheque_z = $aux_pgto[6];
						$observacao_z = $aux_pgto[7];
						$usuario_cadastro_z = $aux_pgto[8];
						$hora_cadastro_z = $aux_pgto[9];
						$data_cadastro_z = $aux_pgto[10];
						$estado_registro_z = $aux_pgto[11];
						$situacao_pagamento_z = $aux_pgto[12];
						$filial_z = $aux_pgto[13];
						$codigo_pessoa_z = $aux_pgto[14];
						$numero_cheque_z = $aux_pgto[15];
						$banco_ted_z = $aux_pgto[16];
						$origem_pgto_z = $aux_pgto[17];
						$codigo_fornecedor_z = $aux_pgto[18];
						$produto_z = $aux_pgto[19];
						$favorecido_print = $aux_pgto[20];
						$cod_produto_z = $aux_pgto[21];
						$agencia_z = $aux_pgto[22];
						$num_conta_z = $aux_pgto[23];
						$tipo_conta_z = $aux_pgto[24];
						$nome_banco_z = $aux_pgto[25];
						$cpf_cnpj_z = $aux_pgto[26];
						$idSankhya_z = $aux_pgto[27];
						$idSankhyaFavorecido = $aux_pgto[28];


						$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_z));
						$valor_print = "<b>" . number_format($valor_z, 2, ",", ".") . "</b>";


						if ($situacao_pagamento_z == "PAGO") {
							$situacao_pagamento_print = "BAIXADO";
						} elseif ($situacao_pagamento_z == "EM_ABERTO") {
							$situacao_pagamento_print = "EM ABERTO";
						} else {
							$situacao_pagamento_print = "";
						}


						if ($tipo_conta_z == "corrente") {
							$tipo_conta_aux = "C/C";
						} elseif ($tipo_conta_z == "poupanca") {
							$tipo_conta_aux = "Poupan&ccedil;a";
						} else {
							$tipo_conta_aux = "";
						}


						if ($banco_cheque_z == "SICOOB") {
							$banco_cheque_aux = "Sicoob";
						} elseif ($banco_cheque_z == "BANCO DO BRASIL") {
							$banco_cheque_aux = "Banco do Brasil";
						} elseif ($banco_cheque_z == "BANESTES") {
							$banco_cheque_aux = "Banestes";
						} else {
							$banco_cheque_aux = "";
						}


						if ($origem_pgto_z == "SOLICITACAO") {
							$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
							$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";
						} else {
							$origem_pgto_print = "COMPRA";
							$codigo_compra_print = $codigo_compra_z;
						}


						if ($forma_pagamento_z == "TED") {
							$forma_pagamento_print = "TRANSFER&Ecirc;NCIA";
							$nome_banco_print = $nome_banco_z;
							$agencia_print = $agencia_z;
							$num_conta_print = $num_conta_z;
							$tipo_conta_print = $tipo_conta_aux;
						} elseif ($forma_pagamento_z == "CHEQUE") {
							$forma_pagamento_print = "CHEQUE";
							$nome_banco_print = $banco_cheque_aux;
							$agencia_print = "";
							$num_conta_print = $numero_cheque_z;
							$tipo_conta_print = "";
						} else {
							$forma_pagamento_print = $forma_pagamento_z;
							$nome_banco_print = "";
							$agencia_print = "";
							$num_conta_print = "";
							$tipo_conta_print = "";
						}


						if (!empty($usuario_cadastro_z)) {
							$dados_cadastro_z = " &#13; Cadastrado por: " . $usuario_cadastro_z . " " . date('d/m/Y', strtotime($data_cadastro_z)) . " " . $hora_cadastro_z;
						}
						// ======================================================================================================


						// ====== RELATORIO =======================================================================================
						/*
							if ($situacao_pagamento_w == "EM_ABERTO") {
								echo "<tr class='tabela_1' title=' ID: $id_z &#13; C&oacute;digo do Favorecido: $codigo_favorecido_z Sankhya: $idSankhyaFavorecido &#13; CPF/CNPJ: $cpf_cnpj_z &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_z &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_z &#13; Observa&ccedil;&atilde;o: $observacao_z &#13; Filial: $filial_z $dados_cadastro_z'>";
							} else {
								echo "<tr class='tabela_2' title=' ID: $id_z &#13; C&oacute;digo do Favorecido: $codigo_favorecido_z Sankhya: $idSankhyaFavorecido &#13; CPF/CNPJ: $cpf_cnpj_z &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_z &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_z &#13; Observa&ccedil;&atilde;o: $observacao_z &#13; Filial: $filial_z $dados_cadastro_z'>";
							}
								*/

						$tootip = " ID: $id_z &#13; Código do Favorecido: $codigo_favorecido_z Sankhya: $idSankhyaFavorecido &#13; CPF/CNPJ: $cpf_cnpj_z &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_z &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_z &#13; Observa&ccedil;&atilde;o: $observacao_z &#13; Filial: $filial_z $dados_cadastro_z'>";

						?>

						<tr title="<?= $tootip ?>">
							<td><?= $data_pgto_print ?></td>
							<td><?= $favorecido_print ?></td>
							<td><?= $forma_pagamento_print ?></td>
							<td><?= $nome_banco_print ?></td>
							<td class="text-right"><?= $agencia_print ?></td>
							<td><?= $num_conta_print ?></td>
							<td><?= $tipo_conta_print ?></td>
							<td class="text-right"><?= $valor_print ?></td>
							<td class="text-right"><?= $idSankhya_z ?></td>
						</tr>


					<?php endwhile; ?>
				</tbody>

				<?php if ($linha_pgto == 0 and $movimentacao_w == "COMPRA") : ?>
					<tfoot>
						<th class="text-secondary text-center fs-6 m-5" scope="row" colspan="6">Nenhum registro encontrado</th>
					</tfoot>
				<?php endif; ?>
			</table>

		</div>


	</div>


	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<div style="width:auto; height:20px; border:0px solid #000; margin-top:20px; text-align:center; font-size:12px">
			<?php
			$complemento_rodape = "$dados_cadastro_w";

			if (!empty($usuario_alteracao_w)) {
				$complemento_rodape = $complemento_rodape . " | $dados_alteracao_w";
			}

			if (!empty($usuario_exclusao_w)) {
				$complemento_rodape = $complemento_rodape . " | $dados_exclusao_w";
			}

			echo $complemento_rodape
			?>
		</div>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>