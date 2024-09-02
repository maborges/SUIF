<?php
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'compra_visualizar';
$titulo = 'Compras';
$menu = 'produtos';
$modulo = 'compras';


// ======= RECEBENDO POST =================================================================================
$numero_compra = $_POST["numero_compra"];
$numero_compra_aux = $_POST["numero_compra_aux"] ?? '';
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$botao_relatorio = $_POST["botao_relatorio"] ?? '';
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"] ?? '';
$cod_produto_aux = $_POST["cod_produto"];
$produtor_ficha = $_POST["fornecedor"];
$monstra_situacao = $_POST["monstra_situacao"];

$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA COMPRA ===================================================================================
$busca_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra'");
$aux_bc = mysqli_fetch_row($busca_compra);
$linhas_bc = mysqli_num_rows($busca_compra);

$fornecedor = $aux_bc[2];
$cod_produto = $aux_bc[39];
$data_compra = $aux_bc[4];
$data_compra_print = date('d/m/Y', strtotime($aux_bc[4]));
$quantidade = $aux_bc[5];
$quantidade_print = number_format($aux_bc[5], 2, ",", ".");
$preco_unitario = $aux_bc[6];
$preco_unitario_print = number_format($aux_bc[6], 2, ",", ".");
$valor_total = $aux_bc[7];
$valor_total_print = number_format($aux_bc[7], 2, ",", ".");
$tipo = $aux_bc[10];
$cod_tipo = $aux_bc[41];
$safra = $aux_bc[9];
$umidade = $aux_bc[12];
$broca = $aux_bc[11];
$impureza = $aux_bc[43];
$data_pagamento = date('d/m/Y', strtotime($aux_bc[14]));
$situacao_pgto = $aux_bc[15];
$observacao = $aux_bc[13];
$usuario_cadastro = $aux_bc[18];
$data_cadastro = date('d/m/Y', strtotime($aux_bc[20]));
$hora_cadastro = $aux_bc[19];
$motivo_alteracao_quant = $aux_bc[35];
$quantidade_original = number_format($aux_bc[36], 2, ",", ".");
$desconto_quantidade = number_format($aux_bc[29], 2, ",", ".");
$desconto_quantidade_2 = $aux_bc[29];
$valor_total_original = number_format($aux_bc[37], 2, ",", ".");
$desconto_em_valor = ($aux_bc[29] * $aux_bc[6]);
$desc_em_valor_print = number_format($desconto_em_valor, 2, ",", ".");
$usuario_altera_quant = $aux_bc[44];
if ($aux_bc[45] == "") {
	$data_altera_quant = "";
} else {
	$data_altera_quant = date('d/m/Y', strtotime($aux_bc[45]));
}
$hora_altera_quant = $aux_bc[46];

// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21], 2, ",", ".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "PF" or $aux_forn[2] == "pf") {
	$cpf_cnpj = $aux_forn[3];
} else {
	$cpf_cnpj = $aux_forn[4];
}
// ======================================================================================================


// ====== SOMA PAGAMENTOS ===============================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND estado_registro='ATIVO'"));
$saldo_pagamento = $valor_total - $soma_pagamentos[0];
// ======================================================================================================


// ====== SOMA PAGAMENTOS 2 ================================================================================
$soma_pagamentos_2 = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND situacao_pagamento='PAGO' AND estado_registro='ATIVO'"));
$saldo_pagamento_2 = $valor_total - $soma_pagamentos_2[0];
$saldo_pagamento_print = number_format($saldo_pagamento_2, 2, ",", ".");

$quant_saldo = $saldo_pagamento_2 / $preco_unitario;
$quant_saldo_print = number_format($quant_saldo, 2, ",", ".");
// =============================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// =============================================================================
include('../../includes/head.php');
?>

<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>

<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->

<body onload="javascript:foco('ok');">


	<!-- =============================================    T O P O    ================================================= -->
	<div id="topo_geral">
		<?php include('../../includes/topo.php'); ?>
	</div>




	<!-- =============================================    M E N U    ================================================= -->
	<!-- ====== MENU ================================================================================================== -->
	<div class="menu">
		<?php include("../../includes/menu_compras.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_compras_compras.php"); ?>
	</div>




	<!-- =============================================   C E N T R O   =============================================== -->
	<div id="centro_geral">

		<div id="centro" style="height:335px; width:1080px; border:0px solid #0000FF; margin:auto">

			<div id="centro" style="height:30px; width:1050px; border:0px solid #000; color:#003466; font-size:12px"></div>

			<!-- ============================================================================================================== -->
			<div id="centro" style="width:1050px; border:0px solid #000; color:#003466; margin-left:25px; font-size:17px; float:left" align="center">

				<div id="centro" style="width:345px; border:0px solid #000; color:#003466; font-size:12px; float:left" align="left">N&ordm; <?php echo "$numero_compra"; ?></div>

				<div id="centro" style="width:345px; border:0px solid #000; color:003466; font-size:13px; float:left" align="center">
					<?php echo "<b>$produto_print</b>"; ?></div>

				<div id="centro" style="width:345px; border:0px solid #000; color:003466; font-size:12px; float:left" align="right">
					<?php
					echo "$data_compra_print";
					?>
				</div>

			</div>

			<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
			<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:20px">
				<div style="margin-top:3px; margin-left:55px">Dados do Produtor:</div>
			</div>
			<div id="centro" style="width:1030px; height:50px; border:1px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

				<div style="width:650px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px; color:003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$fornecedor_print</b>" ?></div>
				</div>
				<div style="width:220px; height:15px; border:0px solid #000; float:left; font-size:11px; color:003466">
					<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$cpf_cnpj</b>" ?></div>
				</div>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

				<div style="width:650px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px; color:003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$cidade_fornecedor - $estado_fornecedor</b>" ?></div>
				</div>
				<div style="width:220px; height:15px; border:0px solid #000; float:left; font-size:11px; color:003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$telefone_fornecedor</b>" ?></div>
				</div>

			</div>

			<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
			<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:20px">
				<div style="margin-top:3px; margin-left:55px">Dados da Compra:</div>
			</div>
			<div id="centro" style="width:1030px; height:70px; border:1px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

				<div style="width:250px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
					<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$produto_print</b>" ?></div>
				</div>
				<div style="width:220px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Tipo:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$tipo</b>" ?></div>
				</div>
				<div style="width:125px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Safra:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$safra</b>" ?></div>
				</div>
				<div style="width:125px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Umidade:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$umidade</b>" ?></div>
				</div>
				<div style="width:125px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Broca:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$broca</b>" ?></div>
				</div>
				<div style="width:125px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Impureza:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$impureza</b>" ?></div>
				</div>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


				<div style="width:780px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
					<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
					<div style="margin-top:3px; margin-left:5px; width:400px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php echo "$observacao" ?></div>
				</div>
				<?php
				if ($produto_print == "PIMENTA") {
					echo "
			<div style='width:220px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Tipo Secagem:</div><div style='margin-top:3px; margin-left:5px; float:left'>$tipo_secagem</div></div>";
				} else {
					echo "
			<div style='width:220px; height:15px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'><!-- Tipo Secagem: --></div><div style='margin-top:3px; margin-left:5px; float:left'></div></div>";
				}
				?>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>


				<div style="width:248px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
					<div style="margin-top:3px; margin-left:5px; float:left">Quantidade:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$quantidade_print</b> $unidade_print" ?></div>
				</div>
				<div style="width:248px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Pre&ccedil;o Unit&aacute;rio:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>R$ $preco_unitario_print</b>" ?></div>
				</div>
				<div style="width:285px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Valor Total:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>R$ $valor_total_print</b>" ?></div>
				</div>
				<div style="width:212px; height:15px; border:0px solid #000; float:left; font-size:11px">
					<div style="margin-top:3px; margin-left:5px; float:left">Saldo a Pagar:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<font style='color:#FF0000'>R$ $saldo_pagamento_print</font>" ?></div>
				</div>


			</div>



			<!-- ============================================================================================================== -->

			<div id="centro" style="width:1020px; height:7px; border:0px solid #000; margin-left:30px; font-size:10px; float:left" align="left"></div>

			<div id="centro" style="width:1020px; height:15px; border:0px solid #000; margin-left:30px; font-size:10px; float:left" align="center">
				<?php
				if ($saldo_pagamento == 0 and $situacao_pgto != "EM_ABERTO") {
					echo "<i style='color:#0000FF'>( Compra Liquidada )</i>";
				} else {
					echo "<i style='color:#006600'>Saldo em aberto: R$ $saldo_pagamento_print (ref. a $quant_saldo_print $unidade_print)</i>";
				}
				?>
			</div>

			<div id="centro" style="width:1020px; height:7px; border:0px solid #000; margin-left:30px; font-size:10px; float:left" align="left"></div>


			<div id="centro" style="width:1020px; height:15px; border:0px solid #000; margin-left:30px; font-size:10px; float:left; color:#FF0000" align="left">
				<?php
				if ($desconto_quantidade_2 > 0) {
					echo "
* Acerto de Quantidade: Quantidade original: $quantidade_original $unidade_print | Valor original: R$ $valor_total_original | Motivo: $motivo_alteracao_quant | Desconto: $desconto_quantidade $unidade_print (R$ $desc_em_valor_print) Quant. alterada por: $usuario_altera_quant $data_altera_quant $hora_altera_quant
";
				} else {
				}
				?>
			</div>




			<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:20px">
				<div style="margin-top:3px; margin-left:55px">Dados do Pagamento:</div>
			</div>



		</div>






		<!-- ================== INICIO DO RELATORIO ================= -->
		<div id="centro" style="height:auto; width:1030px; border:1px solid #999; margin:auto; border-radius:5px;">

			<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
			<?php
			$busca_favorecidos_pgto = mysqli_query($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
			$linha_favorecidos_pgto = mysqli_num_rows($busca_favorecidos_pgto);


			if ($linha_favorecidos_pgto == 0) {
				echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existe pagamento para esta compra.</i></div>";
			} else {
				echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='90px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='300px' align='center' bgcolor='#006699'>Favorecido</td>
<td width='100px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='270px' align='center' bgcolor='#006699'>Dados Banc&aacute;rios</td>
<td width='100px' align='center' bgcolor='#006699'>Quantidade Ref.</td>
<td width='100px' align='center' bgcolor='#006699'>Valor (R$)</td>
</tr>
</table>
</div>
<div id='centro' style='height:10px; width:1030px; border:0px solid #999; margin:auto'></div>";
			}

			echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";

			for ($w = 1; $w <= $linha_favorecidos_pgto; $w++) {
				$aux_favorecido = mysqli_fetch_row($busca_favorecidos_pgto);

				// DADOS DO FAVORECIDO =========================
				$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
				$obs_pgto = ($aux_favorecido[7]);

				$busca_favorecido_2 = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
				$aux_f2 = mysqli_fetch_row($busca_favorecido_2);

				$codigo_pessoa_2 = $aux_f2[1];
				$banco_2 = $aux_f2[2];
				$agencia_2 = $aux_f2[3];
				$conta_2 = $aux_f2[4];
				$tipo_conta_2 = $aux_f2[5];


				$busca_banco_2 = mysqli_query($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
				$aux_b2 = mysqli_fetch_row($busca_banco_2);
				$banco_print_2 = $aux_b2[3];

				if ($tipo_conta_2 == "corrente") {
					$tipo_conta_print_2 = "C/C";
				} elseif ($tipo_conta_2 == "poupanca") {
					$tipo_conta_print_2 = "C/P";
				} else {
					$tipo_conta_print_2 = "C.";
				}

				$busca_pessoa_2 = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
				$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
				$nome_favorecido_2 = $aux_p2[1];
				$tipo_pessoa_2 = $aux_p2[2];
				if ($tipo_pessoa_2 == "PF" or $tipo_pessoa_2 == "pf") {
					$cpf_cnpj_2 = $aux_p2[3];
				} else {
					$cpf_cnpj_2 = $aux_p2[4];
				}

				$valor_pagamento_print_2 = number_format($aux_favorecido[5], 2, ",", ".");
				$quant_ref = $aux_favorecido[5] / $preco_unitario;
				$quant_ref_print = number_format($quant_ref, 2, ",", ".");

				// FORMA DE PAGAMENTO =========================
				if ($aux_favorecido[3] == "DINHEIRO") {
					$forma_pagamento_2 = "Dinheiro";
				} elseif ($aux_favorecido[3] == "CHEQUE") {
					$forma_pagamento_2 = "Cheque";
				} elseif ($aux_favorecido[3] == "TED") {
					$forma_pagamento_2 = "Transfer&ecirc;ncia";
				} elseif ($aux_favorecido[3] == "OUTRA") {
					$forma_pagamento_2 = "Outra";
				} elseif ($aux_favorecido[3] == "PREVISAO") {
					$forma_pagamento_2 = "( PREVIS&Atilde;O )";
				} else {
					$forma_pagamento_2 = "-";
				}

				// DADOS BANCARIOS =========================
				if ($aux_favorecido[3] == "CHEQUE") {
					$dados_bancarios_2 = " $aux_favorecido[6] ( N&ordm; cheque: $aux_favorecido[18] )";
				} elseif ($aux_favorecido[3] == "TED") {
					$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";
				} elseif ($aux_favorecido[3] == "DINHEIRO") {
					$dados_bancarios_2 = "";
				} elseif ($aux_favorecido[3] == "PREVISAO") {
					$dados_bancarios_2 = "";
				} elseif ($aux_favorecido[3] == "OUTRA") {
					$dados_bancarios_2 = "$obs_pgto";
				} else {
					$dados_bancarios_2 = "-";
				}

				// RELATORIO =========================
				echo "
	<tr style='color:#00F' title='Observa&ccedil;&atilde;o: $obs_pgto'>
	<td width='90px' align='left'>&#160;&#160;$data_pagamento_print_2</td>
	<td width='300px' align='left'>&#160;&#160;$nome_favorecido_2 ($aux_favorecido[2])</td>
	<td width='100px' align='left'>&#160;&#160;$forma_pagamento_2</td>
	<td width='270px' align='left'>&#160;&#160;$dados_bancarios_2</td>
	<td width='100px' align='center'>$quant_ref_print $unidade</td>
	<td width='100px' align='right'>$valor_pagamento_print_2&#160;&#160;</td>
	</tr>";
			}
			echo "
</table>
</div>
<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
";


			?>




		</div>
		<!-- ================== FIM DO RELATORIO ================= -->


		<div id="centro" style="height:15px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center"></div>
		<div id="centro" style="height:60px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center">
			<div id='centro' style='float:left; height:55px; width:330px; color:#00F; text-align:center; border:0px solid #000'></div>
			<?php

			if ($pagina_filha == "movimentacao") {
				echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto_aux'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$fornecedor'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<button type='submit' class='botao_1' style='margin-left:0px; width:160px'>Voltar</button>
		<!--<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' />-->
		</form>
		</div>";
			} else {
				echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto_aux'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$fornecedor'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<button type='submit' class='botao_1' style='margin-left:0px; width:160px'>Voltar</button>
		<!--<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' />-->
		</form>
		</div>";
			}




			echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/compra_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_1' style='margin-left:0px; width:160px'>Imprimir Compra</button>
		<!--<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_compra.jpg' border='0' />-->
		</form>
		</div>";


			if ($diretorio_servidor == "sis") {

				echo "
	<div id='centro' style='float:right; height:55px; width:170px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_rovereti_reenviar.php' target='_blank' method='post'>
	<input type='hidden' name='botao' value='ROVERETI'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<button type='submit' class='botao_1' style='margin-left:0px; width:160px'>Reenviar ao Rovereti</button>
	</form>
	</div>";
			}
			?>
		</div>

	</div> <!-- ================================== FIM DA DIV CENTRO GERAL ======================================= -->




	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>