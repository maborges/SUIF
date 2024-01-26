<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "entrada_seleciona";
$titulo = "Entrada de Mercadoria";
$modulo = "compras";
$menu = "ficha_produtor";

include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
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



<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_cadastro.php" method="post">

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:185px">
    Entrada de Romaneio
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:185px">
    Informe o n&uacute;mero de romaneio:
    </div>
</div>

<div style="width:1080px; height:50px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000; font-size:11px; color:#666; text-align:center">
N&ordm; Romaneio:
</div>
<!-- ============================================================================================================= -->


<!-- =========================================  NUMERO ROMANEIO ====================================== -->
<div id="geral" style="width:1080px; height:30px; border:0px solid #000; float:left; text-align:center">
<input type="text" name="numero_romaneio" id="ok" maxlength="20" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px" />
</div>

<div style="width:1080px; height:30px; float:left; border:0px solid #000; text-align:center"></div>
<!-- ============================================================================================================= -->


<!-- =============================================================================================== -->
<div id="geral" style="width:1080px; height:30px; border:0px solid #000; float:left; text-align:center">
<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Confirmar</button>
</form>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
</div>
</div>




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>