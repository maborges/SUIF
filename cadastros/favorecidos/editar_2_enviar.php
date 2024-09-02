<?php
include("../../includes/config.php");
include("../../includes/valida_cookies.php");
$pagina = "editar_2_enviar";
$titulo = "Editar Cadastro de Favorecido";
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
// ================================================================================================================

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$id_w = $_POST["id_w"];
$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$nome_form = $_POST["nome_form"];
$fornecedor_form = $_POST["fornecedor_form"];
$nome_busca = $_POST["nome_busca"];
$status_busca = $_POST["status_busca"];
$banco_form = $_POST["banco_form"];
$agencia_form = $_POST["agencia_form"];
$numero_conta_form = $_POST["numero_conta_form"];
$tipo_conta_form = $_POST["tipo_conta_form"];
$conta_conjunta_form = $_POST["conta_conjunta_form"];
$obs_form = $_POST["obs_form"];
$tipo_chave_pix_form = $_POST["tipo_chave_pix_form"];
$chave_pix_form = $_POST["chave_pix_form"];
$idSankhyaCC_form = $_POST["idSankhyaCC_form"];

if ($conta_conjunta_form == "") {
	$conta_conjunta_aux = "NAO";
} else {
	$conta_conjunta_aux = $_POST["conta_conjunta_form"];
}

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y-m-d', time());
// ================================================================================================================


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
	$cpf_cnpj = $cpf_pessoa;
} elseif ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj") {
	$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";
	$cpf_cnpj = $cnpj_pessoa;
} else {
	$tipo_pessoa_print = "";
}
// ================================================================================================================


// ======= TIPO DE CONTA ==========================================================================================
if ($tipo_conta_form == "corrente") {
	$tipo_conta_print = "Conta Corrente";
} elseif ($tipo_conta_form == "poupanca") {
	$tipo_conta_print = "Conta Poupan&ccedil;a";
} elseif ($tipo_conta_form == "salario") {
	$tipo_conta_print = "Conta Sal&aacute;rio";
} elseif ($tipo_conta_form == "aplicacao") {
	$tipo_conta_print = "Conta Aplica&ccedil;&atilde;o";
} else {
	$tipo_conta_print = "";
}
// ================================================================================================================


// ======= TIPO DE CHAVE PIX ======================================================================================
if ($tipo_chave_pix_form == "cpf_cnpj") {
	$tipo_chave_pix_print = "CPF/CNPJ";
} elseif ($tipo_chave_pix_form == "celular") {
	$tipo_chave_pix_print = "Celular";
} elseif ($tipo_chave_pix_form == "email") {
	$tipo_chave_pix_print = "E-mail";
} elseif ($tipo_chave_pix_form == "aleatoria") {
	$tipo_chave_pix_print = "Chave Aleat&oacute;ria";
} else {
	$tipo_chave_pix_print = "";
}
// ================================================================================================================


// ====== BUSCA BANCO ===================================================================================
include("../../includes/conecta_bd.php");
$busca_banco = mysqli_query($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_form'");
include("../../includes/desconecta_bd.php");

$linha_banco = mysqli_num_rows($busca_banco);
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
<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid #FF0000; color:#999; text-align:center'>
<div style='margin-top:15px; margin-left:0px; width:297px; height:40px; border:1px solid transparent; font-size:16px; text-align:center'>
</div></div>";
}
// ======================================================================================================


// ====== BUSCA PERMISSÕES DE USUÁRIOS ===========================================================================
include("../../includes/conecta_bd.php");
$busca_permissao = mysqli_query($conexao, "SELECT * FROM usuarios_permissoes WHERE username='$username'");
include("../../includes/desconecta_bd.php");

$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================


// ====== BLOQUEIO PARA EDITAR ====================================================================================
if ($permissao[69] == "S") {
	$permite_editar = "SIM";
} else {
	$permite_editar = "NAO";
}
// ================================================================================================================

// ====== BLOQUEIO PARA NOVO CADASTRO =============================================================================
if ($permissao[5] == "S") {
	$permite_novo = "SIM";
} else {
	$permite_novo = "NAO";
}
// ================================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "EDITAR_CADASTRO") {
	if ($codigo_pessoa_w == "") {
		$erro = 1;
		$msg = "<div style='color:#FF0000'>Selecione uma pessoa.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($banco_form == "") {
		$erro = 2;
		$msg = "<div style='color:#FF0000'>Selecione um banco.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($agencia_form == "") {
		$erro = 3;
		$msg = "<div style='color:#FF0000'>Digite o n&uacute;mero da ag&ecirc;ncia banc&aacute;ria.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($numero_conta_form == "") {
		$erro = 4;
		$msg = "<div style='color:#FF0000'>Digite o n&uacute;mero da conta banc&aacute;ria.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} elseif ($tipo_conta_form == "") {
		$erro = 5;
		$msg = "<div style='color:#FF0000'>Informe o tipo de conta.</div>";
		$msg_titulo = "<div style='color:#009900'>$titulo</div>";
	} else {
		$erro = 0;
		$msg = "";
		$msg_titulo = "<div style='color:#0000FF'>Cadastro Editado com Sucesso!</div>";


		// ====== TABELA CADASTRO_FAVORECIDO ======================================================================
		include("../../includes/conecta_bd.php");
		$editar_favorecido = mysqli_query($conexao, "UPDATE cadastro_favorecido SET banco='$banco_form', agencia='$agencia_form', conta='$numero_conta_form', tipo_conta='$tipo_conta_form', usuario_alteracao='$usuario_cadastro', hora_alteracao='$hora_cadastro', data_alteracao='$data_cadastro', observacao='$obs_form', conta_conjunta='$conta_conjunta_aux', tipo_chave_pix='$tipo_chave_pix_form', chave_pix='$chave_pix_form', cpf_cnpj='$cpf_cnpj', nome_banco='$apelido_banco', sequencia_cc_sankhya=$idSankhyaCC_form WHERE codigo='$id_w'");
		include("../../includes/desconecta_bd.php");
	}
}
// ======================================================================================================


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
		<div style="width:1030px; height:400px; margin:auto; border:1px solid transparent; color:#003466">


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


			<!-- =======  BANCO ============================================================================================== -->
			<div style="width:339px; height:200px; border:1px solid transparent; margin-top:10px; float:left; text-align:center">
				<?php echo "$logo_banco" ?>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= AGÊNCIA ============================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Ag&ecirc;ncia:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 3) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$agencia_form</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$agencia_form</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= NUMERO CONTA =========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					N&ordm; da Conta:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($erro == 4) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$numero_conta_form</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$numero_conta_form</div></div>";
					}
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
					<?php
					if ($erro == 5) {
						echo "<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_conta_print</div></div>";
					} else {
						echo "<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_conta_print</div></div>";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CONTA CONJUNTA ========================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Conta Conjunta:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
							<?php echo "$conta_conjunta_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TIPO DE CHAVE PIX ====================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					Tipo de Chave Pix:
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo "$tipo_chave_pix_print" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= CHAVE PIX ============================================================================================== -->
			<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
					Pix:
				</div>

				<div style="width:334px; height:25px; float:left; border:1px solid transparent">
					<div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo "$chave_pix_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= OBSERVAÇÃO ============================================================================================= -->
			<div style="width:680px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:668px; height:17px; border:1px solid transparent; float:left">
					Observa&ccedil;&atilde;o:
				</div>

				<div style="width:668px; height:25px; float:left; border:1px solid transparent">
					<div style="width:666px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
						<div style="margin-top:6px; margin-left:5px; width:654px; height:16px; color:#003466; text-align:left; overflow:hidden">
							<?php echo "$obs_form" ?></div>
					</div>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ============================================================================================================= -->
			<div style="width:1020px; height:30px; float:left"></div>
			<!-- ============================================================================================================= -->





		</div>
		<!-- ===========  FIM DO FORMULÁRIO =========== -->





		<!-- ============================================================================================================= -->
		<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
			<?php
			if ($erro == 0) {
				echo "
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>";



				// ====== BOTAO VOLTAR =========================================================================================================
				echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/$pagina_mae.php' method='post'>
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
				// =============================================================================================================================
			} else {
				// ====== BOTAO VOLTAR =========================================================================================================
				echo "
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/cadastros/favorecidos/editar_1_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='id_w' value='$id_w' />
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w' />
	<input type='hidden' name='nome_form' value='$nome_form' />
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form' />
	<input type='hidden' name='banco_form' value='$banco_form' />
	<input type='hidden' name='agencia_form' value='$agencia_form' />
	<input type='hidden' name='numero_conta_form' value='$numero_conta_form' />
	<input type='hidden' name='tipo_conta_form' value='$tipo_conta_form' />
	<input type='hidden' name='conta_conjunta_form' value='$conta_conjunta_form' />
	<input type='hidden' name='obs_form' value='$obs_form' />
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
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