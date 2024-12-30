<?php
$pagina = 'aviso_5';
$titulo = 'Forma de Pagamento';
$menu = 'produtos';
$modulo = 'compras';
include('../../includes/head.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->

<body onload="javascript:foco('ok');">

	<!-- =============================================    T O P O    ================================================= -->
	<div id="topo_geral">
		<?php include('../../includes/topo.php'); ?>
	</div>




	<!-- =============================================    M E N U    ================================================= -->
	<div id="menu_geral">
		<?php include('../../includes/menu_compras.php'); ?>

		<div class="submenu">
			<?php include("../../includes/submenu_compras_compras.php"); ?>
		</div>
	</div> <!-- FIM menu_geral -->




	<!-- =============================================   C E N T R O   =============================================== -->
	<?php
	// $pagina_mae = $_POST["pagina_mae"];
	// $codigo_conta = $_POST["codigo_conta"];
	?>

	<div id="centro_geral">
		<div id="centro" style="width:930px; border:0px solid #000; margin:auto">

			<div id="espaco_2" style="width:925px"></div>

			<div id="centro" style="height:20px; width:925px; border:0px solid #000; color:#003466; font-size:12px">

			</div>


			<div id="centro_3">


				<div id="espaco_2" style="width:895px; border:0px solid #F00"></div>

				<div id="centro" style="height:20px; width:895px; border:0px solid #0F0; color:#F00; font-size:12px"></div>

				<div id="centro" style="height:330px; width:auto; border:0px solid #F0F">


					<?php
					echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:11px'>
O valor do pagamento informado &eacute; maior do que o saldo a pagar.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
</div>";



					?>










					<!-- ============================================================================================ -->
					<!-- ============================================================================================ -->
				</div>
			</div> <!-- FIM DIV centro_3 -->

			<!-- ============================================================================================ -->








		</div><!-- ### FIM DA DIV CENTRO (início, antes da DESCRICAO ### -->

		<!-- ============================================================================================== -->


		<!-- ============================================================================================== -->






	</div><!-- FIM DIV centro_geral -->




	<!-- =============================================   R O D A P É   =============================================== -->
	<div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>