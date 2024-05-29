<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "usuarios_cadastro";
$titulo = "Cadastros de Usu&aacute;rios";
$modulo = "cadastros";
$menu = "cadastro_usuarios";
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"] ?? '';
$username_w = $_POST["username_w"] ?? '';
$idSanlhy_w = $_POST["idSankhya_w"] ?? '';

$username_form = $_POST["username_form"] ?? '';
$idSankhya_form = $_POST["idSankhya_form"] ?? '';
$nome_form = $_POST["nome_form"] ?? '';
$email_form = $_POST["email_form"] ?? '';
$telefone_form = $_POST["telefone_form"] ?? '';
$filial_form = $_POST["filial_form"] ?? '';

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIADOR DE SENHA =========================================================================================
$monta_senha = "@W9y." . $diretorio_servidor;
$senha_provisoria = md5($monta_senha);
// =================================================================================================================


// ====== BUSCA DUPLICIDADE ==========================================================================================
$busca_duplicidade = mysqli_query($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND username='$username_form' ORDER BY username");
$linha_duplicidade = mysqli_num_rows($busca_duplicidade);
// ==================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $linha_duplicidade >= 1) {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Usu&aacute;rio já existe</div>";
} elseif ($botao == "CADASTRAR" and $username_form == "") {
	$erro = 2;
	$msg = "<div style='color:#FF0000'>Crie um username com o nome e sobrenome do usu&aacute;rio separados por ponto</div>";
} elseif ($botao == "CADASTRAR" and $idSankhya_form == "") {
	$erro = 2;
	$msg = "<div style='color:#FF0000'>Informe o código do usuário Sankhya</div>";
} elseif ($botao == "CADASTRAR" and $nome_form == "") {
	$erro = 3;
	$msg = "<div style='color:#FF0000'>Informe o nome e sobrenome do usu&aacute;rio</div>";
} elseif ($botao == "CADASTRAR" and $filial_form == "") {
	$erro = 4;
	$msg = "<div style='color:#FF0000'>Informe a filial do usu&aacute;rio</div>";
} elseif ($botao == "EDITAR" and $nome_form == "") {
	$erro = 5;
	$msg = "<div style='color:#FF0000'>Informe o nome e sobrenome do usu&aacute;rio</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
	$username_w = "";
	$idSankhya_w = "";
} elseif ($botao == "EDITAR" and $filial_form == "") {
	$erro = 6;
	$msg = "<div style='color:#FF0000'>Informe a filial do usu&aacute;rio</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
	$username_w = "";
	$idSankhya_w = "";
} elseif ($botao == "EXCLUSAO") {
	$erro = 7;
	$msg = "<div style='color:#FF0000'>Deseja realmente excluir este usu&aacute;rio?</div>";
} else {
	$erro = 0;
	$msg = "";
}
// ==================================================================================================================

// ====== CADASTRAR NOVO USUÁRIO ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[55] == "S") {
	// CADASTRO
	$inserir = mysqli_query($conexao, "INSERT INTO usuarios (username, senha, primeiro_nome, nome_completo, email_principal, usuario_interno, celular, contador_bloqueio, estado_registro, filial, nome_filial, usuario_cadastro, data_cadastro, hora_cadastro, id_sankhya) VALUES ('$username_form', '$senha_provisoria', '$nome_form', '$nome_form', '$email_form', 'N', '$telefone_form', '0', 'ATIVO', '$filial_form', '$filial_form', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', '$idSankhya_form')");

	$inserir_permissoes = mysqli_query($conexao, "INSERT INTO usuarios_permissoes (username) VALUES ('$username_form')");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Usu&aacute;rio cadastrado com sucesso!</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} elseif ($botao == "CADASTRAR" and $permissao[55] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar outro usu&aacute;rio</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} else {
}
// ==================================================================================================================


// ====== EDITAR USUÁRIO ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[58] == "S") {
	// EDIÇÃO
	$editar = mysqli_query($conexao, "UPDATE usuarios SET primeiro_nome='$nome_form', nome_completo='$nome_form', email_principal='$email_form', celular='$telefone_form', filial='$filial_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form',id_sankhya='$idSankhya_form' WHERE username='$username_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Usu&aacute;rio editado com sucesso!</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} elseif ($botao == "EDITAR" and $permissao[58] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar outro usu&aacute;rio</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} else {
}
// ==================================================================================================================


// ====== BLOQUEAR / DESBLOQUEAR USUÁRIO ============================================================================
if ($botao == "DESBLOQUEAR" and $permissao[57] == "S") {
	// DESBLOQUEAR
	$desbloquear = mysqli_query($conexao, "UPDATE usuarios SET estado_registro='ATIVO', contador_bloqueio='0', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE username='$username_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Usu&aacute;rio desbloqueado com sucesso!</div>";
} elseif ($botao == "BLOQUEAR" and $permissao[57] == "S") {
	// BLOQUEAR
	$bloquear = mysqli_query($conexao, "UPDATE usuarios SET estado_registro='BLOQUEADO', contador_bloqueio='10', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE username='$username_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Usu&aacute;rio bloqueado com sucesso!</div>";
} elseif (($botao == "BLOQUEAR" or $botao == "DESBLOQUEAR") and $permissao[57] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para bloquear/desbloquear outro usu&aacute;rio</div>";
} else {
}
// ==================================================================================================================


// ====== EXCLUIR USUÁRIO ============================================================================================
if ($botao == "EXCLUIR" and $permissao[128] == "S") {
	// EXCLUSAO
	$excluir = mysqli_query($conexao, "UPDATE usuarios SET estado_registro='EXCLUIDO', contador_bloqueio='10', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE username='$username_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Usu&aacute;rio exclu&iacute;do com sucesso!</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} elseif ($botao == "EXCLUIR" and $permissao[128] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir outro usu&aacute;rio</div>";
	$username_form = "";
	$idSankhya_form = "";
	$nome_form = "";
	$email_form = "";
	$telefone_form = "";
	$filial_form = "";
} else {
}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
$linha_registro = mysqli_num_rows($busca_registro);
// ==================================================================================================================


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
	}, 4000); // 4 Segundos
</script>

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/maskbrphone.js"></script>

<script>
	jQuery(function($) {

		// TELEFONE COM DDD 1
		$("#telddd_1").maskbrphone({
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
		<?php include("../../includes/submenu_cadastro_usuarios.php"); ?>
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
				<?php
				if ($linha_registro == 1) {
					echo "<i>$linha_registro usu&aacute;rio cadastrado</i>";
				} elseif ($linha_registro == 0) {
					echo "";
				} else {
					echo "<i>$linha_registro usu&aacute;rios cadastrados</i>";
				}
				?>
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


		<div class="pqa" style="height:63px">
			<!-- ======================================= FORMULARIO ========================================================== -->


			<!-- ======= ESPAÇAMENTO ============================================================================================ -->
			<div style="width:30px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/usuarios/usuarios_cadastro.php" method="post">
				<?php
				if ($botao == "EDICAO") {
					echo "
						<input type='hidden' name='botao' value='EDITAR' />
						<input type='hidden' name='username_w' value='$username_w' />
						<input type='hidden' name='idSankhya_w' value='idSankhya_w' />";
				} elseif ($botao == "EXCLUSAO") {
					echo "
						<input type='hidden' name='botao' value='EXCLUIR' />
						<input type='hidden' name='username_w' value='$username_w' />
						<input type='hidden' name='idSankhya_w' value='$idSankhya_w' />";
				} else {
					echo "<input type='hidden' name='botao' value='CADASTRAR' />";
				}
				?>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= USERNAME =============================================================================================== -->
			<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
					Usu&aacute;rio:
				</div>

				<div style="width:215px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($botao == "EDICAO" or $botao == "EXCLUSAO") {
						echo "
							<input type='text' name='username_form' class='form_input' onkeydown='if (getKey(event) == 13) return false;' 
							style='width:191px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$username_form' />";
					} else {
						echo "
							<input type='text' name='username_form' class='form_input' id='ok' maxlength='30' onBlur='alteraMaiusculo(this)'
							onkeydown='if (getKey(event) == 13) return false;' style='width:191px; text-align:left; padding-left:5px' value='$username_form' />";
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= NOME USUÁRIO =========================================================================================== -->
			<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
					Nome:
				</div>

				<div style="width:215px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="nome_form" class="form_input" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px" value="<?php echo "$nome_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= EMAIL ================================================================================================== -->
			<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
					E-mail:
				</div>

				<div style="width:215px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="email_form" class="form_input" maxlength="50" onBlur="alteraMinusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px" value="<?php echo "$email_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TELEFONE =============================================================================================== -->
			<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
					Telefone:
				</div>

				<div style="width:150px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="telefone_form" class="form_input" maxlength="15" id="telddd_1" onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo "$telefone_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= FILIAL ================================================================================================= -->
			<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
					Filial:
				</div>

				<div style="width:150px; height:25px; float:left; border:1px solid transparent">
					<select name="filial_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:134px" />
					<option></option>
					<?php
					$busca_filial_usuario = mysqli_query($conexao, "SELECT * FROM filiais WHERE estado_registro!='EXCLUIDO' ORDER BY codigo");
					$linhas_filial_usuario = mysqli_num_rows($busca_filial_usuario);

					for ($f = 1; $f <= $linhas_filial_usuario; $f++) {
						$aux_filial_usuario = mysqli_fetch_row($busca_filial_usuario);

						if ($aux_filial_usuario[1] == $filial_form) {
							echo "<option selected='selected' value='$aux_filial_usuario[1]'>$aux_filial_usuario[2]</option>";
						} else {
							echo "<option value='$aux_filial_usuario[1]'>$aux_filial_usuario[2]</option>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<!-- ================================================================================================================ -->

			<!-- =======  USUÁRIO  SANKHYA =================================================================================== -->
			<div style="width:90px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:80px; height:17px; border:1px solid transparent; float:left">
					Sankhya:
				</div>

				<div style="width:90px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="idSankhya_form" class="form_input" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" style="width:85px; text-align:left; padding-left:5px" value=<?php echo "$idSankhya_form"; ?>>
				</div>
			</div>


			<!-- ======= BOTAO ================================================================================================== -->
			<div style="width:100px; height:50px; border:1px solid transparent; margin-top:0px; margin-left:15px; float:left">
				<div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
					<!-- Botão: -->
				</div>

				<div style="width:95px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($botao == "EDICAO") {
						echo "<button type='submit' class='botao_1'>Salvar</button>";
					} elseif ($botao == "EXCLUSAO") {
						echo "<button type='submit' class='botao_1'>Excluir</button>";
					} else {
						echo "<button type='submit' class='botao_1'>Cadastrar</button>";
					}
					?>
					</form>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= BOTAO CANCELAR ========================================================================================= -->
			<div style="width:100px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
				<div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
					<!-- Botão: -->
				</div>

				<div style="width:95px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($botao == "EDICAO") {
						echo "
							<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post' />
							<button type='submit' class='botao_1'>Cancelar</button>
							</form>";
					} elseif ($botao == "EXCLUSAO") {
						echo "
							<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post' />
							<button type='submit' class='botao_1'>Cancelar</button>
							</form>";
					} else {
					}
					?>
				</div>
			</div>
			<!-- ================================================================================================================ -->



		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="espacamento_20"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<?php
		if ($linha_registro == 0) {
			echo "
				<div style='height:210px'>
				<div class='espacamento_30'></div>";
		} else {
			echo "
				<div class='ct_relatorio'>
				<div class='espacamento_10'></div>

				<table class='tabela_cabecalho'>
				<tr>
				<td width='300px'>Usu&aacute;rio</td>
				<td width='90px'>Sankhya</td>
				<td width='260px'>Nome</td>
				<td width='200px'>Telefone</td>
				<td width='150px'>Status</td>
				<td width='60px'>Editar</td>
				<td width='60px'>Bloquear</td>
				<td width='60px'>Excluir</td>
				</tr>
				</table>";
		}


		echo "<table class='tabela_geral' style='font-size:12px'>";


		// ====== FUNÇÃO FOR ===================================================================================
		for ($x = 1; $x <= $linha_registro; $x++) {
			$aux_registro = mysqli_fetch_row($busca_registro);

			// ====== DADOS DO USUÁRIO ============================================================================
			$username_w = $aux_registro[0];
			$idSankhya_w = $aux_registro[25];
			$primeiro_nome_w = $aux_registro[2];
			$nome_completo_w = $aux_registro[3];
			$email_w = $aux_registro[4];
			$usuario_interno_w = $aux_registro[5];
			$telefone_w = $aux_registro[9];
			$contador_bloqueio_w = $aux_registro[10];
			$estado_registro_w = $aux_registro[11];
			$filial_w = $aux_registro[12];

			$usuario_cadastro_w = $aux_registro[15];
			if ($usuario_cadastro_w == "") {
				$dados_cadastro_w = "";
			} else {
				$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[16]));
				$hora_cadastro_w = $aux_registro[17];
				$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
			}

			$usuario_alteracao_w = $aux_registro[18];
			if ($usuario_alteracao_w == "") {
				$dados_alteracao_w = "";
			} else {
				$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[19]));
				$hora_alteracao_w = $aux_registro[20];
				$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
			}

			$usuario_exclusao_w = $aux_registro[21];
			if ($usuario_exclusao_w == "") {
				$dados_exclusao_w = "";
			} else {
				$data_exclusao_w = date('d/m/Y', strtotime($aux_registro[22]));
				$hora_exclusao_w = $aux_registro[23];
				$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
			}
			// ======================================================================================================


			// ====== BLOQUEIO PARA EDITAR ========================================================================
			if ($estado_registro_w == "ATIVO") {
				$permite_editar = "SIM";
			} else {
				$permite_editar = "NAO";
			}
			// ========================================================================================================


			// ====== BLOQUEIO PARA BLOQUEAR ========================================================================
			$permite_bloquear = "SIM";
			/*
if ($permissao[57] == "S")
{$permite_bloquear = "SIM";}
else
{$permite_bloquear = "NAO";}
*/
			// ========================================================================================================


			// ====== BLOQUEIO PARA EXCLUIR ========================================================================
			if ($estado_registro_w == "ATIVO") {
				$permite_excluir = "SIM";
			} else {
				$permite_excluir = "NAO";
			}
			// ========================================================================================================


			// ====== RELATORIO ========================================================================================
			if ($estado_registro_w == "ATIVO" and $contador_bloqueio_w < 4) {
				echo "<tr class='tabela_1' height='34px' title=' Filial: $filial_w &#13; E-mail: $email_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			} else {
				echo "<tr class='tabela_5' height='34px' title=' Filial: $filial_w &#13; E-mail: $email_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			}

			echo "
				<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$username_w</div></td>
				<td width='90px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$idSankhya_w</div></td>
				<td width='260px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$primeiro_nome_w</div></td>
				<td width='200px' align='center'>$telefone_w</td>
				<td width='150px' align='center'>$estado_registro_w</td>";

			// ====== BOTAO EDITAR ===================================================================================================
			if ($permite_editar == "SIM") {
				echo "
					<td width='60px' align='center'>
					<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='EDICAO'>
					<input type='hidden' name='username_w' value='$username_w'>
					<input type='hidden' name='username_form' value='$username_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='idSankhya_form' value='$idSankhya_w'>
					<input type='hidden' name='nome_form' value='$primeiro_nome_w'>
					<input type='hidden' name='email_form' value='$email_w'>
					<input type='hidden' name='telefone_form' value='$telefone_w'>
					<input type='hidden' name='filial_form' value='$filial_w'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0' />
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center'></td>";
			}
			// =================================================================================================================

			// ====== BOTAO BLOQUEAR / DESBLOQUEAR =============================================================================
			if ($permite_bloquear == "SIM" and $estado_registro_w == "BLOQUEADO") {
				echo "
					<td width='60px' align='center'>
					<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='DESBLOQUEAR'>
					<input type='hidden' name='username_w' value='$username_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
					</form>	
					</td>";
			} elseif ($permite_bloquear == "SIM" and $estado_registro_w == "ATIVO") {
				echo "
					<td width='60px' align='center'>
					<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BLOQUEAR'>
					<input type='hidden' name='username_w' value='$username_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='20px' border='0' />
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center'></td>";
			}
			// =================================================================================================================

			// ====== BOTAO EXCLUIR ===================================================================================================
			if ($permite_excluir == "SIM") {
				echo "
					<td width='60px' align='center'>
					<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='EXCLUSAO'>
					<input type='hidden' name='username_w' value='$username_w'>
					<input type='hidden' name='username_form' value='$username_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='idSankhya_form' value='$idSankhya_form'>
					<input type='hidden' name='nome_form' value='$primeiro_nome_w'>
					<input type='hidden' name='email_form' value='$email_w'>
					<input type='hidden' name='telefone_form' value='$telefone_w'>
					<input type='hidden' name='filial_form' value='$filial_w'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' border='0' />
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center'></td>";
			}
			// =================================================================================================================


		}

		echo "</tr></table>";
		// =================================================================================================================



		// =================================================================================================================
		if ($linha_registro == 0) {
			echo "
				<div class='espacamento_30'></div>
				<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
				<i>Nenhum usu&aacute;rio cadastrado.</i></div>";
		}
		// =================================================================================================================
		?>




		<div class="espacamento_30"></div>


	</div>
	<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



	<!-- ============================================================================================================= -->
	<div class="espacamento_40"></div>


	<!-- ============================================================================================================= -->
	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px; color:#FF0000">
			Observa&ccedil;&otilde;es:
		</div>
	</div>

	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
			O usu&aacute;rio ser&aacute; bloqueado automaticamente quando errar a senha 4 vezes consecutivas.
		</div>
	</div>

	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
			<?php
			if ($permissao[55] == "S") {
				echo 
							"<b>$monta_senha</b> ser&aacute; a senha provis&oacute;ria quando for criado um novo usu&aacute;rio. 
				Oriente o usu&aacute;rio que troque por uma senha pessoal assim que acessar o sistema.";
			}
			?>
		</div>
	</div>
	<!-- ============================================================================================================= -->


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