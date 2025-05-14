<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "compra_editar";
$titulo = "Editar Compra";
$modulo = "compras";
$menu = "compras";

// ======= RECEBENDO POST =================================================================================
$numero_compra = $_POST["numero_compra"];
$numero_compra_aux = $_POST["numero_compra_aux"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$botao = $_POST["botao"] ?? '';
$botao_relatorio = $_POST["botao_relatorio"] ?? '';
$data_inicial = $_POST["data_inicial"] ?? '';
$data_final = $_POST["data_final"] ?? '';
$produto_list = $_POST["produto_list"] ?? '';
$produtor_ficha = $_POST["produtor_ficha"] ?? '';
$monstra_situacao = $_POST["monstra_situacao"] ?? '';

$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA COMPRA ===================================================================================
$busca_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra'");
$aux_bc = mysqli_fetch_row($busca_compra);
$linhas_bc = mysqli_num_rows($busca_compra);

$fornecedor = $aux_bc[2];
$pedidoSankhya = $aux_bc[55];
$pedidoSankhyaConfirmado = $aux_bc[56];
$cod_produto = $aux_bc[39];
$quantidade = $aux_bc[5];
$preco_unitario = number_format($aux_bc[6], 2, ",", ".");
$valor_total = number_format($aux_bc[7], 2, ",", ".");
$safra = $aux_bc[9];
$cod_tipo = $aux_bc[41];
$umidade = $aux_bc[12];
$broca = $aux_bc[11];
$impureza = $aux_bc[43];
if ($aux_bc[14] == "" or $aux_bc[14] == "0000-00-00") {
	$data_pagamento = "";
} else {
	$data_pagamento = date('d/m/Y', strtotime($aux_bc[14]));
}
$observacao = $aux_bc[13];
$usuario_cadastro = $aux_bc[18];
$data_cadastro = date('d/m/Y', strtotime($aux_bc[20]));
$hora_cadastro = $aux_bc[19];
$tipo_registro = $aux_bc[16];
$tipoCompra = $aux_bc[57];
$tipoCompraText = $tipoCompra == 1 ? "NORMAL" : ($tipoCompra == 2 ? 'ARMAZENAGEM' : '');
$modalidadeFreteText  = $aux_bc[59] == 'CIF' ? "Frete Posto (CIF)" : ($aux_bc[59] == 'FOB' ? 'Frete Puxar (FOB)' : '');
$filialFaturamento = $aux_bc[61];


// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21], 2, ",", ".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf" or $aux_forn[2] == "PF") {
	$cpf_cnpj = $aux_forn[3];
} else {
	$cpf_cnpj = $aux_forn[4];
}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================
$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
$linhas_tipo_produto = mysqli_num_rows($busca_tipo_produto);

// Popula percentual de umidade, broca e impureza
$percentualUBI = [];
$query = "SELECT * 
			FROM select_porcentagem 
			WHERE estado_registro='ATIVO' 
			ORDER BY codigo";

$resultSet = $conexao->prepare($query);

// Executa a consulta
if ($resultSet->execute()) {
	$percentualUBI = $resultSet->get_result();
}


// =============================================================================
include("../../includes/head.php");
?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao_bootstrap.css" />


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
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

	<div class="container mt-3" style="padding: 0 120px 0 120px">
		<form name="compra" action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/produtos/compra_editar_enviar.php" method="post">
			<input type="hidden" name="botao" value="compra_editar" />
			<input type="hidden" name="numero_compra" value="<?= "$numero_compra" ?>" />
			<input type="hidden" name="pedidoSankhya" value="<?= "$pedidoSankhya" ?>" />
			<input type="hidden" name="pedidoSankhyaConfirmado" value="<?= "$pedidoSankhyaConfirmado" ?>" />
			<input type="hidden" name="numero_compra_aux" value="<?= "$numero_compra_aux" ?>" />
			<input type="hidden" name="pagina_mae" value="<?= "$pagina_mae" ?>" />
			<input type="hidden" name="data_inicial" value="<?= "$data_inicial" ?>" />
			<input type="hidden" name="data_final" value="<?= "$data_final" ?>" />
			<input type="hidden" name="cod_tipo_anterior" value="<?= "$cod_tipo" ?>" />
			<input type="hidden" name="quantidade" value="<?= "$quantidade" ?>" />
			<input type="hidden" name="fornecedor" value="<?= "$fornecedor" ?>" />
			<input type="hidden" name="fornecedor_print" value="<?= "$fornecedor_print" ?>" />
			<input type="hidden" name="cod_produto" value="<?= "$cod_produto" ?>" />
			<input type="hidden" name="produto_print" value="<?= "$produto_print" ?>" />
			<input type="hidden" name="unidade_print" value="<?= "$unidade_print" ?>" />

			<div class="titulo_form_1 font-weight-bold">
				Edição de Registro
			</div>

			<div class="d-fex justify-content-start mt-2">
				<h6><?= "$tipo_registro - $produto_print" ?></h6>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="filialFaturamento">Filial de Faturamento:</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" name="filialFaturamento" value=<?= $filialFaturamento ?>>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0">Número:</label>
					<input class="form-control form-control-sm" disabled type="text" name="numero_compra_aux" value='<?= $numero_compra ?>'>
				</div>

				<div class=" form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0">Pedido Sankhya:</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" name="pedidoSankhya" value='<?= $pedidoSankhya ?>'>
				</div>

				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0">Fornecedor:</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type=" text" name="fornecedor_print" value='<?= $fornecedor_print ?>'>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0">Quantidade (<?= $unidade_print ?>):</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="quantidade" value='<?= $quantidade ?>'>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0">Preço:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="preco_unitario" value='<?= $preco_unitario ?>'>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Safra:</label>
					<input class="form-control form-control-sm text-center" type="text" name="safra"
						maxlength="4" onkeypress="mascara(this,numero)" value="<?= $safra ?>">
				</div>

				<div class="form-group col-3 ml-0">
					<label for="cod_tipo" class="col-form-label-sm mb-0">Tipo:</label>
					<select class="form-control form-control-sm form-select" name="cod_tipo" id="cod_tipo">
						<option></option>

						<?php foreach ($busca_tipo_produto as $produtoItem): ?>
							<option value="<?= $produtoItem['codigo'] ?>"
								<?= ($cod_tipo == $produtoItem['codigo']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($produtoItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-3 ml-0">
					<label for="umidade" class="col-form-label-sm mb-0">Umidade:</label>
					<select class="form-control form-control-sm form-select" name="umidade" id="umidade">
						<option></option>

						<?php foreach ($percentualUBI as $percentualItem): ?>
							<option value="<?= $percentualItem['descricao'] ?>"
								<?= ($umidade == $percentualItem['descricao']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($percentualItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group col-3 ml-0">
					<label for="broca" class="col-form-label-sm mb-0">Broca:</label>
					<select class="form-control form-control-sm form-select" name="broca" id="broca">
						<option></option>

						<?php foreach ($percentualUBI as $percentualItem): ?>
							<option value="<?= $percentualItem['descricao'] ?>"
								<?= ($broca == $percentualItem['descricao']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($percentualItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group col-3 ml-0">
					<label for="impureza" class="col-form-label-sm mb-0">Impureza:</label>
					<select class="form-control form-control-sm form-select" name="impureza" id="impureza">
						<option></option>

						<?php foreach ($percentualUBI as $percentualItem): ?>
							<option value="<?= $percentualItem['descricao'] ?>"
								<?= ($impureza == $percentualItem['descricao']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($percentualItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Data Pagamento:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="data_pagamento" value="<?= $data_pagamento ?>">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Tipo de Compra:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="tipoCompraText" value="<?= $tipoCompraText ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Modalidade do Frete:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="modalidadeFreteText" value="<?= $modalidadeFreteText ?>">
				</div>

				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0" for="observacao">Observação:</label>
					<input class="form-control form-control-sm" type="text" maxlength="150" name="observacao" value="<?= $observacao ?>">
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-12 d-flex justify-content-center">
					<div class="col-2">
						<button type="submit" class="btn btn-sm btn-secondary w-100">Salvar</button>
					</div>
					<div class="col-2">
						<button type="button" onclick="history.back()" class="btn btn-sm btn-secondary w-100">Voltar</button>
					</div>
				</div>
			</div>


		</form>

		<div class="d-fex justify-content-start mt-2">
			<h6>Valor total:<span>
					<?= "R$ $valor_total" ?>
				</span></h6>
		</div>

		<div class="d-fex justify-content-start mt-2">
			<?= "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro" ?>
		</div>


	</div>

	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>



</html>