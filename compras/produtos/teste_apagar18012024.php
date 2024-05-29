<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "ficha_produtor";
$titulo = "Ficha do Produtor";
$modulo = "compras";
$menu = "ficha_produtor";
// ================================================================================================================


// ====== BUSCA CONFIGURAÇÕES E PERMISSÕES ========================================================================
include ("../../includes/conecta_bd.php");

$busca_permissao = mysqli_query ($conexao,
"SELECT preco_compra_maximo FROM usuarios_permissoes WHERE username='$username'");
$permissao = mysqli_fetch_row($busca_permissao);

include ("../../includes/desconecta_bd.php");
// ===============================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$mes_atras = date ('Y-m-d', strtotime('-60 days')); // 2 mêses atras
$mes_atras_br = date ('d/m/Y', strtotime('-60 days')); // 2 mêses atras
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$novo_tipo = $_POST["novo_tipo"];

if ($botao == "BUSCAR")
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $_POST["filial_busca"];
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $filial_usuario;
}
// ================================================================================================================


// ===============================================================================================================
if ($botao == "CONVERTER_TIPO" and $permissao[0] == "S")
{
include ("../../includes/conecta_bd.php");

$busca_tipo = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$novo_tipo' AND estado_registro='ATIVO'");
$aux_tipo = mysqli_fetch_row($busca_tipo);

$tipo_descricao = $aux_tipo[1];

$converter = mysqli_query ($conexao, "UPDATE compras SET tipo='$tipo_descricao', cod_tipo='$novo_tipo' WHERE fornecedor='$fornecedor_pesquisa' AND cod_produto='$cod_produto_busca' AND filial='$filial_busca'");
include ("../../includes/desconecta_bd.php");
}
// ===============================================================================================================


// ====== BUSCA FORNECEDOR ========================================================================================
include ("../../includes/conecta_bd.php");
$busca_fornecedor = mysqli_query ($conexao,
"SELECT
	nome,
	tipo,
	cpf,
	cnpj,
	cidade,
	estado,
	telefone_1,
	codigo_pessoa,
	observacao
FROM
	cadastro_pessoa
WHERE
	codigo='$fornecedor_pesquisa'");
include ("../../includes/desconecta_bd.php");

$aux_fornecedor = mysqli_fetch_row($busca_fornecedor);
$linha_fornecedor = mysqli_num_rows ($busca_fornecedor);

$pessoa_nome = $aux_fornecedor[0];
$pessoa_tipo = $aux_fornecedor[1];
$pessoa_cpf = $aux_fornecedor[2];
$pessoa_cnpj = $aux_fornecedor[3];
$pessoa_cidade = $aux_fornecedor[4];
$pessoa_estado = $aux_fornecedor[5];
$pessoa_telefone = $aux_fornecedor[6];
$codigo_pessoa = $aux_fornecedor[7];
$obs_pessoa = $aux_fornecedor[8];

if ($pessoa_tipo == "PF" or $pessoa_tipo == "pf")
{$pessoa_cpf_cnpj = "CPF: " . $pessoa_cpf;}
else
{$pessoa_cpf_cnpj = "CNPJ: " . $pessoa_cnpj;}
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $mes_atras_br;
	$data_inicial_busca = $mes_atras;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "compras.data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";

$dia_atras = date('Y-m-d', strtotime('-1 days', strtotime($data_inicial_busca)));


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


if (empty($filial_busca) or $filial_busca == "GERAL")
	{$mysql_filial = "compras.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "compras.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


$mysql_status = "compras.estado_registro='ATIVO'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
if ($linha_fornecedor == 1)
{

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
	data_pagamento,
	movimentacao,
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
	numero_romaneio
FROM 
	compras
WHERE 
	$mysql_filtro_data AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
ORDER BY 
	data_compra");


$busca_tipo_distinct = mysqli_query ($conexao, 
"SELECT
	compras.cod_produto,
	compras.cod_tipo,
	compras.tipo
FROM 
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	$mysql_cod_tipo
GROUP BY
	compras.cod_tipo
ORDER BY
	compras.cod_tipo");

include ("../../includes/desconecta_bd.php");
}
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$linha_tipo_distinct = mysqli_num_rows ($busca_tipo_distinct);
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
include ("../../includes/conecta_bd.php");
$busca_produto = mysqli_query ($conexao,
"SELECT
	descricao,
	produto_print,
	unidade_print,
	nome_imagem
FROM
	cadastro_produto
WHERE
	codigo='$cod_produto_busca'");
include ("../../includes/desconecta_bd.php");

$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[0];
$produto_print_2 = $aux_bp[1];
$unidade_produto = $aux_bp[2];
$nome_imagem_produto = $aux_bp[3];
if ($nome_imagem_produto == "")
{$link_imagem_produto = "";}
else
{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:75px'>";}
// ======================================================================================================



// ====== SALDO DO FORNECEDOR ===========================================================================
if ($linha_fornecedor == 1)
{

include ("../../includes/conecta_bd.php");
$soma_quant_entrada = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));

$soma_quant_saida = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
include ("../../includes/desconecta_bd.php");

// CALCULA SALDO GERAL POR FORNECEDOR
$saldo_geral = ($soma_quant_entrada[0] - $soma_quant_saida[0]);
$saldo_geral_print = number_format($saldo_geral,2,",",".");
}
// ======================================================================================================


// ====== ATUALIZA SALDO ARMAZENADO =====================================================================
/*
include ("../../includes/conecta_bd.php");
include ('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_geral;
include ('../../includes/atualisa_saldo_armaz.php');
include ("../../includes/desconecta_bd.php");
*/
// ======================================================================================================


// ====== SOMA SALDO ANTERIOR  ===========================================================================
if ($linha_fornecedor == 1)
{

include ("../../includes/conecta_bd.php");
$soma_ant_entrada = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	data_compra<='$dia_atras' AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));

$soma_ant_saida = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	data_compra<='$dia_atras' AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));
include ("../../includes/desconecta_bd.php");

// CALCULA SALDO ANTERIOR
$saldo_ant = ($soma_ant_entrada[0] - $soma_ant_saida[0]);
$saldo_ant_print = number_format($saldo_ant,2,",",".");
}
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
	<div class="ct_titulo_1" style="width:900px">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:450px">
    

        <div class="total" style="height:60px; width:430px; float:right; box-shadow:4px 4px 6px #666">
            <div class="total_valor" style="width:80px; height:40px; border:0px solid #999; font-size:12px; margin-top:15px">
            <?php echo "$link_imagem_produto"; ?></div>
            <div class="total_nome" style="width:160px; height:30px; border:0px solid #999; font-size:12px; margin-top:24px">
            <b><?php echo "$produto_print"; ?></b></div>
            <?php
			if ($saldo_geral < 0)
			{echo "<div class='total_valor' style='width:175px; height:30px; font-size:18px; margin-top:20px; color:#FF0000; text-align:center'>
			$saldo_geral_print $unidade_produto</div>";}
			else
			{echo "<div class='total_valor' style='width:175px; height:30px; font-size:18px; margin-top:20px; color:#0000FF; text-align:center'>
			$saldo_geral_print $unidade_produto</div>";}
			?>
        </div>
    
    
    
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="width:900px">
    <?php echo "<b>$pessoa_nome</b>"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="width:700px">
    <?php echo $pessoa_cpf_cnpj; ?>
    </div>

	<div class="ct_subtitulo_left" style="width:740px; overflow:hidden">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="width:400px">
    <?php echo "CIDADE: $pessoa_cidade"; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:1000px; overflow:hidden" title="<?php echo $obs_pessoa; ?>">
    <?php echo $obs_pessoa; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/ficha_produtor.php" method="post" />
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
	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_cadastro.php' target='_blank' method='post' />
	<input type='hidden' name='fornecedor' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_produto' value='$cod_produto_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Nova Compra</button>
	</form>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:6px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	echo"
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona.php' target='_blank' method='post' />
	<input type='hidden' name='fornecedor' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_produto' value='$cod_produto_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Nova Entrada</button>
	</form>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	echo"
	<form action='$servidor/$diretorio_servidor/compras/transferencias/cadastro_3_selec_fornecedor.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='FORMULARIO'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_produto_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Transfer&ecirc;ncia</button>
	</form>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	echo"
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao.php' method='post' target='_blank' />
	<input type='hidden' name='fornecedor' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_produto' value='$cod_produto_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Tal&atilde;o</button>
	</form>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
	echo "
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/contas_pagar_periodo.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='nome_fornecedor' value='$pessoa_nome' />
	<input type='hidden' name='data_inicial_busca' value='01/01/2000' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca' />
	<input type='hidden' name='status_pgto_busca' value='EM_ABERTO' />
	<input type='hidden' name='ordenar_busca' value='DATA' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Contas a Pagar</button>
	</form>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
/*
	echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/contas_pagar_periodo.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='nome_fornecedor' value='$pessoa_nome' />
	<input type='hidden' name='data_inicial_busca' value='01/01/2000' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca' />
	<input type='hidden' name='status_pgto_busca' value='EM_ABERTO' />
	<input type='hidden' name='ordenar_busca' value='DATA' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Contrato Futuro</button>
	</form>";
*/
	echo "<button type='submit' class='botao_1' style='margin-right:20px; color:#BBB'>Contrato Futuro</button>";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:auto; height:30px; border:1px solid transparent; margin-top:5px; float:left">
    <div style="width:auto; height:25px; float:left; border:1px solid transparent">
	<?php
/*
	echo"
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/contas_pagar_periodo.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='nome_fornecedor' value='$pessoa_nome' />
	<input type='hidden' name='data_inicial_busca' value='01/01/2000' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca' />
	<input type='hidden' name='status_pgto_busca' value='EM_ABERTO' />
	<input type='hidden' name='ordenar_busca' value='DATA' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<button type='submit' class='botao_1' style='margin-right:20px'>Contrato Tratado</button>
	</form>";
*/
	echo "<button type='submit' class='botao_1' style='margin-right:20px; color:#BBB'>Contrato Tratado</button>";
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
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='botao' value='IMPRIMIR'>
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$pessoa_nome'>
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
<div class="espacamento" style="height:10px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
$numero_divs = ceil($linha_tipo_distinct / 3);
$altura_div = ($numero_divs * 50) . "px";

echo "<div style='height:$altura_div; width:1450px; border:1px solid transparent; margin:auto'>";


for ($sc=1 ; $sc<=$linha_tipo_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_tipo_distinct);

$cod_produto_t = $aux_bp_distinct[0];
$cod_tipo_t = $aux_bp_distinct[1];
$tipo_print_t = $aux_bp_distinct[2];
$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>";


include ("../../includes/conecta_bd.php");

$soma_tipo_entrada = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	cod_tipo='$cod_tipo_t' AND
	(movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO')"));

$soma_tipo_saida = mysqli_fetch_row(mysqli_query ($conexao,
"SELECT
	SUM(quantidade)
FROM
	compras
WHERE
	$mysql_filial AND
	$mysql_status AND
	$mysql_fornecedor AND
	$mysql_cod_produto AND
	cod_tipo='$cod_tipo_t' AND
	(movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO')"));

include ("../../includes/desconecta_bd.php");

$saldo_tipo = ($soma_tipo_entrada[0] - $soma_tipo_saida[0]);
$saldo_tipo_print = number_format($saldo_tipo,2,",",".");


if ($saldo_tipo < 0)
{echo "
<div style='height:50px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$tipo_print_t</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px; color:#FF0000'>Saldo: $saldo_tipo_print $unidade_produto</div>
</div>
</div>";}

elseif ($saldo_tipo > 0)
{echo "
<div style='height:50px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$tipo_print_t</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px; color:#0000FF'>Saldo: $saldo_tipo_print $unidade_produto</div>
</div>
</div>";}

else
{echo "";}


}

echo "</div>";
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:5px"></div>
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
<td width='260px'>Movimenta&ccedil;&atilde;o</td>
<td width='120px'>N&uacute;mero</td>
<td width='180px'>Produto</td>
<td width='150px'>Tipo</td>
<td width='100px'>Pre&ccedil;o Unit&aacute;rio</td>
<td width='140px'>Valor Total</td>
<td width='140px'>Quantidade</td>
<td width='140px'>Saldo</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


if ($linha_compra >= 1)
{
// ====== SALDO ANTERIOR ==============================================================================
echo "<tr class='tabela_3'>
<td width='60px' align='center'><div style='height:24px; margin-top:0px; border:0px solid #000'></div></td>
<td width='120px' align='center'></td>
<td width='260px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>SALDO ANTERIOR</div></td>
<td width='120px' align='center'></td>
<td width='180px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'></div></td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'></div></td>
<td width='100px' align='right'><div style='height:14px; margin-right:10px'></div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:10px'></div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:10px'></div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$saldo_ant_print $unidade_produto</div></td>
</tr>
";
}


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
$total_geral_w = $aux_compra[7];
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$data_pagamento_w = $aux_compra[11];
$movimentacao_w = $aux_compra[12];
$usuario_cadastro_w = $aux_compra[13];
$hora_cadastro_w = $aux_compra[14];
$data_cadastro_w = $aux_compra[15];
$usuario_alteracao_w = $aux_compra[16];
$hora_alteracao_w = $aux_compra[17];
$data_alteracao_w = $aux_compra[18];
$estado_registro_w = $aux_compra[19];
$filial_w = $aux_compra[20];
$fornecedor_print_w = $aux_compra[21];
$forma_entrega_w = $aux_compra[22];
$usuario_exclusao_w = $aux_compra[23];
$hora_exclusao_w = $aux_compra[24];
$data_exclusao_w = $aux_compra[25];
$numero_romaneio_w = $aux_compra[26];


$data_compra_print = date('d/m/Y', strtotime($data_compra_w));
$quantidade_print = number_format($quantidade_w,2,",",".");
$data_pagamento_print = date('d/m/Y', strtotime($data_pagamento_w));

if ($preco_unitario_w == 0)
{$preco_unitario_print = "";}
else
{$preco_unitario_print = number_format($preco_unitario_w,2,",",".");}

if ($total_geral_w == 0)
{$total_geral_print = "";}
else
{$total_geral_print = "R$ " . number_format($total_geral_w,2,",",".");}


if ($movimentacao_w == "COMPRA")
{$movimentacao_print = "COMPRA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "ENTRADA")
{$movimentacao_print = "ENTRADA ROMANEIO $numero_romaneio_w";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "TRANSFERENCIA_ENTRADA")
{$movimentacao_print = "ENTRADA TRANSFER&Ecirc;NCIA";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/transferencias/transferencia_visualizar";}

elseif ($movimentacao_w == "ENTRADA_FUTURO")
{$movimentacao_print = "ENTRADA CONTRATO FUTURO";
$tipo_movimentacao = "ENTRADA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "TRANSFERENCIA_SAIDA")
{$movimentacao_print = "SA&Iacute;DA TRANSFER&Ecirc;NCIA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/transferencias/transferencia_visualizar";}

elseif ($movimentacao_w == "SAIDA_FUTURO")
{$movimentacao_print = "SA&Iacute;DA PGTO CONTRATO FUTURO";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

elseif ($movimentacao_w == "SAIDA")
{$movimentacao_print = "SA&Iacute;DA";
$tipo_movimentacao = "SAIDA";
$endereco_visualizar = "compras/compras/compra_visualizar";}

else
{$movimentacao_print = $movimentacao_w;}



// ====== CALCULO SALDO ATUAL  =========================================================================
if ($tipo_movimentacao == "SAIDA")
{$saldo_atual = $saldo_ant - $quantidade_w;}
else
{$saldo_atual = $saldo_ant + $quantidade_w;}
$saldo_atual_print = number_format($saldo_atual,2,",",".");
$saldo_ant = $saldo_atual;
// =====================================================================================================



if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}
// ======================================================================================================



// ====== RELATORIO =====================================================================================
if ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Forma de Entrega: $forma_entrega_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

if ($tipo_movimentacao == "ENTRADA")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Forma de Entrega: $forma_entrega_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_w &#13; Forma de Entrega: $forma_entrega_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}



// ====== BOTAO VISUALIZAR ==================================================================================
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/$endereco_visualizar.php' method='post' />
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
<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
// =================================================================================================================


// =================================================================================================================
echo "
<td width='120px' align='center'>$data_compra_print</td>
<td width='260px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$movimentacao_print</div></td>
<td width='120px' align='center'>$numero_compra_w</td>
<td width='180px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$tipo_w</div></td>
<td width='100px' align='right'><div style='height:14px; margin-right:10px'>$preco_unitario_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:10px'>$total_geral_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:10px'><b>$quantidade_print</b> $unidade_w</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$saldo_atual_print $unidade_produto</div></td>";
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
<i>Nenhuma movimenta&ccedil;&atilde;o encontrada nesse per&iacute;odo</i></div>";}
// =================================================================================================================
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:40px"></div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div style=" width:1500px; height:40px; border:1px solid #FFF; margin:auto">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_campo">
    
    <form name="converte_tipo" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/ficha_produtor.php" method="post" />
    <input type="hidden" name="botao" value="CONVERTER_TIPO" />
    <input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
    <input type="hidden" name="nome_fornecedor" value="<?php echo"$nome_fornecedor"; ?>" />
    <input type="hidden" name="data_inicial_busca" value="<?php echo"$data_inicial_br"; ?>" />
    <input type="hidden" name="data_final_busca" value="<?php echo"$data_final_br"; ?>" />
    <input type="hidden" name="cod_produto_busca" value="<?php echo"$cod_produto_busca"; ?>" />
    <input type="hidden" name="cod_tipo_busca" value="<?php echo"$cod_tipo_busca"; ?>" />
    <input type="hidden" name="filial_busca" value="<?php echo"$filial_busca"; ?>" />

    
    <select name="novo_tipo" class="pqa_select" onchange="document.converte_tipo.submit()" style="width:190px" />
    <option>Converter Tipo</option>
    <?php
	include ("../../includes/select_tipo_produto.php"); 

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


</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->




<!-- ============================================================================================================= -->
<div class="espacamento" style="height:50px"></div>
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