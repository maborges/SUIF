<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "saldo_armazenado_analitico";
$titulo = "Relat&oacute;rio de Saldo de Armazenado";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ======= RECEBE POST ============================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$objDataCorrente = new DateTime();
$filial = $filial_usuario;
$aux_filial_dt_compra = "";

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
	$filial_busca = "GERAL";
	$filial_print = "Filial: TODAS";
    $aux_filial_dt_compra = "filial IS NOT NULL";}
else
	{$mysql_filial = "saldo_armazenado.filial='$filial_busca'";
	$filial_busca = $filial_busca;
	$filial_print = "Filial: " . $filial_busca;
	$aux_filial_dt_compra = "filial='$filial_busca'";}
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
<div class="ct_auto">


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
    if ($linha_saldo == 1)
    {echo "$linha_saldo Registro";}
    elseif ($linha_saldo > 1)
    {echo "$linha_saldo Registros";}
    else
    {echo "";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	Saldo dos Produtores
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/saldo_armazenado_analitico.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Produto:
    </div>
    
    <div class="pqa_campo">
    <select name="cod_produto_busca" class="pqa_select" style="width:190px" />
    <?php
	include ("../../includes/cadastro_produto.php"); 

	if ($cod_produto_busca == "GERAL")
	{echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
	else
	{echo "<option value='GERAL'>(TODOS)</option>";}
	
	for ($i=0 ; $i<=count($cadastro_produto) ; $i++)
	{
        if ($cadastro_produto[$i]["codigo"] == $cod_produto_busca)
        {echo "<option selected='selected' value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
        else
        {echo "<option value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
	}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Saldo:
    </div>
    
    <div class="pqa_campo">
    <select name="saldo_busca" class="pqa_select" style="width:160px" />
    <?php
	if ($saldo_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(TODOS)</option>";}
	else
	{echo "<option value='GERAL'>(TODOS)</option>";}

	if ($saldo_busca == "DEVEDOR")
	{echo "<option value='DEVEDOR' selected='selected'>Devedor</option>";}
	else
	{echo "<option value='DEVEDOR'>Devedor</option>";}

	if ($saldo_busca == "CREDOR")
	{echo "<option value='CREDOR' selected='selected'>Credor</option>";}
	else
	{echo "<option value='CREDOR'>Credor</option>";}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Ordenar por:
    </div>
    
    <div class="pqa_campo">
    <select name="ordenar_busca" class="pqa_select" style="width:160px" />
    <?php
	if ($ordenar_busca == "PRODUTOR")
	{echo "<option value='PRODUTOR' selected='selected'>Nome do Produtor</option>";}
	else
	{echo "<option value='PRODUTOR'>Nome do Produtor</option>";}

	if ($ordenar_busca == "SALDO_MAIOR")
	{echo "<option value='SALDO_MAIOR' selected='selected'>Saldo (do maior para o menor)</option>";}
	else
	{echo "<option value='SALDO_MAIOR'>Saldo (do maior para o menor)</option>";}

	if ($ordenar_busca == "SALDO_MENOR")
	{echo "<option value='SALDO_MENOR' selected='selected'>Saldo (do menor para o maior)</option>";}
	else
	{echo "<option value='SALDO_MENOR'>Saldo (do menor para o maior)</option>";}
    ?>
    </select>
    </div>
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
	<form action='$servidor/$diretorio_servidor/compras/relatorios/saldo_armazenado_an_impressao.php' target='_blank' method='post'>
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
$altura_div = ($numero_divs * 50) . "px";

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
<div style='height:50px; width:480px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:430px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:200px; height:15px; color:#003466; font-size:11px; margin-top:3px'>Total Credor: $total_credor_print</div>
	<div class='total_valor' style='width:200px; height:15px; color:#FF0000; font-size:11px; margin-top:3px'>Total Devedor: $total_devedor_print</div>
</div>
</div>";
// ===========================================================================================================
}

echo "</div>";
?>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:10px"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php
if ($linha_saldo == 0)
{echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Ficha</td>
<td width='120px'>C&oacute;digo do Produtor</td>
<td width='350px'>Produtor</td>
<td width='200px'>Produto</td>
<td width='150px'>Saldo</td>
<td width='150px'>Data Ultima Compra</td>
<td width='140px'>Dias em Atraso</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_saldo ; $x++)
{
$aux_saldo = mysqli_fetch_row($busca_saldo);

// ====== DADOS DO CADASTRO ============================================================================
$cod_fornecedor_w = $aux_saldo[0];
$nome_pessoa_w = $aux_saldo[1];
$cod_produto_w = $aux_saldo[2];
$produto_print_w = $aux_saldo[3];
$unidade_print_w = $aux_saldo[4];
$saldo_w = $aux_saldo[5];
$saldo_print = number_format($aux_saldo[5],2,",",".");
// ======================================================================================================

// ====== BUSCA DATA DA ULTIMA COMPRA =========================================================================================

include ("../../includes/conecta_bd.php");

$busca_data_ultima_compra = mysqli_query ($conexao,
"SELECT MAX(data_compra)  
   FROM compras
  WHERE estado_registro != 'EXCLUIDO' 
    AND cod_produto      = $cod_produto_w
    AND fornecedor       = $cod_fornecedor_w
    AND $aux_filial_dt_compra
    AND movimentacao     = 'COMPRA'");

$result_data_ultima_compra = mysqli_fetch_row($busca_data_ultima_compra);
$semaforo   = 'badge-transparent';
$diasAtraso = 0;
$diasAtraso_w = '';

if (!is_null($result_data_ultima_compra[0])) {
  	$data_ultima_compra = date('d/m/Y', strtotime($result_data_ultima_compra[0]));

	if ($saldo_w < 0) {
		$diasAtraso = $objDataCorrente->diff(new DateTime($result_data_ultima_compra[0]))->days; 
		$diasAtraso_w = (string) $diasAtraso;

		if ($diasAtraso <= 30) 
			$semaforo = 'badge-success'; 
		elseif ($diasAtraso <= 60) 
			$semaforo = 'badge-warning'; 
		else 
			$semaforo = 'badge-danger';
  	}
		
}
else
  	$data_ultima_compra =  "";
  
include ("../../includes/desconecta_bd.php");



// echo "<script>console.log('Debug Objects: " . $aux_filial_dt_compra . "' );</script>";
// echo '<script>console.log($aux_filial_dt_compra)</script>';

 // ====== BLOQUEIO PARA VISUALIZAR ========================================================================
$permite_visualizar = "SIM";
// ========================================================================================================


// ====== RELATORIO =======================================================================================
if ($saldo_w <= 0)
{echo "<tr class='tabela_5' title=' ID: $id_w &#13; Filial: $filial_w'>";}

else
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Filial: $filial_w'>";}


// ====== BOTAO VISUALIZAR ===============================================================================================
if ($permite_visualizar == "SIM")
{	
	echo "
	<td width='60px' align='center'>
	<div style='height:22px; margin-top:2px; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank' />
	<input type='hidden' name='fornecedor' value='$cod_fornecedor_w'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='hidden' name='cod_produto' value='$cod_produto_w'>
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


echo "
<td width='120px' align='left'><div style='margin-left:15px'>$cod_fornecedor_w</div></td>
<td width='350px' align='left'><div style='height:14px; margin-left:15px; overflow:hidden'>$nome_pessoa_w</div></td>
<td width='200px' align='left'><div style='height:14px; margin-left:15px; overflow:hidden'>$produto_print_w</div></td>
<td width='150px' align='right'><div style='margin-right:15px'><b>$saldo_print $unidade_print_w</b></div></td>
<td width='150px' align='right'><div style='margin-right:15px'><b>$data_ultima_compra</b></div></td>
<td width='140px' align='center'><div style='margin-right:15px'><span class='badge $semaforo'>$diasAtraso_w</span></div></td>";




echo "</tr>";

}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_saldo == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum saldo encontrado.</i></div>";}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php
if ($linha_saldo >= 1)
{include ("../../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>