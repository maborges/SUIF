<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "index_entrada";
$titulo = "Romaneios de Entrada";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
function ConverteData($data_x){
	if (strstr($data_x, "/"))
	{
	$d = explode ("/", $data_x);
	$rstData = "$d[2]-$d[1]-$d[0]";
	return $rstData;
	}
}
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);

if ($botao == "BUSCAR")
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
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
	$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "estoque.data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "estoque.fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "estoque.fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "estoque.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "estoque.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


$mysql_filial = "estoque.filial='$filial_usuario'";

$mysql_status = "estoque.estado_registro='ATIVO'";

$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_romaneio = mysqli_query ($conexao,
"SELECT
	estoque.codigo,
	estoque.numero_romaneio,
	estoque.fornecedor,
	estoque.data,
	estoque.produto,
	estoque.peso_inicial,
	estoque.peso_final,
	estoque.desconto_sacaria,
	estoque.desconto,
	estoque.quantidade,
	estoque.unidade,
	estoque.tipo_sacaria,
	estoque.movimentacao,
	estoque.placa_veiculo,
	estoque.motorista,
	estoque.observacao,
	estoque.usuario_cadastro,
	estoque.hora_cadastro,
	estoque.data_cadastro,
	estoque.usuario_alteracao,
	estoque.hora_alteracao,
	estoque.data_alteracao,
	estoque.filial,
	estoque.estado_registro,
	estoque.quantidade_prevista,
	estoque.quantidade_sacaria,
	estoque.numero_compra,
	estoque.motorista_cpf,
	estoque.num_romaneio_manual,
	estoque.filial_origem,
	estoque.quant_volume_sacas,
	estoque.cod_produto,
	estoque.usuario_exclusao,
	estoque.hora_exclusao,
	estoque.data_exclusao,
	cadastro_pessoa.nome,
	select_tipo_sacaria.descricao,
	select_tipo_sacaria.peso
FROM
	estoque,
	cadastro_pessoa,
	select_tipo_sacaria
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto) AND
	estoque.fornecedor=cadastro_pessoa.codigo AND
	estoque.tipo_sacaria=select_tipo_sacaria.codigo
ORDER BY
	estoque.codigo DESC");


$busca_produto_distinct = mysqli_query ($conexao,
"SELECT
	estoque.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(estoque.quantidade)
FROM
	estoque,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto) AND
	estoque.cod_produto=cadastro_produto.codigo
GROUP BY
	estoque.cod_produto
ORDER BY
	cadastro_produto.codigo");


$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT
	SUM(quantidade)
FROM
	estoque
WHERE
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto"));


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

if ($soma_romaneio[0] > 0)
{$soma_romaneio_print = "Total: " . number_format($soma_romaneio[0],2,",",".") . " KG";}
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
// ================================================================================================================
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
<?php include ("../../includes/menu_estoque.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_estoque_entrada.php"); ?>
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
    if ($linha_romaneio == 1)
    {echo "$linha_romaneio Romaneio";}
    elseif ($linha_romaneio > 1)
    {echo "$linha_romaneio Romaneios";}
    else
    {echo "";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo $msg; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php echo $soma_romaneio_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorio_periodo.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="nome_fornecedor" value="<?php echo"$nome_fornecedor"; ?>" />
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Data Inicial:
    </div>

    <div class="pqa_campo">
    <input type="text" name="data_inicial_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" value="<?php echo"$data_inicial_br"; ?>" style="width:100px" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= DATA FINAL ============================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Data Final:
    </div>

    <div class="pqa_campo">
    <input type="text" name="data_final_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" value="<?php echo"$data_final_br"; ?>" style="width:100px" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= PRODUTO =========================================================================================== -->
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
<div class="pqa_caixa" style="float:right">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo" style="margin-left:0px; margin-right:30px">
	<?php
	if ($linha_romaneio >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/relatorio_selec_fornecedor.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<button type='submit' class='botao_1' style='float:right'>Filtrar por Fornecedor</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div class="pqa_caixa" style="float:right">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo" style="margin-left:0px; margin-right:30px">
	<?php
	if ($linha_romaneio >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/buscar_romaneio.php' method='post' />
	<button type='submit' class='botao_1' style='float:right'>Buscar por N&uacute;mero</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->

	
</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ("include_totalizador.php"); ?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ("include_relatorio.php"); ?>
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
if ($linha_romaneio >= 1)
{include ("../../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>