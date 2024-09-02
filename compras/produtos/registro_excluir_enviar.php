<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include("../../sankhya/Sankhya.php");
include_once("../../helpers.php");

$pagina = "registro_excluir_enviar";
$titulo = "Excluir";
$modulo = "compras";
$menu = "compras";
// ========================================================================================================


// ====== RECEBE POST =====================================================================================
$filial = $filial_usuario;
$numero_compra = $_POST["numero_compra"];
$numero_compra_aux = $_POST["numero_compra_aux"] ?? '';
$motivo_exclusao = $_POST["motivo_exclusao"] ?? '';

$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$botao = $_POST["botao"] ?? '';
$data_inicial = $_POST["data_inicial"] ?? '';
$data_final = $_POST["data_final"] ?? '';
$produto_list = $_POST["produto_list"] ?? '';
$produtor_ficha = $_POST["produtor_ficha"] ?? '';
$monstra_situacao = $_POST["monstra_situacao"] ?? '';
$numero_compra_aux = $_POST["numero_compra_aux"] ?? '';
$numero_romaneio = $_POST["numero_romaneio"] ?? '';
$numero_transferencia = $_POST["numero_transferencia"] ?? '';
$quantidade = $_POST["quantidade"] ?? '';
$fornecedor = $_POST["fornecedor"] ?? '';
$fornecedor_print = $_POST["fornecedor_print"] ?? '';
$cod_produto = $_POST["cod_produto"] ?? '';
$produto_print = $_POST["produto_print"] ?? '';
$unidade_print = $_POST["unidade_print"] ?? '';
$tipo = $_POST["tipo"] ?? '';
$cod_tipo = $_POST["cod_tipo"] ?? '';
$tipo_print = $_POST["tipo"] ?? '';
$pedidoSankhya = $_POST['pedidoSankhya'] ?? '';

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());
// ========================================================================================================


// ====== BUSCA COMPRA ====================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra'");
$linha_compra = mysqli_num_rows ($busca_compra);
$aux_compra = mysqli_fetch_row($busca_compra);

$fornecedor_print = $aux_compra[42];
$quantidade = $aux_compra[5];
$un_print = $aux_compra[8];
$filial_registro = $aux_compra[25];
$movimentacao = $aux_compra[16];
$produto = $aux_compra[3];
$numero_transferencia = $aux_compra[30];
$pedidoSankhya = $aux_compra[55];
// ========================================================================================================


// ====== BUSCA FORNECEDOR ORIGEM (TRANSFERENCIA) =========================================================
$busca_pessoa_1 = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_transferencia='$numero_transferencia' 
AND movimentacao='TRANSFERENCIA_SAIDA'");
$linha_pessoa_1 = mysqli_num_rows ($busca_pessoa_1);
$aux_pessoa_1 = mysqli_fetch_row($busca_pessoa_1);

$fornecedor_origem = $aux_pessoa_1[2];
$fornecedor_o_print = $aux_pessoa_1[42];
$quantidade_trans = $aux_pessoa_1[5];
// ========================================================================================================


// ====== BUSCA FORNECEDOR DESTINO (TRANSFERENCIA) =========================================================
$busca_pessoa_2 = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_transferencia='$numero_transferencia' 
AND movimentacao='TRANSFERENCIA_ENTRADA'");
$linha_pessoa_2 = mysqli_num_rows ($busca_pessoa_2);
$aux_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);

$fornecedor_destino = $aux_pessoa_2[2];
$fornecedor_d_print = $aux_pessoa_1[42];
// ========================================================================================================


// ====== BUSCA ROMANEIO ==================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

$situacao_romaneio = $aux_romaneio[14];
// ========================================================================================================


// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


// ========================================================================================================
include ("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:1080px; border:0px solid #000; margin:auto">

<?php
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
	<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
	<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
	</form>
	</div>";
}

else
{
	
	// EXCLUSAO ROMANEIO ==================================================================================
	if ($situacao_romaneio == "ENTRADA_DIRETA" or $situacao_romaneio == "SAIDA_DIRETA")
	{$excluir_estoque = mysqli_query ($conexao, "UPDATE estoque SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', 
	hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE numero_romaneio='$numero_romaneio'");}
	else
	{}
	// ====================================================================================================
	
	
	// EXCLUSAO REGISTRO ==================================================================================
	if ($movimentacao == "ENTRADA" or $movimentacao == "ENTRADA_FUTURO")
	{
	// EXCLUI ---------------------------------------------------------------------------------------------
	$excluir = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', 
	hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE numero_compra='$numero_compra'");

	// ATUALIZA SALDO --------------------------------------------------------------------------------------
	include ('../../includes/busca_saldo_armaz.php');
	$saldo = $saldo_produtor - $quantidade;
	include ('../../includes/atualisa_saldo_armaz.php');
	}
	// ====================================================================================================

	// ====================================================================================================
	elseif ($movimentacao == "COMPRA" or $movimentacao == "SAIDA" or $movimentacao == "SAIDA_FUTURO")
	{
	// EXCLUI ---------------------------------------------------------------------------------------------
	$excluir = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', 
	hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE numero_compra='$numero_compra'");

	// ATUALIZA SALDO --------------------------------------------------------------------------------------
	include ('../../includes/busca_saldo_armaz.php');
	$saldo = $saldo_produtor + $quantidade;
	include ('../../includes/atualisa_saldo_armaz.php');

	// Cancela pedido de faturamento no Sankhya
	if ($pedidoSankhya) {
		$resultCancela = Sankhya::cancelaDocumento($pedidoSankhya);
	}
	}
	// ====================================================================================================

	// ====================================================================================================
	elseif ($movimentacao == "TRANSFERENCIA_ENTRADA" or $movimentacao == "TRANSFERENCIA_SAIDA")
	{
	// EXCLUI ---------------------------------------------------------------------------------------------
	$excluir = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', 
	hora_exclusao='$hora_alteracao', data_exclusao='$data_alteracao', motivo_exclusao='$motivo_exclusao' WHERE numero_transferencia='$numero_transferencia'");

	// ATUALIZA SALDO ORIGEM -------------------------------------------------------------------------------
	$busca_saldo_o_armazenado = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_origem' AND filial='$filial' 
	AND cod_produto='$cod_produto'");
	$linhas_sa_o = mysqli_num_rows ($busca_saldo_o_armazenado);
	$aux_sa_o = mysqli_fetch_row($busca_saldo_o_armazenado);

	if ($linhas_sa_o == 0)
	{$saldo_produtor_o = 0;}
	else
	{$saldo_produtor_o = $aux_sa_o[9];}

	$saldo_o = $saldo_produtor_o + $quantidade_trans;

	if ($saldo_o == 0)
	{
	$deleta_saldo_o = mysqli_query ($conexao, "DELETE FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_origem' AND filial='$filial' 
	AND cod_produto='$cod_produto'");
	}
	
	else
	{
		if ($linhas_sa_o == 0)
		{
		$cria_saldo_o = mysqli_query ($conexao, "INSERT INTO saldo_armazenado (codigo, cod_fornecedor, fornecedor_print, filial, cod_produto, 
		produto_print, tipo_produto, tipo_print, unidade_print, saldo) VALUES (NULL, '$fornecedor_origem', '$fornecedor_o_print', '$filial', '$cod_produto', 
		'$produto_print', '$cod_tipo', '$tipo_print', '$unidade_print', '$saldo_o')");
		}
		
		else
		{
		$altera_saldo_o = mysqli_query ($conexao, "UPDATE saldo_armazenado SET saldo='$saldo_o' WHERE cod_fornecedor='$fornecedor_origem' 
		AND filial='$filial' AND cod_produto='$cod_produto'");
		}
	}
	// ------------------------------------------------------------------------------------------------------

	// ATUALIZA SALDO DESTINO -------------------------------------------------------------------------------
	$busca_saldo_d_armazenado = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_destino' AND filial='$filial' 
	AND cod_produto='$cod_produto'");
	$linhas_sa_d = mysqli_num_rows ($busca_saldo_d_armazenado);
	$aux_sa_d = mysqli_fetch_row($busca_saldo_d_armazenado);

	if ($linhas_sa_d == 0)
	{$saldo_produtor_d = 0;}
	else
	{$saldo_produtor_d = $aux_sa_d[9];}

	$saldo_d = $saldo_produtor_d - $quantidade_trans;

	if ($saldo_d == 0)
	{
	$deleta_saldo_d = mysqli_query ($conexao, "DELETE FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_destino' AND filial='$filial' 
	AND cod_produto='$cod_produto'");
	}
	
	else
	{
		if ($linhas_sa_d == 0)
		{
		$cria_saldo_d = mysqli_query ($conexao, "INSERT INTO saldo_armazenado (codigo, cod_fornecedor, fornecedor_print, filial, cod_produto, 
		produto_print, tipo_produto, tipo_print, unidade_print, saldo) VALUES (NULL, '$fornecedor_destino', '$fornecedor_d_print', '$filial', '$cod_produto', 
		'$produto_print', '$cod_tipo', '$tipo_print', '$unidade_print', '$saldo_d')");
		}
		
		else
		{
		$altera_saldo_d = mysqli_query ($conexao, "UPDATE saldo_armazenado SET saldo='$saldo_d' WHERE cod_fornecedor='$fornecedor_destino' 
		AND filial='$filial' AND cod_produto='$cod_produto'");
		}
	}
	// ------------------------------------------------------------------------------------------------------

	}
	// ====================================================================================================

	// ====================================================================================================
	else
	{}	





	
if ($movimentacao == "COMPRA")
{
	// ========================================================================================================================================================
	// INTEGRAÇÃO ROVERETI =====================================================================================================================================
	
	// ====== BUSCA CODIGO FILIAL =======================================================================
	$busca_filial = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
	$cod_ifr = mysqli_fetch_row($busca_filial);
	$cod_integ_filial_rovereti = $cod_ifr[3];
	
	// ====== BUSCA CODIGO USUARIO =======================================================================
	//$usuario_rovereti = "INTEGRADOR.GRANCAFE";
	//$key_rovereti = 25482;

//	$cod_empresa_rovereti = "16"; Número alterado dia 02/07/2018 (Gustavo ligou informando o novo numero)
	$cod_empresa_rovereti = "50";
	$data_rovereti = date('d/m/Y', time());
	$cod_integracao_conta_pagar = $numero_compra;
	$dsc_motivo_cancelamento = "# CANCELAMENTO DE COMPRA - INTEGRACAO SUIF (USERNAME: " . $usuario_alteracao . ") " . $motivo_exclusao;

	//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
	//$token = sha1("USUARIO.TESTE"."18538"."ServiceToken"."05/04/2017");
	$token = sha1($usuario_rovereti.$key_rovereti."ServiceToken".$data_rovereti);
	
	//PARAMETROS CADASTRO CONTA_PAGAR
	 $parametros = '{
					"CodEmpresa":"'.$cod_empresa_rovereti.'",
					"CodIntegracaoFilial":"'.$cod_integ_filial_rovereti.'",
			        "CodIntegracaoContaPagar":"'.$numero_compra.'",
					"DscMotivoCancelamento":"'.utf8_encode($dsc_motivo_cancelamento).'",
					"DscIdentificacaoUsuario":"'.$usuario_rovereti.'",
					"Key":"'.$key_rovereti.'",
					"Token":"'.$token.'"
					}'; 
	
	$url = $rovereti_api_CancelarContaPagar; //'http://appservice.rovereti.com.br/Api/ContaPagar/CancelarContaPagar';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
	array('Content-Type:application/json',
	'Content-Length: ' . strlen($parametros))
	);
	//curl_setopt($ch, CURLOPT_HEADER, 1);
	 
	$retorno =  curl_exec($ch);
	//	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	//	$header = substr($retorno, 0, $header_size);
	//	$body = substr($retorno, $header_size);
	$jsonResultData = json_decode($retorno);
	
	$exec  = curl_exec( $ch );
	$error = curl_error( $ch );
	$errno = curl_errno( $ch );
	
	curl_close( $ch );

	// Exec retornou falso?
	if ($exec === false)
	{$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
	$erro_rovereti = 'sim';}
		
	// Tem algum erro?
	elseif ($error !== '')
	{$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
	$erro_rovereti = 'sim';}
	
	// Tem algum erro? (Redundante)
	elseif ($errno)
	{$msg_rovereti = 'Erro: Esta compra n&atilde;o foi lan&ccedil;ada no ROVERETI.';
	$erro_rovereti = 'sim';}
	
	else
	{$msg_rovereti = 'Compra lan&ccedil;ada no ROVERETI com Sucesso!';
	$erro_rovereti = 'nao';}
		
	
	// ========================================================================================================================================================
	// ========================================================================================================================================================
}
else
{}






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

$email_assunto = "Exclusao de registro no SUIF";

$email_texto = "<font style='color:#090; font-size:22px'>&#8226; <b>Exclusao de registro - $movimentacao</b></font><br />\n";
$email_texto .= "Numero: <b>$numero_compra</b><br />\n";
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
//mail ("fabriciobayerl@gmail.com", $email_assunto, $texto_envio, $email_headers);
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
		Registro exclu&iacute;do com sucesso!</br>
		$motivo_exclusao_aux</div>
		<div id='centro' style='float:left; height:130px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:1045px; color:#000066; text-align:center; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; $numero_compra</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:384px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";
		
	
		

if ($pagina_mae == "movimentacao_produtor")
{
echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
		</form>
		</div>";
}
else
{
echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
		</form>
		</div>";
}

}	

echo "<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";	



?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>