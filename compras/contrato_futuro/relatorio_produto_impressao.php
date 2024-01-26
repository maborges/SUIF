<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorio_produto_impressao';
	$menu = 'contratos';
	$titulo = 'Relat&oacute;rio - Contratos Futuros';
	$modulo = 'compras';


// ====== CONVERTE DATA ============================================================================================	
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


// ======= CONVERTE VALOR ===========================================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================


// ====== RECEBE POST ===========================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$pagina_mae = $_POST["pagina_mae"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = ConverteData($_POST["data_final"]);
$botao = $_POST["botao"];
$monstra_situacao = $_POST["monstra_situacao"];
$produtor = $_POST["produtor"];

if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todos";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
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
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

if ($fornecedor == "" or $linhas_fornecedor == 0)
{$fornecedor_print = "(Necess&aacute;rio selecionar um fornecedor)";}
else
{$fornecedor_print = $aux_forn[1];}

$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


if ($cod_produto == "TODOS" or $linhas_bp == 0)
{
// ======== BUSCA CONTRATOS ====================================================================================================
	if ($monstra_situacao == "todos")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND vencimento>='$data_inicial' AND vencimento<='$data_final' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "aberto")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='EM_ABERTO' AND vencimento>='$data_inicial' AND vencimento<='$data_final' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "pagos")
	{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='PAGO' AND vencimento>='$data_inicial' AND vencimento<='$data_final' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "produtor")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='EM_ABERTO' AND produtor='$fornecedor' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	else
	{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' 
	AND filial='$filial' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}
// =======================================================================================================
}

else
{
// ======== BUSCA CONTRATOS ====================================================================================================
	if ($monstra_situacao == "todos")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND vencimento>='$data_inicial' AND vencimento<='$data_final' AND cod_produto='$cod_produto' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "aberto")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='EM_ABERTO' AND vencimento>='$data_inicial' AND vencimento<='$data_final' AND cod_produto='$cod_produto' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "pagos")
	{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='PAGO' AND vencimento>='$data_inicial' AND vencimento<='$data_final' AND cod_produto='$cod_produto' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	elseif ($monstra_situacao == "produtor")
	{	
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND filial='$filial' 
	AND situacao_contrato='EM_ABERTO' AND produtor='$fornecedor' AND cod_produto='$cod_produto' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}

	else
	{
	$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' 
	AND filial='$filial' AND cod_produto='$cod_produto' ORDER BY vencimento, nome_produtor");
	$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
	}
// =======================================================================================================
}


if ($cod_produto == "TODOS" or $linhas_bp == 0)
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}

else
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}

// ================================================================================================================================
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
$numero_paginas = ceil($linha_cont_futuro / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "<div id='centro' style='width:740px; height:1050px; border:0px solid #000; page-break-after:always'>";
	




echo "
<!-- ####################################################################### -->

<div id='centro' style='width:720px; height:62px; border:0px solid #D85; float:left; margin-top:10px; margin-left:10px; font-size:17px' align='center'>

	<div id='centro' style='width:180px; height:60px; border:0px solid #000; font-size:17px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='175px' /></div>

	<div id='centro' style='width:430px; height:38px; border:0px solid #000; font-size:12px; float:left' align='center'>
	RELAT&Oacute;RIO DE CONTRATOS FUTUROS<br /></div>

	<div id='centro' style='width:100px; height:38px; border:0px solid #000; font-size:9px; float:left' align='right'>";
	$data_atual = date('d/m/Y', time());
	$hora_atual = date('G:i:s', time());
	echo"$data_atual<br />$hora_atual</div>";

	echo "
	<div id='centro' style='width:430px; height:18px; border:0px solid #000; font-size:12px; float:left' align='center'><b>$produto_print</b></div>
	<div id='centro' style='width:100px; height:18px; border:0px solid #000; font-size:9px; float:left' align='right'></div>

</div>



<!-- =================================================================================================================== -->

<div id='centro' style='width:700px; border:0px solid #000; margin-top:1px; margin-left:40px; float:left'>

	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:left; font-size:10px'>";
	if ($monstra_situacao == "")
	{echo "<i>Per&iacute;odo: <b>GERAL</b></i>";}
	else
	{echo "<i>Per&iacute;odo: <b>$data_inicial_aux</b> at&eacute; <b>$data_final_aux</b></i>";}
	
	echo "
	
	</div>
	<div id='centro' style='width:320px; height:15px; border:0px solid #000; float:right; text-align:right; font-size:10px'>";
	if ($linha_cont_futuro == 1)
	{echo"<i><b>$linha_cont_futuro</b> Contrato</i>";}
	elseif ($linha_cont_futuro == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_cont_futuro</b> Contratos</i>";}
	echo "</div>";


echo "
<div id='centro' style='width:695px; border:0px solid #000; margin-top:1px; float:left'>
	<div id='centro' style='width:65px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Data</div>
	<div id='centro' style='width:205px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Fornecedor</div>
	<div id='centro' style='width:50px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	N&ordm;</div>
	<div id='centro' style='width:65px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Vencimento</div>
	<div id='centro' style='width:100px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Produto</div>
	<div id='centro' style='width:80px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Quant. Entregar</div>
	<div id='centro' style='width:40px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Un</div>
	<div id='centro' style='width:70px; height:15px; border:1px solid #000; float:left; color:#FFF; font-size:9px; text-align:center; background-color:#666'>
	Situa&ccedil;&atilde;o</div>
</div>";





echo "<!-- <table border='0' align='center' style='color:#000; font-size:9px'> -->";


for ($x=1 ; $x<=$limite_registros ; $x++)
{
	$aux_contrato = mysqli_fetch_row($busca_cont_futuro);

// DADOS DO CONTRATO =========================
	$produtor = $aux_contrato[1];
	$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
	$produto = $aux_contrato[3];
	$quantidade = $aux_contrato[4];
	$quantidade_adquirida = $aux_contrato[5];
	$unidade = $aux_contrato[6];
	$tipo = $aux_contrato[26];
	$descricao_produto = $aux_contrato[7];
	$vencimento_contrato_print = date('d/m/Y', strtotime($aux_contrato[8]));
	$fiador_1 = $aux_contrato[9];
	$fiador_2 = $aux_contrato[10];
	$observacao = $aux_contrato[11];
	$estado_registro = $aux_contrato[12];
	$quantidade_fracao = $aux_contrato[13];
	$porcentagem_juros = $aux_contrato[14];
	$situacao_contrato = $aux_contrato[15];
	$quantidade_a_entregar = $aux_contrato[16];
	$numero_contrato = $aux_contrato[17];
	$usuario_cadastro = $aux_contrato[18];
	$hora_cadastro = $aux_contrato[19];
	$data_cadastro = $aux_contrato[20];
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
	<div id='centro' style='width:695px; height:15px; border:1px solid #FFF; margin-top:1px; float:left'>
	</div>";	
	}
	
	else
	{
	// RELATORIO =========================
	echo "
	<div id='centro' style='width:695px; border:0px solid #000; margin-top:1px; float:left'>
		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; background-color:#FFF'>
		&#160;&#160;$data_contrato_print</div>
		<div id='centro' style='width:205px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:left; 
		background-color:#FFF; text-transform:uppercase;'>
		&#160;&#160;$fornecedor_print</div>
		<div id='centro' style='width:50px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$numero_contrato</div>
		<div id='centro' style='width:65px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$vencimento_contrato_print</div>
		<div id='centro' style='width:100px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$produto_print</div>
		<div id='centro' style='width:80px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$quantidade_a_entregar</div>
		<div id='centro' style='width:40px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$unidade</div>
		<div id='centro' style='width:70px; height:15px; border:1px solid #FFF; float:left; color:#000; font-size:9px; text-align:center; background-color:#FFF'>
		$situacao_contrato</div>
	</div>";
	}

// =====================================
}



// =============================
$x = ($x + $limite_registros);
// =============================




if ($linha_cont_futuro == 0)
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
	<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-top:5px; float:left; font-size:11px;' align='right'><b><i><u>Total de Contratos Futuros</u></i></b></div>";


$cod_produto = $_POST["cod_produto"];

if ($cod_produto == "TODOS")
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}

else
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}


for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	if ($monstra_situacao == "todos")
	{$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND vencimento>='$data_inicial' AND vencimento<='$data_final'"));
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");}

	elseif ($monstra_situacao == "aberto")
	{$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND vencimento>='$data_inicial' AND vencimento<='$data_final' AND situacao_contrato='EM_ABERTO'"));
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");}
	
	elseif ($monstra_situacao == "pagos")
	{$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND vencimento>='$data_inicial' AND vencimento<='$data_final' AND situacao_contrato='PAGO'"));
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");}

	elseif ($monstra_situacao == "produtor")
	{$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO'"));
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");}
	
	else
	{$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' 
	AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='$aux_bp_geral[20]'"));
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");}


	if ($soma_futuros[0] == 0)
	{}
	else
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>
	$aux_bp_geral[22]: <b>$soma_futuros_print</b> $aux_bp_geral[26]</div>";}

}
/*
	if ($soma_futuros_cafe[0] >= 1)
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>Caf&eacute; Conilon: <b>$soma_futuros_cafe_print</b> Sacas</div>";}
	if ($soma_futuros_pimenta[0] >= 1)
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>Pimenta do Reino: <b>$soma_futuros_pimenta_print</b> Kg</div>";}
	if ($soma_futuros_cacau[0] >= 1)
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>Cacau: <b>$soma_futuros_cacau_print</b> Kg</div>";}
	if ($soma_futuros_cravo[0] >= 1)
	{echo "<div id='centro' style='width:700px; height:18px; border:0px solid #000; margin-left:0px; float:left; font-size:10px;' align='right'>Cravo da &Iacute;ndia: <b>$soma_futuros_cravo_print</b> Kg</div>";}
*/
echo "
</div>


<div id='centro' style='width:720px; height:10px; border:0px solid #000; margin-left:10px; float:left' align='center'>
<hr /></div>




<!-- =============================================================================================== -->
<div id='centro' style='width:720px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:180px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>";
	$ano_atual_rodape = date(Y);
	echo"&copy; $ano_atual_rodape Suif - Solu&ccedil;&otilde;es Web | $nome_fantasia";
	
	echo"
	</div>
	<div id='centro' style='width:430px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>$filial</div>

	<div id='centro' style='width:100px; height:15px; border:0px solid #000; font-size:9px; float:left' align='right'>
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