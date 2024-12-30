<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include_once("../../helpers.php");

$pagina = 'contrato_tratado_relatorio_impressao';
$menu = 'contratos';
$titulo = 'Relat&oacute;rio - Contratos Tratados';
$modulo = 'compras';

// ====== RECEBE POST ===========================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$codigo_contrato = $_POST["codigo_contrato"];
$numero_contrato = $_POST["numero_contrato"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$botao = $_POST["botao"];
$monstra_situacao = $_POST["monstra_situacao"];

$produtor = $_POST["produtor"];
$produto = $_POST["produto"];
$produto_list = $_POST["produto"];
$data_contrato = $_POST["data_contrato"];
$quantidade_adquirida = $_POST["quantidade_adquirida"];
$quantidade_a_entregar = $_POST["quantidade_a_entregar"];
$unidade = $_POST["unidade"];
$tipo = $_POST["tipo"];
$obs = $_POST["obs"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
// ============================================================================================================================


// ====== CRIA MENSAGEM ===================================================================================
if ($monstra_situacao == "todos")
{$situacao_print = "Todos os Contratos";}
elseif ($monstra_situacao == "aberto")
{$situacao_print = "Contratos em Aberto";}
elseif ($monstra_situacao == "pagos")
{$situacao_print = "Contratos Liquidados";}
else
{$situacao_print = "Todos os Contratos";}
// ========================================================================================================


// ======== BUSCA CONTRATOS ====================================================================================================
if ($monstra_situacao == "todos")
{	
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

elseif ($monstra_situacao == "aberto")
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='EM_ABERTO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

elseif ($monstra_situacao == "pagos")
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='PAGO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

else
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}
// ================================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ================================================================================================================================


// ================================================================================================================================
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
$numero_paginas = ceil($linha_cont_tratado / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "<div id='centro' style='width:740px; height:1050px; border:0px solid #000; page-break-after:always'>";
	




echo "
<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:10px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RELAT&Oacute;RIO DE CONTRATOS TRATADOS<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$situacao_print</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:680px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	if ($monstra_situacao == "" or $monstra_situacao == "produtor")
	{echo "<i>Per&iacute;odo: <b>GERAL</b></i>";}
	else
	{echo "<i>Per&iacute;odo: <b>$data_inicial_aux</b> at&eacute; <b>$data_final_aux</b></i>";}
	
	echo "
	
	</div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_cont_tratado == 1)
	{echo"<i><b>$linha_cont_tratado</b> Contrato</i>";}
	elseif ($linha_cont_tratado == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_cont_tratado</b> Contratos</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>
	<div id='centro' style='width:65px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Data</div>
	<div id='centro' style='width:225px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produtor</div>
	<div id='centro' style='width:50px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	N&ordm;</div>
	<div id='centro' style='width:65px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Data Entrega</div>
	<div id='centro' style='width:70px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produto</div>
	<div id='centro' style='width:90px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Quant. Entregar</div>
	<div id='centro' style='width:90px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Pre&ccedil;o Tratado</div>
</div>";





echo "<!-- <table border='0' align='center' style='color:#000; font-size:9px'> -->";


for ($x=1 ; $x<=$limite_registros ; $x++)
{
	$aux_contrato = mysqli_fetch_row($busca_cont_tratado);

// DADOS DO CONTRATO =========================
	$produtor = $aux_contrato[1];
	$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
	$produto = $aux_contrato[5];
	$quantidade = $aux_contrato[6];
	$quantidade_total = number_format($aux_contrato[23],2,",",".");
	$valor_total = number_format($aux_contrato[22],2,",",".");
	$valor_un = number_format($aux_contrato[9],2,",",".");
	$unidade = $aux_contrato[21];
	$descricao_produto = $aux_contrato[8];
	$data_entrega_i = date('d/m/Y', strtotime($aux_contrato[3]));
	$data_entrega_f = date('d/m/Y', strtotime($aux_contrato[4]));
	$fiador_1 = $aux_contrato[12];
	$fiador_2 = $aux_contrato[13];
	$safra = $aux_contrato[10];
	$prazo_pgto = $aux_contrato[11];
	$observacao = $aux_contrato[14];
	$estado_registro = $aux_contrato[15];
	$quantidade_fracao = $aux_contrato[7];
	$situacao_contrato = $aux_contrato[16];
	$numero_contrato = $aux_contrato[20];
	$usuario_cadastro = $aux_contrato[24];
	$hora_cadastro = $aux_contrato[26];
	$data_cadastro = $aux_contrato[25];
//	$filial = $aux_contrato[24];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
//$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE apelido='$produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
//$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$produtor' AND estado_registro!='EXCLUIDO'");
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


	if ($aux_contrato[0] == "")
	{
	echo "
	<div id='centro' style='width:675px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:675px; border:0px solid #000; margin-top:1px; float:left'>
		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
		&#160;&#160;$data_contrato_print</div>
		<div id='centro' style='width:225px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		&#160;&#160;$fornecedor_print</div>
		<div id='centro' style='width:50px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$numero_contrato</div>
		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$data_entrega_i</div>
		<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:8px; text-align:center; background-color:#FFF'>
		$produto_print_2</div>
		<div id='centro' style='width:90px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$quantidade_total $unidade</div>
		<div id='centro' style='width:90px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:right; background-color:#FFF'>
		<div style='margin-right:4px'>R$ $valor_un</div></div>
	</div>";
	}

// =====================================
}



// =============================
$x = ($x + $limite_registros);
// =============================




if ($linha_cont_tratado == 0)
{echo "<tr style='color:#F00; font-size:11px'>
<td width='785px' height='15px' align='left'>&#160;&#160;<i>Nenhum contrato encontrado.</i></td></tr>";}






echo "
<!-- </table> -->


</div>

<div id='centro' style='width:720px; height:15px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:100px; border:0px solid #000; margin-left:10px; float:left; border-radius:7px;' align='center'>
	<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-top:5px; float:left; font-size:11px;' align='right'><b><i><u>Total de Contratos Tratados</u></i></b></div>";

// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================

for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	if ($monstra_situacao == "todos")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}

	elseif ($monstra_situacao == "aberto")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}
	
	elseif ($monstra_situacao == "pagos")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='PAGO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}

	elseif ($monstra_situacao == "produtor")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}
	
	else
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='$aux_bp_geral[20]'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}


	if ($soma_tratados[0] == 0)
	{}
	else
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
	$aux_bp_geral[22]: <b>$soma_tratados_print</b> $aux_bp_geral[26]</div>";}
}

echo "
</div>


<div id='centro' style='width:720px; height:10px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>
<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date('Y');
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia";
	
	echo"
	</div>
	<div id='centro' style='width:430px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>$filial</div>

	<div id='centro' style='width:100px; height:15px; border:0px solid #000; font-size:9px; float:left' align='right'>
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