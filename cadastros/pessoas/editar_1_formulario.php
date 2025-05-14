<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");

require_once("../../sankhya/Sankhya.php");
require_once("../../sankhya/SankhyaKeys.php");

$pagina = "editar_1_formulario";
$pagina_mae = $_POST["pagina_mae"] ?? '';

$titulo = "Editar Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";


// ================================================================================================================
$erro = 0;
$msg  = "";

// Verifica se a requisição é uma requisição AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnGetSankhya'])) {

	if (isset($_POST['IdSankhya']) && $_POST['IdSankhya'] <> "") {

		$idSankhya_form = $_POST['IdSankhya'];
		$_POST['idSankhya_form'] = $idSankhya_form;

		$parceiro = Sankhya::getParceiroById($idSankhya_form);

		if ($parceiro['errorCode'] != 0) {
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>" . $parceiro['errorMessage'] . "</div>";
		} else if (count($parceiro['rows']) == 0) {
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>Informe um código Sankhya válido</div>";
		} else {
			$_POST['tipo_pessoa_form'] = $parceiro['rows'][0][1];
			$_POST["nome_fantasia_form"] = substr($parceiro['rows'][0][2],0,70);
			$_POST["nome_form"] = substr($parceiro['rows'][0][3],0,70);

			if ($_POST['tipo_pessoa_form'] == 'PF') {
				$_POST["cpf_form"] = $parceiro['rows'][0][4];
				$_POST["rg_form"] = $parceiro['rows'][0][5];
			} else {
				$_POST["cnpj_form"] = $parceiro['rows'][0][4];
				$_POST["rg_form"] = '';
			}

			$_POST["telefone_1_form"] = substr($parceiro['rows'][0][6], 2, 12);
			$_POST["telefone_2_form"] = substr($parceiro['rows'][0][7], 2, 12);

			$_POST["endereco_form"] = $parceiro['rows'][0][8];
			$_POST["numero_residencia_form"] = $parceiro['rows'][0][9];
			$_POST["complemento_form"] = $parceiro['rows'][0][22];
			$_POST["bairro_form"] = $parceiro['rows'][0][10];
			$_POST["cep_form"] = $parceiro['rows'][0][11];
			$_POST["email_form"] = $parceiro['rows'][0][12];
			$_POST["obs_form"] = substr($parceiro['rows'][0][13], 1, 150);
			$_POST["data_nascimento_form"] = $parceiro['rows'][0][16];
			$_POST["sexo_form"] = $parceiro['rows'][0][17];
			$_POST['cadastroRevisado'] = $parceiro['rows'][0][24];
			$_POST['embargado'] = $parceiro['rows'][0][25];
			$_POST['validadoSERASA'] = $parceiro['rows'][0][26];

			if ($parceiro['rows'][0][18] == 'P' or $parceiro['rows'][0][18] == 'PC') {  // PRODUTOR
				$_POST["classificacao_1_form"] = 63;
			} else if ($parceiro['rows'][0][18] == 'F') {  // FAVORECIDO
				$_POST["classificacao_1_form"] = 64;
			} else { // SE NÃO FOR PRODUTOR NEM FAVORECIDO, VAI VIRAR CLIENTE
				$_POST["classificacao_1_form"] = 30;
			}

			$idEstado = $parceiro['rows'][0][15];
			$estado = Sankhya::getIdEstadoDeParaSankhya($idEstado);
			$rowsCount = 0;

			if ($estado['errorCode']) {
				$erro = 1;
				$msg  = "<div style='color:#FF0000'>Erro: {$estado['errorCode']}: {$estado['errorMessage']}</div>";
			} else {
				$rowsCount = count($estado['rows'] ?? []);

				if ($rowsCount == 0) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Estado '$idEstado' não cadastrado no SUIF </div>";
				} else if ($rowsCount > 1) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Existe mais de um estado com UF '$uf' cadastrado no SUIF</div>";
				} else {
					$_POST["estado"] = $parceiro['rows'][0][15];
				}
			}

			$cidade = Sankhya::getIdCidadeDeParaSankhya($estado['rows'][0][0], $parceiro['rows'][0][14]);
			$rowsCount = 0;

			if ($cidade['errorCode']) {
				$erro = 1;
				$msg  = "<div style='color:#FF0000'>Erro: {$cidade['errorCode']}: {$cidade['errorMessage']}</div>";
			} else {
				$rowsCount = count($cidade['rows'] ?? []);

				if ($rowsCount == 0) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Cidade {$parceiro['rows'][0][14]} não cadastrada no estado {$parceiro['rows'][0][15]} no SUIF</div>";
				} else if ($rowsCount > 1) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Existe mais de uma cidade no SUIF cadastrada com o nome {$parceiro['rows'][0][14]} para o estado {$parceiro['rows'][0][15]}</div>";
				} else {
					$_POST["cidade"] = $cidade['rows'][0][1];
				}
			}
		}
	} else {
		$erro = 1;
		$msg  = "<div style='color:#FF0000'>Informe um código Sankhya válido</div>";
	}
}

// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"] ?? '';
$id_w = $_POST["id_w"] ?? '';
$idSankhya_w = $_POST["idSankhya_w"] ?? '';
$idSankhyaInformado = $_POST['idSankhyaInformado'] ?? '0';
$codigo_pessoa_w = $_POST["codigo_pessoa_w"] ?? '';
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$pesquisar_por_busca = $_POST["pesquisar_por_busca"] ?? '';
$nome_busca = $_POST["nome_busca"] ?? '';
$cpf_busca = $_POST["cpf_busca"] ?? '';
$cnpj_busca = $_POST["cnpj_busca"] ?? '';
$fantasia_busca = $_POST["fantasia_busca"] ?? '';
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$id_w'");
$linha_pessoa = mysqli_num_rows($busca_pessoa);
// ================================================================================================================


// ====== FUNÇÃO FOR ==============================================================================================
for ($x = 1; $x <= $linha_pessoa; $x++) {
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
}
// ================================================================================================================

if ($botao == "EDITAR") {
	// ====== DADOS DO CADASTRO =======================================================================================
	$idSankhya_w =  $aux_pessoa[41];
	$idSankhyaInformado == $idSankhya_w;
	$nome_form = $aux_pessoa[1];
	$tipo_pessoa_form = $aux_pessoa[2];
	$cpf_form = $aux_pessoa[3];
	$cnpj_form = $aux_pessoa[4];
	$rg_form = $aux_pessoa[5];
	$sexo_form = $aux_pessoa[6];
	$data_nascimento_form = $aux_pessoa[7];
	$endereco_form = $aux_pessoa[8];
	$bairro_form = $aux_pessoa[9];
	$cidade = $aux_pessoa[10];
	$cep_form = $aux_pessoa[11];
	$estado = $aux_pessoa[12];
	$telefone_1_form = $aux_pessoa[14];
	$telefone_2_form = $aux_pessoa[15];
	$email_form = $aux_pessoa[17];
	$classificacao_1_form = $aux_pessoa[18];
	$obs_form = $aux_pessoa[22];
	$nome_fantasia_form = $aux_pessoa[24];
	$numero_residencia_form = $aux_pessoa[25];
	$complemento_form = $aux_pessoa[26];
	$estado_registro_form = $aux_pessoa[34];
	$codigo_pessoa_form = $aux_pessoa[35];
	$cadastroRevisado = $aux_pessoa[46];
	$validadoSERASA = $aux_pessoa[47];
	$embargado = $aux_pessoa[48];

	if ($data_nascimento_form == "1900-01-01" or $data_nascimento_form == "" or empty($data_nascimento_form)) {
		$data_nascimento_print = "";
	} else {
		$data_nascimento_print = date('d/m/Y', strtotime($data_nascimento_form));
	}

	$usuario_cadastro_w = $aux_pessoa[28];
	if ($usuario_cadastro_w == "") {
		$dados_cadastro_w = "";
	} else {
		$data_cadastro_w = date('d/m/Y', strtotime($aux_pessoa[30]));
		$hora_cadastro_w = $aux_pessoa[29];
		$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
	}

	$usuario_alteracao_w = $aux_pessoa[31];
	if ($usuario_alteracao_w == "") {
		$dados_alteracao_w = "";
	} else {
		$data_alteracao_w = date('d/m/Y', strtotime($aux_pessoa[33]));
		$hora_alteracao_w = $aux_pessoa[32];
		$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
	}

	$usuario_exclusao_w = $aux_pessoa[36];
	if ($usuario_exclusao_w == "") {
		$dados_exclusao_w = "";
	} else {
		$data_exclusao_w = date('d/m/Y', strtotime($aux_pessoa[37]));
		$hora_exclusao_w = $aux_pessoa[38];
		$motivo_exclusao_w = $aux_pessoa[39];
		$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
	}
	// ======================================================================================================
} else if ($botao == "") {
	// ======================================================================================================
	$idSankhya_form = $_POST["idSankhya_form"] ?? '';
	$idSankhya = $_POST["idSankhya_form"] ?? '';
	$idSankhya_w = $_POST["idSankhya_form"] ?? '';
	$tipo_pessoa_form = $_POST["tipo_pessoa_form"] ?? '';
	$nome_form = $_POST["nome_form"] ?? '';
	$cpf_form = $_POST["cpf_form"] ?? '';
	$cnpj_form = $_POST["cnpj_form"] ?? '';
	$rg_form = $_POST["rg_form"] ?? '';
	$data_nascimento_form = $_POST["data_nascimento_form"] ?? '';
	$sexo_form = $_POST["sexo_form"] ?? '';
	$nome_fantasia_form = $_POST["nome_fantasia_form"] ?? '';
	$telefone_1_form = $_POST["telefone_1_form"] ?? '';
	$telefone_2_form = $_POST["telefone_2_form"] ?? '';
	$endereco_form = $_POST["endereco_form"] ?? '';
	$numero_residencia_form = $_POST["numero_residencia_form"] ?? '';
	$bairro_form = $_POST["bairro_form"] ?? '';
	$estado = $_POST["estado"] ?? '';
	$cidade = $_POST["cidade"] ?? '';
	$complemento_form = $_POST["complemento_form"] ?? '';
	$cep_form = $_POST["cep_form"] ?? '';
	$email_form = $_POST["email_form"] ?? '';
	$obs_form = $_POST["obs_form"] ?? '';
	$classificacao_1_form = $_POST["classificacao_1_form"] ?? '';
	$banco_form = $_POST["banco_form"] ?? '';
	$agencia_form = $_POST["agencia_form"] ?? '';
	$numero_conta_form = $_POST["numero_conta_form"] ?? '';
	$tipo_conta_form = $_POST["tipo_conta_form"] ?? '';
	$cadastroRevisado = $_POST['cadastroRevisado'] ?? '';
	$validadoSERASA = $_POST['validadoSERASA'] ?? 'N';
	$embargado = $_POST['embargado'] ?? 'N';

	if ($data_nascimento_form == "1900-01-01" or $data_nascimento_form == "" or empty($data_nascimento_form)) {
		$data_nascimento_print = "";
	} else {
		$data_nascimento_print = $data_nascimento_form;
	}
	// ========================================================================================================
}

// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
	$tipo_pessoa_print = "PESSOA F&Iacute;SICA";
} elseif ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
	$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";
} else {
	$tipo_pessoa_print = "";
}
// ================================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($permissao[69] != "S") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar cadastro de pessoa.</div>";
}
// ======================================================================================================


$badge = 'badge-success';
$cadastroRevisado_texto = '';

if ($cadastroRevisado == 'S') {
	$cadastroRevisado_texto = 'Revisado';
} else {
	$cadastroRevisado_texto = 'Não Revisado';
	$badge = 'badge-warning';
}

// =============================================================================
include("../../includes/head.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>
</script>

<script>
	function buscar_cidades() {
		var estado = $('#estado').val();
		if (estado) {
			var url = 'ajax_buscar_cidades.php?estado=' + estado;
			$.get(url, function(dataReturn) {
				$('#load_cidades').html(dataReturn);
			});
		}
	}

	// Função oculta DIV depois de alguns segundos
	setTimeout(function() {
		$('#oculta').fadeOut('fast');
	}, 2500); // 2,5 Segundos

	// Limpa campos do formulário quando o Id do Sankhya for alterado
	function LimpaForms() {

		form = document.getElementById("frmCampos"); // frmCampos frmTipoPessoa

		for (var i = 0; i < form.elements.length; i++) {
			var element = form.elements[i];

			if (element.tagName.toLowerCase() === 'input') {
				element.value = "";
			}

		}

		// trata os elementos combo
		document.getElementById("tipo_pessoa_form").innerText = "";
		document.getElementById("estado").value = "";
		document.getElementById("cidade").value = "";
		document.getElementById("classificacao_1_form").value = "";

		// Elementos que podem não ter sido criados
		elem = document.getElementById("btnSalvar");
		if (elem) {
			elem.disabled = true;
		}

		elem = document.getElementById("sexo_form");
		if (elem) {
			elem.value = '';
		}

	}
</script>

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/maskbrphone.js"></script>
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/jquery.mask_2.js"></script>

<script>
	jQuery(function($) {
		// MASK
		$("#cpf").mask("999.999.999-99");
		$("#cnpj").mask("99.999.999/9999-99");
		$("#rg").mask("99.999.999-9");
		$("#data").mask("99/99/9999");
		$("#hora").mask("99:99:99");
		$("#cep").mask("99.999-999");
		$("#letra").mask("aaaaaa");
		$("#letra_num").mask("*****");
		// "9" = Somente Número
		// "a" = Somente Letra
		// "*" = Letra e Números

		// MASK_2
		$("#conta_bancaria").mask_2("#.##A-A", {
			reverse: true
		});
		$("#cpf_2").mask_2("000.000.000-00");
		$("#data_2").mask_2("00/00/0000", {
			placeholder: "__/__/____"
		});
		$("#hora_2").mask_2("00:00:00");
		$("#data_hora_2").mask_2("00/00/0000 00:00:00");
		$("#dinheiro").mask_2("000.000.000,00", {
			reverse: true
		});
		// "0" = Um digito obrigatório
		// "9" = Um digito opcional
		// "#" = Um digito com recurção
		// "A" = Uma letra de a até z (maiúsculas ou minusculas) ou um digito
		// "S" = Uma letra de a até z (maiúsculas ou minusculas) sem digito 

		// VALOR MONETÁRIO (R$ 8.888,88)
		$("#valor_money").maskMoney({
			symbol: 'R$ ', //Símbolo a ser usado antes de os valores do usuário. padrão: ‘EUA $’
			showSymbol: true, //definir se o símbolo deve ser exibida ou não. padrão: false
			thousands: '.', //Separador de milhares. padrão: ‘,’
			decimal: ',', //Separador do decimal. padrão: ‘.’
			precision: 2, //Quantas casas decimais são permitidas. Padrão: 2
			symbolStay: true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
		});

		// TELEFONE COM DDD 1
		$("#telddd_1").maskbrphone({
			useDdd: true,
			useDddParenthesis: true,
			dddSeparator: ' ',
			numberSeparator: '-'
		});

		// TELEFONE COM DDD 2
		$("#telddd_2").maskbrphone({
			useDdd: true,
			useDddParenthesis: true,
			dddSeparator: ' ',
			numberSeparator: '-'
		});

	});
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
		<?php include("../../includes/menu_cadastro.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_cadastro_pessoas.php"); ?>
	</div>


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_auto">


		<!-- ============================================================================================================= -->
		<div class="espacamento_15"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1">
			<div class="ct_titulo_1">
				<?php echo "$titulo"; ?>
			</div>

			<div class="ct_subtitulo_right" style="margin-top:8px">
				<!-- xxxxxxxxxxxxxxxxx -->
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_2">
			<div class="ct_subtitulo_left">
				<?php echo "$msg"; ?>
			</div>

			<div class="ct_subtitulo_right">
				<!-- xxxxxxxxxxxxxxxxx -->
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
		<div style="width:1030px; height:370px; margin:auto; border:1px solid transparent">

			<!-- =======  ID SANKHYA ============================================================================================== -->
			<div style="width:100%; height:50px; border:1px solid transparent;  float:left">
				<div>
					<form name="frmSankhya" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/editar_1_formulario.php" method="post">

						<input type="hidden" name="botao" value="" />
						<input type="hidden" name="id_w" value="<?php echo "$id_w"; ?>" />
						<input type="hidden" name="idSankhya_w" value="<?php echo "$idSankhya_w"; ?>" />
						<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />
						<input type="hidden" name="tipo_pessoa_form" value="<?php echo "$tipo_pessoa_form"; ?>" />
						<input type="hidden" name="pesquisar_por_busca" value="<?php echo "$pesquisar_por_busca"; ?>" />
						<input type="hidden" name="nome_busca" value="<?php echo "$nome_busca"; ?>" />
						<input type="hidden" name="cpf_busca" value="<?php echo "$cpf_busca"; ?>" />
						<input type="hidden" name="cnpj_busca" value="<?php echo "$cnpj_busca"; ?>" />
						<input type="hidden" name="fantasia_busca" value="<?php echo "$fantasia_busca"; ?>" />
						<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
						<input type='hidden' name='cadastroRevisado' value="<?= $cadastroRevisado ?>">
						<input type='hidden' name='validadoSERASA' value="<?= $validadoSERASA ?>">
						<input type='hidden' name='embargado' value="<?= $embargado ?>">

						<div class="pqa_caixa">
							<div class="pqa_rotulo" style="margin-left:0px;">
								ID Sankhya:
							</div>
							<!--
							<div class="pqa_campo" style="margin-left:0px;">
								<input <?php if ($idSankhyaInformado) {
											echo 'readonly';
										} ?>  type="number" name="IdSankhya" class="pqa_input" id="IdSankhya_w" maxlength="8" onchange="LimpaForms()" style="width:145px; <?php if ($idSankhyaInformado) {
																																												echo ' background-color:#EEE;';
																																											} ?>" value="<?php echo $idSankhya_w ?>" />
							</div>
-->
							<div class="pqa_campo" style="margin-left:0px;">
								<input readonly type="number" name="IdSankhya" class="pqa_input" id="IdSankhya_w" maxlength="8" onchange="LimpaForms()" style="width:145px; background-color:#EEE" value="<?php echo $idSankhya_w ?>" />
							</div>

						</div>

						<div class="pqa_caixa">
							<div class="pqa_rotulo">
							</div>

							<div class="pqa_campo" style="margin-left: 10px;">
								<button type=<?php
												// Botão de atualização só fica visivel qdo Id Sankhya informado
												if ($idSankhyaInformado) {
													echo 'submit';
												} else {
													echo 'hidden';
												}
												?>
									id="btnGetSankhya" name="btnGetSankhya" style='float:left'>Atualizar</button>

								<div style="border:0px solid #000; font-size:12px; float:left; align-items:center; padding-left: 20px">
									<span class="badge <?= $badge ?>" style='font-size:110%'><?= $cadastroRevisado_texto ?></span>
								</div>
							</div>


						</div>

						<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />

					</form>
				</div>
			</div>
			<!-- FIM SANKHYA-->


			<!-- =======  TIPO PESSOA ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Pessoa:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
					<div id='tipo_pessoa_form' style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<form id="frmCampos" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/editar_2_enviar.php" method="post">
				<input type="hidden" name="botao" value="EDITAR_CADASTRO" />
				<input type="hidden" name="id_w" value="<?php echo "$id_w"; ?>" />
				<input type="hidden" name="idSankhya_w" value="<?php echo "$idSankhya_w"; ?>" />
				<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />
				<input type="hidden" name="tipo_pessoa_form" value="<?php echo "$tipo_pessoa_form"; ?>" />
				<input type="hidden" name="pesquisar_por_busca" value="<?php echo "$pesquisar_por_busca"; ?>" />
				<input type="hidden" name="nome_busca" value="<?php echo "$nome_busca"; ?>" />
				<input type="hidden" name="cpf_busca" value="<?php echo "$cpf_busca"; ?>" />
				<input type="hidden" name="cnpj_busca" value="<?php echo "$cnpj_busca"; ?>" />
				<input type="hidden" name="fantasia_busca" value="<?php echo "$fantasia_busca"; ?>" />
				<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
				<input type='hidden' name='cadastroRevisado' value="<?= $cadastroRevisado ?>">
				<input type='hidden' name='embargado' value="<?= $embargado ?>">
				<input type='hidden' name='validadoSERASA' value="<?= $validadoSERASA ?>">

				<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
				<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
						<?php
						if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
							echo "Raz&atilde;o Social:";
						} else {
							echo "Nome:";
						}
						?>
					</div>

					<div style="width:500px; height:25px; float:left; border:1px solid transparent">
						<?php
						if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj" or $tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
							echo "
        <input readonly type='text' name='nome_form' class='form_input' id='ok' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:486px; text-align:left; padding-left:5px' value='$nome_form' />";
						} else {
							echo "
        <input readonly type='text' name='nome_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:486px; text-align:left; padding-left:5px' disabled='disabled' title='Selecione o tipo de pessoa' value='$nome_form' />";
						}
						?>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CPF / CNPJ ============================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						<?php
						if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
							echo "CNPJ:";
						} else {
							echo "CPF:";
						}
						?>
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<?php
						if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
							echo "<input readonly type='text' name='cnpj_form' class='form_input' maxlength='18' id='cnpj' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$cnpj_form' />";
						} else {
							echo "<input readonly type='text' name='cpf_form' class='form_input' maxlength='14' id='cpf' 
        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:145px; text-align:left; padding-left:5px' value='$cpf_form' />";
						}
						?>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= RG / IE ================================================================================================ -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						<?php
						if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
							echo "IE:";
						} else {
							echo "RG:";
						}
						?>
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="rg_form" class="form_input" maxlength="20" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$rg_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= DATA NASCIMENTO ======================================================================================== -->
				<?php
				if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
					echo "
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
		Data de Nascimento:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<input readonly type='text' name='data_nascimento_form' class='form_input' id='data' maxlength='10' 
        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:145px; text-align:left; padding-left:5px' value='$data_nascimento_print' />
        </div>
	</div>";
				}
				?>
				<!-- ================================================================================================================ -->


				<!-- ======= SEXO =================================================================================================== -->
				<?php
				if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
					echo "
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
		Sexo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<select readonly name='sexo_form' id='sexo_form' class='form_select' onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:154px' />";
					if ($sexo_form == "MASCULINO") {
						echo "
		<option></option>
		<option selected='selected' value='MASCULINO'>MASCULINO</option>
		<option value='FEMININO'>FEMININO</option>";
					} elseif ($sexo_form == "FEMININO") {
						echo "
		<option></option>
		<option value='MASCULINO'>MASCULINO</option>
		<option selected='selected' value='FEMININO'>FEMININO</option>";
					} else {
						echo "
		<option></option>
		<option value='MASCULINO'>MASCULINO</option>
		<option value='FEMININO'>FEMININO</option>";
					}

					echo "
		</select>
		</div>
	</div>";
				}
				?>
				<!-- ================================================================================================================ -->


				<!-- ======= NOME FANTASIA ========================================================================================== -->
				<?php
				if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
					echo "
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		Nome Fantasia:
        </div>
        
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <input readonly type='text' name='nome_fantasia_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:315px; text-align:left; padding-left:5px' value='$nome_fantasia_form' />
        </div>
	</div>";
				}
				?>
				<!-- ================================================================================================================ -->


				<!-- ======= TELEFONE 1 ============================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Telefone 1:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="telefone_1_form" class="form_input" id="telddd_1" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$telefone_1_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= TELEFONE 2 ============================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Telefone 2:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="telefone_2_form" class="form_input" id="telddd_2" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$telefone_2_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- =======  ENDEREÇO ============================================================================================== -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Endere&ccedil;o:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="endereco_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$endereco_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= NUMERO ================================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						N&uacute;mero:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="numero_residencia_form" class="form_input" maxlength="10" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$numero_residencia_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= BAIRRO ==================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Bairro/Distrito:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="bairro_form" class="form_input" maxlength="40" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$bairro_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= ESTADO =================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Estado:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly name="estado" id="estado" class="form_select" onchange="buscar_cidades()" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px" />
						<option></option>
						<?php
						$busca_estado = mysqli_query($conexao, "SELECT * FROM cad_estados ORDER BY est_sigla");
						$linhas_estado = mysqli_num_rows($busca_estado);

						for ($i = 0; $i < $linhas_estado; $i++) {
							$aux_estado = mysqli_fetch_array($busca_estado);
							$arrEstados[$aux_estado['est_id']] = $aux_estado['est_sigla'];
						}

						foreach ($arrEstados as $value => $name) {
							if ($estado == $name) {
								echo "<option selected='selected' value='{$value}'>{$name}</option>";
							} else {
								echo "<option value='{$value}'>{$name}</option>";
							}
						}

						?>
						</select>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CIDADE =================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Cidade:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<div id="load_cidades">
							<select readonly name="cidade" id="cidade" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px; font-size:12px" />
							<option></option>
							<?php
							if ($estado != "") {
								$busca_cidade = mysqli_query($conexao, "SELECT * FROM cad_cidades ORDER BY cid_nome");
								$linhas_cidade = mysqli_num_rows($busca_cidade);

								for ($i = 1; $i <= $linhas_cidade; $i++) {
									$aux_cidade = mysqli_fetch_row($busca_cidade);
									if ($aux_cidade[1] == $cidade) {
										echo "<option selected='selected' value='$aux_cidade[0]'>$aux_cidade[1]</option>";
									} else {
										echo "<option value='$aux_cidade[0]'>$aux_cidade[1]</option>";
									}
								}
							}
							?>
							</select>
						</div>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= COMPLEMENTO ============================================================================================ -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Complemento:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="complemento_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$complemento_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CEP ==================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						CEP:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="cep_form" class="form_input" maxlength="10" id="cep" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$cep_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= E-MAIL ================================================================================================= -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						E-mail:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="email_form" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$email_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= OBSERVAÇÃO ============================================================================================= -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Observa&ccedil;&atilde;o:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="obs_form" class="form_input" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$obs_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CLASSIFICACAO ========================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Classifica&ccedil;&atilde;o:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly name="classificacao_1_form" id="classificacao_1_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px" />
						<option></option>
						<?php
						$busca_classificacao = mysqli_query($conexao, "SELECT * FROM classificacao_pessoa WHERE tipo='classificacao' AND estado_registro='ATIVO' ORDER BY codigo");
						$linhas_classificacao = mysqli_num_rows($busca_classificacao);

						for ($i = 1; $i <= $linhas_classificacao; $i++) {
							$aux_classificacao = mysqli_fetch_row($busca_classificacao);

							if ($aux_classificacao[0] == $classificacao_1_form) {
								echo "<option selected='selected' value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";
							} else {
								echo "<option value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";
							}
						}
						?>
						</select>
					</div>
				</div>

				<!-- ======= SERASA ============================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<input type="checkbox" id="validadoSERASA" name="validadoSERASA" value="S" <?= $validadoSERASA == 'S' ? 'checked' : '' ?>>
					<label for="validadoSERASA" class="form_rotulo"> Validado SERASA</label><br>
				</div>

				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<input type="checkbox" id="embargado" name="embargado" value="S" <?= $embargado == 'S' ? 'checked' : '' ?>>
					<label for="embargado" class="form_rotulo"> Embargado</label><br>
				</div>

				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style="width:1020px; height:10px; float:left"></div>
		</div>
		<!-- ===========  FIM DO FORMULÁRIO =========== -->





		<!-- ============================================================================================================= -->
		<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">

			<?php
			if ($erro != 0) {
				echo "
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
	</div>";
			} else {
				echo "
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' id='btnSalvar' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</form>
	</div>";
			}

			?>
		</div>

		<div class="espacamento_10"></div>
		<!-- ============================================================================================================= -->

	</div>
	<!-- ====== FIM DIV CT ========================================================================================= -->

	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>