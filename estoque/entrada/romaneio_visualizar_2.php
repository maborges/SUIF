<?php
// ================================================================================================================
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'romaneio_visualizar_2';
$titulo = 'Estoque - Romaneio de Entrada';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$data_hoje = date('Y-m-d', time());
$mes_atras = date('Y-m-d', strtotime('-30 days'));

$botao = $_POST["botao"];
$botao_desconto = $_POST["botao_desconto"] ?? '';
$data_inicial_busca = $_POST["data_inicial_busca"] ?? '';
$data_final_busca = $_POST["data_final_busca"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$filial = $filial_usuario;

$fornecedor_busca = $_POST["fornecedor_busca"] ?? '';
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"] ?? '';
$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"] ?? '';
$forma_pesagem_busca = $_POST["forma_pesagem_busca"] ?? '';
$filial_busca = $_POST["filial_busca"] ?? '';
$status_busca = $_POST["status_busca"] ?? '';
$seleciona_pessoa = $_POST["seleciona_pessoa"] ?? '';


$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
// ================================================================================================================


// ====== DESCONTO CAFE QUENTE ============================================================================
// Toda carga de café conilon que chegar QUENTE no armazém para pesar, é descontato 500g de cada saca.
if ($botao_desconto == "CAFE_QUENTE") {
	$numero_romaneio = $_POST["numero_romaneio"];
	$quant_sacas_form = $_POST["quant_sacas_form"];
	$peso_inicial_form = $_POST["peso_inicial_form"];
	$quantidade_form = $_POST["quantidade_form"];
	$quant_desconto = $quant_sacas_form;
	//$quant_desconto = round($quant_sacas_form * 0.5);

	$desconto_cq_inicial = ($peso_inicial_form - $quant_desconto);
	$desconto_cq_liquido = ($quantidade_form - $quant_desconto);
	$quant_volume = round($desconto_cq_liquido / 60);

	$editar = mysqli_query($conexao, "UPDATE estoque SET peso_inicial='$desconto_cq_inicial', quantidade='$desconto_cq_liquido', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', quant_volume_sacas='$quant_volume', transferencia_filiais ='CQ', transferencia_numero='CAFE PESADO QUENTE. DESCONTO DE $quant_desconto KG' WHERE numero_romaneio='$numero_romaneio'");
}
// ========================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query($conexao, "SELECT * FROM estoque WHERE numero_romaneio='$numero_romaneio_busca' AND movimentacao='ENTRADA' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows($busca_romaneio);
// ================================================================================================================


// ================================================================================================================
if ($linha_romaneio == 0 or $numero_romaneio_busca == "") {
	header("Location: $servidor/$diretorio_servidor/estoque/entrada/romaneio_nao_localizado.php");
	exit;
}
// ================================================================================================================


// ================================================================================================================
for ($x = 1; $x <= $linha_romaneio; $x++) {
	$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$numero_romaneio = $aux_romaneio[1];
$num_romaneio_print = $aux_romaneio[1];
$fornecedor = $aux_romaneio[2];
$data_romaneio = $aux_romaneio[3];
$data_romaneio_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$cod_tipo_produto = $aux_romaneio[46];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6], $config[31], ",", ".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7], $config[31], ",", ".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto, $config[31], ",", ".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8], $config[31], ",", ".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9], $config[31], ",", ".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10], $config[31], ",", ".");
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = $aux_romaneio[28];
$quant_sacaria_print = number_format($aux_romaneio[28], $config[31], ",", ".");
$quant_volume_form = $aux_romaneio[39];
$quant_volume_print = number_format($aux_romaneio[39], $config[31], ",", ".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$cod_lote = $aux_romaneio[48];
$transferencia_filiais = $aux_romaneio[53];
$transferencia_numero = $aux_romaneio[54];

$usuario_cadastro = $aux_romaneio[19];
if ($usuario_cadastro == "") {
	$dados_cadastro = "";
} else {
	$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
	$hora_cadastro = $aux_romaneio[20];
	$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
}

$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "") {
	$dados_alteracao = "";
} else {
	$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
	$hora_alteracao = $aux_romaneio[23];
	$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}

$usuario_exclusao = $aux_romaneio[40];
if ($usuario_exclusao == "") {
	$dados_exclusao = "";
	$motivo_exclusao = $aux_romaneio[43];
	$data_exclusao = "";
	$hora_exclusao = "";
	$dados_exclusao = "Exclu&iacute;do por:";
} else {
	$usuario_exclusao = $aux_romaneio[40];
	$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
	$hora_exclusao = $aux_romaneio[41];
	$motivo_exclusao = $aux_romaneio[43];
	$dados_exclusao = "Exclu&iacute;do por: $usuario_exclusao $data_exclusao $hora_exclusao";
}
// ================================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0) {
	$descricao_sacaria = "(Sem sacaria)";
} else {
	$descricao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";
}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21], 2, ",", ".");
$quant_kg_saca = $aux_bp[27];
$nome_imagem_produto = $aux_bp[28];
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
// =======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA TIPO DE PRODUTO ===================================================================================
$busca_tipo_prod = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo_produto' AND estado_registro='ATIVO'");
$aux_tipo_prod = mysqli_fetch_row($busca_tipo_prod);

$tipo_produto_print = $aux_tipo_prod[1];
// ======================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
$quantidade_real = ($quantidade / $quant_kg_saca);
$quantidade_real_print = number_format($quantidade_real, 2, ",", ".");
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf") {
	$cpf_cnpj = $aux_forn[3];
} else {
	$cpf_cnpj = $aux_forn[4];
}

if ($linhas_fornecedor == 0) {
	$cidade_uf_fornecedor = "";
} else {
	$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";
}
// ======================================================================================================


// ====== SITUAÇÃO PRINT ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO") {
	$situacao_print = "Pr&eacute;-Romaneio";
} elseif ($situacao_romaneio == "EM_ABERTO") {
	$situacao_print = "Em Aberto";
} elseif ($situacao_romaneio == "FECHADO") {
	$situacao_print = "Fechado";
} else {
	$situacao_print = "-";
}
// ======================================================================================================


// ====== BUSCA ENTRADA =================================================================================
$busca_entrada = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio_busca' ORDER BY codigo");
$aux_busca_entrada = mysqli_fetch_row($busca_entrada);
$linha_entrada = mysqli_num_rows($busca_entrada);

if ($linha_entrada == 0) {
	$num_registro_entrada = "(Romaneio ainda n&atilde;o vinculado a ficha)";
} else {
	$num_registro_entrada = $aux_busca_entrada[1];
}
// ======================================================================================================


// ====== SOMA NOTAS FISCAIS ======================================================================
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio_busca'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0], 2, ",", ".");

$soma_quantidade_nf = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio'"));
$soma_quantidade_nf_print = number_format($soma_quantidade_nf[0], 0, ",", ".") . " $un_descricao";
// ======================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($estado_registro != "ATIVO") {
	$msg = "<div style='color:#FF0000' title='Motivo da Exclus&atilde;o: $motivo_exclusao'>Romaneio Exclu&iacute;do</div>";
	$msg_2 = "<div style='color:#FF0000' title='Motivo da Exclus&atilde;o: $motivo_exclusao'>$dados_exclusao</div>";
} elseif ($situacao_romaneio == "EM_ABERTO") {
	$msg = "<div style='color:#444'>Romaneio de Entrada Em Aberto</div>";
	$msg_2 = "<div style='color:#444'></div>";
} else {
	$msg = "<div style='color:#009900'>Romaneio de Entrada</div>";
	$msg_2 = "<div style='color:#009900'></div>";
}
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_romaneio);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[24] or $permissao[81] == "S") {
	$pode_editar = "S";
} else {
	$pode_editar = "N";
}

//if ($permissao[16] == "S" and $transferencia_filiais != "SIM" and $linha_entrada == 0 and $pode_editar == "S" and $estado_registro == "ATIVO")
if ($permissao[16] == "S" and $linha_entrada == 0 and $pode_editar == "S" and $estado_registro == "ATIVO") {
	$permite_editar = "SIM";
} else {
	$permite_editar = "NAO";
}
// ========================================================================================================

// ====== BLOQUEIO PARA FINALIZAR ========================================================================
if ($permissao[77] == "S" and $estado_registro == "ATIVO" and $situacao_romaneio == "EM_ABERTO") {
	$permite_finalizar = "SIM";
} else {
	$permite_finalizar = "NAO";
}
// ========================================================================================================

// ====== BLOQUEIO PARA NOTA FISCAL =======================================================================
if ($permissao[82] == "S" and $estado_registro == "ATIVO") {
	$permite_nf = "SIM";
} else {
	$permite_nf = "NAO";
}
// ========================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =======================================================================
if ($permissao[79] == "S" and $estado_registro == "ATIVO") {
	$permite_imprimir = "SIM";
} else {
	$permite_imprimir = "NAO";
}
// ========================================================================================================


// ================================================================================================================
include('../../includes/head.php');
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
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
		<?php include("../../includes/menu_estoque.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_estoque_entrada.php"); ?>
	</div>


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_1">

		<!-- ============================================================================================================= -->
		<div class="espacamento_25">
			<div style="float:left; width:520px; height:20px; border:0px solid #000"></div>

			<!-- ====== BOTAO VOLTAR ======================================================================================================== -->
			<div style="float:left; width:46px; border:0px solid #000">
				<form action='<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_numero.php' method='post'>
					<input type='image' src='<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/icones/voltar.png' height='20px' border='0' title='Voltar' />
				</form>
			</div>
			<!-- ============================================================================================================================= -->


			<!-- ====== BOTAO FINALIZAR  ===================================================================================================== -->
			<div style="float:left; width:46px; border:0px solid #000">
				<?php
				if ($permite_finalizar == "SIM") {
					echo "
        <form action='$servidor/$diretorio_servidor/estoque/entrada/finalizar_1_formulario.php' method='post'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='$botao'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
        <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
        <input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
        <input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
        <input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
        <input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/icones/balanca.png' height='20px' border='0' title='Finalizar' />
        </form>";
				} else {
					echo "<input type='image' class='preto_branco' src='$servidor/$diretorio_servidor/imagens/icones/balanca.png' height='20px' border='0' />";
				}

				?>
			</div>
			<!-- ============================================================================================================================= -->


			<!-- ====== BOTAO EDITAR ========================================================================================================= -->
			<div style="float:left; width:46px; border:0px solid #000">
				<?php
				if ($situacao_romaneio == "EM_ABERTO" and $permite_editar == "SIM") {
					echo "
		<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' border='0' title='Editar' />
        </form>";
				} elseif ($situacao_romaneio == "FECHADO" and $permite_editar == "SIM") {
					echo "
		<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' border='0' title='Editar' />
        </form>";
				} else {
					echo "<input type='image' class='preto_branco' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' border='0' />";
				}
				?>
			</div>
			<!-- ============================================================================================================================= -->


			<!-- ====== BOTAO NOTA FISCAL ==================================================================================================== -->
			<div style="float:left; width:46px; border:0px solid #000">
				<?php
				if ($permite_nf == "SIM") {
					echo "
        <form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_entrada/nota_fiscal.php' method='post'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/icones/doc_1.png' height='20px' border='0' title='Nota Fiscal' />
        </form>";
				} else {
					echo "<input type='image' class='preto_branco' src='$servidor/$diretorio_servidor/imagens/icones/doc_1.png' height='20px' border='0' />";
				}
				?>
			</div>
			<!-- ============================================================================================================================= -->


			<!-- ====== BOTAO IMPRIMIR ======================================================================================================= -->
			<div style="float:left; width:46px; border:0px solid #000">
				<?php
				if ($permite_imprimir == "SIM") {
					echo "
        <form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_impressao.php' method='post' target='_blank'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='num_romaneio_aux' value='$numero_romaneio'>
        <input type='hidden' name='numero_compra' value='$numero_compra'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' border='0' title='Imprimir' />
        </form>";
				} else {
					echo "<input type='image' class='preto_branco' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' border='0' />";
				}
				?>
			</div>
			<!-- ============================================================================================================================= -->

		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1" style="width: 100%">
			<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
				<?php echo "$msg"; ?>
			</div>

			<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
				N&ordm; <?php echo "$num_romaneio_print"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_2" style="width: 100%">
			<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
				<?php echo "$msg_2"; ?>
			</div>

			<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
				<?php echo "$data_romaneio_print"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div style="height:160px; width:1080px; border:0px solid #0000FF; margin:auto">


			<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
			<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
				<div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
					Fornecedor:
				</div>
			</div>

			<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

				<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

				<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$fornecedor_print</b>" ?></div>
				</div>

				<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$cpf_cnpj</b>" ?></div>
				</div>

				<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

				<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$cidade_uf_fornecedor</b>" ?></div>
				</div>

				<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$telefone_fornecedor</b>" ?></div>
				</div>

			</div>
			<!-- ======================================================================================================================= -->


			<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
			<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
					Produto:
				</div>
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
					<!-- xxxxxxxxxxxxxx: -->
				</div>
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
					<!-- xxxxxxxxxxxxxx: -->
				</div>
			</div>

			<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
				<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
					<?php echo "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
				</div>

				<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
					<?php echo "<b>$produto_print_2</b>" ?>
				</div>
			</div>

			<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" 
		?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" 
		?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" 
		?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" 
		?>
    </div>
</div>
-->
			<!-- ======================================================================================================================= -->


		</div>
		<!-- ======================================================================================================================= -->


		<div class="espacamento_10"></div>


		<div style="height:310px; width:1080px; border:0px solid #0000FF; margin:auto">

			<!-- =============================================== DADOS DO ROMANEIO ============================================================================= -->
			<div id="centro" style="width:1055px; height:270px; border:0px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:0px">
					<div class="form_rotulo" style="margin-top:5px">Peso Inicial:</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
					<div class="form_rotulo" style="margin-top:5px">Peso Final:</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
					<div class="form_rotulo" style="margin-top:5px">Peso Bruto:</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
					<div class="form_rotulo" style="margin-top:5px">Desconto Sacaria:</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
					<div class="form_rotulo" style="margin-top:5px">Outros Descontos:</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:20px">
					<div class="form_rotulo" style="margin-top:5px">Peso L&iacute;quido:</div>
				</div>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_inicial_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_final_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_bruto_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$desconto_sacaria_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$desconto_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "<b>$quantidade_print</b> Kg" ?></div>
				</div>


				<!-- ================================================= DADOS DO ROMANEIO ============================================================================= -->
				<div style="width:1025px; height:30px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Real em Sacas:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$quantidade_real_print Sacas</b>" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Tipo do Produto:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$tipo_produto_print" ?></div>
				</div>




				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Volume Sacas:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$quant_volume" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$num_romaneio_manual" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$descricao_sacaria" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Filial Origem:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$filial_origem" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quantidade Sacaria:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$quant_sacaria" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Motorista:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$motorista" ?></div>
				</div>


				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Desc. Previsto (Qualidade):</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$desconto_previsto Kg" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$motorista_cpf" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Registro de Ficha:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$num_registro_entrada" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$placa_veiculo" ?></div>
				</div>


				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:975px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
					<div style="margin-top:3px; margin-left:5px; width:650px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php echo "$observacao" ?></div>
				</div>


			</div>



			<!-- ============================================================================================================== -->


			<div id="tabela_2" style="width:1055px; height:20px; border:0px solid #000; font-size:12px; margin-top:15px">
				<div style="width:600px; margin-left:25px; color:#003466; float:left; border:0px solid #000">Notas Fiscais de Entrada:</div>
				<div style="width:200px; color:#003466; float:left; border:0px solid #000; text-align:right">Quantidade: <?php echo "$soma_quantidade_nf_print" ?></div>
				<div style="width:200px; color:#003466; float:right; border:0px solid #000; text-align:right">Total: R$ <?php echo "$soma_nota_fiscal_print"; ?></div>
			</div>



		</div>






		<!-- ================== INICIO DO RELATORIO ================= -->
		<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:0px;">

			<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
			<?php
			$busca_nota_fiscal = mysqli_query($conexao, "SELECT * FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio' ORDER BY data_emissao");
			$linha_nota_fiscal = mysqli_num_rows($busca_nota_fiscal);


			if ($linha_nota_fiscal == 0) {
				echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem notas fiscais para este romaneio.</i></div>";
			} else {
				echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='90px' height='20px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='380px' align='center' bgcolor='#006699'>Produtor</td>
<td width='120px' align='center' bgcolor='#006699'>N&ordm; Nota Fiscal</td>
<td width='122px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Total</td>
</tr>
</table>
</div>";
			}

			echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table class='tabela_geral'>";

			for ($w = 1; $w <= $linha_nota_fiscal; $w++) {
				$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

				// DADOS DO FAVORECIDO =========================
				$busca_favorecido_2 = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
				$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
				$codigo_pessoa_2 = $aux_busca_favorecido_2[35];

				$busca_pessoa_2 = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
				$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
				$nome_favorecido_2 = $aux_busca_pessoa_2[1];
				$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
				if ($tipo_pessoa_2 == "pf") {
					$cpf_cnpj_2 = $aux_busca_pessoa_2[3];
				} else {
					$cpf_cnpj_2 = $aux_busca_pessoa_2[4];
				}

				$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));
				$numero_nf_print_2 = $aux_nota_fiscal[3];
				$serie_nf_print_2 = $aux_nota_fiscal[27];
				$valor_unitario_print_2 = number_format($aux_nota_fiscal[5], 2, ",", ".");
				$valor_total_print_2 = number_format($aux_nota_fiscal[8], 2, ",", ".");
				$unidade_print_2 = $aux_nota_fiscal[6];
				$quantidade_print_2 = number_format($aux_nota_fiscal[7], 0, ",", ".");
				$observacao_print_2 = $aux_nota_fiscal[8];


				// RELATORIO =========================
				echo "
	<tr class='tabela_1' title=' CPF/CNPJ: $cpf_cnpj_2 &#13; Observa&ccedil;&atilde;o: $observacao_print_2'>
	<td width='90px' align='left'>&#160;&#160;$data_nf_print_2</td>
	<td width='380px' align='left'>&#160;&#160;$nome_favorecido_2</td>
	<td width='120px' align='center'>$serie_nf_print_2 $numero_nf_print_2</td>
	<td width='122px' align='center'>$quantidade_print_2 $unidade_print_2</td>
	<td width='122px' align='right'>$valor_unitario_print_2&#160;&#160;</td>
	<td width='122px' align='right'>&#160;&#160;$valor_total_print_2&#160;&#160;</td>
	</tr>";
			}
			echo "
	</table>
	</div>
	<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
	";


			?>




		</div>
		<!-- ================== FIM DO RELATORIO ================= -->


		<div class="espacamento_15"></div>


		<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
			<div id='centro' style='float:left; height:55px; width:135px; text-align:center; border:0px solid #000'></div>
			<?php

			// ====== BOTAO VOLTAR ========================================================================================================
			echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/entrada/buscar_romaneio.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
		</form>
	</div>";
			// =============================================================================================================================


			// ====== BOTAO FINALIZAR ========================================================================================================
			if ($permite_finalizar == "SIM") {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
        <form action='$servidor/$diretorio_servidor/estoque/entrada/finalizar_1_formulario.php' method='post'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='$botao'>
        <input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
        <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
        <input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
        <input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
        <input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
        <input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Finalizar</button>
        </form>
		</div>";
			} else {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Finalizar</button>
		</div>";
			}
			// =============================================================================================================================


			// ====== BOTAO EDITAR ========================================================================================================
			if ($situacao_romaneio == "EM_ABERTO" and $permite_editar == "SIM") {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
        </form>
		</div>";
			} elseif ($situacao_romaneio == "FECHADO" and $permite_editar == "SIM") {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
        <input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
        <input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
        </form>
		</div>";
			} else {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Editar</button>
		</div>";
			}
			// =============================================================================================================================


			// ====== BOTAO NOTA FISCAL =================================================================================================
			if ($permite_nf == "SIM") {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_entrada/nota_fiscal.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nota Fiscal</button>
        </form>
		</div>";
			} else {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Nota Fiscal</button>
		</div>";
			}
			// =============================================================================================================================


			// ====== BOTAO IMPRIMIR =======================================================================================================
			if ($permite_imprimir == "SIM") {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
        <form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$numero_romaneio'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
        <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Romaneio</button>
        </form>
		</div>";
			} else {
				echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir Romaneio</button>
		</div>";
			}
			// =============================================================================================================================

			?>
		</div>



		<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
			<div id='centro' style='float:left; height:55px; width:20px; text-align:center; border:0px solid #000'></div>

			<?php
			// ====== BOTAO DESCONTO =======================================================================================================
			if ($permite_editar == "SIM" and $cod_produto == "2" and $transferencia_filiais != "CQ") {
				echo "
		<div id='centro' style='float:left; height:55px; width:120px; color:#00F; text-align:left; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_visualizar_2.php' method='post'>
			<input type='hidden' name='botao' value='$botao'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
			<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
			<input type='hidden' name='data_final_busca' value='$data_final_busca'>
			<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
			<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
			<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
			<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
			<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
			<input type='hidden' name='botao_desconto' value='CAFE_QUENTE'>
			<input type='hidden' name='peso_inicial_form' value='$peso_inicial'>
			<input type='hidden' name='quantidade_form' value='$quantidade'>

	        <div class='form_rotulo' style='width:110px; height:17px; border:1px solid transparent; float:left; margin-top:10px'>
			Quant. em Kg:
    	    </div>
		</div>

		<div id='centro' style='float:left; height:55px; width:70px; color:#00F; text-align:left; border:0px solid #000'>
        
			<div style='width:60px; height:25px; float:left; border:1px solid transparent; margin-top:5px'>
			<input type='text' name='quant_sacas_form' class='form_input' maxlength='10' onkeydown='if (getKey(event) == 13) return false;' 
			style='width:50px; text-align:center' />
			</div>
		</div>

		<div id='centro' style='float:left; height:55px; width:120px; color:#00F; text-align:left; border:0px solid #000'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:100px'>Descontar</button>
	        </form>
		</div>";
			}
			// =============================================================================================================================
			?>





		</div>


		<div style="width:1050px; height:25px; border:0px solid #000; color:#003466; border-radius:0px; overflow:hidden; margin:auto; font-size:10px">
			<?php
			if ($transferencia_filiais == "CQ") {
				echo "<i>* $transferencia_numero</i>";
			}
			?>
		</div>




		<div class="espacamento_30"></div>

		<div id="centro" style="width:1050px; height:25px; border:1px solid #999; color:#003466; border-radius:0px; overflow:hidden; margin:auto; background-color:#EEE">
			<div style="margin-left:20px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo "<i>$dados_cadastro</i>" ?></div>
			<div style="margin-left:40px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo "<i>$dados_alteracao</i>" ?></div>
		</div>



		<!-- ============================================================================================================= -->
		<div class="espacamento_20"></div>
		<!-- ============================================================================================================= -->





	</div>
	<!-- ====== FIM DIV CT_1 ========================================================================================= -->




	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>