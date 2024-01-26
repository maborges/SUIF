<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_saldo_impressao";
$titulo = "Relat&oacute;rio de Saldo dos Produtores (Armazenado)";
$modulo = "compras";
$menu = "produtos";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$filial = $filial_usuario;
$cod_produto = $_POST["cod_produto"];	
$botao = $_POST["botao"];
$ordenar_busca = $_POST["ordenar_busca"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "geral";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}

if ($ordenar_busca == "PRODUTOR")
{$mysql_ordenar_busca = "fornecedor_print";}
elseif ($ordenar_busca == "SALDO_MAIOR")
{$mysql_ordenar_busca = "saldo DESC";}
elseif ($ordenar_busca == "SALDO_MENOR")
{$mysql_ordenar_busca = "saldo ASC";}
else
{$mysql_ordenar_busca = "fornecedor_print";}
// ================================================================================================================


// ====== BUSCA SALDO ARMAZENADO =================================================================================
$busca_saldo = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE filial='$filial' AND cod_produto='$cod_produto' ORDER BY $mysql_ordenar_busca");
$linhas_saldo = mysqli_num_rows ($busca_saldo);
// ================================================================================================================


// ====== BUSCA PRODUTO PRINCIPAL ===================================================================================
$busca_produto_principal = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp_principal = mysqli_fetch_row($busca_produto_principal);

$produto_print_principal = $aux_bp_principal[1];
// ===========================================================================================================


// =======================================================================================================
	include ('../../includes/head_impressao.php');
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
<body onLoad="imprimir()">

<div id="centro" style="width:745px; border:0px solid #F00">

<?php
echo "<div id='centro' style='width:740px; height:auto; border:0px solid #000'>";


echo "
<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:11px; float:left' align='center'>
	RELAT&Oacute;RIO DE SALDO DE ARMAZENADO (SALDO DOS PRODUTORES)<br /></div>

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

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left'></div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>
	</div>";


echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='100px' align='center' bgcolor='#666'>C&oacute;digo</td>
<td width='485px' align='center' bgcolor='#666'>Produtor</td>
<td width='200px' align='center' bgcolor='#666'>Tipo</td>
<td width='200px' align='center' bgcolor='#666'>Saldo</td>
</tr>
</table>";


echo "<table border='0' align='center' style='color:#000; font-size:11px'>";


// =================================================================================================================
for ($w=1 ; $w<=$linhas_saldo ; $w++)
{
	$aux_saldo = mysqli_fetch_row($busca_saldo);

	$cod_fornecedor = $aux_saldo[1];
	$fornecedor_print = $aux_saldo[2];
	$cod_tipo = $aux_saldo[6];
	$tipo_print = $aux_saldo[7];
	$unidade_print = $aux_saldo[8];
	$saldo = $aux_saldo[9];
	$saldo_print = number_format($saldo,2,",",".");
	
	if ($cod_produto == 4 or $cod_produto == 9)
	{$tipo_print_2 = $tipo_print;}
	else
	{$tipo_print_2 ="";}



// RELATORIO ======================================================================================================
	if ($saldo > 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'credores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_credor = $soma_credor + $saldo;
	echo "
	<tr style='color:#000'>
	<td width='100px' height='15px' align='left'><div style='margin-left:10px'>$cod_fornecedor</div></td>
	<td width='485px' height='15px' align='left'><div style='margin-left:10px'>$fornecedor_print</div></td>
	<td width='200px' height='15px' align='center'>$tipo_print_2</td>
	<td width='200px' height='15px' align='right'><div style='margin-right:10px'>$saldo_print $unidade_print</div></td>
	</tr>";}

	elseif ($saldo < 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_devedor = $soma_devedor + $saldo;
	echo "
	<tr style='color:#000'>
	<td width='100px' height='15px' align='left'><div style='margin-left:10px'>$cod_fornecedor</div></td>
	<td width='485px' height='15px' align='left'><div style='margin-left:10px'>$fornecedor_print</div></td>
	<td width='200px' height='15px' align='center'>$tipo_print_2</td>
	<td width='200px' height='15px' align='right'><div style='margin-right:10px'>$saldo_print $unidade_print</div></td>
	</tr>";}

	else
	{}



}

$soma_credor_print = number_format($soma_credor,2,",",".");
$soma_devedor_print = number_format($soma_devedor,2,",",".");




echo "
</table>


</div>

<div id='centro' style='width:720px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'></div>

<div id='centro' style='width:100px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'></div>
<div id='centro' style='width:570px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'>
<font style='color:#000'>TOTAL PRODUTORES: </font><b style='color:#000'>$conta_produtor</b>
</div>

<div id='centro' style='width:100px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'></div>
<div id='centro' style='width:570px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'>";
if ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores')
{echo "<font style='color:#000'>TOTAL DEVEDORES: </font><b style='color:#000'>$soma_devedor_print $unidade_print</b>";}
else
{echo "<font style='color:#000'>TOTAL CREDORES: </font><b style='color:#000'>$soma_credor_print $unidade_print</b></div>";}
echo "</div>

<div id='centro' style='width:100px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'></div>
<div id='centro' style='width:570px; height:18px; border:0px solid #000; margin-left:10px; float:left; font-size:11px' align='left'>";
if ($monstra_situacao == 'geral')
{echo "<font style='color:#000'>TOTAL CREDORES: </font><b style='color:#000'>$soma_credor_print $unidade_print</b></div>";}
else
{echo "";}
echo "</div>";

// ##############################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             #######
// ############################################################################################################## 

$limite_registros = 46;
$numero_paginas = ceil($conta_produtor / $limite_registros);
$quebra_pag = $limite_registros * $numero_paginas;

// ############################################################################################################## 

if ($quebra_pag = $conta_produtor)
{$ultima_pagina = $conta_produtor - (($numero_paginas + 1) * $limite_registros);
$reg_rest = $limite_registros - $ultima_pagina;
$reg_restante = $reg_rest - 6;}

elseif ($quebra_pag > $conta_produtor)
{$ultima_pagina = $conta_produtor - (($numero_paginas - 1) * $limite_registros);
$reg_rest = $limite_registros - $ultima_pagina;
$reg_restante = $reg_rest - 6;}

else
{}

while ($reg_restante = 0)
{
echo "<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>$reg_restante</div>";
$reg_restante--;
}





echo "
<div id='centro' style='width:720px; height:10px; border:0px solid #000; margin-left:10px; float:left' align='center'></div>

<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #f85; float:left; margin-left:10px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date(Y);
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia</div>";

	echo"
	<div id='centro' style='width:420px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'>$filial</div>

	<div id='centro' style='width:100px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>";
		if ($numero_paginas <= 1)
		{echo "$numero_paginas P&aacute;gina";}
		else
		{echo "$numero_paginas P&aacute;ginas";}
	echo"
	</div>
</div>
<!-- =============================================================================================== -->

<!-- ####################################################################### -->";

echo "</div>"; // quebra de página
//} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->