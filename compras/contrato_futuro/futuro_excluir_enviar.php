<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'registro_excluir_enviar';
	$titulo = 'Excluir Contrato Futuro';
	$menu = 'contratos';
	$modulo = 'compras';


	$filial = $filial_usuario;
	$codigo_contrato = $_POST["codigo_contrato"];
	$codigo_contrato_aux = $_POST["codigo_contrato_aux"];
	$motivo_exclusao = $_POST["motivo_exclusao"];

	$pagina_mae = $_POST["pagina_mae"];
	$pagina_filha = $_POST["pagina_filha"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$cod_produto = $_POST["cod_produto"];
	$cod_tipo = $_POST["cod_tipo"];
	$fornecedor = $_POST["fornecedor"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$movimentacao = $_POST["movimentacao"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


	include ('../../includes/head.php'); 
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>

<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>

<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:1080px; border:0px solid #000; margin:auto">

<?php
// ====== BUSCA CONTRATO  ==========================================================================================
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND codigo='$codigo_contrato'");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);

$fornecedor = $aux_contrato[1];
$cod_produto = $aux_contrato[31];
$quantidade = $aux_contrato[4];
$quantidade_adquirida = $aux_contrato[5];
$unidade_print = $aux_contrato[6];
$num_contrato_print = $aux_contrato[17];
$filial_contrato = $aux_contrato[24];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================


// ====== BUSCA POR FORNECEDOR ==========================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
// ======================================================================================================


// ======================================================================================================
if ($motivo_exclusao == "" or $motivo_exclusao == " ")
{
echo "<div id='centro' style='float:left; height:5px; width:1050px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1045px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:40px; width:1045px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Motivo da exclus&atilde;o &eacute; obrigat&oacute;rio</br>
$motivo_exclusao_aux</div>
<div id='centro' style='float:left; height:130px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
	<div style='float:left; width:920px; color:#000066; text-align:center; border:0px solid #000; font-size:10px; line-height:18px'>
	</br>
	</div>
</div>

<div id='centro' style='float:left; height:90px; width:384px; color:#00F; text-align:center; border:0px solid #000'>
</div>

<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/futuro_excluir.php' method='post'>
<input type='hidden' name='codigo_contrato' value='$codigo_contrato'>
<input type='hidden' name='codigo_contrato_aux' value='$codigo_contrato_aux'>
<input type='hidden' name='botao' value='$botao'>
<input type='hidden' name='data_inicial' value='$data_inicial'>
<input type='hidden' name='data_final' value='$data_final'>
<input type='hidden' name='cod_produto' value='$cod_produto'>
<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
<input type='hidden' name='pagina_mae' value='$pagina_mae'>
<input type='hidden' name='pagina_filha' value='$pagina_filha'>
<input type='hidden' name='fornecedor' value='$fornecedor'>
<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
</div>";
}

else
{
// EXCLUSAO  ==========================================================================================
$excluir = mysqli_query ($conexao, "UPDATE contrato_futuro SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE codigo='$codigo_contrato'");

// ===================================================
// ATUALIZA SALDO ====================================
include ('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_produtor - $quantidade_adquirida;
include ('../../includes/atualisa_saldo_armaz.php');
// ===================================================
// ===================================================

$excluir_entrada = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE movimentacao='ENTRADA_FUTURO' AND numero_transferencia='$num_contrato_print'");
$excluir_saida = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE movimentacao='SAIDA_FUTURO' AND numero_transferencia='$num_contrato_print'");

	

// ENVIAR E-MAIL  ==========================================================================================
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

$email_assunto = "Exclusao de contrato no SUIF";

$email_texto = "<font style='color:#090; font-size:22px'>&#8226; <b>Exclusao de Contrato Futuro</b></font><br />\n";
$email_texto .= "Numero: <b>$num_contrato_print</b><br />\n";
$email_texto .= "Fornecedor: <b>$fornecedor_email</b><br />\n";
$email_texto .= "Produto: <b>$produto_email</b><br />\n";
$email_texto .= "Quantidade: <b>$quantidade $unidade_print</b><br />\n";
$email_texto .= "Filial: <b>$filial_contrato</b><br />\n";	
$email_texto .= "Usuario: <b>$usuario_alteracao</b><br />\n";
$email_texto .= "Data: <b>$data_exclusao</b><br />\n";
$email_texto .= "hora: <b>$hora_alteracao</b><br />\n";
$email_texto .= "Motivo da exclusao: $motivo_email</b><br /><br />\n";
$email_texto .= "<img src='$servidor/$diretorio_servidor/imagens/logomarca_topo.png' border='0' height='63px' /></b><br />\n";

$texto_envio = utf8_encode($email_texto);

$email_headers = implode ( "\n",array ( "From: $email_remet", "Reply-To: $email_resposta", "Return-Path: $email_resposta", "MIME-Version: 1.1", "X-Priority: 3", "Content-Type: text/html; charset=UTF-8" ) );

// ENVIAR PARA CLIENTE ===================================================================================
mail ($email_destinatario, $email_assunto, $texto_envio, $email_headers);

// ENVIAR PARA SUIF ======================================================================================
mail ("fabriciobayerl@gmail.com", $email_assunto, $texto_envio, $email_headers);
//mail ("contato@suif.net.br", $email_assunto, $texto_envio, $email_headers);
// =======================================================================================================
}

else
{}
// =======================================================================================================







		echo "<div id='centro' style='float:left; height:5px; width:1050px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:1045px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:1045px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Contrato exclu&iacute;do com sucesso!</br>
		</div>
		<div id='centro' style='float:left; height:130px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:1045px; color:#000066; text-align:center; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; $num_contrato_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:384px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";

	echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/$pagina_mae.php' method='post'>
		<input type='hidden' name='codigo_contrato' value='$codigo_contrato'>
		<input type='hidden' name='codigo_contrato_aux' value='$codigo_contrato_aux'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";


}	

echo "<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";	



?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>