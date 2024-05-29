<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_relatorio_numero';
$titulo = 'Estoque - Buscar Romaneio';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$filial = $filial_usuario;
$botao = $_POST["botao"];
$num_romaneio_aux = $_POST["num_romaneio_aux"];
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' 
AND numero_romaneio='$num_romaneio_aux' AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='SAIDA' AND numero_romaneio='$num_romaneio_aux' AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


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
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:auto; margin:auto; background-color:#FFF; border-radius:20px; border:1px solid #999">
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Estoque - Sa&iacute;da
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:14px">
	Buscar Romaneio
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:36px; width:1250px; border:0px solid #000; margin:auto; background-color:#708090">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/saida_relatorio_numero.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />

	<div id="centro" style="height:36px; width:10px; border:0px solid #000; float:left"></div>

    <div id="centro" style="height:20px; width:145px; border:0px solid #999; float:left; margin-top:11px"></div>

    <div id="centro" style="height:20px; width:100px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:left; margin-top:11px">
        <i>N&deg; Romaneio:&#160;</i>
    </div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
		<input type="text" name="num_romaneio_aux" id="ok" maxlength="15" style="height:16px; width:80px; color:#0000FF; font-size:11px" 
        value="<?php echo"$num_romaneio_aux"; ?>" />
	</div>

    <div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#FFF; font-size:11px; float:left; margin-left:20px; margin-top:8px">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
		</form>
	</div>
	
</div>

<div id="centro" style="height:20px; width:1250px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:30px; width:1200px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:390px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_romaneio >= 1)
	{echo"
	<!--
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='index_contas_pagar'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='botao_1' value='$botao_1'>
	<input type='hidden' name='botao_2' value='$botao_2'>	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>
	-->";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:390px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_romaneio == 1)
	{echo"<i><b>$linha_romaneio</b> Romaneio</i>";}
	elseif ($linha_romaneio == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_romaneio</b> Romaneios</i>";}
	?>
	</div>

	<div id="centro" style="width:390px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_romaneio >= 1)
	{echo"TOTAL DE SA&Iacute;DA: <b>$soma_romaneio_print Kg</b>";}
	else
	{ }
	?>
	</div>
</div>




<!-- ====================================================================================== -->
<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
	AND movimentacao='SAIDA' AND numero_romaneio='$num_romaneio_aux' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
	$soma_quant_print = number_format($soma_quant_produto[0],2,",",".");

	if ($soma_quant_produto[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:30px; width:290px; border:0px solid #999; float:left; margin-left:25px'>
		<div id='centro' style='height:20px; width:280px; margin-top:6px; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
			<div id='centro' style='height:15px; width:130px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:right; font-size:11px; color:#009900'>
			<b>$aux_bp_geral[22]</b>	
			</div>
			<div id='centro' style='height:15px; width:130px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			$soma_quant_print Kg
			</div>
		</div>
	</div>
	";}


}
?>

<div id="centro" style="height:20px; width:1250px; border:0px solid #000; margin:auto"></div>


<!-- ====================================================================================== -->
<?php include ('include_relatorio_estoque_saida.php'); ?>
<!-- ====================================================================================== -->




<!-- ====================================================================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>




<!-- ====== RODAPÉ =============================================================================================== -->
<!-- FIM CENTRO GERAL (depois do menu geral) -->
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>