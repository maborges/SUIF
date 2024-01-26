<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "index_relatorios";
$titulo = "Relat&oacute;rios";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ================================================================================================================
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
<?php include ("../../includes/submenu_compras_relatorios.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_fixo">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo $msg; ?>
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:20px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1450px; height:400px; border:1px solid transparent; margin:auto">


<!-- ============================================================================================================= -->
<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/saldo_armazenado_analitico.php">
    &#8226; Saldo de Armazenado (Anal&iacute;tico)
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/saldo_armazenado_sintetico.php">
    &#8226; Saldo de Armazenado (Sint&eacute;tico)
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_periodo.php">
    &#8226; Compras por Per&iacute;odo
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_selec_fornecedor.php">
    &#8226; Compras por Fornecedor
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_consolidado.php">
    &#8226; Consolidado de Compras
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_ranking.php">
    &#8226; Ranking de Fornecedores
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/entradas/relatorio_periodo.php">
    &#8226; Entradas
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_usuario.php">
    &#8226; Compras por Usu&aacute;rio
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/contas_pagar_periodo.php">
    &#8226; Saldo Financeiro a Pagar
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_excluidos.php">
    &#8226; Registros Exclu&iacute;dos
    </a>
    </div>
</div>

<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_desagio.php">
    &#8226; Quebras e Des&aacute;gios
    </a>
    </div>
</div>


<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/transferencias/relatorio_transferencia.php">
    &#8226; Transfer&ecirc;ncias
    </a>
    </div>
</div>


<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_ultima_compra.php">
    &#8226; &Uacute;ltimas Movimenta&ccedil;&otilde;es
    </a>
    </div>
</div>


<div style="width:350px; height:38px; border:1px solid transparent; font-size:14px; color:#666; float:left; margin-left:0px">
	<div class="link_4">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/adiantamentos/relatorio_adto.php">
    &#8226; Pagamentos (Adiantamentos e Notas Fiscais)
    </a>
    </div>
</div>


</div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>