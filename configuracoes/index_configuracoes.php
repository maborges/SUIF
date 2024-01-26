<?php
include ('../includes/config.php'); 
include ('../includes/conecta_bd.php');
include ('../includes/valida_cookies.php');
$pagina = 'index_configuracoes';
$menu = 'config';

include ('../includes/head.php');
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
Configura&ccedil;&otilde;es
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body>

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../includes/menu.php'); ?>

<div id="menu" style="margin:auto">

<div id="centro" style="float:left; width:930px; height:2px; border:0px solid #000"></div>

<div id="centro" style="float:left; width:930px; height:20px; border-radius:5px; margin-left:10px; border:1px solid #999">

</div>


</div>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">




</div>
</div>




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../includes/desconecta_bd.php'); ?>
</body>
</html>