<?php
// ================================================================================================================
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'romaneio_visualizar';
$titulo = 'Estoque - Romaneio de Entrada';
$modulo = 'compras';
$menu = 'ficha_produtor';
// ================================================================================================================

// ====== RECEBE POST =============================================================================================
$botao = $_POST["botao"] ?? '';
$data_inicial = $_POST["data_inicial"] ?? '';
$data_final = $_POST["data_final"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$filial = $filial_usuario;

$numero_compra = $_POST["numero_compra"] ?? '';
$numero_romaneio = $_POST["numero_romaneio"] ?? '';
$fornecedor = $_POST["fornecedor"] ?? '';
$cod_produto = $_POST["cod_produto"] ?? '';
$monstra_situacao = $_POST["monstra_situacao"] ?? '';

$numero_romaneio_w = $_POST["numero_romaneio"] ?? '';
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio_w' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows($busca_romaneio);

for ($x = 1; $x <= $linha_romaneio; $x++) {
	$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6], 0, ",", ".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7], 0, ",", ".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto, 0, ",", ".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8], 0, ",", ".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9], 0, ",", ".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10], 0, ",", ".");
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$sit_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28], 0, ",", ".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$usuario_cadastro = $aux_romaneio[19];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
$usuario_alteracao = $aux_romaneio[22];

if ($usuario_alteracao == "") {
	$dados_alteracao = "";
} else {
	$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
	$hora_alteracao = $aux_romaneio[23];
	$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}
// ================================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0) {
	$descrisao_sacaria = "(Sem sacaria)";
} else {
	$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";
}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
if ($produto == "CAFE") {
	$quantidade_real = ($quantidade / 60);
} elseif ($produto == "CAFE_ARABICA") {
	$quantidade_real = ($quantidade / 60);
} elseif ($produto == "PIMENTA") {
	$quantidade_real = ($quantidade / 50);
} elseif ($produto == "CACAU") {
	$quantidade_real = ($quantidade / 60);
} elseif ($produto == "CRAVO") {
	$quantidade_real = ($quantidade / 60);
} elseif ($produto == "RESIDUO_CACAU") {
	$quantidade_real = ($quantidade / 60);
} else {
	$quantidade_real = 0;
}

$quantidade_real_print = number_format($quantidade_real, 2, ",", ".");
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf") {
	$cpf_cnpj = $aux_pessoa[3];
} else {
	$cpf_cnpj = $aux_pessoa[4];
}
// ======================================================================================================


// ====== SITUAÇÃO PRINT ===================================================================================
if ($sit_romaneio == "PRE_ROMANEIO") {
	$situacao_print = "Pr&eacute;-Romaneio";
} elseif ($sit_romaneio == "EM_ABERTO") {
	$situacao_print = "Em Aberto";
} elseif ($sit_romaneio == "FECHADO") {
	$situacao_print = "Fechado";
} else {
	$situacao_print = "-";
}
// ======================================================================================================


// ====== BUSCA ENTRADA =================================================================================
$busca_entrada = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$aux_busca_entrada = mysqli_fetch_row($busca_entrada);
$linha_entrada = mysqli_num_rows($busca_entrada);

if ($linha_entrada == 0) {
	$num_registro_entrada = "(Romaneio ainda n&atilde;o vinculado a ficha)";
} else {
	$num_registro_entrada = $aux_busca_entrada[1];
}
// ======================================================================================================


// ====== SOMA NOTAS FISCAIS ======================================================================
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0], 2, ",", ".");
// ======================================================================================================


// ================================================================================================================
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
	<div id="topo_geral">
		<?php include('../../includes/topo.php'); ?>
	</div>


	<!-- ====== MENU ================================================================================================== -->
	<div id="menu_geral">
		<?php include('../../includes/menu_estoque.php'); ?>
		<div class="submenu">
			<?php include('../../includes/submenu_estoque_entrada.php'); ?>
		</div>
	</div>



	<!-- ====== CENTRO ================================================================================================= -->
	<div class="ct_1">


		<!-- ============================================================================================================= -->
		<div class="espacamento_15"></div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_1">
			<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
				Romaneio de Entrada
			</div>

			<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
				N&ordm; <?php echo "$numero_romaneio_w"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div class="ct_topo_2">
			<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
				<!-- Romaneio de Entrada -->
			</div>

			<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
				<?php echo "$data_print"; ?>
			</div>
		</div>
		<!-- ============================================================================================================= -->


		<!-- ============================================================================================================= -->
		<div id="centro" style="height:440px; width:1080px; border:0px solid #0000FF; margin:auto">


			<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
			<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:0px">
				<div style="margin-top:0px; margin-left:55px; color:#003466">Fornecedor</div>
			</div>
			<div id="centro" style="width:1030px; height:50px; border:1px solid #999; color:#003466; border-radius:0px; overflow:hidden; margin-left:25px; background-color:#EEE">

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:670px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$fornecedor_print</b>" ?></div>
				</div>
				<div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; color:#003466">
					<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$cpf_cnpj</b>" ?></div>
				</div>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:670px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$cidade_fornecedor - $estado_fornecedor</b>" ?></div>
				</div>
				<div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; color:#003466">
					<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$telefone_fornecedor</b>" ?></div>
				</div>

			</div>


			<!-- ================================================= PRODUTO ============================================================================= -->
			<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:8px">
				<div style="margin-top:0px; margin-left:55px; color:#003466">Produto</div>
			</div>

			<div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
				<div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
					<div style="margin-top:9px; margin-left:5px; float:left"><?php echo "<b>$produto_print</b>" ?></div>
				</div>
			</div>
			<!--
    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div></div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div></div>
    </div>

    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" 
																	?></div></div>
    </div>
-->


			<!-- =============================================== DADOS DO ROMANEIO ============================================================================= -->
			<div id="centro" style="width:1055px; height:270px; border:0px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:0px">
					<div style="margin-top:5px">Peso Inicial</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
					<div style="margin-top:5px">Peso Final</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
					<div style="margin-top:5px">Peso L&iacute;quido</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
					<div style="margin-top:5px">Desconto Sacaria</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
					<div style="margin-top:5px">Outros Descontos</div>
				</div>
				<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
					<div style="margin-top:5px">Peso Desc. Sacaria</div>
				</div>

				<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_inicial_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_final_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$peso_bruto_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$desconto_sacaria_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "$desconto_print Kg" ?></div>
				</div>
				<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
					<div style="margin-top:5px"><?php echo "<b>$quantidade_print</b> Kg" ?></div>
				</div>


				<!-- ================================================= DADOS DO ROMANEIO ============================================================================= -->
				<div style="width:1025px; height:30px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Real em Sacas:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "<b>$quantidade_real_print Sacas</b>" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Situa&ccedil;&atilde;o Romaneio:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$situacao_print" ?></div>
				</div>




				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Volume Sacas:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$quant_volume" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$num_romaneio_manual" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$descrisao_sacaria" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Filial Origem:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$filial_origem" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quantidade Sacaria:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$quant_sacaria" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Motorista:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$motorista" ?></div>
				</div>


				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Desc. Previsto (Qualidade):</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$desconto_previsto Kg" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$motorista_cpf" ?></div>
				</div>



				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Registro de Ficha:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$num_registro_entrada" ?></div>
				</div>

				<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div>
					<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$placa_veiculo" ?></div>
				</div>


				<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

				<div style="width:975px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
					<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
					<div style="margin-top:3px; margin-left:5px; width:650px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php echo "$observacao" ?></div>
				</div>


			</div>



			<!-- ============================================================================================================== -->


			<div id="tabela_2" style="width:1055px; height:20px; border:0px solid #000; font-size:12px; margin-top:15px">
				<div style="width:800px; margin-left:25px; color:#003466; float:left; border:0px solid #000">Notas Fiscais de Entrada:</div>
				<div style="width:200px; color:#003466; float:right; border:0px solid #000; text-align:right">Total R$ <?php echo "$soma_nota_fiscal_print"; ?></div>
			</div>



		</div>






		<!-- ================== INICIO DO RELATORIO ================= -->
		<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:0px;">

			<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
			<?php
			$busca_nota_fiscal = mysqli_query($conexao, "SELECT * FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio_w' ORDER BY data_emissao");
			$linha_nota_fiscal = mysqli_num_rows($busca_nota_fiscal);


			if ($linha_nota_fiscal == 0) {
				echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem notas fiscais para este romaneio.</i></div>";
			} else {
				echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='100px' height='20px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='360px' align='center' bgcolor='#006699'>Produtor</td>
<td width='120px' align='center' bgcolor='#006699'>N&ordm; Nota Fiscal</td>
<td width='160px' align='center' bgcolor='#006699'>Natureza da Orepação</td>
<td width='140px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='120px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='140px' align='center' bgcolor='#006699'>Valor Total</td>
</tr>
</table>
</div>";
			}

			echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table class='tabela_geral'>";

			for ($w = 1; $w <= $linha_nota_fiscal; $w++) {
				$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

				// DADOS DO FAVORECIDO =========================
				$busca_favorecido_2 = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
				$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
				$codigo_pessoa_2 = $aux_busca_favorecido_2[35];

				$busca_pessoa_2 = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
				$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
				$nome_favorecido_2 = $aux_busca_pessoa_2[1];
				$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
				if ($tipo_pessoa_2 == "pf") {
					$cpf_cnpj_2 = $aux_busca_pessoa_2[3];
				} else {
					$cpf_cnpj_2 = $aux_busca_pessoa_2[4];
				}

				$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));
				$numero_nf_print_2 = $aux_nota_fiscal[3];
				$valor_unitario_print_2 = number_format($aux_nota_fiscal[5], 2, ",", ".");
				$valor_total_print_2 = number_format($aux_nota_fiscal[8], 2, ",", ".");
				$unidade_print_2 = $aux_nota_fiscal[6];
				$quantidade_print_2 = $aux_nota_fiscal[7];
				$observacao_print_2 = $aux_nota_fiscal[8];
				$natureza_operacao_nf = $aux_nota_fiscal[26];


				// RELATORIO =========================
				echo "
	<tr class='tabela_1' title=' CPF/CNPJ: $cpf_cnpj_2 &#13; Observa&ccedil;&atilde;o: $observacao_print_2'>
	<td width='100px' align='left'>&#160;&#160;$data_nf_print_2</td>
	<td width='360px' align='left'>&#160;&#160;$nome_favorecido_2</td>
	<td width='120px' align='center'>$numero_nf_print_2</td>
	<td width='160px' align='center'>$natureza_operacao_nf</td>
	<td width='140px' align='center'>$quantidade_print_2 $unidade_print_2</td>
	<td width='120px' align='right'>$valor_unitario_print_2&#160;&#160;</td>
	<td width='140px' align='right'>&#160;&#160;$valor_total_print_2&#160;&#160;</td>
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


		<div class="espacamento_15"></div>


		<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
			<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>
			<?php
			echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
			<input type='hidden' name='numero_compra' value='$numero_compra'>
			<input type='hidden' name='botao' value='botao'>
			<input type='hidden' name='data_inicial' value='$data_inicial'>
			<input type='hidden' name='data_final' value='$data_final'>
			<input type='hidden' name='cod_produto' value='$cod_produto'>
			<input type='hidden' name='fornecedor' value='$fornecedor'>
			<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
			</form>
			</div>";

			echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_impressao.php' method='post' target='_blank'>
			<input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
			<input type='hidden' name='num_romaneio_aux' value='$numero_romaneio_w'>
			<input type='hidden' name='numero_compra' value='$numero_compra'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Romaneio</button>
			</form>
			</div>";
			?>
		</div>





		<div class="espacamento_30"></div>

		<div id="centro" style="width:1050px; height:25px; border:1px solid #999; color:#003466; border-radius:0px; overflow:hidden; margin:auto; background-color:#EEE">
			<div style="margin-left:20px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo "<i>$dados_cadastro</i>" ?></div>
			<div style="margin-left:40px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo "<i>$dados_alteracao</i>" ?></div>
		</div>



		<!-- ============================================================================================================= -->
		<div class="espacamento_20"></div>
		<!-- ============================================================================================================= -->





	</div>
	<!-- ====== FIM DIV CT_1 ========================================================================================= -->




	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>