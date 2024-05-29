<?php
include ("../includes/config.php"); 
include ("../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "index_financeiro";
$titulo = "Financeiro";
$modulo = "financeiro";
$menu = "contas_pagar";
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

$numero_compra = $_POST["numero_compra"];
$total_pago = $_POST["total_pago"];
$saldo_pagar = $_POST["saldo_pagar"];
$valor_compra = $_POST["valor_compra"];

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
// ================================================================================================================


// ================================================================================================================
if (!empty($numero_compra))
{
include ("../includes/conecta_bd.php");
$soma_pgto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra'"));
include ("../includes/desconecta_bd.php");

// Calcula o saldo a pagar da compra
$total_pago_atual = $soma_pgto[0];
$saldo_pagar_atual = $valor_compra - $soma_pgto[0];

if ($saldo_pagar_atual == 0)
{$situacao_pagamento = "PAGO";}
else
{$situacao_pagamento = "EM_ABERTO";}


	include ("../includes/conecta_bd.php");
	$editar_compra = mysqli_query ($conexao, 
	"UPDATE
		compras
	SET
		situacao_pagamento='$situacao_pagamento',
		total_pago='$total_pago_atual',
		saldo_pagar='$saldo_pagar_atual'
	WHERE
		numero_compra='$numero_compra'");
	include ("../includes/desconecta_bd.php");

}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../includes/conecta_bd.php");


$busca_compra = mysqli_query ($conexao, 
"SELECT 
	codigo,
	numero_compra,
	fornecedor,
	produto,
	data_compra,
	quantidade,
	preco_unitario,
	valor_total,
	unidade,
	tipo,
	observacao,
	data_pagamento,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao,
	estado_registro,
	filial,
	fornecedor_print,
	forma_entrega,
	usuario_exclusao,
	hora_exclusao,
	data_exclusao,
	situacao_pagamento,
	total_pago,
	saldo_pagar
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	movimentacao='COMPRA' AND
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
	SUM(compras.valor_total),
	SUM(compras.quantidade)
FROM 
	compras,
	cadastro_produto
WHERE
	($mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	movimentacao='COMPRA'AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo) AND
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
	movimentacao='COMPRA' AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo"));


include ("../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

if ($soma_compra[0] > 0)
{$soma_compra_print = "Total: R$ " . number_format($soma_compra[0],2,",",".");}
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
$msg = $soma_pgto[0];
// ================================================================================================================


// ================================================================================================================
include ("../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../includes/submenu_compras_relatorios.php"); ?>
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
    {echo "$linha_compra Compra";}
    elseif ($linha_compra > 1)
    {echo "$linha_compra Compras";}
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
    <?php echo $soma_compra_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/update.php" method="post" />
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
	include ("../includes/cadastro_produto.php"); 

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
	include ("../includes/select_tipo_produto.php"); 

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
	include ("../includes/filiais.php"); 
	
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
	echo"
	<a href='$servidor/$diretorio_servidor/compras/produtos/cadastro_1_selec_produto.php'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Nova Compra</button>
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
	<form action='$servidor/$diretorio_servidor/compras/relatorios/relatorio_selec_fornecedor.php' method='post' />
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
	/*
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo_impressao.php' target='_blank' method='post' />
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
	*/
	?>
    </div>
</div>
<!-- ================================================================================================================ -->



</div>
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
<td width='120px'>Data</td>
<td width='300px'>Fornecedor</td>
<td width='120px'>N&uacute;mero</td>
<td width='180px'>Produto</td>
<td width='120px'>Situacao</td>
<td width='140px'>Valor Total</td>
<td width='140px'>Soma Pago</td>
<td width='140px'>Total Pago</td>
<td width='140px'>Saldo a Pagar</td>
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
$cod_fornecedor_w = $aux_compra[2];
$produto_print_w = $aux_compra[3];
$data_compra_w = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$quantidade_w = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario_w = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$total_geral_w = $aux_compra[7];
$total_geral_print = "R$ " . number_format($aux_compra[7],2,",",".");
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$data_pagamento_w = $aux_compra[11];
$data_pagamento_print = date('d/m/Y', strtotime($aux_compra[11]));
$usuario_cadastro_w = $aux_compra[12];
$hora_cadastro_w = $aux_compra[13];
$data_cadastro_w = $aux_compra[14];
$usuario_alteracao_w = $aux_compra[15];
$hora_alteracao_w = $aux_compra[16];
$data_alteracao_w = $aux_compra[17];
$estado_registro_w = $aux_compra[18];
$filial_w = $aux_compra[19];
$fornecedor_print_w = $aux_compra[20];
$forma_entrega_w = $aux_compra[21];
$usuario_exclusao_w = $aux_compra[22];
$hora_exclusao_w = $aux_compra[23];
$data_exclusao_w = $aux_compra[24];
$situacao_pagamento_w = $aux_compra[25];
$total_pago_w = $aux_compra[26];
$saldo_pagar_w = $aux_compra[27];


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}



include ("../includes/conecta_bd.php");
$soma_pgto_x = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra_w'"));
include ("../includes/desconecta_bd.php");

if ($soma_pgto_x[0] == 0 or $soma_pgto_x[0] == 0.00 or empty($soma_pgto_x[0]))
{$soma_pgto_z = 0;}
else
{$soma_pgto_z = $soma_pgto_x[0];}

if ($total_pago_w == 0 or $total_pago_w == 0.00 or empty($total_pago_w))
{$total_pago_z = 0;}
else
{$total_pago_z = $total_pago_w;}


$total_comparacao = $total_pago_z + $saldo_pagar_w;
// ======================================================================================================


// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
$permite_visualizar = "SIM";
// ========================================================================================================


// ====== RELATORIO =======================================================================================
if ($soma_pgto_z <> $total_pago_z)
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w &#13;&#13; Soma PGTO: $soma_pgto_z &#13;&#13; Total PGTO: $total_pago_z'>";}

elseif ($total_comparacao <> $total_geral_w)
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w &#13;&#13; Soma PGTO: $soma_pgto_z &#13;&#13; Total PGTO: $total_pago_z'>";}

else
{}


// ====== BOTAO VISUALIZAR ==================================================================================
if ($total_comparacao <> $total_geral_w)
{	
	echo "
	<td width='60px' align='center'>
	<div style='height:24px; margin-top:0px; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/financeiro/update.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='numero_compra' value='$numero_compra_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='numero_venda_busca' value='$numero_compra_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='total_pago' value='$total_pago_w'>
	<input type='hidden' name='saldo_pagar' value='$saldo_pagar_w'>
	<input type='hidden' name='valor_compra' value='$total_geral_w'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
	</form>
	</div>
	</td>";
}
// =================================================================================================================


// =================================================================================================================
if ($total_comparacao <> $total_geral_w)
{	
echo "
<td width='120px' align='center'>$data_compra_print</td>
<td width='300px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='120px' align='center'>$numero_compra_w</td>
<td width='180px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:10px'>$situacao_pagamento_w</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$total_geral_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$soma_pgto_z</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$total_pago_w</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$saldo_pagar_w</div></td>";

echo "</tr>";
}
// =================================================================================================================


}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_compra == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma compra encontrada.</i></div>";}
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
{include ("../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../includes/desconecta_bd.php"); ?>
</body>
</html>