<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	include("../../helpers.php");
	$pagina = 'relatorio_data_impressao';
	$titulo = 'Relat&oacute;rio de Compras';
	$modulo = 'compras';
	$menu = 'produtos';


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
// =======================================================================================================


// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_inicial' AND movimentacao='COMPRA' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_inicial' AND movimentacao='COMPRA' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================




// ===========================================================================================================
	
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

<div id='centro' style='width:740px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RELAT&Oacute;RIO DE COMPRAS<br /></div>

	<div id='centro' style='width:124px; height:38px; border:0px solid #000; font-size:9px; float:right' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>POR DATA</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	echo "<i>Data: <b>$data_inicial_aux</b></i>";
	
	echo "
	
	</div>
	<div id='centro' style='width:350px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; float:left'>

	<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Data</div>
	
	<div id='centro' style='width:230px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produtor</div>
	
	<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	N&ordm; Compra</div>
	
	<div id='centro' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produto</div>
	
	<div id='centro' style='width:95px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Quantidade</div>
	
	<div id='centro' style='width:75px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Pre&ccedil;o Un</div>
	
	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Valor Total</div>
	
</div>";






for ($x=1 ; $x<=$limite_registros ; $x++)
{
	$aux_compra = mysqli_fetch_row($busca_compra);

// DADOS DA COMPRA =========================
$numero_compra = $aux_compra[1];
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
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$usuario_alteracao = $aux_compra[21];
if ($aux_compra[23] == "")
{$data_alteracao = "";}
else
{$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));}
$hora_alteracao = $aux_compra[22];


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
	<div id='centro' style='width:740px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; float:left'>

		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
		&#160;&#160;$data_compra_print</div>
		
		<div id='centro' style='width:230px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		&#160;&#160;$fornecedor_print</div>
		
		<div id='centro' style='width:60px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$numero_compra</div>
		
		<div id='centro' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$produto_print</div>
		
		<div id='centro' style='width:95px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$quantidade_print $unidade_print</div>
		
		<div id='centro' style='width:75px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		$preco_unitario_print&#160;&#160;</div>
		
		<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		$valor_total_print&#160;&#160;</div>
		
	</div>";
	}

// =====================================
}



// =============================
$x = ($x + $limite_registros);
// =============================




if ($linha_compra == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='740px' height='15px' align='left'>&#160;&#160;<i>Nenhuma compra encontrada.</i></td></tr>";}






echo "


</div>

<div id='centro' style='width:740px; height:15px; border:0px solid #000; margin-left:40px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:100px; border:0px solid #000; margin-left:40px; float:left; border-radius:7px;' align='center'>

	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b><u>TOTAL DE COMPRAS:</u></b>	
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<b><u>R$ $soma_compras_print</u></b>
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<!-- ------------ -->
		</div>
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#000000'>
		<!-- ------------ -->
		</div>
	</div>";


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================



for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_inicial' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_inicial' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}

	if ($soma_compra_produto[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:11px; color:#000000'>
		<b>$aux_bp_geral[22]</b>	
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Quant. comprada: $quant_produto_print $aux_bp_geral[26]
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Valor total: R$ $soma_cp_print
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Pre&ccedil;o m&eacute;dio: R$ $media_produto_print / $aux_bp_geral[26]
		</div>
	</div>
	";}


}



echo "
</div>


<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:40px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
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