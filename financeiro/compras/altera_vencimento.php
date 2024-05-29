<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'altera_vencimento';
$titulo = 'Altera Data de Pagamento';
$modulo = 'financeiro';
$menu = 'contas_pagar';

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
<?php include ('../../includes/menu_financeiro.php'); ?>

<?php include ('../../includes/sub_menu_financeiro_contas_pagar.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:450px; width:930px; border:0px solid #000; margin:auto">

<?php
// =================================================================================================================
	$filial = $filial_usuario;
	$cod_compra = $_POST["cod_compra"];
	$data_pagamento = Helpers::ConverteData($_POST["data_pagamento"]);
	$pagina_mae = $_POST["pagina_mae"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$monstra_situacao = $_POST["monstra_situacao"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// =============================================================================================================
// =============================================================================================================
if ($botao == "alterar_pgto" and $data_pagamento != "" and $data_pagamento != "0000-00-00")
{
$alterar_vencimento = mysqli_query ($conexao, "UPDATE compras SET data_pagamento='$data_pagamento' WHERE codigo='$cod_compra'");
}

else
{}










// =============================================================================================================
// =============================================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$data_pgto = $aux_compra[14];
$data_pgto_print = date('d/m/Y', strtotime($aux_compra[14]));
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
$numero_romaneio = $aux_compra[28];



// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
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
	{$unidade_print = "Caixa";}
	elseif ($unidade == "UN")
	{$unidade_print = "Unidade";}
	else
	{$unidade_print = "-";}

// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
}
// =================================================================================================================




		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>";

		if ($botao == "alterar_pgto")
		{echo "
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Data de pagamento alterada com sucesso!</div>";}
		else
		{echo "
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Altera&ccedil;&atilde;o da data de pagamento</div>";}

		echo "
		<div id='centro' style='float:left; height:130px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:11px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:11px; line-height:18px'>
			N&ordm; $numero_compra</br>
			Fornecedor: $fornecedor_print</br>
			Produto: $produto_print</br>
			Valor: R$ $valor_total_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:11px'>
			<form action='$servidor/$diretorio_servidor/financeiro/compras/altera_vencimento.php' method='post'>
			<input type='text' name='data_pagamento' maxlength='10' onkeypress='mascara(this,data)' id='calendario' style='color:#0000FF; width:90px; text-align:center' value='$data_pgto_print' />

		</div>		
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<input type='hidden' name='cod_compra' value='$cod_compra'>
			<input type='hidden' name='botao' value='alterar_pgto'>
			<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_confirmar_2.png' border='0' /></form>
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