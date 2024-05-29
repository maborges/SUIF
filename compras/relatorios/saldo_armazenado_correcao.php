<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "saldo_armazenado_correcao";
$titulo = "* Relat&oacute;rio de Saldo de Armazenado";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================

// ======= RECEBE POST ============================================================================================
$botao = $_POST["botao"];
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
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $_POST["filial_busca"];
}

else
{
$cod_produto_busca = "GERAL";
$filial_busca = $filial;
}

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

$mysql_filtro_data = "data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";

if ($cod_produto_busca == "" or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if ($filial_busca == "")
	{$mysql_filial = "filial='$filial'";
	$filial_busca = "$filial";}
elseif ($filial_busca == "GERAL")
	{$mysql_filial = "filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "filial='$filial_busca'";
	$filial_busca = $filial_busca;}
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  ===============================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ===============================================================================================================


// ====== BUSCA POR FORNECEDORES =================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT DISTINCT fornecedor FROM compras WHERE $mysql_filtro_data AND $mysql_filial AND estado_registro='ATIVO' AND $mysql_cod_produto ORDER BY fornecedor_print");
$linha_registro = mysqli_num_rows ($busca_registro);
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


// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 5000); // 5 Segundos


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
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo"Saldo de Armazenado <font style='color:#FF0000'>(Relat&oacute;rio Interno)</font>"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_registro == 1)
    {echo"$linha_registro Registro";}
    elseif ($linha_registro > 1)
    {echo"$linha_registro Registros";}
    else
    {echo"";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="font-size:10px">
	Calcula o saldo do produtor de forma geral e compara com o saldo do relat&oacute;rio da tabela saldo_armazenado, 
    mostrando apenas registros com diverg&ecirc;ncia.
    </div>

	<div class="ct_subtitulo_right" style="font-size:10px; color:#FF0000">
    Recomenda-se gerar o relat&oacute;rio por m&ecirc;s.
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa" style="width:1400px; height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/saldo_armazenado_correcao.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
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



</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->







<!-- ============================================================================================================= -->
<?php
if ($linha_registro == 0)
{echo "
<div style='height:200px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='120px'>C&oacute;digo do Produtor</td>
<td width='350px'>Produtor</td>
<td width='200px'>Produto</td>
<td width='160px'>Saldo Ficha (Correto)</td>
<td width='160px'>Saldo Relat&oacute;rio</td>
<td width='60px'>Ficha</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{

$aux_saldo = mysqli_fetch_row($busca_registro);
$fornecedor = $aux_saldo[0];


// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE fornecedor='$fornecedor' AND $mysql_cod_produto   AND $mysql_filial AND estado_registro='ATIVO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SAÍDA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE fornecedor='$fornecedor' AND $mysql_cod_produto   AND $mysql_filial AND estado_registro='ATIVO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0],2,",",".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto,2,",",".");




$busca_saldo_relat = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND $mysql_filial AND $mysql_cod_produto");
$aux_saldo_relat = mysqli_fetch_row($busca_saldo_relat);
$linha_saldo_relat = mysqli_num_rows ($busca_saldo_relat);

$saldo_x = $aux_saldo_relat[9];
$saldo_x_print = number_format($saldo_x,2,",",".");


if ($saldo_geral_produto_print != $saldo_x_print)
{


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_busca'");
$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[1];
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($saldo_geral_produto <= 0)
{echo "<tr class='tabela_5'>";}

else
{echo "<tr class='tabela_1'>";}

echo "
<td width='120px' align='left'><div style='margin-left:15px'>$fornecedor</div></td>
<td width='350px' align='left'><div style='height:14px; margin-left:15px; overflow:hidden'>$nome_pessoa</div></td>
<td width='200px' align='left'><div style='height:14px; margin-left:15px; overflow:hidden'>$produto_print</div></td>
<td width='160px' align='right'><div style='margin-right:15px'><b>$saldo_geral_produto_print</b></div></td>
<td width='160px' align='right'><div style='margin-right:15px'><b>$saldo_x_print</b></div></td>";

// ====== BOTAO VISUALIZAR ===============================================================================================
echo "
<td width='60px' align='center'>
<div style='height:22px; margin-top:2px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank' />
<input type='hidden' name='fornecedor' value='$fornecedor'>
<input type='hidden' name='botao' value='seleciona'>
<input type='hidden' name='cod_produto' value='$cod_produto_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' />
</form>
</div>
</td>";

// =================================================================================================================
}



}

echo "</tr></table>";
// =================================================================================================================



?>



</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>