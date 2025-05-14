<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "pagamentos_periodo";
$titulo = "Pagamentos";
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
$forma_pgto_busca = $_POST["forma_pgto_busca"];
$status_pgto_busca = $_POST["status_pgto_busca"];
$filial_busca = $filial_usuario;
$ordenar_busca = "DATA";
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$forma_pgto_busca = $_POST["forma_pgto_busca"];
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

$mysql_filtro_data = "data_pagamento BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "codigo_pessoa IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "codigo_pessoa='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "(cod_produto IS NOT NULL OR cod_produto IS NULL)";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "filial='$filial_busca'";
	$filial_busca = $filial_busca;}


if (empty($forma_pgto_busca) or $forma_pgto_busca == "GERAL")
	{$mysql_forma_pgto = "forma_pagamento IS NOT NULL";
	$forma_pgto_busca = "GERAL";}
else
	{$mysql_forma_pgto = "forma_pagamento='$forma_pgto_busca'";
	$forma_pgto_busca = $forma_pgto_busca;}


if (empty($status_pgto_busca) or $status_pgto_busca == "GERAL")
	{$mysql_status_pgto = "situacao_pagamento IS NOT NULL";
	$status_pgto_busca = "GERAL";}
else
	{$mysql_status_pgto = "situacao_pagamento='$status_pgto_busca'";
	$status_pgto_busca = $status_pgto_busca;}


if ($ordenar_busca == "BANCO")
	{$mysql_ordenar_busca = "nome_banco, favorecido_print";}
elseif ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "favorecido_print";}
else
	{$mysql_ordenar_busca = "codigo";}


$mysql_status = "estado_registro='ATIVO'";

// ================================================================================================================


// ==================================================================================================================================================
if ($botao == "BAIXAR")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE codigo='$id_pgto'");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ==================================================================================================================================================
if ($botao == "BAIXAR_TODOS")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_forma_pgto AND $mysql_status_pgto");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ==================================================================================================================================================
if ($botao == "ESTORNAR")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE codigo='$id_pgto'");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ==================================================================================================================================================
if ($botao == "ESTORNAR_TODOS")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_cod_produto AND $mysql_forma_pgto AND $mysql_status_pgto");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");

$busca_pgto = mysqli_query ($conexao, 
"SELECT 
	codigo,
	codigo_compra,
	codigo_favorecido,
	forma_pagamento,
	data_pagamento,
	valor,
	banco_cheque,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	estado_registro,
	situacao_pagamento,
	filial,
	codigo_pessoa,
	numero_cheque,
	banco_ted,
	origem_pgto,
	codigo_fornecedor,
	produto,
	favorecido_print,
	cod_produto,
	agencia,
	num_conta,
	tipo_conta,
	nome_banco,
	cpf_cnpj
FROM 
	favorecidos_pgto
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_forma_pgto AND
	$mysql_status_pgto
ORDER BY 
	$mysql_ordenar_busca");


$soma_pgto = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor) 
FROM 
	favorecidos_pgto 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_forma_pgto AND
	$mysql_status_pgto"));


if ($forma_pgto_busca == "CHEQUE")
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, banco_cheque, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY banco_cheque
ORDER BY banco_cheque");
}
elseif ($forma_pgto_busca == "TED")
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, nome_banco, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY banco_ted
ORDER BY nome_banco");
}
else
{
$busca_banco_distinct = mysqli_query ($conexao, 
"SELECT banco_ted, forma_pagamento, SUM(valor)
FROM favorecidos_pgto
WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_fornecedor AND $mysql_forma_pgto AND $mysql_status_pgto AND $mysql_cod_produto
GROUP BY forma_pagamento
ORDER BY forma_pagamento");
}

include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_pgto = mysqli_num_rows ($busca_pgto);
$linha_banco_distinct = mysqli_num_rows ($busca_banco_distinct);

if ($soma_pgto[0] > 0)
{$soma_pgto_print = "Total: R$ " . number_format($soma_pgto[0],2,",",".");}
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Favorecido: <b>$nome_fornecedor</b>";}
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
    if ($linha_pgto == 1)
    {echo "$linha_pgto Pagamento";}
    elseif ($linha_pgto > 1)
    {echo "$linha_pgto Pagamentos";}
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
    <?php echo $soma_pgto_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/pagamentos_periodo.php" method="post" />
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
    Forma de Pagamento:
    </div>
    
    <div class="pqa_campo">
    <select name="forma_pgto_busca" class="pqa_select" style="width:190px" />
    <?php
	if ($forma_pgto_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
	else
	{echo "<option value='GERAL'>(GERAL)</option>";}

	if ($forma_pgto_busca == "DINHEIRO")
	{echo "<option value='DINHEIRO' selected='selected'>Dinheiro</option>";}
	else
	{echo "<option value='DINHEIRO'>Dinheiro</option>";}

	if ($forma_pgto_busca == "CHEQUE")
	{echo "<option value='CHEQUE' selected='selected'>Cheque</option>";}
	else
	{echo "<option value='CHEQUE'>Cheque</option>";}

	if ($forma_pgto_busca == "TED")
	{echo "<option value='TED' selected='selected'>Transfer&ecirc;ncia</option>";}
	else
	{echo "<option value='TED'>Transfer&ecirc;ncia</option>";}

	if ($forma_pgto_busca == "OUTRA")
	{echo "<option value='OUTRA' selected='selected'>Outra Forma de PGTO</option>";}
	else
	{echo "<option value='OUTRA'>Outra Forma de PGTO</option>";}

	if ($forma_pgto_busca == "PREVISAO")
	{echo "<option value='PREVISAO' selected='selected'>(Previs&atilde;o)</option>";}
	else
	{echo "<option value='PREVISAO'>(Previs&atilde;o)</option>";}

	if ($forma_pgto_busca == "A NEGOCIAR") {
	echo "<option value='A NEGOCIAR' selected='selected'>A Negociar</option>";
	} else {
	echo "<option value='A NEGOCIAR'>A Negociar</option>";
	}
    ?>
    </select>
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
	{echo "<option value='NOME' selected='selected'>Nome do Favorecido</option>";}
	else
	{echo "<option value='NOME'>Nome do Favorecido</option>";}

	if ($ordenar_busca == "BANCO")
	{echo "<option value='BANCO' selected='selected'>Banco</option>";}
	else
	{echo "<option value='BANCO'>Banco</option>";}
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
	if ($linha_pgto >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_selec_fornecedor.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Filtrar por Favorecido</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_pgto >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BAIXAR_TODOS'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Baixar Todos</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_pgto >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='ESTORNAR_TODOS'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Estornar Todos</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_pgto >= 1)
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
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
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
<div class="espacamento_5"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
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
?>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php

if ($linha_pgto == 0)
{echo "
<div class='espacamento' style='height:400px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Baixar</td>
<td width='120px'>Data</td>
<td width='360px'>Favorecido</td>
<td width='100px'>N&ordm; Compra</td>
<td width='140px'>Forma de Pagamento</td>
<td width='150px'>Banco</td>
<td width='100px'>Ag&ecirc;ncia</td>
<td width='130px'>N&uacute;mero</td>
<td width='120px'>Tipo de Conta</td>
<td width='130px'>Valor</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pgto ; $x++)
{
$aux_pgto = mysqli_fetch_row($busca_pgto);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_pgto[0];
$codigo_compra_w = $aux_pgto[1];
$codigo_favorecido_w = $aux_pgto[2];
$forma_pagamento_w = $aux_pgto[3];
$data_pagamento_w = $aux_pgto[4];
$valor_w = $aux_pgto[5];
$banco_cheque_w = $aux_pgto[6];
$observacao_w = $aux_pgto[7];
$usuario_cadastro_w = $aux_pgto[8];
$hora_cadastro_w = $aux_pgto[9];
$data_cadastro_w = $aux_pgto[10];
$estado_registro_w = $aux_pgto[11];
$situacao_pagamento_w = $aux_pgto[12];
$filial_w = $aux_pgto[13];
$codigo_pessoa_w = $aux_pgto[14];
$numero_cheque_w = $aux_pgto[15];
$banco_ted_w = $aux_pgto[16];
$origem_pgto_w = $aux_pgto[17];
$codigo_fornecedor_w = $aux_pgto[18];
$produto_w = $aux_pgto[19];
$favorecido_print = $aux_pgto[20];
$cod_produto_w = $aux_pgto[21];
$agencia_w = $aux_pgto[22];
$num_conta_w = $aux_pgto[23];
$tipo_conta_w = $aux_pgto[24];
$nome_banco_w = $aux_pgto[25];
$cpf_cnpj_w = $aux_pgto[26];


$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_w));
$valor_print = "<b>" . number_format($valor_w,2,",",".") . "</b>";


if($situacao_pagamento_w == "PAGO")
{$situacao_pagamento_print = "BAIXADO";}
elseif($situacao_pagamento_w == "EM_ABERTO")
{$situacao_pagamento_print = "EM ABERTO";}
else
{$situacao_pagamento_print = "";}


if($tipo_conta_w == "corrente")
{$tipo_conta_aux = "C/C";}
elseif($tipo_conta_w == "poupanca")
{$tipo_conta_aux = "Poupan&ccedil;a";}
else
{$tipo_conta_aux = "";}


if($banco_cheque_w == "SICOOB")
{$banco_cheque_aux = "Sicoob";}
elseif($banco_cheque_w == "BANCO DO BRASIL")
{$banco_cheque_aux = "Banco do Brasil";}
elseif($banco_cheque_w == "BANESTES")
{$banco_cheque_aux = "Banestes";}
else
{$banco_cheque_aux = "";}


if($origem_pgto_w == "SOLICITACAO")
{$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";}
else
{$origem_pgto_print = "COMPRA";
$codigo_compra_print = $codigo_compra_w;}


if($forma_pagamento_w == "TED")
{$forma_pagamento_print = "TRANSFER&Ecirc;NCIA";
$nome_banco_print = $nome_banco_w;
$agencia_print = $agencia_w;
$num_conta_print = $num_conta_w;
$tipo_conta_print = $tipo_conta_aux;}
elseif($forma_pagamento_w == "CHEQUE")
{$forma_pagamento_print = "CHEQUE";
$nome_banco_print = $banco_cheque_aux;
$agencia_print = "";
$num_conta_print = $numero_cheque_w;
$tipo_conta_print = "";}
else
{$forma_pagamento_print = $forma_pagamento_w;
$nome_banco_print = "";
$agencia_print = "";
$num_conta_print = "";
$tipo_conta_print = "";}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($situacao_pagamento_w == "EM_ABERTO")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; C&oacute;digo do Favorecido: $codigo_favorecido_w &#13; CPF/CNPJ: $cpf_cnpj_w &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_w &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_w &#13; C&oacute;digo do Favorecido: $codigo_favorecido_w &#13; CPF/CNPJ: $cpf_cnpj_w &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_w &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}


// ====== BOTAO BAIXAR ========================================================================================
if ($situacao_pagamento_w == "EM_ABERTO")
{
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='BAIXAR'>
<input type='hidden' name='id_pgto' value='$id_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
}

else
{
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='ESTORNAR'>
<input type='hidden' name='id_pgto' value='$id_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
}
// =================================================================================================================



// =================================================================================================================
echo "
<td width='120px' align='center'>$data_pgto_print</td>
<td width='360px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$favorecido_print</div></td>
<td width='100px' align='center'>$codigo_compra_print</td>
<td width='140px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$forma_pagamento_print</div></td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$nome_banco_print</div></td>
<td width='100px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$agencia_print</div></td>
<td width='130px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$num_conta_print</div></td>
<td width='120px' align='center'>$tipo_conta_print</td>
<td width='130px' align='right'><div style='height:14px; margin-right:15px'>$valor_print</div></td>";
// =================================================================================================================

echo "</tr>";

}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_pgto == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum pagamento encontrado.</i></div>";}
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
if ($linha_pgto >= 1)
	{echo "
	<div style='width:10px; height:10px; float:left; background-color:#003466; margin-top:2px'></div>
    <div style='width:150px; float:left; color:#003466; margin-left:8px'>EM ABERTO</div>
	<div style='width:10px; height:10px; float:left; background-color:#006400; margin-top:2px'></div>
    <div style='width:150px; float:left; color:#006400; margin-left:8px'>BAIXADO</div>";}
?>
</div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php
if ($linha_pgto >= 1)
{include ("../../includes/rodape.php");}
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>