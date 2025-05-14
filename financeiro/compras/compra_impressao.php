<?php
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'compra_impressao';
$titulo = 'Impress&atilde;o de Compra';
$menu = 'produtos';
$modulo = 'compras';

include('../../includes/head_impressao.php');

$numero_compra = $_POST["numero_compra"];

// =============================================================================================================
// =============================================================================================================
$busca_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows($busca_compra);

for ($x = 1; $x <= $linha_compra; $x++) {
	$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5], 2, ",", ".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6], 2, ",", ".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7], 2, ",", ".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$tipo_secagem = $aux_compra[27];
$observacao = $aux_compra[13];
$motivo_alteracao_quant = $aux_compra[35];
$quantidade_original = number_format($aux_compra[36], 2, ",", ".");
$desconto_quantidade = number_format($aux_compra[29], 2, ",", ".");
$desconto_quantidade_2 = $aux_compra[29];
$valor_total_original = number_format($aux_compra[37], 2, ",", ".");
$desconto_em_valor = ($aux_compra[29] * $aux_compra[6]);
$desc_em_valor_print = number_format($desconto_em_valor, 2, ",", ".");
$pedidoSankhya = $aux_compra[55];


$usuario_print = $aux_compra[18];
$filial_print = $aux_compra[25];


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================

// UNIDADE PRINT  ==========================================================================================
if ($unidade == "SC") {
	if ($quantidade <= 1) {
		$unidade_print = "Saca";
	} else {
		$unidade_print = "Sacas";
	}
} elseif ($unidade == "KG") {
	if ($quantidade <= 1) {
		$unidade_print = "Kg";
	} else {
		$unidade_print = "Kg";
	}
} elseif ($unidade == "CX") {
	$unidade_print = "Cx";
} elseif ($unidade == "UN") {
	$unidade_print = "Un";
} else {
	$unidade_print = "-";
}

// SITUA��O PRINT  ==========================================================================================
if ($situacao == "POSTO") {
	$situacao_print = "POSTO";
} elseif ($situacao == "A_RETIRAR") {
	$situacao_print = "A RETIRAR";
} elseif ($situacao == "ARMAZENADO") {
	$situacao_print = "ARMAZENADO";
} else {
	$situacao_print = "-";
}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows($busca_pessoa);
for ($y = 1; $y <= $linha_pessoa; $y++) {
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
	$cod_fornecedor_print = $aux_pessoa[0];
	if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf") {
		$cpf_cnpj = $aux_pessoa[3];
	} else {
		$cpf_cnpj = $aux_pessoa[4];
	}
}
?>

<!-- ==================================   T � T U L O   D A   P � G I N A   ====================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N � C I O   =============================================== -->

<body onLoad="imprimir()">


	<div style="width:800px; border:0px solid #000; float:left; margin: 20px 20px">
		<div style="height:65px; border:0px solid #000; font-size:17px;">
			<img src=" <?php echo "$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" alt="Grancafe" height="80px" />
		</div>

		<div style="width:100%; height:auto; font-size:17px; float:left; display: flex; 
					flex-direction: column; align-items: center; justify-content: center; margin-bottom: 15px;">

			<div style="width:100%; display: flex; font-size:10px;">
				<div style="flex: 1; text-align: left;"></div>
				<div style="flex: 1; text-align: center; font-size:13px;">
					<b>CONFIRMA&Ccedil;&Atilde;O DE COMPRA</b>
				</div>
				<div style="flex: 1; text-align: right;"></div>
			</div>

			<div style="width:100%; display: flex; justify-content: space-between; font-size:12px; margin-top:20px;">
				<div style="flex: 1; text-align: left;">N&ordm; <?= $numero_compra ?></div>
				<div style="flex: 1; text-align: center; font-size:13px;"><b><?= $produto_print ?></b></div>
				<div style="flex: 1; text-align: right;"><?= $data_compra_print ?></div>
			</div>
		</div>

		<!-- ======================================================================================================================================= -->

		<div id="centro" style="width:100%; border:0px solid #000; margin-top:2px; line-height: 2;">

			<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
			<div style="padding-top: 40px; font-size:9px">Dados do Vendedor:</div>
			<div style="height:auto; border: 1px solid #000; color:#000; border-radius:5px; padding: 10px;">

				<div style="display: flex; width:100%; border:0px solid #000; font-size:11px">
					<div style="flex: 3; margin-top:3px; ">Nome: <b><?php echo $fornecedor_print; ?></b></div>
					<div style="flex: 1; margin-top:3px; ">CPF/CNPJ: <b><?php echo $cpf_cnpj; ?></b></div>
				</div>

				<div style="display: flex; width: 100%; border: 0px solid #000; font-size: 11px;">
					<div style="flex: 1; ">C&oacute;digo: <b><?= $cod_fornecedor_print ?></b></div>
					<div style="flex: 2; text-align: center;">Cidade: <b><?= "$aux_pessoa[10] - $aux_pessoa[12]" ?></b></div>
					<div style="flex: 1; ">Telefone: <b><?= $aux_pessoa[14] ?></b></div>
				</div>
			</div>

			<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
			<div style="display: flex; justify-content: space-between; height:auto; font-size:9px; margin-top:20px; ">
				<div style="text-align: left;">Dados da Compra:</div>
				<div style="flex: 1; text-align: right;">Pedido Sankhya: <b><?= $pedidoSankhya ?></b></div>
			</div>

			<div style="height:auto; border: 1px solid #000; color:#000; border-radius:5px; padding: 10px; overflow:hidden">

				<div style="display: flex; width: 100%; font-size: 11px;">
					<div style="flex: 2;">Produto: <b><?= $produto_print ?></b></div>
					<div style="flex: 1;">Safra: <b><?= $safra ?></b></div>
					<div style="flex: 1;">Tipo: <b><?= $tipo ?></b></div>
					<div style="flex: 1;">Umidade: <b><?= $umidade ?></b></div>

					<?php if ($produto == "CAFE") : ?>
						<div style="flex: 1; margin-top: 3px; margin-left: 5px;">Broca: <b><?= $broca ?></b></div>
					<?php elseif ($produto == "PIMENTA") : ?>
						<div style="flex: 1; margin-top: 3px; margin-left: 5px;">Impureza: <b><?= $broca ?></b></div>
					<?php endif; ?>
				</div>

				<?php if ($produto == "PIMENTA") : ?>
					<div style="display: flex; width: 100%; height: 25px; font-size: 11px; margin-top: 15px;	">
						<div style="flex: 2; margin-top: 3px; margin-left: 5px;">Tipo Secagem: <b><?= $tipo_secagem ?></b></div>
					</div>
				<?php endif; ?>


				<div style="display: flex; width: auto; font-size: 11px;">
					<div style="flex: 1; text-align: left;">Quantidade: <b><?= $quantidade_print ?></b> <?= $unidade_print ?></div>
					<div style="flex: 1; text-align: center;">Pre&ccedil;o Unit&aacute;rio: <b><?= $preco_unitario_print ?></b></div>
					<div style="flex: 1; text-align: right;">Valor Total: <b><?= $valor_total_print ?></b></div>
				</div>

			</div>

			<?php
			// SOMA PAGAMENTOS  ==========================================================================================
			//$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND situacao_pagamento='PAGO' AND estado_registro='ATIVO'"));

			$soma_pagamentos = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND forma_pagamento!='PREVISAO' AND estado_registro='ATIVO'"));
			$saldo_pagamento = $valor_total - $soma_pagamentos[0];
			$saldo_pagamento_print = number_format($saldo_pagamento, 2, ",", ".");
			?>

			<div style="display: flex; justify-content: space-between; font-size:9px; margin-top:20px; ">
				<div style="flex: 2">
					<div>Observação:</div>
					<div style="border: 1px solid #000; color:#000; border-radius:5px; padding: 5px; overflow:hidden; min-height: 20px">
						<?= $observacao ?> 
					</div>
				</div>
				<div style="flex: 1; margin-left: 20px">
					<div>Saldo a Pagar:</div>
					<div style="border: 1px solid #000; color:#000; border-radius:5px; padding: 5px; overflow:hidden; max-height: 20px; font-size:13px; text-align: center">
						R$ <b><?= $saldo_pagamento_print ?></b>
					</div>

				</div>
			</div>


			<!-- ========================================================== DADOS DO PAGAMENTO ============================================================================= -->
			<?php
			$busca_favorecidos_pgto = mysqli_query($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
			$linha_favorecidos_pgto = mysqli_num_rows($busca_favorecidos_pgto);

			$quant_saldo = $saldo_pagamento / $preco_unitario;
			$quant_saldo_print = number_format($quant_saldo, 2, ",", ".");
			?>

			<div style="display: flex; justify-content: space-between; height:auto; font-size:9px">
				<div style="margin-top:20px; margin-left:5px">Dados do Pagamento:</div>
				<div style="margin-top:20px; flex: 1; text-align: right;">
					<?php
					if ($saldo_pagamento == 0) {
						echo "<i>( Compra Liquidada )</i>";
					} else {
						echo "<i>Saldo em aberto: R$ $saldo_pagamento_print (ref. a $quant_saldo_print $unidade)</i>";
					}
					?>
				</div>
			</div>


			<div style="display: flex; height:auto; font-size:9px;">
				<div style='width:56px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Data Pgto</i></div>
				</div>
				<div style='width:200px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Favorecido</i></div>
				</div>
				<div style='width:90px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>CPF/CNPJ</i></div>
				</div>
				<div style='width:70px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Forma de Pgto</i></div>
				</div>
				<div style='width:185px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Dados Banc&aacute;rios</i></div>
				</div>
				<div style='width:50px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Quant:</i></div>
				</div>
				<div style='width:65px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Valor:</i></div>
				</div>
				<div style='width:25px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>PG</i></div>
				</div>
				<div style='width:50px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
					<div><i>Sankhya</i></div>
				</div>
			</div>

			<?php for ($w = 1; $w <= $linha_favorecidos_pgto; $w++): ?>
				<?php
				$aux_favorecido = mysqli_fetch_row($busca_favorecidos_pgto);

				// DADOS DO FAVORECIDO =========================
				$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
				$obs_pgto = ($aux_favorecido[7]);
				$idPedidoSankhya = $aux_favorecido[38];

				$busca_favorecido_2 = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
				$aux_f2 = mysqli_fetch_row($busca_favorecido_2);

				$codigo_pessoa_2 = $aux_f2[1];
				$banco_2 = $aux_f2[2];
				$agencia_2 = $aux_f2[3];
				$conta_2 = $aux_f2[4];
				$tipo_conta_2 = $aux_f2[5];
				$conta_conjunta = $aux_f2[15];

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
				?>

				<div style="display: flex; height:auto; font-size:9px; margin-top:5px;">
					<div style='width:56px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px; text-align:center;'>
						<div style='margin-left:2px'><?= $data_pagamento_print_2 ?></div>
					</div>
					<div style='width:200px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px;'>
						<div style='margin-left:2px'> <?= $nome_favorecido_2 ?> <?= $conta_conjunta == "SIM" ? "(*)" : "" ?></div>
					</div>

					<div style='width:90px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px;'>
						<div style='margin-left:2px'> <?= $cpf_cnpj_2 ?></div>
					</div>

					<div style='width:70px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px;'>
						<div style='margin-left:2px'><?= $forma_pagamento_2 ?></div>
					</div>
					<div style='width:185px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px;'>
						<div style='margin-left:2px'><?= $dados_bancarios_2 ?></div>
					</div>
					<div style='width:50px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px; text-align:right;'>
						<div style='margin-right:2px'><?= "$quant_ref_print $unidade" ?></div>
					</div>
					<div style='width:65px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px; text-align:right;'>
						<div style='margin-right:2px'><?= $valor_pagamento_print_2 ?></div>
					</div>
					<div style='width:25px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px; text-align:center;'>
						<div>
							<?php if ($aux_favorecido[15] == "PAGO") : ?>
								<b>&#10004;</b>
							<?php endif; ?>
						</div>
					</div>
					<div style='width:50px; height:17px; border:1px solid #999; float:left; font-size:8px; margin-left:2px; text-align:right;'>
						<div style='margin-right:2px'><?= $idPedidoSankhya ?></div>
					</div>
				</div>

				<!--
				<div id='centro' style='height:5px; width:825px; border:0px solid #999; margin:auto; float:left'></div>
				
				<div style='width:56px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-left:4px; float:left'><?= $data_pagamento_print_2 ?></div>
				</div>

				<div style='width:200px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px; overflow:hidden'>
					<div style='margin-top:3px; margin-left:4px; float:left'><?= $nome_favorecido_2 ?> <?= $conta_conjunta == "SIM" ? "(*)" : "" ?> </div>
				</div>

				<div style='width:90px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-left:4px; float:left'><?= $cpf_cnpj_2 ?></div>
				</div>
				<div style='width:70px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-left:4px; float:left'><?= $forma_pagamento_2 ?></div>
				</div>
				<div style='width:185px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-left:4px; float:left'><?= $dados_bancarios_2 ?></div>
				</div>
				<div style='width:50px; height:17px; border:1px solid #000; float:left; text-align:center; font-size:7px; margin-left:2px'>
					<div style='margin-top:3px'><?= "$quant_ref_print $unidade" ?></div>
				</div>
				<div style='width:65px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-right:4px; float:right'><?= $valor_pagamento_print_2 ?></div>
				</div>

				<div style='width:25px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
					<div style='margin-top:2px; margin-right:4px; float:right'>
						<?php if ($aux_favorecido[15] == "EM_ABERTO") : ?>
							<b>&#10004;</b>
						<?php endif; ?>
					</div>
				</div>

				<div style='width:50px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
					<div style='margin-top:3px; margin-right:4px; float:right'><?= $idPedidoSankhya ?></div>
				</div>

				<div id='centro' style='height:5px; width:825px; border:0px solid #999; margin:auto; float:left'></div>
				-->
			<?php endfor; ?>
		</div>

		<?php if ($desconto_quantidade_2 > 0) : ?>
			<div id="centro" style="width:100%; font-size:11px; margin: 15px 15px;float:left">
				<?= "* Acerto de Quantidade: Quantidade original: $quantidade_original - Valor original: R$ $valor_total_original - Motivo: $motivo_alteracao_quant - Desconto: $desconto_quantidade $unidade_print (R$ $desc_em_valor_print)" ?>
			</div>
		<?php endif; ?>

	</div>

	<footer style="position: absolute; bottom: 0; width: 100%;">
		<div style="width:800px; border-top: 1px solid #999; float:left; margin: 20px 20px; display: flex; justify-content: space-between; padding: 5px;">
			<div style="flex: 1; font-size:9px; text-align: left;">
				&copy; SUIF | GRANCAF&Eacute;
			</div>
			<div style="flex: 1; font-size:9px; text-align: center;">
				<?php echo "$usuario_print" ?> ( <?php echo "$filial_print" ?> )
			</div>
			<div style="flex: 1; font-size:9px; text-align: right;">
				VIA FINANCEIRO
			</div>
		</div>
	</footer>

</body>

</html>
<!-- ==================================================   FIM   ================================================= -->