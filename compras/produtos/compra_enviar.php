<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
require("../../sankhya/Sankhya.php");
include_once("../../helpers.php");

$pagina = "compra_enviar";
$titulo = "Compra de Produto";
$modulo = "compras";
$menu = "compras";


// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================
include("../../includes/head.php");


?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao_bootstrap.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>
</script>

<script src=<?= "../../includes/loading/loading.js" ?>></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
	function voltarPagina() {
		window.history.back();
	}
</script>

</head>

<body onload="loading();">

	<?php
	include("../../includes/loading/loading.php");
	?>

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
	// ======= RECEBENDO POST =================================================================================
	$fornecedor 			= $_POST["fornecedor"];
	$idSankhya 				= $_POST["idSankhya"];
	$idProdutoSankhya 		= $_POST["idProdutoSankhya"];
	$pedidoSankhya 			= $_POST["pedidoSankhya"] ?? '';
	$cod_produto 			= $_POST["cod_produto"];
	$botao 					= $_POST["botao"];
	$numero_compra 			= $_POST["numero_compra"];
	$quantidade 			= $_POST["quantidade"];
	$preco_unitario 		= Helpers::ConverteValor($_POST["preco_unitario"]);
	$preco_unitario_print 	= $_POST["preco_unitario"] ?? 0;
	$retorno_rovereti 		= '';
	$safra 					= $_POST["safra"];
	$cod_tipo 				= $_POST["cod_tipo"];
	$umidade 				= $_POST["umidade"];
	$broca 					= $_POST["broca"];
	$impureza 				= $_POST["impureza"];
	$data_pgto 				= $_POST["data_pagamento"] ?? '';
	$tipo_compra 			= $_POST["tipo_compra"] ?? 0;
	$tipoCompraText 		= $tipo_compra == 1 ? 'Normal' : ($tipo_compra == 2 ? 'Armazenagem' : '');
	$observacao 			= $_POST["observacao"];
	$data_compra 			= date('Y-m-d', time());
	$filial 				= $filial_usuario;
	$movimentacao 			= "COMPRA";
	$data_pagamento_br 		= $_POST["data_pagamento"];
	$data_pagamento 		= $_POST["data_pagamento"];
	$situacao_pagamento 	= "EM_ABERTO";
	$forma_pagamento  		= '';
	$usuario_cadastro 		= $nome_usuario_print;
	$hora_cadastro 			= date('G:i:s', time());
	$data_cadastro 			= date('Y-m-d', time());
	$valor_total            = 0;
	$categoriaProdutor      = $_POST["categoriaProdutor"] ?? '';
	$modalidadeFrete        = $_POST["modalidadeFrete"] ?? '';

	$situacao_compra_w      = $_POST["situacao_compra_w"] ?? '';
	$color_bg_w             = $_POST["color_bg_w"] ?? '';

	$errno 					= $_POST["errno"] ?? 0;
	$error 					= $_POST["error"] ?? '';
	$msgSankhya   			= $_POST["msgSankhya"] ?? '';
	$errorSankhya 			= 0;
	$nomeComprador 			= $_POST["nomeComprador"] ?? '';
	$filialFaturamento 		= $_POST["filial_faturamento"] ?? '';


	if ($quantidade && $preco_unitario) {
		$valor_total = ($quantidade * $preco_unitario);
	}

	$valor_total_print 		= number_format($valor_total, 2, ",", ".");

	if (strtotime($data_pagamento) > strtotime($data_compra)) {
		$forma_pagamento = "PREVISAO";
	}

	// ====== BUSCA PRODUTO ================================================================================
	$busca_produto = mysqli_query($conexao, "SELECT * 
											   FROM cadastro_produto 
											  WHERE codigo = '$cod_produto' 
											    AND estado_registro!='EXCLUIDO'");
	$aux_bp = mysqli_fetch_row($busca_produto);

	$produto_print 			= $aux_bp[1];
	$produto_print_2 		= $aux_bp[22];
	$cod_unidade 			= $aux_bp[7];
	$usuario_alteracao 		= $aux_bp[16];
	$data_alteracao 		= date('d/m/Y', strtotime($aux_bp[18]));
	$produto_apelido 		= $aux_bp[20];
	$preco_maximo 			= $aux_bp[21];
	$preco_maximo_print 	= number_format($aux_bp[21], 2, ",", ".");
	$quantidade_un 			= $aux_bp[23];
	$cod_class_gerencial 	= $aux_bp[24]; // ==== INTEGRAÇÃO ROVERETI =====
	$cod_centro_custo 		= $aux_bp[25]; // ==== INTEGRAÇÃO ROVERETI =====
	$plano_conta 			= $aux_bp[35];
	$plano_conta_mae 		= $aux_bp[41];
	$produto_rovereti 		= str_replace($comAcentos, $semAcentos, $produto_print); // ==== INTEGRAÇÃO ROVERETI ===== ATENÇÃO: REVERETI não aceita "acentos"
	// ======================================================================================================


	// ====== BUSCA FORNECEDOR ==============================================================================
	$busca_fornecedor = mysqli_query($conexao, "SELECT * 
												  FROM cadastro_pessoa 
												 WHERE codigo           = '$fornecedor' 
												   AND estado_registro != 'EXCLUIDO'");
	$aux_forn = mysqli_fetch_row($busca_fornecedor);
	$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

	$fornecedor_print 		= $aux_forn[1];
	$cidade_fornecedor 		= $aux_forn[10];
	$estado_fornecedor 		= $aux_forn[12];
	$telefone_fornecedor 	= $aux_forn[14];
	$codigo_pessoa 			= $aux_forn[35];
	$produtorSankhya 		= $aux_forn[41];
	$comprador 				= $aux_forn[49];

	if (strtoupper($aux_forn[2]) == "PF") {
		$cpf_cnpj = $aux_forn[3];
	} else {
		$cpf_cnpj = $aux_forn[4];
	}

	$cpf_aux 				= Helpers::limpa_cpf_cnpj($cpf_cnpj); // ==== INTEGRAÇÃO ROVERETI =====
	$fornecedor_rovereti 	= str_replace($comAcentos, $semAcentos, $fornecedor_print); // ==== INTEGRAÇÃO ROVERETI =====
	$situacao_compra 		= $aux_forn[40];

	// ====== BUSCA FAVORECIDO ==============================================================================
	$busca_favorecido = mysqli_query($conexao,   "SELECT codigo, nome, id_sankhya 
													FROM cadastro_favorecido 
												   WHERE estado_registro != 'EXCLUIDO' 
													 AND codigo_pessoa='$codigo_pessoa' 
												  ORDER BY nome LIMIT 1");
	$aux_favorecido 		= mysqli_fetch_row($busca_favorecido);

	$cod_favorecido			= $aux_favorecido[0];
	$favorecido_print 		= $aux_favorecido[1];
	$favorecidoSankhya 		= $aux_favorecido[2];

	// ====== BUSCA NUMERO DE COMPRA ==============================================================================
	$busca_num_compra = mysqli_query($conexao, "SELECT 1 
												  FROM compras 
												 WHERE estado_registro != 'EXCLUIDO' 
												   AND numero_compra    = '$numero_compra'");

	$achou_num_compra 		= mysqli_num_rows($busca_num_compra);


	// ====== BUSCA CONTROLE DE TALAO  ============================================================================
	$busca_talao = mysqli_query($conexao, "SELECT * 
										 	 FROM talao_controle 
											WHERE estado_registro != 'EXCLUIDO' 
											  AND codigo_pessoa    = '$fornecedor' 
											  AND cod_produto      = '$cod_produto' 
											  AND devolvido        = 'N' ORDER BY codigo");

	$linha_talao 			= mysqli_num_rows($busca_talao);

	// ====== BUSCA TOPS  ============================================================================
	$busca_tops = mysqli_query($conexao, "SELECT tops_requisicao 
	   										FROM tipo_operacao_produto 
										   WHERE tipo_operacao='ECPR' 
										     AND produto_sankhya = '$idProdutoSankhya'");
	$resultTops = mysqli_fetch_row($busca_tops);

	// ===========================================================================================================
	$msg = '';

	if (!$filialFaturamento) {
		$msg = 'Filial de Faturamento deve ser informada';
	} elseif ($fornecedor == '') {
		$msg = 'Fornecedor deve ser informado';
	} elseif ($achou_num_compra >= 1) {
		$msg = 'Já existe uma compra realizada com este número';
	} elseif (($linha_talao > 1 and $permissao[45] != 'S') or $situacao_compra == 2) {
		$msg = 'Compra não autorizada';
	} elseif (!$idSankhya) {
		$msg = 'Código do Fornecedor Sankhya deve ser informado';
	} elseif (!$idProdutoSankhya) {
		$msg = 'Código do Produto Sankhya deve ser informado.';
	} elseif ($resultTops == '') {
		$msg = 'Tipo de Operação do Produto Sankhya (TOPS) não encontrado';
	} elseif ($quantidade == '') {
		$msg = 'Quantidade deve ser informada';
	} elseif (!is_numeric($quantidade)) {
		$msg = 'Quantidade inválida';
	} elseif ($preco_unitario == '' or $preco_unitario <= 0) {
		$msg = 'Preço unitário inválido';
	} elseif (!$safra) {
		$msg = 'Safra deve ser informada';
	} elseif ($cod_tipo == '') {
		$msg = 'Tipo deve ser informado';
	} elseif ($linhas_fornecedor == 0) {
		$msg = 'Fornecedor não cadastrado';
	} elseif ($preco_unitario > $preco_maximo and $permissao[41] != 'S') {
		$msg = 'Compra não autolizada';
	} elseif (!$data_pagamento) {
		$msg = 'Informe a data de pagamento';
	} elseif (!$tipo_compra) {
		$msg = 'Informe o tipo de compra';
	} elseif (!$modalidadeFrete) {
		$msg = 'Informe a modalidade do frete';
	}

	$codigo_tops = $resultTops[0];

	if ($msg) {
		showErrorMessage('Dados da compra inconsistêntes', $msg);
		exit;
	}

	// ====== BUSCA TIPO PRODUTO ==========================================================================
	$busca_tipo_produto = mysqli_query($conexao, "SELECT descricao 
														FROM select_tipo_produto 
													WHERE codigo           = '$cod_tipo' 
														AND estado_registro != 'EXCLUIDO'");
	$aux_tp 				= mysqli_fetch_row($busca_tipo_produto);

	$tipo_print 			= $aux_tp[0];

	// ====== BUSCA UNIDADE DE MEDIDA ==========================================================================
	$busca_un_med = mysqli_query($conexao, "SELECT descricao, abreviacao 
												FROM unidade_produto 
												WHERE codigo           = '$cod_unidade' 
												AND estado_registro !='EXCLUIDO'");
	$aux_un_med   			= mysqli_fetch_row($busca_un_med);

	$un_descricao 			= $aux_un_med[0];
	$unidade_print 			= $aux_un_med[1];

	// ====== BUSCA CATEGORIA DO PRODUTOR=========================================================================
	$dataSet = mysqli_query($conexao, "SELECT nome 
										 FROM categoria_produtor 
				                        WHERE codigo = '$categoriaProdutor'");
	$resultSet 				= mysqli_fetch_row($dataSet);

	$categoriaProdutorNome	= $resultSet[0];

	if ($modalidadeFrete == 'CIF') {
		$modalidadeFreteNome = 'Frete Posto (CIF)';
	} else {
		$modalidadeFreteNome = 'Frete Puxar (FOB)';
	}


	// ATUALIZA SALDO ARMAZENADO ========================================
	include('../../includes/busca_saldo_armaz.php');

	// Verifica se há saldo quando compra armazenado
	if ($tipo_compra == 2 && ($saldo_produtor < 0 || $saldo_produtor < $quantidade)) {
		$msg = "Saldo armazenado ($saldo_produtor) do produto é inferiar à quantidade sendo comprada.";
	}

	if ($msg) {
		showErrorMessage('Saldo Armazenado', $msg);
		exit;
	}

	// Iniciar uma transação
	mysqli_autocommit($conexao, FALSE);
	mysqli_begin_transaction($conexao);
	$msg   = '';
	$title = "";

	$msgRovereti    = '';
	$msgSankhya     = '';
	$msgSUIF        = '';
	$msgSankhya     = '';
	$msgSankhyaErro = '';

	try {
		try {
			$mySQL = "INSERT INTO compras
						(codigo,
						numero_compra,
						fornecedor,
						produto,
						data_compra,
						quantidade,
						preco_unitario,
						valor_total,
						unidade,
						safra,
						tipo,
						broca,
						umidade,
						observacao,
						data_pagamento,
						situacao_pagamento,
						movimentacao,
						usuario_cadastro,
						hora_cadastro,
						data_cadastro,
						estado_registro,
						filial,
						quantidade_original,
						valor_total_original,
						cod_produto,
						cod_unidade,
						cod_tipo,
						fornecedor_print,
						impureza,
						quantidade_original_primaria,
						total_pago,
						saldo_pagar,
						plano_conta_mae,
						plano_conta,
						tipo_compra,
						modalidade_frete,
						filial_faturamento)
					VALUES
						(NULL,
						'$numero_compra',
						'$fornecedor',
						'$produto_print',
						'$data_compra',
						'$quantidade',
						'$preco_unitario',
						'$valor_total',
						'$unidade_print',
						'$safra',
						'$tipo_print',
						'$broca',
						'$umidade',
						'$observacao',
						'$data_pagamento',
						'$situacao_pagamento',
						'$movimentacao',
						'$usuario_cadastro',
						'$hora_cadastro',
						'$data_cadastro',
						'ATIVO',
						'$filial',
						'$quantidade',
						'$valor_total',
						'$cod_produto',
						'$cod_unidade',
						'$cod_tipo',
						'$fornecedor_print',
						'$impureza',
						'$quantidade',
						'0',
						'$valor_total',
						'$plano_conta_mae',
						'$plano_conta',
						 $tipo_compra,
						'$modalidadeFrete',
						'$filialFaturamento')";


			$title = 'Erro ao grava no SUIF (compras)';
			$inserir = $conexao->prepare($mySQL);
			$inserir->execute();

			if ($forma_pagamento == "PREVISAO") {
				$title = 'Erro ao grava no SUIF (favorecido)';
				$mySQL = "INSERT INTO favorecidos_pgto (codigo, codigo_compra, codigo_favorecido, forma_pagamento, data_pagamento, valor, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, situacao_pagamento, filial, codigo_pessoa, produto, favorecido_print, cod_produto) 
								VALUES (NULL, '$numero_compra', '$cod_favorecido', 'PREVISAO', '$data_pagamento', '$valor_total', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', 'EM_ABERTO', '$filial', '$codigo_pessoa', '$produto_print', '$favorecido_print', '$cod_produto')";
				$inserir = $conexao->prepare($mySQL);
				$inserir->execute();
			}

			if (!$comprador) {
				$updateComprador = mysqli_query($conexao, "UPDATE cadastro_pessoa SET comprador = '$usuario_cadastro' WHERE codigo = '$fornecedor'");
			}
		} catch (mysqli_sql_exception $e) {
			$msg = $e->getMessage();
			var_dump($msg);
			mysqli_rollback($conexao);
			return;
		}


		// ATUALIZA SALDO ARMAZENADO ========================================
		try {
			include('../../includes/busca_saldo_armaz.php');
			$saldo = $saldo_produtor - $quantidade;
			include('../../includes/atualisa_saldo_armaz.php');
		} catch (mysqli_sql_exception $e) {
			$title = 'Erro ao grava no SUIF (armazenagem)';
			$msg = $e->getMessage();
			mysqli_rollback($conexao);
			return;
		}

		// ====== INTEGRAÇÃO ROVERETI =======================================================================
		try {
			$busca_filial = mysqli_query($conexao, "SELECT codigo_integracao FROM filiais WHERE descricao='$filial'");
			$cod_ifr = mysqli_fetch_row($busca_filial);
			$cod_integ_filial_rovereti = $cod_ifr[0];

			// ====== BUSCA CODIGO USUARIO =======================================================================
			$cod_empresa_rovereti = "50";
			$data_rovereti = date('d/m/Y', time());
			$desc_comp_rovereti = "COMPRA DE " . $produto_rovereti . " - " . $quantidade . " " . $unidade_print . " X " . $preco_unitario_print;
			$cpf_cnpj_rovereti = $cpf_aux;
			$observacao_rovereti = "# CADASTRO INTEGRACAO SUIF (USERNAME: " . $usuario_cadastro . ") " . " OBS: " . $observacao . " | TIPO: " . $tipo_print;
			$valor_rovereti = number_format($valor_total, 2, ",", "");

			//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
			$token = sha1($usuario_rovereti . $key_rovereti . "ServiceToken" . $data_rovereti);
			//PARAMETROS CADASTRO CONTA_PAGAR
			$parametros = '{
						"CodEmpresa":"' . $cod_empresa_rovereti . '",
						"CodIntegracaoFilial":"' . $cod_integ_filial_rovereti . '",
						"DscContaPagar":"' . utf8_encode($desc_comp_rovereti) . '",
						"NumCpfCnpj":"' . $cpf_cnpj_rovereti . '",
						"NomFornecedor":"' . utf8_encode($fornecedor_rovereti) . '",
						"NumDocumento":"' . $numero_compra . '",
						"DatEmissao":"' . $data_rovereti . '",
						"DatVencimento":"' . $data_pgto . '",
						"VlrConta":"' . $valor_rovereti . '",
						"VlrMultaAtraso":"",
						"VlrJurosAtrasoDia":"",
						"VlrDesconto":"",
						"DatLimiteDesconto":"",
						"NumAnoMesCompetencia":"",
						"IndContaReconhecida":"S",
						"CodIntegracaoAcaoContabil":"",
						"CodIntegracaoClassGerencial":"' . $cod_class_gerencial . '",
						"CodIntegracaoCentroCusto":"' . $cod_centro_custo . '",
						"DscObservacao":"' . utf8_encode($observacao_rovereti) . '",
						"CodIntegracaoContaPagar":"' . $numero_compra . '",
						"NomFavorecido":"",
						"NumCpfCnpjFavorecido":"",
						"NumBanco":"",
						"NumAgencia":"",
						"NumContaCorrente":"",
						"NumDigitoContaCorrente":"",
						"DscIdentificacaoUsuario":"' . $usuario_rovereti . '",
						"Key":"' . $key_rovereti . '",
						"Token":"' . $token . '"
						}';

			$url = $rovereti_api_IncluirContaPagar;
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);

			curl_setopt(
				$ch,
				CURLOPT_HTTPHEADER,
				array(
					'Content-Type:application/json',
					'Content-Length: ' . strlen($parametros)
				)
			);

			$retorno     		= curl_exec($ch);
			$retornoJSON 		= json_decode($retorno, true);
			$error 		      	= "";
			$errno 		      	= 0;

			if (isset($retornoJSON['Error'])) {
				$error = $retornoJSON['Error'];
				$msg   = $error;
				$errno = 1;
			} else {
				$error = curl_error($ch);
				$msg   = $error;
				$errno = curl_errno($ch);
			}

			curl_close($ch);

			// Exec retornou falso?
			if (Trim($error) != '') {
				$title = 'Erro na chamada API Rovereti';
				mysqli_rollback($conexao);
				return;
			}

			// Efetiva a gravação da compra só depois de ter gerado o ROVERETI
			mysqli_commit($conexao);

			// Reativar o commit automático
			mysqli_autocommit($conexao, TRUE);

			$retorno_rovereti = "$retorno $error $errno";

			// ====== ATUALIZA NÚMERO DO ROVERETI NA COMPRA ======================================================
			// Obs: O retorno do ROVERETI deve ser gravado após os dados da compra serem persistidos no bando de 
			// dados. Caso não consiga gerar o retorno do ROVERETI, todas será dado rollback no SUIF. 
			// Entretando, é possivel que seja dado commit nas inclusões mas não foi possivel grava o retorno do 
			// ROVERETI por conta de algum erro.
			// O usuário deve ser avisado e fazer a atualização manualmente ou deve ser criado processo para isso.

			try {
				$mySQL = "UPDATE compras SET retorno_rovereti = '$retorno_rovereti' WHERE numero_compra = '$numero_compra'";
				$update = $conexao->prepare($mySQL);
				$update->execute();
			} catch (mysqli_sql_exception $e) {
				$title = 'Erro na atualização do SUIF do retorno ROVERETI';
				$msg = $e->getMessage();
				return;
			}

			$msgRovereti = "Compra " . $numero_compra . " lançada no ROVERETI com Sucesso!";
		} catch (Exception $e) {
			$title = 'Erro na atualização do Rovereti';
			$msg   = $e->getMessage();
			mysqli_rollback($conexao);
			return;
		}

		$msgSUIF = 'Dados do SUIF atualizados com sucesso!';

		// ====== INTEGRAÇÃO SANKHYA =======================================================================
		$errorSankhya = 0;
		$resultPedido = Sankhya::insertPedidoCompra($numero_compra);

		if ($resultPedido['errorCode']) {
			$errorSankhya   = $resultPedido['errorCode'];
			$msgSankhyaErro = $resultPedido['errorMessage'];
		} else {
			$pedidoSankhya = $resultPedido['rows']['pk']['NUNOTA']['$'];

			// Faz a confirmação do pedido somente se conseguiu alterarar favorecido de faturamento
			$confirmaPedido = Sankhya::confirmaPedidoCompra($pedidoSankhya);

			if ($confirmaPedido['errorCode']) {
				$pedidoConfirmado = 'X';
				$errorSankhya     = $confirmaPedido['errorCode'];
				$msgSankhyaErro   = $msgSankhya;
			} else {
				$pedidoConfirmado = 'S';
			}

			// Atualiza o número do pedido Sankhya na compra do SUIF
			if ($pedidoSankhya) {
				$sql =  "UPDATE compras 
							SET id_pedido_sankhya         = $pedidoSankhya, 
								pedido_confirmado_sankhya = '$pedidoConfirmado' 
						  WHERE numero_compra = $numero_compra";

				$resultUpdateCompra = Sankhya::queryExecuteDB($sql);
				$msgSankhya = "Pedido $pedidoSankhya criado no Sankhya.";
			}
		}
	} finally {
		if ($msg) {
			showErrorMessage($title, $msg);
			return;
		}

		// Reativar o commit automático
		mysqli_autocommit($conexao, TRUE);
	}

	?>

	<div class="container mt-3" style="padding: 0 120px 0 120px ">
		<form name="compra" action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/produtos/compra_enviar.php" method="post">

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

			<div class="form-row mt-3">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="filialFaturamento">Filial de Faturamento:</label>
					<input class="form-control form-control-sm" disabled type="text" name="filialFaturamento" value=<?= $filialFaturamento ?>>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="idSankhya">Fornecedor Sankhya:</label>
					<input class="form-control form-control-sm" disabled type="number" name="idSankhya" value=<?= $idSankhya ?>>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="idProdutoSankhya">Produto Sankhya:</label>
					<input class="form-control form-control-sm" disabled type="number" name="idProdutoSankhya" value=<?= $idProdutoSankhya ?>>
				</div>
				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="idTOPS">Código TOPS:</label>
					<input class="form-control form-control-sm" disabled type="number" name="idTOPS" value=<?= $codigo_tops ?>>
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
					<input class="form-control form-control-sm font-weight-bold text-center" disabled type="text" name="situacao_compra_w" value="<?= $situacao_compra_w ?>"
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
					<input type="text" disabled class="form-control form-control-sm text-center" id="ok" name="quantidade"
						value=<?= $quantidade ?>>
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="preco_unitario">Preço:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="preco_unitario"
						value="<?= $preco_unitario ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="safra">Safra:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="safra" value="<?= date('Y') ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="$tipo_print">Tipo:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="$tipo_print" value="<?= $tipo_print ?>">
				</div>


			</div>

			<div class="form-row">
				<div class="form-group col-3 ml-0">
					<label for="umidade" class="col-form-label-sm mb-0">Umidade:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="umidade"
						value="<?= $umidade ?>">
				</div>

				<div class="form-group col-3 ml-0">
					<label for="broca" class="col-form-label-sm mb-0">Broca:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="broca"
						value="<?= $broca ?>">
				</div>

				<div class="form-group col-3 ml-0">
					<label for="impureza" class="col-form-label-sm mb-0">Impureza:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="impureza"
						value="<?= $impureza ?>">
				</div>

				<div class="form-group col-md-3 ml-0">
					<label class="col-form-label-sm mb-0" for="data_pagamento_br">Data Pagamento:</label>
					<input class="form-control form-control-sm text-center" disabled type="date" name="data_pagamento_br"
						value="<?= $data_pagamento_br ?>">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group col-3 ml-0">
					<label for="tipoCompraText" class="col-form-label-sm mb-0">Tipo de Compra:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="tipoCompraText" value="<?= $tipoCompraText ?>">
				</div>

				<div class="form-group col-3 ml-0">
					<label for="modalidadeFreteNome" class="col-form-label-sm mb-0">Modalidade do Frete:</label>
					<input class="form-control form-control-sm text-center" disabled type="text" name="modalidadeFreteNome" value="<?= $modalidadeFreteNome ?>">
				</div>

				<div class="form-group col-md-6 ml-0">
					<label class="col-form-label-sm mb-0" for="observacao">Observação:</label>
					<input class="form-control form-control-sm" disabled type="text" name="observacao" value="<?= $observacao ?>">
				</div>
			</div>

			<div class="form-row mt-3">
				<div class="col-12 d-flex justify-content-center">
					<div class="form-group col-10 p-2 d-flex justify-content-around">
						<form></form>
						<form method="post" action="<?= "$servidor/$diretorio_servidor" ?>/compras/produtos/compra_impressao.php" target='_blank'>
							<input type='hidden' name='numero_compra' value='<?= $numero_compra ?>'>
							<button class="btn btn-sm btn-secondary" type="submit">Imprimir Compra</button>
						</form>

						<form method="post" action="<?= "$servidor/$diretorio_servidor" ?>/compras/forma_pagamento/forma_pagamento.php">
							<input type='hidden' name='numero_compra' value='<?= $numero_compra ?>'>
							<input type='hidden' name='pedidoSankhya' value='<?= $pedidoSankhya ?>'>
							<button class="btn btn-sm btn-secondary" type="submit">Forma de Pagamento</button>
						</form>

						<form method="post" action="<?= "$servidor/$diretorio_servidor" ?>/compras/ficha_produtor/movimentacao_produtor.php">
							<input type='hidden' name='fornecedor' value='<?= $fornecedor ?>'>
							<input type='hidden' name='cod_produto' value='<?= $cod_produto ?>'>
							<input type='hidden' name='botao' value='seleciona'>
							<button class="btn btn-sm btn-secondary" type="submit">Ficha do Produtor</button>
						</form>

						<form method="post" action="<?= "$servidor/$diretorio_servidor" ?>/compras/produtos/cadastro_1_selec_produto.php">
							<input type='hidden' name='fornecedor' value='<?= $fornecedor ?>'>
							<input type='hidden' name='cod_produto' value='<?= $cod_produto ?>'>
							<input type='hidden' name='botao' value='seleciona'>
							<button class="btn btn-sm btn-secondary" type="submit">Nova Compra</button>
						</form>
					</div>
				</div>
			</div>

			<div class="form-row mt-3">
				<div class="col-12 d-flex justify-content-center">
					<div class="card border-success mb-3 shadow">
						<div class="card-body bg-success p-2 text-white bg-opacity-40">
							<div><?= $msgSUIF ?></div>
							<div><?= $msgRovereti ?></div>
							<div><?= $msgSankhya ?>
							</div>
						</div>
					</div>
				</div>
				<?php if ($msgSankhyaErro) : ?>
					<div class="col-12 d-flex justify-content-center">
						<div class="card border-danger mb-3 shadow">
							<div class="card-body bg-danger p-2 text-white bg-opacity-40">
								<div><?= $msgSankhyaErro ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

			</div>
		</form>
	</div>

	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>
<?php
function showErrorMessage($title, $message)
{
	// Faz tratamento para os caracteres "`"
	$safeTitle = str_replace('`', "'", $title);
	$safeMessage = str_replace('`', "'", $message);

	echo "
		<script>
			const mensagem = `$safeMessage`;
			Swal.fire({
				icon: 'error',
				title: '$safeTitle',
				text: mensagem,
				position: 'center',
				showConfirmButton: true,
				confirmButtonText: 'Voltar',
				customClass: {
					popup: 'card text-danger border border-2 border-danger p-3',
					title: 'h5',
					confirmButton: 'btn btn-sm btn-danger'
				}
			}).then((result) => {
				voltarPagina();
			});
		</script>";
}
