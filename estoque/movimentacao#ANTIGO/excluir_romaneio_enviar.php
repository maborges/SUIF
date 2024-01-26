<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_romaneio_enviar';
$titulo = 'Excluir Romaneio';
$modulo = 'estoque';
$menu = 'movimentacao';


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
<?php include ('../../includes/menu_estoque.php'); ?>

<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php
// =================================================================================================================
	$filial = $filial_usuario;
	$numero_romaneio = $_POST["numero_romaneio"];
	
	$pagina_mae = $_POST["pagina_mae"];
	$pagina_filha = $_POST["pagina_filha"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$produto_list = $_POST["produto_list"];
	$produtor_ficha = $_POST["produtor_ficha"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$num_romaneio_aux = $_POST["num_romaneio_aux"];
	$movimentacao = $_POST["movimentacao"];
	$motivo_exclusao = $_POST["motivo_exclusao"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// BUSCA COMPRA  ==========================================================================================
/*
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra'");
	$linha_compra = mysqli_num_rows ($busca_compra);
	for ($t=1 ; $t<=$linha_compra ; $t++)
	{
	$aux_compra = mysqli_fetch_row($busca_compra);
	$fornecedor = $aux_compra[2];
	$quantidade = $aux_compra[5];
	$movimentacao = $aux_compra[16];
	$produto = $aux_compra[3];
	}
*/

// BUSCA ROMANEIO  ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	
	for ($r=1 ; $r<=$linha_romaneio ; $r++)
	{
	$aux_romaneio = mysqli_fetch_row($busca_romaneio);
	}

$movimentacao = $aux_romaneio[13];
$filial_romaneio = $aux_romaneio[25];
/*
$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$data_romaneio = $aux_romaneio[3];
$data_romaneio_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$fornecedor = $aux_romaneio[2];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$tipo = $aux_romaneio[5];
$observacao = $aux_romaneio[18];
$estado_registro = $aux_romaneio[26];
$movimentacao = $aux_romaneio[13];
$usuario_cadastro = $aux_romaneio[19];
$hora_cadastro = $aux_romaneio[20];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$usuario_exclusao = $aux_romaneio[40];
$hora_exclusao = $aux_romaneio[41];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$motivo_exclusao = $aux_romaneio[43];
*/



// EXCLUSAO  ==========================================================================================
$excluir = mysqli_query ($conexao, "UPDATE estoque SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE numero_romaneio='$numero_romaneio'");
	
// ==========================================================================================



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

$email_assunto = "Exclusao de Romaneio no SUIF";

$email_texto = "<font style='color:#090; font-size:22px'>&#8226; <b>Exclusao de Romaneio - $movimentacao</b></font><br />\n";
$email_texto .= "Numero do romaneio: <b>$numero_romaneio</b><br />\n";
$email_texto .= "Fornecedor: <b>$fornecedor_email</b><br />\n";
$email_texto .= "Produto: <b>$produto_email</b><br />\n";
$email_texto .= "Quantidade: <b>$quantidade $un_print</b><br />\n";
$email_texto .= "Filial: <b>$filial_registro</b><br />\n";	
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






















		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Romaneio exclu&iacute;do com sucesso!</div>
		<div id='centro' style='float:left; height:130px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:920px; color:#000066; text-align:center; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; Romaneio: $numero_romaneio</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:320px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";

echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/excluir_romaneio.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";




echo "

		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
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