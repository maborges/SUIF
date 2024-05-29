<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "relatorio_adto";
$titulo = "Pagamentos - Adiantamentos e Notas Fiscais";
$modulo = "financeiro";
$menu = "contas_pagar";

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
$total_nf_br = $_POST["total_nf_form"];
$total_nf_form = Helpers::ConverteValor($_POST["total_nf_form"]);
$valor_pgto = $_POST["valor_pgto"];

$id_pgto = $_POST["id_pgto"];

if (empty($botao))
{
$favorecido_pesquisa = $_POST["favorecido_pesquisa"];
$nome_favorecido = $_POST["nome_favorecido"];
$preposto_pesquisa = $_POST["preposto_pesquisa"];
$nome_preposto = $_POST["nome_preposto"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$status_pgto_busca = $_POST["status_pgto_busca"];
$filial_busca = $filial_usuario;
$ordenar_busca = "DATA";
}

else
{
$favorecido_pesquisa = $_POST["favorecido_pesquisa"];
$nome_favorecido = $_POST["nome_favorecido"];
$preposto_pesquisa = $_POST["preposto_pesquisa"];
$nome_preposto = $_POST["nome_preposto"];
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

$mysql_filtro_data = "data_pagamento BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($favorecido_pesquisa) or $favorecido_pesquisa == "GERAL")
	{$mysql_favorecido = "codigo_pessoa IS NOT NULL";
	$favorecido_pesquisa = "GERAL";}
else
	{$mysql_favorecido = "codigo_pessoa='$favorecido_pesquisa'";
	$favorecido_pesquisa = $favorecido_pesquisa;}


if (empty($preposto_pesquisa) or $preposto_pesquisa == "GERAL")
	{$mysql_preposto = "codigo_fornecedor IS NOT NULL";
	$preposto_pesquisa = "GERAL";}
else
	{$mysql_preposto = "codigo_fornecedor='$preposto_pesquisa'";
	$preposto_pesquisa = $preposto_pesquisa;}


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


if (empty($status_pgto_busca) or $status_pgto_busca == "GERAL")
	{$mysql_status_pgto = "nf_adto IS NOT NULL";
	$status_pgto_busca = "GERAL";}
else
	{$mysql_status_pgto = "nf_adto='$status_pgto_busca'";
	$status_pgto_busca = $status_pgto_busca;}


if ($ordenar_busca == "NOME")
	{$mysql_ordenar_busca = "favorecido_print";}
else
	{$mysql_ordenar_busca = "codigo";}


$mysql_status = "estado_registro='ATIVO'";

// ================================================================================================================


// ==================================================================================================================================================
if ($botao == "NF")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET nf_adto='NF' WHERE codigo='$id_pgto'");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ==================================================================================================================================================
/*
if ($botao == "BAIXAR_TODOS")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_favorecido AND $mysql_cod_produto AND $mysql_forma_pgto AND $mysql_status_pgto");
include ("../../includes/desconecta_bd.php");
}
*/
// ==================================================================================================================================================


// ==================================================================================================================================================
if ($botao == "ADTO")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET nf_adto='ADTO' WHERE codigo='$id_pgto'");
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


// ==================================================================================================================================================
/*
if ($botao == "ESTORNAR_TODOS")
{
include ("../../includes/conecta_bd.php");
$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE $mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_favorecido AND $mysql_cod_produto AND $mysql_forma_pgto AND $mysql_status_pgto");
include ("../../includes/desconecta_bd.php");
}
*/
// ==================================================================================================================================================


// ==================================================================================================================================================
if ($botao == "TOTAL_NF")
{
include ("../../includes/conecta_bd.php");

	if ($total_nf_form >= $valor_pgto)
	{$altera_total = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET nf_adto='NF', total_nf='$total_nf_form' WHERE codigo='$id_pgto'");}
	else
	{$altera_total = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET nf_adto='ADTO', total_nf='$total_nf_form' WHERE codigo='$id_pgto'");}

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
	cpf_cnpj,
	nf_adto,
	total_nf
FROM 
	favorecidos_pgto
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_favorecido AND
	$mysql_preposto AND
	$mysql_cod_produto AND
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
	$mysql_favorecido AND
	$mysql_preposto AND
	$mysql_cod_produto AND
	$mysql_status_pgto"));


$soma_nf = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor) 
FROM 
	favorecidos_pgto 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_favorecido AND
	$mysql_preposto AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	nf_adto='NF'"));


$soma_adto = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor) 
FROM 
	favorecidos_pgto 
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_favorecido AND
	$mysql_preposto AND
	$mysql_cod_produto AND
	$mysql_status_pgto AND
	nf_adto='ADTO'"));


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_pgto = mysqli_num_rows ($busca_pgto);
$linha_banco_distinct = mysqli_num_rows ($busca_banco_distinct);

if ($soma_pgto[0] > 0)
{$soma_pgto_print = "Total: R$ " . number_format($soma_pgto[0],2,",",".");}

$soma_nf_print = "R$ " . number_format($soma_nf[0],2,",",".");
$soma_adto_print = "R$ " . number_format($soma_adto[0],2,",",".");
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if (!empty($nome_favorecido))
{$msg = "Favorecido: <b>$nome_favorecido</b>";}
elseif (!empty($nome_preposto))
{$msg = "Preposto: <b>$nome_preposto</b>";}
else
{$msg = "";}
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
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/adiantamentos/relatorio_adto.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="favorecido_pesquisa" value="<?php echo"$favorecido_pesquisa"; ?>" />
<input type="hidden" name="nome_favorecido" value="<?php echo"$nome_favorecido"; ?>" />
<input type="hidden" name="preposto_pesquisa" value="<?php echo"$preposto_pesquisa"; ?>" />
<input type="hidden" name="nome_preposto" value="<?php echo"$nome_preposto"; ?>" />

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
    Status do Documento:
    </div>
    
    <div class="pqa_campo">
    <select name="status_pgto_busca" class="pqa_select" style="width:140px" />
    <?php
	if ($status_pgto_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
	else
	{echo "<option value='GERAL'>(GERAL)</option>";}

	if ($status_pgto_busca == "ADTO")
	{echo "<option value='ADTO' selected='selected'>Adiantamento</option>";}
	else
	{echo "<option value='ADTO'>Adiantamento</option>";}

	if ($status_pgto_busca == "NF")
	{echo "<option value='NF' selected='selected'>Nota Fiscal</option>";}
	else
	{echo "<option value='NF'>Nota Fiscal</option>";}

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
	<form action='$servidor/$diretorio_servidor/financeiro/adiantamentos/adto_selec_favorecido.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='favorecido_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='preposto_pesquisa' value='$preposto_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='nome_preposto' value='$nome_preposto'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
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
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($linha_pgto >= 1)
    {echo"
	<form action='$servidor/$diretorio_servidor/financeiro/adiantamentos/adto_selec_preposto.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='favorecido_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='preposto_pesquisa' value='$preposto_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='nome_preposto' value='$nome_preposto'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Filtrar por Preposto</button>
	</form>";}
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<!--
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
-->
	<?php
	/*
	if ($linha_pgto >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BAIXAR_TODOS'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Baixar Todos</button>
	</form>";}
	*/
	?>
<!--
    </div>
</div>
-->
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<!--
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
-->
	<?php
/*
	if ($linha_pgto >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='ESTORNAR_TODOS'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Estornar Todos</button>
	</form>";}
*/
	?>
<!--
    </div>
</div>
-->
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<!--
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
-->
	<?php
/*
	if ($linha_pgto >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/pagamentos_periodo_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='forma_pgto_busca' value='$forma_pgto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_1' style='margin-right:20px'>Imprimir</button>
	</form>";}
*/
	?>
<!--
    </div>
</div>
-->
<!-- ================================================================================================================ -->



</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_5"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
echo "<div style='height:50px; width:1450px; border:0px solid #000; margin:auto'>";


echo "
<div style='height:45px; width:355px; border:0px solid #000; float:left'>
<div class='total' style='height:35px; width:330px; margin-top:0px'>
	<div class='total_valor' style='width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px'></div>
	<div class='total_nome' style='width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'><b>NOTAS FISCAIS</b></div>
	<div class='total_valor' style='width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'>$soma_nf_print</div>
</div>
</div>";

echo "
<div style='height:45px; width:355px; border:0px solid #000; float:left'>
<div class='total' style='height:35px; width:330px; margin-top:0px'>
	<div class='total_valor' style='width:10px; height:20px; border:0px solid #999; font-size:11px; margin-top:10px'></div>
	<div class='total_nome' style='width:160px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'><b>ADIANTAMENTOS</b></div>
	<div class='total_valor' style='width:130px; height:20px; border:0px solid #999; font-size:12px; margin-top:10px'>$soma_adto_print</div>
</div>
</div>";


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
<td width='60px'>NF</td>
<td width='120px'>Documento</td>
<td width='100px'>Data</td>
<td width='340px'>Favorecido</td>
<td width='80px'>N&ordm; Compra</td>
<td width='340px'>Preposto</td>
<td width='160px'>Produto</td>
<td width='130px'>Quantidade Ref.</td>
<td width='110px'>Pre&ccedil;o Unit&aacute;rio</td>
<td width='120px'>Valor Pagamento</td>
<td width='120px'>Total Nota Fiscal</td>
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
$nf_adto_w = $aux_pgto[27];
$total_nf_w = $aux_pgto[28];


// ====== BUSCA PREPOSTO ==========================================================================================
include ("../../includes/conecta_bd.php");
$busca_preposto = mysqli_query ($conexao, "SELECT nome FROM cadastro_pessoa WHERE codigo=$codigo_fornecedor_w");
include ("../../includes/desconecta_bd.php");

$aux_preposto = mysqli_fetch_row($busca_preposto);
$nome_preposto = $aux_preposto[0];
// ================================================================================================================


// ====== BUSCA COMPRA ==========================================================================================
include ("../../includes/conecta_bd.php");
$busca_compra = mysqli_query ($conexao, "SELECT quantidade, preco_unitario, unidade FROM compras WHERE numero_compra=$codigo_compra_w");
include ("../../includes/desconecta_bd.php");

$aux_compra = mysqli_fetch_row($busca_compra);
$quant_compra = $aux_compra[0];
$valor_un = $aux_compra[1];
$unidade_compra = $aux_compra[2];

$valor_un_print = number_format($valor_un,2,",",".");

$quant_ref = $valor_w / $valor_un;
$quant_ref_print = number_format($quant_ref,2,",",".") . " $unidade_compra";
// ================================================================================================================


$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_w));
$valor_print = number_format($valor_w,2,",",".");
$total_nf_print = number_format($total_nf_w,2,",",".");

if($situacao_pagamento_w == "PAGO")
{$situacao_pagamento_print = "BAIXADO";}
elseif($situacao_pagamento_w == "EM_ABERTO")
{$situacao_pagamento_print = "EM ABERTO";}
else
{$situacao_pagamento_print = "";}


if($nf_adto_w == "NF")
{$status_documento_print = "NOTA FISCAL";}
elseif($nf_adto_w == "ADTO")
{$status_documento_print = "ADIANTAMENTO";}
else
{$situacao_pagamento_print = "";}


if($origem_pgto_w == "SOLICITACAO")
{$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";}
else
{$origem_pgto_print = "COMPRA";
$codigo_compra_print = $codigo_compra_w;}


if($forma_pagamento_w == "TED")
{$forma_pagamento_print = "TRANSFER&Ecirc;NCIA";}
elseif($forma_pagamento_w == "CHEQUE")
{$forma_pagamento_print = "CHEQUE";}
else
{$forma_pagamento_print = $forma_pagamento_w;}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($nf_adto_w == "ADTO")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; C&oacute;digo do Favorecido: $codigo_favorecido_w &#13; CPF/CNPJ: $cpf_cnpj_w &#13; Documento: $status_documento_print &#13; Forma de Pagamento: $forma_pagamento_print &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_w &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_w &#13; C&oacute;digo do Favorecido: $codigo_favorecido_w &#13; CPF/CNPJ: $cpf_cnpj_w &#13; Documento: $status_documento_print &#13; Forma de Pagamento: $forma_pagamento_print &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_w &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w $dados_cadastro_w'>";}


// ====== BOTAO BAIXAR ========================================================================================
if ($nf_adto_w == "ADTO")
{
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/financeiro/adiantamentos/relatorio_adto.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='NF'>
<input type='hidden' name='id_pgto' value='$id_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='favorecido_pesquisa' value='$favorecido_pesquisa'>
<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
<input type='hidden' name='preposto_pesquisa' value='$preposto_pesquisa'>
<input type='hidden' name='nome_preposto' value='$nome_preposto'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
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
<form action='$servidor/$diretorio_servidor/financeiro/adiantamentos/relatorio_adto.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='ADTO'>
<input type='hidden' name='id_pgto' value='$id_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='favorecido_pesquisa' value='$favorecido_pesquisa'>
<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
<input type='hidden' name='preposto_pesquisa' value='$preposto_pesquisa'>
<input type='hidden' name='nome_preposto' value='$nome_preposto'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
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
<td width='120px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$status_documento_print</div></td>
<td width='100px' align='center'>$data_pgto_print</td>
<td width='340px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$favorecido_print</div></td>
<td width='80px' align='center'>$codigo_compra_print</td>
<td width='340px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$nome_preposto</div></td>
<td width='160px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_w</div></td>
<td width='130px' align='right'><div style='height:14px; margin-right:15px'>$quant_ref_print</div></td>
<td width='110px' align='right'><div style='height:14px; margin-right:15px'>$valor_un_print</div></td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$valor_print</div></td>
<td width='120px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/adiantamentos/relatorio_adto.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='TOTAL_NF'>
	<input type='hidden' name='id_pgto' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='favorecido_pesquisa' value='$favorecido_pesquisa'>
	<input type='hidden' name='nome_favorecido' value='$nome_favorecido'>
	<input type='hidden' name='preposto_pesquisa' value='$preposto_pesquisa'>
	<input type='hidden' name='nome_preposto' value='$nome_preposto'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='valor_pgto' value='$valor_w'>
	<input type='text' name='total_nf_form' class='form_input' maxlength='12' value='$total_nf_print' onkeypress='mascara(this,mvalor)' 
	style='width:100px; text-align:center; border:transparent; background-color:transparent' />
	</form>
</td>";
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
    <div style='width:150px; float:left; color:#003466; margin-left:8px'>ADIANTAMENTO</div>
	<div style='width:10px; height:10px; float:left; background-color:#006400; margin-top:2px'></div>
    <div style='width:150px; float:left; color:#006400; margin-left:8px'>NOTA FISCAL</div>";}
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