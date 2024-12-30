<?php
// ================================================================================================================
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'editar_3_formulario';
$titulo = 'Editar Romaneio de Entrada';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"] ?? '';
$filial = $filial_usuario;

$numero_romaneio = $_POST["numero_romaneio"] ?? '';
$fornecedor_form = $_POST["fornecedor_form"] ?? '';
$cod_produto_form = $_POST["cod_produto_form"] ?? '';

$data_inicial_busca = $_POST["data_inicial_busca"] ?? '';
$data_final_busca = $_POST["data_final_busca"] ?? '';
$fornecedor_busca = $_POST["fornecedor_busca"] ?? '';
$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
$numero_romaneio_busca = $_POST["numero_romaneio_busca"] ?? '';
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"] ?? '';
$forma_pesagem_busca = $_POST["forma_pesagem_busca"] ?? '';

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
// ================================================================================================================


// ================================================================================================================
if ($numero_romaneio == "") {
	header("Location: $servidor/$diretorio_servidor/estoque/entrada/romaneio_nao_localizado_2.php");
	exit;
}
// ================================================================================================================


// ====== ALTERA PRODUTO ==========================================================================================
if ($botao == "ALTERAR_PRODUTO") {
	$editar = mysqli_query($conexao, "UPDATE estoque SET produto='$cod_produto_form', cod_produto='$cod_produto_form', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE numero_romaneio='$numero_romaneio'");
}
// ================================================================================================================


// ====== ALTERA FORNECEDOR ========================================================================================
if ($botao == "ALTERAR_FORNECEDOR") {
	$editar = mysqli_query($conexao, "UPDATE estoque SET fornecedor='$fornecedor_form', fornecedor_print='$fornecedor_form', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE numero_romaneio='$numero_romaneio'");
}
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' AND movimentacao='ENTRADA' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows($busca_romaneio);
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

$fornecedor = $aux_romaneio[2];
$data_romaneio = $aux_romaneio[3];
$data_romaneio_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$cod_tipo_produto = $aux_romaneio[46];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6], $config[31], ",", ".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7], $config[31], ",", ".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto, $config[31], ",", ".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8], $config[31], ",", ".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9], $config[31], ",", ".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10], $config[31], ",", ".");
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = $aux_romaneio[28];
$quant_sacaria_print = number_format($aux_romaneio[28], $config[31], ",", ".");
$quant_volume_form = $aux_romaneio[39];
$quant_volume_print = number_format($aux_romaneio[39], $config[31], ",", ".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$cod_lote = $aux_romaneio[48];
// ================================================================================================================



// ====== MONTA FORMULÁRIO ===================================================================================
if ($botao == "ERRO") {
	$fornecedor_form = $_POST["fornecedor_form"];
	$cod_produto_form = $_POST["cod_produto_form"];

	$peso_form = $_POST["peso_form"];
	$peso_inicial_form = $_POST["peso_inicial_form"];
	$peso_final_form = $_POST["peso_final_form"];
	$cod_sacaria_form = $_POST["cod_sacaria_form"];
	$quant_sacaria_form = $_POST["quant_sacaria_form"];
	$desconto_form = $_POST["desconto_form"];
	$quant_volume_form = $_POST["quant_volume_form"];
	$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
	$romaneio_manual_form = $_POST["romaneio_manual_form"];
	$filial_origem_form = $_POST["filial_origem_form"];
	$motorista_form = $_POST["motorista_form"];
	$motorista_cpf_form = $_POST["motorista_cpf_form"];
	$placa_veiculo_form = $_POST["placa_veiculo_form"];
	$obs_form = $_POST["obs_form"];
} else {
	$fornecedor_form = $fornecedor;
	$cod_produto_form = $cod_produto;

	$peso_form = $peso_inicial;
	$peso_inicial_form = $peso_inicial;
	$peso_final_form = $peso_final;
	$cod_sacaria_form = $t_sacaria;
	$quant_sacaria_form = $quant_sacaria;
	$desconto_form = $desconto;
	$quant_volume_form = $quant_volume_form;
	$cod_tipo_produto_form = $cod_tipo_produto;
	$romaneio_manual_form = $num_romaneio_manual;
	$filial_origem_form = $filial_origem;
	$motorista_form = $motorista;
	$motorista_cpf_form = $motorista_cpf;
	$placa_veiculo_form = $placa_veiculo;
	$obs_form = $observacao;
}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21], 2, ",", ".");
$nome_imagem_produto = $aux_bp[28];
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
// =======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_form' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf") {
	$cpf_cnpj = $aux_forn[3];
} else {
	$cpf_cnpj = $aux_forn[4];
}

if ($linhas_fornecedor == 0) {
	$cidade_uf_fornecedor = "";
} else {
	$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";
}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($fornecedor_form == "") {
	$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um fornecedor</div>";
} elseif ($linhas_fornecedor == 0) {
	$erro = 2;
	$msg = "<div style='color:#FF0000'>Fornecedor inv&aacute;lido</div>";
} elseif ($cod_produto_form == "" or $linhas_bp == 0) {
	$erro = 3;
	$msg = "<div style='color:#FF0000'>Selecione um produto</div>";
} else {
	$erro = 0;
	$msg = "";
}
// ======================================================================================================


// =============================================================================
include('../../includes/head.php');
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
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
		<?php include("../../includes/menu_estoque.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_estoque_entrada.php"); ?>
	</div>




	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_1" style="height:560px">


		<!-- ============================================================================================================= -->
		<div class="espacamento_15"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div style="height:40px; border:0px solid #000">
			<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
				Editar Romaneio de Entrada
			</div>


			<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
				N&ordm; <?php echo "$numero_romaneio"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div style="height:40px; border:0px solid #000">
			<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
				<?php echo "$msg"; ?>
			</div>

			<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
				<?php echo "$data_hoje"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div style="height:160px; width:1080px; border:0px solid #0000FF; margin:auto">
			<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/entrada/editar_4_enviar.php" method="post" />
			<input type="hidden" name="botao" value="EDITAR_ROMANEIO" />
			<input type="hidden" name="fornecedor_form" value="<?php echo "$fornecedor_form"; ?>" />
			<input type="hidden" name="cod_produto_form" value="<?php echo "$cod_produto_form"; ?>" />
			<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />


			<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
			<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
				<div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
					Fornecedor:
				</div>
			</div>

			<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

				<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

				<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$fornecedor_print</b>" ?></div>
				</div>

				<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$cpf_cnpj</b>" ?></div>
				</div>

				<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

				<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$cidade_uf_fornecedor</b>" ?></div>
				</div>

				<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
					<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
					<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo "<b>$telefone_fornecedor</b>" ?></div>
				</div>

			</div>
			<!-- ======================================================================================================================= -->


			<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
			<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
					Produto:
				</div>
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
					<!-- xxxxxxxxxxxxxx: -->
				</div>
				<div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
					<!-- xxxxxxxxxxxxxx: -->
				</div>
			</div>

			<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
				<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
					<?php echo "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
				</div>

				<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
					<?php echo "<b>$produto_print_2</b>" ?>
				</div>
			</div>

			<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" 
		?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" 
		?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" 
		?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" 
		?>
    </div>
</div>
-->
			<!-- ======================================================================================================================= -->


		</div>
		<!-- ======================================================================================================================= -->


		<div class="espacamento_10"></div>


		<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
		<div style="width:1030px; height:195px; margin:auto; border:1px solid transparent">


			<?php
			if ($filial_config[9] == "S") {
				if ($permissao[113] == 'S') {
					echo "
	<!-- =======  PESO INICIAL =========================================================================================== -->
		<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
			<div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
			Peso Inicial (Kg):
			</div>
			
			<div style='width:167px; height:25px; float:left; border:1px solid transparent'>
			<input type='text' name='peso_inicial_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
			onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_inicial_form' />
			</div>
		</div>
	<!-- ================================================================================================================ -->
	
	
	<!-- =======  PESO FINAL =========================================================================================== -->
		<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
			<div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
			Peso Final (Kg):
			</div>
			
			<div style='width:167px; height:25px; float:left; border:1px solid transparent'>
			<input type='text' name='peso_final_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
			onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_final_form' />
			</div>
		</div>
	<!-- ================================================================================================================ -->
	";
				} else {
					echo "
	<!-- =======  PESO INICIAL =========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Inicial:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<input type='hidden' name='peso_inicial_form' value='$peso_inicial_form' />
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; color:#003466; overflow:hidden'>$peso_inicial_form Kg</div></div>
		</div>
	</div>
	<!-- ================================================================================================================ -->
	
	
	<!-- =======  PESO FINAL =========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Final:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<input type='hidden' name='peso_final_form' value='$peso_final_form' />
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; color:#003466; overflow:hidden'>$peso_final_form Kg</div></div>
		</div>
	</div>
	<!-- ================================================================================================================ -->
	";
				}
			} else {
				if ($permissao[113] == 'S') {
					echo "
	<!-- =======  PESO ================================================================================================== -->
		<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
			<div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
			Peso (Kg):
			</div>
			
			<div style='width:167px; height:25px; float:left; border:1px solid transparent'>
			<input type='text' name='peso_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
			onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_form' />
			</div>
		</div>
	<!-- ================================================================================================================ -->
	";
				} else {
					echo "
	<!-- =======  PESO ================================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<input type='hidden' name='peso_form' value='$peso_form' />
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; color:#003466; overflow:hidden'>$peso_form Kg</div></div>
		</div>
	</div>
	<!-- ================================================================================================================ -->
	";
				}
			}

			?>





			<!-- =======  TIPO SACARIA ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Tipo Sacaria:"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<select name="cod_sacaria_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
					<option></option>
					<?php

					$busca_tipo_sacaria = mysqli_query($conexao, "SELECT * FROM select_tipo_sacaria WHERE movimentacao='ENTRADA' AND estado_registro='ATIVO' ORDER BY codigo");
					$linhas_tipo_sacaria = mysqli_num_rows($busca_tipo_sacaria);

					for ($t = 1; $t <= $linhas_tipo_sacaria; $t++) {
						$aux_tipo_sacaria = mysqli_fetch_row($busca_tipo_sacaria);

						if ($aux_tipo_sacaria[0] == $cod_sacaria_form) {
							echo "<option selected='selected' value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";
						} else {
							echo "<option value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= QUANTIDADE SACARIA =========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Quant. de Sacaria (Un):"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="quant_sacaria_form" class="form_input" maxlength="12" onkeypress="<?php echo "$config[30]"; ?>" onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo "$quant_sacaria_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= OUTROS DESCONTOS =========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Outros Descontos (Kg):"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="desconto_form" class="form_input" maxlength="12" onkeypress="<?php echo "$config[30]"; ?>" onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo "$desconto_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= QUANTIDADE VOLUME SACAS ================================================================================= -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Quant. Volume de Sacas:"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="quant_volume_form" class="form_input" maxlength="12" onkeypress="<?php echo "$config[30]"; ?>" onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center; color:#999" disabled="disabled" value="<?php echo "$quant_volume_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- =======  TIPO PRODUTO ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Tipo do Produto:"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<select name="cod_tipo_produto_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
					<option></option>
					<?php
					$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto_form' AND estado_registro='ATIVO' ORDER BY codigo");
					$linhas_tipo_produto = mysqli_num_rows($busca_tipo_produto);

					for ($tp = 1; $tp <= $linhas_tipo_produto; $tp++) {
						$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);

						if ($aux_tipo_produto[0] == $cod_tipo_produto_form) {
							echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
						} else {
							echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
						}
					}
					?>
					</select>
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<!-- ======= NUMERO ROMANEIO MANUAL ========================================================================================== -->
			<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
					<?php echo "N&ordm; Romaneio Manual:"; ?>
				</div>

				<div style="width:167px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="romaneio_manual_form" class="form_input" maxlength="10" onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo "$romaneio_manual_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->


			<?php
			if ($config[34] == "S") {
				echo "
<!-- ======= MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='motorista_form' class='form_input' maxlength='25' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$motorista_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";
			}




			if ($config[35] == "S") {
				echo "
<!-- ======= CPF MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        CPF Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='motorista_cpf_form' class='form_input' maxlength='14' onkeypress='mascara(this,num_cpf)' onBlur='mascara(this,num_cpf)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$motorista_cpf_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";
			}




			if ($config[36] == "S") {
				echo "
<!-- ======= PLACA VEICULO ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Placa do Ve&iacute;culo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='placa_veiculo_form' class='form_input' maxlength='20' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$placa_veiculo_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";
			}




			if ($config[33] == "S") {
				echo "
<!-- =======  FILIAL ORIGEM ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Filial Origem:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <select name='filial_origem_form' class='form_select' onkeydown='if (getKey(event) == 13) return false;' style='width:154px' />
        <option></option>";
				$busca_filial_origem = mysqli_query($conexao, "SELECT * FROM filiais ORDER BY codigo");
				$linhas_filial_origem = mysqli_num_rows($busca_filial_origem);

				for ($f = 1; $f <= $linhas_filial_origem; $f++) {
					$aux_filial_origem = mysqli_fetch_row($busca_filial_origem);

					if ($aux_filial_origem[1] == $filial_origem_form) {
						echo "<option selected='selected' value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";
					} else {
						echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";
					}
				}
				echo "
        </select>
        </div>
	</div>
<!-- ================================================================================================================ -->
";
			}
			?>


			<!-- =======  OBSERVAÇÃO ============================================================================================ -->
			<div style="width:682px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
				<div class="form_rotulo" style="width:674px; height:17px; border:1px solid transparent; float:left">
					<?php echo "Observa&ccedil;&atilde;o:"; ?>
				</div>

				<div style="width:674px; height:25px; float:left; border:1px solid transparent">
					<input type="text" name="obs_form" class="form_input" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="width:663px; text-align:left" value="<?php echo "$obs_form"; ?>" />
				</div>
			</div>
			<!-- ================================================================================================================ -->




		</div>
		<!-- ===========  FIM DO FORMULÁRIO =========== -->





		<!-- ============================================================================================================= -->
		<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">

			<?php
			if ($erro == 0) {
				echo "
	<div id='centro' style='float:left; height:45px; width:235px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:45px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:45px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_2_selec_fornecedor.php' method='post'>
	<input type='hidden' name='botao' value='ALTERAR_FORNECEDOR'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Alterar Fornecedor</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:45px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_1_selec_produto.php' method='post'>
	<input type='hidden' name='botao' value='ALTERAR_PRODUTO'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Alterar Produto</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:45px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/$pagina_mae.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</form>
	</div>";
			} else {
				echo "
	<div id='centro' style='float:left; height:45px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:45px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_1_selecionar.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
	</div>";
			}

			?>
		</div>














	</div>
	<!-- ====== FIM DIV CT_1 ========================================================================================= -->



	<!-- ====== RODAPÉ =============================================================================================== -->
	<div class="rdp_1">
		<?php include('../../includes/rodape.php'); ?>
	</div>


	<!-- ====== FIM ================================================================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>