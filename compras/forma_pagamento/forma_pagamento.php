<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../includes/desconecta_bd.php");
$pagina = "forma_pagamento";
$titulo = "Forma de Pagamento";
$modulo = "compras";
$menu = "compras";



// ============================================== CONVERTE DATA ====================================================	
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data){

	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){

	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================


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


// ==================================================================================================================	
$numero_compra = $_POST["numero_compra"];

$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao_relatorio = $_POST["botao_relatorio"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];
$num_compra_aux = $_POST["num_compra_aux"];

$codigo_pgto_favorecido = $_POST["codigo_pgto_favorecido"];
$valor_excluido = $_POST["valor_excluido"];

$codigo_favorecido = $_POST["representante"];
$forma_pagamento = $_POST["forma_pagamento"];
$data_pagamento = ConverteData($_POST["data_pagamento"]);
$data_pagamento_print = $_POST["data_pagamento"];	
$valor_pagamento = ConverteValor($_POST["valor_pagamento"]);
// deu erro dia 16/07/2018
// $valor_pagamento_print = number_format($valor_pagamento,2,",",".");
$valor_pagamento_print = number_format($_POST["valor_pagamento"],2,",",".");
$banco_cheque = $_POST["banco_cheque"];
$numero_cheque = $_POST["numero_cheque"];
$obs_pgto = $_POST["obs_pgto"];
$botao = $_POST["botao"];
$nf_adto = $_POST["nf_adto"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());

$filial = $filial_usuario;


$hoje = date ("d/m/Y", time());
	if ($botao == "incluir")
	{$data_print = $data_pagamento_print;}
	else
	{$data_print = $hoje;}

// =============================================================================================================
// =============================================================================================================
include ("../../includes/conecta_bd.php");
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
include ("../../includes/desconecta_bd.php");
$linha_compra = mysqli_num_rows ($busca_compra);

for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$observacao = $aux_compra[13];
$motivo_alteracao_quant = $aux_compra[35];
$quantidade_original = number_format($aux_compra[36],2,",",".");
$desconto_quantidade = number_format($aux_compra[29],2,",",".");
$desconto_quantidade_2 = $aux_compra[29];
$valor_total_original = number_format($aux_compra[37],2,",",".");
$desconto_em_valor = ($aux_compra[29] * $aux_compra[6]);
$desc_em_valor_print = number_format($desconto_em_valor,2,",",".");
$total_pago_compra = $aux_compra[50];
$saldo_pagar_compra = $aux_compra[51];




// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	elseif ($produto == "RESIDUO_CACAU")
	{$produto_print = "Res&iacute;duo de Cacau";}
	else
	{$produto_print = "-";}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{	
		if ($quantidade <= 1)
			{$unidade_print = "Saca";}
		else	
		{$unidade_print = "Sacas";}
	}
	elseif ($unidade == "KG")
	{
		if ($quantidade <= 1)
		{$unidade_print = "Kg";}
		else	
		{$unidade_print = "Kg";}
	}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao == "ENTREGUE")
	{$situacao_print = "ENTREGUE";}
	elseif ($situacao == "A_ENTREGAR")
	{$situacao_print = "A ENTREGAR";}
	elseif ($situacao == "ARMAZENADO")
	{$situacao_print = "ARMAZENADO";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
include ("../../includes/conecta_bd.php");
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
include ("../../includes/desconecta_bd.php");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($y=1 ; $y<=$linha_pessoa ; $y++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa_fav = $aux_pessoa[35];
	if ($aux_pessoa[2] == "pf" or $aux_pessoa[2] == "PF")
	{$cpf_cnpj = $aux_pessoa[3];}
	else
	{$cpf_cnpj = $aux_pessoa[4];}
	
$cpf_aux = limpa_cpf_cnpj($cpf_cnpj); // ==== INTEGRAÇÃO ROVERETI =====
}


// ACHA FAVORECIDO  ==========================================================================================
include ("../../includes/conecta_bd.php");
$acha_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$codigo_favorecido' ORDER BY nome");
include ("../../includes/desconecta_bd.php");
$linha_acha_favorecido = mysqli_num_rows ($acha_favorecido);
for ($f=1 ; $f<=$linha_acha_favorecido ; $f++)
{
	$aux_fav = mysqli_fetch_row($acha_favorecido);
	$cod_pessoa_1 = $aux_fav[1];
	$banco_ted = $aux_fav[2];
	$tipo_conta = $aux_fav[5];
	$agencia_fav = $aux_fav[3];
	$num_conta_fav = $aux_fav[4];
	$agencia_ted_aux = limpa_cpf_cnpj($aux_fav[3]);
	$conta_ted_aux = limpa_cpf_cnpj($aux_fav[4]);
	$conta_ted = substr($conta_ted_aux, 0, -1);
	$conta_digito = substr($conta_ted_aux, -1);
	$nome_fav = $aux_fav[14];
	$nome_banco = $aux_fav[22];
	$favorecido_rovereti = str_replace($comAcentos, $semAcentos, $nome_fav); // ==== INTEGRAÇÃO ROVERETI =====
	

	// BUSCA CPF FAVORECIDO  ==========================================================================================
	include ("../../includes/conecta_bd.php");
	$busca_cpf_fav = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$cod_pessoa_1' ORDER BY nome");
	include ("../../includes/desconecta_bd.php");
	$aux_cpf_fav = mysqli_fetch_row($busca_cpf_fav);

	if ($aux_cpf_fav[2] == "pf" or $aux_cpf_fav[2] == "PF")
	{$cpf_cnpj_fav = $aux_cpf_fav[3];}
	else
	{$cpf_cnpj_fav = $aux_cpf_fav[4];}
	

	$cpf_aux_fav = limpa_cpf_cnpj($cpf_cnpj_fav); // ==== INTEGRAÇÃO ROVERETI =====

}
// ===============================================================
	if ($banco_ted == "001")
	{
		$agencia_ted = substr($agencia_ted_aux, 0, -1);
		$agencia_digito = substr($agencia_ted_aux, -1);
	}
	else
	{
		$agencia_ted = $agencia_ted_aux;
		$agencia_digito = "";
	}

// ===============================================================
	if ($tipo_conta == "poupanca")
	{
		$tipo_conta_rovereti = "CP";
	}
	else
	{
		$tipo_conta_rovereti = "CC";
	}
// ===============================================================


// ===============================================================
	if ($tipo_conta == "poupanca" and $banco_ted == "104")
	{
		$codigo_ope = "013";
	}
	elseif ($tipo_conta == "corrente" and $banco_ted == "104" and ($aux_cpf_fav[2] == "pf" or $aux_cpf_fav[2] == "PF"))
	{
		$codigo_ope = "001";
	}
	elseif ($tipo_conta == "corrente" and $banco_ted == "104" and ($aux_cpf_fav[2] == "pj" or $aux_cpf_fav[2] == "PJ"))
	{
		$codigo_ope = "003";
	}
	else
	{
		$codigo_ope = "";
	}
// ===============================================================





	if ($forma_pagamento == "PREVISAO")
	{	
		include ("../../includes/conecta_bd.php");
		$acha_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo_pessoa='$codigo_pessoa_fav' ORDER BY nome");
		include ("../../includes/desconecta_bd.php");
		$linha_acha_favorecido_2 = mysqli_num_rows ($acha_favorecido_2);
		for ($e=1 ; $e<=$linha_acha_favorecido_2 ; $e++)
		{
			$aux_fav_2 = mysqli_fetch_row($acha_favorecido_2);
			$codigo_fav_aux = $aux_fav_2[0];
		}
	}



// SOMA PAGAMENTOS  ==========================================================================================
include ("../../includes/conecta_bd.php");
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND estado_registro='ATIVO'"));
include ("../../includes/desconecta_bd.php");
$saldo_pagamento_aux_2 = $valor_total - $soma_pagamentos[0];
$saldo_pagamento = number_format($saldo_pagamento_aux_2,2,".","");


// =================================================================================================================
if ($forma_pagamento == "PREVISAO")
{
		if ($data_pagamento == '')
		{
		$mensagem_erro = "&#10033; <i>Informe a data de pagamento</i>";
		$erro = 2;
		}
		
		elseif ($valor_pagamento == '' or $valor_pagamento == 0)
		{
		$mensagem_erro = "&#10033; <i>Informe o valor do pagamento</i>";
		$erro = 3;
		}

		elseif ($valor_pagamento > $saldo_pagamento)
		{
		$mensagem_erro = "&#10033; <i>O valor do pagamento informado &eacute; maior do que o saldo a pagar</i>";
		$erro = 5;
		}
	
		else
		{
		include ("../../includes/conecta_bd.php");
		$inserir = mysqli_query ($conexao, "INSERT INTO favorecidos_pgto (codigo, codigo_compra, codigo_favorecido, forma_pagamento, data_pagamento, valor, observacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, situacao_pagamento, filial, codigo_pessoa, codigo_fornecedor, produto) 
		VALUES (NULL, '$numero_compra', '$codigo_fav_aux', '$forma_pagamento', '$data_pagamento', '$valor_pagamento', '$obs_pgto', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', 'EM_ABERTO', '$filial', '$codigo_pessoa_fav', '$fornecedor', '$produto')");
		include ("../../includes/desconecta_bd.php");
		$banco_cheque = "";
		$numero_cheque = "";
		$codigo_favorecido = "";
		}
}
	
elseif ($botao == "incluir")
{
		if ($forma_pagamento == "")
		{
		$mensagem_erro = "&#10033; <i>Selecione a forma de pagamento</i>";
		$erro = 1;
		// header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_1.php");
		}
		
		elseif ($data_pagamento == "")
		{
		$mensagem_erro = "&#10033; <i>Informe a data de pagamento</i>";
		$erro = 2;
		// header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_2.php");
		}
		
		elseif ($valor_pagamento == "" or $valor_pagamento == 0)
		{
		$mensagem_erro = "&#10033; <i>Informe o valor do pagamento</i>";
		$erro = 3;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_3.php");
		}
		
		elseif ($codigo_favorecido == "")
		{
		$mensagem_erro = "&#10033; <i>Informe o favorecido do pagamento</i>";
		$erro = 4;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_4.php");
		}
	
		elseif ($valor_pagamento > $saldo_pagamento)
		{
		$mensagem_erro = "&#10033; <i>O valor do pagamento informado &eacute; maior do que o saldo a pagar</i>";
		$erro = 5;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_5.php");
		}
	
		elseif ($linha_acha_favorecido == 0)
		{
		$mensagem_erro = "&#10033; <i>Favorecido inexistente</i>";
		$erro = 6;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_6.php");
		}

		elseif ($nf_adto == "")
		{
		$mensagem_erro = "&#10033; <i>Informe se o pagamento é referente a Nota Fiscal ou Adiantamento</i>";
		$erro = 7;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_6.php");
		}

		
		else
		{
	
	
			if ($forma_pagamento == "TED")
			{
			// =================================================================================================================================
			// ====== INTEGRAÇÃO ROVERETI ======================================================================================================
	
			// ====== BUSCA CODIGO FILIAL ==========================================================================================
			include ("../../includes/conecta_bd.php");
			$busca_filial = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
			include ("../../includes/desconecta_bd.php");
			$cod_ifr = mysqli_fetch_row($busca_filial);
			$cod_integ_filial_rovereti = $cod_ifr[3];
			
			// ====== DADOS PARA PARAMETROS =======================================================================================
			//$usuario_rovereti = "INTEGRADOR.GRANCAFE";
			// $key_rovereti = 25482;
			$cod_empresa_rovereti = "50";
			$data_rovereti = date('d/m/Y', time());
			$cpf_cnpj_rovereti = $cpf_aux_fav;
			$valor_rovereti = number_format($valor_pagamento,2,",","");
			$token = sha1($usuario_rovereti.$key_rovereti."ServiceToken".$data_rovereti);
			
			//parametros conta pagar tranferencias				
			$parametros = '{
							"CodEmpresa":"'.$cod_empresa_rovereti.'",
							"CodIntegracaoFilial":"'.$cod_integ_filial_rovereti.'",
							"CodIntegracaoContaPagar":"'.$numero_compra.'",
							"Key":"'.$key_rovereti.'",
							"Token":"'.$token.'",
							"DscIdentificacaoUsuario":"'.$usuario_rovereti.'",
					        "Favorecidos":[{
								"NomFavorecido":"'.utf8_encode($favorecido_rovereti).'",
								"NumCpfCnpjFavorecido":"'.$cpf_cnpj_rovereti.'",
								"VlrTransferencia":"'.$valor_rovereti.'",
								"NumBanco":"'.$banco_ted.'",
								"NumAgencia":"'.$agencia_ted.'",
								"NumDigitoAgencia":"'.$agencia_digito.'",
								"NumContaCorrente":"'.$conta_ted.'",
								"NumDigitoContaCorrente":"'.$conta_digito.'",
								"CodTipoContaCorrente":"'.$tipo_conta_rovereti.'",
								"CodigoOperacao":"'.$codigo_ope.'"}]
							}';
						
							
			$url = $rovereti_api_IncluirPrePagamentoTransferencia; //'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirPrePagamentoTransferencia';
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
		
			$retorno =  curl_exec($ch);
			$jsonResultData = json_decode($retorno);

			$retorno_rovereti = "$retorno";
			$parametros_rovereti = "$parametros";
			
			
			// ====== FIM INTEGRAÇÃO ROVERETI ==================================================================================================
			// =================================================================================================================================
			
			}

				if ($forma_pagamento == "CHEQUE")
				{
					if ($banco_cheque == "BANCO DO BRASIL")
					{
					$banco_ted = "001";
					$agencia_fav = "";
					$num_conta_fav = "";
					$tipo_contav = "";
					$nome_banco = "Banco do Brasil";
					}
					elseif ($banco_cheque == "BANESTES")
					{
					$banco_ted = "021";
					$agencia_fav = "";
					$num_conta_fav = "";
					$tipo_contav = "";
					$nome_banco = "Banestes";
					}
					elseif ($banco_cheque == "SICOOB")
					{
					$banco_ted = "756";
					$agencia_fav = "";
					$num_conta_fav = "";
					$tipo_contav = "";
					$nome_banco = "Sicoob";
					}
					else
					{}
				}

			// Calcula o saldo a pagar da compra
			$total_pago_atual = $total_pago_compra + $valor_pagamento;
			$saldo_pagar_atual = $saldo_pagar_compra - $valor_pagamento;
			
			if ($saldo_pagar_atual == 0)
			{$situacao_pagamento = "PAGO";}
			else
			{$situacao_pagamento = "EM_ABERTO";}


			include ("../../includes/conecta_bd.php");
			$inserir = mysqli_query ($conexao, "INSERT INTO favorecidos_pgto (codigo, codigo_compra, codigo_favorecido, forma_pagamento, 
			data_pagamento, valor, banco_cheque, observacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, 
			situacao_pagamento, filial, codigo_pessoa, numero_cheque, banco_ted, codigo_fornecedor, produto, favorecido_print, cod_produto, 
			retorno_rovereti, agencia, num_conta, tipo_conta, nome_banco, cpf_cnpj, nf_adto) 
			VALUES (NULL, '$numero_compra', '$codigo_favorecido', '$forma_pagamento', '$data_pagamento', '$valor_pagamento', 
			'$banco_cheque', '$obs_pgto', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', 'EM_ABERTO', 
			'$filial', '$cod_pessoa_1', '$numero_cheque', '$banco_ted', '$fornecedor', '$produto', '$nome_fav', '$cod_produto', 
			'$retorno_rovereti', '$agencia_fav', '$num_conta_fav', '$tipo_conta', '$nome_banco', '$cpf_cnpj_fav', '$nf_adto')");
			include ("../../includes/desconecta_bd.php");


			include ("../../includes/conecta_bd.php");
			$editar_compra = mysqli_query ($conexao, 
			"UPDATE
				compras
			SET
				situacao_pagamento='$situacao_pagamento',
				total_pago='$total_pago_atual',
				saldo_pagar='$saldo_pagar_atual'
			WHERE
				numero_compra='$numero_compra'");
			include ("../../includes/desconecta_bd.php");
			
						
				if ($forma_pagamento == "CHEQUE")
				{
				include ("../../includes/conecta_bd.php");
				$inserir_cheque = mysqli_query ($conexao, "INSERT INTO cheques (codigo, codigo_compra, codigo_favorecido, forma_pagamento, 
				data_pagamento, valor, banco_cheque, usuario_cadastro, hora_cadastro, data_cadastro, usuario_alteracao, hora_alteracao, 
				data_alteracao, estado_registro, situacao_pagamento, filial, codigo_pessoa, numero_cheque, comp_cheque) 
				VALUES (NULL, '$numero_compra', '$codigo_favorecido', '$forma_pagamento', '$data_pagamento', '$valor_pagamento', 
				'$banco_cheque', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$usuario_alteracao', '$hora_alteracao', 
				'$data_alteracao', 'ATIVO', 'EM_ABERTO', '$filial', '$cod_pessoa_1', '$numero_cheque', 'N')");
				include ("../../includes/desconecta_bd.php");
				}

			$banco_cheque = "";
			$numero_cheque = "";
			$codigo_favorecido = "";


		}
}

elseif ($botao == "excluir")
{

// Calcula o saldo a pagar da compra
$total_pago_atual = $total_pago_compra - $valor_excluido;
$saldo_pagar_atual = $saldo_pagar_compra + $valor_excluido;

if ($saldo_pagar_atual == 0)
{$situacao_pagamento = "PAGO";}
else
{$situacao_pagamento = "EM_ABERTO";}


include ("../../includes/conecta_bd.php");
$excluir = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', data_exclusao='$data_alteracao', hora_exclusao='$hora_alteracao', motivo_exclusao='$motivo_exclusao' WHERE codigo='$codigo_pgto_favorecido'");
include ("../../includes/desconecta_bd.php");


include ("../../includes/conecta_bd.php");
$editar_compra = mysqli_query ($conexao, 
"UPDATE
	compras
SET
	situacao_pagamento='$situacao_pagamento',
	total_pago='$total_pago_atual',
	saldo_pagar='$saldo_pagar_atual'
WHERE
	numero_compra='$numero_compra'");
include ("../../includes/desconecta_bd.php");

}



else
{}


// SOMA PAGAMENTOS  ==========================================================================================
include ("../../includes/conecta_bd.php");
$soma_pagamentos_2 = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND estado_registro='ATIVO'"));
include ("../../includes/desconecta_bd.php");
$saldo_pagamento_2 = $valor_total - $soma_pagamentos_2[0];
$saldo_pagamento_print = number_format($saldo_pagamento_2,2,",",".");

$quant_saldo = $saldo_pagamento_2 / $preco_unitario;
$quant_saldo_print = number_format($quant_saldo,2,",",".");	


// ================================================================================================================
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




<div id="centro" style="height:390px; width:1080px; border:0px solid #0000FF; margin:auto">

<div id="espaco_2" style="width:1050px"></div>

<div id="centro" style="height:15px; width:1050px; border:0px solid #000; color:#003466; font-size:12px"></div>

<div id="centro" style="height:120px; width:1050px; border:0px solid #000; color:#003466; font-size:12px">

		<div id='centro' style='float:left; height:120px; width:1050px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:115px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:690px; color:#000066; text-align:left; border:1px solid #999; font-size:10px; line-height:15px'>
				<div style="margin-left:10px; margin-top:5px; margin-bottom:5px">
				<?php echo"
				N&ordm; da Compra: $numero_compra</br>
				Vendedor: $fornecedor_print</br>
				Produto: $produto_print</br>
				Quantidade: $quantidade_print $unidade_print</br>
				Valor Unit&aacute;rio: R$ $preco_unitario_print</br>
				Valor Total: <b>R$ $valor_total_print</b></br>
				Saldo a Pagar: <font style='color:#FF0000'><b>R$ $saldo_pagamento_print</b> <font style='color:#999'>&#160;&#160; (Ref. a $quant_saldo_print $unidade)<font></br>
				"; ?>
				</div>
			</div>
		</div>
</div>
<div id="centro" style="height:10px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:200px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:690px; border:0px solid #000; color:#003466; font-size:14px; float:left">
<b>&#160;&#160;&#8226; Selecione a forma de pagamento e o favorecido:</b>
</div>

<div id="centro" style="height:5px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<form name="forma_pagamento" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/forma_pagamento/forma_pagamento.php" method="post" />
<input type="hidden" name="numero_compra" value="<?php echo"$numero_compra"; ?>" />
<input type="hidden" name="botao" value="incluir" />
<input type="hidden" name="botao_relatorio" value="<?php echo"$botao_relatorio"; ?>" />
<input type='hidden' name='pagina_mae' value='<?php echo"$pagina_mae"; ?>'>
<input type='hidden' name='pagina_filha' value='<?php echo"$pagina_filha"; ?>'>
<input type='hidden' name='botao_relatorio' value='<?php echo"$botao_relatorio"; ?>' />
<input type='hidden' name='data_inicial' value='<?php echo"$data_inicial"; ?>'>
<input type='hidden' name='data_final' value='<?php echo"$data_final"; ?>'>
<input type='hidden' name='produto_list' value='<?php echo"$produto_list"; ?>'>
<input type='hidden' name='cod_produto' value='<?php echo"$cod_produto"; ?>'>
<input type='hidden' name='produtor_ficha' value='<?php echo"$produtor_ficha"; ?>'>
<input type='hidden' name='fornecedor' value='<?php echo"$fornecedor"; ?>'>
<input type='hidden' name='monstra_situacao' value='<?php echo"$monstra_situacao"; ?>'>
<input type='hidden' name='num_compra_aux' value='<?php echo"$num_compra_aux"; ?>'>




<div style="width:200px; height:195px; border:0px solid #000; float:left"></div>

<!-- ========================================================================================================== -->
<div id="tabela_2" style="width:180px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:179px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 1)
{echo "Forma de Pagamento <b style='color:#FF0000;'>*</b>";}
else
{echo "Forma de Pagamento";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:125px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:124px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 2)
{echo "Data de Pagamento <b style='color:#FF0000;'>*</b>";}
else
{echo "Data de Pagamento";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:120px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:118px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 3 or $erro == 5)
{echo "Valor <b style='color:#FF0000;'>*</b>";}
else
{echo "Valor";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:130px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:158px; height:5px; border:0px solid #000"></div>Cheque Banco:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:90px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:88px; height:5px; border:0px solid #000"></div>N&ordm; Cheque:</div>


<!-- =================================  FORMA PAGAMENTO ====================================== -->
<div id="tabela_2" style="width:180px; border:0px solid #000">
<select name='forma_pagamento' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:178px; height:21px; font-size:11px' id='ok' />
<option></option>
<?php
if ($botao == "incluir" and $forma_pagamento == "DINHEIRO")
{echo "<option value='DINHEIRO' selected='selected'>DINHEIRO</option>";}
else
{echo "<option value='DINHEIRO'>DINHEIRO</option>";}

if ($botao == "incluir" and $forma_pagamento == "CHEQUE")
{echo "<option value='CHEQUE' selected='selected'>CHEQUE</option>";}
else
{echo "<option value='CHEQUE'>CHEQUE</option>";}

if ($botao == "incluir" and $forma_pagamento == "TED")
{echo "<option value='TED' selected='selected'>TRANSFER&Ecirc;NCIA BANC&Aacute;RIA</option>";}
else
{echo "<option value='TED'>TRANSFER&Ecirc;NCIA BANC&Aacute;RIA</option>";}

if ($botao == "incluir" and $forma_pagamento == "OUTRA")
{echo "<option value='OUTRA' selected='selected'>OUTRA FORMA DE PGTO</option>";}
else
{echo "<option value='OUTRA'>OUTRA FORMA DE PGTO</option>";}

if ($botao == "incluir" and $forma_pagamento == "PREVISAO")
{echo "<option value='PREVISAO' selected='selected'>(PREVIS&Atilde;O)</option>";}
else
{echo "<option value='PREVISAO'>(PREVIS&Atilde;O)</option>";}
?>

</select>
</div>

<!-- =========================================  DATA PAGAMENTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:125px; border:0px solid #000">
<input type='text' name='data_pagamento' value='<?php echo "$data_print"; ?>' size='14' maxlength='10' id='calendario' onkeypress='mascara(this,data)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:118px; font-size:12px' />
</div>

<!-- =========================================  VALOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:120px; border:0px solid #000">
<input type='text' name='valor_pagamento' value='<?php echo "$saldo_pagamento_print"; ?>' maxlength='15' onkeypress='mascara(this,mvalor)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:118px; font-size:12px' />

</div>

<!-- =========================================  BANCO CHEQUE ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:130px; border:0px solid #000">
<select name="banco_cheque" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:128px; height:20px; font-size:11px" />
<option></option>
<?php
if ($botao == "incluir" and $banco_cheque == "BANCO DO BRASIL")
{echo "<option value='BANCO DO BRASIL' selected='selected'>BANCO DO BRASIL</option>";}
else
{echo "<option value='BANCO DO BRASIL'>BANCO DO BRASIL</option>";}

if ($botao == "incluir" and $banco_cheque == "BANESTES")
{echo "<option value='BANESTES' selected='selected'>BANESTES</option>";}
else
{echo "<option value='BANESTES'>BANESTES</option>";}

if ($botao == "incluir" and $banco_cheque == "SICOOB")
{echo "<option value='SICOOB' selected='selected'>SICOOB</option>";}
else
{echo "<option value='SICOOB'>SICOOB</option>";}
?>

</select>
</div>


<!-- =========================================  NUMERO CHEQUE ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:90px; border:0px solid #000">
<input type="text" name="numero_cheque" maxlength="15" onkeypress='mascara(this,numero)' onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:88px; font-size:12px" value="<?php echo"$numero_cheque"; ?>" />
</div>





<!-- ====================================================================================== -->
<div id="tabela_2" style="width:620px; height:19px; border:0px solid #000; float:left">
<div id="espaco_1" style="width:615px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 4 or $erro == 6)
{echo "Favorecido (F2) <b style='color:#FF0000;'>*</b>";}
else
{echo "Favorecido (F2)";}
?>
</div>


<div id="tabela_2" style="width:180px; height:19px; border:0px solid #000; float:left">
<div id="espaco_1" style="width:175px; height:5px; border:0px solid #000"></div>
Nota Fiscal/Adiantamento</div>


<!-- =========================================  FAVORECIDO ====================================== -->
<div id="tabela_2" style="width:620px; border:0px solid #000; float:left">
<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">

<!-- ========================================================================================================== -->
<script type="text/javascript">
function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=270,width=700');
	}

</script>
<script type="text/javascript" src="representante_funcao.js"></script>

<!-- ========================================================================================================== -->
<div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar produtor" />
<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php" target="_blank">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/editar.png" border="0" height="18px" title="Cadastrar novo fornecedor" /></a>
</div>

<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">
&#160;

<!-- ========================================================================================================== -->
<script type="text/javascript">
document.onkeyup=function(e)
	{
		if(e.which == 113)
		{
			//Pressionou F2, aqui vai a função para esta tecla.
			//alert(tecla F2);
			var aux_f2 = document.forma_pagamento.representante.value;
			javascript:foco('busca');
			javascript:abrir('busca_pessoa_popup.php');
			//javascript:buscarNoticias(aux_f2);
		}
	}
</script>

<!-- ========================================================================================================== -->
<input id='busca' type='text' name='representante' onClick='buscarNoticias(this.value)' onBlur='buscarNoticias(this.value)' onkeydown='if (getKey(event) == 13) return false; ' style='color:#0000FF; width:50px; font-size:12px' value='<?php echo "$codigo_favorecido"; ?>' />
&#160;</div>
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="resultado" style="width:415px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


</div>


</div>
<!-- ========================================================================================================== -->

<div id="tabela_2" style="width:180px; border:0px solid #000">
<select name="nf_adto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:150px; height:20px; font-size:11px" />
<option></option>
<option value="NF">Nota Fiscal</option>
<option value="ADTO">Adiantamento</option>

<?php
/*
if ($botao == "incluir" and $nf_adto == "NF")
{echo "<option value='NF' selected='selected'>Nota Fiscal</option>";}
else
{echo "<option value='NF'>Nota Fiscal</option>";}

if ($botao == "incluir" and $nf_adto == "ADTO")
{echo "<option value='ADTO' selected='selected'>Adiantamento</option>";}
else
{echo "<option value='ADTO'>Adiantamento</option>";}
*/
?>

</select>
</div>



<!-- ====================================================================================== -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o</div>


<!-- ====================================================================================== -->
<div id="geral" style="width:730px; height:25px; border:0px solid #000; font-size:12px; color:#FF0000; float:left">
<input type="text" name="obs_pgto" maxlength="200" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:600px; float:left" />
<button type='submit' class='botao_1' style='margin-left:10px'>Salvar</button>
</form>
</div>



<!-- ====================================================================================== -->
<div id="geral" style="width:850px; height:20px; border:0px solid #000; font-size:10px; color:#999; float:left; margin-top:6px">
<?php
if ($desconto_quantidade_2 > 0)
{echo "
&#10033; Acerto de Quantidade: Quantidade original: $quantidade_original $unidade_print - Valor original: R$ $valor_total_original - Motivo: $motivo_alteracao_quant - Desconto: $desconto_quantidade $unidade_print (R$ $desc_em_valor_print)
";}
else
{}
?>
</div>

<div id="geral" style="width:850px; height:20px; border:0px solid #000; font-size:12px; color:#FF0000; float:left; margin-top:6px">
<?php echo "$mensagem_erro"; ?>
</div>


</div>





<!-- ================== INICIO DO RELATORIO ================= -->
<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:5px;">

<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
<?php
include ("../../includes/conecta_bd.php");
$busca_favorecidos_pgto = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
include ("../../includes/desconecta_bd.php");
$linha_favorecidos_pgto = mysqli_num_rows ($busca_favorecidos_pgto);


if ($linha_favorecidos_pgto == 0)
{echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existe pagamento para esta compra.</i></div>";}
else
{echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='90px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='300px' align='center' bgcolor='#006699'>Favorecido</td>
<td width='100px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='270px' align='center' bgcolor='#006699'>Dados Banc&aacute;rios</td>
<td width='100px' align='center' bgcolor='#006699'>Quantidade Ref.</td>
<td width='100px' align='center' bgcolor='#006699'>Valor (R$)</td>
<td width='60px' align='center' bgcolor='#006699'>Estornar</td>
</tr>
</table>
</div>
<div id='centro' style='height:10px; width:1030px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";

for ($w=1 ; $w<=$linha_favorecidos_pgto ; $w++)
{
	$aux_favorecido = mysqli_fetch_row($busca_favorecidos_pgto);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);
	
	include ("../../includes/conecta_bd.php");
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
	include ("../../includes/desconecta_bd.php");
	$fav_2_aux = mysqli_fetch_row($busca_favorecido_2);
	
	$codigo_pessoa_2 = $fav_2_aux[1];
	$banco_2 = $fav_2_aux[2];
	$agencia_2 = $fav_2_aux[3];
	$conta_2 = $fav_2_aux[4];
	$tipo_conta_2 = $fav_2_aux[5];
	$conta_conjunta = $fav_2_aux[15];
	
	include ("../../includes/conecta_bd.php");
	$busca_banco_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
	include ("../../includes/desconecta_bd.php");
	$banco_2_aux = mysqli_fetch_row($busca_banco_2);
	
	$banco_print_2 = $banco_2_aux[3];
	
	if ($tipo_conta_2 == "corrente")
	{$tipo_conta_print_2 = "C/C";}
	elseif ($tipo_conta_2 == "poupanca")
	{$tipo_conta_print_2 = "C/P";}
	else
	{$tipo_conta_print_2 = "C.";}
	
	include ("../../includes/conecta_bd.php");
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	include ("../../includes/desconecta_bd.php");
	$pessoa_2_aux = mysqli_fetch_row($busca_pessoa_2);
	
	$nome_favorecido_2 = $pessoa_2_aux[1];
	$tipo_pessoa_2 = $pessoa_2_aux[2];
		if ($tipo_pessoa_2 == "pf" or $tipo_pessoa_2 == "PF")
		{$cpf_cnpj_2 = $pessoa_2_aux[3];}
		else
		{$cpf_cnpj_2 = $pessoa_2_aux[4];}
		
	$valor_pagamento_print_2 = number_format($aux_favorecido[5],2,",",".");
	$quant_ref = $aux_favorecido[5] / $preco_unitario;
	$quant_ref_print = number_format($quant_ref,2,",",".");

// FORMA DE PAGAMENTO =========================
	if ($aux_favorecido[3] == "DINHEIRO")
	{$forma_pagamento_2 = "Dinheiro";}
	elseif ($aux_favorecido[3] == "CHEQUE")
	{$forma_pagamento_2 = "Cheque";}
	elseif ($aux_favorecido[3] == "TED")
	{$forma_pagamento_2 = "Transfer&ecirc;ncia";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$forma_pagamento_2 = "Outra";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$forma_pagamento_2 = "(PREVIS&Atilde;O)";}
	else
	{$forma_pagamento_2 = "-";}
	
// DADOS BANCARIOS =========================
	if ($aux_favorecido[3] == "CHEQUE")
	{$dados_bancarios_2 = " $aux_favorecido[6] ( N&ordm; cheque: $aux_favorecido[18] )";}
	elseif ($aux_favorecido[3] == "TED")
	{$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";}
	elseif ($aux_favorecido[3] == "DINHEIRO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$dados_bancarios_2 = "$obs_pgto";}
	else
	{$dados_bancarios_2 = "-";}

// RELATORIO =========================
	echo "
	<tr style='color:#00F' title='Observa&ccedil;&atilde;o: $obs_pgto'>
	<td width='90px' align='left'>&#160;&#160;$data_pagamento_print_2</td>";
	
	if ($conta_conjunta == "SIM")
	{echo "<td width='300px' align='left'>&#160;&#160;$nome_favorecido_2 - $aux_favorecido[2] (*)</td>";}
	else
	{echo "<td width='300px' align='left'>&#160;&#160;$nome_favorecido_2 - $aux_favorecido[2]</td>";}

	
	
	echo "
	<td width='100px' align='left'>&#160;&#160;$forma_pagamento_2</td>
	<td width='270px' align='left'>&#160;&#160;$dados_bancarios_2</td>
	<td width='100px' align='center'>$quant_ref_print $unidade</td>
	<td width='100px' align='right'>$valor_pagamento_print_2&#160;&#160;</td>";
	
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "
	<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post'>
		<input type='hidden' name='botao' value='excluir' />
		<input type='hidden' name='codigo_pgto_favorecido' value='$aux_favorecido[0]' />
		<input type='hidden' name='numero_compra' value='$numero_compra' />
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='botao_relatorio' value='$botao_relatorio' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<input type='hidden' name='valor_excluido' value='$aux_favorecido[5]'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' /></form>
	</td>";}
	else
	{echo "
	<td width='60px' align='center'>
	</td>";}
		
							
	echo "
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


<div id="centro" style="height:15px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center"></div>
<div id="centro" style="height:180px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center">
<?php
	if ($botao_relatorio == "financeiro")
	{echo "<div id='centro' style='float:left; height:55px; width:222px; color:#00F; text-align:center; border:0px solid #000'></div>";}
	else
	{echo "<div id='centro' style='float:left; height:55px; width:330px; color:#00F; text-align:center; border:0px solid #000'></div>";}

?>


<?php
	if ($botao_relatorio == "relatorio")
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
		</form>
		</div>";}
		
	elseif ($botao_relatorio == "financeiro")
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/financeiro/compras/relatorio_numero.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
		</form>
		</div>";}
		
	elseif ($pagina_mae == "movimentacao_produtor")
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
		</form>
		</div>";}
		
	else
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='botao' value='seleciona'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Ficha do Produtor</button></form>
		</div>";}


		
	if ($botao_relatorio == "financeiro")
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/financeiro/compras/compra_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Imprimir Compra</button>
		</form>
		</div>";
		
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/financeiro/compras/compra_impressao_recibo.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Imprimir Recibo</button>
		</form>
		</div>";}
	
	else
	{echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/compra_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Imprimir Compra</button>
		</form>
		</div>";}
		
?>

	<div style="float:left; width:1030px; height:auto; border:0px solid #000; font-size:10px; color:#999; text-align:center">
	<?php
    if ($forma_pagamento == "TED")
	{echo "Retorno WebService Rovereti: " . $retorno . "</br></br>";
	/*echo "Parametros: " . $parametros;*/}
	else
	{}
	?>
    </div>




</div>
</div> <!-- ================================== FIM DA DIV CENTRO GERAL ======================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>