<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'relatorio_romaneios_excluidos_impressao';
$titulo = 'Relat&oacute;rio de Romaneios Exclu&iacute;dos';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace(".", "", $valor_x);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ================================================================================================================


// ====== CONVERTE PESO ==========================================================================================
function ConvertePeso($peso_x){
	$peso_1 = str_replace(".", "", $peso_x);
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$filial = $filial_usuario;

$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$numero_romaneio_w = $_POST["numero_romaneio_w"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());


if ($botao == "BUSCAR")
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = ConverteData($_POST["data_final_busca"]);}
else
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}


$mysql_filtro_data = "data_exclusao BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($situacao_romaneio_busca == "" or $situacao_romaneio_busca == "GERAL")
	{$mysql_situacao_romaneio = "situacao_romaneio IS NOT NULL";
	$situacao_romaneio_busca = "GERAL";}
else
	{$mysql_situacao_romaneio = "situacao_romaneio='$situacao_romaneio_busca'";
	$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];}


if ($forma_pesagem_busca == "" or $forma_pesagem_busca == "GERAL")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao='ENTRADA_DIRETA')";
	$forma_pesagem_busca = "GERAL";}
elseif ($forma_pesagem_busca == "BALANCA")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao!='ENTRADA_DIRETA')";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}
else
	{$mysql_forma_pesagem = "situacao='ENTRADA_DIRETA'";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}


if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
	{$mysql_fornecedor = "(fornecedor IS NOT NULL OR fornecedor IS NULL OR fornecedor='')";
	$fornecedor_busca = "GERAL";}
else
	{$mysql_fornecedor = "fornecedor='$fornecedor_busca'";
	$fornecedor_busca = $_POST["fornecedor_busca"];}


if ($cod_produto_busca == "" or $cod_produto_busca == "TODOS")
	{$mysql_cod_produto = "(cod_produto IS NOT NULL OR cod_produto IS NULL OR cod_produto='')";
	$cod_produto_busca = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro='EXCLUIDO' AND movimentacao='SAIDA' 
AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque WHERE estado_registro='EXCLUIDO' AND movimentacao='SAIDA' 
AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial' ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro='EXCLUIDO' 
AND movimentacao='SAIDA' AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ====== BUSCA PRODUTO FORM ==============================================================================
$busca_prod = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_busca' AND estado_registro='EXCLUIDO'");
$aux_prod = mysqli_fetch_row($busca_prod);
$linhas_prod = mysqli_num_rows ($busca_prod);

$prod_print = $aux_prod[1];
// ======================================================================================================


// ====== BUSCA FORNECEDOR ===================================================================================
$busca_forne = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_busca' AND estado_registro='EXCLUIDO'");
$aux_forne = mysqli_fetch_row($busca_forne);
$linhas_forne = mysqli_num_rows ($busca_forne);

if ($fornecedor_busca == "")
{$forne_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_forne == 0)
{$forne_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$forne_print = $aux_forne[1];}
// =================================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_romaneio == 0)
{$print_quant_reg = "";}
elseif ($linha_romaneio == 1)
{$print_quant_reg = "$linha_romaneio ROMANEIO";}
else
{$print_quant_reg = "$linha_romaneio ROMANEIOS";}

if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
{$print_fornecedor = "";}
else
{$print_fornecedor = "$aux_forne[1]";}

/*
if ($botao_mae != "BUSCAR" and ($pagina_mae == "index_saida" or $pagina_mae == "saida_relatorio_fornecedor"))
{$print_periodo = "";}
else
{$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";}
*/
$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";
// ==================================================================================================================


// ==================================================================================================================
include ('../../includes/head_impressao.php');
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onLoad="imprimir()">

<div id="centro" style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             					 #######
// #################################################################################################################################
$limite_registros = 48;
$numero_paginas = ceil($linha_romaneio / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "
<div id='centro' style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:710px; height:80px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:200px; height:40px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='160px' /></div>

	<div style='width:315px; height:40px; border:0px solid #000; font-size:16px; float:left' align='center'>
	<div style='margin-top:5px'>ESTOQUE - ROMANEIOS EXCLU&Iacute;DOS<br /></div></div>

	<div style='width:190px; height:40px; border:0px solid #000; font-size:11px; float:left' align='right'>
	<div style='margin-top:5px'>$data_atual<br />$hora_atual</div></div>

<!-- ====================== -->
	<div style='width:200px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'></div>

	<div style='width:315px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$prod_print</div></div>

	<div style='width:190px; height:18px; border:0px solid #000; font-size:11px; float:left' align='right'></div>

<!-- ====================== -->
	<div style='width:200px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>$print_periodo</div>

	<div style='width:315px; height:16px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$print_fornecedor</div></div>

	<div style='width:190px; height:16px; border:0px solid #000; font-size:11px; float:left' align='right'>$print_quant_reg</div>

</div>



<!-- =================================================================================================================== -->

<div style='width:710px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:40px; float:left'>

<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:63px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Data</div></div>
	<div style='width:204px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Fornecedor</div></div>
	<div style='width:48px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>N&ordm;</div></div>
	<div style='width:100px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Produto</div></div>
	<div style='width:69px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>P. L&iacute;quido (Kg)</div></div>
	<div style='width:138px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Exclu&iacute;do por</div></div>
	<div style='width:70px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Data Exclus&atilde;o</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);


// ====== DADOS DO ROMANEIO ============================================================================
$num_romaneio_print = $aux_romaneio[1];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],0,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],0,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,0,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],0,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],0,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],0,",",".");
$unidade = $aux_romaneio[11];
$unidade_print = "Kg";
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial_w = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],0,",",".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$usuario_cadastro = $aux_romaneio[19];
if ($usuario_alteracao == "")
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";

$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}

$usuario_exclusao = $aux_romaneio[40];
if ($usuario_exclusao == "")
{
$dados_exclusao = "";
$motivo_exclusao = $aux_romaneio[43];
$data_exclusao = "";
$hora_exclusao = "";
}
else
{
$usuario_exclusao = $aux_romaneio[40];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$hora_exclusao = $aux_romaneio[41];
$motivo_exclusao = $aux_romaneio[43];
}
// ======================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descrisao_sacaria = "(Sem sacaria)";}
else
{$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
if ($produto == "CAFE")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CAFE_ARABICA")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "PIMENTA")
{$quantidade_real = ($quantidade / 50);}
elseif ($produto == "CACAU")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CRAVO")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "RESIDUO_CACAU")
{$quantidade_real = ($quantidade / 60);}
else
{$quantidade_real = 0;}

$quantidade_real_print = number_format($quantidade_real,2,",",".");
// ================================================================================================================


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


// ====== SITUAÇÃO PRINT ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($situacao_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($situacao_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if ($aux_romaneio[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:9px'>

		<div style='width:63px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='margin-left:6px'>$data_print</div></div>";
		
		if ($situacao == "ENTRADA_DIRETA")
		{echo "
		<div style='width:204px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:14px; margin-left:6px; overflow:hidden'>$fornecedor_print</div></div>";}
		else
		{echo "
		<div style='width:204px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:14px; margin-left:6px; overflow:hidden'>$fornecedor_print</div></div>";}
		
		echo "
		<div style='width:48px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$num_romaneio_print</div>
		
		<div style='width:100px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; font-size:8px'>
		$produto_print</div>

		<div style='width:69px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'>$quantidade_print $unidade_print</div></div>

		<div style='width:138px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$usuario_exclusao</div>

		<div style='width:70px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'>$data_exclusao</div></div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_romaneio == 0)
{echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
		<div style='width:705px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		<i>Nenhum romaneio encontrado.</i></div>
	</div>";}
else
{}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{
	echo "
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'>
	<!-- TOTAL DE ENTRADA: -->
	</div>";

for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro='EXCLUIDO' 
AND movimentacao='SAIDA' AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor 
AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$soma_quant_print = number_format($soma_quant_produto[0],2,",",".");


	if ($soma_quant_produto[0] == 0)
	{}
	else
	{
	$linhas_t_aux = $linhas_t_aux + 1;
	
	
	echo "
	<div style='width:300px; height:27px; border:0px solid #999; margin-top:5px; margin-left:204px; float:left; background-color:#FFF; font-size:11px'>
	<div style='height:26px; width:290px; border:1px solid #000; border-radius:3px; background-color:#FFF; margin-left:0px'>
		<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:10px; margin-top:6px'>
		<!-- <b>$aux_bp_geral[22]</b> --></div>
		
		<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:10px; margin-top:6px; text-align:left'>
		<!-- <b>$soma_quant_print</b> Kg --></div>
		
	</div>
	</div>";
	}

}
$linhas_totalizador = 2 + ($linhas_t_aux * 2);
$vazio = $contador_vazio - $linhas_totalizador;
}

else
{}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
echo "<div style='width:708px; height:15px; border:1px solid #FFF; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>";
	}
}

else
{}



echo "</div>";
// ========================================================================================================






echo "
<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:10px; border:0px solid #000; margin-left:40px; margin-top:20px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>FILIAL: $filial</div>

	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:right' align='right'>
	P&Aacute;GINA $x_principal/$numero_paginas</div>
</div>
<!-- =============================================================================================== -->



<!-- =============================================================================================== -->";
echo "</div>"; // quebra de página
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->