<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_pessoa_impressao";
$titulo = "Relat&oacute;rio - Cadastros de Pessoas";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$filial = $filial_usuario;

$pesquisar_por_busca = $_POST["pesquisar_por_busca"];
$nome_busca = $_POST["nome_busca"];
$cpf_busca = $_POST["cpf_busca"];
$cnpj_busca = $_POST["cnpj_busca"];
$fantasia_busca = $_POST["fantasia_busca"];

$tipo_pessoa_busca = $_POST["tipo_pessoa_busca"];
$classificacao_busca = $_POST["classificacao_busca"];
$cidade_busca = $_POST["cidade_busca"];
$status_busca = $_POST["status_busca"];
// ================================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_busca'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA - RELATORIO ======================================================================
if ($tipo_pessoa_busca == "" or $tipo_pessoa_busca == "GERAL")
	{$mysql_tipo_pessoa = "tipo IS NOT NULL";
	$tipo_pessoa_print = "";}
else
	{$mysql_tipo_pessoa = "tipo='$tipo_pessoa_busca'";
	$tipo_pessoa_print = "Tipo Pessoa: $tipo_pessoa_busca&#160;&#160;&#160;";}

if ($classificacao_busca == "" or $classificacao_busca == "GERAL")
	{$mysql_classificacao = "classificacao_1 IS NOT NULL";
	$classificacao_print = "";}
else
	{$mysql_classificacao = "classificacao_1='$classificacao_busca'";
	$classificacao_print = "Classifica&ccedil;&atilde;o: $classificacao_print&#160;&#160;&#160;";}

if ($cidade_busca == "" or $cidade_busca == "GERAL")
	{$mysql_cidade = "cidade IS NOT NULL";
	$cidade_print = "";}
else
	{$mysql_cidade = "cidade='$cidade_busca'";
	$cidade_print = "Cidade: $cidade_busca&#160;&#160;&#160;";}

if ($status_busca == "" or $status_busca == "GERAL")
	{$mysql_status = "estado_registro IS NOT NULL";
	$status_print = "";}
else
	{$mysql_status = "estado_registro='$status_busca'";
	$status_print = "Status Cadastro: $status_busca&#160;&#160;&#160;";}
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if ($pesquisar_por_busca == "NOME" and $nome_busca != "")
{$mysql_busca = "nome LIKE '%$nome_busca%' ORDER BY nome";
$pesquisar_por_print = "Nome: $nome_busca";}

elseif ($pesquisar_por_busca == "CPF")
{$mysql_busca = "cpf='$cpf_busca' ORDER BY nome";
$pesquisar_por_print = "CPF: $cpf_busca";}

elseif ($pesquisar_por_busca == "CNPJ")
{$mysql_busca = "cnpj='$cnpj_busca' ORDER BY nome";
$pesquisar_por_print = "CNPJ: $cnpj_busca";}

elseif ($pesquisar_por_busca == "FANTASIA" and $fantasia_busca != "")
{$mysql_busca = "nome_fantasia LIKE '%$fantasia_busca%' ORDER BY nome";
$pesquisar_por_print = "Nome Fantasia: $fantasia_busca";}

elseif ($pesquisar_por_busca == "RELATORIO")
{$mysql_busca = "$mysql_tipo_pessoa AND $mysql_classificacao AND $mysql_cidade AND $mysql_status ORDER BY nome";
$pesquisar_por_print = "$tipo_pessoa_print $classificacao_print $cidade_print $status_print";
}

else
{}
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
if ($botao == "BUSCAR")
{
	$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE $mysql_busca");
	$linha_pessoa = mysqli_num_rows ($busca_pessoa);
}

if ($linha_pessoa == 1)
{$print_quant_reg = "$linha_pessoa CADASTRO";}
elseif ($linha_pessoa > 1)
{$print_quant_reg = "$linha_pessoa CADASTROS";}
else
{$print_quant_reg = "";}

// ================================================================================================================


// ==================================================================================================================
include ("../../includes/head_impressao.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onLoad="imprimir()">

<div style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             					 #######
// #################################################################################################################################
$limite_registros = 48;
$numero_paginas = ceil($linha_pessoa / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "
<div style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:710px; height:90px; border:0px solid #D85; float:left; margin-top:15px; margin-left:40px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:200px; height:68px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' height='68px' /></div>

	<div style='width:310px; height:40px; border:0px solid #000; font-size:16px; float:left' align='center'>
	<div style='margin-top:15px'>CADASTROS DE PESSOAS<br /></div></div>

	<div style='width:190px; height:40px; border:0px solid #000; font-size:11px; float:left' align='right'>
	<div style='margin-top:15px'>$data_hoje_br<br />$hora_br</div></div>

<!-- ====================== -->


	<div style='width:310px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'></div></div>

	<div style='width:190px; height:18px; border:0px solid #000; font-size:11px; float:left' align='right'></div>

<!-- ====================== -->
	<div style='width:575px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>$pesquisar_por_print</div>

	<div style='width:5px; height:16px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'></div></div>

	<div style='width:120px; height:16px; border:0px solid #000; font-size:11px; float:left' align='right'>$print_quant_reg</div>

</div>



<!-- =================================================================================================================== -->

<div style='width:710px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:40px; float:left'>

<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:60px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>C&oacute;digo</div></div>
	<div style='width:270px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Nome</div></div>
	<div style='width:110px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>CPF/CNPJ</div></div>
	<div style='width:90px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Telefone</div></div>
	<div style='width:160px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Cidade/UF</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);


// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_pessoa[0];
$nome_w = $aux_pessoa[1];
$tipo_w = $aux_pessoa[2];
$cpf_w = $aux_pessoa[3];
$cnpj_w = $aux_pessoa[4];
$rg_w = $aux_pessoa[5];
$sexo_w = $aux_pessoa[6];
$data_nascimento_w = $aux_pessoa[7];
$endereco_w = $aux_pessoa[8];
$bairro_w = $aux_pessoa[9];
$cidade_w = $aux_pessoa[10];
$cep_w = $aux_pessoa[11];
$estado_w = $aux_pessoa[12];
$ponto_referencia_w = $aux_pessoa[13];
$telefone_1_w = $aux_pessoa[14];
$telefone_2_w = $aux_pessoa[15];
$email_w = $aux_pessoa[17];
$classificacao_1_w = $aux_pessoa[18];
$observacao_w = $aux_pessoa[22];
$nome_fantasia_w = $aux_pessoa[24];
$numero_residencia_w = $aux_pessoa[25];
$complemento_w = $aux_pessoa[26];
$estado_registro_w = $aux_pessoa[34];
$codigo_pessoa_w = $aux_pessoa[35];

if ($tipo_w == "PF" or $tipo_w == "pf")
{$cpf_cnpj_print = $cpf_w;}
else
{$cpf_cnpj_print = $cnpj_w;}
// ======================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_w'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ====== RELATORIO =====================================================================================
	if ($aux_pessoa[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:9px'>

		<div style='width:60px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:11px; margin-left:6px'>$id_w</div></div>
		
		<div style='width:270px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:11px; margin-left:6px; overflow:hidden'>$nome_w</div></div>
		
		<div style='width:110px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$cpf_cnpj_print</div>
		
		<div style='width:90px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$telefone_1_w</div>

		<div style='width:160px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$cidade_w/$estado_w</div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_pessoa == 0)
{echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
		<div style='width:705px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		<i>Nenhum cadastro encontrado.</i></div>
	</div>";}
else
{}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{
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
<div style='width:710px; height:10px; border:0px solid #000; margin-left:40px; margin-top:20px; float:left' align='center'>
<div style='width:710px; height:5px; border-bottom:2px solid #999; margin-left:0px; float:left'></div>
</div>


<!-- =============================================================================================== -->
<div style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:left' align='center'></div>

	<div style='width:233px; height:15px; border:0px solid #000; font-size:10px; float:right' align='right'>
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