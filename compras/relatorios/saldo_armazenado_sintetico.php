<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "saldo_armazenado_sintetico";
$titulo = "Relat&oacute;rio de Saldo de Armazenado";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ======= RECEBE POST ============================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

if ($botao == "BUSCAR")
{
$cod_produto_busca = $_POST["cod_produto_busca"];
$saldo_busca = $_POST["saldo_busca"];
$ordenar_busca = $_POST["ordenar_busca"];
$filial_busca = $_POST["filial_busca"];
}

else
{
$cod_produto_busca = "GERAL";
$saldo_busca = "GERAL";
$ordenar_busca = "PRODUTOR";
$filial_busca = $filial;
}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "saldo_armazenado.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "saldo_armazenado.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if ($saldo_busca == "DEVEDOR")
	{$mysql_saldo_busca = "saldo_armazenado.saldo <= 0";
	$saldo_busca = $saldo_busca;}
elseif ($saldo_busca == "CREDOR")
	{$mysql_saldo_busca = "saldo_armazenado.saldo > 0";
	$saldo_busca = $saldo_busca;}
else
	{$mysql_saldo_busca = "saldo_armazenado.saldo IS NOT NULL";
	$saldo_busca = "GERAL";}


if ($ordenar_busca == "SALDO_MAIOR")
	{$mysql_ordenar_busca = "saldo_armazenado.saldo DESC";
	$ordenar_busca = $ordenar_busca;}
elseif ($ordenar_busca == "SALDO_MENOR")
	{$mysql_ordenar_busca = "saldo_armazenado.saldo ASC";
	$ordenar_busca = $ordenar_busca;}
else
	{$mysql_ordenar_busca = "saldo_armazenado.fornecedor_print";
	$ordenar_busca = "PRODUTOR";}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "saldo_armazenado.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "saldo_armazenado.filial='$filial_busca'";
	$filial_busca = $filial_busca;}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_saldo = mysqli_query ($conexao,
"SELECT
	cod_fornecedor,
	fornecedor_print,
	cod_produto,
	produto_print,
	unidade_print,
	saldo
FROM
	saldo_armazenado
WHERE
	$mysql_filial AND
	$mysql_cod_produto AND
	$mysql_saldo_busca
ORDER BY
	$mysql_ordenar_busca");


$busca_produto_distinct = mysqli_query ($conexao,
"SELECT DISTINCT
	saldo_armazenado.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem
FROM
	saldo_armazenado,
	cadastro_produto
WHERE
	($mysql_filial AND
	$mysql_cod_produto AND
	$mysql_saldo_busca) AND
	saldo_armazenado.cod_produto=cadastro_produto.codigo
ORDER BY
	saldo_armazenado.cod_produto");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_saldo = mysqli_num_rows ($busca_saldo);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
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
<?php include ("../../includes/javascript.php"); ?>
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
<?php include ("../../includes/submenu_compras_relatorios.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_fixo">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right">
	<?php 
    if ($linha_produto_distinct == 1)
    {echo "$linha_produto_distinct Produto";}
    elseif ($linha_produto_distinct > 1)
    {echo "$linha_produto_distinct Produtos";}
    else
    {echo "";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/saldo_armazenado_sintetico.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Filial:
    </div>
    
    <div class="pqa_campo">
    <select name="filial_busca" class="pqa_select" style="width:190px" />
    <?php
	include ("../../includes/filiais.php"); 
	
	if ($filial_busca == "GERAL")
	{echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
	else
	{echo "<option value='GERAL'>(TODAS)</option>";}
	
	for ($f=0 ; $f<=count($filiais) ; $f++)
	{
        if ($filiais[$f]["descricao"] == $filial_busca)
        {echo "<option selected='selected' value='" . $filiais[$f]["descricao"] . "'>" . $filiais[$f]["apelido"] . "</option>";}
        else
        {echo "<option value='" . $filiais[$f]["descricao"] . "'>" . $filiais[$f]["apelido"] . "</option>";}
	}

    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo">
    <button type='submit' class='botao_1' style='float:left'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo">
	<?php
	if ($linha_saldo >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/compras/relatorios/saldo_armazenado_sint_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='saldo_busca' value='$saldo_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='float:left'>Imprimir</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:20px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
$numero_divs = ceil($linha_produto_distinct / 3);
$altura_div = ($numero_divs * 120) . "px";

echo "<div style='height:$altura_div; width:1450px; border:1px solid transparent; margin:auto'>";


for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

$cod_produto_t = $aux_bp_distinct[0];
$produto_print_t = $aux_bp_distinct[1];
$unidade_print_t = $aux_bp_distinct[2];
$nome_imagem_produto_t = $aux_bp_distinct[3];

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}

// ===========================================================================================================
include ("../../includes/conecta_bd.php");

$soma_total_credor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND $mysql_cod_produto AND saldo > 0 AND cod_produto='$cod_produto_t'"));

$soma_total_devedor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo) FROM saldo_armazenado WHERE $mysql_filial AND $mysql_cod_produto AND saldo <= 0 AND cod_produto='$cod_produto_t'"));

include ("../../includes/desconecta_bd.php");
// ===========================================================================================================


// ===========================================================================================================
$total_credor_print = number_format($soma_total_credor[0],2,",",".") . " $unidade_print_t";
$total_devedor_print = number_format($soma_total_devedor[0],2,",",".") . " $unidade_print_t";

echo "
<div style='height:120px; width:480px; border:0px solid #000; float:left'>
<div class='total' style='height:80px; width:440px; margin-top:0px; border-radius:0px;' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:28px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:165px; height:15px; border:0px solid #999; font-size:12px; margin-top:35px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:205px; height:15px; color:#003466; font-size:12px; margin-top:20px'>Total Credor: $total_credor_print</div>
	<div class='total_valor' style='width:205px; height:15px; color:#FF0000; font-size:12px; margin-top:15px'>Total Devedor: $total_devedor_print</div>
</div>
</div>";

}

echo "</div>";
?>
<!-- ============================================================================================================= -->









</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>