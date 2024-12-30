<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "index_transferencia";
$titulo = "Transfer&ecirc;ncias";
$modulo = "compras";
$menu = "ficha_produtor";
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
$movimentacao_busca = $_POST["movimentacao_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = $_POST["status_busca"];
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$movimentacao_busca = "GERAL";
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


if (empty($movimentacao_busca) or $movimentacao_busca == "GERAL")
	{$mysql_movimentacao = "compras.movimentacao LIKE '%TRANSFERENCIA%'";
	$movimentacao_busca = "GERAL";}
else
	{$mysql_movimentacao = "compras.movimentacao='$movimentacao_busca'";
	$movimentacao_busca = $movimentacao_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "compras.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "compras.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


if (empty($status_busca) or $status_busca == "GERAL")
	{$mysql_status = "compras.estado_registro IS NOT NULL";
	$status_busca = "GERAL";}
else
	{$mysql_status = "compras.estado_registro='$status_busca'";
	$status_busca = $status_busca;}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_compra = mysqli_query ($conexao,
"SELECT
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	unidade,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	numero_transferencia,
	fornecedor_print,
	movimentacao
FROM
	compras
WHERE
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto
ORDER BY
	codigo");


$busca_produto_distinct = mysqli_query ($conexao,
"SELECT
	compras.cod_produto,
	cadastro_produto.descricao,
	cadastro_produto.unidade_print,
	cadastro_produto.nome_imagem,
	SUM(compras.quantidade)
FROM
	compras,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao AND
	$mysql_fornecedor AND
	$mysql_cod_produto) AND
	compras.cod_produto=cadastro_produto.codigo
GROUP BY
	compras.cod_produto
ORDER BY
	compras.cod_produto");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
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
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
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
    if ($linha_compra == 1)
    {echo "$linha_compra Transfer&ecirc;ncia";}
    elseif ($linha_compra > 1)
    {echo "$linha_compra Transfer&ecirc;ncias";}
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
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/transferencias/relatorio_transferencia.php" method="post" />
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


 <!-- ======= MOVIMENTAÇÃO =========================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Movimenta&ccedil;&atilde;o:
    </div>
    
    <div class="pqa_campo">
    <select name="movimentacao_busca" class="pqa_select" style="width:190px" />
    <?php
    if ($movimentacao_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
    else
    {echo "<option value='GERAL'>(TODAS)</option>";}

    if ($movimentacao_busca == "TRANSFERENCIA_ENTRADA")
    {echo "<option selected='selected' value='TRANSFERENCIA_ENTRADA'>Transfer&ecirc;ncia Entrada</option>";}
    else
    {echo "<option value='TRANSFERENCIA_ENTRADA'>Transfer&ecirc;ncia Entrada</option>";}

    if ($movimentacao_busca == "TRANSFERENCIA_SAIDA")
    {echo "<option selected='selected' value='TRANSFERENCIA_SAIDA'>Transfer&ecirc;ncia Sa&iacute;da</option>";}
    else
    {echo "<option value='TRANSFERENCIA_SAIDA'>Transfer&ecirc;ncia Sa&iacute;da</option>";}
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


 <!-- ======= STATUS REGISTRO ======================================================================================= -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Status dos Registros:
    </div>
    
    <div class="pqa_campo">
    <select name="status_busca" class="pqa_select" style="width:160px" />
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
    echo"
	<a href='$servidor/$diretorio_servidor/compras/transferencias/cadastro_1_selec_produto.php'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Nova Transfer&ecirc;ncia</button>
	</a>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_compra >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/compras/transferencias/transferencia_selec_fornecedor.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
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
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/transferencias/relatorio_transferencia_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
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

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}

$soma_quantidade_print = number_format($soma_quantidade_geral,2,",",".") . " $unidade_print_t";


echo "
<div style='height:42px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:12px; margin-top:14px'>$soma_quantidade_print</div>

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
if ($linha_compra == 0)
{echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Visualizar</td>
<td width='110px'>Data</td>
<td width='360px'>Fornecedor</td>
<td width='110px'>N&uacute;mero</td>
<td width='170px'>Produto</td>
<td width='110px'>Quantidade</td>
<td width='200px'>Movimenta&ccedil;&atilde;o</td>
<td width='180px'>Usu&aacute;rio</td>
<td width='120px'>Filial</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_compra[0];
$numero_compra_w = $aux_compra[1];
$fornecedor_w = $aux_compra[2];
$produto_w = $aux_compra[3];
$data_compra_w = $aux_compra[4];
$quantidade_w = $aux_compra[5];
$unidade_w = $aux_compra[6];
$observacao_w = $aux_compra[7];
$usuario_cadastro_w = $aux_compra[8];
$hora_cadastro_w = $aux_compra[9];
$data_cadastro_w = $aux_compra[10];
$usuario_alteracao_w = $aux_compra[11];
$hora_alteracao_w = $aux_compra[12];
$data_alteracao_w = $aux_compra[13];
$estado_registro_w = $aux_compra[14];
$filial_w = $aux_compra[15];
$numero_transferencia_w = $aux_compra[16];
$fornecedor_print_w = $aux_compra[17];
$movimentacao_w = $aux_compra[18];

$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " $unidade_w";

if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "ATIVO")
{
if ($movimentacao_w == "TRANSFERENCIA_ENTRADA")
{echo "<tr class='tabela_1' title=' N&uacute;mero Transfer&ecirc;ncia: $numero_transferencia_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

elseif ($movimentacao_w == "TRANSFERENCIA_SAIDA")
{echo "<tr class='tabela_7' title=' N&uacute;mero Transfer&ecirc;ncia: $numero_transferencia_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

else
{echo "<tr class='tabela_6' title=' N&uacute;mero Transfer&ecirc;ncia: $numero_transferencia_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

}

else
{echo "<tr class='tabela_4' title=' N&uacute;mero Transfer&ecirc;ncia: $numero_transferencia_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}


// =================================================================================================================
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/compras/transferencias/transferencia_visualizar.php' method='post'>
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='transferencias'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='VISUALIZAR'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_compra' value='$numero_compra_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='hidden' name='status_busca' value='$status_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";


echo "
<td width='110px' align='center'>$data_compra_print</td>
<td width='360px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='110px' align='center'>$numero_compra_w</td>
<td width='170px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_w</div></td>
<td width='110px' align='center'>$quantidade_print</td>
<td width='200px' align='left'><div style='margin-left:7px'>$movimentacao_w</div></td>
<td width='180px' align='left'><div style='margin-left:7px'>$usuario_cadastro_w</div></td>
<td width='120px' align='left'><div style='margin-left:7px'>$filial_w</div></td>";
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
<i>Nenhuma transfer&ecirc;ncia encontrada.</i></div>";}
// =================================================================================================================
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:10px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1450px; height:30px; border:1px solid transparent; margin:auto; font-size:11px">
<?php
if ($linha_compra >= 1)
	{echo "
	<div style='width:10px; height:10px; float:left; background-color:#CD0000; margin-top:2px'></div>
    <div style='width:200px; float:left; color:#CD0000; margin-left:8px'>TRANSFER&Ecirc;NCIA SA&Iacute;DA</div>
	<div style='width:10px; height:10px; float:left; background-color:#003466; margin-top:2px'></div>
    <div style='width:200px; float:left; color:#003466; margin-left:8px'>TRANSFER&Ecirc;NCIA ENTRADA</div>";}
?>
</div>
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