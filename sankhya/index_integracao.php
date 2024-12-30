<?php
include ("../includes/config.php"); 
include ("../includes/valida_cookies.php");
$pagina = "index_sankhya";
$titulo = "Sankhya";
$modulo = "sankhya";
$menu   = "integracao_sankhya";
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
<?php include ("../includes/menu_sankhya.php"); ?>
</div>

<div class="submenu">
<?php include ("../includes/submenu_sankhya_integracao.php"); ?>
</div>

<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:400px; width:930px; border:0px solid #000; margin:auto">


</div><!-- 1º centro -->
</div><!-- centro_geral -->





<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../includes/desconecta_bd.php"); ?>
</body>
</html>