<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include_once("../../helpers.php");
$pagina = 'extrato_ficha_impressao';
$titulo = 'Movimenta&ccedil;&atilde;o Ficha do Produtor';
$modulo = 'compras';
$menu = 'ficha_produtor';
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$hoje = date('Y-m-d', time());
$data_hoje = date('d/m/Y', time());
$mes_atras = date ('d/m/Y', strtotime('-60 days')); // 2 m�ses atras

$filial = $filial_usuario;
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];

if ($botao == "seleciona")
{
$data_inicial_aux = $mes_atras;
$data_inicial = Helpers::ConverteData($mes_atras);
$data_final_aux = $data_hoje;
$data_final = Helpers::ConverteData($data_hoje);
}
else
{
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
}

$dia_atras = date('Y-m-d', strtotime('-1 days', strtotime($data_inicial)));

if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf")
{$cpf_cnpj = "CPF: " . $aux_forn[3];}
elseif ($aux_forn[2] == "pj")
{$cpf_cnpj = "CNPJ: " . $aux_forn[4];}
else
{$cpf_cnpj = "";}

if ($linhas_fornecedor == 0)
{$cid_uf_fornecedor = "";}
else
{$cid_uf_fornecedor = $cidade_fornecedor . "-" . $estado_fornecedor;}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA E SOMA COMPRAS ========================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' ORDER BY data_compra");
$linha_compra = mysqli_num_rows ($busca_compra);
	
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' "));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// ======================================================================================================


// ====== SALDO PRODUTOR =================================================================================
// ------ SOMA QUANTIDADE DE ENTRADA (PER�ODO) --------------------------------------------------------------------
$soma_quant_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_e_print = number_format($soma_quant_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SA�DA (PER�ODO) -----------------------------------------------------------------------
$soma_quant_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_s_print = number_format($soma_quant_produto_s[0],2,",",".");

// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SA�DA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0],2,",",".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto,2,",",".");
// ================================================================================================================


// ====== SOMA SALDO ANTERIOR  ====================================================================================
$soma_ant_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND data_compra<='$dia_atras' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$soma_ant_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND data_compra<='$dia_atras' AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$saldo_ant = ($soma_ant_e[0] - $soma_ant_s[0]);
$saldo_ant_print = number_format($saldo_ant,2,",",".");
// ================================================================================================================


// ================================================================================================================
include ('../../includes/head_impressao.php');
// ================================================================================================================
?>


<!-- ==================================   T � T U L O   D A   P � G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N � C I O   =============================================== -->
<body onLoad="imprimir()">

<div id="centro" style="width:745px; border:0px solid #F00">
<?php
// ================================================================================================================
echo "
<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='170px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	FICHA DO PRODUTOR - EXTRATO DE MOVIMENTA&Ccedil;&Otilde;ES<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$produto_print</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>

<!-- ================================================================================================================ -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:5px; margin-left:10px; font-size:12px' align='center'>

	<div id='centro' style='width:500px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Nome:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$fornecedor_print</div>
	</div>

	<div id='centro' style='width:215px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>$cpf_cnpj</div>
	<div style='margin-top:3px; margin-left:5px; float:left'></div>
	</div>

	<div id='centro' style='width:500px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Cidade:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$cidade_fornecedor - $estado_fornecedor</div>
	</div>

	<div id='centro' style='width:215px; height:20px; border:0px solid #000; font-size:12px; float:left' align='left'>
	<div style='margin-top:3px; margin-left:5px; float:left'>Telefone:</div>
	<div style='margin-top:3px; margin-left:5px; float:left'>$telefone_fornecedor</div>
	</div>

</div>




<!-- =================================================================================================================== -->

<div id='centro' style='width:680px; border:0px solid #000; margin-top:1px; margin-left:00px; float:left'>";

	echo "<div id='centro' style='width:200px; height:15px; border:	0px solid #000; float:left; font-size:10px'>";
		if ($monstra_situacao == "")
		{echo "<i>Per&iacute;odo: GERAL</i>";}
		else
		{echo "<i>Per&iacute;odo: $data_inicial_aux at&eacute; $data_final_aux</i>";}
	echo "</div>";

	echo "<div id='centro' style='width:150px; height:15px; border:0px solid #000; float:left; text-align:center; font-size:10px'>";
		if ($linha_compra >= 1)
		{echo"<i>Entradas: <b>$quant_produto_e_print</b> $unidade_print</i>";}
		else
		{echo"";}
	echo "</div>";

	echo "<div id='centro' style='width:240px; height:15px; border:0px solid #000; float:left; text-align:center; font-size:10px'>";
		if ($linha_compra >= 1)
		{echo"<i>Compras / Transfer&ecirc;ncias: <b>$quant_produto_s_print</b> $unidade_print</i>";}
		else
		{echo"";}
	echo "</div>";
	
	echo "<div id='centro' style='width:80px; height:15px; border:0px solid #000; float:right; text-align:center; font-size:10px'>";
		if ($linha_compra == 1)
		{echo"<i>$linha_compra Registro</i>";}
		elseif ($linha_compra == 0)
		{echo"";}
		else
		{echo"<i>$linha_compra Registros</i>";}
	echo "</div>";

echo "</div>";


echo "
<!-- =================================================================================================================== -->

<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Data</i></div>
	
	<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Movimenta&ccedil;&atilde;o</i></div>
	
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>N&ordm;</i></div>
	
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Produto</i></div>

	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Tipo</i></div>
	
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Quantidade</i></div>
	
	<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Pre&ccedil;o Un</i></div>
	
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	<i>Saldo</i></div>
	
</div>

<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>
	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'></div>
	<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
	background-color:#FFF; text-transform:uppercase;'>
	&#160;&#160;<i>SALDO ANTERIOR</i></div>
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'></div>
	<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'></div>
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>";
	echo"<i>$saldo_ant_print&#160;</i>";
	echo"
	</div>
</div>";
// ================================================================================================================


// ====== FUN��O FOR ==============================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

//$produto = $aux_compra[3];
//$cod_produto = $aux_compra[39];
//$unidade = $aux_compra[8];
//$unidade_print = $aux_compra[8];
//$fornecedor = $aux_compra[2];
$numero_compra = $aux_compra[1];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
if ($preco_unitario == 0)
{$preco_unitario_print = "";}
else
{$preco_unitario_print = number_format($aux_compra[6],2,",",".");}
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $aux_compra[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$movimentacao = $aux_compra[16];
$observacao = $aux_compra[13];
$numero_romaneio = $aux_compra[28];
$desc_quant = number_format($aux_compra[29],2,",",".");
$numero_transferencia = $aux_compra[30];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$usuario_alteracao = $aux_compra[21];
if ($aux_compra[23] == "")
{$data_alteracao = "";}
else
{$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));}
$hora_alteracao = $aux_compra[22];
// ================================================================================================================


// ====== MOVIMENTACAO PRINT ======================================================================================
include ('inc_movimentacao_print.php');
// ================================================================================================================


// ====== BUSCA PAGAMENTO  ========================================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
// ================================================================================================================


// ====== CALCULO SALDO A PAGAR  ==================================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");
// ================================================================================================================


// ====== CALCULO SALDO QUANTIDADE  ===============================================================================
if ($movimentacao == "COMPRA" or $movimentacao == "TRANSFERENCIA_SAIDA" or $movimentacao == "SAIDA" or $movimentacao == "SAIDA_FUTURO")
{$saldo_quant = $saldo_ant - $quantidade;}
else
{$saldo_quant = $saldo_ant + $quantidade;}
$saldo_quant_print = number_format($saldo_quant,2,",",".");
$saldo_ant = $saldo_quant;
// ================================================================================================================


// ====== SE FOR COMPRA  =================================================================================
if ($aux_compra[0] == "")
{
echo "
<div id='centro' style='width:675px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
</div>";	
}
	
else
{
// ------ RELATORIO -----------------------------------------------------------------------------------
echo "
<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
	&#160;&#160;$data_compra_print</div>
	
	<div id='centro' style='width:170px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
	background-color:#FFF; text-transform:uppercase;'>
	&#160;&#160;$movimentacao_print</div>
	
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
	$numero_compra</div>
	
	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
	$produto_print</div>

	<div id='centro' style='width:85px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
	$tipo</div>
	
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
	$quantidade_print $unidade_print</div>
	
	<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
	$preco_unitario_print&#160;</div>
	
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
	$saldo_quant_print&#160;</div>
	
</div>";
}
// ----------------------------------------------------------------------------------------------------
}


if ($linha_compra == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='785px' height='15px' align='left'>&#160;&#160;<i>Nenhum registro encontrado.</i></td></tr>";}






echo "
</div>

<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr />
</div>

<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:60px; border:0px solid #000; margin-left:10px; float:left; border-radius:7px;' align='center'>";


// ====== BUSCA TIPO  =================================================================================
$busca_tipo = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' ORDER BY codigo");
$linha_tipo = mysqli_num_rows ($busca_tipo);

for ($t=1 ; $t<=$linha_tipo ; $t++)
{
$aux_tipo = mysqli_fetch_row($busca_tipo);


	$soma_tipo_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));
	
	$soma_tipo_saida = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial' AND cod_tipo='$aux_tipo[0]'"));

	$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
	$saldo_tipo_print = number_format($saldo_tipo,2,",",".");

// ===================================================================================================================================
if ($saldo_tipo == 0)
{echo "";}
else
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		
		</div>
		<div id='centro' style='height:15px; width:150px; margin-left:1px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		 Saldo Tipo: $aux_tipo[1]
		</div>
		<div id='centro' style='height:15px; width:250px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
		<b>$saldo_tipo_print $unidade_print</b>
		</div>
		<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
		</div>
	</div>";}

}
// ===================================================================================================================================
echo "
<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
	<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
	<div id='centro' style='height:15px; width:10px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
	<b></b>	
	</div>
	<div id='centro' style='height:15px; width:300px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
	Total de Compras: R$ $soma_compras_print
	</div>
	<div id='centro' style='height:15px; width:150px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:10px; color:#000000'>
	
	</div>
	<div id='centro' style='height:15px; width:220px; margin-left:0px; margin-top:3px; border:0px solid #999; float:right; text-align:right; font-size:10px; color:#000000'>
	
	</div>
</div>";





echo "
</div>


<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr style='border:1px solid #000' /></div>";
?>

<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy;
<?php
$ano_atual_rodape = date('Y');
echo" $ano_atual_rodape ";
?>
Suif - Solu&ccedil;&otilde;es Web | <?php echo"$nome_fantasia"; ?>
</div>
<div id="centro" style="width:250px; height:25px; border:0px solid #000; font-size:9px; float:left" align="center">
</div>
<div id="centro" style="width:30px; height:25px; border:0px solid #000; font-size:8px; float:left" align="center">
</div>
<div id="centro" style="width:200px; height:25px; border:0px solid #000; font-size:9px; float:left" align="right">
<?php echo"$nome_usuario_print" ?> ( <?php echo"$filial" ?> )
</div>

</div>
<!-- =============================================================================================== -->



</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->