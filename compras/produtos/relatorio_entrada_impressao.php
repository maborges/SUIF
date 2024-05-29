<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	include("../../helpers.php");
	$pagina = 'relatorio_entrada_impressao';
	$titulo = 'Relat&oacute;rio de Entrada';
	$modulo = 'compras';
	$menu = 'produtos';


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$cod_produto = $_POST["cod_produto"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$produto_print_principal = $aux_bp_geral[1];
$unidade_print_principal = $aux_bp_geral[26];
// =======================================================================================================
	

// GERAL  ==========================================================================================
if ($monstra_situacao == "todas")
{	
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA') AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA') AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");
}



// ENTRADA  ==========================================================================================
elseif ($monstra_situacao == "entrada")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");
}



// TRANSFERECIA ENTRADA  ==========================================================================================
elseif ($monstra_situacao == "transferencia")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='TRANSFERENCIA_ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='TRANSFERENCIA_ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");
}



else
{}



$soma_bruta = $soma_entrada[0] + $soma_descontos[0];
$soma_bruta_print = number_format($soma_bruta,2,",",".");



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

<div id="centro" style="width:745px; border:0px solid #F00">

<?php
// ##############################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecer� em cada p�gina de impress�o #######
// #######           � importante sempre testar antes para ver quantas linhas s�o necess�rias             #######
// ############################################################################################################## 
$limite_registros = 44;
$numero_paginas = ceil($linha_compra / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "<div id='centro' style='width:740px; height:1050px; border:0px solid #000; page-break-after:always'>";
	




echo "
<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:60px; border:0px solid #D85; float:left; margin-top:25px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RELAT&Oacute;RIO DE ENTRADA - FICHA PRODUTOR<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$produto_print_principal</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:680px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	if ($monstra_situacao == "")
	{echo "<i>Per&iacute;odo: <b>GERAL</b></i>";}
	else
	{echo "<i>Per&iacute;odo: <b>$data_inicial_aux</b> at&eacute; <b>$data_final_aux</b></i>";}
	
	echo "
	
	</div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Registro</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Registros</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

	<div id='centro' style='width:55px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Data</div>
	
	<div id='centro' style='width:220px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produtor</div>
	
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	N&ordm;</div>
	
	<div id='centro' style='width:105px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produto</div>
	
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Quantidade</div>
	
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Desconto</div>
	
	<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Movimenta&ccedil;&atilde;o</div>
	
</div>";






for ($x=1 ; $x<=$limite_registros ; $x++)
{
	$aux_compra = mysqli_fetch_row($busca_compra);

// DADOS DA COMPRA =========================
$numero_compra = $aux_compra[1];
$num_compra_print = $aux_compra[1];
$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $cod_tipo[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];

$des = $aux_compra[29];
if ($des > 0)
{$desconto = number_format($aux_compra[29],2,",",".");
$desconto_print = $desconto . " " . $unidade_print;}
else
{$desconto_print = "";}

$quant_bruta = $quantidade + $des;
$quant_bruta_print = number_format($quant_bruta,2,",",".");

if ($aux_compra[16] == "TRANSFERENCIA_ENTRADA")
{$movimentacao = "TRANSF.";}
elseif ($aux_compra[16] == "ENTRADA")
{$movimentacao = "ENTRADA";}
else
{}



// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


	if ($aux_compra[0] == "")
	{
	echo "
	<div id='centro' style='width:675px; height:10px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>

		<div id='centro' style='width:55px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
		&#160;&#160;$data_compra_print</div>
		
		<div id='centro' style='width:220px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		&#160;&#160;$fornecedor_print</div>
		
		<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$num_compra_print</div>
		
		<div id='centro' style='width:105px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$produto_print</div>
		
		<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$quantidade_print $unidade_print</div>
		
		<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$desconto_print</div>
		
		<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$movimentacao</div>
		
	</div>";
	}

// =====================================
}



// =============================
$x = ($x + $limite_registros);
// =============================




if ($linha_compra == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='785px' height='15px' align='left'>&#160;&#160;<i>Nenhum registro encontrado.</i></td></tr>";}






echo "


</div>

<div id='centro' style='width:720px; height:10px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:100px; border:0px solid #000; margin-left:10px; float:left; border-radius:7px;' align='center'>
	<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-top:5px; float:left; font-size:11px;' align='right'><b><i><u>TOTAL DE ENTRADA</u></i></b></div>";


	
	echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>$produto_print_principal</div>";
	
	echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'><b>$soma_entrada_print</b> $unidade_print_principal</div>
</div>

<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:24px; border:0px solid #f85; float:left; margin-left:10px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date('Y');
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia";
	
	echo"
	</div>
	<div id='centro' style='width:430px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'>$filial</div>

	<div id='centro' style='width:100px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
	P&aacute;gina $x_principal/$numero_paginas</div>
</div>
<!-- =============================================================================================== -->

<!-- ####################################################################### -->";

echo "</div>"; // quebra de p�gina
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->