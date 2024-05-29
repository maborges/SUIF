<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
include("../../includes/desconecta_bd.php");
require_once("../../sankhya/Sankhya.php");
require_once("../../sankhya/SankhyaKeys.php");


$pagina = "editar_1_formulario";
$titulo = "Editar Cadastro de Favorecido";
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
// ================================================================================================================

// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"] ?? '';
$nome_form = $_POST["nome_form"] ?? '';
$nome_busca = $_POST["nome_busca"];
$status_busca = $_POST["status_busca"];
$id_w = $_POST["id_w"];
$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$erro = 0;
$msg = "";
$leBase = true;

// ========================================================================================================

// Trata botão de pesquisa da conta corrente
// Verifica se o formulário foi enviado (se o método de requisição é POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica qual botão foi clicado (se o nome do botão está presente nos dados POST)
    if (isset($_POST["btnGetSankhyaCC"])) {
		$idSankhya_form = $_POST['idSankhya_form'];
		$idSankhyaCC_form = $_POST['idSankhyaCC_form'];

		// Conta Corrente
		$ContaCorrenteSankhya = Sankhya::getContaCorrenteByParceiro($idSankhya_form, $idSankhyaCC_form);

		if ($ContaCorrenteSankhya['errorCode']) {
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>Erro: {$ContaCorrenteSankhya['errorCode']}: {$ContaCorrenteSankhya['errorMessage']}</div>";
		} else if (Count($ContaCorrenteSankhya['rows']) == 0) {
			$erro = 1;
			$msg  = "<div style='color:#FF0000'>Conta corrente $idSankhyaCC_form não foi encontrada no Sankhya para este favorecido.</div>";
		} else {
			// Pega a primeira conta encontrada
			$_POST["agencia_form"]        = $ContaCorrenteSankhya['rows'][0][1] . $ContaCorrenteSankhya['rows'][0][2];
			$_POST["numero_conta_form"]   =  $ContaCorrenteSankhya['rows'][0][3] . $ContaCorrenteSankhya['rows'][0][4];
			$_POST["conta_conjunta_form"] = 'NAO';
			

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
					$msg  = "<div style='color:#FF0000'>Código de banco {$ContaCorrenteSankhya['rows'][0][0]} não cadastrado no SUIF.</div>";
				} else if ($rowsCount > 1) {
					$erro = 1;
					$msg  = "<div style='color:#FF0000'>Existe mais de um banco de código {$ContaCorrenteSankhya['rows'][0][0]}} cadastrado no SUIF.</div>";
				}
			}

		}
    }
}


if ($botao == "EDITAR") {
	// ====== BUSCA CADASTRO ==========================================================================================
	include("../../includes/conecta_bd.php");
	$busca_favorecido = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$id_w'");
	include("../../includes/desconecta_bd.php");
	$linha_favorecido = mysqli_num_rows($busca_favorecido);
	// ================================================================================================================


	// ====== FUNÇÃO FOR ==============================================================================================
	for ($x = 1; $x <= $linha_favorecido; $x++) {
		$aux_favorecido = mysqli_fetch_row($busca_favorecido);
	}
	// ================================================================================================================

	// ====== DADOS DO CADASTRO ============================================================================
	$id_w = $aux_favorecido[0];
	$codigo_pessoa_w = $aux_favorecido[1];
	$banco_form = $aux_favorecido[2];
	$agencia_form = $aux_favorecido[3];
	$numero_conta_form = $aux_favorecido[4];
	$tipo_conta_form = $aux_favorecido[5];
	$obs_form = $aux_favorecido[12];
	$estado_registro_w = $aux_favorecido[13];
	$nome_w = $aux_favorecido[14];
	$conta_conjunta_form = $aux_favorecido[15];
	$tipo_chave_pix_form = $aux_favorecido[19];
	$chave_pix_form = $aux_favorecido[20];
	$idSankhya_form = $aux_favorecido[23];
	$idSankhyaCC_form = $aux_favorecido[25];

	if ($conta_conjunta_form == "SIM" and $agencia_form != "") {
		$conta_conjunta_print = "SIM";
	} elseif ($conta_conjunta_form != "SIM" and $agencia_form != "") {
		$conta_conjunta_print = "N&Atilde;O";
	} else {
		$conta_conjunta_print = "";
	}

	$usuario_cadastro_w = $aux_favorecido[6];
	if ($usuario_cadastro_w == "") {
		$dados_cadastro_w = "";
	} else {
		$data_cadastro_w = date('d/m/Y', strtotime($aux_favorecido[8]));
		$hora_cadastro_w = $aux_favorecido[7];
		$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
	}

	$usuario_alteracao_w = $aux_favorecido[9];
	if ($usuario_alteracao_w == "") {
		$dados_alteracao_w = "";
	} else {
		$data_alteracao_w = date('d/m/Y', strtotime($aux_favorecido[11]));
		$hora_alteracao_w = $aux_favorecido[10];
		$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
	}

	$usuario_exclusao_w = $aux_favorecido[16];
	if ($usuario_exclusao_w == "") {
		$dados_exclusao_w = "";
	} else {
		$data_exclusao_w = date('d/m/Y', strtotime($aux_favorecido[17]));
		$hora_exclusao_w = $aux_favorecido[18];
		$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
	}
	// ======================================================================================================
} else {
	// ======================================================================================================
	$banco_form = $_POST["banco_form"];
	$agencia_form = $_POST["agencia_form"];
	$numero_conta_form = $_POST["numero_conta_form"];
	$tipo_conta_form = $_POST["tipo_conta_form"];
	$conta_conjunta_form = $_POST["conta_conjunta_form"];
	$obs_form = $_POST["obs_form"] ?? '';
	$tipo_chave_pix_form = $_POST["tipo_chave_pix_form"] ?? '';
	$chave_pix_form = $_POST["chave_pix_form"] ?? '';
	// ======================================================================================================
}



// ====== BUSCA PESSOA ===================================================================================
include("../../includes/conecta_bd.php");
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$codigo_pessoa_w'");
include("../../includes/desconecta_bd.php");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linha_pessoa = mysqli_num_rows($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];
// ======================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf") {
	$tipo_pessoa_print = "PESSOA F&Iacute;SICA";
} elseif ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj") {
	$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";
} else {
	$tipo_pessoa_print = "";
}
// ================================================================================================================


// ====== BUSCA BANCO ===================================================================================
include("../../includes/conecta_bd.php");
$busca_banco = mysqli_query($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_form'");
include("../../includes/desconecta_bd.php");
$aux_banco = mysqli_fetch_row($busca_banco);

$apelido_banco = $aux_banco[3];
$logomarca_banco = $aux_banco[4];

if (empty($logomarca_banco) and $banco_form != "") {
	$logo_banco = "
		<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid #999; color:#999; text-align:center'>
		<div style='margin-top:15px; margin-left:0px; width:297px; height:40px; border:1px solid transparent; font-size:16px; text-align:center'>
		<i>$apelido_banco</i></div></div>";
} elseif ($logomarca_banco != "") {
	$logo_banco = "
		<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid transparent; color:#999; text-align:center'>
		<img src='$servidor/$diretorio_servidor/imagens/$logomarca_banco' style='height:68px' /></div>";
} else {
	$logo_banco = "
		<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid #999; color:#999; text-align:center'>
		<div style='margin-top:15px; margin-left:0px; width:297px; height:40px; border:1px solid transparent; font-size:16px; text-align:center'>
		<i>Selecione um banco</i></div></div>";
}
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($permissao[5] != "S") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar favorecido</div>";
} else if (!$idSankhya_form) {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Código Sankhya precisa estar informado.</div>";
}
// ======================================================================================================


// =============================================================================
include("../../includes/head.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?= "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include("../../includes/javascript.php"); ?>
</script>

<script>
	// Função oculta DIV depois de alguns segundos
	setTimeout(function() {
		$('#oculta').fadeOut('fast');
	}, 2500); // 2,5 Segundos
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
		<?php include("../../includes/submenu_cadastro_favorecidos.php"); ?>
	</div>


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_fixo" style="height:560px">


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
				<!-- xxxxxxxxxxxxxxxxxxxxxx -->
			</div>
		</div>
		<!-- ============================================================================================================= -->

		<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
		<div style="width:1030px; height:400px; margin:auto; border:1px solid transparent">

		<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
		<div style="width:1030px; height:400px; margin:auto; border:1px solid transparent">

				<!-- SANKHYA-->
				<div style="width:100%; height:50px; border:1px solid transparent;  float:left">
					<div>

						<div class="pqa_caixa">
							<div class="pqa_rotulo" style="margin-left:0px;">
								ID Sankhya:
							</div>

							<div class="pqa_campo" style="margin-left:0px;">
								<input readonly type="number" name="idSankhya_form" class="pqa_input" id="idSankhya_form" maxlength="8" style="width:145px; background-color:#EEE" value="<?php echo $idSankhya_form ?>" />
							</div>
						</div>

					</div>
				</div>
				<!-- FIM SANKHYA-->



			<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
			<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
					<?php
					if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj") {
						echo "Raz&atilde;o Social:";
					} else {
						echo "Nome:";
					}
					?>
				</div>

				<div style="width:500px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
					<div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa</b></div></div>";
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CPF / CNPJ ============================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php
					if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj") {
						echo "CNPJ:";
					} else {
						echo "CPF:";
					}
					?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj") {
						echo "<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
						<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
						<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TELEFONE 1 ============================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Telefone:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo "$telefone_pessoa" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- =======  TIPO PESSOA ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Pessoa:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
					<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- SANKHYA - Dados da Conta Corrente -->
			<form action="#" method="post" id="frmBuscaCC">
				<input type="hidden" name="botao" value="" />
				<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
				<input type="hidden" name="fornecedor_form" value="<?php echo "$fornecedor_form"; ?>" />
				<input type="hidden" name="nome_form" value="<?php echo "$nome_form"; ?>" />
				<input type="hidden" name="nome_busca" value="<?php echo "$nome_busca"; ?>" />
				<input type="hidden" name="status_busca" value="<?php echo "$status_busca"; ?>" />
				<input type="hidden" name="id_w" value="<?php echo "$id_w"; ?>" />
				<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />
				<input type="hidden" name="idSankhya_form" value="<?php echo "$idSankhya_form"; ?>" />
				
				
				<div style="width:100%; height:50px; border:1px solid transparent;  float:left; display: flex; justify-content: flex-start">
					<div>
						<div class="pqa_caixa">
							<div class="pqa_rotulo" style="margin-left:0px;">
								Código da CC Sankhya:
							</div>

							<div class="pqa_campo" style="margin-left:0px;">
								<input <?php 
											if (!$idSankhya_form) {echo 'readonly';}
								       ?> 
									   type="number" name="idSankhyaCC_form" class="pqa_input" id="idSankhyaCC_form" maxlength="8" value="<?= $idSankhyaCC_form ?>" 
									   style="width:145px; <?php 
									   						if (!$idSankhya_form) {echo 'background-color:#EEE;';}
														  ?> ">
							</div>
						</div>

						<div class="pqa_caixa">
							<div class="pqa_rotulo">
							</div>

							<div class="pqa_campo" style="margin-left: 10px;">
								<button <?php if (!$idSankhya_form) {echo 'hidden';}?>
								type='submit' name="btnGetSankhyaCC" style='float:left' value="PESQUISACC"
								formaction="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/favorecidos/editar_1_formulario.php">
								Buscar CC</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!-- FIM SANKHYA-->


			<!-- =======  BANCO ============================================================================================== -->
			<div style="width:339px; height:200px; border:1px solid transparent; margin-top:10px; float:left; text-align:center">
				<?php echo "$logo_banco" ?>
			</div>
			<!-- ================================================================================================================ -->



			<!-- ======= BANCO ================================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Banco:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<form name="banco" action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/favorecidos/editar_1_formulario.php" method="post">
					<input type="hidden" name="botao" value="BANCO" />
					<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
					<input type="hidden" name="nome_form" value="<?php echo "$nome_form"; ?>" />
					<input type="hidden" name="nome_busca" value="<?php echo "$nome_busca"; ?>" />
					<input type="hidden" name="status_busca" value="<?php echo "$status_busca"; ?>" />
					<input type="hidden" name="fornecedor_form" value="<?php echo "$fornecedor_form"; ?>" />
					<input type="hidden" name="agencia_form" value="<?php echo "$agencia_form"; ?>" />
					<input type="hidden" name="numero_conta_form" value="<?php echo "$numero_conta_form"; ?>" />
					<input type="hidden" name="tipo_conta_form" value="<?php echo "$tipo_conta_form"; ?>" />
					<input type="hidden" name="conta_conjunta_form" value="<?php echo "$conta_conjunta_form"; ?>" />
					<input type="hidden" name="obs_form" value="<?php echo "$obs_form"; ?>" />
					<input type="hidden" name="id_w" value="<?php echo "$id_w"; ?>" />
					<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />
					<input type="hidden" name="tipo_chave_pix_form" value="<?php echo "$tipo_chave_pix_form"; ?>" />
					<input type="hidden" name="chave_pix_form" value="<?php echo "$chave_pix_form"; ?>" />

					<select readonly name="banco_form" class="form_select" onchange="document.banco.submit()" style="width:154px; background-color:#EEE">
						<option></option>
						<?php
						include("../../includes/conecta_bd.php");
						$busca_banco = mysqli_query($conexao, "SELECT * FROM cadastro_banco ORDER BY preferencia, apelido");
						include("../../includes/desconecta_bd.php");

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
					</form>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= AGÊNCIA ================================================================================================ -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/favorecidos/editar_2_enviar.php" method="post" />
				<input type="hidden" name="botao" value="EDITAR_CADASTRO" />
				<input type="hidden" name="nome_form" value="<?php echo "$nome_form"; ?>" />
				<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
				<input type="hidden" name="nome_busca" value="<?php echo "$nome_busca"; ?>" />
				<input type="hidden" name="status_busca" value="<?php echo "$status_busca"; ?>" />
				<input type="hidden" name="fornecedor_form" value="<?php echo "$fornecedor_form"; ?>" />
				<input type="hidden" name="banco_form" value="<?php echo "$banco_form"; ?>" />
				<input type="hidden" name="id_w" value="<?php echo "$id_w"; ?>" />
				<input type="hidden" name="codigo_pessoa_w" value="<?php echo "$codigo_pessoa_w"; ?>" />
				<input type="hidden" name="tipo_chave_pix_form" value="<?php echo "$tipo_chave_pix_form"; ?>" />
				<input type="hidden" name="chave_pix_form" value="<?php echo "$chave_pix_form"; ?>" />


				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Ag&ecirc;ncia:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<input readonly type='text' name='agencia_form' class='form_input' maxlength='8' onBlur='alteraMaiusculo(this)' 
						onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px; background-color:#EEE' value='$agencia_form' />";
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= Nº DA CONTA ============================================================================================ -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					N&ordm; da Conta:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<input readonly type='text' name='numero_conta_form' class='form_input' maxlength='20' id='conta_bancaria' onBlur='alteraMaiusculo(this)' 
					onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px; background-color:#EEE' value='$numero_conta_form' />";
					?>

				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TIPO DE CONTA ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Conta:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<select readonly name="tipo_conta_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; background-color:#EEE" />
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


			<!-- ======= CONTA CONJUNTA ========================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Conta Conjunta:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<select readonly name="conta_conjunta_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; background-color:#EEE" />
					<option></option>
					<?php
					if ($conta_conjunta_form == "SIM") {
						echo "<option value='SIM' selected='selected'>SIM</option>";
					} else {
						echo "<option value='SIM'>SIM</option>";
					}

					if ($conta_conjunta_form == "NAO") {
						echo "<option value='NAO' selected='selected'>N&Atilde;O</option>";
					} else {
						echo "<option value='NAO'>N&Atilde;O</option>";
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
					<select readonly name="tipo_chave_pix_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; background-color:#EEE" />
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
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					Pix:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<?php
					echo "<input readonly type='text' name='chave_pix_form' class='form_input' maxlength='50' 
						onkeydown='if (getKey(event) == 13) return false;' style='width:315px; text-align:left; padding-left:5px; background-color:#EEE' value='$chave_pix_form' />";
					?>

				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= OBSERVAÇÃO ============================================================================================= -->
			<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
					Observa&ccedil;&atilde;o:
				</div>

				<div style="width:500px; height:25px; float:left; border:1px solid transparent">
					<input readonly type="text" name="obs_form" class="form_input" maxlength="80" onkeydown="if (getKey(event) == 13) return false;" style="width:486px; text-align:left; padding-left:5px; background-color:#EEE" value="<?php echo "$obs_form"; ?>" />
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
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/$pagina_mae.php' method='post' />
					<input type='hidden' name='pagina_mae' value='$pagina_mae'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='VOLTAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
					</form>
					</div>";
			} else {
				echo "
					<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

					<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
					<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
					</form>
					</div>

					<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/$pagina_mae.php' method='post' />
					<input type='hidden' name='pagina_mae' value='$pagina_mae'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='VOLTAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
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