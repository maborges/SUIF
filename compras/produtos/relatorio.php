<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio";
$titulo = "Relat&oacute;rios de Compras";
$modulo = "compras";
$menu = "relatorios";

include ("../../includes/head.php"); 
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





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:100px; text-align:left">
    Relat&oacute;rios de Compras:
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:185px">
    <?php // echo "$msg_erro"; ?>
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->

<!--

	<div id="centro" style="height:50px; width:1080px; border:0px solid #999; color:#666; font-size:14px; border-radius:5px; float:left">
	</div>
	
	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:40px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:auto; height:38px; float:left; border:0px solid #999">
			<div id="geral" style="float:left; border:0px solid #999; font-size:14px; color:#000066">
			&#160;&#160; <b>Relat&oacute;rios:</b>
			</div>
		</div>
	</div>
-->
<style>
#atalho_relatorio a {
	color:#0000FF;
	text-decoration:none;
	font-size:14px;
	font-style:normal;
}
	
#atalho_relatorio a:hover {
	color:#090;
	text-decoration:none;
	font-size:14px;
	font-style:normal;
}
</style>


	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_saldo_resumo.php">&#8226; Relat&oacute;rio de Saldo de Armazenado (Anal&iacute;tico)</a>
			</div>
		</div>
        
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_data.php">&#8226; Compras por Data</a>
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_armazenado.php">&#8226; Relat&oacute;rio de Saldo de Armazenado (Sint&eacute;tico)</a>
			</div>
		</div>
        
   		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_periodo.php">&#8226; Compras por Per&iacute;odo</a>
			</div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_consolidado.php">&#8226; Relat&oacute;rio Consolidado de Compras</a>
			</div>
		</div>
        
   		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_2_selec_fornecedor.php">&#8226; Compras por Fornecedor</a>
			</div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_ranking.php">&#8226; Ranking de Compras</a>
			</div>
		</div>
        
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>       
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_periodo.php">&#8226; Compras por Produto</a>
			</div>
		</div>
	</div>





	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_compras_pagar.php">&#8226; Saldo de Compras a Pagar</a>
			</div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_periodo.php">&#8226; Compras por Tipo</a>
			</div>
		</div>
	</div>




	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_entrada.php">&#8226; Relat&oacute;rio de Entradas</a>
			</div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_numero.php">&#8226; Compras por N&uacute;mero</a>
			</div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_registros_excluidos.php">&#8226; Relat&oacute;rio de Registros Exclu&iacute;dos</a>
			</div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:400px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_comprador.php">&#8226; Relat&oacute;rio de Compras por Comprador</a>
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/acerto_quantidade_relatorio.php">&#8226; Relat&oacute;rio de Quebras / Des&aacute;gios</a>
			</div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:400px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<!-- ================= -->
			</div>
		</div>
	</div>




</div><!-- 1º centro -->
</div><!-- centro_geral -->





<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>