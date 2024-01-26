<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_romaneio_3_enviar';
$titulo = 'Romaneio n&atilde;o localizado';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$filial = $filial_usuario;
$usuario_exclusao = $nome_usuario_print;
$data_exclusao = date('Y/m/d', time());
$hora_exclusao = date('G:i:s', time());

$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];
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
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:1090px; float:left; text-align:center; border:0px solid #000">
    <?php echo "<div style='color:#FF0000'>Romaneio n&atilde;o localizado</div>"; ?>
    </div>
    
	<!--
	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    xxxxxxxxxxxxx
    </div>-->
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:745px; float:left; text-align:left; color:#FF0000">
    <!-- xxxxxxxxxxxxx -->
	</div>

	<div class="ct_subtitulo_1" style="width:345px; float:right; text-align:right; font-style:normal">
    <!-- xxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:175px; width:1080px; border:0px solid #0000FF; margin:auto">







</div>
<!-- ============================================================================================================== -->






<!-- ============================================================================================================= -->
<div id="centro" style="height:50px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	<?php

		echo "
			<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/saida/saida_relatorio_numero.php' method='post'>
			<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
			<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
			</form>
			</div>";
	?>
</div>
<!-- ============================================================================================================== -->







</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>