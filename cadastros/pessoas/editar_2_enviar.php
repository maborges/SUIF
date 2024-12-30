<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
include("../../sankhya/Sankhya.php");
include("../../helpers.php");

$pagina = "editar_2_enviar";
$titulo = "Editar Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$id_w = $_POST["id_w"];

$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$idSankhya_w = $_POST["idSankhya_w"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d');
$data_hoje_br = date('d/m/Y');
$filial = $filial_usuario;

$pesquisar_por_busca = $_POST["pesquisar_por_busca"];
$nome_busca = $_POST["nome_busca"];
$cpf_busca = $_POST["cpf_busca"];
$cnpj_busca = $_POST["cnpj_busca"];
$fantasia_busca = $_POST["fantasia_busca"];

$tipo_pessoa_form = $_POST["tipo_pessoa_form"];
$nome_form = $_POST["nome_form"];
$cpf_form = $_POST["cpf_form"] ?? '';
$cnpj_form = $_POST["cnpj_form"] ?? '';
$rg_form = $_POST["rg_form"];
$data_nascimento_form = $_POST["data_nascimento_form"] ?? '';
$sexo_form = $_POST["sexo_form"] ?? '';
$nome_fantasia_form = $_POST["nome_fantasia_form"] ?? '';
$telefone_1_form = $_POST["telefone_1_form"];
$telefone_2_form = $_POST["telefone_2_form"];
$endereco_form = $_POST["endereco_form"];
$numero_residencia_form = $_POST["numero_residencia_form"];
$bairro_form = $_POST["bairro_form"];
$estado = $_POST["estado"];
$cidade = $_POST["cidade"];
$complemento_form = $_POST["complemento_form"];
$cep_form = $_POST["cep_form"];
$email_form = $_POST["email_form"];
$obs_form = $_POST["obs_form"];
$classificacao_1_form = $_POST["classificacao_1_form"];

$cadastroRevisado = $_POST["cadastroRevisado"] ?? 'N';
$validadoSERASA   = $_POST["validadoSERASA"] ?? 'N';
$embargado        = $_POST["embargado"] ?? 'N';

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
// ================================================================================================================


// ======= ALTERA DATA ==========================================================================================
$data_nascimento_aux = Helpers::ConverteData($data_nascimento_form);

if ($data_nascimento_aux == "" or $data_nascimento_aux <= 1900 - 01 - 01) {
	$data_nascimento_aux = "1900-01-01";
}
// ================================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
	$tipo_pessoa_print = "PESSOA F&Iacute;SICA";
} elseif ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
	$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";
} else {
	$tipo_pessoa_print = "";
}
// ================================================================================================================


// ======= BUSCA ESTADO E CIDADE ==================================================================================
$busca_estado = mysqli_query($conexao, "SELECT * FROM cad_estados WHERE est_id='$estado'");
$aux_be = mysqli_fetch_row($busca_estado);
$estado_aux = $aux_be[1];

$busca_cidade = mysqli_query($conexao, "SELECT * FROM cad_cidades WHERE cid_id='$cidade'");
$aux_bc = mysqli_fetch_row($busca_cidade);
$cidade_aux = $aux_bc[1];
// ================================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_form'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================

// ======= BUSCA CPF E CNPJ =======================================================================================
$busca_cpf = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND cpf='$cpf_form' AND cpf!='' AND codigo_pessoa!='$codigo_pessoa_w'");
$achou_cpf = mysqli_num_rows($busca_cpf);

$busca_cnpj = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND cnpj='$cnpj_form' AND cnpj!='' AND codigo_pessoa!='$codigo_pessoa_w'");
$achou_cnpj = mysqli_num_rows($busca_cnpj);
// ================================================================================================================


// ======= VALIDADOR DE CPF =======================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf") {
	// limpar caracteres como ponto e traços (máscaras)
	$cpf_validar = str_replace("-", "", $cpf_form);
	$cpf_validar = str_replace(".", "", $cpf_validar);
	$cpf_aux = $cpf_validar;

	// verificar se a quantidade de caracteres está correta
	if (strlen($cpf_validar) != 11) {
		$valida_cpf = "erro";
	}

	// Verifica se nenhuma das sequências foi digitada
	elseif (
		$cpf_validar == '00000000000' or $cpf_validar == '11111111111'
		or $cpf_validar == '22222222222' or $cpf_validar == '33333333333'
		or $cpf_validar == '44444444444' or $cpf_validar == '55555555555'
		or $cpf_validar == '66666666666' or $cpf_validar == '77777777777'
		or $cpf_validar == '88888888888' or $cpf_validar == '99999999999'
	) {
		$valida_cpf = "erro";
	} else {
		// pegando apenas os digito a serem verificados
		$cod = substr($cpf_validar, 0, 9);

		// cálculando o primeiro dígito
		$soma = 0;
		$numero_calculo = 10;
		for ($i = 0; $i < 9; $i++) {
			$soma += ($cod[$i] * $numero_calculo--);
		}

		$resto = $soma % 11;
		if ($resto < 2) {
			$cod .= "0";
		} else {
			$cod .= (11 - $resto);
		}

		// calculando o segundo dígito
		$soma = 0;
		$numero_calculo = 11;
		for ($i = 0; $i < 10; $i++) {
			$soma += ($cod[$i] * $numero_calculo--);
		}
		$resto = $soma % 11;

		if ($resto < 2) {
			$cod .= "0";
		} else {
			$cod .= (11 - $resto);
		}

		// Se forem os mesmos é porque está correto
		if ($cod === $cpf_validar) {
			$valida_cpf = "ok";
		} else {
			$valida_cpf = "erro";
		}
	}
}
// ================================================================================================================


// ======= VALIDADOR DE CNPJ ======================================================================================
if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj") {
	// limpar caracteres como ponto e traços (máscaras)
	$cnpj_validar = str_replace("-", "", $cnpj_form);
	$cnpj_validar = str_replace(".", "", $cnpj_validar);
	$cnpj_validar = str_replace("/", "", $cnpj_validar);
	$cnpj_aux = $cnpj_validar;

	// verificar se a quantidade de caracteres está correta
	if (strlen($cnpj_validar) != 14) {
		$valida_cnpj = "erro";
	}

	// Verifica se nenhuma das sequências foi digitada
	elseif (
		$cnpj_validar == '00000000000000' or $cnpj_validar == '11111111111111' or
		$cnpj_validar == '22222222222222' or $cnpj_validar == '33333333333333' or
		$cnpj_validar == '44444444444444' or $cnpj_validar == '55555555555555' or
		$cnpj_validar == '66666666666666' or $cnpj_validar == '77777777777777' or
		$cnpj_validar == '88888888888888' or $cnpj_validar == '99999999999999'
	) {
		$valida_cnpj = "erro";
	} else {

		// Calcula os números para verificar se o CNPJ é verdadeiro
		function verificaCNPJ($cnpj_x)
		{
			if (strlen($cnpj_x) <> 18) return 0;
			$soma1 = ($cnpj_x[0] * 5) +

				($cnpj_x[1] * 4) +
				($cnpj_x[3] * 3) +
				($cnpj_x[4] * 2) +
				($cnpj_x[5] * 9) +
				($cnpj_x[7] * 8) +
				($cnpj_x[8] * 7) +
				($cnpj_x[9] * 6) +
				($cnpj_x[11] * 5) +
				($cnpj_x[12] * 4) +
				($cnpj_x[13] * 3) +
				($cnpj_x[14] * 2);
			$resto = $soma1 % 11;
			$digito1 = $resto < 2 ? 0 : 11 - $resto;
			$soma2 = ($cnpj_x[0] * 6) +

				($cnpj_x[1] * 5) +
				($cnpj_x[3] * 4) +
				($cnpj_x[4] * 3) +
				($cnpj_x[5] * 2) +
				($cnpj_x[7] * 9) +
				($cnpj_x[8] * 8) +
				($cnpj_x[9] * 7) +
				($cnpj_x[11] * 6) +
				($cnpj_x[12] * 5) +
				($cnpj_x[13] * 4) +
				($cnpj_x[14] * 3) +
				($cnpj_x[16] * 2);
			$resto = $soma2 % 11;
			$digito2 = $resto < 2 ? 0 : 11 - $resto;
			return (($cnpj_x[16] == $digito1) && ($cnpj_x[17] == $digito2));
		}

		if (!verificaCNPJ($cnpj_form)) {
			$valida_cnpj = "erro";
		} else {
			$valida_cnpj = "ok";
		}
	}
}
// ================================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "EDITAR_CADASTRO") {
	if ($tipo_pessoa_form == "") {
		$erro = 1;
		$msg = "<div style='color:#FF0000'>Selecione o tipo de pessoa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PF" and $nome_form == "") {
		$erro = 2;
		$msg = "<div style='color:#FF0000'>Digite o nome da pessoa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PJ" and $nome_form == "") {
		$erro = 3;
		$msg = "<div style='color:#FF0000'>Digite a raz&atilde;o social da empresa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PF" and $cpf_form == "") {
		$erro = 4;
		$msg = "<div style='color:#FF0000'>Informe o CPF da pessoa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PF" and $valida_cpf == "erro") {
		$erro = 5;
		$msg = "<div style='color:#FF0000'>CPF inv&aacute;lido.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PF" and $achou_cpf >= 1) {
		$erro = 6;
		$msg = "<div style='color:#FF0000'>CPF j&aacute; cadastrado.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PJ" and $cnpj_form == "") {
		$erro = 7;
		$msg = "<div style='color:#FF0000'>Informe o CNPJ da empresa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PJ" and $valida_cnpj == "erro") {
		$erro = 8;
		$msg = "<div style='color:#FF0000'>CNPJ inv&aacute;lido.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_pessoa_form == "PJ" and $achou_cnpj >= 1) {
		$erro = 9;
		$msg = "<div style='color:#FF0000'>CNPJ j&aacute; cadastrado.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($estado == "") {
		$erro = 10;
		$msg = "<div style='color:#FF0000'>Selecione o estado.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($cidade == "") {
		$erro = 11;
		$msg = "<div style='color:#FF0000'>Selecione a cidade.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($classificacao_1_form == "") {
		$erro = 12;
		$msg = "<div style='color:#FF0000'>Classifica&ccedil;&atilde;o &eacute; obrigat&oacute;rio para o cadastro.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($permissao[69] != "S") {
		$erro = 13;
		$msg = "<div style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar cadastro de pessoa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} else {
		$erro = 0;
		$msg = "";
		$msg_titulo = "<div style='color:#0000FF'>Cadastro Editado com Sucesso!</div>";


		// ====== TABELA CADASTRO_PESSOA ==========================================================================
		$editar = mysqli_query($conexao, "UPDATE cadastro_pessoa 
										  SET nome='$nome_form', 
										  	  cpf='$cpf_form', 
											  cnpj='$cnpj_form', 
											  rg='$rg_form', 
											  sexo='$sexo_form', 
											  data_nascimento='$data_nascimento_aux', 
											  endereco='$endereco_form', 
											  bairro='$bairro_form', 
											  cidade='$cidade_aux', 
											  cep='$cep_form', 
											  estado='$estado_aux', 
											  telefone_1='$telefone_1_form', 
											  telefone_2='$telefone_2_form', 
											  email='$email_form', 
											  classificacao_1='$classificacao_1_form', 
											  observacao='$obs_form', 
											  nome_fantasia='$nome_fantasia_form', 
											  numero_residencia='$numero_residencia_form', 
											  complemento='$complemento_form', 
											  usuario_alteracao='$usuario_alteracao', 
											  hora_alteracao='$hora_alteracao', 
											  data_alteracao='$data_alteracao', 
											  id_sankhya='$idSankhya_w', 
											  tipo='$tipo_pessoa_form',
											  validado_serasa='$validadoSERASA',
											  embargado ='$embargado',
											  cadastro_validado = '$cadastroRevisado'
										WHERE codigo='$id_w'");

		// ====== TABELA CADASTRO_FAVORECIDO ======================================================================
		$editar_favorecido = mysqli_query($conexao, "UPDATE cadastro_favorecido 
												     SET usuario_alteracao='$usuario_alteracao', 
														 hora_alteracao='$hora_alteracao', 
														 data_alteracao='$data_alteracao', 
														 nome='$nome_form', 
														 id_sankhya='$idSankhya_w' 
												   WHERE codigo_pessoa='$codigo_pessoa_w'");

		// ===== ATUALIZA INFORMAÇÕES NO SANKHYA =========================
		$result = Sankhya::atualizaSERASASankhya($idSankhya_w, $validadoSERASA, $embargado);

	}
}
// ======================================================================================================


// ====== BLOQUEIO PARA IMPRESSAO =================================================================================
if ($permissao[72] == "S") {
	$permite_imprimir = "SIM";
} else {
	$permite_imprimir = "NAO";
}
// ================================================================================================================


$badgeRevisado = 'badge-success';
$cadastroRevisado_texto = '';

if ($cadastroRevisado == 'S') {
	$cadastroRevisado_texto = 'Cadastro Revisado';
} else {
	$cadastroRevisado_texto = 'Cadastro Não Revisado';
	$badgeRevisado = 'badge-warning';
}

$badgeValidadoSERASA = 'badge-success';
$validadoSERASA_texto = '';

if ($validadoSERASA == 'S') {
	$validadoSERASA_texto = 'SERASA Validado';
} else {
	$validadoSERASA_texto = 'SERASA Não Validado';
	$badgeValidadoSERASA  = 'badge-warning';
}

$badgeembargado = 'badge-success';
$embargado_texto = '';

if ($embargado == 'S') {
	$embargado_texto = 'Bloqueado no SERASA';
	$badgeEmbargado  = 'badge-danger';
}



// ======================================================================================================
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
	<div class="ct_fixo" style="height:560px">


		<!-- ============================================================================================================= -->
		<div class="espacamento_15"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1">
			<div class="ct_titulo_1">
				<?php echo "$msg_titulo"; ?>
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
		<div style="width:1030px; height:370px; margin:auto; border:1px solid transparent; color:#003466">

			<!-- Avisos de situaçao -->
			<div style="width:335px; height:30px; border:1px solid transparent; margin-top:10px; float:left">
				<div style="border:0px solid #000; font-size:12px; float:left; align-items:center;">
					<div class="badge <?= $badgeRevisado ?>" style='font-size:110%'><?= $cadastroRevisado_texto ?></div>
				</div>
			</div>

			<div style="width:335px; height:30px; border:1px solid transparent; margin-top:10px; float:left">
				<div style="border:0px solid #000; font-size:12px; align-items:center;">
					<span class="badge <?= $badgeValidadoSERASA ?>" style='font-size:110%'><?= $validadoSERASA_texto ?></span>
				</div>
			</div>

			<div style="width:335px; height:30px; border:1px solid transparent; margin-top:10px; float:left">
				<div style="border:0px solid #000; font-size:12px; float:left; align-items:right;">
					<?php if ($embargado_texto) : ?>
						<span class="badge <?= $badgeEmbargado ?>" style='font-size:110%'><?= $embargado_texto ?></span>
					<?php endif; ?>
				</div>
			</div>


			<!-- =======  TIPO PESSOA ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Pessoa:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 1) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_pessoa_print</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
			<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
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
					if ($erro == 2 or $erro == 3) {
						echo "<div style='width:495px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:485px; height:16px; text-align:left; overflow:hidden'>$nome_form</div></div>";
					} else {
						echo "<div style='width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:485px; height:16px; text-align:left; overflow:hidden'>$nome_form</div></div>";
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
						if ($erro == 7 or $erro == 8 or $erro == 9) {
							echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cnpj_form</div></div>";
						} else {
							echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cnpj_form</div></div>";
						}
					} else {
						if ($erro == 4 or $erro == 5 or $erro == 6) {
							echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cpf_form</div></div>";
						} else {
							echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cpf_form</div></div>";
						}
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
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$rg_form" ?></div>
					</div>
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
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$data_nascimento_form</div></div>
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
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$sexo_form</div></div>
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
        <div style='width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden'>$nome_fantasia_form</div></div>
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
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$telefone_1_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TELEFONE 2 ============================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Telefone 2:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$telefone_2_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- =======  ENDEREÇO ============================================================================================== -->
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					Endere&ccedil;o:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo "$endereco_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= NUMERO ================================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					N&uacute;mero:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$numero_residencia_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= BAIRRO ==================================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Bairro/Distrito:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$bairro_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= ESTADO =================================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Estado:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 10) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$estado_aux</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$estado_aux</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CIDADE =================================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Cidade:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 11) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cidade_aux</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cidade_aux</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- =======  COMPLEMENTO ============================================================================================== -->
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					Complemento:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo "$complemento_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CEP ==================================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					CEP:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo "$cep_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= E-MAIL ================================================================================================= -->
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					E-mail:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo "$email_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= OBSERVAÇÃO ============================================================================================= -->
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					Observa&ccedil;&atilde;o:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo "$obs_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CLASSIFICACAO ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Classifica&ccedil;&atilde;o:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 12) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$classificacao_print</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$classificacao_print</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->













		</div>
		<!-- ===========  FIM DO FORMULÁRIO =========== -->





		<!-- ============================================================================================================= -->
		<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
			<?php
			if ($erro == 0) {
				echo "
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>";


				// ====== BOTAO VOLTAR =========================================================================================================
				echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
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
				// =============================================================================================================================

				// ====== BOTAO IMPRIMIR =======================================================================================================
				if ($permite_imprimir == "SIM") {
					echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_impressao.php' method='post' target='_blank'>
	<input type='hidden' name='id_w' value='$id_w'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir</button>
	</form>
</div>";
				} else {
					echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir</button>
	</div>";
				}
				// =============================================================================================================================
			} else {
				// ====== BOTAO VOLTAR =========================================================================================================
				echo "
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/cadastros/pessoas/editar_1_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>	
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<input type='hidden' name='tipo_pessoa_form' value='$tipo_pessoa_form' />
	<input type='hidden' name='nome_form' value='$nome_form' />
	<input type='hidden' name='cpf_form' value='$cpf_form' />
	<input type='hidden' name='cnpj_form' value='$cnpj_form' />
	<input type='hidden' name='rg_form' value='$rg_form' />
	<input type='hidden' name='data_nascimento_form' value='$data_nascimento_form' />
	<input type='hidden' name='sexo_form' value='$sexo_form' />
	<input type='hidden' name='nome_fantasia_form' value='$nome_fantasia_form' />
	<input type='hidden' name='telefone_1_form' value='$telefone_1_form' />
	<input type='hidden' name='telefone_2_form' value='$telefone_2_form' />
	<input type='hidden' name='endereco_form' value='$endereco_form' />
	<input type='hidden' name='numero_residencia_form' value='$numero_residencia_form' />
	<input type='hidden' name='bairro_form' value='$bairro_form' />
	<input type='hidden' name='estado' value='$estado_aux' />
	<input type='hidden' name='cidade' value='$cidade_aux' />
	<input type='hidden' name='complemento_form' value='$complemento_form' />
	<input type='hidden' name='cep_form' value='$cep_form' />
	<input type='hidden' name='email_form' value='$email_form' />
	<input type='hidden' name='obs_form' value='$obs_form'>
	<input type='hidden' name='classificacao_1_form' value='$classificacao_1_form'>
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
				// =============================================================================================================================
			}

			?>
		</div>








	</div>
	<!-- ====== FIM DIV CT_1 ========================================================================================= -->



	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>