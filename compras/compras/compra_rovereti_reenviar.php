<?php
include("../../includes/config.php");
include("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "compra_rovereti_reenviar";
$titulo = "Reenvio de Compra ao Rovereti";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());

$numero_compra_busca = $_POST["numero_compra_busca"];
// ================================================================================================================







// ======= INTEGRAÇÃO ROVERETI ====================================================================================
// ================================================================================================================
include("../../includes/conecta_bd.php");

if ($botao == "ROVERETI") {

	// ====== BUSCA COMPRA ===================================================================================
	$busca_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra_busca'");
	$aux_bc = mysqli_fetch_row($busca_compra);
	$linhas_bc = mysqli_num_rows($busca_compra);
	$data_hoje = date('d/m/Y', time());

	$fornecedor = $aux_bc[2];
	$cod_produto = $aux_bc[39];
	$data_compra = $aux_bc[4];
	$data_compra_print = date('d/m/Y', strtotime($aux_bc[4]));
	$quantidade = $aux_bc[5];
	$quantidade_print = number_format($aux_bc[5], 2, ",", ".");
	$preco_unitario = $aux_bc[6];
	$preco_unitario_print = number_format($aux_bc[6], 2, ",", ".");
	$valor_total = $aux_bc[7];
	$valor_total_print = number_format($aux_bc[7], 2, ",", ".");
	$tipo = $aux_bc[10];
	$cod_tipo = $aux_bc[41];
	$safra = $aux_bc[9];
	$umidade = $aux_bc[12];
	$broca = $aux_bc[11];
	$impureza = $aux_bc[43];
	$data_pagamento = $aux_bc[14];
	$data_pgto = date('d/m/Y', strtotime($aux_bc[14]));
	$data_pagamento_br = date('d/m/Y', strtotime($aux_bc[14]));
	$situacao_pgto = $aux_bc[15];
	$observacao = $aux_bc[13];
	$usuario_cadastro = $aux_bc[18];
	$data_cadastro = date('d/m/Y', strtotime($aux_bc[20]));
	$hora_cadastro = $aux_bc[19];
	$motivo_alteracao_quant = $aux_bc[35];
	$quantidade_original = number_format($aux_bc[36], 2, ",", ".");
	$desconto_quantidade = number_format($aux_bc[29], 2, ",", ".");
	$desconto_quantidade_2 = $aux_bc[29];
	$valor_total_original = number_format($aux_bc[37], 2, ",", ".");
	$desconto_em_valor = ($aux_bc[29] * $aux_bc[6]);
	$desc_em_valor_print = number_format($desconto_em_valor, 2, ",", ".");
	$usuario_altera_quant = $aux_bc[44];
	$movimentacao = "COMPRA";
	$filial_compra = $aux_bc[25];

	$usuario_cadastro = $nome_usuario_print;
	$hora_cadastro = date('G:i:s', time());
	$data_cadastro = date('Y-m-d', time());
	// ======================================================================================================


	// ====== BUSCA PRODUTO ================================================================================
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
	$produto_rovereti = str_replace($comAcentos, $semAcentos, $produto_print); // ==== INTEGRAÇÃO ROVERETI ===== ATENÇÃO: REVERETI não aceita "acentos"
	$cod_class_gerencial = $aux_bp[24]; // ==== INTEGRAÇÃO ROVERETI =====
	$cod_centro_custo = $aux_bp[25]; // ==== INTEGRAÇÃO ROVERETI =====
	// ======================================================================================================


	// ====== BUSCA FORNECEDOR ==============================================================================
	$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
	$aux_forn = mysqli_fetch_row($busca_fornecedor);
	$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

	$fornecedor_print = $aux_forn[1];
	$codigo_pessoa = $aux_forn[35];
	$cidade_fornecedor = $aux_forn[10];
	$estado_fornecedor = $aux_forn[12];
	$telefone_fornecedor = $aux_forn[14];
	if ($aux_forn[2] == "PF" or $aux_forn[2] == "pf") {
		$cpf_cnpj = $aux_forn[3];
	} else {
		$cpf_cnpj = $aux_forn[4];
	}
	$cpf_aux = Helpers::limpa_cpf_cnpj($cpf_cnpj); // ==== INTEGRAÇÃO ROVERETI =====
	$fornecedor_rovereti = str_replace($comAcentos, $semAcentos, $fornecedor_print); // ==== INTEGRAÇÃO ROVERETI =====
	// ======================================================================================================


	// ====== BUSCA FAVORECIDO ==============================================================================
	$busca_favorecido = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa' ORDER BY nome LIMIT 1");
	$aux_favorecido = mysqli_fetch_row($busca_favorecido);
	$linhas_favorecido = mysqli_num_rows($busca_favorecido);

	$cod_favorecido = $aux_favorecido[0];
	$favorecido_print = $aux_favorecido[14];
	// ========================================================================================================


	// ====== BUSCA TIPO PRODUTO ==========================================================================
	$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
	$aux_tp = mysqli_fetch_row($busca_tipo_produto);

	$tipo_print = $aux_tp[1];
	// ===========================================================================================================


	// ====== BUSCA UNIDADE DE MEDIDA ==========================================================================
	$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
	$aux_un_med = mysqli_fetch_row($busca_un_med);

	$un_descricao = $aux_un_med[1];
	$unidade_print = $aux_un_med[2];
	// ===========================================================================================================


	// ====== BUSCA CODIGO FILIAL =======================================================================
	$busca_filial = mysqli_query($conexao, "SELECT * FROM filiais WHERE descricao='$filial_compra'");
	$cod_ifr = mysqli_fetch_row($busca_filial);
	$cod_integ_filial_rovereti = $cod_ifr[3];

	// ====== BUSCA CODIGO USUARIO =======================================================================
	// $usuario_rovereti = "INTEGRADOR.GRANCAFE";
	// $key_rovereti = 25482;  // Agora peda do conecta_db.php

	//	$cod_empresa_rovereti = "16"; Número alterado dia 02/07/2018 (Gustavo ligou informando o novo numero)
	$cod_empresa_rovereti = "50";
	//	$data_rovereti = date('d/m/Y', time());
	$data_rovereti = $data_compra_print;
	$desc_comp_rovereti = "COMPRA DE " . $produto_rovereti . " - " . $quantidade . " " . $unidade_print . " X " . $preco_unitario_print;
	$cpf_cnpj_rovereti = $cpf_aux;
	$observacao_rovereti = "# CADASTRO INTEGRACAO SUIF (USERNAME: " . $usuario_cadastro . ") " . " OBS: " . $observacao . " | TIPO: " . $tipo_print;
	$valor_rovereti = number_format($valor_total, 2, ",", "");

	//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
	//$token = sha1("USUARIO.TESTE"."18538"."ServiceToken"."05/04/2017");
	// $token = sha1($usuario_rovereti.$key_rovereti."ServiceToken".$data_hoje);
	$token = sha1($usuario_rovereti . $key_rovereti . "ServiceToken" . $data_rovereti);


	//PARAMETROS CADASTRO CONTA_PAGAR
	$parametros = '{
				"CodEmpresa":"' . $cod_empresa_rovereti . '",
				"CodIntegracaoFilial":"' . $cod_integ_filial_rovereti . '",
				"DscContaPagar":"' . utf8_encode($desc_comp_rovereti) . '",
				"NumCpfCnpj":"' . $cpf_cnpj_rovereti . '",
				"NomFornecedor":"' . utf8_encode($fornecedor_rovereti) . '",
				"NumDocumento":"' . $numero_compra_busca . '",
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
				"CodIntegracaoContaPagar":"' . $numero_compra_w . '",
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





	$url = $rovereti_api_IncluirContaPagar; //'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';
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
	//curl_setopt($ch, CURLOPT_HEADER, 1);

	$retorno =  curl_exec($ch);
	//	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	//	$header = substr($retorno, 0, $header_size);
	//	$body = substr($retorno, $header_size);
	$jsonResultData = json_decode($retorno);

	$error = curl_error($ch);
	$errno = curl_errno($ch);

	curl_close($ch);

	// Exec retornou falso?
	if ($exec === false) {
		$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
		$erro_rovereti = 'sim';
	}

	// Tem algum erro?
	elseif ($error !== '') {
		$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
		$erro_rovereti = 'sim';
	}

	// Tem algum erro? (Redundante)
	elseif ($errno) {
		$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
		$erro_rovereti = 'sim';
	} else {
		$msg_rovereti = 'Compra lan&ccedil;ada no ROVERETI com Sucesso!';
		$erro_rovereti = 'nao';
	}


	$msg = "Compra reenviada ao Rovereti";
}

include("../../includes/desconecta_bd.php");
// ================================================================================================================








// ======= MYSQL FILTRO DE BUSCA ==================================================================================
$mysql_numero = "numero_compra='$numero_compra_busca'";
$mysql_status = "estado_registro='ATIVO'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include("../../includes/conecta_bd.php");


$busca_compra = mysqli_query(
	$conexao,
	"SELECT 
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	preco_unitario,
	valor_total,
	unidade,
	tipo,
	observacao,
	data_pagamento,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	fornecedor_print,
	forma_entrega,
	usuario_exclusao,
	hora_exclusao,
	data_exclusao,
	retorno_rovereti
FROM 
	compras
WHERE 
	$mysql_numero AND
	$mysql_status AND
	movimentacao='COMPRA'
ORDER BY 
	codigo"
);


include("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows($busca_compra);
// ================================================================================================================


// ================================================================================================================
include("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>

	// Função oculta DIV depois de alguns segundos
	setTimeout(function() {
		$('#oculta').fadeOut('fast');
	}, 3000); // 3 Segundos
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


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_auto">


		<!-- ============================================================================================================= -->
		<div class="espacamento" style="height:15px"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1">
			<div class="ct_titulo_1">
				<?php echo $titulo; ?>
			</div>

			<div class="ct_subtitulo_right">
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_2">
			<div class="ct_subtitulo_left" id="oculta" style="color:#00F">
				<?php echo $msg; ?>
			</div>

			<div class="ct_subtitulo_right">
			</div>
		</div>
		<!-- ============================================================================================================= -->



		<!-- ============================================================================================================= -->
		<div class="pqa">


			<!-- ================================================================================================================ -->
			<div class="pqa_caixa">
				<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/compras/compra_rovereti_reenviar.php" method="post" />
				<input type="hidden" name="botao" value="BUSCAR" />
				<input type="hidden" name="fornecedor_pesquisa" value="<?php echo "$fornecedor_pesquisa"; ?>" />
				<input type="hidden" name="nome_fornecedor" value="<?php echo "$nome_fornecedor"; ?>" />
			</div>
			<!-- ================================================================================================================ -->


			<!-- ================================================================================================================ -->
			<div class="pqa_caixa">
				<div class="pqa_rotulo">
					N&uacute;mero da Compra:
				</div>

				<div class="pqa_campo">
					<input type="text" name="numero_compra_busca" class="pqa_input" id="ok" maxlength="12" value="<?php echo $numero_compra_busca; ?>" style="width:120px" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= BOTAO ================================================================================================== -->
			<div class="pqa_caixa">
				<div class="pqa_rotulo">
				</div>

				<div class="pqa_campo">
					<button type='submit' class='botao_1' style='float:left'>Buscar</button>
					</form>
				</div>
			</div>
			<!-- ================================================================================================================ -->


		</div>
		<!-- ====== FIM DIV PQA ============================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="espacamento" style="height:30px"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<?php
		if ($linha_compra == 0) {
			echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";
		} else {
			echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Reenviar</td>
<td width='100px'>Data</td>
<td width='350px'>Fornecedor</td>
<td width='100px'>N&uacute;mero</td>
<td width='160px'>Produto</td>
<td width='120px'>Quantidade</td>
<td width='100px'>Pre&ccedil;o Unit&aacute;rio</td>
<td width='140px'>Valor Total</td>
<td width='260px'>Retorno Rovereti</td>
</tr>
</table>";
		}


		echo "<table class='tabela_geral'>";


		// ====== FUNÇÃO FOR ===================================================================================
		for ($x = 1; $x <= $linha_compra; $x++) {
			$aux_compra = mysqli_fetch_row($busca_compra);

			// ====== DADOS DO CADASTRO ============================================================================
			$id_w = $aux_compra[0];
			$numero_compra_w = $aux_compra[1];
			$cod_fornecedor_w = $aux_compra[2];
			$produto_print_w = $aux_compra[3];
			$data_compra_w = $aux_compra[4];
			$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
			$quantidade_w = $aux_compra[5];
			$quantidade_print = number_format($aux_compra[5], 2, ",", ".");
			$preco_unitario_w = $aux_compra[6];
			$preco_unitario_print = number_format($aux_compra[6], 2, ",", ".");
			$total_geral_w = $aux_compra[7];
			$total_geral_print = "R$ " . number_format($aux_compra[7], 2, ",", ".");
			$unidade_w = $aux_compra[8];
			$tipo_w = $aux_compra[9];
			$observacao_w = $aux_compra[10];
			$data_pagamento_w = $aux_compra[11];
			$data_pagamento_print = date('d/m/Y', strtotime($aux_compra[11]));
			$usuario_cadastro_w = $aux_compra[12];
			$hora_cadastro_w = $aux_compra[13];
			$data_cadastro_w = $aux_compra[14];
			$usuario_alteracao_w = $aux_compra[15];
			$hora_alteracao_w = $aux_compra[16];
			$data_alteracao_w = $aux_compra[17];
			$estado_registro_w = $aux_compra[18];
			$filial_w = $aux_compra[19];
			$fornecedor_print_w = $aux_compra[20];
			$forma_entrega_w = $aux_compra[21];
			$usuario_exclusao_w = $aux_compra[22];
			$hora_exclusao_w = $aux_compra[23];
			$data_exclusao_w = $aux_compra[24];
			$retorno_rovereti_w = $aux_compra[25];


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


			// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
			$permite_visualizar = "SIM";
			// ========================================================================================================


			// ====== RELATORIO =======================================================================================
			if ($estado_registro_w == "INATIVO") {
				echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w &#13; Retorno Rovereti: $retorno_rovereti_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			} elseif ($estado_registro_w == "EXCLUIDO") {
				echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w &#13; Retorno Rovereti: $retorno_rovereti_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			} else {
				echo "<tr class='tabela_1' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w &#13; Retorno Rovereti: $retorno_rovereti_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			}



			// ====== BOTAO VISUALIZAR ==================================================================================
			if ($permite_visualizar == "SIM") {
				echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/compras/compras/compra_rovereti_reenviar.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='ROVERETI'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_compra' value='$numero_compra_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/atualizar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
			} else {
				echo "
<td width='60px' align='center'></td>";
			}
			// =================================================================================================================


			// =================================================================================================================
			echo "
<td width='100px' align='center'>$data_compra_print</td>
<td width='350px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='100px' align='center'>$numero_compra_w</td>
<td width='160px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:10px'>$quantidade_print $unidade_w</div></td>
<td width='100px' align='right'><div style='height:14px; margin-right:10px'>$preco_unitario_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$total_geral_print</div></td>
<td width='260px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$retorno_rovereti_w</div></td>";
			// =================================================================================================================

		}

		echo "</tr></table>";
		// =================================================================================================================


		// =================================================================================================================
		if ($linha_compra == 0 and $botao == "BUSCAR") {
			echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma compra encontrada.</i></div>";
		}
		// =================================================================================================================
		?>


		<!-- ============================================================================================================= -->
		<div class="espacamento" style="height:30px"></div>
		<!-- ============================================================================================================= -->


	</div>
	<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


	<!-- ============================================================================================================= -->
	<div class="espacamento" style="height:30px"></div>
	<!-- ============================================================================================================= -->


	</div>
	<!-- ====== FIM DIV CT ========================================================================================= -->



	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php
		//include ("../../includes/rodape.php");
		?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>