<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_registro';
$titulo = 'Excluir Movimenta&ccedil;&atilde;o de Estoque';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje_br = date('d/m/Y');
$data_hoje = date('Y-m-d', time());
$pagina_mae = $_POST["pagina_mae"];
$id_w = $_POST["id_w"];
$numero_movimentacao_w = $_POST["numero_movimentacao_w"];
$filial = $filial_usuario;
$cod_armazem_form = $_POST["cod_armazem_form"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];


$usuario_exclusao = $nome_usuario_print;
$data_exclusao = date('Y-m-d', time());
$hora_exclusao = date('G:i:s', time());
// ================================================================================================================


// ====== BUSCA MOVIMENTAÇÃO ===================================================================================
$busca_movimentacao = mysqli_query ($conexao, "SELECT * FROM estoque_lote WHERE numero_movimentacao='$numero_movimentacao_w' AND filial='$filial' ORDER BY id");
$linha_movimentacao = mysqli_num_rows ($busca_movimentacao);
$aux_movimentacao = mysqli_fetch_row($busca_movimentacao);

$cod_armazem = $aux_movimentacao[15];
$lote_form = $aux_movimentacao[4];
$cod_lote_w = $aux_movimentacao[4];
$tipo_movimentacao_w = $aux_movimentacao[2];
$data_movimentacao_w = date('d/m/Y', time($aux_movimentacao[3]));
$peso_total_w = $aux_movimentacao[6];
$cod_produto_w = $aux_movimentacao[9];
$tipo_prod_w = $aux_movimentacao[11];
$tipo_prod_form = $aux_movimentacao[11];
$quant_bag_w = $aux_movimentacao[16];
$peso_bag_w = $aux_movimentacao[33];
$cod_sacaria_w = $aux_movimentacao[17];
$densidade_form = $aux_movimentacao[19];
$umidade_form = $aux_movimentacao[18];
$impureza_form = $aux_movimentacao[20];
$broca_form = $aux_movimentacao[21];
// ==================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA LOTE DE ARMAZENAGEM =====================================================================
$busca_lote = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE codigo_lote='$cod_lote_w' AND estado_registro!='EXCLUIDO'");
$aux_lote = mysqli_fetch_row($busca_lote);

$lote_descricao = $aux_lote[2];
$cod_sacaria = $aux_lote[20];
$cod_armazem = $aux_lote[4];
// ======================================================================================================


// ====== BUSCA TIPO PRODUÇÃO =====================================================================
$busca_tipo_prod = mysqli_query ($conexao, "SELECT * FROM cad_tipo_producao WHERE codigo_tipo='$tipo_prod_w' AND estado_registro!='EXCLUIDO'");
$aux_tipo_prod = mysqli_fetch_row($busca_tipo_prod);

$tipo_prod_descricao = $aux_tipo_prod[2];
// ======================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$cod_sacaria_w' ORDER BY codigo");
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


// ====== PERGUNTA EXCLUSAO E MONTA MENSAGEM =========================================================
if ($botao == "DESEJA_EXCLUIR")
{
	$erro = 1;
	$msg = "<div style='color:#FF0000'></div>";
	$msg_titulo = "<div style='color:#FF0000'>Deseja excluir esta movimenta&ccedil;&atilde;o?</div>";
	$num_movimentacao_print = "<div style='color:#009900'>N&ordm; $numero_movimentacao_w</div>";

	
}

elseif ($botao == "EXCLUIR")
{
	$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Movimenta&ccedil;&atilde;o exclu&iacute;da com sucesso!</div>";
	$num_movimentacao_print = "<div style='color:#0000FF'>N&ordm; $numero_movimentacao_w</div>";
	
	$excluir = mysqli_query ($conexao, "UPDATE estoque_lote SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', data_exclusao='$data_exclusao', hora_exclusao='$hora_exclusao' WHERE numero_movimentacao='$numero_movimentacao_w'");
	
	// ==================================================================
	// ATUALIZA SALDO ARMAZENADO ========================================
	include ('include_busca_saldo_armaz.php');
		if ($tipo_movimentacao_w == "ENTRADA")
		{$saldo = $saldo_lote - $peso_total_w;}
		else
		{$saldo = $saldo_lote + $peso_total_w;}
	include ('include_atualiza_saldo_armaz.php');
	// ==================================================================
	// ==================================================================
	
	
}

else
{}
// ======================================================================================================


// ======================================================================================================
$quant_bag_print = number_format($quant_bag_w,0,",",".");
$peso_bag_print = number_format($peso_bag_w,0,",",".");
$peso_total_print = number_format($peso_total_w,0,",",".");


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
    <?php echo"$data_movimentacao_w"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div id="centro" style="height:280px; width:1080px; border:0px solid #0000FF; margin:auto">


<!-- ================================================= PRODUTO ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:8px">
	<div style="margin-top:0px; margin-left:55px; color:#003466">Produto</div></div>

	<?php

	echo"
    <div style='width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left'>
	<div style='width:300px; height:15px; border:0px solid #000; float:left; font-size:14px; margin-left:25px; color:#003466'>
	<div style='margin-top:7px; margin-left:5px; float:left'><b>$produto_print_2</b></div></div>
    </div>";

	?>
    
    




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
    <div style="margin-top:5px">Densidade</div></div>

    <div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

	<?php
	echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$lote_descricao</div></div>";

	echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_movimentacao_w</div></div>";

	echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_prod_descricao</div></div>";

	echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_bag_print</div></div>";

	echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE'>
	<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_bag_print Kg</div></div>";

	?>

    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
    <div style="margin-top:5px; width:150px; height:14px; text-align:center; overflow:hidden"><?php echo"$densidade_form" ?></div></div>
    
   
<!-- ============================================================================================================== -->

	<div style="width:1025px; height:10px; border:0px solid #000; float:left"></div>
    
	<div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:0px">
    <div style="margin-top:3px">Umidade</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px">Impureza</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px">Broca</div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px"><!-- xxxxx --></div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:21px">
    <div style="margin-top:3px"><!-- xxxxx --></div></div>
    <div style="width:153px; height:18px; border:1px solid #FFF; border-radius:5px; float:left; font-size:12px; text-align:center; margin-left:20px">
    <div style="margin-top:3px"><!-- xxxxx --></div></div>

    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
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
if ($botao == "DESEJA_EXCLUIR")
{
	echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO VOLTAR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form name='voltar' action='$servidor/$diretorio_servidor/estoque/controle_lote/$pagina_mae.php' method='post'>
		<input type='hidden' name='botao' value='BUSCAR_LOTE' />
		<input type='hidden' name='cod_armazem_form' value='$cod_armazem_form' />
		<input type='hidden' name='codigo_lote_w' value='$cod_lote_w' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
		</form>
    </div>";
// =============================================================================================================================

// ====== BOTAO EXCLUIR ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form name='voltar' action='$servidor/$diretorio_servidor/estoque/controle_lote/excluir_registro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='botao' value='EXCLUIR' />
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_movimentacao_w' value='$numero_movimentacao_w'>
		<input type='hidden' name='cod_armazem_form' value='$cod_armazem_form' />
		<input type='hidden' name='codigo_lote_w' value='$cod_lote_w' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Excluir</button>
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
		<form name='voltar' action='$servidor/$diretorio_servidor/estoque/controle_lote/$pagina_mae.php' method='post'>
		<input type='hidden' name='botao' value='BUSCAR_LOTE' />
		<input type='hidden' name='cod_armazem_form' value='$cod_armazem_form' />
		<input type='hidden' name='codigo_lote_w' value='$cod_lote_w' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
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