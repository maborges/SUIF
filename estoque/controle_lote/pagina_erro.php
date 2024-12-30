<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'pagina_erro';
$titulo = 'Erro';
$modulo = 'estoque';
$menu = 'movimentacao';
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
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:1090px; float:left; text-align:center; border:0px solid #000">
    <?php echo "<div style='color:#FF0000'>Erro</div>"; ?>
    </div>
    
	<!--
	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    xxxxxxxxxxxxx
    </div>-->
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:745px; float:left; text-align:left; color:#FF0000">
    <!-- xxxxxxxxxxxxx -->
	</div>

	<div class="ct_subtitulo_1" style="width:345px; float:right; text-align:right; font-style:normal">
    <!-- xxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:175px; width:1080px; border:0px solid #0000FF; margin:auto">







</div>
<!-- ============================================================================================================== -->






<!-- ============================================================================================================= -->
<div id="centro" style="height:50px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	<?php

		echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/controle_lote/index_controle_lote.php' method='post'>
			<input type='hidden' name='num_romaneio_form' value='$num_romaneio_form'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
			</form>
			</div>";
	?>
</div>
<!-- ============================================================================================================== -->







</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>