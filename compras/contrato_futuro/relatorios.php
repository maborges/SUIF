<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorios';
	$titulo = 'Relat&oacute;rios de Contratos Futuros';
	$menu = 'contratos';
	$modulo = 'compras';

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

<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:100px; text-align:left">
    Relat&oacute;rios de Contratos Futuros:
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
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_periodo.php">&#8226; Contratos por Per&iacute;odo</a>
			</div>
		</div>
        
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_fornecedor_seleciona.php">&#8226; Contratos por Fornecedor</a>
			</div>
		</div>
	</div>


	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_produto.php">&#8226; Contratos por Produto</a>
			</div>
		</div>
        
   		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_contratos_excluidos.php">&#8226; Contratos Exclu&iacute;dos</a>
			</div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_consolidado.php">&#8226; Relat&oacute;rio Consolidado de Compras</a>
			-->
            </div>
		</div>
        
   		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<!--
            <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_fornecedor_seleciona.php">&#8226; Compras por Fornecedor</a>
			-->
            </div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_ranking.php">&#8226; Ranking de Compras</a>
            -->
			</div>
		</div>
        
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>       
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_produto.php">&#8226; Compras por Produto</a>
            -->
			</div>
		</div>
	</div>





	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
            <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_compras_pagar.php">&#8226; Saldo de Compras a Pagar</a>
			-->
            </div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_tipo_seleciona.php">&#8226; Compras por Tipo</a>
			-->
            </div>
		</div>
	</div>




	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<!--
            <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_entrada.php">&#8226; Relat&oacute;rio de Entradas</a>
			-->
            </div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
			<!--
            <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_numero.php">&#8226; Compras por N&uacute;mero</a>
			-->
            </div>
		</div>
	</div>



	<div id="centro" style="height:40px; width:1080px; border:0px solid #000; float:left">
		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:380px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_registros_excluidos.php">&#8226; Relat&oacute;rio de Registros Exclu&iacute;dos</a>
            -->
			</div>
		</div>

		<div id="geral" style="width:100px; height:38px; float:left; border:0px solid #999"></div>
		<div id="geral" style="width:400px; height:38px; float:left; border:0px solid #999">
			<div id="atalho_relatorio">
            <!--
			<a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/acerto_quantidade_relatorio.php">&#8226; Relat&oacute;rio de Quebras / Des&aacute;gios</a>
            -->
			</div>
		</div>
	</div>




</div><!-- 1º centro -->
</div><!-- centro_geral -->





<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>