<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
include("../../includes/desconecta_bd.php");
$pagina = "cadastro_2_selec_fornecedor";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$fornecedor_form = $_POST["fornecedor_form"] ?? '';
$cod_produto_form = $_POST["cod_produto_form"];
$nome_form = $_POST["nome_form"] ?? '';
$data_hoje = date('d/m/Y');

// ========================================================================================================

/*
// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================
*/

// ====== CONTADOR NÚMERO COMPRA ==========================================================================
include("../../includes/conecta_bd.php");


if ($botao == "selecionar") {
	$busca_num_compra = mysqli_query($conexao, "SELECT contador_numero_compra FROM configuracoes");
	$aux_bnc = mysqli_fetch_row($busca_num_compra);
	$numero_compra = $aux_bnc[0];

	$contador_num_compra = $numero_compra + 1;
	$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
}
// ================================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($nome_form != "") {
	$busca_pessoa_geral = mysqli_query($conexao, "SELECT codigo, nome, tipo, cpf, cnpj, cidade, estado, telefone_1, situacao_compra, id_sankhya, cadastro_validado, categoria FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome_form%' ORDER BY nome");
	$linha_pessoa_geral = mysqli_num_rows($busca_pessoa_geral);
} else {
	$busca_pessoa_geral = 0;
	$linha_pessoa_geral = 0;
}


include("../../includes/desconecta_bd.php");
// ========================================================================================================


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


	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_1">


		<!-- ============================================================================================================= -->
		<div class="espacamento_15"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1">
			<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
				<?php echo "$titulo"; ?>
			</div>

			<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; margin-top:0px; border:0px solid #000">
				<?php echo isset($produto_print_2) ? $produto_print_2 : '' ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_2">
			<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
				Selecione um fornecedor
			</div>

			<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
				<!-- xxxxxxxxxxxxxxxxxxxxxx -->
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="pqa">

			<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/produtos/cadastro_2_selec_fornecedor.php" method="post" />
			<input type="hidden" name="botao" value="selecionar" />
			<input type="hidden" name="cod_produto_form" value="<?php echo "$cod_produto_form"; ?>" />

			<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

			<div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Nome:</div>

			<div style="height:34px; width:400px; border:0px solid #999; float:left">
				<input class="pqa_input" type="text" name="nome_form" id="ok" maxlength="50" style="width:395px" value="<?php echo "$nome_form"; ?>" />
			</div>

			<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:0px">
				<button type='submit' class='botao_1'>Buscar</button>
				</form>
			</div>

		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="espacamento_40"></div>
		<!-- ============================================================================================================= -->

		<?php
		// ======================================================================================================
		if ($linha_pessoa_geral == 0) {
			echo "<div id='centro_3_relatorio'> <div id='centro' style='height:242px'>";
		} else {
			echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
				<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:5px'>";
		}

		echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

		if ($linha_pessoa_geral == 0) {
			echo "";
		} else {
			echo "
				<table border='0' align='center' style='color:#FFF; font-size:11px'>
				<tr>
				<td width='370x' height='24px' align='center' bgcolor='#006699'>Nome</td>
				<td width='90px' height='24px' align='center' bgcolor='#006699'>Id Sankhya</td>
				<td width='180px' align='center' bgcolor='#006699'>CPF/CNPJ</td>
				<td width='150px' align='center' bgcolor='#006699'>Telefone</td>
				<td width='300px' align='center' bgcolor='#006699'>Cidade/UF</td>
				<td width='100px' align='center' bgcolor='#006699'>Situação</td>
				</tr>
				</table>";
		}

		echo "<table class='tabela_geral' style='font-size:12px'>";

		$cpf_cnpj_w = '';
		
		// ====== FUNÇÃO FOR ===================================================================================
		for ($x = 1; $x <= $linha_pessoa_geral; $x++) {
			$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

			// ====== DADOS DO CADASTRO ============================================================================
			$codigo_pessoa_w = $aux_pessoa_geral[0];
			$nome_pessoa_w = $aux_pessoa_geral[1];
			$tipo_pessoa_w = $aux_pessoa_geral[2];
			$cpf_pessoa_w = $aux_pessoa_geral[3];
			$cnpj_pessoa_w = $aux_pessoa_geral[4];
			$cidade_pessoa_w = $aux_pessoa_geral[5];
			$estado_pessoa_w = $aux_pessoa_geral[6];
			$telefone_pessoa_w = $aux_pessoa_geral[7];
			$idSankhya_pessoa_w = $aux_pessoa_geral[9];
			$cadastroValidado = $aux_pessoa_geral[10] ?? 'N'; 
			$categoriaProdutor = $aux_pessoa_geral[11] ?? '';
			
			if ($tipo_pessoa_w == "PF" or $tipo_pessoa_w == "pf") {
				$cpf_cnpj_w = $cpf_pessoa_w;
			} else {
				$cpf_cnpj_w = $cnpj_pessoa_w;
			}

			$situacao_w = $aux_pessoa_geral[8];

			$situacao_compra_w = 'ANALISE';
			$color_bg_w = "#FFFF00";

			if ($situacao_w == 0) {
				$situacao_compra_w = 'LIBERADA';
				$color_bg_w = "#7FFF00";
			} elseif ($situacao_w == 2) {
				$situacao_compra_w = 'BLOQUEADA';
				$color_bg_w = "#FF0000";
			}


			// ======================================================================================================


			// ====== RELATORIO ========================================================================================
			echo "<tr class='tabela_1'>";

			$totalCompras = 0;

			if ($cadastroValidado == 'N') {
				include("../../includes/conecta_bd.php");

				try {
					$contaCompras = mysqli_query($conexao, "select count(1) 
															  from compras 
															 where fornecedor      = $codigo_pessoa_w 
															   and movimentacao    = 'COMPRA' 
															   and estado_registro = 'ATIVO'");
					$totalCompras = mysqli_fetch_row($contaCompras);

				} finally {
					include("../../includes/desconecta_bd.php");
				}
			}


			// total de compras somente será maior que zeros qdo o 
			// produtar ainda não estiver validado e já tem compras
			// segundo definição, o produtor só poderá fazem uma 
			// compra até que seu cadastro seja validado

			/*
			** 10/12/2024
			** A pedido do Rubens, deixar passar mesmo que o cadastro ainda não esteja validado,
			** então vou forçar a variável $cadastroValidado e dar a mensagem de alerta somente
			

			if ($totalCompras) {
				echo "<td width='367px' height='24px' align='left'>
						<div style='margin-left:15px' onclick='alert(\"Produtor bloqueado para novas compras até que seu cadastro seja validado! \");'>
							$nome_pessoa_w
						</div>
					</td>";

			} else
			*/

			if ($situacao_w == 2) {
				echo "<td width='367px' height='24px' align='left'>
					<div style='margin-left:15px' onclick='alert(\"Compra bloqueada para este produtor \");'>
						$nome_pessoa_w
					</div>
					</td>";
			} else {
				if (!isset($selecionar)) {$selecionar = '';}
				echo "<td width='367px' height='24px' align='left'>
					<div style='margin-left:10px'>
					<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post'>
					<input type='hidden' name='botao' value='$selecionar' />
					<input type='hidden' name='fornecedor' value='$codigo_pessoa_w' />
					<input type='hidden' name='idSankhya' value='$idSankhya_pessoa_w' />
					<input type='hidden' name='nome_fornecedor' value='$nome_pessoa_w' />
					<input type='hidden' name='cod_produto' value='$cod_produto_form' />
					<input type='hidden' name='numero_compra' value='$numero_compra' />
					<input type='hidden' name='categoriaProdutor' value='$categoriaProdutor' />
					<input class='tabela_1' type='submit' style='width:360px; height:22px; text-align:left; border:0px solid #000; background-color:transparent' value='$nome_pessoa_w'>
					</form>
					</div>
				</td>";
			}
			echo 
				"<td width='90px' align='center'>$idSankhya_pessoa_w</td>
				<td width='180px' align='center'>$cpf_cnpj_w</td>
				<td width='150px' align='center'>$telefone_pessoa_w</td>
				<td width='300px' align='center'>$cidade_pessoa_w/$estado_pessoa_w</td>
				<td width='100px' align='center' style='background-color:$color_bg_w'>$situacao_compra_w</td>
				</tr>";
		}

		echo "</table>";
		// =================================================================================================================


		// =================================================================================================================
		if ($linha_pessoa_geral == 0 and ($nome_form != '' or $cpf_cnpj_w != '')) {
			echo "
			<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum fornecedor encontrado.</i></div>";
		} else {
		}
		// =================================================================================================================


		// =================================================================================================================
		echo "
			<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
			</div>		<!-- FIM DIV centro_4 -->
			<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
			</div>		<!-- FIM DIV centro_3 -->";
		// =================================================================================================================

		?>





		<!-- ============================================================================================================= -->
		<div class="espacamento_30"></div>
		<!-- ============================================================================================================= -->




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