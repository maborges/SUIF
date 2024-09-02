<?php

require_once("../../includes/config.php");
require_once("../../includes/conecta_bd.php");
require_once("../../includes/valida_cookies.php");
require_once("../../helpers.php");
require_once("../../sankhya/Sankhya.php");
require_once("../../sankhya/SankhyaKeys.php");


$pagina = "cadastro_1_formulario";
$titulo = "Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";

// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST['botao'] ?? '';
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$erro = 0;
$msg  = '';


// Verifica se a requisição é uma requisição AJAX e se foi clicado nos botões do Sankhya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (isset($_POST['btnGetSankhya'])) {
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
				$_POST["nome_fantasia_form"] = $parceiro['rows'][0][2];
				$_POST["nome_form"] = $parceiro['rows'][0][3];

				if ($_POST['tipo_pessoa_form'] == 'PF') {
					$_POST["cpf_form"] = Helpers::mask($parceiro['rows'][0][4], '###.###.###-##');
					$_POST["rg_form"] = $parceiro['rows'][0][5];
				} else {
					$_POST["cnpj_form"] = Helpers::mask($parceiro['rows'][0][4], '##.###.###/####-##');
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
				$_POST["favorecido_form"] = $parceiro['rows'][0][23];

				if ($parceiro['rows'][0][18] == 'P' or $parceiro['rows'][0][18] == 'PC') {  // PRODUTOR
					$_POST["classificacao_1_form"] = 63;
				} else if ($parceiro['rows'][0][18] == 'F') {  // FORNECEDOR
					$_POST["classificacao_1_form"] = 32;
				} else {
					$_POST["classificacao_1_form"] = 30;
				}

				$siglaEstado = $parceiro['rows'][0][15];
				$recEstado = Sankhya::getIdEstadoDeParaSankhya($siglaEstado);
				$rowsCount = 0;

				if ($recEstado['errorCode']) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Erro: {$recEstado['errorCode']}: {$recEstado['errorMessage']}</div>";
				} else {
					$rowsCount = count($recEstado['rows'] ?? []);

					if ($rowsCount == 0) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Estado '$siglaEstado' não cadastrado no SUIF </div>";
					} else if ($rowsCount > 1) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Existe mais de um estado com UF '$siglaEstado' cadastrado no SUIF</div>";
					} else {
						$_POST["estado"] = $siglaEstado;
					}
				}

				$cidade = Sankhya::getIdCidadeDeParaSankhya($recEstado['rows'][0][0], $parceiro['rows'][0][14]);
				$rowsCount = 0;

				if ($cidade['errorCode']) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Erro: {$cidade['errorCode']}: {$cidade['errorMessage']}</div>";
				} else {
					$rowsCount = count($cidade['rows']) ?? [];

					if ($rowsCount == 0) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Cidade {$parceiro['rows'][0][14]} não cadastrada no estado {$parceiro['rows'][0][15]} no SIUF</div>";
					} else if ($rowsCount > 1) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Existe mais de uma cidade no SUIF cadastrada com o nome {$parceiro['rows'][0][14]} para o estado {$parceiro['rows'][0][15]}</div>";
					} else {
						$_POST["cidade"] = $cidade['rows'][0][1];
						$cidade = $cidade['rows'][0][1];
					}
				}
			}
		} else {
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>Informe um código Sankhya válido</div>";
		}
	} else if (isset($_POST['btnGetSankhyaCC'])) {

		if (isset($_POST['idSankhyaCC']) && $_POST['idSankhyaCC'] <> "") {
			$idSankhya_form = $_POST['idSankhya'];
			$idSankhyaCC_form = $_POST['idSankhyaCC'];
			$_POST['idSankhyaCC_form'] = $_POST['idSankhyaCC'];

			// Conta Corrente
			$ContaCorrenteSankhya = Sankhya::getContaCorrenteByParceiro($idSankhya_form, $idSankhyaCC_form);

			if ($ContaCorrenteSankhya['errorCode']) {
				$erro = 1;
				$msg  = "<div style='color:#FF0000'>Erro: {$ContaCorrenteSankhya['errorCode']}: {$ContaCorrenteSankhya['errorMessage']}</div>";
			} else if (Count($ContaCorrenteSankhya['rows']) == 0) {
				$erro = 1;
				$msg  = "<div style='color:#FF0000'>Nenhuma conta corrente foi encontrada no Sankhya para esta pessoa.</div>";
			} else {
				// Pega a primeira conta encontrada
				$_POST["agencia_form"]      = $ContaCorrenteSankhya['rows'][0][1] . $ContaCorrenteSankhya['rows'][0][2];
				$_POST["numero_conta_form"] =  $ContaCorrenteSankhya['rows'][0][3] . $ContaCorrenteSankhya['rows'][0][4];

				// corrente, poupanca, salario e aplicacao
				switch ($ContaCorrenteSankhya['rows'][0][5]) {
					case 1:
						$_POST["tipo_conta_form"] = 'corrente';
						break;
					case 2:
						$_POST["tipo_conta_form"] = 'poupanca';
						break;
					case 3:
						$_POST["tipo_conta_form"] = 'salario';
						break;
					case 4:
						$_POST["tipo_conta_form"] = 'aplicacao';
						break;
					default:
						$_POST["tipo_conta_form"] = 'corrente';
				}


				if (($ContaCorrenteSankhya['rows'][0][7]) <> "") {
					$_POST["chave_pix_form"] = $ContaCorrenteSankhya['rows'][0][7];

					switch ($ContaCorrenteSankhya['rows'][0][6]) {
						case 1:
							$_POST["tipo_chave_pix_form"] = 'cpf_cnp';
							break;
						case 2:
							$_POST["tipo_chave_pix_form"] = 'celular';
							break;
						case 3:
							$_POST["tipo_chave_pix_form"] = 'email';
							break;
						case 4:
							$_POST["tipo_chave_pix_form"] = 'aleatoria';
							break;
						default:
							$_POST["tipo_chave_pix_form"] = 'cpf_cnp';
					}
				}

				$banco = Sankhya::getIdBancoDeParaSankhya($ContaCorrenteSankhya['rows'][0][0]);
				$rowsCount = 0;

				if ($banco['errorCode']) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Erro: {$banco['errorCode']}: {$banco['errorMessage']}</div>";
				} else {
					$rowsCount = Count($banco['rows']);
					$_POST["banco_form"] = $banco['rows'][0][0];

					if ($rowsCount == 0) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Código de banco {$ContaCorrenteSankhya['rows'][0][0]}} não cadastrado no SUIF.</div>";
					} else if ($rowsCount > 1) {
						$erro = 1;
						$msg  = "<div style='color:#FF0000'>Existe mais de um banco de código {$ContaCorrenteSankhya['rows'][0][0]}} cadastrado no SUIF.</div>";
					}
				}
			}
		} else {
			$_POST["banco_form"] = '';
			$_POST["agencia_form"]      = '';
			$_POST["numero_conta_form"] = '';
			$_POST["tipo_conta_form"]   = '';
			$_POST["tipo_chave_pix_form"] = '';		
			$_POST["chave_pix_form"] = '';	
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>Informe um código Sankhya da Conta Corrente válido</div>";
		}
	}
}

$idSankhya_form = $_POST['idSankhya_form'] ?? '';
$idSankhya = $_POST['idSankhya_form'] ?? '';

$idSankhyaCC_form = $_POST['idSankhyaCC_form'] ?? '';
$idSankhyaCC = $_POST['idSankhyaCC_form'] ?? '';

$tipo_pessoa_form = $_POST['tipo_pessoa_form'] ?? '';
$nome_form = $_POST["nome_form"] ?? '';
$cpf_form = $_POST["cpf_form"] ?? '';
$cnpj_form = $_POST["cnpj_form"] ?? '';
$rg_form = $_POST["rg_form"] ?? '';
$data_nascimento_form = $_POST["data_nascimento_form"] ?? '';
$sexo_form = $_POST["sexo_form"] ?? '';
$favorecido_form = $_POST["favorecido_form"] ?? '';
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
$tipo_chave_pix_form = $_POST["tipo_chave_pix_form"] ?? '';
$chave_pix_form = $_POST["chave_pix_form"] ?? '';

// ====== MONTA MENSAGEM ===================================================================================
if ($permissao[5] != "S") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastro de pessoa</div>";
}
// ======================================================================================================

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

	function BuscaCC(action) {
		form = document.getElementById("frmCampos");
		form.setAttribute("action", action);
		form.submit();

		// Elementos que podem não ter sido criados
		elem = document.getElementById("btnSalvar");
		if (elem) {
			elem.disabled = false;
		}
	}

	// Função oculta DIV depois de alguns segundos
	setTimeout(function() {
		$('#oculta').fadeOut('fast');
	}, 2500); // 2,5 Segundos

	// Limpa campos do formulário quando o Id do Sankhya for alterado
	function LimpaForms() {
		document.getElementById("tipo_pessoa_form").value = "";
		document.getElementById("nome_form").value = "";
		document.getElementById("rg_form").value = "";
		document.getElementById("telefone_1_form").value = "";
		document.getElementById("telefone_2_form").value = "";
		document.getElementById("endereco_form").value = "";
		document.getElementById("numero_residencia_form").value = "";
		document.getElementById("bairro_form").value = "";
		document.getElementById("estado").value = "";
		document.getElementById("bairro_form").value = "";
		document.getElementById("cidade").value = "";
		document.getElementById("complemento_form").value = "";
		document.getElementById("cep").value = "";
		document.getElementById("email_form").value = "";
		document.getElementById("obs_form").value = "";
		document.getElementById("classificacao_1_form").value = "";
		document.getElementById("IdSankhyaCC").value = "";

		elem = document.getElementById("nome_fantasia_form");
		if (elem) {
			elem.value = '';
		}

		elem = document.getElementById("cpf_form");
		if (elem) {
			elem.value = '';
		}

		elem = document.getElementById("cnpj_form");
		if (elem) {
			elem.value = '';
		}

		elem = document.getElementById("sexo_form");
		if (elem) {
			elem.value = '';
		}

		// Elementos que podem não ter sido criados
		elem = document.getElementById("btnSalvar");
		if (elem) {
			elem.disabled = true;
		}

		elem = document.getElementById("btnGetSankhyaCC");
		if (elem) {
			elem.disabled = true;
		}

		LimpaFormsCC();
	}

	function LimpaFormsCC() {
		document.getElementById("banco_form").value = "";
		document.getElementById("agencia_form").value = "";
		document.getElementById("numero_conta_form").value = "";
		document.getElementById("tipo_conta_form").value = "";
		document.getElementById("tipo_chave_pix_form").value = "";
		document.getElementById("chave_pix_form").value = "";
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
		$("#telefone_1_form").maskbrphone({
			useDdd: true,
			useDddParenthesis: true,
			dddSeparator: ' ',
			numberSeparator: '-'
		});

		// TELEFONE COM DDD 2
		$("#telefone_2_form").maskbrphone({
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

			<!-- SANKHYA-->
			<div style="width:100%; height:50px; border:1px solid transparent;  float:left">
				<div>
					<form name="frmSankhya" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php" method="post">

						<div class="pqa_caixa">
							<div class="pqa_rotulo" style="margin-left:0px;">
								ID Sankhya:
							</div>

							<div class="pqa_campo" style="margin-left:0px;">
								<input type="number" name="IdSankhya" class="pqa_input" id="IdSankhya" maxlength="8" onchange="LimpaForms()" style="width:145px;" value="<?php echo $idSankhya_form ?>" />
							</div>
						</div>

						<div class="pqa_caixa">
							<div class="pqa_rotulo">
							</div>

							<div class="pqa_campo" style="margin-left: 10px;">
								<button type='submit' id="btnGetSankhya" name="btnGetSankhya" style='float:left'>Buscar</button>
							</div>
						</div>

					</form>
				</div>
			</div>
			<!-- FIM SANKHYA-->

			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Pessoa:
				</div>
				<div style="background-color:#EEE; width:154px; height:25px; float:left; border:1px solid transparent">

					<form id="frmTipoPessoa" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php" method="post">
						<input readonly type="hidden" name="botao" value="TIPO_PESSOA" />

						<select readonly="readonly" id="tipo_pessoa_form" name="tipo_pessoa_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" onchange="document.tipo_pessoa.submit()" style="background-color:#EEE; width:154px">
							<option></option>
							<?php
							if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
								echo "<option selected='selected' value='PF'>Pessoa F&iacute;sica</option>";
							} else {
								echo "<option value='PF'>Pessoa F&iacute;sica</option>";
							}

							if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
								echo "<option selected='selected' value='PJ'>Pessoa Jur&iacute;dica</option>";
							} else {
								echo "<option value='PJ'>Pessoa Jur&iacute;dica</option>";
							}
							?>
						</select>
					</form>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<form id="frmCampos" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_2_enviar.php" method="post">
				<input type="hidden" name="botao" value="NOVO_CADASTRO" />
				<input type="hidden" name="tipo_pessoa_form" value="<?= $tipo_pessoa_form ?>" />
				<input type="hidden" name="idSankhya_form" value="<?= $idSankhya_form ?>" />
				<input type="hidden" name="banco_form" value="<?= $banco_form ?>" />
				<input type="hidden" name="agencia_form" value="<?=$agencia_form?>" />
				<input type="hidden" name="numero_conta_form" value="<?= $numero_conta_form ?>" />
				<input type="hidden" name="tipo_conta_form" value="<?= $tipo_conta_form ?>" />
				<input type="hidden" name="tipo_chave_pix_form" value="<?= $tipo_chave_pix_form ?>" />
				<input type="hidden" name="chave_pix_form" value="<?= $chave_pix_form ?>" />
				<input type="hidden" name="idSankhyaCC_form" value="<?= $idSankhyaCC_form ?>" />
				<input type='hidden' name='estado' value="<?= $estado ?>" />
				<input type='hidden' name='cidade' value="<?= $cidade ?>" />
				<input type='hidden' name='favorecido_form' value="<?= $favorecido_form ?>" />

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
						if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "PJ") {
							echo "
								<input readonly type='text' name='nome_form' class='form_input' id='nome_form' maxlength='70' onBlur='alteraMaiusculo(this)' 
								onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:486px; text-align:left; padding-left:5px' value='$nome_form' />";
						} else {
							echo "
								<input readonly type='text' name='nome_form' id='nome_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
								onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:486px; text-align:left; padding-left:5px' 
								readonly='readonly' title='Selecione o tipo de pessoa' value='$nome_form' />";
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
							echo "<input readonly type='text' name='cnpj_form' id='cnpj_form' class='form_input' maxlength='18' id='cnpj' onkeypress='mascara(this,num_cnpj)' onBlur='mascara(this,num_cnpj)'
					        onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:145px; text-align:left; padding-left:5px' value='$cnpj_form' />";
						} else {
							echo "<input readonly type='text' name='cpf_form' id='cpf_form'  class='form_input' maxlength='14' id='cpf' onkeypress='mascara(this,num_cpf)' onBlur='mascara(this,num_cpf)' 
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
						<input readonly type="text" name="rg_form" id="rg_form" class="form_input" maxlength="20" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$rg_form"; ?>" />
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
							onkeydown='if (getKey(event) == 13) return false;' style='background-color:#EEE; width:145px; text-align:left; padding-left:5px' value='$data_nascimento_form' />
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
							<select readonly='readonly' name='sexo_form' id='sexo_form' class='form_select' onkeydown='if (getKey(event) == 13) return false;' style='width:154px' />";
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
						<input readonly type='text' name='nome_fantasia_form' id='nome_fantasia_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
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
						<input readonly type="text" name="telefone_1_form" class="form_input" id="telefone_1_form" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$telefone_1_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= TELEFONE 2 ============================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Telefone 2:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="telefone_2_form" class="form_input" id="telefone_2_form" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$telefone_2_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- =======  ENDEREÇO ============================================================================================== -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Endere&ccedil;o:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="endereco_form" id="endereco_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$endereco_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= NUMERO ================================================================================================= -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						N&uacute;mero:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="numero_residencia_form" id="numero_residencia_form" class="form_input" maxlength="10" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$numero_residencia_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= BAIRRO ==================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Bairro/Distrito:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="bairro_form" id="bairro_form" class="form_input" maxlength="40" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$bairro_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->

				<!-- ======= ESTADO ==================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Estado:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="estado" id="estado" class="form_input" maxlength="40" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$estado"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->

				<!-- ======= CIDADE ==================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Cidade:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="cidade" id="cidade" class="form_input" maxlength="40" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?php echo "$cidade"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= COMPLEMENTO ============================================================================================ -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Complemento:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="complemento_form" id="complemento_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$complemento_form"; ?>" />
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
						<input readonly type="text" name="email_form" id="email_form" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$email_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= OBSERVAÇÃO ============================================================================================= -->
				<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
						Observa&ccedil;&atilde;o:
					</div>

					<div style="width:334px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="obs_form" id="obs_form" class="form_input" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:315px; text-align:left; padding-left:5px" value="<?php echo "$obs_form"; ?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CLASSIFICACAO ========================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Classificação:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly="readonly" name="classificacao_1_form" id="classificacao_1_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px">
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
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style="width:1020px; height:10px; float:left"></div>


				<div style="width:1020px; height:16px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:15px; border:1px solid transparent; float:left">
						<b>Dados Banc&aacute;rios:</b>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- SANKHYA - Dados da Conta Corrente -->
				<div style="width:100%; height:50px; border:1px solid transparent;  float:left">
					<div>
						<form name="frmSankhyaCC" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php" method="post">

							<input type="hidden" name="idSankhya" value="<?php echo "$idSankhya_form"; ?>" />

							<div class="pqa_caixa">
								<div class="pqa_rotulo" style="margin-left:0px;">
									Código da CC Sankhya:
								</div>

								<div class="pqa_campo" style="margin-left:0px;">
									<input type="number" name="idSankhyaCC" class="pqa_input" id="IdSankhyaCC" maxlength="8" onchange="LimpaFormsCC()" style="width:145px;" value=<?= $idSankhyaCC_form ?> />
								</div>
							</div>

							<div class="pqa_caixa">
								<div class="pqa_rotulo">
								</div>

								<div class="pqa_campo" style="margin-left: 10px;">
									<button type='submit' id="btnGetSankhyaCC" name="btnGetSankhyaCC" style='float:left' onclick="<?php echo "BuscaCC('$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_1_formulario.php')"; ?>">Buscar CC</button>
								</div>
							</div>



						</form>
					</div>
				</div>
				<!-- FIM SANKHYA-->


				<!-- ======= BANCO ================================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Banco:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly="readonly" name="banco_form" id="banco_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px">
							<option></option>
							<?php
							$busca_banco = mysqli_query($conexao, "SELECT * FROM cadastro_banco ORDER BY preferencia, apelido");
							$linhas_banco = mysqli_num_rows($busca_banco);

							for ($j = 1; $j <= $linhas_banco; $j++) {
								$aux_banco = mysqli_fetch_row($busca_banco);

								if ($aux_banco[2] == $banco_form) {
									echo "<option selected='selected' value='$aux_banco[2]'>$aux_banco[3] ($aux_banco[2])</option>";
								} else {
									echo "<option value='$aux_banco[2]'>$aux_banco[3] ($aux_banco[2])</option>";
								}
							}
							?>
						</select>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= AGÊNCIA ================================================================================================ -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Ag&ecirc;ncia:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="agencia_form" id="agencia_form" class="form_input" maxlength="8" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?=$agencia_form?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= Nº DA CONTA ============================================================================================ -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						N&ordm; da Conta:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="numero_conta_form" id="numero_conta_form" class="form_input" maxlength="13" id="conta_bancaria" onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?=$numero_conta_form?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= TIPO DE CONTA ========================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Tipo de Conta:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly="readonly" name="tipo_conta_form" id="tipo_conta_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px">
							<option></option>
							<?php
							if ($tipo_conta_form == "corrente") {
								echo "<option value='corrente' selected='selected'>Conta Corrente</option>";
							} else {
								echo "<option value='corrente'>Conta Corrente</option>";
							}

							if ($tipo_conta_form == "poupanca") {
								echo "<option value='poupanca' selected='selected'>Conta Poupan&ccedil;a</option>";
							} else {
								echo "<option value='poupanca'>Conta Poupan&ccedil;a</option>";
							}

							if ($tipo_conta_form == "salario") {
								echo "<option value='salario' selected='selected'>Conta Sal&aacute;rio</option>";
							} else {
								echo "<option value='salario'>Conta Sal&aacute;rio</option>";
							}

							if ($tipo_conta_form == "aplicacao") {
								echo "<option value='aplicacao' selected='selected'>Conta Aplica&ccedil;&atilde;o</option>";
							} else {
								echo "<option value='aplicacao'>Conta Aplica&ccedil;&atilde;o</option>";
							}
							?>
						</select>
					</div>
				</div>
				<!-- ================================================================================================================ -->



				<!-- ======= TIPO DE CHAVE PIX ====================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Tipo de Chave Pix:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<select readonly="readonly" name="tipo_chave_pix_form" id="tipo_chave_pix_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:154px">
							<option></option>
							<?php
							if ($tipo_chave_pix_form == "cpf_cnpj") {
								echo "<option value='cpf_cnpj' selected='selected'>CPF/CNPJ</option>";
							} else {
								echo "<option value='cpf_cnpj'>CPF/CNPJ</option>";
							}

							if ($tipo_chave_pix_form == "celular") {
								echo "<option value='celular' selected='selected'>Celular</option>";
							} else {
								echo "<option value='celular'>Celular</option>";
							}

							if ($tipo_chave_pix_form == "email") {
								echo "<option value='email' selected='selected'>E-mail</option>";
							} else {
								echo "<option value='email'>E-mail</option>";
							}

							if ($tipo_chave_pix_form == "aleatoria") {
								echo "<option value='aleatoria' selected='selected'>Chave Aleat&oacute;ria</option>";
							} else {
								echo "<option value='aleatoria'>Chave Aleat&oacute;ria</option>";
							}
							?>
						</select>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= CHAVE PIX ============================================================================================== -->
				<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
					<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
						Pix:
					</div>

					<div style="width:167px; height:25px; float:left; border:1px solid transparent">
						<input readonly type="text" name="chave_pix_form" id="chave_pix_form" class="form_input" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:145px; text-align:left; padding-left:5px" value="<?=$chave_pix_form?>" />
					</div>
				</div>
				<!-- ================================================================================================================ -->

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
					<form action='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php' method='post'>
					<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
					</form>
					</div>";
			} else {
				echo "
					<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

					<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
					<button type='submit' class='botao_2' id='btnSalvar' style='margin-left:10px; width:180px'
					onclick=BuscaCC('$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_2_enviar.php')
					>Salvar</button>


					</form>
					</div>

					<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
					<a href='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php'>
					<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>

					</a>
					</div>";
			}

			$erro = 0;
			$msg = "";
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

</body>


</html>