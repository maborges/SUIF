<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "relatorio_periodo";
$titulo = "Relat&oacute;rio de Entradas";
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
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = "ATIVO";
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $filial_usuario;
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

$mysql_filtro_data = "compras.data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "compras.fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "compras.fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "compras.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "compras.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($cod_tipo_busca) or $cod_tipo_busca == "GERAL")
	{$mysql_cod_tipo = "compras.cod_tipo IS NOT NULL";
	$cod_tipo_busca = "GERAL";}
else
	{$mysql_cod_tipo = "compras.cod_tipo='$cod_tipo_busca'";
	$cod_tipo_busca = $cod_tipo_busca;}


if (empty($filial_busca))
	{$mysql_filial = "compras.filial='$filial_usuario'";
	$filial_busca = $filial_usuario;}
elseif ($filial_busca == "GERAL")
	{$mysql_filial = "compras.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "compras.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


$mysql_status = "compras.estado_registro='ATIVO'";

$mysql_movimentacao = "compras.movimentacao='ENTRADA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_entrada = mysqli_query ($conexao, 
"SELECT 
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	unidade,
	tipo,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	numero_romaneio,
	desconto_quantidade,
	usuario_exclusao,
	hora_exclusao,
	data_exclusao,
	cod_produto,
	fornecedor_print,
	quantidade_original_primaria
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
ORDER BY 
	codigo");


$busca_produto_distinct = mysqli_query ($conexao, 
"SELECT
	compras.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(compras.quantidade),
	SUM(compras.desconto_quantidade)
FROM 
	compras,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo) AND
	compras.cod_produto=cadastro_produto.codigo
GROUP BY
	compras.cod_produto
ORDER BY
	compras.cod_produto");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_entrada = mysqli_num_rows ($busca_entrada);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
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
    if ($linha_entrada == 1)
    {echo "$linha_entrada Entrada";}
    elseif ($linha_entrada > 1)
    {echo "$linha_entrada Entradas";}
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
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/entradas/relatorio_periodo.php" method="post" />
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
    Tipo:
    </div>
    
    <div class="pqa_campo">
    <select name="cod_tipo_busca" class="pqa_select" style="width:190px" />
    <?php
	include ("../../includes/select_tipo_produto.php"); 

    if ($cod_tipo_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
    else
    {echo "<option value='GERAL'>(TODOS)</option>";}

	for ($t=0 ; $t<=count($select_tipo_produto) ; $t++)
    {
	if ($select_tipo_produto[$t]["cod_produto"] == $cod_produto_busca)
		{
			if ($select_tipo_produto[$t]["codigo"] == $cod_tipo_busca)
			{echo "<option selected='selected' value='" . $select_tipo_produto[$t]["codigo"] . "'>" . $select_tipo_produto[$t]["descricao"] . "</option>";}
			else
			{echo "<option value='" . $select_tipo_produto[$t]["codigo"] . "'>" . $select_tipo_produto[$t]["descricao"] . "</option>";}
		}
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

	
</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1450px; height:40px; border:1px solid transparent; margin:auto">


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_entrada >= 1)
    {echo"
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona.php'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Nova Entrada</button>
	</a>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_entrada >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/compras/entradas/relatorio_selec_fornecedor.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Filtrar por Fornecedor</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_entrada >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/entradas/relatorio_periodo_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Imprimir</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->



</div>
<!-- ============================================================================================================= -->


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
$soma_quantidade_geral = $aux_bp_distinct[4];
$soma_desconto_geral = $aux_bp_distinct[5];

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}


$soma_quantidade_bruta = $soma_quantidade_geral + $soma_desconto_geral;

$soma_quantidade_print = number_format($soma_quantidade_geral,2,",",".") . " $unidade_print_t";
$soma_desconto_print = number_format($soma_desconto_geral,2,",",".") . " $unidade_print_t";
$soma_quant_bruta_print = number_format($soma_quantidade_bruta,2,",",".") . " $unidade_print_t";


echo "
<div style='height:50px; width:460px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:430px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:4px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:200px; height:15px; border:0px solid #999; font-size:11px; margin-top:4px'>Quant. Bruta: $soma_quant_bruta_print</div>
	<div class='total_nome' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px; color:#444'>
	Desconto: $soma_desconto_print</div>
	<div class='total_valor' style='width:200px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px'>
	Quant. L&iacute;quida: $soma_quantidade_print</div>

</div>
</div>";

}

echo "</div>";
?><!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<?php
if ($linha_entrada == 0)
{echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Visualizar</td>
<td width='100px'>Data</td>
<td width='350px'>Fornecedor</td>
<td width='100px'>N&ordm; Entrada</td>
<td width='100px'>N&ordm; Romaneio</td>
<td width='170px'>Produto</td>
<td width='150px'>Tipo</td>
<td width='140px'>Quantidade Bruta</td>
<td width='100px'>Desconto</td>
<td width='140px'>Quantidade L&iacute;quida</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_entrada ; $x++)
{
$aux_entrada = mysqli_fetch_row($busca_entrada);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_entrada[0];
$numero_compra_w = $aux_entrada[1];
$cod_fornecedor_w = $aux_entrada[2];
$produto_print_w = $aux_entrada[3];
$data_compra_w = $aux_entrada[4];
$quantidade_w = $aux_entrada[5];
$unidade_w = $aux_entrada[6];
$tipo_w = $aux_entrada[7];
$observacao_w = $aux_entrada[8];
$usuario_cadastro_w = $aux_entrada[9];
$hora_cadastro_w = $aux_entrada[10];
$data_cadastro_w = $aux_entrada[11];
$usuario_alteracao_w = $aux_entrada[12];
$hora_alteracao_w = $aux_entrada[13];
$data_alteracao_w = $aux_entrada[14];
$estado_registro_w = $aux_entrada[15];
$filial_w = $aux_entrada[16];
$numero_romaneio_w = $aux_entrada[17];
$desconto_quantidade_w = $aux_entrada[18];
$usuario_exclusao_w = $aux_entrada[19];
$hora_exclusao_w = $aux_entrada[20];
$data_exclusao_w = $aux_entrada[21];
$cod_produto_w = $aux_entrada[22];
$fornecedor_print_w = $aux_entrada[23];
$quantidade_original_primaria_w = $aux_entrada[24];


$quantidade_bruta = $quantidade_w + $desconto_quantidade_w;

$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " $unidade_w";
$desconto_quantidade_print = number_format($desconto_quantidade_w,2,",",".") . " $unidade_w";
$quantidade_bruta_print = number_format($quantidade_bruta,2,",",".") . " $unidade_w";


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}
// ======================================================================================================



// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "INATIVO")
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13 Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

elseif ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13 Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
else
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13 Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}



// ====== BOTAO VISUALIZAR ==================================================================================
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/compras/compras/compra_visualizar.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='VISUALIZAR'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_compra' value='$numero_compra_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
// =================================================================================================================


// =================================================================================================================
echo "
<td width='100px' align='center'>$data_compra_print</td>
<td width='350px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='100px' align='center'>$numero_compra_w</td>
<td width='100px' align='center'>$numero_romaneio_w</td>
<td width='170px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$tipo_w</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:10px'>$quantidade_bruta_print</div></td>
<td width='100px' align='right'><div style='height:14px; margin-right:10px; color:#CD0000'>$desconto_quantidade_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$quantidade_print</div></td>";
// =================================================================================================================

echo "</tr>";

}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_entrada == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma entrada encontrada.</i></div>";}
// =================================================================================================================
?>


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
if ($linha_entrada >= 1)
{include ("../../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>