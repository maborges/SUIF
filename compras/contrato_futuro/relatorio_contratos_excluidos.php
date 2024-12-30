<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'relatorio_contratos_excluidos';
$titulo = 'Relat&oacute;rios de Contratos Futuros';
$modulo = 'compras';
$menu = 'contratos';
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$situacao_contrato = $_POST["situacao_contrato"];
$filtro_data = $_POST["filtro_data"];

$codigo_contrato_w = $_POST["codigo_contrato_w"];
$numero_contrato_w = $_POST["numero_contrato_w"];
$fornecedor_w = $_POST["fornecedor_w"];
$fornecedor_print_w = $_POST["fornecedor_print_w"];
$produto_w = $_POST["produto_w"];
$cod_produto_w = $_POST["cod_produto_w"];
$unidade_w = $_POST["unidade_w"];
$cod_unidade_w = $_POST["cod_unidade_w"];
$tipo_w = $_POST["tipo_w"];
$cod_tipo_w = $_POST["cod_tipo_w"];
$quantidade_a_entregar_w = $_POST["quantidade_a_entregar_w"];



$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
$usuario_baixa = $nome_usuario_print;
$hora_baixa = date('G:i:s', time());
$data_baixa = date('Y/m/d', time());


if ($botao == "BUSCAR")
	{$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);}
else
	{$data_inicial_aux = $data_hoje_aux;
	$data_inicial = $data_hoje;
	$data_final_aux = $data_hoje_aux;
	$data_final = $data_hoje;}


if ($situacao_contrato == "" or $situacao_contrato == "GERAL")
	{$mysql_situacao_contrato = "situacao_contrato IS NOT NULL";
	$situacao_contrato = "GERAL";}
else
	{$mysql_situacao_contrato = "situacao_contrato='$situacao_contrato'";
	$situacao_contrato = $_POST["situacao_contrato"];}


if ($filtro_data == "" or $filtro_data == "DATA_VENCIMENTO")
	{$mysql_filtro_data = "vencimento BETWEEN '$data_inicial' AND '$data_final'";
	$filtro_data = "DATA_VENCIMENTO";}
else
	{$mysql_filtro_data = "$filtro_data BETWEEN '$data_inicial' AND '$data_final'";
	$filtro_data = $_POST["filtro_data"];}
	

if ($fornecedor_form == "" or $fornecedor_form == "GERAL")
	{$mysql_fornecedor = "produtor IS NOT NULL";
	$fornecedor_form = "GERAL";}
else
	{$mysql_fornecedor = "produtor='$fornecedor_form'";
	$fornecedor_form = $_POST["fornecedor_form"];}


if ($cod_produto_form == "" or $cod_produto_form == "TODOS")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_form = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_form'";
	$cod_produto_form = $_POST["cod_produto_form"];}
// ============================================================================================================================


// ===== BUSCA CONTRATOS =============================================================================================
$busca_cont_futuro = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro='EXCLUIDO' AND filial='$filial' 
AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto ORDER BY vencimento, nome_produtor");

$linha_cont_futuro = mysqli_num_rows ($busca_cont_futuro);
// =============================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ================================================================================================================================


// ================================================================================================================================
include ('../../includes/head.php');
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
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>
<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:auto; margin:auto; background-color:#FFF; border-radius:10px; border:1px solid #999">
<div style="width:1250px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1100px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:0px">
    Contratos Futuros Exclu&iacute;dos
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:0px">
    	<div id="menu_atalho_3" style="margin-top:10px">
    	<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorios.php' >
        &#8226; Outros relat&oacute;rios de contratos futuros</a>
        </div>
    </div>
</div>

<div style="width:1250px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1100px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:0px; font-size:14px">
	<!-- Relat&oacute;rio Por Produto -->
    </div>
</div>

<div style="width:1100px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<!--
<div style="width:1250px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    <?php // echo "$msg"; ?>
    </div>
</div>

<div style="width:1080px; height:5px; border:0px solid #000; margin:auto"></div>
-->
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:36px; width:1250px; border:1px solid #999; margin:auto; background-color:#EEE">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_contratos_excluidos.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />

	<div id="centro" style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div id="centro" style="height:20px; width:75px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:left; margin-top:11px">
	<i>Data inicial:&#160;</i>
    </div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_inicial_aux"; ?>" />
	</div>

    <div id="centro" style="height:20px; width:85px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Data final:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_final_aux"; ?>" />
	</div>

    <div id="centro" style="height:20px; width:90px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Produto:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:160px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
	<select name="cod_produto_form" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:12px" />
    <option value="TODOS">(TODOS)</option>
    <?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_form)
		{
		echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
		else
		{
		echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
	}
    ?>
    </select>
    </div>


    <div id="centro" style="height:20px; width:90px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Filtrar por:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:160px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <select name="filtro_data" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:12px" />
    <?php
    if ($filtro_data == "vencimento")
    {echo "<option selected='selected' value='vencimento'>Data de Vencimento</option>";}
    else
    {echo "<option value='vencimento'>Data de Vencimento</option>";}
    if ($filtro_data == "data")
    {echo "<option selected='selected' value='data'>Data de Emiss&atilde;o</option>";}
    else
    {echo "<option value='data'>Data de Emiss&atilde;o</option>";}
    if ($filtro_data == "data_cadastro")
    {echo "<option selected='selected' value='data_cadastro'>Data de Cadastro</option>";}
    else
    {echo "<option value='data_cadastro'>Data de Cadastro</option>";}
    if ($filtro_data == "data_baixa")
    {echo "<option selected='selected' value='data_baixa'>Data de Baixa</option>";}
    else
    {echo "<option value='data_baixa'>Data de Baixa</option>";}
    ?>
    </select>
    </div>
    
    <div id="centro" style="height:20px; width:135px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Situa&ccedil;&atilde;o do Contrato:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:95px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <select name="situacao_contrato" onkeydown="if (getKey(event) == 13) return false;" style="height:21px; width:85px; color:#0000FF; font-size:11px" />
    <?php
    if ($situacao_contrato == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(Geral)</option>";}
    else
    {echo "<option value='GERAL'>(Geral)</option>";}

    if ($situacao_contrato == "EM_ABERTO")
    {echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
    else
    {echo "<option value='EM_ABERTO'>Em Aberto</option>";}

    if ($situacao_contrato == "PAGO")
    {echo "<option value='PAGO' selected='selected'>Quitado</option>";}
    else
    {echo "<option value='PAGO'>Quitado</option>";}
    ?>
    </select>
	</div>

	<div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:20px; margin-top:8px">
    <input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
    </form>
	</div>
	
</div>

<div id="centro" style="height:15px; width:1250px; border:0px solid #000; margin:auto"></div>


<div id="centro" style="height:26px; width:1250px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:400px; float:left; height:26px; margin-left:10px; border:0px solid #999">
<!--
		<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/contrato_futuro_seleciona.php">
		<input type="image" src="<?php // echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/novo.jpg" border="0" style="float:left" />
		</a>
-->
	</div>
	
	<div id="centro" style="width:400px; float:left; height:26px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
		<?php 
        if ($linha_cont_futuro >= 1)
        {
			/*
		echo"
        <form action='$servidor/$diretorio_servidor/compras/contrato_futuro/relatorio_contratos_excluidos_print.php' target='_blank' method='post'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='imprimir'>
        <input type='hidden' name='data_inicial' value='$data_inicial_aux'>
        <input type='hidden' name='data_final' value='$data_final_aux'>
        <input type='hidden' name='situacao_contrato' value='$situacao_contrato'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>";
		*/
		}
        else
        {}
        ?>
	</div>

	<div id="centro" style="width:400px; float:right; height:26px; border:0px solid #999; font-size:12px; color:#003466; text-align:right; margin-right:10px">
		<?php 
        if ($linha_cont_futuro == 1)
        {echo"<i><b>$linha_cont_futuro</b> Contrato</i>";}
        elseif ($linha_cont_futuro == 0)
        {echo"";}
        else
        {echo"<i><b>$linha_cont_futuro</b> Contratos</i>";}
        ?>
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:10px; width:1250px; border:0px solid #000; margin:auto; border-radius:0px"></div>


<!-- ====================================================================================== -->
<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	$soma_futuros = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_a_entregar) FROM contrato_futuro WHERE estado_registro='EXCLUIDO' 
	AND filial='$filial' AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND produto='$aux_bp_geral[20]'"));
	$soma_adquirido = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_adquirida) FROM contrato_futuro WHERE estado_registro='EXCLUIDO' 
	AND filial='$filial' AND $mysql_situacao_contrato AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND produto='$aux_bp_geral[20]'"));


	if ($soma_futuros[0] == 0 or $soma_adquirido[0] == 0)
	{}
	else
	{
	$juros = ($soma_futuros[0] - $soma_adquirido[0]);
	$juros_porcento = ($juros / $soma_adquirido[0]) * 100;
	$soma_futuros_print = number_format($soma_futuros[0],2,",",".");
	$soma_adquirido_print = number_format($soma_adquirido[0],2,",",".");
	$juros_print = number_format($juros,2,",",".");
	$juros_porc_print = number_format($juros_porcento,0,",",".") . "%";
	
	echo "
	<div id='centro' style='height:35px; width:1250px; border:0px solid #999; margin:auto; background-color:#FFF; font-size:11px'>
	<div style='height:26px; width:930px; border:1px solid #009900; border-radius:5px; background-color:#EEE; margin-left:0px'>
		<div style='width:180px; color:#009900; float:left; margin-left:8px; margin-top:5px'><b>$aux_bp_geral[22]</b></div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Quant. Adquirida:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px'><b>$soma_adquirido_print</b> $aux_bp_geral[26]</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Quant. Entregar:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px'><b>$soma_futuros_print</b> $aux_bp_geral[26]</div>
		<div style='width:70px; color:#666; float:left; margin-left:8px; margin-top:5px; text-align:right'>Juros:</div>
		<div style='width:120px; color:#666; float:left; margin-left:8px; margin-top:5px'>$juros_print $aux_bp_geral[26] 
			<font title='M&eacute;dia de Juros'>($juros_porc_print)</font></div>
	</div>
	</div>";
	}

}
?>
<!-- ====================================================================================== -->


<!-- ====================================================================================== -->
<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>


<?php
// ======================================================================================================
if ($linha_cont_futuro == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:5px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_cont_futuro == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='275px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='50px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='70px' align='center' bgcolor='#006699'>Vencimento</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='95px' align='center' bgcolor='#006699'>Valor Un.</td>
<td width='95px' align='center' bgcolor='#006699'>Quant. Adquirida</td>
<td width='95px' align='center' bgcolor='#006699'>Quant. Entregar</td>
<td width='60px' align='center' bgcolor='#006699'>Juros</td>
<td width='50px' align='center' bgcolor='#006699'>Unidade</td>
<td width='120px' align='center' bgcolor='#006699'>Exclu&iacute;do por</td>
<td width='80px' align='center' bgcolor='#006699'>Data Exclus&atilde;o</td>
</tr>
</table>";}

echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:10px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($w=1 ; $w<=$linha_cont_futuro ; $w++)
{
$aux_contrato = mysqli_fetch_row($busca_cont_futuro);

// ====== DADOS DO CONTRATO ============================================================================
$fornecedor_w = $aux_contrato[1];
$cod_produto_w = $aux_contrato[31];
$data_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[2]));
$produto_w = $aux_contrato[3];
$quantidade_w = $aux_contrato[4];
$quantidade_adquirida_w = $aux_contrato[5];
$unidade_w = $aux_contrato[6];
$cod_unidade_w = $aux_contrato[32];
$tipo_w = $aux_contrato[26];
$cod_tipo_w = $aux_contrato[33];
$desc_produto_w = $aux_contrato[7];
$venc_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[8]));
$fiador_1_w = $aux_contrato[9];
$fiador_2_w = $aux_contrato[10];
$observacao_w = $aux_contrato[11];
$estado_registro_w = $aux_contrato[12];
$quantidade_fracao_w = $aux_contrato[13];
$porcentagem_juros_w = $aux_contrato[14];
$situacao_contrato_w = $aux_contrato[15];
$quantidade_a_entregar_w = $aux_contrato[16];
$numero_contrato_w = $aux_contrato[17];
$usuario_cadastro_w = $aux_contrato[18];
$hora_cadastro_w = $aux_contrato[19];
$data_cadastro_w = $aux_contrato[20];
$filial_w = $aux_contrato[24];
$preco_produto_w = number_format($aux_contrato[27],2,",",".");
$porc_juros_print_w = number_format($aux_contrato[14],0,",",".") . "%";
$usuario_exclusao = $aux_contrato[34];
$hora_exclusao = $aux_contrato[36];
if ($aux_contrato[35] == "" or $aux_contrato[35] == "0000-00-00")
{$data_exclusao = "";}
else
{$data_exclusao = date('d/m/Y', strtotime($aux_contrato[35]));}
$motivo_exclusao = $aux_contrato[37];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print_w = $aux_bp[1];
$produto_print_2_w = $aux_bp[22];
$produto_apelido_w = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_w' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print_w = $aux_pessoa[1];
$codigo_pessoa_w = $aux_pessoa[35];
$cidade_fornecedor_w = $aux_pessoa[10];
$estado_fornecedor_w = $aux_pessoa[12];
$telefone_fornecedor_w = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj_w = $aux_pessoa[3];}
else
{$cpf_cnpj_w = $aux_pessoa[4];}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	if ($situacao_contrato_w == "EM_ABERTO")
	{echo "<tr style='color:#F00' title='Observa&ccedil;&atilde;o: $observacao_w&#013;Motivo da Exclus&atilde;o: $motivo_exclusao'>";}
	else
	{echo "<tr style='color:#F00' title='Observa&ccedil;&atilde;o: $observacao_w&#013;Motivo da Exclus&atilde;o: $motivo_exclusao'>";}

	echo "
	<td width='70px' align='left'>&#160;$data_contrato_print_w</td>
	<td width='275px' align='left'>&#160;$fornecedor_print_w</td>
	<td width='50px' align='center'>$numero_contrato_w</td>
	<td width='70px' align='center'>$venc_contrato_print_w</td>
	<td width='100px' align='center'>$produto_print_2_w</td>
	<td width='95px' align='center'>$preco_produto_w</td>
	<td width='95px' align='center'>$quantidade_adquirida_w</td>
	<td width='95px' align='center'>$quantidade_a_entregar_w</td>
	<td width='60px' align='center'>$porc_juros_print_w</td>
	<td width='50px' align='center'>$unidade_w</td>
	<td width='120px' align='center'>$usuario_exclusao</td>
	<td width='80px' align='center'>$data_exclusao</td>";


	echo "</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_cont_futuro == 0 and $botao == "BUSCAR")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum contrato encontrado.</i></div>";}
else
{}
// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================
?>


<!-- ====================================================================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<!-- FIM CENTRO GERAL (depois do menu geral) -->
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>