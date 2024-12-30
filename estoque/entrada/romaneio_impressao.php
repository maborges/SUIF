<?php
include("../../includes/config.php");
include("../../includes/valida_cookies.php");
$pagina = "romaneio_impressao";
$titulo = "Romaneio de Entrada";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$numero_romaneio = $_POST["numero_romaneio"] ?? '';
$msg_situacao = $_POST["msg_situacao"] ?? '';
$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";
// ================================================================================================================


// ========================================================================================================
include("../../includes/conecta_bd.php");

// ====== BUSCA CADASTROS =========================================================================================
$busca_romaneio = mysqli_query($conexao, "SELECT estoque.codigo, estoque.numero_romaneio, estoque.fornecedor, estoque.data, estoque.produto, estoque.peso_inicial, estoque.peso_final, estoque.desconto_sacaria, estoque.desconto, estoque.quantidade, estoque.unidade, estoque.tipo_sacaria, estoque.movimentacao, estoque.placa_veiculo, estoque.motorista, estoque.observacao, estoque.usuario_cadastro, estoque.hora_cadastro, estoque.data_cadastro, estoque.usuario_alteracao, estoque.hora_alteracao, estoque.data_alteracao, estoque.filial, estoque.estado_registro, estoque.quantidade_prevista, estoque.quantidade_sacaria, estoque.numero_compra, estoque.motorista_cpf, estoque.num_romaneio_manual, estoque.filial_origem, estoque.quant_volume_sacas, estoque.cod_produto, estoque.usuario_exclusao, estoque.hora_exclusao, estoque.data_exclusao, cadastro_pessoa.nome, cadastro_pessoa.tipo, cadastro_pessoa.cpf, cadastro_pessoa.cnpj, cadastro_pessoa.cidade, cadastro_pessoa.estado, cadastro_pessoa.telefone_1, select_tipo_sacaria.descricao, select_tipo_sacaria.peso, estoque.cod_tipo, estoque.quant_quebra_previsto FROM estoque, cadastro_pessoa, select_tipo_sacaria WHERE (estoque.numero_romaneio='$numero_romaneio' AND $mysql_movimentacao) AND estoque.fornecedor=cadastro_pessoa.codigo AND estoque.tipo_sacaria=select_tipo_sacaria.codigo ORDER BY estoque.codigo");
$linha_romaneio = mysqli_num_rows($busca_romaneio);
// ========================================================================================================

// ====== BUSCA NOTAS FISCAIS ======================================================================
$busca_nota_fiscal = mysqli_query($conexao, "SELECT a.codigo, a.codigo_romaneio, a.codigo_fornecedor, 
													a.numero_nf, a.data_emissao, a.valor_unitario, 
													a.unidade, a.quantidade, a.valor_total, 
													a.observacao, a.usuario_cadastro, a.hora_cadastro, 
													a.data_cadastro, a.usuario_alteracao, a.hora_alteracao, 
													a.data_alteracao, a.estado_registro, a.filial, 
													a.natureza_operacao, 
													b.nome, b.tipo, b.cpf, b.cnpj, a.serie_nf
											   FROM nota_fiscal_entrada a, 
											   		cadastro_pessoa b 
											  WHERE a.estado_registro='ATIVO' 
											    AND a.codigo_romaneio='$numero_romaneio' 
												AND a.codigo_fornecedor=b.codigo 
												ORDER BY a.data_emissao");
$linha_nota_fiscal = mysqli_num_rows($busca_nota_fiscal);

$soma_nota_fiscal = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_entrada WHERE estado_registro='ATIVO' AND codigo_romaneio='$numero_romaneio'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0], 2, ",", ".");

$soma_quantidade_nf = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM nota_fiscal_entrada WHERE estado_registro='ATIVO' AND codigo_romaneio='$numero_romaneio'"));
$soma_quantidade_nf_print = number_format($soma_quantidade_nf[0], 0, ",", ".");
// ======================================================================================================

include("../../includes/desconecta_bd.php");
// ========================================================================================================



// ====== FUN��O FOR ===================================================================================
for ($x = 1; $x <= $linha_romaneio; $x++) {
	$aux_romaneio = mysqli_fetch_row($busca_romaneio);

	// ====== DADOS DO CADASTRO ============================================================================
	$id_w = $aux_romaneio[0];
	$numero_romaneio_w = $aux_romaneio[1];
	$fornecedor_w = $aux_romaneio[2];
	$data_w = $aux_romaneio[3];
	$produto_w = $aux_romaneio[4];
	$peso_inicial_w = $aux_romaneio[5];
	$peso_final_w = $aux_romaneio[6];
	$desconto_sacaria_w = $aux_romaneio[7];
	$desconto_w = $aux_romaneio[8];
	$quantidade_w = $aux_romaneio[9];
	$unidade_w = $aux_romaneio[10];
	$tipo_sacaria_w = $aux_romaneio[11];
	$movimentacao_w = $aux_romaneio[12];
	$placa_veiculo_w = $aux_romaneio[13];
	$motorista_w = $aux_romaneio[14];
	$observacao_w = $aux_romaneio[15];
	$usuario_cadastro_w = $aux_romaneio[16];
	$hora_cadastro_w = $aux_romaneio[17];
	$data_cadastro_w = $aux_romaneio[18];
	$usuario_alteracao_w = $aux_romaneio[19];
	$hora_alteracao_w = $aux_romaneio[20];
	$data_alteracao_w = $aux_romaneio[21];
	$filial_w = $aux_romaneio[22];
	$estado_registro_w = $aux_romaneio[23];
	$quantidade_prevista_w = $aux_romaneio[24];
	$quantidade_sacaria_w = $aux_romaneio[25];
	$numero_compra_w = $aux_romaneio[26];
	$motorista_cpf_w = $aux_romaneio[27];
	$num_romaneio_manual_w = $aux_romaneio[28];
	$filial_origem_w = $aux_romaneio[29];
	$quant_volume = $aux_romaneio[30];
	$cod_produto_w = $aux_romaneio[31];
	$usuario_exclusao_w = $aux_romaneio[32];
	$hora_exclusao_w = $aux_romaneio[33];
	$data_exclusao_w = $aux_romaneio[34];
	$pessoa_nome_w = $aux_romaneio[35];
	$pessoa_tipo_w = $aux_romaneio[36];
	$pessoa_cpf_w = $aux_romaneio[37];
	$pessoa_cnpj_w = $aux_romaneio[38];
	$pessoa_cidade_w = $aux_romaneio[39];
	$pessoa_estado_w = $aux_romaneio[40];
	$pessoa_telefone_w = $aux_romaneio[41];
	$nome_sacaria_w = $aux_romaneio[42];
	$peso_sacaria_w = $aux_romaneio[43];
	$cod_tipo_w = $aux_romaneio[44];
	$quant_quebra_previsto_w = $aux_romaneio[45];

	if ($pessoa_tipo_w == "PF" or $pessoa_tipo_w == "pf") {
		$pessoa_cpf_cnpj = $pessoa_cpf_w;
	} else {
		$pessoa_cpf_cnpj = $pessoa_cnpj_w;
	}


	$peso_bruto = ($peso_inicial_w - $peso_final_w);

	$data_print = date('d/m/Y', strtotime($data_w));
	$peso_inicial_print = number_format($peso_inicial_w, 0, ",", ".") . " " . $unidade_w;
	$peso_final_print = number_format($peso_final_w, 0, ",", ".") . " " . $unidade_w;
	$peso_bruto_print = number_format($peso_bruto, 0, ",", ".") . " " . $unidade_w;
	$desconto_sacaria_print = number_format($desconto_sacaria_w, 0, ",", ".") . " " . $unidade_w;
	$desconto_print = number_format($desconto_w, 0, ",", ".") . " " . $unidade_w;
	$quantidade_print = "<b>" . number_format($quantidade_w, 0, ",", ".") . "</b> " . $unidade_w;
	$quantidade_sacaria_print = number_format($quantidade_sacaria_w, 0, ",", ".");
	$quant_quebra_prev_print = number_format($quant_quebra_previsto_w, 0, ",", ".") . " " . $unidade_w;


	if (empty($numero_compra_w)) {
		$num_registro_entrada = "(Romaneio n&atilde;o vinculado a ficha)";
	} else {
		$num_registro_entrada = $numero_compra_w;
	}


	if (!empty($usuario_cadastro_w)) {
		$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;
	}

	if (!empty($usuario_alteracao_w)) {
		$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;
	}

	if (!empty($usuario_exclusao_w)) {
		$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;
	}
}
// ================================================================================================================


// ====== CADASTRO PRODUTOS =======================================================================================
include("../../includes/cadastro_produto.php");

for ($p = 0; $p <= count($cadastro_produto) - 1; $p++) {
	if ($cadastro_produto[$p]["codigo"] == $cod_produto_w) {
		$cod_produto_p = $cadastro_produto[$p]["codigo"];
		$produto_print_p = $cadastro_produto[$p]["descricao"];
		$nome_produto_p = $cadastro_produto[$p]["produto_print"];
		$unidade_print_p = $cadastro_produto[$p]["unidade_print"];
		$nome_imagem_produto_p = $cadastro_produto[$p]["nome_imagem"];
		$quant_kg_saca_p = $cadastro_produto[$p]["quant_kg_saca"];

		if (empty($nome_imagem_produto_p)) {
			$link_img_produto = "";
		} else {
			$link_img_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_p.png' style='width:60px'>";
		}
	}
}
// ================================================================================================================


// ====== CADASTRO TIPO PRODUTOS ==================================================================================
include("../../includes/select_tipo_produto.php");

for ($t = 0; $t <= count($select_tipo_produto) - 1; $t++) {
	if ($select_tipo_produto[$t]["codigo"] == $cod_tipo_w) {
		$tipo_print_t = $select_tipo_produto[$t]["descricao"];
	}
}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
$quantidade_real = ($quantidade_w / $quant_kg_saca_p);
$quantidade_real_print = "<b>" . number_format($quantidade_real, 2, ",", ".") . "</b> SC";
// ================================================================================================================


// ================================================================================================================
include('../../includes/head_impressao.php');
?>

<!-- ====== T�TULO DA P�GINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== IN�CIO ================================================================================================ -->

<body onLoad="imprimir()">

	<div style="width:745px; border:0px solid #000; float:left">

		<div style="width:730px; height:40px; border:0px solid #000; margin-left:25px; font-size:17px; float:left">
		</div>

		<div style="width:730px; height:80px; border:0px solid #000; margin-left:25px; font-size:17px; float:left">
			<div style="width:355px; height:80px; border:0px solid #000; font-size:17px; float:left">
				<img src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" height="80px"/>
			</div>
			<div style="width:355px; height:50px; border:0px solid #000; font-size:26px; margin-top:20px; float:right">
				Romaneio de Entrada
			</div>
		</div>

		<div style="width:730px; height:50px; border:0px solid #000; margin-left:25px; font-size:17px; float:left">

			<div style="width:235px; height:20px; border:0px solid #000; font-size:18px; float:left"><?php echo "$data_print"; ?></div>
			<div style="width:235px; height:20px; border:0px solid #000; font-size:18px; float:left">
				<?php echo $msg_situacao; ?></div>
			<div style="width:235px; height:40px; border:0px solid #000; font-size:18px; float:right">N&ordm; <?php echo "$numero_romaneio"; ?></div>

			<div style="width:235px; height:20px; border:0px solid #000; font-size:14px; float:left"><?php // echo"$hora_cadastro"; 
																													?></div>
			<div style="width:235px; height:20px; border:0px solid #000; font-size:14px; float:left" >
				<?php // echo"<b>$produto_print</b>"; 
				?></div>


		</div>


		<!-- ======================================================================================================================================= -->

		<div style="width:735px; border:0px solid #000; margin-top:5px; margin-left:20px; float:left">

			<!-- ================================================ FORNECEDOR ============================================================================= -->
			<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left">
				<div style="margin-top:0px; margin-left:25px">Fornecedor:</div>
			</div>

			<div id="tabela_2" style="width:730px; height:50px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left">

				<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:500px; height:15px; border:0px solid #000; float:left; font-size:12px">
					<div style="margin-top:3px; margin-left:25px; float:left">Nome:</div>
					<div style="margin-top:3px; margin-left:5px; float:left">
						<?php echo "<b>$pessoa_nome_w</b>" ?></div>
				</div>

				<div style="width:225px; height:15px; border:0px solid #000; float:left; font-size:12px">
					<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
					<div style="margin-top:3px; margin-left:5px; float:left">
						<?php echo "<b>$pessoa_cpf_cnpj</b>" ?></div>
				</div>

				<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:500px; height:15px; border:0px solid #000; float:left; font-size:12px">
					<div style="margin-top:3px; margin-left:25px; float:left">Cidade:</div>
					<div style="margin-top:3px; margin-left:5px; float:left">
						<?php echo "<b>$pessoa_cidade_w / $pessoa_estado_w</b>" ?></div>
				</div>

				<div style="width:225px; height:15px; border:0px solid #000; float:left; font-size:12px">
					<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
					<div style="margin-top:3px; margin-left:5px; float:left">
						<?php echo "<b>$pessoa_telefone_w</b>" ?></div>
				</div>

			</div>


			<!-- ================================================ PRODUTO ============================================================================= -->
			<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left"></div>


			<div style="width:730px; height:20px; border:0px solid #000; font-size:12px; float:left">
				<div style="margin-top:0px; margin-left:25px">Produto:</div>
			</div>

			<div style="width:241px; height:32px; border:1px solid #000; color:#000; overflow:hidden; margin-left:0px; float:left">
				<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#000">
					<?php echo "$link_img_produto"; ?>
				</div>

				<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#000; overflow:hidden">
					<?php echo "<b>$produto_print_p</b>" ?>
				</div>
			</div>

			<!--
<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div>
    </div>
</div>

<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div>
    </div>
</div>

<div id="tabela_2" style="width:166px; height:32px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; float:left; margin-left:20px">
    <div style="width:160px; height:20px; border:0px solid #000; float:left; font-size:12px">
    	<div style="margin-top:9px; margin-left:25px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div>
    </div>
</div>
-->

			<!-- ====================================== DADOS DA PESAGEM ============================================================================= -->
			<div id="tabela_2" style="width:730px; height:330px; border:0px solid #000; color:#000; overflow:hidden; float:left">

				<div style="width:725px; height:15px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:0px">
					<div style="margin-top:5px">Peso Inicial</div>
				</div>
				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Peso Final</div>
				</div>
				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Peso Bruto</div>
				</div>
				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Desconto Sacaria</div>
				</div>
				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Outros Descontos</div>
				</div>
				<div style="width:103px; height:20px; border:1px solid #FFF; border-radius:0px; float:left; font-size:11px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Peso L&iacute;quido</div>
				</div>

				<div style="width:720px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px">
					<div style="margin-top:5px"><?php echo "$peso_inicial_print" ?></div>
				</div>
				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px"><?php echo "$peso_final_print" ?></div>
				</div>
				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px"><?php echo "$peso_bruto_print" ?></div>
				</div>
				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px"><?php echo "$desconto_sacaria_print" ?></div>
				</div>
				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px"><?php echo "$desconto_print" ?></div>
				</div>
				<div style="width:103px; height:25px; border:1px solid #000; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px"><?php echo "$quantidade_print" ?></div>
				</div>




				<!-- ======= DADOS DO ROMANEIO ====================================================================================================== -->
				<div style="width:725px; height:20px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quant. Real em Sacas:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$quantidade_real_print" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Tipo do Produto:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$tipo_print_t" ?></div>
				</div>



				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quant. Volume Sacas:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$quant_volume" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$num_romaneio_manual_w" ?></div>
				</div>



				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Tipo Sacaria:</div>
					<div style="width:185px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
						<?php echo "$nome_sacaria_w" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Filial Origem:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$filial_origem_w" ?></div>
				</div>



				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:135px; margin-top:7px; margin-left:5px; float:left">Quantidade Sacaria:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$quantidade_sacaria_print" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Motorista:</div>
					<div style="width:190px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
						<?php echo "$motorista_w" ?></div>
				</div>



				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:155px; margin-top:7px; margin-left:5px; float:left">Desc. Previsto (Qualidade):</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$quant_quebra_prev_print" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">CPF Motorista:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$motorista_cpf_w" ?></div>
				</div>



				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:135px; margin-top:7px; margin-left:5px; float:left">N&ordm; Registro de Ficha:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$num_registro_entrada" ?></div>
				</div>

				<div style="width:330px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:22px">
					<div style="width:130px; margin-top:7px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div>
					<div style="margin-top:7px; margin-left:0px; float:left"><?php echo "$placa_veiculo_w" ?></div>
				</div>



				<div style="width:682px; height:26px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:80px; margin-top:7px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
					<div style="width:590px; height:19px; border:0px solid #000; margin-top:7px; margin-left:0px; float:left; overflow:hidden">
						<?php echo "$observacao_w" ?></div>
				</div>


			</div>
		</div>

		<!-- ========= NOTAS FISCAIS ================================================================================================================== -->
		<div style="width:730px; height:25px; border:0px solid #000; font-size:12px; margin-top:10px; float:left">
			<?php
			if ($linha_nota_fiscal != 0) {
				echo "
	<div style='margin-left:35px; width:250px; height:20px; border:0px solid #000; float:left'>Notas Fiscais de Entrada:</div>
	<div style='width:210px; height:20px; border:0px solid #000; float:left; text-align:right'>Quantidade: $soma_quantidade_nf_print $unidade_print_p</div>
	<div style='width:210px; height:20px; border:0px solid #000; float:right; text-align:right'>Total: R$ $soma_nota_fiscal_print</div>
";
			}
			?>
		</div>
		<!-- ========================================================================================================================================== -->


		<!-- ================== INICIO DO RELATORIO ================= -->
		<div id="tabela_2" style="width:730px; height:210px; border:1px solid #000; color:#000; border-radius:0px; overflow:hidden; margin-left:20px">

			<div style="height:5px; width:725px; border:0px solid #999; margin:auto"></div>

			<?php
			if ($linha_nota_fiscal == 0) {
				echo "<div id='centro' style='height:30px; width:725px; border:0px solid #999; font-size:12px; color:#000; margin-left:30px'><!-- <i>Nenhuma nota fiscal cadastrada.</i> --></div>";
			} else {
				echo "
<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>
	<div style='width:65px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:5px; text-align:center'>Data</div>
	<div style='width:195px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>Produtor</div>
	<div style='width:90px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>CPF/CNPJ</div>
	<div style='width:85px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>N&ordm; NF</div>
	<div style='width:40px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>Natureza</div>
	<div style='width:75px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>Quantidade</div>
	<div style='width:50px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>Valor Un</div>
	<div style='width:75px; height:20px; border:1px solid #FFF; float:left; font-size:10px; margin-left:4px; text-align:center'>Valor Total</div>
</div>
<div id='centro' style='height:5px; width:725px; border:0px solid #999; margin:auto'></div>";
			}

			echo "
<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>";

			// ====== FUN��O FOR ===================================================================================
			for ($z = 1; $z <= $linha_nota_fiscal; $z++) {
				$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

				// ====== DADOS DO CADASTRO ============================================================================
				$id_z = $aux_nota_fiscal[0];
				$numero_romaneio_z = $aux_nota_fiscal[1];
				$fornecedor_z = $aux_nota_fiscal[2];
				$numero_nf_z = $aux_nota_fiscal[3];
				$data_emissao_z = $aux_nota_fiscal[4];
				$valor_unitario_z = $aux_nota_fiscal[5];
				$unidade_z = $aux_nota_fiscal[6];
				$quantidade_z = $aux_nota_fiscal[7];
				$valor_total_z = $aux_nota_fiscal[8];
				$observacao_z = $aux_nota_fiscal[9];
				$usuario_cadastro_z = $aux_nota_fiscal[10];
				$hora_cadastro_z = $aux_nota_fiscal[11];
				$data_cadastro_z = $aux_nota_fiscal[12];
				$usuario_alteracao_z = $aux_nota_fiscal[13];
				$hora_alteracao_z = $aux_nota_fiscal[14];
				$data_alteracao_z = $aux_nota_fiscal[15];
				$estado_registro_z = $aux_nota_fiscal[16];
				$filial_z = $aux_nota_fiscal[17];
				$natureza_operacao_z = $aux_nota_fiscal[18];
				$produtor_nome_z = $aux_nota_fiscal[19];
				$produtor_tipo_z = $aux_nota_fiscal[20];
				$produtor_cpf_z = $aux_nota_fiscal[21];
				$produtor_cnpj_z = $aux_nota_fiscal[22];
				$serie_nf_z =  $aux_nota_fiscal[23];

				if ($produtor_tipo_z == "PF" or $produtor_tipo_z == "pf") {
					$produtor_cpf_cnpj = $produtor_cpf_z;
				} else {
					$produtor_cpf_cnpj = $produtor_cnpj_z;
				}

				if ($natureza_operacao_z == "ARMAZENAGEM") {
					$natureza_operacao_z = "ARMAZ.";
				} elseif ($natureza_operacao_z == "VENDA") {
					$natureza_operacao_z = "VENDA";
				} else {
					$natureza_operacao_z = "";
				}


				$data_emissao_print = date('d/m/Y', strtotime($data_emissao_z));
				$quantidade_print = number_format($quantidade_z, 0, ",", ".") . " " . $unidade_print_p;
				$valor_unitario_print = number_format($valor_unitario_z, 2, ",", ".");
				$valor_total_print = number_format($valor_total_z, 2, ",", ".");

				if (!empty($usuario_cadastro_z)) {
					$dados_cadastro_z = " &#13; Cadastrado por: " . $usuario_cadastro_z . " " . date('d/m/Y', strtotime($data_cadastro_z)) . " " . $hora_cadastro_z;
				}

				if (!empty($usuario_alteracao_z)) {
					$dados_alteracao_z = " &#13; Editado por: " . $usuario_alteracao_z . " " . date('d/m/Y', strtotime($data_alteracao_z)) . " " . $hora_alteracao_z;
				}
				// ======================================================================================================

				// RELATORIO =========================
				echo "
	<div style='width:65px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:5px; text-align:center'>
	<div style='margin-top:2px; margin-left:0px'>$data_emissao_print</div></div>
	<div style='width:195px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; overflow:hidden'>
	<div style='margin-top:2px; margin-left:5px; float:left'>$produtor_nome_z</div></div>
	<div style='width:90px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$produtor_cpf_cnpj</div></div>
	<div style='width:85px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$numero_nf_z</div></div>
	<div style='width:45px; height:18px; border:1px solid #000; float:left; font-size:9px; margin-left:4px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$natureza_operacao_z</div></div>
	<div style='width:70px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; text-align:center'>
	<div style='margin-top:2px; margin-left:5px'>$quantidade_print</div></div>
	<div style='width:50px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; text-align:right'>
	<div style='margin-top:2px; margin-right:5px'>$valor_unitario_print</div></div>
	<div style='width:75px; height:18px; border:1px solid #000; float:left; font-size:10px; margin-left:4px; text-align:right'>
	<div style='margin-top:2px; margin-right:5px'>$valor_total_print</div></div>
	
	<div id='centro' style='height:2px; width:725px; border:0px solid #999; margin:auto; float:left'></div>";
			}
			echo "
</div>
";


			?>




		</div>
		<!-- ================== FIM DO RELATORIO ================= -->














		<!-- =============================================================================================== -->
		<!-- =============================================================================================== -->
		<!-- =============================================================================================== -->

		<div style="width:720px; height:75px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center">

			<div style="width:710px; height:43px; border:0px solid #000; font-size:10px; float:left" align="left">
				<?php
				if ($transferencia_filiais == "CQ") {
					echo "<i>* CAF&Eacute; QUENTE</i>";
				}
				?>
			</div>

			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">__________________________</div>

			<div style="width:710px; height:10px; border:0px solid #000; font-size:17px; float:left" align="center"></div>

			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Balan&ccedil;a</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Armaz&eacute;m</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Compras</div>
			<div style="width:160px; height:10px; border:0px solid #000; font-size:11px; float:left; margin-left:20px" align="center">Fornecedor / Produtor</div>

		</div>



		<div style="width:750px; height:10px; border:0px solid #000; margin-left:10px; margin-top:20px; font-size:17px; float:left" align="center"></div>
		<div style="width:750px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
			<hr style="border:1px solid #999" />
		</div>




		<!-- =============================================================================================== -->
		<div style="width:740px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
			<div style="width:280px; height:25px; border:0px solid #000; font-size:10px; float:left" align="left">
				<?php echo "&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m"; ?>
			</div>

			<div style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:left" align="center">
				<?php echo "$usuario_cadastro_w" ?>
			</div>

			<div style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:left" align="center">
				<?php echo date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w ?>
			</div>

			<div style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:right" align="right">
				<?php echo "FILIAL: $filial_w" ?>
			</div>
		</div>
		<!-- =============================================================================================== -->

		<!-- =============================================================================================== -->
		<!-- =============================================================================================== -->
		<!-- =============================================================================================== -->





	</div>

</body>

</html>
<!-- ==================================================   FIM   ================================================= -->