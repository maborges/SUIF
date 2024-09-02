<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");


$pagina = 'compra_impressao';
$titulo = 'Impress&atilde;o de Compra';
$menu = 'produtos';
$modulo = 'compras';

include ('../../includes/head_impressao.php');
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

<div style='border:1px solid #FFF'>
<div id="centro" style="width:745px; border:0px solid #000; float:left; page-break-after:always">

<?php


	$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	$dia = date ("d", time());
	$mes = date ("m", time());
	$ano = date ("Y", time());
	$hoje = date ("Y-m-d", time());

// ==================================================================================================================	

$numero_compra = $_POST["numero_compra"];

// =============================================================================================================
// =============================================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
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
$tipo_secagem = $aux_compra[27];
$observacao = $aux_compra[13];
$motivo_alteracao_quant = $aux_compra[35];
$quantidade_original = number_format($aux_compra[36],2,",",".");
$desconto_quantidade = number_format($aux_compra[29],2,",",".");
$desconto_quantidade_2 = $aux_compra[29];
$valor_total_original = number_format($aux_compra[37],2,",",".");
$desconto_em_valor = ($aux_compra[29] * $aux_compra[6]);
$desc_em_valor_print = number_format($desconto_em_valor,2,",",".");


$usuario_print = $aux_compra[18];
$filial_print = $aux_compra[25];



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

// SITUA��O PRINT  ==========================================================================================
	if ($situacao == "POSTO")
	{$situacao_print = "POSTO";}
	elseif ($situacao == "A_RETIRAR")
	{$situacao_print = "A RETIRAR";}
	elseif ($situacao == "ARMAZENADO")
	{$situacao_print = "ARMAZENADO";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($y=1 ; $y<=$linha_pessoa ; $y++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
$cod_fornecedor_print = $aux_pessoa[0];
	if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf")
	{$cpf_cnpj = $aux_pessoa[3];}
	else
	{$cpf_cnpj = $aux_pessoa[4];}
}
// =================================================================================================================



// =============================================================================================================
// =============================================================================================================
?>
<div id="centro" style="width:720px; height:10px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center"></div>
<div id="centro" style="width:720px; height:65px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" alt="Grancafe" border="0" height="80px" />
</div>

<div id="centro" style="width:720px; height:52px; border:0px solid #000; margin-left:25px; font-size:17px; float:left" align="center">

    <div id="centro" style="width:238px; height:30px; border:0px solid #000; font-size:10px; float:left" align="left"></div>

    <div id="centro" style="width:238px; height:30px; border:0px solid #000; font-size:13px; float:left" align="center">
    <b>CONFIRMA&Ccedil;&Atilde;O DE COMPRA</b></div>

    <div id="centro" style="width:238px; height:30px; border:0px solid #000; font-size:9px; float:left" align="right"></div>
	
	

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="left">N&ordm; <?php echo"$numero_compra"; ?></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:13px; float:left" align="center">
    <?php echo"<b>$produto_print</b>"; ?></div>

    <div id="centro" style="width:238px; height:20px; border:0px solid #000; font-size:12px; float:left" align="right">
    <?php
	echo"$data_compra_print";
	/*
    $data_atual = date('d/m/Y', time());
    $hora_atual = date('G:i:s', time());
    echo"$data_atual $hora_atual"*/
	?>
    </div>



</div>


<!-- ======================================================================================================================================= -->

<div id="centro" style="width:735px; border:0px solid #000; margin-top:2px; margin-left:20px; float:left">


<!-- ========================================================== DADOS DO VENDEDOR ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:50px; border:0px solid #000; font-size:9px">
	<div style="margin-top:30px; margin-left:5px">Dados do Vendedor:</div></div>
		<div id="tabela_2" style="width:730px; height:80px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:705px; height:15px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:540px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div></div>
			<div style="width:180px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

			<div style="width:705px; height:10px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:190px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">C&oacute;digo:</div><div style="margin-top:3px; margin-left:5px; float:left">
			<?php echo"<b>$cod_fornecedor_print</b>" ?></div></div>
			
			<div style="width:350px; height:25px; border:0px solid #000; float:left; font-size:11px; text-align:center">
			<div style="margin-top:3px; width:338px; float:left; text-align:center">Cidade:<?php echo" <b>$aux_pessoa[10] - $aux_pessoa[12]</b>" ?></div></div>

			<div style="width:180px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left">
			<?php echo"<b>$aux_pessoa[14]</b>" ?></div></div>




		</div>

<!-- ========================================================== DADOS DA COMPRA ============================================================================= -->
	<div id="tabela_2" style="width:730px; height:50px; border:0px solid #000; font-size:9px; margin-top:20px">
	<div style="margin-top:30px; margin-left:5px">Dados da Compra:</div></div>
		<div id="tabela_2" style="width:730px; height:130px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden">

			<div style="width:705px; height:15px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:160px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:120px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Safra:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$safra</b>" ?></div></div>
			<div style="width:160px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$tipo</b>" ?></div></div>
			<div style="width:100px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Umidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$umidade</b>" ?></div></div>
			<div style="width:180px; height:25px; border:0px solid #000; float:left; font-size:11px">
			
			<?php
			if ($produto == "CAFE")
			{$escreve_1 = "Broca:";}
			elseif ($produto == "PIMENTA")
			{$escreve_1 = "Impureza:";}
			else
			{$escreve_1 = "";}
			?>
	
			<div style="margin-top:3px; margin-left:5px; float:left"><?php echo "$escreve_1"; ?></div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$broca</b>" ?></div></div>

			<div style="width:705px; height:15px; border:0px solid #000; float:left; font-size:11px"></div>


			<?php
			if ($produto == "PIMENTA")
			{echo "
			<div style='width:700px; height:25px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'>Tipo Secagem:</div><div style='margin-top:3px; margin-left:5px; float:left'>$tipo_secagem</div></div>";}
			else
			{echo "
			<div style='width:700px; height:25px; border:0px solid #000; float:left; font-size:11px'>
			<div style='margin-top:3px; margin-left:5px; float:left'><!-- Tipo Secagem: --></div><div style='margin-top:3px; margin-left:5px; float:left'></div></div>";}
			?>

			<div style="width:705px; height:15px; border:0px solid #000; float:left; font-size:11px"></div>


			<div style="width:270px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_print</b> $unidade_print" ?></div></div>
			<div style="width:270px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Pre&ccedil;o Unit&aacute;rio:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $preco_unitario_print</b>" ?></div></div>
			<div style="width:180px; height:25px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Valor Total:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>R$ $valor_total_print</b>" ?></div></div>


		</div>
<?php
// SOMA PAGAMENTOS  ==========================================================================================
//$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND situacao_pagamento='PAGO' AND estado_registro='ATIVO'"));

$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND forma_pagamento!='PREVISAO' AND estado_registro='ATIVO'"));
$saldo_pagamento = $valor_total - $soma_pagamentos[0];
$saldo_pagamento_print = number_format($saldo_pagamento,2,",",".");
?>

<!-- ==================================================================================================================================================== -->
	<div id="tabela_2" style="width:730px; height:12px; border:0px solid #000; font-size:9px; margin-top:20px">
		<div style="margin-top:0px; margin-left:5px; width:495px; height:10px; float:left; border:0px solid #000">Observa&ccedil;&atilde;o:</div>
		<div style="margin-top:0px; margin-left:0px; width:30px; height:10px; float:left; border:0px solid #000"></div>
		<div style="margin-top:0px; margin-left:5px; width:100px; height:10px; float:left; border:0px solid #000">Saldo a Pagar:</div>
	</div>

<!-- ========================================================== OBSERVACAO ============================================================================= -->
		<div id="tabela_2" style="width:500px; height:30px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden">
			<div style="margin-top:8px; margin-left:5px; width:495px; height:18px; float:left; border:0px solid #000; overflow:hidden"><?php echo"$observacao" ?></div>
		</div>

		<div id="tabela_2" style="width:28px; height:30px; border:0px solid #999; color:#000; border-radius:5px; overflow:hidden">
		</div>

<!-- ========================================================== SALDO A PAGAR ============================================================================= -->
		<div id="tabela_2" style="width:200px; height:30px; border:1px solid #000; color:#000; border-radius:5px; overflow:hidden">
			<div style="margin-top:8px; margin-left:5px; width:195px; height:18px; float:left; border:0px solid #000; font-size:13px; text-align:center">
			<?php echo"<b>R$ $saldo_pagamento_print</b>" ?></div>
		</div>



<!-- ========================================================== DADOS DO PAGAMENTO ============================================================================= -->
	<div id="geral" style="width:730px; height:50px; border:0px solid #000; font-size:9px; margin-top:20px">
<?php
$busca_favorecidos_pgto = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
$linha_favorecidos_pgto = mysqli_num_rows ($busca_favorecidos_pgto);

$quant_saldo = $saldo_pagamento / $preco_unitario;
$quant_saldo_print = number_format($quant_saldo,2,",",".");	
?>

	<div style="margin-top:25px; margin-left:5px; width:400px; height:20px; border:0px solid #000; float:left">Dados do Pagamento:</div>
		<?php
		if ($saldo_pagamento == 0)
			{echo "
			<div style='margin-top:25px; margin-left:20px; width:300px; height:20px; border:0px solid #000; float:left; text-align:right; font-size:9px'>
			<div style='margin-top:2px'><i>( Compra Liquidada )</i></div></div>";}
		
		else
			{echo "
			<div style='margin-top:25px; margin-left:20px; width:300px; height:20px; border:0px solid #000; float:left; text-align:right; font-size:9px'>
			<div style='margin-top:2px'><i>Saldo em aberto: R$ $saldo_pagamento_print (ref. a $quant_saldo_print $unidade)</i></div></div>";}
		?>
	</div>
	

<!-- ======================================================================================================================================================= -->


<!-- ================== INICIO DO RELATORIO ================= -->
<?php
if ($linha_favorecidos_pgto <= 13)
{echo "
<div id='tabela_2' style='width:730px; height:350px; border:0px solid #FF0000; color:#000; border-radius:5px; overflow:hidden'>
<div id='centro' style='height:15px; width:725px; border:0px solid #999; margin:auto'></div>";}

else
{echo "
<div id='tabela_2' style='width:730px; height:auto; border:0px solid #FF0000; color:#000; border-radius:5px; overflow:hidden'>
<div id='centro' style='height:15px; width:725px; border:0px solid #999; margin:auto'></div>";}






if ($linha_favorecidos_pgto == 0)
{echo "<div id='centro' style='height:30px; width:725px; border:0px solid #999; font-size:12px; color:#000; margin-left:30px'><!-- <i>N&atilde;o existem pagamentos para esta compra.</i> --></div>";}
else
{echo "

<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>

	<div style='width:56px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Data Pgto:</i></div></div>
	<div style='width:160px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Favorecido:</i></div></div>
	<div style='width:90px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>CPF/CNPJ:</i></div></div>
	<div style='width:70px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Forma de Pgto:</i></div></div>
	<div style='width:185px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Dados Banc&aacute;rios:</i></div></div>
	<div style='width:50px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Quant:</i></div></div>
	<div style='width:65px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>Valor:</i></div></div>
	<div style='width:17px; height:17px; border:1px solid #999; float:left; font-size:9px; margin-left:2px; text-align:center; background-color:#CCC'>
	<div style='margin-top:4px'><i>PG</i></div></div>

</div>

<div id='centro' style='height:10px; width:725px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:725px; border:0px solid #999; margin:auto'>";

for ($w=1 ; $w<=$linha_favorecidos_pgto ; $w++)
{
	$aux_favorecido = mysqli_fetch_row($busca_favorecidos_pgto);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);

	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
	$aux_f2 = mysqli_fetch_row($busca_favorecido_2);
	
	$codigo_pessoa_2 = $aux_f2[1];
	$banco_2 = $aux_f2[2];
	$agencia_2 = $aux_f2[3];
	$conta_2 = $aux_f2[4];
	$tipo_conta_2 = $aux_f2[5];
	$conta_conjunta = $aux_f2[15];
	
	$busca_banco_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
	$aux_b2 = mysqli_fetch_row($busca_banco_2);
	$banco_print_2 = $aux_b2[3];
	
	if ($tipo_conta_2 == "corrente")
	{$tipo_conta_print_2 = "C/C";}
	elseif ($tipo_conta_2 == "poupanca")
	{$tipo_conta_print_2 = "C/P";}
	else
	{$tipo_conta_print_2 = "C.";}
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_p2[1];
	$tipo_pessoa_2 = $aux_p2[2];
		if ($tipo_pessoa_2 == "PF" or $tipo_pessoa_2 == "pf")
		{$cpf_cnpj_2 = $aux_p2[3];}
		else
		{$cpf_cnpj_2 = $aux_p2[4];}
		
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
	{$forma_pagamento_2 = "( PREVIS&Atilde;O )";}
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
	<div id='centro' style='height:5px; width:725px; border:0px solid #999; margin:auto; float:left'></div>
	
	<div style='width:56px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
	<div style='margin-top:3px; margin-left:4px; float:left'>$data_pagamento_print_2</div></div>
	<div style='width:160px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px; overflow:hidden'>
	<div style='margin-top:3px; margin-left:4px; float:left'>$nome_favorecido_2</div></div>
	<div style='width:90px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
	<div style='margin-top:3px; margin-left:4px; float:left'>$cpf_cnpj_2</div></div>
	<div style='width:70px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
	<div style='margin-top:3px; margin-left:4px; float:left'>$forma_pagamento_2</div></div>
	<div style='width:185px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
	<div style='margin-top:3px; margin-left:4px; float:left'>$dados_bancarios_2</div></div>
	<div style='width:50px; height:17px; border:1px solid #000; float:left; text-align:center; font-size:7px; margin-left:2px'>
	<div style='margin-top:3px'>$quant_ref_print $unidade</div></div>
	<div style='width:65px; height:17px; border:1px solid #000; float:left; font-size:8px; margin-left:2px'>
	<div style='margin-top:3px; margin-right:4px; float:right'>$valor_pagamento_print_2</div></div>";
	
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "
	<div style='width:17px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-right:4px; float:right'></div></div>";}
	else
	{echo "
	<div style='width:17px; height:17px; border:1px solid #000; float:left; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px; margin-right:4px; float:right'><b>&#10004;</b></div></div>";}
	
}
echo "
	<div id='centro' style='height:5px; width:725px; border:0px solid #999; margin:auto; float:left'></div>";

/*
if ($saldo_pagamento == 0)
	{echo "
	<div style='width:725px; height:17px; border:0px solid #000; float:left; text-align:center; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px'><i>( Compra Liquidada )</i></div></div>";}

else
	{echo "
	<div style='width:725px; height:17px; border:0px solid #000; float:left; text-align:center; font-size:9px; margin-left:2px'>
	<div style='margin-top:2px'><i>Saldo em aberto: R$ $saldo_pagamento_print (ref. a $quant_saldo_print $unidade)</i></div></div>";}
*/

echo "</div>";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->



<div id="centro" style="width:720px; height:18px; border:0px solid #000; margin-left:10px; font-size:12px; float:left" align="center">
<?php
echo "Declaro ter recebido o(s) pagamento(s) discriminado(s) nesta confirma&ccedil;&atilde;o de compra.";
?>
</div>



<div id="centro" style="width:720px; height:30px; border:0px solid #000; margin-left:10px; font-size:12px; float:left" align="left">
</div>



<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:10px; font-size:12px; float:left" align="left">
	<div id="centro" style="width:355px; height:15px; border:0px solid #000; font-size:12px; float:left" align="center">
	<i><u>&#160;&#160;&#160;&#160;&#160;&#160; <?php echo $dia . " de " . $meses [$mes-1] . " de " . $ano; ?> &#160;&#160;&#160;&#160;&#160;&#160;</u></i>
	</div>

	<div id="centro" style="width:355px; height:15px; border:0px solid #000; font-size:12px; float:left" align="center">
	_________________________________________
	</div>
</div>




<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:10px; font-size:10px; float:left" align="left">
	<div id="centro" style="width:355px; height:15px; border:0px solid #000; font-size:10px; float:left" align="center">
	Data
	</div>

	<div id="centro" style="width:355px; height:15px; border:0px solid #000; font-size:10px; float:left" align="center">
	Assinatura
	</div>
</div>





<div id="centro" style="width:720px; height:24px; border:0px solid #000; margin-left:10px; font-size:11px; float:left" align="left">
<?php
if ($desconto_quantidade_2 > 0)
{echo "
* Acerto de Quantidade: Quantidade original: $quantidade_original - Valor original: R$ $valor_total_original - Motivo: $motivo_alteracao_quant - Desconto: $desconto_quantidade $unidade_print (R$ $desc_em_valor_print)
";}
else
{}
?>
</div>


<div id="centro" style="width:720px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<hr style="border:1px solid #999" />
</div>




<!-- =============================================================================================== -->
<div id="centro" style="width:720px; height:27px; border:0px solid #000; margin-left:10px; font-size:17px; float:left" align="center">
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="left">
&copy; SUIF | GRANCAF&Eacute;
</div>
<div id="centro" style="width:250px; height:25px; border:0px solid #000; font-size:9px; float:left" align="center">
<?php echo"$usuario_print" ?> ( <?php echo"$filial_print" ?> )
</div>
<div id="centro" style="width:230px; height:25px; border:0px solid #000; font-size:9px; float:left" align="right">
VIA FINANCEIRO
</div>
</div>
<!-- =============================================================================================== -->


<div id="centro" style="width:720px; height:30px; border:0px solid #000; margin-left:5px; font-size:17px; float:left" align="center"></div>






<!-- =============================================================================================== -->
</div>
</div>
<!-- =============================================================================================== -->


<?php
$busca_favorecidos_ch = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
$linha_favorecidos_ch = mysqli_num_rows ($busca_favorecidos_ch);


for ($c=1 ; $c<=$linha_favorecidos_ch ; $c++)
{
	$aux_cheque = mysqli_fetch_row($busca_favorecidos_ch);

// DADOS DO FAVORECIDO CHEQUE =========================
	$data_cheque = date('Y-m-d', strtotime($aux_cheque[4]));
	$data_cheque_print = date('d/m/Y', strtotime($aux_cheque[4]));
	$obs_pgto = $aux_cheque[7];
	$banco_cheque = $aux_cheque[6];
	$valor_cheque = $aux_cheque[5];
	$valor_cheque_print = number_format($aux_cheque[5],2,",",".");
	$codigo_compra = $aux_cheque[1];
	$cod_compra = $cod_compra . " " . $codigo_compra;


	$busca_favorecido_ch = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_cheque[2]' ORDER BY nome");
	$aux_fch = mysqli_fetch_row($busca_favorecido_ch);
	$codigo_pessoa_ch = $aux_fch[1];
	
	$busca_pessoa_ch = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_ch' ORDER BY nome");
	$aux_pessoa_ch = mysqli_fetch_row($busca_pessoa_ch);
	$nome_favorecido_ch = $aux_pessoa_ch[1];
	$tipo_pessoa_ch = $aux_pessoa_ch[2];
		if ($tipo_pessoa_ch == "PF" or $tipo_pessoa_ch == "pf")
		{$cpf_cnpj_ch = $aux_pessoa_ch[3];}
		else
		{$cpf_cnpj_ch = $aux_pessoa_ch[4];}
		
// DADOS BANCARIOS =========================
	if ($banco_cheque == "BANCO DO BRASIL")
	{$banco_ch = "Banco do Brasil (001)"; $ag_ch = "3431-2"; $conta_ch = "34.419-2"; $img_ch = "b_brasil";}
	elseif ($banco_cheque == "BANESTES")
	{
		if ($filial_print == "LINHARES")
		{$banco_ch = "Banestes (021)"; $ag_ch = "0124"; $conta_ch = "11.910.387"; $img_ch = "b_banestes";}
		else
		{$banco_ch = "Banestes (021)"; $ag_ch = "0176"; $conta_ch = "19.830.348"; $img_ch = "b_banestes";}
	}
	elseif ($banco_cheque == "SICOOB")
	{
		if ($filial_print == "LINHARES")
		{$banco_ch = "Sicoob (756)"; $ag_ch = "3007"; $conta_ch = "106.997-7"; $img_ch = "b_sicoob";}
		else
		{$banco_ch = "Sicoob (756)"; $ag_ch = "3007"; $conta_ch = "39.872-1"; $img_ch = "b_sicoob";}
	}
	else
	{$banco_ch = ""; $ag_ch = ""; $conta_ch = "";}

// RELATORIO COPIA DE CHEQUE =========================

	if ($aux_cheque[3] == "CHEQUE" and $data_cheque == $hoje)
	{
	echo "
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!--                                      COPIA DE CHEQUE                                            -->

<!-- =============================================================================================== -->
<div id='centro' style='width:745px; height:540px; border:1px solid #FFF; float:left'> <!-- INICIO DIV 587 -->
<!-- =============================================================================================== -->

<div id='centro' style='width:720px; height:30px; border:0px solid #000; margin-left:25px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:720px; border:1px solid #000; margin-left:25px; border-radius:20px; float:left'>

<div id='centro' style='width:700px; height:50px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:700px; height:30px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'>
	<div id='centro' style='width:340px; height:30px; border:0px solid #000; font-size:18px; float:left' align='left'>
	<b>C&oacute;pia de Cheque</b>
	</div>

	<div id='centro' style='width:300px; height:30px; border:0px solid #000; font-size:18px; float:right; margin-right:20px' align='right'>
	 N&ordm; $aux_cheque[18]
	</div>
</div>

<div id='centro' style='width:700px; height:50px; border:0px solid #000; margin-left:20px; font-size:17px; float:left' align='center'></div>


<div id='centro' style='width:700px; border:1px solid #000; margin-left:10px; float:left'> <!-- INICIO DIV 600 -->

<div id='centro' style='width:690px; height:15px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:30px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:180px; height:30px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Banco: $banco_ch
	</div>

	<div id='centro' style='width:100px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	 Ag&ecirc;ncia: $ag_ch
	</div>

	<div id='centro' style='width:120px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	Conta: $conta_ch
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	N&ordm; do cheque: $aux_cheque[18]
	</div>
	
	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:right; margin-right:10px' align='right'>
	<b>Valor: R$ $valor_cheque_print</b>
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:42px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:40px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Valor:
	</div>

	<div id='centro' style='width:15px; height:40px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:600px; height:40px; border:0px solid #000; font-size:14px; float:left' align='left'>";
	echo Helpers::valorPorExtenso($valor_cheque);
	
	echo "
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:18px; border:0px solid #000; font-size:11px; float:left; margin-top:3px' align='left'>
	Favorecido:
	</div>

	<div id='centro' style='width:15px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:590px; height:18px; border:0px solid #000; font-size:14px; float:left' align='left'>
	$nome_favorecido_ch
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:70px; height:18px; border:0px solid #000; font-size:11px; float:left; margin-top:3px' align='left'>
	CPF/CNPJ:
	</div>

	<div id='centro' style='width:15px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:590px; height:18px; border:0px solid #000; font-size:14px; float:left' align='left'>
	$cpf_cnpj_ch
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:690px; height:110px; border:0px solid #000; margin-left:10px; float:left' align='center'>
	<div id='centro' style='width:190px; height:108px; border:0px solid #000; font-size:9px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/$img_ch.png' height='50' />
	</div>

	<div id='centro' style='width:115px; height:108px; border:0px solid #000; font-size:11px; float:left' align='left'>
	</div>

	<div id='centro' style='width:380px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>";
	
	$dia_data = date ("d", time($aux_cheque[4]));
	$mes_data = date ("m", time($aux_cheque[4]));
	$ano_data = date ("Y", time($aux_cheque[4]));

	
	echo "Linhares, " . $dia_data . " de " . $meses [$mes_data-1] . " de " . $ano_data;
	
	echo "
	</div>

	<div id='centro' style='width:380px; height:35px; border:0px solid #000; font-size:11px; float:left' align='center'>

	</div>

	<div id='centro' style='width:380px; height:20px; border:0px solid #000; font-size:11px; float:left' align='center'>
	___________________________________________
	</div>

	<div id='centro' style='width:380px; height:15px; border:0px solid #000; font-size:11px; float:left' align='center'>
	GRANCAF&Eacute; COM. IMP. E EXP. DE CAF&Eacute; LTDA
	</div>
	<div id='centro' style='width:380px; height:15px; border:0px solid #000; font-size:11px; float:left' align='center'>
	CNPJ: 02.239.346/0001-72
	</div>
</div>

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

</div> <!-- FIM DIV 600 -->

<div id='centro' style='width:690px; height:20px; border:0px solid #000; margin-left:10px; font-size:17px; float:left' align='center'></div>

<div id='centro' style='width:700px; height:30px; border:0px solid #000; margin-left:20px; float:left' align='center'>
	<div id='centro' style='width:240px; height:30px; border:0px solid #000; font-size:11px; float:left' align='left'>
	Confirma&ccedil;&atilde;o de Compra: $numero_compra
	</div>

	<div id='centro' style='width:40px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>

	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>
	
	<div id='centro' style='width:135px; height:30px; border:0px solid #000; font-size:14px; float:right' align='right'>
	<!-- xxxxxxxxxxxxxxxxxxxxx -->
	</div>
</div>
</div>
<!-- =============================================================================================== -->
</div> <!-- FIM DIV 587 -->
<!-- =============================================================================================== -->


<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
<!-- =============================================================================================== -->
";
	
	}
	else
	{

	}



}

?>






</div>
</body>
</html>
<!-- ==================================================   FIM   ================================================= -->