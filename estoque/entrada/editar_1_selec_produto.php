<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'editar_1_selec_produto';
$titulo = 'Editar Romaneio de Entrada';
$modulo = 'estoque';
$menu = 'entrada';
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
<?php include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


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
	Selecione um produto
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->



<?php

 include ('../../includes/seleciona_produto.php'); 
 
 ?>



<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->







<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
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