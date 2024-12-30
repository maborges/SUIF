<?php
include ("../../includes/conecta_bd.php");
// INTEGRAÇÃO ROVERETI ======================================================================================================

// ====== BUSCA CODIGO FILIAL =======================================================================
$busca_filial = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
$cod_ifr = mysqli_fetch_row($busca_filial);
$cod_integ_filial_rovereti = $cod_ifr[3];

// ====== BUSCA CODIGO USUARIO =======================================================================
// $usuario_rovereti = "INTEGRADOR.GRANCAFE";  //agora busca do conecta_db
// $key_rovereti = 25482;

//	$cod_empresa_rovereti = "16"; Número alterado dia 02/07/2018 (Gustavo ligou informando o novo numero)
$cod_empresa_rovereti = "50";
$data_rovereti = date('d/m/Y', time());
$desc_comp_rovereti = "COMPRA DE " . $produto_rovereti . " - " . $quantidade . " " . $unidade_print . " X " . $preco_unitario_print;
$cpf_cnpj_rovereti = $cpf_aux;
$observacao_rovereti = "# CADASTRO INTEGRACAO SUIF (USERNAME: " . $usuario_cadastro . ") " . " OBS: " . $observacao . " | TIPO: " . $tipo_print;
$valor_rovereti = number_format($valor_total,2,",","");

//O token é gerado pela DscIdentificacaoUsuario + key + a string ServiceToken + data de hoje
//$token = sha1("USUARIO.TESTE"."18538"."ServiceToken"."05/04/2017");
//$token = sha1($usuario_rovereti.$key_rovereti."ServiceToken".$data_rovereti);
$token = sha1($usuario_rovereti . $key_rovereti . "ServiceToken" . $data_rovereti);

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


?>