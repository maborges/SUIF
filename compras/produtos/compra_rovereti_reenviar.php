<?php
	include ('../../includes/config.php'); 
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'compra_rovereti_reenviar';
	$titulo = 'Reenvio de Compra ao Rovereti';
	$menu = 'produtos';
	$modulo = 'compras';


// ========== ELIMINA MÁSCARAS CPF E CNPJ ================================================================
function limpa_cpf_cnpj($limpa){
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
$botao = $_POST["botao"];
$numero_compra = $_POST["numero_compra"];
$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA COMPRA ===================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra'");
$aux_bc = mysqli_fetch_row($busca_compra);
$linhas_bc = mysqli_num_rows ($busca_compra);
$data_hoje = date('d/m/Y', time());

$fornecedor = $aux_bc[2];
$cod_produto = $aux_bc[39];
$data_compra = $aux_bc[4];
$data_compra_print = date('d/m/Y', strtotime($aux_bc[4]));
$quantidade = $aux_bc[5];
$quantidade_print = number_format($aux_bc[5],2,",",".");
$preco_unitario = $aux_bc[6];
$preco_unitario_print = number_format($aux_bc[6],2,",",".");
$valor_total = $aux_bc[7];
$valor_total_print = number_format($aux_bc[7],2,",",".");
$tipo = $aux_bc[10];
$cod_tipo = $aux_bc[41];
$safra = $aux_bc[9];
$umidade = $aux_bc[12];
$broca = $aux_bc[11];
$impureza = $aux_bc[43];
$data_pagamento = $aux_bc[14];
$data_pgto = date('d/m/Y', strtotime($aux_bc[14]));
$data_pagamento_br = date('d/m/Y', strtotime($aux_bc[14]));
$situacao_pgto = $aux_bc[15];
$observacao = $aux_bc[13];
$usuario_cadastro = $aux_bc[18];
$data_cadastro = date('d/m/Y', strtotime($aux_bc[20]));
$hora_cadastro = $aux_bc[19];
$motivo_alteracao_quant = $aux_bc[35];
$quantidade_original = number_format($aux_bc[36],2,",",".");
$desconto_quantidade = number_format($aux_bc[29],2,",",".");
$desconto_quantidade_2 = $aux_bc[29];
$valor_total_original = number_format($aux_bc[37],2,",",".");
$desconto_em_valor = ($aux_bc[29] * $aux_bc[6]);
$desc_em_valor_print = number_format($desconto_em_valor,2,",",".");
$usuario_altera_quant = $aux_bc[44];
$movimentacao = "COMPRA";

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y-m-d', time());
// ======================================================================================================


// ====== BUSCA PRODUTO ================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
$produto_rovereti = str_replace($comAcentos, $semAcentos, $produto_print); // ==== INTEGRAÇÃO ROVERETI ===== ATENÇÃO: REVERETI não aceita "acentos"
$cod_class_gerencial = $aux_bp[24]; // ==== INTEGRAÇÃO ROVERETI =====
$cod_centro_custo = $aux_bp[25]; // ==== INTEGRAÇÃO ROVERETI =====
// ======================================================================================================


// ====== BUSCA FORNECEDOR ==============================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "PF" or $aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
$cpf_aux = limpa_cpf_cnpj($cpf_cnpj); // ==== INTEGRAÇÃO ROVERETI =====
$fornecedor_rovereti = str_replace($comAcentos, $semAcentos, $fornecedor_print); // ==== INTEGRAÇÃO ROVERETI =====
// ======================================================================================================


// ====== BUSCA FAVORECIDO ==============================================================================
$busca_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa' ORDER BY nome LIMIT 1");
$aux_favorecido = mysqli_fetch_row($busca_favorecido);
$linhas_favorecido = mysqli_num_rows ($busca_favorecido);

$cod_favorecido = $aux_favorecido[0];
$favorecido_print = $aux_favorecido[14];
// ========================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ==========================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ===========================================================================================================


// ===========================================================================================================
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

<?php include ('../../includes/sub_menu_compras_produtos.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:500px; width:1080px; border:0px solid #000; margin:auto">

<?php
if ($botao == "ROVERETI" and $numero_compra != "")
{

/*

// ==========================================================================================================================
// INTEGRAÇÃO ROVERETI ======================================================================================================

// ====== BUSCA CODIGO FILIAL =======================================================================
$busca_filial = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
$cod_ifr = mysqli_fetch_row($busca_filial);
$cod_integ_filial_rovereti = $cod_ifr[3];

// ====== BUSCA CODIGO USUARIO =======================================================================
$usuario_rovereti = "INTEGRADOR.GRANCAFE";
$key_rovereti = 25482;

//	$cod_empresa_rovereti = "16"; Número alterado dia 02/07/2018 (Gustavo ligou informando o novo numero)
$cod_empresa_rovereti = "50";
//	$data_rovereti = date('d/m/Y', time());
$data_rovereti = $data_compra_print;
$desc_comp_rovereti = "COMPRA DE " . $produto_rovereti . " - " . $quantidade . " " . $unidade_print . " X " . $preco_unitario_print;
$cpf_cnpj_rovereti = $cpf_aux;
$observacao_rovereti = "# CADASTRO INTEGRACAO SUIF (USERNAME: " . $usuario_cadastro . ") " . " OBS: " . $observacao . " | TIPO: " . $tipo_print;
$valor_rovereti = number_format($valor_total,2,",","");

//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
//$token = sha1("USUARIO.TESTE"."18538"."ServiceToken"."05/04/2017");
// $token = sha1($usuario_rovereti.$key_rovereti."ServiceToken".$data_hoje);


//PARAMETROS CADASTRO CONTA_PAGAR
 $parametros = '{
				"CodEmpresa":"'.$cod_empresa_rovereti.'",
				"CodIntegracaoFilial":"'.$cod_integ_filial_rovereti.'",
				"DscContaPagar":"'.utf8_encode($desc_comp_rovereti).'",
				"NumCpfCnpj":"'.$cpf_cnpj_rovereti.'",
				"NomFornecedor":"'.utf8_encode($fornecedor_rovereti).'",
				"NumDocumento":"'.$numero_compra.'",
				"DatEmissao":"'.$data_rovereti.'",
				"DatVencimento":"'.$data_pgto.'",
				"VlrConta":"'.$valor_rovereti.'",
				"VlrMultaAtraso":"",
				"VlrJurosAtrasoDia":"",
				"VlrDesconto":"",
				"DatLimiteDesconto":"",
				"NumAnoMesCompetencia":"",
				"IndContaReconhecida":"S",
				"CodIntegracaoAcaoContabil":"",
				"CodIntegracaoClassGerencial":"'.$cod_class_gerencial.'",
				"CodIntegracaoCentroCusto":"'.$cod_centro_custo.'",
				"DscObservacao":"'.utf8_encode($observacao_rovereti).'",
				"CodIntegracaoContaPagar":"'.$numero_compra.'",
				"NomFavorecido":"",
				"NumCpfCnpjFavorecido":"",
				"NumBanco":"",
				"NumAgencia":"",
				"NumContaCorrente":"",
				"NumDigitoContaCorrente":"",
				"DscIdentificacaoUsuario":"'.$usuario_rovereti.'",
				"Key":"'.$key_rovereti.'",
				"Token":"'.$token.'"
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

*/




	echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
	<div id='centro' style='float:left; height:25px; width:1080px; color:#4F4F4F; text-align:center; border:0px solid #000; font-size:12px'>
	Retorno Rovereti: $retorno $error $errno</div>

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
	</div>

	<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
	</div>


	<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
	</div>


	<div id='centro' style='float:left; height:50px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
	</div>
	

	
	";




}



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