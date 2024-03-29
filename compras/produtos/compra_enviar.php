<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "compra_enviar";
$titulo = "Compra de Produto";
$modulo = "compras";
$menu = "compras";



// =========== CONVERTE DATA ==========================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data)
{
	if (strstr($data, "/")) //verifica se tem a barra
	{
		$d = explode("/", $data); //tira a barra
		$rstData = "$d[2]-$d[1]-$d[0]"; //separa as datas $d[2] = ano $d[1] = mes etc...
		return $rstData;
	}
}
// ======================================================================================================


// ========== CONVERTE VALOR ============================================================================	
function ConverteValor($valor)
{
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =======================================================================================================


// ========== ELIMINA MÁSCARAS CPF E CNPJ ================================================================
function limpa_cpf_cnpj($limpa)
{
	$limpa = trim($limpa);
	$limpa = str_replace(".", "", $limpa);
	$limpa = str_replace(",", "", $limpa);
	$limpa = str_replace("-", "", $limpa);
	$limpa = str_replace("/", "", $limpa);
	return $limpa;
}
// ========================================================================================================


// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$botao = $_POST["botao"];
$numero_compra = $_POST["numero_compra"];
$quantidade = $_POST["quantidade"];
$preco_unitario = ConverteValor($_POST["preco_unitario"]);
//$preco_unitario_print = number_format($preco_unitario,2,",",".");
$preco_unitario_print = $_POST["preco_unitario"];
$valor_total = ($quantidade * $preco_unitario);
$valor_total_print = number_format($valor_total, 2, ",", ".");
$safra = $_POST["safra"];
$cod_tipo = $_POST["cod_tipo"];
$umidade = $_POST["umidade"];
$broca = $_POST["broca"];
$impureza = $_POST["impureza"];
$data_pagamento = ConverteData($_POST["data_pagamento"]);
$data_pgto = $_POST["data_pagamento"];
$observacao = $_POST["observacao"];
$data_compra = date('Y/m/d', time());
$filial = $filial_usuario;
$movimentacao = "COMPRA";
$data_pagamento_br = $_POST["data_pagamento"];
$data_pagamento = ConverteData($_POST["data_pagamento"]);
$situacao_pagamento = "EM_ABERTO";

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());

if (strtotime($data_pagamento) > strtotime($data_compra)) {
	$forma_pagamento = "PREVISAO";
}

$errno = $_POST[""] ?? 0;
$error = $_POST[""] ?? '';


// ====== BUSCA PRODUTO ================================================================================
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
$plano_conta = $aux_bp[35];
$plano_conta_mae = $aux_bp[41];
$produto_rovereti = str_replace($comAcentos, $semAcentos, $produto_print); // ==== INTEGRAÇÃO ROVERETI ===== ATENÇÃO: REVERETI não aceita "acentos"
$cod_class_gerencial = $aux_bp[24]; // ==== INTEGRAÇÃO ROVERETI =====
$cod_centro_custo = $aux_bp[25]; // ==== INTEGRAÇÃO ROVERETI =====
// ======================================================================================================


// ====== BUSCA FORNECEDOR ==============================================================================
$busca_fornecedor = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf" or $aux_forn[2] == "PF") {
	$cpf_cnpj = $aux_forn[3];
} else {
	$cpf_cnpj = $aux_forn[4];
}
$cpf_aux = limpa_cpf_cnpj($cpf_cnpj); // ==== INTEGRAÇÃO ROVERETI =====
$fornecedor_rovereti = str_replace($comAcentos, $semAcentos, $fornecedor_print); // ==== INTEGRAÇÃO ROVERETI =====
$situacao_compra = $aux_forn[40];
// ======================================================================================================


// ====== BUSCA FAVORECIDO ==============================================================================
$busca_favorecido = mysqli_query($conexao, "SELECT * FROM cadastro_favorecido WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa' ORDER BY nome LIMIT 1");
$aux_favorecido = mysqli_fetch_row($busca_favorecido);
$linhas_favorecido = mysqli_num_rows($busca_favorecido);

$cod_favorecido = $aux_favorecido[0];
$favorecido_print = $aux_favorecido[14];
// ========================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ==========================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ===========================================================================================================


// ====== BUSCA NUMERO DE COMPRA ==============================================================================
$busca_num_compra = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra'");
$achou_num_compra = mysqli_num_rows($busca_num_compra);
// ============================================================================================================


// ====== BUSCA CONTROLE DE TALAO  ============================================================================
$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$fornecedor' AND cod_produto='$cod_produto' AND devolvido='N' ORDER BY codigo");
$linha_talao = mysqli_num_rows($busca_talao);
// ===========================================================================================================


// ===========================================================================================================
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
		<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">


			<?php
			if ($fornecedor == '' or $quantidade == '' or $preco_unitario == '' or $cod_tipo == '') {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Formul&aacute;rio incompleto.</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					Campos obrigat&oacute;rios: Quantidade, Pre&ccedil;o e Tipo.
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} elseif ($preco_unitario <= 0) {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
						<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
						<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
						<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
						Pre&ccedil;o do produto inv&aacute;lido.</div>
						<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
						O pre&ccedil;o n&atilde;o pode ser menor ou igual a zero.
						</div>
						<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
							<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
							<input type='hidden' name='botao' value='erro_enviar' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='hidden' name='cod_produto' value='$cod_produto' />
							<input type='hidden' name='cod_tipo' value='$cod_tipo' />
							<input type='hidden' name='quantidade' value='$quantidade' />
							<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
							<input type='hidden' name='safra' value='$safra' />
							<input type='hidden' name='umidade' value='$umidade' />
							<input type='hidden' name='broca' value='$broca' />
							<input type='hidden' name='impureza' value='$impureza' />
							<input type='hidden' name='data_pagamento' value='$data_pagamento' />
							<input type='hidden' name='observacao' value='$observacao' />
							<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
							</form>
						</div>";
			} elseif ($achou_num_compra >= 1) {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Aten&ccedil;&atilde;o! compra j&aacute; realizada.</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					Pesquise nos relat&oacute;rios a compra de n&uacute;mero $numero_compra.
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} elseif ($linhas_fornecedor == 0) {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Fornecedor inexistente.</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} elseif ($preco_unitario > $preco_maximo and $permissao[41] != 'S') {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Compra n&atilde;o autorizada</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					Pre&ccedil;o de compra maior do que o pre&ccedil;o comercializado do dia.
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} elseif (($linha_talao > 1 and $permissao[45] != 'S') or $situacao_compra == 2) {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Compra n&atilde;o autorizada</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					Este produtor possui pend&ecirc;ncia de tal&atilde;o ou bloqueio para compras.
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} elseif (!is_numeric($quantidade)) {
				echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
					<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
					<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
					<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
					Quantidade inv&aacute;lida</div>
					<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
					Verifique o que foi digitado no campo quantidade.
					</div>
					<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
						<input type='hidden' name='botao' value='erro_enviar' />
						<input type='hidden' name='fornecedor' value='$fornecedor' />
						<input type='hidden' name='cod_produto' value='$cod_produto' />
						<input type='hidden' name='cod_tipo' value='$cod_tipo' />
						<input type='hidden' name='quantidade' value='$quantidade' />
						<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
						<input type='hidden' name='safra' value='$safra' />
						<input type='hidden' name='umidade' value='$umidade' />
						<input type='hidden' name='broca' value='$broca' />
						<input type='hidden' name='impureza' value='$impureza' />
						<input type='hidden' name='data_pagamento' value='$data_pagamento' />
						<input type='hidden' name='observacao' value='$observacao' />
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
						</form>
					</div>";
			} else {


				// ==================================================================
				// ATUALIZA SALDO ARMAZENADO ========================================
				include('../../includes/busca_saldo_armaz.php');
				$saldo = $saldo_produtor - $quantidade;
				include('../../includes/atualisa_saldo_armaz.php');
				// ==================================================================
				// ==================================================================



				// ==========================================================================================================================
				// INTEGRAÇÃO ROVERETI ======================================================================================================

				// ====== BUSCA CODIGO FILIAL =======================================================================
				$busca_filial = mysqli_query($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
				$cod_ifr = mysqli_fetch_row($busca_filial);
				$cod_integ_filial_rovereti = $cod_ifr[3];

				// ====== BUSCA CODIGO USUARIO =======================================================================
				// $usuario_rovereti = "INTEGRADOR.GRANCAFE";  // Agora busca do conecta_db.php
				// $key_rovereti = 25482; // Agora busca do conecta_db.php

				//	$cod_empresa_rovereti = "16"; Número alterado dia 02/07/2018 (Gustavo ligou informando o novo numero)
				$cod_empresa_rovereti = "50";
				$data_rovereti = date('d/m/Y', time());
				$desc_comp_rovereti = "COMPRA DE " . $produto_rovereti . " - " . $quantidade . " " . $unidade_print . " X " . $preco_unitario_print;
				$cpf_cnpj_rovereti = $cpf_aux;
				$observacao_rovereti = "# CADASTRO INTEGRACAO SUIF (USERNAME: " . $usuario_cadastro . ") " . " OBS: " . $observacao . " | TIPO: " . $tipo_print;
				$valor_rovereti = number_format($valor_total, 2, ",", "");

				//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
				//$token = sha1("USUARIO.TESTE"."18538"."ServiceToken"."05/04/2017");
   				$token = sha1($usuario_rovereti . $key_rovereti . "ServiceToken" . $data_rovereti);


				//PARAMETROS CADASTRO CONTA_PAGAR
				$parametros = '{
					"CodEmpresa":"' . $cod_empresa_rovereti . '",
					"CodIntegracaoFilial":"' . $cod_integ_filial_rovereti . '",
					"DscContaPagar":"' . utf8_encode($desc_comp_rovereti) . '",
					"NumCpfCnpj":"' . $cpf_cnpj_rovereti . '",
					"NomFornecedor":"' . utf8_encode($fornecedor_rovereti) . '",
					"NumDocumento":"' . $numero_compra . '",
					"DatEmissao":"' . $data_rovereti . '",
					"DatVencimento":"' . $data_pgto . '",
					"VlrConta":"' . $valor_rovereti . '",
					"VlrMultaAtraso":"",
					"VlrJurosAtrasoDia":"",
					"VlrDesconto":"",
					"DatLimiteDesconto":"",
					"NumAnoMesCompetencia":"",
					"IndContaReconhecida":"S",
					"CodIntegracaoAcaoContabil":"",
					"CodIntegracaoClassGerencial":"' . $cod_class_gerencial . '",
					"CodIntegracaoCentroCusto":"' . $cod_centro_custo . '",
					"DscObservacao":"' . utf8_encode($observacao_rovereti) . '",
					"CodIntegracaoContaPagar":"' . $numero_compra . '",
					"NomFavorecido":"",
					"NumCpfCnpjFavorecido":"",
					"NumBanco":"",
					"NumAgencia":"",
					"NumContaCorrente":"",
					"NumDigitoContaCorrente":"",
					"DscIdentificacaoUsuario":"' . $usuario_rovereti . '",
					"Key":"' . $key_rovereti . '",
					"Token":"' . $token . '"
					}';




				$url = $rovereti_api_IncluirContaPagar; //'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);

				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
				curl_setopt(
					$ch,
					CURLOPT_HTTPHEADER,
					array(
						'Content-Type:application/json',
						'Content-Length: ' . strlen($parametros)
					)
				);
				//curl_setopt($ch, CURLOPT_HEADER, 1);

				$retorno = curl_exec($ch);

				//	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				//	$header = substr($retorno, 0, $header_size);
				//	$body = substr($retorno, $header_size);
				// $jsonResultData = json_decode($retorno);

				// $exec  = curl_exec( $ch );  // estava chamando exec duas vezes
				$error = curl_error($ch);
				$errno = curl_errno($ch);

				$retorno_rovereti = '';
				$errorSUIF = 'Dados do SUIF atualizados com sucesso!';

				curl_close($ch);

				// Exec retornou falso?
				if (Trim($error) != '') {
					$msg_rovereti = 'Erro: Esta compra não foi lançada no ROVERETI.';
					$erro_rovereti = 'sim';

					// ERRO NA ATUALIZAÇÃO DO ROVERETI
					echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
						<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
						<img src='$servidor/$diretorio_servidor/imagens/icones/atencao_vermelho.png' border='0' /></div>
						<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
						Erro na atualização do Rovereti. Verifique a mensagem abaixo: </div>
						<h4 id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
						$retorno</h4>
						<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
						$error.
						</div>
						<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
							<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' method='post' />
							<input type='hidden' name='botao' value='erro_enviar' />
							<input type='hidden' name='fornecedor' value='$fornecedor' />
							<input type='hidden' name='cod_produto' value='$cod_produto' />
							<input type='hidden' name='cod_tipo' value='$cod_tipo' />
							<input type='hidden' name='quantidade' value='$quantidade' />
							<input type='hidden' name='preco_unitario' value='$preco_unitario_print' />
							<input type='hidden' name='safra' value='$safra' />
							<input type='hidden' name='umidade' value='$umidade' />
							<input type='hidden' name='broca' value='$broca' />
							<input type='hidden' name='impureza' value='$impureza' />
							<input type='hidden' name='data_pagamento' value='$data_pagamento' />
							<input type='hidden' name='observacao' value='$observacao' />
							<input type='hidden' name='error' value='$error' />
							<input type='hidden' name='errno' value='$errno' />
							<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
							</form>
						</div>";
		
				} else {
					$msg_rovereti = "Compra ".$numero_compra." lançada no ROVERETI com Sucesso!";
					$erro_rovereti = 'nao';

					$retorno_rovereti = "$retorno $error $errno";

					// ========================================================================================================================================================

					$inserir = mysqli_query(
						$conexao,
						"INSERT INTO compras
									(codigo,
									numero_compra,
									fornecedor,
									produto,
									data_compra,
									quantidade,
									preco_unitario,
									valor_total,
									unidade,
									safra,
									tipo,
									broca,
									umidade,
									observacao,
									data_pagamento,
									situacao_pagamento,
									movimentacao,
									usuario_cadastro,
									hora_cadastro,
									data_cadastro,
									estado_registro,
									filial,
									quantidade_original,
									valor_total_original,
									cod_produto,
									cod_unidade,
									cod_tipo,
									fornecedor_print,
									impureza,
									quantidade_original_primaria,
									retorno_rovereti,
									total_pago,
									saldo_pagar,
									plano_conta_mae,
									plano_conta)
								VALUES
									(NULL,
									'$numero_compra',
									'$fornecedor',
									'$produto_print',
									'$data_compra',
									'$quantidade',
									'$preco_unitario',
									'$valor_total',
									'$unidade_print',
									'$safra',
									'$tipo_print',
									'$broca',
									'$umidade',
									'$observacao',
									'$data_pagamento',
									'$situacao_pagamento',
									'$movimentacao',
									'$usuario_cadastro',
									'$hora_cadastro',
									'$data_cadastro',
									'ATIVO',
									'$filial',
									'$quantidade',
									'$valor_total',
									'$cod_produto',
									'$cod_unidade',
									'$cod_tipo',
									'$fornecedor_print',
									'$impureza',
									'$quantidade',
									'$retorno_rovereti',
									'0',
									'$valor_total',
									'$plano_conta_mae',
									'$plano_conta')"
					);

					if ($inserir ) {
						if ($forma_pagamento == "PREVISAO") {
							$inserir_pgto = mysqli_query($conexao, "INSERT INTO favorecidos_pgto (codigo, codigo_compra, codigo_favorecido, forma_pagamento, data_pagamento, valor, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, situacao_pagamento, filial, codigo_pessoa, produto, favorecido_print, cod_produto) 
						VALUES (NULL, '$numero_compra', '$cod_favorecido', 'PREVISAO', '$data_pagamento', '$valor_total', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', 'EM_ABERTO', '$filial', '$codigo_pessoa', '$produto_print', '$favorecido_print', '$cod_produto')");

							if (!$inserir_pgto) {
								$errorSUIF = 'Erro ao grava no SUIF (favorecido): ' . $inserir_pgto;
							}
						} 

					} else {
						$errorSUIF = 'Erro ao grava no SUIF (compras): ' . $inserir;
					}

					// ========================================================================================================================================================

					echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
						<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
						<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
						<div id='centro' style='float:left; height:25px; width:1080px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
						Compra realizada com sucesso!</div>
						<div id='centro' style='float:left; height:250px; width:1080px; color:#00F; text-align:center; border:0px solid #000'>
							<div style='float:left; width:200px; height:230px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
					
							<!-- =========  PRODUTO ============================================================================= -->
							<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
							Produto:</div>
							<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							</div>
							<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:681px; height:20px; border:1px solid #999; float:left; color:#009900; text-align:left; font-size:14px; 
							border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'><b>$produto_print</b></div></div>
							<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

							
							<!-- =========  NUMERO DA COMPRA E FORNECEDOR ============================================================================= -->
							<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
							N&uacute;mero da Compra:</div>
							<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Fornecedor:</div>
							<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$numero_compra</div></div>
							<div style='width:504px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$fornecedor_print</div></div>
							<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

							
							<!-- =========  QUANTIDADE, PREÇO, SAFRA E TIPO ============================================================================= -->
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
							Quantidade:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Pre&ccedil;o:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Safra:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Tipo:</div>
							<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$quantidade $unidade_print</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>R$ $preco_unitario_print</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$safra</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$tipo_print</div></div>
							<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

							<!-- =========  UMIDADE, BROCA, IMPUREZA E VALOR TOTAL ============================================================================= -->
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
							Umidade:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Broca:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Impureza:</div>
							<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
							Valor Total:</div>
							<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$umidade</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$broca</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$impureza</div></div>
							<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>R$ $valor_total_print</div></div>
							<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

							<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

							<!-- =========  OBSERVACAO ============================================================================= -->
							<div style='width:680px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
							Observa&ccedil;&atilde;o:</div>
							<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


							<div style='width:680px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
							border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$observacao</div></div>
							<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

						</div>
						
						<div id='centro' style='float:left; height:50px; width:168px; color:#00F; text-align:center; border:0px solid #000'>
						</div>

						<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/produtos/compra_impressao.php' method='post' target='_blank'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Imprimir Compra</button>
						</form>
						</div>

						<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post'>
						<input type='hidden' name='numero_compra' value='$numero_compra'>
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Forma de Pagamento</button>
						</form>
						</div>


						<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
						<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
						<input type='hidden' name='fornecedor' value='$fornecedor'>
						<input type='hidden' name='cod_produto' value='$cod_produto'>
						<input type='hidden' name='botao' value='seleciona'>
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Ficha do Produtor</button>
						</form>
						</div>


						<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
						<a href='$servidor/$diretorio_servidor/compras/produtos/compra_selecionar.php' >
						<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Nova Compra</button></a>
						</div>";
					echo "<div id='centro' style='float:left; height:25px; width:1080px; color:#696969; text-align:center; 	border:0px solid #000; font-size:11px'>
					<h4>$msg_rovereti</h4></div>
					<div id='centro' style='float:left; height:25px; width:1080px; color:#696969; text-align:center; 	border:0px solid #000; font-size:11px'>
					<h4>$errorSUIF</h4></div>
					<br>";
								
				}
			}

?>

		</div>
	</div><!-- FIM DIV CENTRO GERAL -->


	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>