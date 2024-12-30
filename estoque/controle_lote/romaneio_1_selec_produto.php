<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_1_selec_produto';
$titulo = 'Movimenta&ccedil;&atilde;o de Estoque - Romaneio';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$numero_romaneio_form = $_POST["numero_romaneio_form"];
// ================================================================================================================


// ====== CONTADOR NÚMERO MOVIMENTAÇÃO ==========================================================================
$busca_numero_movimentacao = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnm = mysqli_fetch_row($busca_numero_movimentacao);
$numero_movimentacao = $aux_bnm[27];

$contador_num_movimentacao = $numero_movimentacao + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_movimentacao='$contador_num_movimentacao'");
// ================================================================================================================


// ================================================================================================================
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
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Informe o n&uacute;mero do romaneio
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/romaneio_2_formulario.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />
    <input type='hidden' name='pagina_mae' value='<?php echo"$pagina"; ?>' />
    <input type="hidden" name="numero_movimentacao" value="<?php echo"$numero_movimentacao"; ?>" />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:120px; border:0px solid #000">N&deg; Romaneio:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="numero_romaneio_form" id="ok" maxlength="15" 
    style="width:80px; text-align:center" value="<?php echo"$numero_romaneio_form"; ?>" />
	</div>

	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>
	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->




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