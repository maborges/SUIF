<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "produtos_cadastro";
$titulo = "Cadastros de Produtos";
$modulo = "cadastros";
$menu = "cadastro_produtos";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$codigo_w = $_POST["codigo_w"];

$nome_form = $_POST["nome_form"];
$cod_unidade_form = $_POST["cod_unidade_form"];
$quant_unidade_form = $_POST["quant_unidade_form"];
$quant_kg_saca_form = $_POST["quant_kg_saca_form"];
$tipo_form = $_POST["tipo_form"];

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== ACENTO / SEM ACENTO =======================================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú', ' ');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', '_');
// =================================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($botao == "CADASTRAR" and $nome_form == "") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Informe o nome do produto</div>";
} elseif ($botao == "CADASTRAR" and $cod_unidade_form == "") {
	$erro = 2;
	$msg = "<div style='color:#FF0000'>Informe a unidade de medida</div>";
} elseif ($botao == "CADASTRAR" and (!is_numeric($quant_unidade_form) or $quant_unidade_form == "")) {
	$erro = 3;
	$msg = "<div style='color:#FF0000'>Informe a quantidade de unidade em Kg</div>";
} elseif ($botao == "CADASTRAR" and (!is_numeric($quant_kg_saca_form) or $quant_kg_saca_form == "")) {
	$erro = 4;
	$msg = "<div style='color:#FF0000'>Informe a quantidade da saca em Kg</div>";
} elseif ($botao == "EDITAR" and $nome_form == "") {
	$erro = 5;
	$msg = "<div style='color:#FF0000'>Informe o nome do produto</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "EDITAR" and $cod_unidade_form == "") {
	$erro = 6;
	$msg = "<div style='color:#FF0000'>Informe a unidade de medida</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "EDITAR" and (!is_numeric($quant_unidade_form) or $quant_unidade_form == "")) {
	$erro = 7;
	$msg = "<div style='color:#FF0000'>Informe a quantidade de unidade em Kg</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "EDITAR" and (!is_numeric($quant_kg_saca_form) or $quant_kg_saca_form == "")) {
	$erro = 8;
	$msg = "<div style='color:#FF0000'>Informe a quantidade da saca em Kg</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "EXCLUSAO") {
	$erro = 9;
	$msg = "<div style='color:#FF0000'>Deseja realmente excluir este produto?</div>";
} else {
	$erro = 0;
	$msg = "";
}
// ==================================================================================================================

// ====== CADASTRAR NOVO PRODUTO ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[7] == "S") {
	// ====== CONTADOR CÓDIGO PRODUTO ===============================================================================
	$busca_codigo_produto = mysqli_query($conexao, "SELECT * FROM configuracoes");
	$aux_bcp = mysqli_fetch_row($busca_codigo_produto);
	$codigo_produto = $aux_bcp[6];

	$contador_cod_produto = $codigo_produto + 1;
	$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_codigo_produto='$contador_cod_produto'");
	// ==============================================================================================================

	// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
	$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade_form' AND estado_registro!='EXCLUIDO'");
	$aux_un_med = mysqli_fetch_row($busca_un_med);

	$un_descricao = $aux_un_med[2];
	// ======================================================================================================

	// ====== CONVERTE MAIÚSCULAS EM MINÚSCULAS =============================================================
	$produto_print_minu = ucfirst(strtolower($nome_form));
	$produto_apelido = str_replace($comAcentos, $semAcentos, $nome_form);
	// ======================================================================================================

	// CADASTRO
	
	$inserir = mysqli_query($conexao, "INSERT INTO cadastro_produto (codigo, descricao, codigo_produto, unidade, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, apelido, produto_print, quantidade_un, unidade_print, quant_kg_saca, cod_tipo_preferencial) VALUES ($contador_cod_produto, '$nome_form', '$codigo_produto', '$cod_unidade_form', '$usuario_cadastro_form', '$hora_cadastro_form', '$data_cadastro_form', 'ATIVO', '$produto_apelido', '$produto_print_minu', '$quant_unidade_form', '$un_descricao', '$quant_kg_saca_form', '$tipo_form')");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Produto cadastrado com sucesso!</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "CADASTRAR" and $permissao[7] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar produto</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} else {
}
// ==================================================================================================================


// ====== EDITAR PRODUTO ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[7] == "S") {

	// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
	$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade_form' AND estado_registro!='EXCLUIDO'");
	$aux_un_med = mysqli_fetch_row($busca_un_med);

	$un_descricao = $aux_un_med[2];
	// ======================================================================================================

	// ====== CONVERTE MAIÚSCULAS EM MINÚSCULAS =============================================================
	$produto_print_minu = ucfirst(strtolower($nome_form));
	$produto_apelido = str_replace($comAcentos, $semAcentos, $nome_form);
	// ======================================================================================================

	// EDIÇÃO
	$editar = mysqli_query($conexao, "UPDATE cadastro_produto SET descricao='$nome_form', unidade='$cod_unidade_form', usuario_alteracao='$usuario_cadastro_form', hora_alteracao='$hora_cadastro_form', data_alteracao='$data_cadastro_form', apelido='$produto_apelido', produto_print='$produto_print_minu', quantidade_un='$quant_unidade_form', unidade_print='$un_descricao', quant_kg_saca='$quant_kg_saca_form', cod_tipo_preferencial='$tipo_form' WHERE codigo='$codigo_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Produto editado com sucesso!</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} elseif ($botao == "EDITAR" and $permissao[7] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar produto</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} else {
}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR PRODUTO =================================================================================
if ($botao == "ATIVAR" and $permissao[7] == "S") {
	// ATIVAR
	$ativar = mysqli_query($conexao, "UPDATE cadastro_produto SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', hora_alteracao='$hora_cadastro_form', data_alteracao='$data_cadastro_form' WHERE codigo='$codigo_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Produto ativado com sucesso!</div>";
} elseif ($botao == "INATIVAR" and $permissao[7] == "S") {
	// INATIVAR
	$inativar = mysqli_query($conexao, "UPDATE cadastro_produto SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', hora_alteracao='$hora_cadastro_form', data_alteracao='$data_cadastro_form' WHERE codigo='$codigo_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Produto inativado com sucesso!</div>";
} elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[7] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar produto</div>";
} else {
}
// ==================================================================================================================


// ====== EXCLUIR PRODUTO ===========================================================================================
if ($botao == "EXCLUIR" and $permissao[7] == "S") {
	// EXCLUSAO
	$excluir = mysqli_query($conexao, "UPDATE cadastro_produto SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Produto exclu&iacute;do com sucesso!</div>";
} elseif ($botao == "EXCLUIR" and $permissao[7] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir produto</div>";
	$nome_form = "";
	$cod_unidade_form = "";
	$quant_unidade_form = "";
	$quant_kg_saca_form = "";
	$tipo_form = "";
} else {
}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' ORDER BY descricao");
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
		<?php include("../../includes/submenu_cadastro_produtos.php"); ?>
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
					echo "<i>$linha_registro produto cadastrado</i>";
				} elseif ($linha_registro == 0) {
					echo "";
				} else {
					echo "<i>$linha_registro produtos cadastrados</i>";
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
				<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/produtos/produtos_cadastro.php" method="post" />
				<?php
				if ($botao == "EDICAO") {
					echo "
	<input type='hidden' name='botao' value='EDITAR' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";
				} elseif ($botao == "EXCLUSAO") {
					echo "
	<input type='hidden' name='botao' value='EXCLUIR' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";
				} else {
					echo "<input type='hidden' name='botao' value='CADASTRAR' />";
				}
				?>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= NOME PRODUTO =========================================================================================== -->
			<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
					Nome do Produto:
				</div>

				<div style="width:215px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="nome_form" class="form_input" maxlength="30" id="ok" onBlur='alteraMaiusculo(this)' onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px" value="<?php echo "$nome_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= UNIDADE MEDIDA ========================================================================================= -->
			<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
					Unidade de Medida:
				</div>

				<div style="width:150px; height:25px; float:left; border:1px solid transparent">
					<select name="cod_unidade_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:134px" />
					<option></option>
					<?php
					$busca_un_medida = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE estado_registro='ATIVO' ORDER BY codigo");
					$linhas_un_medida = mysqli_num_rows($busca_un_medida);

					for ($u = 1; $u <= $linhas_un_medida; $u++) {
						$aux_un_medida = mysqli_fetch_row($busca_un_medida);

						if ($aux_un_medida[0] == $cod_unidade_form) {
							echo "<option selected='selected' value='$aux_un_medida[0]'>$aux_un_medida[2]</option>";
						} else {
							echo "<option value='$aux_un_medida[0]'>$aux_un_medida[2]</option>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= QUANTIDADE UNIDADE ===================================================================================== -->
			<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
					Quantidade Un. (Kg):
				</div>

				<div style="width:150px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="quant_unidade_form" class="form_input" maxlength="4" onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo "$quant_unidade_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= QUANTIDADE SACA ======================================================================================== -->
			<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
					Quantidade Saca (Kg):
				</div>

				<div style="width:150px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="quant_kg_saca_form" class="form_input" maxlength="4" onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo "$quant_kg_saca_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= TIPO PREFERENCIAL ====================================================================================== -->
			<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:196px; height:17px; border:1px solid transparent; float:left">
					Tipo Preferencial:
				</div>

				<div style="width:196px; height:25px; float:left; border:1px solid transparent">
					<select name="tipo_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:180px" />
					<option></option>
					<?php
					$busca_tipo_pref = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE estado_registro='ATIVO' ORDER BY produto, codigo");
					$linhas_tipo_pref = mysqli_num_rows($busca_tipo_pref);

					for ($t = 1; $t <= $linhas_tipo_pref; $t++) {
						$aux_tipo_pref = mysqli_fetch_row($busca_tipo_pref);

						if ($aux_tipo_pref[0] == $tipo_form) {
							echo "<option selected='selected' value='$aux_tipo_pref[0]'>$aux_tipo_pref[1] ($aux_tipo_pref[2])</option>";
						} else {
							echo "<option value='$aux_tipo_pref[0]'>$aux_tipo_pref[1] ($aux_tipo_pref[2])</option>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= BOTAO ================================================================================================== -->
			<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
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
			<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
					<!-- Botão: -->
				</div>

				<div style="width:95px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($botao == "EDICAO") {
						echo "
	<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";
					} elseif ($botao == "EXCLUSAO") {
						echo "
	<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post' />
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
<td width='100px'>C&oacute;digo</td>
<td width='350px'>Nome</td>
<td width='130px'>Unidade Medida</td>
<td width='130px'>Quantidade Unidade</td>
<td width='130px'>Quantidade Saca</td>
<td width='160px'>Tipo Preferencial</td>
<td width='60px'>Editar</td>
<td width='60px'>Inativar</td>
<td width='60px'>Excluir</td>
</tr>
</table>";
		}


		echo "<table class='tabela_geral' style='font-size:12px'>";


		// ====== FUNÇÃO FOR ===================================================================================
		for ($x = 1; $x <= $linha_registro; $x++) {
			$aux_registro = mysqli_fetch_row($busca_registro);

			// ====== DADOS DO USUÁRIO ============================================================================
			$codigo_w = $aux_registro[0];
			$nome_w = $aux_registro[1];
			$cod_unidade_w = $aux_registro[7];
			$quant_unidade_w = $aux_registro[23];
			$quant_saca_w = $aux_registro[27];
			$tipo_w = $aux_registro[29];
			$estado_registro_w = $aux_registro[19];
			$bloqueio_w = $aux_registro[40];

			$usuario_cadastro_w = $aux_registro[13];
			if ($usuario_cadastro_w == "") {
				$dados_cadastro_w = "";
			} else {
				$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[15]));
				$hora_cadastro_w = $aux_registro[14];
				$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
			}

			$usuario_alteracao_w = $aux_registro[16];
			if ($usuario_alteracao_w == "") {
				$dados_alteracao_w = "";
			} else {
				$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[18]));
				$hora_alteracao_w = $aux_registro[17];
				$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
			}
			// ======================================================================================================


			// ====== BUSCA UNIDADE DE MEDIDA =======================================================================
			$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade_w' AND estado_registro!='EXCLUIDO'");
			$aux_un_med = mysqli_fetch_row($busca_un_med);

			$un_descricao = $aux_un_med[2];
			// ======================================================================================================


			// ====== BUSCA TIPO DE PRODUTO =========================================================================
			$busca_tipo_prod = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$tipo_w' AND estado_registro='ATIVO'");
			$aux_tipo_prod = mysqli_fetch_row($busca_tipo_prod);

			$tipo_produto_print = $aux_tipo_prod[1];
			// ======================================================================================================


			// ====== BLOQUEIO PARA EDITAR ==========================================================================
			if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM") {
				$permite_editar = "SIM";
			} else {
				$permite_editar = "NAO";
			}
			// =======================================================================================================


			// ====== BLOQUEIO PARA ATIVAR ===========================================================================
			$permite_ativar = "SIM";
			/*
if ($bloqueio_w != "SIM")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
*/
			// ========================================================================================================


			// ====== BLOQUEIO PARA EXCLUIR ===========================================================================
			if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM") {
				$permite_excluir = "SIM";
			} else {
				$permite_excluir = "NAO";
			}
			// ========================================================================================================


			// ====== RELATORIO ========================================================================================
			if ($estado_registro_w == "ATIVO") {
				echo "<tr class='tabela_1' height='34px' title=' $dados_cadastro_w $dados_alteracao_w'>";
			} else {
				echo "<tr class='tabela_4' height='34px' title=' $dados_cadastro_w $dados_alteracao_w'>";
			}


			echo "
<td width='100px' align='center'>$codigo_w</td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_w</div></td>
<td width='130px' align='center'>$un_descricao</td>
<td width='130px' align='center'>$quant_unidade_w Kg</td>
<td width='130px' align='center'>$quant_saca_w Kg</td>
<td width='160px' align='center'>$tipo_produto_print</td>";

			// ====== BOTAO EDITAR ===================================================================================================
			if ($permite_editar == "SIM") {
				echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='nome_form' value='$nome_w'>
		<input type='hidden' name='cod_unidade_form' value='$cod_unidade_w'>
		<input type='hidden' name='quant_unidade_form' value='$quant_unidade_w'>
		<input type='hidden' name='quant_kg_saca_form' value='$quant_saca_w'>
		<input type='hidden' name='tipo_form' value='$tipo_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0	' />
		</form>	
		</td>";
			} else {
				echo "
		<td width='60px' align='center'></td>";
			}
			// =================================================================================================================

			// ====== BOTAO ATIVAR / INATIVAR ==================================================================================
			if ($permite_ativar == "SIM" and $estado_registro_w == "INATIVO") {
				echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
		</form>	
		</td>";
			} elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO") {
				echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='nome_form' value='$nome_w'>
		<input type='hidden' name='cod_unidade_form' value='$cod_unidade_w'>
		<input type='hidden' name='quant_unidade_form' value='$quant_unidade_w'>
		<input type='hidden' name='quant_kg_saca_form' value='$quant_saca_w'>
		<input type='hidden' name='tipo_form' value='$tipo_w'>
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
<i>Nenhum produto cadastrado.</i></div>";
		}
		// =================================================================================================================
		?>




		<!-- ============================================================================================================= -->
		<div class="espacamento_30"></div>
		<!-- ============================================================================================================= -->



	</div>
	<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



	<!-- ============================================================================================================= -->
	<div class="espacamento_40"></div>
	<!-- ============================================================================================================= -->


	<!-- ============================================================================================================= -->
	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
			<!-- ======== Observações ============= -->
		</div>
	</div>

	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
			<!-- ======== Observações ============= -->
		</div>
	</div>

	<div class="contador">
		<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
			<!-- ======== Observações ============= -->
		</div>
	</div>
	<!-- ============================================================================================================= -->


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