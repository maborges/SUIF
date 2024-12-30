<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "relatorio_consolidado";
$titulo = "Relat&oacute;rio Consolidado de Compras";
$modulo = "compras";
$menu = "relatorios";
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

if ($botao == "BUSCAR")
{
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $_POST["filial_busca"];
}

else
{
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = "GERAL";
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

$mysql_filtro_data = "compras.data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "compras.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "compras.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "compras.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "compras.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


$mysql_status = "compras.estado_registro='ATIVO'";

$mysql_movimentacao = "compras.movimentacao='COMPRA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_compra = mysqli_query ($conexao, 
"SELECT 
	produto,
	SUM(quantidade),
	SUM(valor_total),
	unidade,
	filial
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_cod_produto
GROUP BY
	filial, cod_produto
ORDER BY 
	filial, cod_produto");


$busca_produto_distinct = mysqli_query ($conexao, 
"SELECT
	compras.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(compras.valor_total),
	SUM(compras.quantidade)
FROM 
	compras,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_cod_produto) AND
	compras.cod_produto=cadastro_produto.codigo
GROUP BY
	compras.cod_produto
ORDER BY
	compras.cod_produto");


$soma_compra = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT
	SUM(valor_total)
FROM
	compras
WHERE
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_cod_produto"));


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

if ($soma_compra[0] > 0)
{$soma_compra_print = "Total: R$ " . number_format($soma_compra[0],2,",",".");}
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo $titulo; ?>
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
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo $msg; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php echo $soma_compra_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_consolidado.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Data Inicial:
    </div>

    <div class="pqa_campo">
    <input type="text" name="data_inicial_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" value="<?php echo $data_inicial_br; ?>" style="width:100px" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Data Final:
    </div>

    <div class="pqa_campo">
    <input type="text" name="data_final_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" value="<?php echo $data_final_br; ?>" style="width:100px" />
    </div>
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
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/relatorios/relatorio_consolidado_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Imprimir</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->

	
</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
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
$soma_total_geral = $aux_bp_distinct[4];
$soma_quantidade_geral = $aux_bp_distinct[5];

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}


if ($soma_quantidade_geral <= 0)
{$media_produto_print = "R$ 0,00";}
else
{$media_produto = ($soma_total_geral / $soma_quantidade_geral);
$media_produto_print = "R$ " . number_format($media_produto,2,",",".");}

$soma_total_print = "R$ " . number_format($soma_total_geral,2,",",".");
$soma_quantidade_print = number_format($soma_quantidade_geral,2,",",".") . " $unidade_print_t";


echo "
<div style='height:50px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:4px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px'>Total: $soma_total_print</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:4px; color:#444'>
	Quant.: $soma_quantidade_print</div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px'>
	Pre&ccedil;o m&eacute;dio: $media_produto_print</div>

</div>
</div>";

}

echo "</div>";
?><!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:10px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_compra == 0)
{echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='250px'>Filial</td>
<td width='250px'>Produto</td>
<td width='160px'>Pre&ccedil;o M&eacute;dio</td>
<td width='200px'>Valor Total</td>
<td width='200px'>Quantidade</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$produto_print_w = $aux_compra[0];
$soma_quantidade_w = $aux_compra[1];
$soma_total_w = $aux_compra[2];
$unidade_w = $aux_compra[3];
$filial_w = $aux_compra[4];

$preco_medio = $soma_total_w / $soma_quantidade_w;

$quantidade_print = "<b>" . number_format($soma_quantidade_w,2,",",".") . " " . $unidade_w . "</b>";
$preco_medio_print = number_format($preco_medio,2,",",".");
$total_geral_print = "R$ " . number_format($soma_total_w,2,",",".");
// ======================================================================================================



// ====== RELATORIO =======================================================================================
echo "<tr class='tabela_1'>";


// =================================================================================================================
echo "
<td width='250px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$filial_w</div></td>
<td width='250px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='160px' align='right'><div style='height:14px; margin-right:10px'>$preco_medio_print</div></td>
<td width='200px' align='right'><div style='height:14px; margin-right:10px'>$total_geral_print</div></td>
<td width='200px' align='right'><div style='height:14px; margin-right:15px'>$quantidade_print</div></td>";
// =================================================================================================================

echo "</tr>";

}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_compra == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma compra nesse per&iacute;odo.</i></div>";}
// =================================================================================================================
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php
if ($linha_compra >= 1)
{include ("../../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>