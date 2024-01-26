<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorio_ranking_impressao';
	$titulo = 'Ranking de Compras';
	$modulo = 'compras';
	$menu = 'relatorio';


// ====== CONVERTE DATA ================================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ======================================================================================================


// ====== CONVERTE VALOR =================================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =======================================================================================================


// ====== DADOS PARA BUSCA =================================================================================
$ordem = $_POST["ordem"];
$produto_print = $_POST["produto_print"];
$unidade_print = $_POST["unidade_print"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$cidade_busca = $_POST["cidade_busca"];

if ($cidade_busca == "" or $cidade_busca == "TODAS")
	{$mysql_filtro_cidade = "cidade IS NOT NULL";
	$cidade_busca = "TODAS";}
else
	{$mysql_filtro_cidade = "cidade='$cidade_busca'";
	$cidade_busca = $_POST["cidade_busca"];}
// =======================================================================================================


// ====== BUSCA TABELA RANKING  =======================================================================
if ($ordem == "QUANT")
{
$busca_ranking = mysqli_query ($conexao, "SELECT * FROM ranking_compras WHERE $mysql_filtro_cidade ORDER BY quantidade DESC");
$linhas_ranking = mysqli_num_rows ($busca_ranking);
}
else
{
$busca_ranking = mysqli_query ($conexao, "SELECT * FROM ranking_compras WHERE $mysql_filtro_cidade ORDER BY fornecedor_print");
$linhas_ranking = mysqli_num_rows ($busca_ranking);
}

// =================================================================================================================
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM ranking_compras WHERE $mysql_filtro_cidade"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM ranking_compras WHERE $mysql_filtro_cidade"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compras[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}
// =================================================================================================================




// ===========================================================================================================
	
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
// ##############################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             #######
// ############################################################################################################## 
$limite_registros = 44;
$numero_paginas = ceil($linhas_ranking / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "<div id='centro' style='width:740px; height:1050px; border:0px solid #000; page-break-after:always'>";
	




echo "
<!-- ####################################################################### -->

<div id='centro' style='width:740px; height:62px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RANKING DE COMPRAS<br /></div>

	<div id='centro' style='width:124px; height:38px; border:0px solid #000; font-size:9px; float:right' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$produto_print</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	echo "<i>Per&iacute;odo: <b>$data_inicial</b> at&eacute; <b>$data_final</b></i>";
	
	echo "
	
	</div>
	<div id='centro' style='width:350px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linhas_ranking == 1)
	{echo"<i><b>$linhas_ranking</b> Fornecedor</i>";}
	elseif ($linhas_ranking == 0)
	{echo"";}
	else
	{echo"<i><b>$linhas_ranking</b> Fornecedores</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; margin-left:20px; float:left'>

	<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Classifica&ccedil;&atilde;o</div>
	
	<div id='centro' style='width:260px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Fornecedor</div>
	
	<div id='centro' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produto</div>
	
	<div id='centro' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Quantidade</div>
	
	<div id='centro' style='width:130px; height:15px; border:1px solid #FFF; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Valor Total</div>
	
</div>";






for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_ranking = mysqli_fetch_row($busca_ranking);

$forne_print = $aux_ranking[2];
$quant_print = number_format($aux_ranking[5],2,",",".");
$un_print = $aux_ranking[6];
$valor_t_print = number_format($aux_ranking[7],2,",",".");
$prod_print = $aux_ranking[4];

$classificacao = $classificacao + 1;

	if ($aux_ranking[0] == "")
	{
	echo "
	<div id='centro' style='width:740px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:740px; border:0px solid #000; margin-top:1px; margin-left:20px; float:left'>

		<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		<div style='margin-left:0px'>$classificacao &ordm;</div></div>
		
		<div id='centro' style='width:260px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		<div style='margin-left:10px'>$forne_print</div></div>
		
		<div id='centro' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		<div style='margin-left:0px'>$prod_print</div></div>
		
		<div id='right' style='width:120px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		<div style='margin-right:0px'>$quant_print $un_print</div></div>
		
		<div id='right' style='width:130px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		<div style='margin-right:10px'>R$ $valor_t_print</div></div>
		
	</div>";
	}

// =====================================
}



// =============================
$x = ($x + $limite_registros);
// =============================




if ($linhas_ranking == 0)
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


	if ($soma_compras[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='width:710px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
		<div id='centro' style='height:15px; width:10px; margin-left:0px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
		<div id='centro' style='height:15px; width:120px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:11px; color:#000000'>
		<b>$produto_print</b>	
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Quant. comprada: $quant_produto_print $unidade_print
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Valor total: R$ $soma_compras_print
		</div>
		
		<div id='centro' style='height:15px; width:190px; margin-left:0px; margin-top:3px; border:0px solid #999; float:left; text-align:left; 
		font-size:10px; color:#000000'>
		Pre&ccedil;o m&eacute;dio: R$ $media_produto_print / $unidade_print
		</div>
	</div>
	";}



echo "
</div>


<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:40px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:27px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:25px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date(Y);
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia";
	
	echo"
	</div>
	<div id='centro' style='width:430px; height:25px; border:0px solid #000; font-size:9px; float:left' align='center'>$filial</div>

	<div id='centro' style='width:100px; height:25px; border:0px solid #000; font-size:9px; float:left' align='right'>
	P&aacute;gina $x_principal/$numero_paginas</div>
</div>
<!-- =============================================================================================== -->

<!-- ####################################################################### -->";

echo "</div>"; // quebra de página
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->