<?php
	include ('../../includes/config.php'); 
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'index_produtos';
	$titulo = 'Compras';
	$modulo = 'compras';
	$menu = 'produtos';
	
	include ('../../includes/head.php'); 
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_produtos.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">



</div>
</div>





<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>