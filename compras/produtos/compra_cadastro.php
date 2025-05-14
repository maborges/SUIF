<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");

$pagina = "compra_cadastro";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";

// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"] ?? '';
$idSankhya = $_POST['idSankhya'] ?? '';
$pedidoSankhya = $_POST['pedidoSankhya'] ?? '';
$cod_produto = $_POST["cod_produto"] ?? '';
$categoriaProdutor = $_POST["categoriaProdutor"] ?? '';
$botao = $_POST["botao"] ?? '';
$quantidade = $_POST["quantidade"] ?? '';
$preco_unitario = $_POST["preco_unitario"] ?? '';
$safra = $_POST["safra"] ?? date('Y');
$cod_tipo = $_POST["cod_tipo"] ?? '';
$umidade = $_POST["umidade"] ?? '';
$broca = $_POST["broca"] ?? '';
$impureza = $_POST["impureza"] ?? '';
$data_pagamento = $_POST["data_pagamento"] ?? date('Y-m-d');;
$tipo_compra = $_POST["tipo_compra"] ?? 0;
$observacao = $_POST["observacao"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$situacao_compra = $_POST["situacao_compra"] ?? '';
$modalidadeFrete =  $_POST["modalidadeFrete"] ?? '';
$filialFaturamento = $_POST["filial_faturamento"] ?? '';

$filial = $filial_usuario;
$erro = 0;
$linhas_ultima_compra = 0;


// ================================================================================================================
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


<script src=<?= "../../includes/loading/loading.js" ?>></script>

<script>
	function voltarPagina() {
		window.history.back();
	}

	function vaiParaPagina(pagina) {
		window.location.href = pagina;
	}
</script>


</head>

<body onload="loading();">

	<?php include("../../includes/loading/loading.php"); ?>

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


	<?php
	// ====== CONTADOR NÚMERO COMPRA ==========================================================================
	if ($pagina_mae == "movimentacao_produtor") {
		$busca_num_compra = mysqli_query($conexao, "SELECT contador_numero_compra FROM configuracoes");
		$aux_bnc = mysqli_fetch_row($busca_num_compra);
		$numero_compra = $aux_bnc[0];

		$contador_num_compra = $numero_compra + 1;
		$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
	} else {
		//$numero_compra = $_POST["numero_compra"];

		$busca_num_compra = mysqli_query($conexao, "SELECT contador_numero_compra FROM configuracoes");
		$aux_bnc = mysqli_fetch_row($busca_num_compra);
		$numero_compra = $aux_bnc[0];

		$contador_num_compra = $numero_compra + 1;
		$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
	}
	// ========================================================================================================


	// ====== BUSCA PRODUTO ===================================================================================
	$busca_produto = mysqli_query($conexao, "SELECT descricao, produto_print, unidade_print, id_sankhya FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
	$aux_bp = mysqli_fetch_row($busca_produto);
	$linhas_bp = mysqli_num_rows($busca_produto);

	$produto_print = $aux_bp[0];
	$produto_print_2 = $aux_bp[1];
	$unidade_print = $aux_bp[2];
	$idProdutoSankhya = $aux_bp[3];
	// ======================================================================================================


	// ====== BUSCA TOPS  ============================================================================
	$busca_tops = mysqli_query($conexao, "SELECT tops_requisicao FROM tipo_operacao_produto WHERE tipo_operacao='ECPR' AND produto_sankhya='$idProdutoSankhya'");
	$idTOPS = mysqli_fetch_row($busca_tops)[0];

	// ====== BUSCA PESSOA ===================================================================================
	$busca_fornecedor = mysqli_query($conexao, "SELECT nome, situacao_compra, id_sankhya, comprador FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
	$aux_forn = mysqli_fetch_row($busca_fornecedor);
	$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

	$fornecedor_print = $aux_forn[0];
	$situacao_compra_print = $aux_forn[1];
	$idSankhya = $aux_forn[2];
	$idComprador = $aux_forn[3];

	// ====== BUSCA NOME DO COMPRADOR EM USUARIOS=========================================================
	$nomeComprador = $comprador[0];
	$resultSet = mysqli_query($conexao, "SELECT ifnull(nome_completo, primeiro_nome) nome_comprador 
										   FROM usuarios
									 	  WHERE username = '$idComprador' ");

	if (!$resultSet) {
		$nomeComprador = 'DISPONIVEL';
	} else {
		$comprador = mysqli_fetch_row($resultSet);
		$nomeComprador = $comprador[0];
	}

	$situacao_compra_w = 'Análise';
	$color_bg_w = "#FFFF00";

	if ($situacao_compra_print == 0) {
		$situacao_compra_w = 'Liberada';
		$color_bg_w = "#7FFF00";
	} elseif ($situacao_compra_print == 2) {
		$situacao_compra_w = 'Bloqueada';
		$color_bg_w = "#FF0000";
	}

	// ====== BUSCA SALDO ARMAZENADO ========================================================================
	$busca_saldo_arm = mysqli_query($conexao, "SELECT saldo FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
	$linhas_saldo_arm = mysqli_num_rows($busca_saldo_arm);
	$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
	$saldo_armazenado_print = number_format($aux_saldo_arm[0], 2, ",", ".");

	// ====== BUSCA ULTIMA COMPRA ========================================================================
	$busca_ultima_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE fornecedor='$fornecedor' AND filial='$filial_usuario' AND cod_produto='$cod_produto' AND estado_registro='ATIVO' AND movimentacao='COMPRA' ORDER BY codigo DESC LIMIT 1");
	$aux_ultima_compra = mysqli_fetch_row($busca_ultima_compra);
	$linhas_ultima_compra = mysqli_num_rows($busca_ultima_compra);

	$data_uc = date('d/m/Y', strtotime($aux_ultima_compra[4]));
	$quant_uc = number_format($aux_ultima_compra[5], 2, ",", ".");
	$preco_uc = number_format($aux_ultima_compra[6], 2, ",", ".");
	$valor_uc = number_format($aux_ultima_compra[7], 2, ",", ".");


	// ====== MONTA MENSAGEM ===================================================================================
	if ($botao == "selecionar") {
		if ($fornecedor == "") {
			$erro = 1;
			$msg_erro = "* Selecione um fornecedor";
		} elseif ($linhas_fornecedor == 0) {
			$erro = 2;
			$msg_erro = "* Fornecedor inválido";
		} elseif ($idSankhya == "") {
			$erro = 3;
			$msg_erro = "* Fornecedor Sankhya não informado no cadastro";
		} elseif ($idProdutoSankhya == "") {
			$erro = 4;
			$msg_erro = "* Código do produto Sankhya não informado no cadastro";
		} elseif ($cod_produto == "") {
			$erro = 5;
			$msg_erro = "* Selecione um produto";
		} elseif ($linhas_bp == 0) {
			$erro = 6;
			$msg_erro = "* Produto inválido";
		} elseif ($situacao_compra_print == 2) {
			$erro = 7;
			$msg_erro = "* Produtor bloqueado para compras";
		} elseif (!$modalidadeFrete) {
			$erro = 8;
			$msg_erro = "* Informa a modalidade do frete";
		} else {
			$erro = 0;
			$msg_erro = "";
		}
	}
	// ======================================================================================================

	// Popula tipo de produto
	$busca_tipo_produto = [];
	$query = "SELECT * 
	            FROM select_tipo_produto 
   			   WHERE cod_produto='$cod_produto' 
			     AND estado_registro='ATIVO' 
			  ORDER BY codigo";

	$resultSet = $conexao->prepare($query);

	// Executa a consulta
	if ($resultSet->execute()) {
		$busca_tipo_produto = $resultSet->get_result();
	}

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

	// Popula filiais
	$busca_filiais_faturamento = [];

	$query = "SELECT c.descricao
				FROM cadastro_pessoa a
 					 INNER JOIN  filial_comprador b
						ON b.comprador = a.comprador
					 INNER JOIN filiais c
						ON c.codigo = b.filial 
			   WHERE a.codigo = $fornecedor
				 AND a.comprador IS NOT NULL";

	$query = "SELECT c.descricao
				FROM filiais c
			   WHERE id_sankhya is not null
	          order by c.descricao";

	$resultSet = $conexao->prepare($query);

	// Executa a consulta
	if ($resultSet->execute()) {
		$busca_filiais_faturamento = $resultSet->get_result();
	}

	// ====== BUSCA CATEGORIA DO PRODUTOR=========================================================================
	$dataSet = mysqli_query($conexao, "SELECT nome 
										 FROM categoria_produtor 
				                        WHERE codigo = '$categoriaProdutor'");
	$resultSet 				= mysqli_fetch_row($dataSet);

	if (!$resultSet) {
		$categoriaProdutorNome = "Não Informado";
	} else {
		$categoriaProdutorNome = $resultSet[0];
	}

	$tipoCompra = [
		['codigo' => 0, 'descricao' => ''],
		['codigo' => 1, 'descricao' => 'Normal'],
		['codigo' => 2, 'descricao' => 'Armazenagem']
	];

	$tipoModalidadeFrete = [
		['codigo' => '', 'descricao' => ''],
		['codigo' => 'CIF', 'descricao' => 'Frete Posto (CIF)'],
		['codigo' => 'FOB', 'descricao' => 'Frete Puxar (FOB)']
	]

	?>

	<!-- =============================================   C E N T R O   =============================================== -->
	<div class="container mt-3" style="padding: 0 120px 0 120px">
		<form name="compra" action="<?= "$servidor/$diretorio_servidor"; ?>/compras/produtos/compra_enviar.php" method="post">
			<input type="hidden" name="botao" value="compra_cadastro" />
			<input type="hidden" name="fornecedor" value="<?= $fornecedor ?>" />
			<input type="hidden" name="idSankhya" value="<?= $idSankhya ?>" />
			<input type="hidden" name="pedidoSankhya" value="<?= $pedidoSankhya ?>" />
			<input type="hidden" name="idProdutoSankhya" value="<?= $idProdutoSankhya ?>" />
			<input type="hidden" name="cod_produto" value="<?= $cod_produto ?>" />
			<input type="hidden" name="numero_compra" value="<?= $numero_compra ?>" />
			<input type="hidden" name="situacao_compra_w" value="<?= $situacao_compra_w ?>" />
			<input type="hidden" name="color_bg_w" value="<?= $color_bg_w ?>" />
			<input type="hidden" name="categoriaProdutor" value="<?= $categoriaProdutor ?>" />
			<input type="hidden" name="nomeComprador" value="<?= $nomeComprador ?>" />

			<div class="form-row mt-3">
				<div class="form-group col-md-6 ml-0">
					<div class="titulo_form_1">
						Compra<?= !in_array($erro, [1, 2, 3, 4, 5]) ? " de $produto_print_2" : "" ?>
					</div>
				</div>

				<div class="form-group col-md-6 ml-0 d-flex align-items-center justify-content-between">
					<span class="col-form-label badge <?= $nomeComprador == 'DISPONIVEL' ? 'badge-warning' : 'badge-light' ?>">Comprador: <?= $nomeComprador ?></span>
					<span class="col-form-label badge badge-success"><?= $categoriaProdutorNome ?></span>
				</div>
			</div>

			<div class="form-row mt-4">
				<div class="form-group col-3 ml-0">
					<label for="filial_faturamento" class="col-form-label-sm mb-0">Filial de Faturamento:</label>
					<select class="form-control form-control-sm form-select" name="filial_faturamento" id="filial_faturamento">
						<option></option>

						<?php foreach ($busca_filiais_faturamento as $filialFaturamento): ?>
							<option value="<?= $filialFaturamento['descricao'] ?>"
								<?= ($filial_faturamento == $filialFaturamento['descricao']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($filialFaturamento['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="idSankhya">Fornecedor Sankhya:</label>
					<input class="form-control form-control-sm" disabled type="number" name="idSankhya" value=<?= $idSankhya ?>>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="idProdutoSankhya">Produto Sankhya:</label>
					<input class="form-control form-control-sm" disabled type="number" name="idProdutoSankhya" value=<?= $idProdutoSankhya ?>>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="pedidoSankhya">Pedido Sankhya:</label>
					<input class="form-control form-control-sm" disabled type="number" name="pedidoSankhya" value=<?= $pedidoSankhya ?>>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="numero_compra">Número da Compra:</label>
					<input class="form-control form-control-sm" disabled type="text" name="numero_compra" value=<?= $numero_compra ?>>
				</div>

				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0" for="fornecedor_print">Fornecedor:</label>
					<input class="form-control form-control-sm font-weight-bold" disabled type="text" name="fornecedor_print" value="<?= $fornecedor_print ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="situacao_compra_w">Situação:</label>
					<input class="form-control form-control-sm font-weight-bold text-center" disabled type="text" name="situacao_compra_w" value=<?= $situacao_compra_w ?>
						style="background-color: <?= $color_bg_w ?>">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="quantidade">
						Quantidade
						<?= !($fornecedor == "" or $cod_produto == "" or $linhas_bp == 0 or $linhas_fornecedor == 0)  ? " ($unidade_print)" : '' ?>
						:
					</label>
					<input type="text" class="form-control form-control-sm text-center" maxlength="15" id="ok" name="quantidade"
						onkeypress="troca(this)" value=<?= $quantidade ?>>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="preco_unitario">Preço:</label>
					<input class="form-control form-control-sm text-center" type="text" name="preco_unitario"
						maxlength="15" onkeypress="mascara(this,mvalor)" value="<?= $preco_unitario ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Safra:</label>
					<input class="form-control form-control-sm text-center" type="text" name="safra"
						maxlength="4" onkeypress="mascara(this,numero)" value="<?= date('Y') ?>">
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
					<label class="col-form-label-sm mb-0" for="data_pagamento">Data Pagamento:</label>
					<input class="form-control form-control-sm text-center" type="date" name="data_pagamento"
						value="<?= $data_pagamento ?>">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-3 ml-0">
					<label for="tipo_compra" class="col-form-label-sm mb-0">Tipo de Compra:</label>
					<select class="form-control form-control-sm form-select" name="tipo_compra" id="tipo_compra">
						<?php foreach ($tipoCompra as $tipoCompraItem): ?>
							<option value="<?= $tipoCompraItem['codigo'] ?>"
								<?= ($impureza == $tipoCompraItem['codigo']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($tipoCompraItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<!-- ======= MODALIDADE DO FRETE =========================================================================================== -->

				<div class="form-group col-3 ml-0">
					<label for="tipo_compra" class="col-form-label-sm mb-0">Modalidade do Frete:</label>
					<select class="form-control form-control-sm form-select" name="modalidadeFrete" id="modalidadeFrete">
						<?php foreach ($tipoModalidadeFrete as $tipoModalidadeFreteItem): ?>
							<option value="<?= $tipoModalidadeFreteItem['codigo'] ?>"
								<?= ($modalidadeFrete == $tipoModalidadeFreteItem['codigo']) ? 'selected' : '' ?>>
								<?= htmlspecialchars($tipoModalidadeFreteItem['descricao']) ?>
							</option>
						<?php endforeach; ?>
					</select>
					</select>
				</div>

				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0" for="observacao">Observação:</label>
					<input class="form-control form-control-sm" type="text" maxlength="150" name="observacao" value="<?= $observacao ?>">
				</div>

			</div>

			<div class="form-row mt-3">
				<div class="col-12 d-flex justify-content-center">
					<?php if ($erro): ?>
						<button class="btn btn-sm btn-secondary" type="button" onclick="voltarPagina()">Voltar</button>
					<?php else: ?>
						<div class="form-group col-4 p-2 d-flex justify-content-around">
							<button class="btn btn-sm btn-secondary" type="submit">Salvar</button>
							<button class="btn btn-sm btn-secondary" type="button" onclick="voltarPagina()">Cancelar</button>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-row mt-3">
				<div class="col-12 d-flex justify-content-center">
					<div class="card border-success mb-3 shadow">
						<div class="card-body bg-success p-2 text-white bg-opacity-40">
							<div><?= "Saldo de armazenado do produtor: $saldo_armazenado_print $unidade_print" ?></div>
							<div><?= "Última compra do produtor: $quant_uc $unidade_print x $preco_uc = R$ $valor_uc ($data_uc)" ?></div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>



	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>