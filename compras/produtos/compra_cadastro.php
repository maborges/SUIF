<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "compra_cadastro";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";



// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"] ?? '';
$idSankhya = $_POST['idSankhya'] ?? '';
$pedidoSankhya = $_POST['pedidoSankhya'] ?? '';
$cod_produto = $_POST["cod_produto"] ?? '';
$botao = $_POST["botao"] ?? '';
$quantidade = $_POST["quantidade"] ?? '0';
$preco_unitario = $_POST["preco_unitario"] ?? '0';
$safra = $_POST["safra"] ?? '';
$cod_tipo = $_POST["cod_tipo"] ?? '';
$umidade = $_POST["umidade"] ?? '';
$broca = $_POST["broca"] ?? '';
$impureza = $_POST["impureza"] ?? '';
$data_pagamento = $_POST["data_pagamento"] ?? '';
$observacao = $_POST["observacao"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$situacao_compra = $_POST["situacao_compra"] ?? '';
$filial = $filial_usuario;
$erro = 0;
$msg_erro = "";
$linhas_ultima_compra = 0;

// ========================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==========================================================================
if ($pagina_mae == "movimentacao_produtor") {
	$busca_num_compra = mysqli_query($conexao, "SELECT contador_numero_compra FROM configuracoes");
	$aux_bnc = mysqli_fetch_row($busca_num_compra);
	$numero_compra = $aux_bnc[0];

	$contador_num_compra = $numero_compra + 1;
	$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
} else {
	//$numero_compra = $_POST["numero_compra"];

	$busca_num_compra = mysqli_query($conexao, "SELECT contador_numero_compra FROM configuracoes");
	$aux_bnc = mysqli_fetch_row($busca_num_compra);
	$numero_compra = $aux_bnc[0];

	$contador_num_compra = $numero_compra + 1;
	$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
}
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT descricao, produto_print, unidade_print, id_sankhya FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[0];
$produto_print_2 = $aux_bp[1];
$unidade_print = $aux_bp[2];
$idProdutoSankhya = $aux_bp[3];
// ======================================================================================================


// ====== BUSCA TOPS  ============================================================================
$busca_tops = mysqli_query($conexao, "SELECT tops_requisicao FROM tipo_operacao_produto WHERE tipo_operacao='ECPR' AND produto_sankhya='$idProdutoSankhya'");
$idTOPS = mysqli_fetch_row($busca_tops)[0];

// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT nome, situacao_compra, id_sankhya FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[0];
$situacao_compra_print = $aux_forn[1];
$idSankhya = $aux_forn[2];

$situacao_compra_w = 'ANALISE';
$color_bg_w = "#FFFF00";

if ($situacao_compra_print == 0) {
	$situacao_compra_w = 'LIBERADA';
	$color_bg_w = "#7FFF00";
} elseif ($situacao_compra_print == 2) {
	$situacao_compra_w = 'BLOQUEADA';
	$color_bg_w = "#FF0000";
}

/*
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf" or $aux_forn[2] == "PF")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}

if ($linhas_fornecedor == 0)
{$cidade_uf_fornecedor = "";}
else
{$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";}
*/
// ======================================================================================================


// ====== BUSCA SALDO ARMAZENADO ========================================================================
$busca_saldo_arm = mysqli_query($conexao, "SELECT saldo FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
$linhas_saldo_arm = mysqli_num_rows($busca_saldo_arm);
$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
$saldo_armazenado_print = number_format($aux_saldo_arm[0], 2, ",", ".");
// ======================================================================================================


// ====== BUSCA ULTIMA COMPRA ========================================================================

$busca_ultima_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE fornecedor='$fornecedor' AND filial='$filial_usuario' AND cod_produto='$cod_produto' AND estado_registro='ATIVO' AND movimentacao='COMPRA' ORDER BY codigo DESC LIMIT 1");
$aux_ultima_compra = mysqli_fetch_row($busca_ultima_compra);
$linhas_ultima_compra = mysqli_num_rows($busca_ultima_compra);

$data_uc = date('d/m/Y', strtotime($aux_ultima_compra[4]));
$quant_uc = number_format($aux_ultima_compra[5], 2, ",", ".");
$preco_uc = number_format($aux_ultima_compra[6], 2, ",", ".");
$valor_uc = number_format($aux_ultima_compra[7], 2, ",", ".");

// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
/*
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
*/
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($botao == "selecionar") {
	if ($fornecedor == "") {
		$erro = 1;
		$msg_erro = "* Selecione um fornecedor";
	} elseif ($linhas_fornecedor == 0) {
		$erro = 2;
		$msg_erro = "* Fornecedor inválido";
	} elseif ($idSankhya == "") {
		$erro = 3;
		$msg_erro = "* Fornecedor Sankhya não informado no cadastro";
	} elseif ($idProdutoSankhya == "") {
		$erro = 4;
		$msg_erro = "* Código do produto Sankhya não informado no cadastro";
	} elseif ($cod_produto == "") {
		$erro = 5;
		$msg_erro = "* Selecione um produto";
	} elseif ($linhas_bp == 0) {
		$erro = 6;
		$msg_erro = "* Produto inválido";
	} elseif ($situacao_compra_print == 2) {
		$erro = 7;
		$msg_erro = "* Produtor bloqueado para compras";
	} else {
		$erro = 0;
		$msg_erro = "";
	}
} else {
}
// ======================================================================================================


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
		<?php include("../../includes/submenu_compras_compras.php"); ?>
	</div>




	<!-- =============================================   C E N T R O   =============================================== -->
	<div id="centro_geral">
		<div id="centro" style="height:440px; width:950px; border:0px solid #000; margin:auto">
			<form name="compra" action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/produtos/compra_enviar.php" method="post">
			<input type="hidden" name="botao" value="compra_cadastro" />
			<input type="hidden" name="fornecedor" value="<?php echo "$fornecedor"; ?>" />
			<input type="hidden" name="idSankhya" value="<?php echo "$idSankhya"; ?>" />
			<input type="hidden" name="pedidoSankhya" value="<?php echo "$pedidoSankhya"; ?>" />
			<input type="hidden" name="idProdutoSankhya" value="<?php echo "$idProdutoSankhya"; ?>" />
			<input type="hidden" name="cod_produto" value="<?php echo "$cod_produto"; ?>" />
			<input type="hidden" name="numero_compra" value="<?php echo "$numero_compra"; ?>" />

			<div style="width:950px; height:15px; float:left; border:0px solid #000"></div>
			<!-- ============================================================================================================= -->


			<!-- ============================================================================================================= -->
			<div style="width:950px; height:30px; float:left; border:0px solid #000">
				<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:140px">
					<?php
					if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4 or $erro == 5) {
						echo "Compra";
					} else {
						echo "Compra de $produto_print_2";
					}
					?>
				</div>
			</div>

			<div style="width:950px; height:10px; float:left; border:0px solid #000"></div>
			<!-- ============================================================================================================= -->


			<!-- ============================================================================================================= -->
			<div style="width:950px; height:20px; float:left; border:0px solid #000">
				<div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:140px">
					<?php echo "$msg_erro"; ?>
				</div>
			</div>

			<div style="width:950px; height:10px; float:left; border:0px solid #000"></div>
			<!-- ============================================================================================================= -->


			<!-- ====================================================================================== -->
			<div style="width:140px; height:360px; border:0px solid #000; float:left">
			</div>


<!-- ################################# inicio sankhya   -->

			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Fornecedor Sankhya:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Produto Sankhya:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Código TOPS:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Pedido Sankhya:</div>
			</div>


			<!-- =========================================  CODIGO SANKHYA ================================== -->
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input disabled type="number" name="idSankhya" style="font-size:12px; font-weight:bold; width:145px" value=<?=$idSankhya?>>
			</div>

			<!-- =========================================  PRODUTO SANKHYA  ================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input disabled type="number" name="idProdutoSankhya"  style="font-size:12px; font-weight:bold; width:145px" value=<?=$idProdutoSankhya?>>
			</div>

			<!-- =========================================  TOPS  ================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input disabled type="number" name="idTOPS" id="idTOPS" style="font-size:12px; font-weight:bold; width:145px" value=<?=$idTOPS ?>>
			</div>

			<!-- =========================================  PEDIDO SANKHYA  ================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input disabled type="number" name="pedidoSankhya " id="pedidoSankhya " style="font-size:12px; font-weight:bold; width:145px" value=<?=$pedidoSankhya ?>>
			</div>
			<!-- ========================================================================================================== -->

    <!-- ###################################fim sankhya -->



			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Número da Compra:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:330px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:325px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Fornecedor:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Situação:</div>
			</div>

			<!-- =========================================  CODIGO ====================================== -->
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input type="text" name="numero_compra_aux" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$numero_compra"; ?>" disabled="disabled" />
			</div>

			<!-- =========================================  FORNECEDOR ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:330px; border:0px solid #000">

				<!-- ========================================================================================================== -->
				<input type="text" name="fornecedor_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; font-weight:bold; width:325px" value="<?php echo "$fornecedor_print"; ?>" disabled="disabled" />

			</div>
			<!-- ========================================================================================================== -->

			<!-- =========================================  SITUAÇÃO ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">

				<!-- ========================================================================================================== -->
				<input type="text" name="situacao_compra_w" onkeydown="if (getKey(event) == 13) return false;" style="<?php echo "color: 0000FF; font-size:12px; font-weight:bold; width:145px; background-color:$color_bg_w" ?>" ; value="<?php echo "$situacao_compra_w"; ?>" disabled="disabled" />

			</div>
			<!-- ========================================================================================================== -->



			<!-- ====================================================================================== -->
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<?php
				if ($fornecedor == "" or $cod_produto == "" or $linhas_bp == 0 or $linhas_fornecedor == 0) {
					echo "Quantidade:";
				} else {
					echo "Quantidade ($unidade_print):";
				}
				?>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Pre&ccedil;o:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Safra:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Tipo:</div>
			</div>

			<!-- =========================================  QUANTIDADE ====================================== -->
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input type="text" name="quantidade" id="ok" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$quantidade"; ?>" />
			</div>

			<!-- =========================================  PREÇO UNITARIO ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input type="text" name="preco_unitario" maxlength="15" onkeypress="mascara(this,mvalor)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$preco_unitario"; ?>" />
			</div>

			<!-- ========================================= SAFRA  ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<input type="text" name="safra" maxlength="4" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo date('Y') ?>" />
			</div>

			<!-- ========================================= TIPO  ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:240px; border:0px solid #000">
				<select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" >
				<option></option>
				<?php
				$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
				$linhas_tipo_produto = mysqli_num_rows($busca_tipo_produto);

				for ($t = 1; $t <= $linhas_tipo_produto; $t++) {
					$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);

					if ($aux_tipo_produto[0] == $cod_tipo) {
						echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
					} else {
						echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
					}
				}
				?>
				</select>
			</div>


			<!-- ====================================================================================== -->
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Umidade:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Broca:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Impureza:</div>
			</div>

			<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Data Pagamento:</div>
			</div>

			<!-- =========================================  UMIDADE ====================================== -->
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<select name="umidade" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
				<option></option>
				<?php
				$busca_porcentagem = mysqli_query($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
				$linhas_porcentagem = mysqli_num_rows($busca_porcentagem);

				for ($t = 1; $t <= $linhas_porcentagem; $t++) {
					$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);
					if ($botao == "selecionar") {
						if ($aux_porcentagem[1] == "") {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					} else {
						if ($aux_porcentagem[1] == $umidade) {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					}
				}
				?>
				</select>
			</div>

			<!-- =========================================  BROCA  ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<select name="broca" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
				<option></option>
				<?php
				$busca_porcentagem = mysqli_query($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
				$linhas_porcentagem = mysqli_num_rows($busca_porcentagem);

				for ($t = 1; $t <= $linhas_porcentagem; $t++) {
					$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);
					if ($botao == "selecionar") {
						if ($aux_porcentagem[1] == "") {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					} else {
						if ($aux_porcentagem[1] == $broca) {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					}
				}
				?>
				</select>
			</div>

			<!-- ========================================= IMPUREZA  ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:150px; border:0px solid #000">
				<select name="impureza" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
				<option></option>
				<?php
				$busca_porcentagem = mysqli_query($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
				$linhas_porcentagem = mysqli_num_rows($busca_porcentagem);

				for ($t = 1; $t <= $linhas_porcentagem; $t++) {
					$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);
					if ($botao == "selecionar") {
						if ($aux_porcentagem[1] == "") {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					} else {
						if ($aux_porcentagem[1] == $impureza) {
							echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						} else {
							echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";
						}
					}
				}
				?>
				</select>
			</div>

			<!-- ========================================= DATA PAGAMENTO  ====================================== -->
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="tabela_2" style="width:240px; border:0px solid #000">
				<input type="text" name="data_pagamento" maxlength="10" onkeypress="mascara(this,data)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo date('d/m/Y') ?>" id="calendario" />
			</div>


			<!-- ============================================================================================ -->
			<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
				<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>
				<div style="float:left">Observa&ccedil;&atilde;o:</div>
			</div>

			<!-- =========================================  OBSERVAÇÃO ====================================== -->
			<div id="tabela_2" style="width:730px; border:0px solid #000">
				<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:683px" value="<?php echo "$observacao"; ?>" />
			</div>
			<!-- =============================================================================================== -->


			<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#006400">
			</div>


			<div id="geral" style="width:730px; text-align:center; border:0px solid #000; float:left; height:30px">
				<?php
				if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4 or $erro == 5) {
					echo "
						<div id='geral' style='width:728px; height:28px; text-align:center; border:0px solid #000; float:left'>
						</form>
						<form name='volta' action='$servidor/$diretorio_servidor/compras/produtos/cadastro_1_selec_produto.php' method='post'>
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='idSankhya' value='$idSankhya' />
						<input type='hidden' name='pedidoSankhya' value='$pedidoSankhya' />
						<input type='hidden' name='idProdutoSankhya' value='$idProdutoSankhya' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
						</div>
					";
				} else {
					echo "
						<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'></div>
						<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Salvar</button>
						</form>
						</div>
						<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>
						<a href='$servidor/$diretorio_servidor/compras/produtos/cadastro_1_selec_produto.php'>
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Cancelar</button></a>
						</div>
						<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>
						</div>
					";
				}
				?>
			</div>

			<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#006400">
			</div>


			<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#090">
				<?php
				if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4 or $erro == 5) {
					echo "";
				} else {
					echo "<div title=''>Saldo de armazenado do produtor: $saldo_armazenado_print $unidade_print</div>";
				}
				?>
			</div>

			<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#090">
				<?php
				if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4 or $erro == 5) {
					echo "";
				} elseif ($linhas_ultima_compra == 0) {
					echo "<div title=''>&Uacute;ltima compra do produtor:</div>";
				} else {
					echo "<div title=''>&Uacute;ltima compra do produtor: $quant_uc $unidade_print x $preco_uc = R$ $valor_uc ($data_uc)</div>";
				}
				?>
			</div>


			<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#666666">
				<?php
				/* by Borgus - Os dados para impressão não estão sendo alimentados
				if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4 or $erro == 5)
				{echo "";}
				else
				{echo "<div title='&Uacute;ltima atualiza&ccedil;&atilde;o: $data_alteracao ($usuario_alteracao)'>Pre&ccedil;o comercializado do dia: R$ $preco_maximo_print / $unidade_print</div>";}
				*/
				?>
			</div>




			<div id="geral" style="width:900px; height:20px; border:0px solid #000; float:left; font-size:12px; color:#666666">
			</div>


		</div>
	</div>




	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include("../../includes/rodape.php"); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>