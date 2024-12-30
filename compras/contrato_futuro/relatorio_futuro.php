<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "relatorio_futuro";
$titulo = "Relat&oacute;rio de Contrato Futuro";
$modulo = "compras";
$menu = "contratos";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$filial = $filial_usuario;

if ($botao == "BUSCAR")
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filtro_data = $_POST["filtro_data"];
$situacao_contrato = $_POST["situacao_contrato"];
$filial_busca = $_POST["filial_busca"];
$status_busca = $_POST["status_busca"];
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filtro_data = "vencimento";
$situacao_contrato = "EM_ABERTO";
$filial_busca = $filial;
$status_busca = "ATIVO";
}
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "$filtro_data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($fornecedor_pesquisa == "" or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "produtor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "produtor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if ($cod_produto_busca == "" or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if ($situacao_contrato == "" or $situacao_contrato == "GERAL")
	{$mysql_situacao_contrato = "situacao_contrato IS NOT NULL";
	$situacao_contrato = "GERAL";}
else
	{$mysql_situacao_contrato = "situacao_contrato='$situacao_contrato'";
	$situacao_contrato = $situacao_contrato;}


if ($filial_busca == "")
	{$mysql_filial = "filial='$filial'";
	$filial_busca = "$filial";}
elseif ($filial_busca == "GERAL")
	{$mysql_filial = "filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "filial='$filial_busca'";
	$filial_busca = $filial_busca;}


if ($status_busca == "" or $status_busca == "GERAL")
	{$mysql_status = "estado_registro IS NOT NULL";
	$status_busca = "GERAL";}
else
	{$mysql_status = "estado_registro='$status_busca'";
	$status_busca = $status_busca;}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_situacao_contrato AND $mysql_filial AND $mysql_status ORDER BY codigo");
$linha_contrato = mysqli_num_rows ($busca_contrato);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM contrato_futuro WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_situacao_contrato AND $mysql_filial AND $mysql_status ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

//$soma_contrato = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM contrato_futuro WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_situacao_contrato AND $mysql_filial AND $mysql_status"));
//$soma_contrato_print = "R$ " . number_format($soma_contrato[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  ===============================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ===============================================================================================================


// ====== BUSCA FORNECEDOR =======================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_pesquisa'");
$aux_fornecedor = mysqli_fetch_row($busca_fornecedor);
$linha_fornecedor = mysqli_num_rows ($busca_fornecedor);

$nome_fornecedor = $aux_fornecedor[1];
$tipo_fornecedor = $aux_fornecedor[2];
$cpf_fornecedor = $aux_fornecedor[3];
$cnpj_fornecedor = $aux_fornecedor[4];
$cidade_fornecedor = $aux_fornecedor[10];
$estado_fornecedor = $aux_fornecedor[12];
$telefone_fornecedor = $aux_fornecedor[14];
$codigo_fornecedor = $aux_fornecedor[35];
// ================================================================================================================


// ====== MONTA MENSAGEM ==================================================================================
if ($linha_fornecedor >= 1)
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}

else
{$erro = 0;
$msg = "";}
// ================================================================================================================



// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo"$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_contrato == 1)
    {echo"$linha_contrato Contrato";}
    elseif ($linha_contrato > 1)
    {echo"$linha_contrato Contratos";}
    else
    {echo"";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/futuro_selec_fornecedor.php"><i>Buscar por fornecedor</i></a>
    </div>
    </div>
</div>
<!-- ============================================================================================================= -->

PAREI AQUI #######

<!-- ============================================================================================================= -->
<div class="pqa" style="width:1350px; height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:20px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_adiantamento/relatorio_adto.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="fornecedor_busca" value="<?php echo"$fornecedor_busca"; ?>" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
</div>
<!-- ================================================================================================================ -->

 <!-- ======= DATA INICIAL ========================================================================================== -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Inicial:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_inicial_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_inicial_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= DATA FINAL ============================================================================================ -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Final:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_final_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_final_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= PRODUTO =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_busca" class="form_select" style="width:190px" />
    <?php
    if ($cod_produto_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
    else
    {echo "<option value='GERAL'>(TODOS)</option>";}

    $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
    $linhas_produto_list = mysqli_num_rows ($busca_produto_list);

    for ($i=1 ; $i<=$linhas_produto_list ; $i++)
    {
    $aux_produto_list = mysqli_fetch_row($busca_produto_list);	

        if ($aux_produto_list[0] == $cod_produto_busca)
        {echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
        else
        {echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= FILIAL =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="filial_busca" class="form_select" style="width:190px" />
    <?php
    if ($filial_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
    else
    {echo "<option value='GERAL'>(TODAS)</option>";}

    $busca_filial_list = mysqli_query ($conexao, "SELECT * FROM filiais WHERE estado_registro='ATIVO' ORDER BY codigo");
    $linhas_filial_list = mysqli_num_rows ($busca_filial_list);

    for ($f=1 ; $f<=$linhas_filial_list ; $f++)
    {
    $aux_filial_list = mysqli_fetch_row($busca_filial_list);	

        if ($aux_filial_list[1] == $filial_busca)
        {echo "<option selected='selected' value='$aux_filial_list[1]'>$aux_filial_list[2]</option>";}
        else
        {echo "<option value='$aux_filial_list[1]'>$aux_filial_list[2]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= STATUS REGISTRO ======================================================================================= -->
<div style="width:190px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:188px; height:17px; border:1px solid transparent; float:left">
    Status dos Registros:
    </div>
    
    <div style="width:188px; height:25px; float:left; border:1px solid transparent">
    <select name="status_busca" class="form_select" style="width:160px" />
    <?php
	if ($status_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(Todos os Registros)</option>";}
	else
	{echo "<option value='GERAL'>(Todos os Registros)</option>";}

	if ($status_busca == "ATIVO")
	{echo "<option value='ATIVO' selected='selected'>ATIVOS</option>";}
	else
	{echo "<option value='ATIVO'>ATIVOS</option>";}

	if ($status_busca == "EXCLUIDO")
	{echo "<option value='EXCLUIDO' selected='selected'>EXCLU&Iacute;DOS</option>";}
	else
	{echo "<option value='EXCLUIDO'>EXCLU&Iacute;DOS</option>";}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= IMPRIMIR ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	/*
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/relatorios/relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='numero_venda_busca' value='$numero_compra_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir</button>
	</form>";}
	*/
	?>
    </div>
</div>
<!-- ================================================================================================================ -->



	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
$numero_divs = ceil($linha_produto_distinct / 3);
$altura_div = ($numero_divs * 50) . "px";

echo "<div style='height:$altura_div; width:1350px; border:1px solid transparent; margin:auto'>";


for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

// ====== BUSCA PRODUTO ======================================================================================
$busca_prod_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$aux_bp_distinct[0]'");
$aux_prod_2 = mysqli_fetch_row($busca_prod_2);

$cod_prod_2 = $aux_prod_2[0];
$prod_print_2 = $aux_prod_2[1];
$un_print_2 = $aux_prod_2[26];
$nome_imagem_produto_2 = $aux_prod_2[28];
if ($nome_imagem_produto_2 == "")
{$link_imagem_produto_2 = "";}
else
{$link_imagem_produto_2 = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_2.png' style='width:60px'>";}

// ===========================================================================================================

$soma_valor_geral = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM contrato_adiantamento WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_filial AND $mysql_status AND cod_produto='$aux_bp_distinct[0]'"));
$soma_valor_print = "R$ " . number_format($soma_valor_geral[0],2,",",".");


echo "
<div style='height:42px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_imagem_produto_2</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$prod_print_2</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:12px; margin-top:14px'>$soma_valor_print</div>

</div>
</div>";

}

echo "</div>";
?><!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php
if ($linha_contrato == 0)
{echo "
<div style='height:400px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='120px'>Data</td>
<td width='350px'>Fornecedor</td>
<td width='100px'>N&uacute;mero</td>
<td width='200px'>Produto</td>
<td width='200px'>Valor</td>
<td width='120px'>Vencimento</td>
<td width='60px'>Visualizar</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_contrato ; $x++)
{
$aux_contrato = mysqli_fetch_row($busca_contrato);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_contrato[0];
$numero_contrato_w = $aux_contrato[1];
$data_contrato_w = $aux_contrato[2];
$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
$data_vencimento_w = $aux_contrato[3];
$data_vencimento_print = date('d/m/Y', strtotime($aux_contrato[3]));
$cod_fornecedor_w = $aux_contrato[4];
$nome_fornecedor_w = $aux_contrato[5];
$cod_produto_w = $aux_contrato[6];
$nome_produto_w = $aux_contrato[7];
$valor_w = $aux_contrato[8];
$valor_print = "R$ " . number_format($aux_contrato[8],2,",",".");
$safra_w = $aux_contrato[9];
$filial_w = $aux_contrato[10];
$observacao_w = $aux_contrato[11];
$estado_registro_w = $aux_contrato[12];
$pendencia_assinatura_w = $aux_contrato[13];


$usuario_cadastro_w = $aux_contrato[14];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_contrato[15]));
$hora_cadastro_w = $aux_contrato[16];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_contrato[17];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_contrato[18]));
$hora_alteracao_w = $aux_contrato[19];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_contrato[20];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_contrato[21]));
$hora_exclusao_w = $aux_contrato[22];
$motivo_exclusao_w = $aux_contrato[23];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$cod_fornecedor_w'");
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
{$cpf_cnpj_print = "CPF: $cpf_pessoa";}
else
{$cpf_cnpj_print = "CNPJ: $cnpj_pessoa";}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ============================================================================================================


// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
$permite_visualizar = "SIM";
// ========================================================================================================


// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' Nome: $nome_pessoa &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; $cpf_cnpj_print &#13; N&uacute;mero Contrato: $numero_contrato_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

else
{echo "<tr class='tabela_4' title=' Nome: $nome_pessoa &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; $cpf_cnpj_print &#13; N&uacute;mero Contrato: $numero_contrato_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}


echo "
<td width='120px' align='center'>$data_contrato_print</td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_pessoa</div></td>
<td width='100px' align='center'>$numero_contrato_w</td>
<td width='200px' align='center'>$produto_print</td>
<td width='200px' align='right'><div style='height:14px; margin-right:7px'>$valor_print</div></td>
<td width='120px' align='center'>$data_vencimento_print</td>";

// ====== BOTAO VISUALIZAR ===============================================================================================
	if ($permite_visualizar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<div style='height:22px; margin-top:2px; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/contrato_visualizar.php' method='post'>
		<input type='hidden' name='modulo_mae' value='$modulo'>
		<input type='hidden' name='menu_mae' value='contrato_adiantamento'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='VISUALIZAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_contrato' value='$numero_contrato_w'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='filial_busca' value='$filial_busca'>
		<input type='hidden' name='status_busca' value='$status_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' />
		</form>
		</div>
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}

// =================================================================================================================


}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_contrato == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum contrato encontrado.</i></div>";}
// =================================================================================================================
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>