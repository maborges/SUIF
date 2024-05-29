<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "pesquisar_favorecido";
$titulo = "Pesquisar Favorecido";
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
$erro = 0;
$msg = '';
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"] ?? '';
$botao_2 = $_POST["botao_2"] ?? '';
$id_w = $_POST["id_w"] ?? '';
$idSankhya_w = $_POST["idSankhya_w"] ?? '';
$codigo_pessoa_w = $_POST["codigo_pessoa_w"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';

$nome_busca = $_POST["nome_busca"] ?? '';
$status_busca = $_POST["status_busca"] ?? '';

$nome_w = $_POST["nome_w"] ?? '';
$cpf_cnpj_w = $_POST["cpf_cnpj_w"] ?? '';
$banco_w = $_POST["banco_w"] ?? '';
$agencia_w = $_POST["agencia_w"] ?? '';
$conta_w = $_POST["conta_w"] ?? '';

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
$motivo_exclusao = $_POST["motivo_exclusao"] ?? '';

if ($status_busca == "" or $status_busca == "GERAL") {
	$mysql_status = "estado_registro IS NOT NULL";
	$status_busca = "GERAL";
} else {
	$mysql_status = "estado_registro='$status_busca'";
	$status_busca = $_POST["status_busca"];
}

if ($botao == "") {
	$status_busca = "ATIVO";
}
// ================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao_2 == "EXCLUSAO") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Deseja realmente excluir este favorecido?</div>";
}
// ================================================================================================================


// ====== ATIVAR / INATIVAR CADASTRO ==============================================================================
if ($botao_2 == "ATIVAR" and $permissao[35] == "S") {
	// ATIVAR
	$ativar_favorecido = mysqli_query($conexao, "UPDATE cadastro_favorecido SET estado_registro='ATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao, id_sankhya = $idSankhya_w' WHERE codigo='$id_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Cadastro de favorecido ativado com sucesso!</div>";
} elseif ($botao_2 == "INATIVAR" and $permissao[35] == "S") {
	// INATIVAR
	$inativar_favorecido = mysqli_query($conexao, "UPDATE cadastro_favorecido SET estado_registro='INATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', id_sankhya=$idSankhya_w WHERE codigo='$id_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Cadastro de favorecido inativado com sucesso!</div>";
} elseif (($botao_2 == "INATIVAR" or $botao_2 == "ATIVAR") and $permissao[35] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar favorecido.</div>";
} else {
}
// ==================================================================================================================


// ====== EXCLUIR CADASTRO ==========================================================================================
if ($botao_2 == "EXCLUIR" and $permissao[36] == "S") {
	// EXCLUSAO
	$excluir_favorecido = mysqli_query($conexao, "UPDATE cadastro_favorecido SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', data_exclusao='$data_alteracao', hora_exclusao='$hora_alteracao', id_senkhya = $idSankhya_w WHERE codigo='$id_w'");

	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#0000FF'>Cadastro de favorecido exclu&iacute;do com sucesso!</div>";
} elseif ($botao_2 == "EXCLUIR" and $permissao[36] != "S") {
	// MONTA MENSAGEM
	$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir favorecido.</div>";
} else {
}
// ==================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
if ($botao == "BUSCAR" and $nome_busca != "") {
	$busca_favorecido = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE nome LIKE '%$nome_busca%' AND $mysql_status ORDER BY nome");
	$linha_favorecido = mysqli_num_rows($busca_favorecido);
} else {
	$linha_favorecido = 0;
}
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
		<?php include("../../includes/submenu_cadastro_favorecidos.php"); ?>
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
				if ($linha_favorecido == 1) {
					echo "$linha_favorecido Cadastro";
				} elseif ($linha_favorecido > 1) {
					echo "$linha_favorecido Cadastros";
				} else {
					echo "";
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




		<?php
		if ($botao_2 == "EXCLUSAO") {
			echo "
				<!-- ======================================= FORMULARIO ========================================================== -->
				<div class='pqa' style='height:63px; border:1px solid #FF0000'>


				<!-- ======= ESPAÇAMENTO ========================================================================================= -->
				<div style='width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/pesquisar_favorecido.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='EXCLUIR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:325px; height:17px; border:1px solid transparent; float:left'>
					Nome:
					</div>
					
					<div style='width:325px; height:25px; float:left; border:1px solid transparent'>

					<input type='text' name='aux_nome' class='form_input' onkeydown='if (getKey(event) == 13) return false;' 
					style='width:300px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$nome_w'>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style='width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:195px; height:17px; border:1px solid transparent; float:left'>
					CPF/CNPJ:
					</div>
					
					<div style='width:195px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='aux_cpf_cnpj' class='form_input' maxlength='50'
					onkeydown='if (getKey(event) == 13) return false;' style='width:170px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$cpf_cnpj_w'>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style='width:140px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:135px; height:17px; border:1px solid transparent; float:left'>
					Banco:
					</div>
					
					<div style='width:135px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='aux_banco' class='form_input' maxlength='15' id='telddd_1'
					onkeydown='if (getKey(event) == 13) return false;' style='width:110px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$banco_w'>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style='width:140px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:135px; height:17px; border:1px solid transparent; float:left'>
					Ag&ecirc;ncia:
					</div>
					
					<div style='width:135px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='aux_agencia' class='form_input' maxlength='15' id='telddd_1'
					onkeydown='if (getKey(event) == 13) return false;' style='width:110px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$agencia_w'>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ================================================================================================================ -->
				<div style='width:140px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:135px; height:17px; border:1px solid transparent; float:left'>
					Conta:
					</div>
					
					<div style='width:135px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='aux_conta' class='form_input' maxlength='15' id='telddd_1'
					onkeydown='if (getKey(event) == 13) return false;' style='width:110px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$conta_w'>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= BOTAO ================================================================================================== -->
				<div style='width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:95px; height:17px; border:1px solid transparent; float:left'>
					<!-- Botão: -->
					</div>
					
					<div style='width:95px; height:25px; float:left; border:1px solid transparent'>
					<button type='submit' class='botao_1'>Excluir</button>
					</form>
					</div>
				</div>
				<!-- ================================================================================================================ -->


				<!-- ======= BOTAO CANCELAR ========================================================================================= -->
				<div style='width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:95px; height:17px; border:1px solid transparent; float:left'>
					<!-- Botão: -->
					</div>
					
					<div style='width:95px; height:25px; float:left; border:1px solid transparent'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/pesquisar_favorecido.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<button type='submit' class='botao_1'>Cancelar</button>
					</form>
					</div>
				</div>
				<!-- ================================================================================================================ -->



				</div>
				<!-- ============================================================================================================= -->


				<!-- ============================================================================================================= -->
				<div class='espacamento_20'></div>
				<!-- ============================================================================================================= -->
";
		} ?>





		<!-- ============================================================================================================= -->
		<div class="pqa" style="height:63px">


			<!-- ======= ESPAÇAMENTO ============================================================================================ -->
			<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/favorecidos/pesquisar_favorecido.php" method="post">
					<input type='hidden' name='botao' value='BUSCAR'>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ================================================================================================================ -->
			<div style="width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:328px; height:17px; border:1px solid transparent; float:left">
					Nome:
				</div>

				<div style="width:328px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="nome_busca" class="form_input" id="ok" onBlur="alteraMaiusculo(this)" style="width:300px; text-align:left; padding-left:5px" value="<?php echo "$nome_busca"; ?>">
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= STATUS CADASTRO ======================================================================================= -->
			<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
					Status do Cadastro:
				</div>

				<div style="width:218px; height:25px; float:left; border:1px solid transparent">
					<select name="status_busca" class="form_select" style="width:190px">
						<?php
						if ($status_busca == "ATIVO") {
							echo "<option value='ATIVO' selected='selected'>ATIVOS</option>";
						} else {
							echo "<option value='ATIVO'>ATIVOS</option>";
						}

						if ($status_busca == "INATIVO") {
							echo "<option value='INATIVO' selected='selected'>INATIVOS</option>";
						} else {
							echo "<option value='INATIVO'>INATIVOS</option>";
						}

						if ($status_busca == "EXCLUIDO") {
							echo "<option value='EXCLUIDO' selected='selected'>EXCLU&Iacute;DOS</option>";
						} else {
							echo "<option value='EXCLUIDO'>EXCLU&Iacute;DOS</option>";
						}

						if ($status_busca == "GERAL") {
							echo "<option value='GERAL' selected='selected'>(Todos os Cadastros)</option>";
						} else {
							echo "<option value='GERAL'>(Todos os Cadastros)</option>";
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
					<button type='submit' class='botao_1'>Buscar</button>
					</form>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= IMPRIMIR ================================================================================================== -->
			<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
					<!-- Botão: -->
				</div>

				<div style="width:95px; height:25px; float:left; border:1px solid transparent">
					<?php
					if ($linha_favorecido >= 1) {
						echo "
							<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/relatorio_favorecido_impressao.php' target='_blank' method='post'>
							<input type='hidden' name='pagina_mae' value='$pagina'>
							<input type='hidden' name='botao' value='BUSCAR'>
							<input type='hidden' name='nome_busca' value='$nome_busca'>
							<input type='hidden' name='status_busca' value='$status_busca'>
							<button type='submit' class='botao_1'>Imprimir</button>
							</form>";
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
		if ($linha_favorecido == 0) {
			echo "
				<div style='height:210px'>
				<div class='espacamento_30'></div>";
		} else {
			echo "
				<div class='ct_relatorio'>
				<div class='espacamento_10'></div>

				<table class='tabela_cabecalho'>
				<tr>
				<td width='65px'>Sankhya</td>
				<td width='275px'>Nome</td>
				<td width='160px'>CPF/CNPJ</td>
				<td width='140px'>Banco</td>
				<td width='80px'>Ag&ecirc;ncia</td>
				<td width='125px'>N&ordm; da Conta</td>
				<td width='95px'>Tipo de Conta</td>
				<td width='60px'>Visualizar</td>
				<td width='60px'>Editar</td>
				<td width='60px'>Inativar</td>
				<td width='60px'>Excluir</td>
				</tr>
				</table>";
		}


		echo "<table class='tabela_geral' style='font-size:12px'>";


		// ====== FUNÇÃO FOR ===================================================================================
		for ($x = 1; $x <= $linha_favorecido; $x++) {
			$aux_favorecido = mysqli_fetch_row($busca_favorecido);

			// ====== DADOS DO CADASTRO ============================================================================
			$id_w = $aux_favorecido[0];
			$idSankhya_w =  $aux_favorecido[23];
			$codigo_pessoa_w = $aux_favorecido[1];
			$banco_w = $aux_favorecido[2];
			$agencia_w = $aux_favorecido[3];
			$conta_w = $aux_favorecido[4];
			$tipo_conta_w = $aux_favorecido[5];
			$observacao_w = $aux_favorecido[12];
			$estado_registro_w = $aux_favorecido[13];
			$nome_w = $aux_favorecido[14];
			$conta_conjunta_w = $aux_favorecido[15];

			if ($conta_conjunta_w == "SIM" and $agencia_w != "") {
				$conta_conjunta_print = "SIM";
			} elseif ($conta_conjunta_w != "SIM" and $agencia_w != "") {
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


			// ====== BUSCA PESSOA ===================================================================================
			$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$codigo_pessoa_w'");
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

			if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf") {
				$cpf_cnpj = $cpf_pessoa;
			} else {
				$cpf_cnpj = $cnpj_pessoa;
			}

			if ($linha_pessoa == 0) {
				$cidade_uf = "";
			} else {
				$cidade_uf = "$cidade_pessoa/$estado_pessoa";
			}
			// ======================================================================================================


			// ====== BUSCA BANCO ===================================================================================
			$busca_banco = mysqli_query($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_w'");
			$aux_banco = mysqli_fetch_row($busca_banco);

			$apelido_banco = $aux_banco[3];
			$logomarca_banco = $aux_banco[4];

			if (empty($logomarca_banco)) {
				$logo_banco = "$apelido_banco";
			} else {
				$logo_banco = "<img src='$servidor/$diretorio_servidor/imagens/$logomarca_banco' style='height:22px'>";
			}
			// ======================================================================================================


			// ====== TIPO DE CONTA =================================================================================
			if ($tipo_conta_w == "corrente") {
				$tipo_conta_print = "Corrente";
			} elseif ($tipo_conta_w == "poupanca") {
				$tipo_conta_print = "Poupan&ccedil;a";
			} elseif ($tipo_conta_w == "salario") {
				$tipo_conta_print = "Sal&aacute;rio";
			} elseif ($tipo_conta_w == "aplicacao") {
				$tipo_conta_print = "Aplica&ccedil;&atilde;o";
			} else {
				$tipo_conta_print = "";
			}
			// ======================================================================================================


			// ====== BLOQUEIO PARA EDITAR ========================================================================
			if ($estado_registro_w == "ATIVO") {
				$permite_editar = "SIM";
			} else {
				$permite_editar = "NAO";
			}
			// ========================================================================================================


			// ====== BLOQUEIO PARA ATIVAR ========================================================================
			$permite_ativar = "SIM";
			/*
if ($permissao[35] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
*/
			// ========================================================================================================


			// ====== BLOQUEIO PARA EXCLUIR ========================================================================
			if ($estado_registro_w != "EXCLUIDO") {
				$permite_excluir = "SIM";
			} else {
				$permite_excluir = "NAO";
			}
			// ========================================================================================================


			// ====== RELATORIO ========================================================================================
			if ($estado_registro_w == "INATIVO") {
				echo "<tr class='tabela_4' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			} elseif ($estado_registro_w == "EXCLUIDO") {
				echo "<tr class='tabela_5' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			} else {
				echo "<tr class='tabela_1' title=' Nome: $nome_pessoa &#13; ID Cadastro: $id_w &#13; C&oacute;digo Pessoa: $codigo_pessoa_w &#13; Status Cadastro: $estado_registro_w &#13; Conta Conjunta: $conta_conjunta_print &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
			}

			echo "
				<td width='65px' align='center' style='height:28px'>$idSankhya_w</td>
				<td width='275px' align='left' style='height:28px'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_pessoa</div></td>
				<td width='160px' align='center' style='height:28px'>$cpf_cnpj</td>
				<td width='140px' align='center' style='height:28px'>$logo_banco</td>
				<td width='80px' align='center' style='height:28px'>$agencia_w</td>
				<td width='125px' align='center' style='height:28px'>$conta_w</td>
				<td width='95px' align='center' style='height:28px'>$tipo_conta_print</td>";

			// ====== BOTAO VISUALIZAR ===============================================================================================
			echo "
				<td width='60px' align='center' style='height:28px'>
				<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/visualizar_cadastro.php' method='post'>
				<input type='hidden' name='pagina_mae' value='$pagina'>
				<input type='hidden' name='botao' value='VISUALIZAR'>
				<input type='hidden' name='id_w' value='$id_w'>
				<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
				<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
				<input type='hidden' name='nome_form' value='$nome_busca'>
				<input type='hidden' name='nome_busca' value='$nome_busca'>
				<input type='hidden' name='status_busca' value='$status_busca'>
				<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' border='0'>
				</form>	
				</td>";
			// =================================================================================================================


			// ====== BOTAO EDITAR ===================================================================================================
			if ($permite_editar == "SIM") {
				echo "
					<td width='60px' align='center' style='height:28px'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/editar_1_formulario.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='EDITAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_form' value='$nome_busca'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0'>
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center' style='height:28px'></td>";
			}
			// =================================================================================================================

			// ====== BOTAO ATIVAR / INATIVAR ==================================================================================
			if ($permite_ativar == "SIM" and $estado_registro_w == "INATIVO") {
				echo "
					<td width='60px' align='center' style='height:28px'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/pesquisar_favorecido.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='ATIVAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0'>
					</form>	
					</td>";
			} elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO") {
				echo "
					<td width='60px' align='center'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/pesquisar_favorecido.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='INATIVAR'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='20px' border='0'>
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center' style='height:28px'></td>";
			}
			// =================================================================================================================

			// ====== BOTAO EXCLUIR ===================================================================================================
			if ($permite_excluir == "SIM") {
				echo "
					<td width='60px' align='center' style='height:28px'>
					<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/pesquisar_favorecido.php' method='post'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='botao' value='BUSCAR'>
					<input type='hidden' name='botao_2' value='EXCLUSAO'>
					<input type='hidden' name='id_w' value='$id_w'>
					<input type='hidden' name='idSankhya_w' value='$idSankhya_w'>
					<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
					<input type='hidden' name='nome_busca' value='$nome_busca'>
					<input type='hidden' name='status_busca' value='$status_busca'>
					<input type='hidden' name='nome_w' value='$nome_pessoa'>
					<input type='hidden' name='cpf_cnpj_w' value='$cpf_cnpj'>
					<input type='hidden' name='banco_w' value='$apelido_banco'>
					<input type='hidden' name='agencia_w' value='$agencia_w'>
					<input type='hidden' name='conta_w' value='$conta_w'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' border='0'>
					</form>	
					</td>";
			} else {
				echo "
					<td width='60px' align='center' style='height:28px'></td>";
			}
			// =================================================================================================================


		}

		echo "</tr></table>";
		// =================================================================================================================



		// =================================================================================================================
		if ($linha_favorecido == 0 and $botao == "BUSCAR") {
			echo "
			<div class='espacamento_30'></div>
			<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
			<i>Nenhum cadastro de favorecido encontrado.</i></div>";
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