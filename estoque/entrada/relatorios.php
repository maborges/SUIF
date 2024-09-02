<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'relatorios';
$titulo = 'Estoque - Relat&oacute;rios de Entradas';
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
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/submenu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Relat&oacute;rios de Romaneios de Entrada
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->





<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<div style="width:1250px; height:315px; margin:auto; font-size:14px">


	<div id="centro" style="height:40px; width:1240px; border:0px solid #000; float:left">
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_numero.php">
            &#8226; Buscar Romaneio por N&uacute;mero</a>
			</div>
		</div>
        
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_periodo.php">
            &#8226; Contratos por Per&iacute;odo</a>
            -->
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1240px; border:0px solid #000; float:left">
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_produto.php">
            &#8226; Relat&oacute;rio por Produto</a>
			</div>
		</div>
        
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_periodo.php">&#8226; Contratos por Per&iacute;odo</a>
            -->
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1240px; border:0px solid #000; float:left">
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_fornecedor_seleciona.php">
            &#8226; Relat&oacute;rio por Fornecedor</a>
			</div>
		</div>
        
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_periodo.php">&#8226; Contratos por Per&iacute;odo</a>
            -->
			</div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1240px; border:0px solid #000; float:left">
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorio_romaneios_excluidos.php">
            &#8226; Relat&oacute;rio de Romaneios Exclu&iacute;dos</a>
			</div>
		</div>
        
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
            <!-- xxxxxxxxxxxxxxxx -->
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1240px; border:0px solid #000; float:left">
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_entrada/nf_relatorio_fornecedor_seleciona.php">
            &#8226; Relat&oacute;rio de Notas Fiscais de Entrada</a>
			</div>
		</div>
        
		<div id="geral" style="width:150px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:465px; height:38px; float:left; border:0px solid #999">
			<div class="link">
            <!-- xxxxxxxxxxxxxxxx -->
			</div>
		</div>
	</div>


</div>
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