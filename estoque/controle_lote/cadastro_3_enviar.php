<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_3_enviar';
$titulo = 'Movimenta&ccedil;&atilde;o Interna de Estoque';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== CONVERTE PESO ==========================================================================================
function ConvertePeso($peso){
	$peso_1 = str_replace(".", "", $peso);
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje_br = date('d/m/Y');
$data_hoje = date('Y-m-d', time());
$numero_movimentacao = $_POST["numero_movimentacao"];
$cod_produto_form = $_POST["cod_produto_form"];
$lote_form = $_POST["lote_form"];
$movimentacao_form = $_POST["movimentacao_form"];
$tipo_prod_form = $_POST["tipo_prod_form"];
$quant_bag_form = ConvertePeso($_POST["quant_bag_form"]);
$peso_bag_form = ConvertePeso($_POST["peso_bag_form"]);
$cod_sacaria_form = $_POST["cod_sacaria_form"];
$densidade_form = $_POST["densidade_form"];
$umidade_form = $_POST["umidade_form"];
$impureza_form = $_POST["impureza_form"];
$broca_form = $_POST["broca_form"];
$filial = $filial_usuario;
$estado_registro = "ATIVO";
$un_aux = "KG";
$cod_un_aux = "20";

if ($quant_bag_form == "")
{$quant_bag = 0;}
else
{$quant_bag = $quant_bag_form;}

if ($peso_bag_form == "")
{$peso_bag = 0;}
else
{$peso_bag = $peso_bag_form;}

$peso_total = ($quant_bag * $peso_bag);

$usuario_cadastro = $nome_usuario_print;
$data_cadastro = date('Y-m-d', time());
$hora_cadastro = date('G:i:s', time());
// ================================================================================================================


// ================================================================================================================
if ($numero_movimentacao == "")
{header ("Location: $servidor/$diretorio_servidor/estoque/controle_lote/pagina_erro.php");
exit;}
else {}
// ================================================================================================================


// ====== ATUALIZA SACARIA LOTE ===================================================================================
if ($cod_sacaria_form != "")
{
$editar_sacaria = mysqli_query ($conexao, "UPDATE cadastro_lote SET cod_sacaria='$cod_sacaria_form' WHERE codigo_lote='$lote_form'");
}
// ================================================================================================================

// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA LOTE DE ARMAZENAGEM =====================================================================
$busca_lote = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE codigo_lote='$lote_form' AND estado_registro!='EXCLUIDO'");
$aux_lote = mysqli_fetch_row($busca_lote);

$lote_descricao = $aux_lote[2];
$cod_sacaria = $aux_lote[20];
$cod_armazem = $aux_lote[4];
// ======================================================================================================


// ====== BUSCA TIPO PRODUÇÃO =====================================================================
$busca_tipo_prod = mysqli_query ($conexao, "SELECT * FROM cad_tipo_producao WHERE codigo_tipo='$tipo_prod_form' AND estado_registro!='EXCLUIDO'");
$aux_tipo_prod = mysqli_fetch_row($busca_tipo_prod);

$tipo_prod_descricao = $aux_tipo_prod[2];
// ======================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$cod_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$descricao_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
$capacidade_sacaria = $aux_sacaria[14];
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
if ($permissao[112] == "S")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =======================================================================
if ($permissao[112] == "S")
{$permite_imprimir = "SIM";}
else
{$permite_imprimir = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOVA MOVIMENTAÇÃO =====================================================================
if ($permissao[112] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ========================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "NOVA_MOVIMENTACAO")
{
	if ($cod_produto_form == "")
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($linhas_bp == 0)
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($lote_form == "")
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Selecione um lote.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($movimentacao_form == "")
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Selecione a movimenta&ccedil;&atilde;o.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($tipo_prod_form == "")
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Selecione o tipo do produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($quant_bag_form == "" or $quant_bag_form <= 0 or !is_numeric($quant_bag))
	{$erro = 6;
	$msg = "<div style='color:#FF0000'>Quantidade de bag inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($peso_bag_form == "" or $peso_bag_form <= 0 or !is_numeric($peso_bag))
	{$erro = 7;
	$msg = "<div style='color:#FF0000'>Peso por bag inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	elseif ($cod_sacaria_form == "")
	{$erro = 8;
	$msg = "<div style='color:#FF0000'>Informe o tipo de sacaria.</div>";
	$msg_titulo = "<div style='color:#009900'>Movimenta&ccedil;&atilde;o Interna de Estoque</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Movimenta&ccedil;&atilde;o interna realizada com sucesso!</div>";
	$num_movimentacao_print = "<div style='color:#0000FF'>N&ordm; $numero_movimentacao</div>";
	
	$inserir = mysqli_query ($conexao, "INSERT INTO estoque_lote (id, numero_movimentacao, tipo_movimentacao, data, cod_lote, nome_lote, peso, unidade_medida, cod_produto, nome_produto, cod_tipo_producao, cod_armazem, quantidade_bag, cod_sacaria, umidade, densidade, impureza, broca, filial, usuario_cadastro, data_cadastro, hora_cadastro, estado_registro, peso_bag) VALUES (NULL, '$numero_movimentacao', '$movimentacao_form', '$data_hoje', '$lote_form', '$lote_descricao', '$peso_total', '$un_aux', '$cod_produto_form', '$produto_print', '$tipo_prod_form', '$cod_armazem', '$quant_bag', '$cod_sacaria', '$umidade_form', '$densidade_form', '$impureza_form', '$broca_form', '$filial', '$usuario_cadastro', '$data_cadastro', '$hora_cadastro', '$estado_registro', '$peso_bag')");
	
	// ==================================================================
	// ATUALIZA SALDO ARMAZENADO ========================================
	include ('include_busca_saldo_armaz.php');
		if ($movimentacao_form == "ENTRADA")
		{$saldo = $saldo_lote + $peso_total;}
		else
		{$saldo = $saldo_lote - $peso_total;}
	include ('include_atualiza_saldo_armaz.php');
	// ==================================================================
	// ==================================================================
	
	}
}
else
{}
// ======================================================================================================


// ======================================================================================================
$quant_bag_print = number_format($quant_bag,0,",",".");
$peso_bag_print = number_format($peso_bag,0,",",".");
$peso_total_print = number_format($peso_total,0,",",".");


// ======================================================================================================


// ======================================================================================================
include ('../../includes/head.php'); 
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>




<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
	<?php echo"$msg_titulo"; ?>
    </div>


	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
	<?php echo"$num_movimentacao_print"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
    <?php echo"$data_hoje_br"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div id="centro" style="height:280px; width:1080px; border:0px solid #0000FF; margin:auto">


<!-- ================================================= PRODUTO ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:8px">
	<div style="margin-top:0px; margin-left:55px; color:#003466">Produto</div></div>

	<?php
	if ($erro == 1 or $erro == 5)
	{echo"
	<div style='width:241px; height:32px; border:1px solid #F00; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left'>
	<div style='width:300px; height:15px; border:0px solid #000; float:left; font-size:14px; margin-left:25px; color:#003466'>
	<div style='margin-top:7px; margin-left:5px; float:left'><b>$produto_print_2</b></div></div>
	</div>";}

	else
	{echo"
    <div style='width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left'>
	<div style='width:300px; height:15px; border:0px solid #000; float:left; font-size:14px; margin-left:25px; color:#003466'>
	<div style='margin-top:7px; margin-left:5px; float:left'><b>$produto_print_2</b></div></div>
    </div>";}

	?>
    
    


<!--
    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>

    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>
-->


<!-- =============================================== DADOS DA MOVIMENTAÇÃO ============================================================================= -->
	<div id="centro" style="width:1055px; height:180px; border:0px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

    <div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:0px">
    <div style="margin-top:5px">Lote</div></div>
    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:5px">Movimenta&ccedil;&atilde;o</div></div>
    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:5px">Tipo do Produto</div></div>
    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:5px">Quantidade</div></div>
    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:5px">Peso por Bag</div></div>
    <div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:5px">Tipo Sacaria</div></div>

    <div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

	<?php
	if ($erro == 2)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$lote_descricao</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$lote_descricao</div></div>";}

	if ($erro == 3)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$movimentacao_form</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$movimentacao_form</div></div>";}

	if ($erro == 4)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_prod_descricao</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_prod_descricao</div></div>";}

	if ($erro == 6)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_bag_print</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_bag_print</div></div>";}

	if ($erro == 7)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_bag_print</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_bag_print Kg</div></div>";}

	if ($erro == 8)
	{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$descricao_sacaria</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$descricao_sacaria</div></div>";}

	?>

   
<!-- ============================================================================================================== -->

	<div style="width:1025px; height:10px; border:0px solid #000; float:left"></div>
    
	<div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:0px">
    <div style="margin-top:3px">Densidade</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px">Umidade</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px">Impureza</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px">Broca</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px"><!-- xxxxx --></div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:3px"><!-- xxxxx --></div></div>

    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
    <div style="margin-top:5px; width:150px; height:14px; text-align:center; overflow:hidden"><?php echo"$densidade_form" ?></div></div>

    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
    <div style="margin-top:5px; width:150px; height:14px; text-align:center; overflow:hidden"><?php echo"$umidade_form" ?></div></div>
    
    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
    <div style="margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden"><?php echo"$impureza_form" ?></div></div>

    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
    <div style="margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden"><?php echo"$broca_form" ?></div></div>


    
	</div>
<!-- ============================================================================================================== -->


</div>
















<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:335px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/controle_lote/cadastro_1_selec_produto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nova Movimenta&ccedil;&atilde;o</button>
		</form>
    </div>";
// =============================================================================================================================

// ====== BOTAO EDITAR ========================================================================================================
    if ($permite_editar == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/controle_lote/editar_2_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='index_controle_lote'>
		<input type='hidden' name='botao' value='EDITAR_1'>
		<input type='hidden' name='numero_movimentacao' value='$numero_movimentacao' />
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
		</form>
    </div>";
	}

	else
	{
        echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Editar</button>
		</div>";
	}
// =============================================================================================================================

// ====== BOTAO VOLTAR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/controle_lote/index_controle_lote.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
		</form>
    </div>";
// =============================================================================================================================
}

else
{
// ====== BOTAO VOLTAR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/estoque/controle_lote/cadastro_2_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_movimentacao' value='$numero_movimentacao' />
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
	<input type='hidden' name='lote_form' value='$lote_form' />
	<input type='hidden' name='movimentacao_form' value='$movimentacao_form' />
	<input type='hidden' name='tipo_prod_form' value='$tipo_prod_form' />
	<input type='hidden' name='quant_bag_form' value='$quant_bag_form' />
	<input type='hidden' name='peso_bag_form' value='$peso_bag_form' />
	<input type='hidden' name='cod_sacaria_form' value='$cod_sacaria_form' />
	<input type='hidden' name='umidade_form' value='$umidade_form' />
	<input type='hidden' name='densidade_form' value='$densidade_form' />
	<input type='hidden' name='impureza_form' value='$impureza_form' />
	<input type='hidden' name='broca_form' value='$broca_form' />
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
// =============================================================================================================================
}

?>
</div>








</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>