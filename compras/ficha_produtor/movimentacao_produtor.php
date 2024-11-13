<?php
// ================================================================================================================
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "movimentacao_produtor";
$titulo = "Movimenta&ccedil;&atilde;o Ficha do Produtor";
$modulo = "compras";
$menu = "ficha_produtor";
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$hoje = date('Y-m-d', time());
$data_hoje = date('d/m/Y', time());
$mes_atras = date('d/m/Y', strtotime('-60 days')); // 2 mêses atras

$filial = $filial_usuario;
$fornecedor = $_POST["fornecedor"] ?? '';
$cod_produto = $_POST["cod_produto"] ?? '';
$mostra_cancelada = $_POST["mostra_cancelada"] ?? '';
$botao = $_POST["botao"] ?? '';
$numero_compra_aux = $_POST["numero_compra_aux"] ?? '';
$cod_produtor  = $_POST["cod_produtor"] ?? '';

if ($botao == "seleciona") {
	$data_inicial_aux = $mes_atras;
	$data_inicial = Helpers::ConverteData($mes_atras);
	$data_final_aux = $data_hoje;
	$data_final = Helpers::ConverteData($data_hoje);
} else {
	$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);
}

$dia_atras = date('Y-m-d', strtotime('-1 days', strtotime($data_inicial)));

if ($_POST["monstra_situacao"] ?? '' == "") {
	$monstra_situacao = "todas";
} else {
	$monstra_situacao = $_POST["monstra_situacao"];
}
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
$obs_fornecedor = $aux_forn[22];

if ($aux_forn[2] == "pf") {
	$cpf_cnpj = "CPF: " . $aux_forn[3];
} elseif ($aux_forn[2] == "pj") {
	$cpf_cnpj = "CNPJ: " . $aux_forn[4];
} else {
	$cpf_cnpj = "";
}

if ($linhas_fornecedor == 0) {
	$cid_uf_fornecedor = "";
} else {
	$cid_uf_fornecedor = $cidade_fornecedor . "-" . $estado_fornecedor;
}

$obs_print = '';
if (!empty($obs_fornecedor)) {
	$obs_print = "OBSERVA&Ccedil;&Atilde;O: " . $obs_fornecedor;
}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($fornecedor == "") {
	$erro = 1;
	$msg_erro = "* Selecione um fornecedor";
} elseif ($linhas_fornecedor == 0) {
	$erro = 2;
	$msg_erro = "* Fornecedor inv&aacute;lido";
} elseif ($cod_produto == "") {
	$erro = 3;
	$msg_erro = "* Selecione um produto";
} elseif ($linhas_bp == 0) {
	$erro = 4;
	$msg_erro = "* Produto inv&aacute;lido";
} else {
	$erro = 0;
	$msg_erro = "";
}
// ======================================================================================================


// ====== BUSCA SALDO ARMAZENADO ========================================================================
/*
$busca_saldo_arm = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
$linhas_saldo_arm = mysqli_num_rows ($busca_saldo_arm);
$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
$saldo_armazenado_print = number_format($aux_saldo_arm[9],2,",",".");
*/
// ======================================================================================================


// ====== BUSCA CONTROLE DE TALAO ========================================================================
$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE filial='$filial' AND codigo_pessoa='$fornecedor' AND devolvido='N' AND estado_registro!='EXCLUIDO' ORDER BY codigo");
$linha_talao = mysqli_num_rows($busca_talao);
// ======================================================================================================


// ====== BUSCA CONTRATO FUTURO ========================================================================
$busca_futuro = mysqli_query($conexao, "SELECT * FROM contrato_futuro WHERE filial='$filial' AND produtor='$fornecedor' AND estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' ORDER BY codigo");
$linha_futuro = mysqli_num_rows($busca_futuro);
// ======================================================================================================


// ====== BUSCA CONTRATO TRATADO ========================================================================
$busca_tratado = mysqli_query($conexao, "SELECT * FROM contrato_tratado WHERE  filial='$filial' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO' AND estado_registro!='EXCLUIDO' ORDER BY codigo");
$linha_tratado = mysqli_num_rows($busca_tratado);
// ======================================================================================================


// ====== BUSCA E SOMA COMPRAS ========================================================================
$busca_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' ORDER BY data_compra");
$linha_compra = mysqli_num_rows($busca_compra);

$soma_compras = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor_total) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final'"));
$soma_compras_print = number_format($soma_compras[0], 2, ",", ".");
// ======================================================================================================


// ====== SALDO PRODUTOR =================================================================================
// ------ SOMA QUANTIDADE DE ENTRADA (PERÍODO) --------------------------------------------------------------------
$soma_quant_produto_e = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final'"));
$quant_produto_e_print = number_format($soma_quant_produto_e[0], 2, ",", ".");

// ------ SOMA QUANTIDADE DE SAÍDA (PERÍODO) -----------------------------------------------------------------------
$soma_quant_produto_s = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
$quant_produto_s_print = number_format($soma_quant_produto_s[0], 2, ",", ".");

// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0], 2, ",", ".");

// ------ SOMA QUANTIDADE DE SAÍDA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0], 2, ",", ".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto, 2, ",", ".");
// ======================================================================================================


// ====== SOMA SALDO ANTERIOR  ====================================================================================
$soma_ant_e = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra<='$dia_atras'"));

$soma_ant_s = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra<='$dia_atras'"));
$saldo_ant = ($soma_ant_e[0] - $soma_ant_s[0]);
$saldo_ant_print = number_format($saldo_ant, 2, ",", ".");
// ================================================================================================================


// ====== ATUALIZA SALDO ARMAZENADO =====================================================================
include('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_geral_produto;
include('../../includes/atualisa_saldo_armaz.php');
// ======================================================================================================


// ================================================================================================================
include("../../includes/head.php");
?>

<!-- ====== TÍTULO DA PÁGINA =========================================================================================== -->
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
		<?php include("../../includes/menu_compras.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_compras_ficha_produtor.php"); ?>
	</div>


	<!-- ====== CENTRO GERAL =================================================================================== -->
	<div id="centro_geral">
		<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>
		<!-- ======================================================================================================= -->


		<!-- ======================================================================================================= -->
		<div id="centro" style="height:70px; width:1080px; border:0px solid #000; margin:auto">

			<!-- ======================================================================================================= -->
			<div style="height:70px; width:700px; border:0px solid #000; float:left">
				<div id="titulo_form_1" style="width:648px; height:30px; float:left; border:0px solid #000; margin-left:50px">
					Ficha do Produtor - Movimenta&ccedil;&atilde;o
				</div>

				<div style="width:648px; height:10px; float:left; border:0px solid #000; margin-left:50px">
				</div>


				<?php
				if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4) {
					echo "
						<div id='titulo_form_3' style='width:130px; height:28px; float:left; border:0px solid #000; margin-left:50px'>
						<form name='volta' action='$servidor/$diretorio_servidor/compras/ficha_produtor/seleciona_produtor.php' method='post'>
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_3.png' border='0' />
						</form>
						</div>
						<div id='titulo_form_3' style='width:514px; height:28px; float:left; border:0px solid #000; margin-left:0px; margin-top:2px'>
						$msg_erro
						</div>";
				} else {
					echo "
						<div style='width:648px; height:28px; border:0px solid #000; float:left; font-size:18px; margin-left:50px; color:#00F'>
						<b>$fornecedor_print</b>
						</div>";
				}
				?>

			</div>
			<!-- ======================================================================================================= -->


			<!-- ======================================================================================================= -->
			<div style="height:70px; width:300px; float:right; border:0px solid #999; border-radius:0px; background-color:#EEE; box-shadow:4px 4px 6px #666; margin-right:40px">
				<div style="height:17px; width:300px; border:0px solid #000; float:left; text-align:center; font-size:10px; color:#009900; margin-top:3px">
					<?php
					if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4) {
						echo "";
					} else {
						echo "Saldo:";
					}
					?>
				</div>

				<div style="height:32px; width:300px; border:0px solid #000; float:left; text-align:center">
					<?php
					if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4) {
						echo "";
					} elseif ($saldo_geral_produto < 0) {
						echo "
							<div style='font-size:20px; color:#FF0000'>
								$saldo_geral_produto_print $unidade_print
							</div>";
					} else {
						echo "
							<div style='font-size:20px; color:#0000FF'>
								$saldo_geral_produto_print $unidade_print
							</div>";
					}
					?>
				</div>

				<div style="height:17px; width:300px; border:0px solid #000; float:left; text-align:center; font-size:10px; color:#009900">
					<?php
					if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4) {
						echo "";
					} else {
						echo "de $produto_print_2";
					}
					?>
				</div>

			</div>
			<!-- ======================================================================================================= -->

		</div>
		<!-- ======================================================================================================= -->


		<!-- ======================================================================================================= -->
		<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
		<!-- ============================================================================================================= -->


		<!-- ====== DADOS DO FORNECEDOR ============================================================================= -->
		<div id="centro" style="width:1080px; height:25px; border:1px solid #999; border-radius:5px; overflow:hidden; margin:auto">

			<div style="width:320px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-top:5px; margin-left:50px">
				<div style="color:#003466; float:left"><?php echo "<b>$cpf_cnpj</b>"; ?></div>
			</div>

			<div style="width:320px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-top:5px; color:003466; text-align:center">
				<div style="color:#003466"><?php echo "<b>$telefone_fornecedor</b>"; ?></div>
			</div>

			<div style="width:320px; height:15px; border:0px solid #000; float:right; font-size:11px; margin-top:5px; margin-right:50px">
				<div style="color:#003466; float:right"><?php echo "<b>$cid_uf_fornecedor</b>"; ?></div>
			</div>

		</div>
		<!-- ======================================================================================================= -->


		<div style="width:1080px; height:5px; border:0px solid #000; margin:auto"></div>


		<!-- ====== CONTROLE DE TALAO E CONTRATOS ================================================================== -->
		<div id="centro" style="height:25px; width:1080px; border:0px solid #000; margin:auto">
			<div id="centro" style="height:25px; width:30px; border:0px solid #000; float:left"></div>

			<div id="centro" style="height:25px; width:320px; border:0px solid #000; color:#FF0000; font-size:12px; float:left">
				<div id="geral" style="width:318px; float:left; border:0px solid #999; text-align:center; margin-top:6px">
					<?php
					// ====== CONTROLE DE TALAO  ==========================================================================================
					if ($linha_talao == 0) {
						echo "
							<font style='text-decoration:none; color:#E8E8E8; margin-top:3px'>&#160;<i>(Sem pend&ecirc;ncia de tal&atilde;o)</i></font>";
					} elseif ($linha_talao == 1) {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post' name='talao' target='_blank' />
							<input type='hidden' name='botao' value='ficha' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/buscar.png' height='20px' title='Consultar Tal&atilde;o' style='float:left' />
							</form>
							<font style='text-decoration:none; color:#FF0000; margin-top:2px'>&#160;<i>Este produtor possui 1 tal&atilde;o impresso.</i></font>";
					} else {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post' name='talao' target='_blank' />
							<input type='hidden' name='botao' value='ficha' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/buscar.png' height='20px' title='Consultar Tal&atilde;o' style='float:left' />
							</form>
							<font style='text-decoration:none; float:left; color:#FF0000; margin-top:2px'>&#160;<i>Este produtor possui $linha_talao tal&otilde;es impressos.</i></font>";
					}
					?>

				</div>
			</div>

			<div id="centro" style="height:25px; width:30px; border:0px solid #000; float:left"></div>

			<div id="centro" style="height:25px; width:320px; border:0px solid #000; color:#003466; font-size:12px; float:left">
				<div id="geral" style="width:318px; float:left; border:0px solid #999; text-align:center; margin-top:6px">
					<?php
					// ====== CONTROLE DE CONTRATO FUTURO  ==========================================================================================
					if ($linha_futuro == 0) {
						echo "<font style='text-decoration:none; color:#E8E8E8'><i>(Sem Contrato Futuro)</i></font>";
					} else {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/relatorio_fornecedor.php' method='post' target='_blank' />
							<input type='hidden' name='fornecedor_form' value='$fornecedor' />
							<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/buscar.png' height='20px' title='Consultar Contrato Futuro' style='float:left' />
							</form>
							<font style='color:#FF0000; margin-top:2px'>&#160;<i>Este produtor possui Contrato Futuro.</i></font>";
					}
					?>
				</div>
			</div>

			<div id="centro" style="height:25px; width:30px; border:0px solid #000; float:left"></div>

			<div id="centro" style="height:25px; width:320px; border:0px solid #000; color:#003466; font-size:12px; float:left">
				<div id="geral" style="width:318px; float:left; border:px solid #999; text-align:center; margin-top:6px">
					<?php
					// ====== CONTROLE DE CONTRATO TRATADO  ==========================================================================================
					if ($linha_tratado == 0) {
						echo "<font style='text-decoration:none; color:#E8E8E8'><i>(Sem Contrato Tratado)</i></font>";
					} else {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_produtor.php' method='post' target='_blank' />
							<input type='hidden' name='produtor' value='$fornecedor' />
							<input type='hidden' name='monstra_situacao' value='produtor' />
							<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/buscar.png' height='20px' title='Consultar Contrato Tratado' style='float:left' />
							</form>
							<font style='color:#FF0000; margin-top:2px'>&#160;<i>Este produtor possui Contrato Tratado.</i></font>";
					}
					?>
				</div>
			</div>
		</div>
		<!-- ======================================================================================================= -->


		<!-- ====== LEMBRETE E INFORMAÇÃO ========================================================================== -->
		<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
			<?php

			echo "
				<div id='centro' style='height:30px; width:35px; border:0px solid #000; float:left'></div>
				<div id='centro' style='height:auto; width:950px; border:0px solid #000; color:#FF0000; font-size:12px; float:left'>
					<font style='float:left; color:#FF0000; margin-top:5px'><i><b>$obs_print</b></i></font>
				</div>";
			?>
		</div>
		<!-- ======================================================================================================= -->


		<!-- ====== FILTRO DE BUSCA E BOTÕES ======================================================================= -->
		<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">

			<div id="centro" style="height:36px; width:1080px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left; margin-left:0px">
				<div id="centro" style="width:25px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right"></div>

				<div id="centro" style="width:75px; float:left; color:#666; border:0px solid #999; text-align:right; margin-top:11px">
					<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/movimentacao_produtor.php" method="post" />
					<input type='hidden' name='botao' value='1' />
					<input type='hidden' name='fornecedor' value='<?php echo "$fornecedor"; ?>' />
					<i>Data inicial:&#160;</i>
				</div>

				<div id="centro" style="width:100px; float:left; border:0px solid #999; text-align:left; margin-top:7px">
					<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px; text-align:center" value="<?php echo "$data_inicial_aux"; ?>" />
				</div>

				<div id="centro" style="width:75px; float:left; color:#666; border:0px solid #999; text-align:right; margin-top:11px">
					<i>Data final:&#160;</i>
				</div>

				<div id="centro" style="width:100px; float:left; border:0px solid #999; text-align:left; margin-top:7px">
					<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px; text-align:center" value="<?php echo "$data_final_aux"; ?>" />
				</div>

				<div id="centro" style="width:75px; float:left; color:#666; border:0px solid #999; text-align:right; margin-top:11px">
					<i>Produto:&#160;</i>
				</div>

				<div id="centro" style="width:160px; float:left; border:0px solid #999; text-align:left; margin-top:7px">
					<select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:150px; height:21px; font-size:12px; text-align:left" />
					<option></option>
					<?php
					$busca_produto_list = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
					$linhas_produto_list = mysqli_num_rows($busca_produto_list);

					for ($j = 1; $j <= $linhas_produto_list; $j++) {
						$aux_produto_list = mysqli_fetch_row($busca_produto_list);
						if ($aux_produto_list[0] == $cod_produto) {
							echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
						} else {
							echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
						}
					}
					?>
					</select>
				</div>




				<div id="centro" style="width:70px; float:left; border:0px solid #999; text-align:left; margin-top:8px">
					<input type="image" src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" height="20px" style="float:left" />
					</form>
				</div>


				<div id="centro" style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:6px">
					<?php
					if ($permissao[84] == 'S') {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='hidden' name='cod_produto' value='$cod_produto' />
							<input type='hidden' name='pagina_mae' value='$pagina' />
							<button type='submit' class='botao_1' style='margin-left:10px'>Compra</button>
							</form>";
					} else {
					}
					?>
				</div>


				<div id="centro" style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:6px">
					<?php
					if ($permissao[31] == 'S') {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona.php' method='post' />
							<input type='hidden' name='aux_cod_produtor' value='$cod_produtor' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='hidden' name='cod_produto' value='$cod_produto' />
							<button type='submit' class='botao_1' style='margin-left:10px'>Entrada</button>
							</form>";
					} else {
					}
					?>
				</div>

				<div id="centro" style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:6px">
					<?php
					if ($permissao[42] == 'S') {
						echo "
							<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao.php' method='post' target='_blank' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='hidden' name='cod_produto' value='$cod_produto' />
							<button type='submit' class='botao_1' style='margin-left:10px'>Tal&atilde;o</button>
							</form>";
					} else {
					}
					?>
				</div>


			</div>

		</div>
		<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>
		<!-- ======================================================================================================= -->


		<!-- ====== BOTÃO IMPRIMIR E TOTAIS ======================================================================== -->
		<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

		<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
			<div id="centro" style="width:140px; float:left; height:25px; margin-left:10px; border:0px solid #999; color:#009900; font-size:12px">
				<?php
				if ($linha_compra >= 1) {
					echo "
						<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/extrato_ficha_impressao.php' target='_blank' method='post'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='botao' value='extrato'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>	
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='mostra_cancelada' value='$mostra_cancelada'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir</button>
						</form>";
				} else {
					echo "";
				}
				?>
			</div>

			<div id="centro" style="width:170px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
				<?php
				if ($linha_compra == 1) {
					echo "<b>$linha_compra</b> Registro";
				} elseif ($linha_compra == 0) {
					echo "";
				} else {
					echo "<b>$linha_compra</b> Registros";
				}
				?>
			</div>

			<div id="centro" style="width:228px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
				<?php
				if ($linha_compra >= 1) {
					echo "Entradas: <b>$quant_produto_e_print</b> $unidade_print";
				} else {
				}
				?>
			</div>

			<div id="centro" style="width:286px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
				<?php
				if ($linha_compra >= 1) {
					echo "Compras / Transfer&ecirc;ncias: <b>$quant_produto_s_print</b> $unidade_print";
				} else {
				}
				?>
			</div>

			<div id="centro" style="width:228px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
				<?php
				if ($linha_compra >= 1) {
					echo "Total de Compras: <b>R$ $soma_compras_print</b>";
				} else {
				}
				?>
			</div>
		</div>
		<!-- ======================================================================================================= -->


		<!-- ======================================================================================================= -->
		<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

		<?php
		$busca_tipo = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' ORDER BY codigo");
		$linha_tipo = mysqli_num_rows($busca_tipo);

		for ($t = 1; $t <= $linha_tipo; $t++) {
			$aux_tipo = mysqli_fetch_row($busca_tipo);


			$soma_tipo_entrada = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_tipo='$aux_tipo[0]'"));

			$soma_tipo_saida = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_tipo='$aux_tipo[0]'"));

			$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
			$saldo_tipo_print = number_format($saldo_tipo, 2, ",", ".");

			// ===================================================================================================================================
			if ($saldo_tipo == 0) {
				echo "";
			} else {
				echo "

					<div id='centro' style='height:27px; width:1080px; margin:auto; border:0px solid #999'>
						<div id='centro' style='height:25px; width:380px; border:0px solid #009900; border-radius:0px; background-color:#EEE; box-shadow:4px 4px 6px #666; float:left'>
							
							<div id='centro' style='height:15px; width:160px; margin-left:30px; margin-top:4px; border:0px solid #999; 
							float:left; text-align:left; font-size:12px; color:#009900'>
							Tipo: $aux_tipo[1]
							</div>";

				if ($saldo_tipo < 0)
					echo "
						<div id='centro' style='height:15px; width:160px; margin-left:5px; margin-top:4px; border:0px solid #999; float:left; 
						text-align:left; font-size:11px; color:#FF0000'>
							Saldo: $saldo_tipo_print $unidade_print
						</div>";
				else
					echo "
						<div id='centro' style='height:15px; width:160px; margin-left:5px; margin-top:4px; border:0px solid #999; float:left; 
						text-align:left; font-size:11px; color:#0000FF'>
							Saldo: $saldo_tipo_print $unidade_print
						</div>";

				echo "
					</div>
					<div id='centro' style='height:25px; width:500px; border:0px solid #999; border-radius:5px; float:left'>
					</div>
				</div>
				<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>";
			}
		}

		?>


		<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

		<!-- ====================================================================================== -->

		<?php
		if ($linha_compra == 0) {
			echo "<div id='centro_3'>
				<div id='centro' style='height:210px'>";
		} else {
			echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
				<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";
		}
		?>

		<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

		<?php
		if ($linha_compra == 0) {
			echo "";
		} else {
			echo "
				<table border='0' align='center' style='color:#FFF; font-size:9px'>
					<tr>
					<td width='80px' align='center' bgcolor='#006699'>Data</td>
					<td width='150px' align='center' bgcolor='#006699'>Movimenta&ccedil;&atilde;o</td>
					<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
					<td width='60px' align='center' bgcolor='#006699'>Sankhya</td>
					<td width='100px' align='center' bgcolor='#006699'>Produto</td>
					<td width='90px' align='center' bgcolor='#006699'>Tipo</td>
					<td width='85px' align='center' bgcolor='#006699'>Quantidade</td>
					<td width='85px' align='center' bgcolor='#006699'>Pre&ccedil;o Un</td>
					<td width='95px' align='center' bgcolor='#006699'>Valor Total</td>
					<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
					<td width='54px' align='center' bgcolor='#006699'>Editar</td>
					<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
					<td width='54px' align='center' bgcolor='#006699'>Pgto</td>
					</tr>
				</table>";
		}

		if ($linha_compra == 0) {
			echo "";
		} else {
			echo "
				<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>
					<tr style='color:#666'>
					<td width='80px' align='left'></td>
					<td width='150px' align='left'><div style='margin-left:3px'><i>SALDO ANTERIOR</i></div></td>
					<td width='60px' align='center'></td>
					<td width='60px' align='center'></td>
					<td width='100px' align='center'></td>
					<td width='90px' align='center'></td>
					<td width='85px' align='center'>$saldo_ant_print $unidade_print</td>
					<td width='85px' align='right'></td>
					<td width='95px' align='right'></td>
					<td width='54px' align='center'></td>
					<td width='54px' align='center'></td>
					<td width='54px' align='center'></td>
					<td width='54px' align='center'></td>
					</tr>
				";
		}

		// ====== FUNÇÃO FOR ===================================================================================
		$saldo_pagar_total = 0;
		$quant_entregar_aux = 0;
		$numero_compra = '';

		for ($x = 1; $x <= $linha_compra; $x++) {
			$aux_compra = mysqli_fetch_row($busca_compra);

			//$produto = $aux_compra[3];
			//$cod_produto = $aux_compra[39];
			//$unidade = $aux_compra[8];
			//$unidade_print = $aux_compra[8];
			//$fornecedor = $aux_compra[2];
			$numero_compra = $aux_compra[1];
			$idContratoSankhya = $aux_compra[55] ?? '';
			$data_compra = $aux_compra[4];
			$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
			$quantidade = $aux_compra[5];
			$quantidade_print = number_format($aux_compra[5], 2, ",", ".");
			$preco_unitario = $aux_compra[6];
			$preco_unitario_print = number_format($aux_compra[6], 2, ",", ".");
			$valor_total = $aux_compra[7];
			$valor_total_print = number_format($aux_compra[7], 2, ",", ".");
			$safra = $aux_compra[9];
			$tipo = $aux_compra[10];
			$cod_tipo = $aux_compra[41];
			$broca = $aux_compra[11];
			$umidade = $aux_compra[12];
			$situacao = $aux_compra[17];
			$situacao_pgto = $aux_compra[15];
			$movimentacao = $aux_compra[16];
			$observacao = $aux_compra[13];
			$numero_romaneio = $aux_compra[28];
			$desc_quant = number_format($aux_compra[29], 2, ",", ".");
			$numero_transferencia = $aux_compra[30];
			$usuario_cadastro = $aux_compra[18];
			$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
			$hora_cadastro = $aux_compra[19];
			$usuario_alteracao = $aux_compra[21];
			if ($aux_compra[23] == "") {
				$data_alteracao = "";
			} else {
				$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));
			}
			$hora_alteracao = $aux_compra[22];
			// ======================================================================================================


			// ====== MOVIMENTACAO PRINT ===================================================================================
			include('inc_movimentacao_print.php');
			// ======================================================================================================


			// ====== BUSCA PAGAMENTO  ===============================================================================
			$acha_pagamento = mysqli_query($conexao, "SELECT * FROM favorecidos_pgto WHERE codigo_compra='$aux_compra[1]' AND estado_registro!='EXCLUIDO' ORDER BY codigo");
			$linha_acha_pagamento = mysqli_num_rows($acha_pagamento);
			$soma_pagamentos = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$aux_compra[1]' AND estado_registro!='EXCLUIDO' AND situacao_pagamento='PAGO'"));
			$soma_pagamentos_print = number_format($soma_pagamentos[0], 2, ",", ".");
			// ======================================================================================================


			// ====== CALCULO SALDO A PAGAR  ==========================================================================
			$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
			$saldo_a_pagar = number_format($saldo_a_pagar_aux, 2, ",", ".");
			$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
			$saldo_pagar_total_print = number_format($saldo_pagar_total, 2, ",", ".");
			// ======================================================================================================


			// ====== CALCULA QUANTIDADE A ENTREGAR ===================================================================
			if ($movimentacao == "COMPRA") {
				$quant_entregar = $saldo_a_pagar_aux / $preco_unitario;
				$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
				$quant_entregar_print = number_format($quant_entregar_aux, 2, ",", ".");
				$quant_ref_print = number_format($quant_entregar, 2, ",", ".");
			} else {
			}
			// ======================================================================================================


			// ====== SE FOR COMPRA  =================================================================================
			if ($movimentacao == "COMPRA") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				if ($soma_pagamentos[0] < $valor_total) {
					echo "<tr style='color:#006600' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar (ref. a $quant_ref_print $unidade_print)&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";
				} else {
					echo "<tr style='color:#FF0000' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";
				}

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'>$preco_unitario_print</div></td>
					<td width='95px' align='right'><div style='margin-right:3px'>$valor_total_print</div></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "
					<td width='54px' align='center'>
					<form action='$servidor/$diretorio_servidor/compras/produtos/compra_visualizar.php' method='post'>
					<input type='hidden' name='numero_compra' value='$numero_compra'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='pagina_filha' value='movimentacao'>
					<input type='hidden' name='botao' value='1'>
					<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
					<input type='hidden' name='data_final' value='$data_final_aux'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				if ($permissao[65] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				if ($linha_acha_pagamento == 0 and $permissao[30] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post'>
					<input type='hidden' name='numero_compra' value='$numero_compra'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='pagina_filha' value='movimentacao'>
					<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
					<input type='hidden' name='data_final' value='$data_final_aux'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='cod_tipo' value='$cod_tipo'>
					<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/financeiro_2.png' height='20px' /></form>	
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ======================================================================================================


			// ====== SE FOR ENTRADA  =================================================================================
			elseif ($movimentacao == "ENTRADA") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#0000FF' title='N&ordm; Romaneio: $numero_romaneio&#013;Desconto Quantidade: $desc_quant $unidade_print&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";

				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "
					<td width='54px' align='center'>
				<!--	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_visualizar.php' method='post'> -->
					<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/romaneio_entrada_visualizar.php' method='post'>
					<input type='hidden' name='numero_compra' value='$numero_compra'>
					<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
					<input type='hidden' name='pagina_mae' value='$pagina'>
					<input type='hidden' name='pagina_filha' value='movimentacao'>
					<input type='hidden' name='botao' value='1'>
					<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
					<input type='hidden' name='data_final' value='$data_final_aux'>
					<input type='hidden' name='cod_produto' value='$cod_produto'>
					<input type='hidden' name='fornecedor' value='$fornecedor'>
					<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				if ($permissao[65] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				if ($linha_acha_pagamento == 0 and $permissao[30] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ======================================================================================================


			// ====== SE FOR ENTRADA_FUTURO  ========================================================================
			elseif ($movimentacao == "ENTRADA_FUTURO") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#0000FF' title='N&ordm; Contrato: $numero_transferencia&#013; Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";
					
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "
					<td width='54px' align='center'>
					<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_impressao.php' method='post' target='_blank'>
					<input type='hidden' name='numero_contrato' value='$numero_transferencia'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				if ($permissao[65] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ======================================================================================================


			// ====== SE FOR TRANSFERENCIA_SAIDA  ====================================================================
			elseif ($movimentacao == "TRANSFERENCIA_SAIDA" or $movimentacao == "SAIDA") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#FF0000' title='N&ordm;: $numero_transferencia&#013; Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ========================================================================================================


			// ====== SE FOR SAIDA_FUTURO  ============================================================================
			elseif ($movimentacao == "SAIDA_FUTURO") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#FF0000' title='N&ordm; Contrato: $numero_transferencia&#013; Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "
					<td width='54px' align='center'>
					<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_impressao.php' method='post' target='_blank'>
					<input type='hidden' name='numero_contrato' value='$numero_transferencia'>
					<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				if ($permissao[65] == 'S') {
					echo "
						<td width='54px' align='center'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
						<input type='hidden' name='pagina_mae' value='$pagina'>
						<input type='hidden' name='pagina_filha' value='movimentacao'>
						<input type='hidden' name='botao_relatorio' value='relatorio'>
						<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
						<input type='hidden' name='data_final' value='$data_final_aux'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_tipo' value='$cod_tipo'>
						<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
						<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' /></form>	
						</td>";
				} else {
					echo "<td width='54px' align='center'></td>";
				}
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ======================================================================================================


			// ====== SE FOR TRANSFERENCIA_ENTRADA  ====================================================================
			elseif ($movimentacao == "TRANSFERENCIA_ENTRADA") {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#0000FF' title='N&ordm; Transfer&ecirc;ncia: $numero_transferencia&#013; Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				echo "<td width='54px' align='center'></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// =======================================================================================================


			// ====== SE NÃO ==========================================================================================
			else {

				// ------ RELATORIO -----------------------------------------------------------------------------------
				echo "<tr style='color:#000' title='N&ordm; $numero_compra&#013; Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";

				echo "
					<td width='80px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
					<td width='150px' align='left'><div style='margin-left:3px'>$movimentacao_print</div></td>
					<td width='60px' align='center'>$numero_compra</td>
					<td width='60px' align='center'>$idContratoSankhya</td>
					<td width='100px' align='center'>$produto_print_2</td>
					<td width='90px' align='center'>$tipo</td>
					<td width='85px' align='center'>$quantidade_print $unidade_print</td>
					<td width='85px' align='right'><div style='margin-right:3px'></div></td>
					<td width='95px' align='right'><div style='margin-right:3px'></div></td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO VIZUALIZAR ------------------------------------------------------------------------------
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EDITAR --------------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO EXCLUIR --------------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ------ BOTAO PAGAMENTOS ----------------------------------------------------------------------------	
				echo "
					<td width='54px' align='center'>
					</td>";
				// ----------------------------------------------------------------------------------------------------


				// ----------------------------------------------------------------------------------------------------
				echo "</tr>";
			}
			// ============================================================================================================


		} // ====== FIM DO FOR ======


		echo "</table>";
		// ============================================================================================================
		?>




		<?php
		if ($linha_compra == 0 /*and $botao == "1"*/) {
			echo "
				<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>N&atilde;o existem movimenta&ccedil;&otilde;es neste per&iacute;odo.</i></div>";
		} else {
		}
		?>



		<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
	</div><!-- FIM DIV centro_4 -->
	<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
	<!-- ======================================================================================================== -->
	</div><!-- FIM DIV centro_3 -->




	<div id="centro" style="height:32px; width:1080px; border:0px solid #000; margin:auto">
		<div id="centro" style="height:20px; width:350px; border:0px solid #000; float:left; font-size:11px; color:#003466">
		</div>

		<div id="centro" style="height:20px; width:300px; border:0px solid #000; float:left; font-size:11px; color:#003466">
		</div>

		<div id="centro" style="height:20px; width:400px; border:0px solid #000; float:left; font-size:11px; color:#003466">
			<?php
			// BUSCA PAGAMENTO GERAL  ==========================================================================================
			$acha_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE filial='$filial' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND estado_registro!='EXCLUIDO' AND movimentacao='COMPRA' ORDER BY codigo");
			$linha_acha_compra = mysqli_num_rows($acha_compra);
			$acumulativo_saldo = 0;
			$acumulativo_ref_quant = 0;

			for ($so = 1; $so <= $linha_acha_compra; $so++) {
				$aux_acha_compra = mysqli_fetch_row($acha_compra);

				$soma_pgto = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$aux_acha_compra[1]' AND  estado_registro!='EXCLUIDO' AND forma_pagamento!='PREVISAO'"));

				$saldo_pgto = $aux_acha_compra[7] - $soma_pgto[0];
				$ref_quant = $saldo_pgto / $aux_acha_compra[6];

				$acumulativo_saldo = $acumulativo_saldo + $saldo_pgto;
				$acumulativo_ref_quant = $acumulativo_ref_quant + $ref_quant;
			}

			$saldo_pgto_print = number_format($acumulativo_saldo, 2, ",", ".");
			$ref_quant_print = number_format($acumulativo_ref_quant, 2, ",", ".");

			echo "Saldo Total a Pagar: R$ $saldo_pgto_print (Referente a $ref_quant_print $unidade_print)";
			?>
		</div>
	</div>


	<div id="centro" style="height:32px; width:1080px; border:0px solid #000; margin:auto">
		<?php
		/*
		// ---- Botão para atualizar saldo armazenado ---------------------------------------------------------------------

		if ($permissao[61] == 'S')
		{
		$cod_forne = $produtor_ficha;
		include ('../../includes/busca_saldo_armaz.php');
		$saldo_atual = $saldo_produtor;
		echo "
			<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
			<input type='hidden' name='botao' value='atualiza_saldo' />
			<input type='hidden' name='representante' value='$produtor_ficha'>
			<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
			<input type='hidden' name='data_final' value='$data_final_aux'>
			<input type='hidden' name='produto_list' value='$produto_list'>
			<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/atualizar.png' border='0' title='Saldo Relat&oacute;rio Armazenado: $saldo_produtor $unidade_print' /></form>";
		}
		else
		{}
		// ----------------------------------------------------------------------------------------------------------------
		*/
		?>
	</div>

	<!-- ====================================================================================== -->
	</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
	<!-- ====================================================================================== -->

	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>