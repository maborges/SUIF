<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_favorecido_impressao";
$titulo = "Relat&oacute;rio - Cadastros de Favorecidos";
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje_br = date('d/m/Y', time());
$hora_br = date('G:i:s', time());
$filial = $filial_usuario;

$nome_busca = $_POST["nome_busca"];
$status_busca = $_POST["status_busca"];
$banco_busca = $_POST["banco_busca"];
// ================================================================================================================


// ======= BUSCA BANCO ==================================================================================
$busca_nome_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_busca'");
$aux_nome_banco = mysqli_fetch_row($busca_nome_banco);
$banco_nome_print = $aux_nome_banco[3] . " (" . $aux_nome_banco[2] . ") ";
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA - RELATORIO ======================================================================
if ($banco_busca == "" or $banco_busca == "GERAL")
	{$mysql_banco = "banco IS NOT NULL";
	$banco_busca = "GERAL";
	$banco_nome_print = "";}
else
	{$mysql_banco = "banco='$banco_busca'";
	$banco_busca = $_POST["banco_busca"];
	$banco_nome_print = "Banco: $banco_nome_print&#160;&#160;&#160;";}


if ($status_busca == "" or $status_busca == "GERAL")
	{$mysql_status = "estado_registro IS NOT NULL";
	$status_busca = "GERAL";
	$status_print = "";}
else
	{$mysql_status = "estado_registro='$status_busca'";
	$status_busca = $_POST["status_busca"];
	$status_print = "Status Cadastro: $status_busca&#160;&#160;&#160;";}


if ($nome_busca == "")
	{$mysql_nome = "nome IS NOT NULL";
	$nome_busca = "";
	$nome_print = "";}
else
	{$mysql_nome = "nome LIKE '%$nome_busca%'";
	$nome_busca = $_POST["nome_busca"];
	$nome_print = "Nome: $nome_busca&#160;&#160;&#160;";}


$pesquisar_por_print = "$nome_print $banco_nome_print $status_print";
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
if ($botao == "BUSCAR")
{
	$busca_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE $mysql_status AND $mysql_banco AND $mysql_nome ORDER BY nome");
	$linha_favorecido = mysqli_num_rows ($busca_favorecido);
}

if ($linha_favorecido == 1)
{$print_quant_reg = "$linha_favorecido CADASTRO";}
elseif ($linha_favorecido > 1)
{$print_quant_reg = "$linha_favorecido CADASTROS";}
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
$numero_paginas = ceil($linha_favorecido / $limite_registros);


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
	<div style='margin-top:15px'>CADASTROS DE FAVORECIDOS<br /></div></div>

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
	<div style='width:50px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>C&oacute;digo</div></div>
	<div style='width:220px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Nome</div></div>
	<div style='width:110px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>CPF/CNPJ</div></div>
	<div style='width:90px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Banco</div></div>
	<div style='width:70px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Ag&ecirc;ncia</div></div>
	<div style='width:70px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>N&ordm; da Conta</div></div>
	<div style='width:70px; height:15px; border:1px solid #000; float:left; background-color:#666'>
	<div style='margin-top:2px'>Tipo de Conta</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_favorecido = mysqli_fetch_row($busca_favorecido);


// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_favorecido[0];
$codigo_pessoa_w = $aux_favorecido[1];
$banco_w = $aux_favorecido[2];
$agencia_w = $aux_favorecido[3];
$conta_w = $aux_favorecido[4];
$tipo_conta_w = $aux_favorecido[5];
$observacao_w = $aux_favorecido[12];
$estado_registro_w = $aux_favorecido[13];
$nome_w = $aux_favorecido[14];
$conta_conjunta_w = $aux_favorecido[15];

if ($conta_conjunta_w == "SIM" and $agencia_w!="")
{$conta_conjunta_print = "SIM";}
elseif ($conta_conjunta_w != "SIM" and $agencia_w!="")
{$conta_conjunta_print = "N&Atilde;O";}
else
{$conta_conjunta_print = "";}

$usuario_cadastro_w = $aux_favorecido[6];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_favorecido[8]));
$hora_cadastro_w = $aux_favorecido[7];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_favorecido[9];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_favorecido[11]));
$hora_alteracao_w = $aux_favorecido[10];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_favorecido[16];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_favorecido[17]));
$hora_exclusao_w = $aux_favorecido[18];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$codigo_pessoa_w'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linha_pessoa = mysqli_num_rows ($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];

if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{$cpf_cnpj = $cpf_pessoa;}
else
{$cpf_cnpj = $cnpj_pessoa;}

if ($linha_pessoa == 0)
{$cidade_uf = "";}
else
{$cidade_uf = "$cidade_pessoa/$estado_pessoa";}
// ======================================================================================================


// ====== BUSCA BANCO ===================================================================================
$busca_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_w'");
$aux_banco = mysqli_fetch_row($busca_banco);

$apelido_banco = $aux_banco[3];
$logomarca_banco = $aux_banco[4];

if (empty($logomarca_banco))
{$logo_banco = "$apelido_banco";}
else
{$logo_banco = "$apelido_banco";}
// ======================================================================================================


// ====== TIPO DE CONTA =================================================================================
if ($tipo_conta_w == "corrente")
{$tipo_conta_print = "Corrente";}
elseif ($tipo_conta_w == "poupanca")
{$tipo_conta_print = "Poupan&ccedil;a";}
elseif ($tipo_conta_w == "salario")
{$tipo_conta_print = "Sal&aacute;rio";}
elseif ($tipo_conta_w == "aplicacao")
{$tipo_conta_print = "Aplica&ccedil;&atilde;o";}
else
{$tipo_conta_print = "";}
// ======================================================================================================



// ====== RELATORIO =====================================================================================
	if ($aux_pessoa[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:9px'>

		<div style='width:50px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:11px; margin-left:6px'>$id_w</div></div>
		
		<div style='width:220px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:11px; margin-left:6px; overflow:hidden'>$nome_pessoa</div></div>
		
		<div style='width:110px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$cpf_cnpj</div>
		
		<div style='width:90px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$logo_banco</div>

		<div style='width:70px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$agencia_w</div>

		<div style='width:70px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$conta_w</div>

		<div style='width:70px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$tipo_conta_print</div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_favorecido == 0)
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
<hr />
</div>


<!-- =============================================================================================== -->
<div style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'></div>

	<div style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:right' align='right'>
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