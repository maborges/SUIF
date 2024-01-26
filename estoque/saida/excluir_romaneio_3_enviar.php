<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_romaneio_3_enviar';
$titulo = 'Excluir Romaneio';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


// ====== RECEBE POST =============================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_inicial = $_POST["data_inicia_buscal"];
$data_final = $_POST["data_final_busca"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$filial = $filial_usuario;
$usuario_exclusao = $nome_usuario_print;
$hora_exclusao = date('G:i:s', time());
$data_exclusao = date('Y-m-d', time());


$num_romaneio_form = $_POST["num_romaneio_form"];
$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$motivo_exclusao = $_POST["motivo_exclusao"];
$motivo_obrigatorio = "NAO";
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$num_romaneio_form' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$cod_produto = $aux_romaneio[44];
$filial_registro = $aux_romaneio[25];
$transferencia_filiais = $aux_romaneio[53];
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
// ======================================================================================================


// ====== EXCLUI ROMANEIO ==========================================================================================
if ($motivo_obrigatorio == "SIM" and ($motivo_exclusao == "" or $motivo_exclusao == " "))
{
$erro = 1;
$msg = "<div style='color:#FF0000'>Motivo da exclus&atilde;o &eacute; obrigat&oacute;rio</div>";
}

elseif ($linha_romaneio == 0 or $num_romaneio_form == "")
{
$erro = 2;
$msg = "<div style='color:#FF0000'>Romaneio n&atilde;o localizado</div>";
}

else
{
// ====== EXCLUSAO ============
	if ($transferencia_filiais == "SIM")
	{
	$excluir_1 = mysqli_query ($conexao, "UPDATE estoque SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', 
	hora_exclusao='$hora_exclusao', data_exclusao='$data_exclusao', motivo_exclusao='$motivo_exclusao' WHERE numero_romaneio='$num_romaneio_form'");
	$excluir_2 = mysqli_query ($conexao, "UPDATE estoque SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', 
	hora_exclusao='$hora_exclusao', data_exclusao='$data_exclusao', motivo_exclusao='$motivo_exclusao' WHERE transferencia_numero='$num_romaneio_form'");
	}
	else
	{
	$excluir = mysqli_query ($conexao, "UPDATE estoque SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', 
	hora_exclusao='$hora_exclusao', data_exclusao='$data_exclusao', motivo_exclusao='$motivo_exclusao' WHERE numero_romaneio='$num_romaneio_form'");
	}
// ============================

// ====== ENVIAR E-MAIL ========
$busca_email = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_email = mysqli_fetch_row($busca_email);
$email_destinatario = $aux_email[16];
$email_remet = $aux_email[17];
$email_resposta = $aux_email[13];
$enviar_email = $aux_email[18];
$data_exclusao = date('d/m/Y', time());

	if ($enviar_email == "S")
	{
		$fornecedor_email = str_replace($comAcentos, $semAcentos, $fornecedor_print);
		$produto_email = str_replace($comAcentos, $semAcentos, $produto_print);
		$motivo_email = str_replace($comAcentos, $semAcentos, $motivo_exclusao);
		
		$email_assunto = "SUIF - Exclusao de Romaneio";
		
		$email_texto = "<font style='color:#090; font-size:22px'>&#8226; <b>Exclusao de Romaneio - $movimentacao</b></font><br />\n";
		$email_texto .= "Numero do romaneio: <b>$num_romaneio_form</b><br />\n";
		$email_texto .= "Cliente: <b>$fornecedor_email</b><br />\n";
		$email_texto .= "Produto: <b>$produto_email</b><br />\n";
		$email_texto .= "Filial: <b>$filial_registro</b><br />\n";	
		$email_texto .= "Usuario: <b>$usuario_exclusao</b><br />\n";
		$email_texto .= "Motivo da exclusao: $motivo_email</b><br /><br />\n";
		$email_texto .= "<img src='$servidor/$diretorio_servidor/imagens/logomarca_topo.png' border='0' height='63px' /></b><br />\n";
		
		$texto_envio = utf8_encode($email_texto);
		
		$email_headers = implode ( "\n",array ( "From: $email_remet", "Reply-To: $email_resposta", "Return-Path: $email_resposta", "MIME-Version: 1.1", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8" ) );
		
		// ENVIAR PARA CLIENTE ========
		mail ($email_destinatario, $email_assunto, $texto_envio, $email_headers);
		
		// ENVIAR PARA SUIF ===========
		//mail ("fabriciobayerl@gmail.com", $email_assunto, $texto_envio, $email_headers);
		//mail ("contato@suif.net.br", $email_assunto, $texto_envio, $email_headers);
		// ============================
	}
	
	else
	{}
	// ================================


$erro = 0;
$msg = "<div style='color:#009900'>Romaneio exclu&iacute;do com sucesso!</div>";
}
// ==================================================================================================================


// ================================================================================================================
include ('../../includes/head.php'); 
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:1090px; float:left; text-align:center; border:0px solid #000">
    <?php echo "$msg"; ?>
    </div>
    
	<!--
	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    xxxxxxxxxxxxx
    </div>-->
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:745px; float:left; text-align:left; color:#FF0000">
    <!-- xxxxxxxxxxxxx -->
	</div>

	<div class="ct_subtitulo_1" style="width:345px; float:right; text-align:right; font-style:normal">
    <!-- xxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:175px; width:1080px; border:0px solid #0000FF; margin:auto">







</div>
<!-- ============================================================================================================== -->






<!-- ============================================================================================================= -->
<div id="centro" style="height:50px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	<?php

		echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/saida/excluir_romaneio_1.php' method='post'>
			<input type='hidden' name='num_romaneio_form' value='$num_romaneio_form'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
			</form>
			</div>";
	?>
</div>
<!-- ============================================================================================================== -->







</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>