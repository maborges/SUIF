<?php
include ("includes/config.php");
include ("includes/valida_cookies_index.php");
$pagina = "pagina_manutencao";
$titulo = "P&aacute;gina em Manuten&ccedil;&atilde;o";
$modulo = "";
$menu = "";
// ================================================================================================================


// ================================================================================================================
include ("includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body>


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
</div>

<div class="submenu">
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_fixo">





<div style="height:400px; width:910px; border:0px solid #000; margin:auto">



<div style="height:100px; width:905px; border:0px solid #000; margin-top:20px; float:left" align="center">
<!-- <img src="http://www.suif.com.br/config/imagens/logomarca_suif.png" border="0" height="80px" /> -->
</div>

<div style="height:50px; width:905px; border:0px solid #000; float:left; color:#FF0000; font-size:16px" align="center">
P&aacute;gina em manuten&ccedil;&atilde;o.
</div>

<div style="height:50px; width:905px; border:0px solid #000; float:left; color:#777272; font-size:11px" align="center">
Estamos fazendo alguns ajustes nesta p&aacute;gina.<!-- Em breve voltar&aacute; a funcionar normalmente.-->
<!--Previs&atilde;o de retorno: hoje &agrave;s 8:30h -->
</div>


</div>

</div>
<!-- ====== FIM DIV CT =========================================================================================== -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("includes/desconecta_bd.php"); ?>
</body>
</html>