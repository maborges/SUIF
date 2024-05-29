<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "contas_pagar_periodo";
$titulo = "Contas a Pagar";
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

$id_pgto = $_POST["id_pgto"];

if (empty($botao))
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$status_pgto_busca = "EM_ABERTO";
$filial_busca = $filial_usuario;
$ordenar_busca = "DATA";
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$status_pgto_busca = $_POST["status_pgto_busca"];
$filial_busca = $_POST["filial_busca"];
$ordenar_busca = $_POST["ordenar_busca"];
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
	{$mysql_cod_produto = "(compras.cod_produto IS NOT NULL OR compras.cod_produto IS NULL)";
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


if (empty($status_pgto_busca) or $status_pgto_busca == "GERAL")
	{$mysql_status_pgto = "compras.situacao_pagamento IS NOT NULL";
	$status_pgto_busca = "GERAL";}
else
	{$mysql_status_pgto = "compras.situacao_pagamento='$status_pgto_busca'";
	$status_pgto_busca = $status_pgto_busca;}


if ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "compras.fornecedor_print";}
else
	{$mysql_ordenar_busca = "compras.codigo";}


$mysql_status = "compras.estado_registro='ATIVO'";

$mysql_movimentacao = "compras.movimentacao='COMPRA'";
// ================================================================================================================


// ====== ATUALIZA CADASTROS ======================================================================================
/*
include ("../../includes/conecta_bd.php");

$busca_compra_update = mysqli_query ($conexao, 
"SELECT 
	numero_compra,
	valor_total,
	situacao_pagamento,
	total_pago,
	saldo_pagar
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_movimentacao
ORDER BY 
	$mysql_ordenar_busca");

$linha_compra_update = mysqli_num_rows ($busca_compra_update);

for ($up=1 ; $up<=$linha_compra_update ; $up++)
{
$aux_compra_update = mysqli_fetch_row($busca_compra_update);

$numero_compra_up = $aux_compra_update[0];
$valor_total_up = $aux_compra_update[1];
$situacao_pagamento_up = $aux_compra_update[2];
$total_pago_up = $aux_compra_update[3];
$saldo_pagar_up = $aux_compra_update[4];

	$soma_pgto_up = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra_up' AND estado_registro='ATIVO'"));

	if ($soma_pgto_up[0] != $total_pago_up)
	{
		if ($soma_pgto_up[0] == $valor_total_up)
		{
			$novo_saldo_pagar = $valor_total_up - $soma_pgto_up[0];
			$atualiza_saldo = mysqli_query ($conexao, "UPDATE compras SET total_pago='$soma_pgto_up[0]', saldo_pagar='$novo_saldo_pagar', situacao_pagamento='PAGO' 
			WHERE numero_compra='$numero_compra_up'");
		}
		
		else
		{
			$novo_saldo_pagar = $valor_total_up - $soma_pgto_up[0];
			$atualiza_saldo = mysqli_query ($conexao, "UPDATE compras SET total_pago='$soma_pgto_up[0]', saldo_pagar='$novo_saldo_pagar', situacao_pagamento='EM_ABERTO' 
			WHERE numero_compra='$numero_compra_up'");
		}
	}
	
} // FIM DO FOR

include ("../../includes/desconecta_bd.php");
*/
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
	preco_unitario,
	valor_total,
	unidade,
	tipo,
	observacao,
	situacao_pagamento,
	movimentacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	estado_registro,
	filial,
	cod_produto,
	fornecedor_print,
	total_pago,
	saldo_pagar
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	$mysql_movimentacao
ORDER BY 
	$mysql_ordenar_busca");


$soma_total_geral = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor_total) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	$mysql_movimentacao"));

/*
$soma_total_pago = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(total_pago) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	$mysql_movimentacao"));


$soma_saldo_pagar = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(saldo_pagar) 
FROM 
	compras 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	$mysql_movimentacao"));
*/

$soma_total_pago = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(favorecidos_pgto.valor) 
FROM 
	compras,
	favorecidos_pgto
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	$mysql_movimentacao AND
	favorecidos_pgto.estado_registro='ATIVO' AND 
	compras.numero_compra=favorecidos_pgto.codigo_compra"));


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_pago_print = "R$ " . number_format($soma_total_pago[0],2,",",".");
$soma_saldo_aux = ($soma_total_geral[0] - $soma_total_pago[0]);
$soma_saldo_print = "R$ " . number_format($soma_saldo_aux,2,",",".");
$soma_total_print = "R$ " . number_format($soma_total_geral[0],2,",",".");

$soma_x1_print = "R$ " . number_format($soma_total_pg[0],2,",",".");
$soma_x2_print = "R$ " . number_format($soma_total_pg[1],2,",",".");
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
<?php include ("../../includes/menu_financeiro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_financeiro_contas_pagar.php"); ?>
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
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/contas_pagar_periodo.php" method="post" />
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
    Status do Pagamento:
    </div>
    
    <div class="pqa_campo">
    <select name="status_pgto_busca" class="pqa_select" style="width:140px" />
    <?php
	if ($status_pgto_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
	else
	{echo "<option value='GERAL'>(GERAL)</option>";}

	if ($status_pgto_busca == "EM_ABERTO")
	{echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
	else
	{echo "<option value='EM_ABERTO'>Em Aberto</option>";}

	if ($status_pgto_busca == "PAGO")
	{echo "<option value='PAGO' selected='selected'>Pago</option>";}
	else
	{echo "<option value='PAGO'>Pago</option>";}

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
    <select name="filial_busca" class="pqa_select" style="width:140px" />
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


 <!-- ======= ORDENAR POR =========================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Ordenar por:
    </div>
    
    <div class="pqa_campo">
    <select name="ordenar_busca" class="pqa_select" style="width:170px" />
    <?php
	if ($ordenar_busca == "DATA")
	{echo "<option value='DATA' selected='selected'>Data e Hora</option>";}
	else
	{echo "<option value='DATA'>Data e Hora</option>";}

	if ($ordenar_busca == "NOME")
	{echo "<option value='NOME' selected='selected'>Nome</option>";}
	else
	{echo "<option value='NOME'>Nome</option>";}
    ?>
    </select>
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
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_compra >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/contas_pagar_selec_fornecedor.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
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
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/contas_pagar_periodo_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
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
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->


<!-- ======= TOTALIZADOR ========================================================================================= -->
<div style="height:50px; width:1450px; border:0px solid #000; margin:auto">

<div style="height:45px; width:355px; border:0px solid #000; float:left">
<div class="total" style="height:35px; width:330px; margin-top:0px">
	<div class="total_valor" style="width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px"></div>
	<div class="total_nome" style="width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><b>TOTAL PAGO</b></div>
	<div class="total_valor" style="width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><?php echo $soma_pago_print; ?></div>
</div>
</div>

<div style="height:45px; width:355px; border:0px solid #000; float:left">
<div class="total" style="height:35px; width:330px; margin-top:0px">
	<div class="total_valor" style="width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px"></div>
	<div class="total_nome" style="width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><b>SALDO A PAGAR</b></div>
	<div class="total_valor" style="width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><?php echo $soma_saldo_print; ?></div>
</div>
</div>

<div style="height:45px; width:355px; border:0px solid #000; float:left">
<div class="total" style="height:35px; width:330px; margin-top:0px">
	<div class="total_valor" style="width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px"></div>
	<div class="total_nome" style="width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><b>TOTAL GERAL</b></div>
	<div class="total_valor" style="width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px"><?php echo $soma_total_print; ?></div>
</div>
</div>

</div>
<!-- ============================================================================================================= -->

<?php
/*
$numero_divs = ceil($linha_banco_distinct / 4);
$altura_div = ($numero_divs * 50) . "px";

echo "<div style='height:$altura_div; width:1450px; border:0px solid #000; margin:auto'>";


for ($b=1 ; $b<=$linha_banco_distinct ; $b++)
{
$aux_banco_distinct = mysqli_fetch_row($busca_banco_distinct);

$banco_ted_b = "(" . $aux_banco_distinct[0] . ")";
$nome_banco_b = $aux_banco_distinct[1];
$soma_banco_b = $aux_banco_distinct[2];

$soma_banco_print = "R$ " . number_format($soma_banco_b,2,",",".");

if($nome_banco_b == "TED")
{$nome_banco_b = "TRANSFER&Ecirc;NCIAS";}
elseif($nome_banco_b == "CHEQUE")
{$nome_banco_b = "CHEQUES";}
else
{$nome_banco_b = $nome_banco_b;}


echo "
<div style='height:45px; width:355px; border:0px solid #000; float:left'>
<div class='total' style='height:35px; width:330px; margin-top:0px'>
	<div class='total_valor' style='width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px'></div>
	<div class='total_nome' style='width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'><b>$nome_banco_b</b></div>
	<div class='total_valor' style='width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'>$soma_banco_print</div>
</div>
</div>";

}

echo "</div>";
*/
?>
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
<td width='360px'>Fornecedor</td>
<td width='100px'>N&ordm; Compra</td>
<td width='150px'>Produto</td>
<td width='120px'>Quantidade</td>
<td width='120px'>Pre&ccedil;o Unit&aacute;rio</td>
<td width='120px'>Valor Pago</td>
<td width='120px'>Saldo a Pagar</td>
<td width='120px'>Valor Total</td>
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
$quantidade_w = $aux_compra[5];
$preco_unitario_w = $aux_compra[6];
$valor_total_w = $aux_compra[7];
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$situacao_pagamento_w = $aux_compra[11];
$movimentacao_w = $aux_compra[12];
$usuario_cadastro_w = $aux_compra[13];
$hora_cadastro_w = $aux_compra[14];
$data_cadastro_w = $aux_compra[15];
$estado_registro_w = $aux_compra[16];
$filial_w = $aux_compra[17];
$cod_produto_w = $aux_compra[18];
$fornecedor_print_w = $aux_compra[19];
$total_pago_w = $aux_compra[20];
$saldo_pagar_w = $aux_compra[21];


$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".") . " " . $unidade_w;
$preco_unitario_print = number_format($preco_unitario_w,2,",",".");
$valor_total_print = "<b>" . number_format($valor_total_w,2,",",".") . "</b>";
//$total_pago_print = number_format($total_pago_w,2,",",".");
//$saldo_pagar_print = number_format($saldo_pagar_w,2,",",".");

include ("../../includes/conecta_bd.php");
$pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra_w' AND estado_registro='ATIVO'"));
include ("../../includes/desconecta_bd.php");

$pagametos_print = number_format($pagamentos[0],2,",",".");
$saldo_aux = ($valor_total_w - $pagamentos[0]);
$saldo_print = number_format($saldo_aux,2,",",".");


if($situacao_pagamento_w == "PAGO")
{$situacao_pagamento_print = "PAGO";}
elseif($situacao_pagamento_w == "EM_ABERTO")
{$situacao_pagamento_print = "EM ABERTO";}
else
{$situacao_pagamento_print = "";}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($situacao_pagamento_w == "EM_ABERTO")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Status do Pagamento: $situacao_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Status do Pagamento: $situacao_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}


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
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
// =================================================================================================================



// =================================================================================================================
echo "
<td width='120px' align='center'>$data_compra_print</td>
<td width='360px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='100px' align='center'>$numero_compra_w</td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$quantidade_print</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$preco_unitario_print</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$pagametos_print</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$saldo_print</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$valor_total_print</div></td>";
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
<i>Nenhuma conta a pagar encontrada.</i></div>";}
// =================================================================================================================
?>


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
	<div style='width:10px; height:10px; float:left; background-color:#003466; margin-top:2px'></div>
    <div style='width:150px; float:left; color:#003466; margin-left:8px'>EM ABERTO</div>
	<div style='width:10px; height:10px; float:left; background-color:#006400; margin-top:2px'></div>
    <div style='width:150px; float:left; color:#006400; margin-left:8px'>PAGO</div>";}
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