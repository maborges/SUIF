<?php
	include ('../../includes/config.php'); 
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorio_hoje';
	$titulo = 'Compras do dia';
	$modulo = 'compras';
	$menu = 'produtos';


// ====== CONVERTE DATA ================================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ======================================================================================================


// ====== CONVERTE VALOR =================================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =======================================================================================================


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
// =======================================================================================================


// ====== BUSCA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE filial='$filial' AND data_compra='$data_hoje' AND movimentacao='COMPRA' AND estado_registro!='EXCLUIDO' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
// =======================================================================================================


// ====== SOMAS COMPRAS GERAL  ============================================================================
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE filial='$filial' AND data_compra='$data_hoje' AND movimentacao='COMPRA' AND estado_registro!='EXCLUIDO'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ===========================================================================================================
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

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:30px; width:30px; border:0px solid #000; float:left"></div>
	<div id="titulo_form_1" style="width:200px; height:30px; float:left; border:0px solid #000; font-size:22px; color:#090">
	Compras do dia
    </div>

	<div id="centro" style="height:25px; width:20px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:820px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:75px; float:left; height:20px; color:#0000FF; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Relat&oacute;rios:&#160;</i></div>

		<div id="centro" style="width:740px; float:left; height:20px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:740px; height:8px; float:left; border:0px solid #999"></div>
		<div id="menu_atalho">		
			<div id="geral" style="margin-right:20px; margin-left:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_data.php">&#8226; Data</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_periodo.php">&#8226; Per&iacute;odo</a></div>			
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_fornecedor_seleciona.php">&#8226; Fornecedor</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_produto.php">&#8226; Produto</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_tipo_seleciona.php">&#8226; Tipo</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_ranking.php">&#8226; Ranking</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_saldo_resumo.php">&#8226; Saldo de Armazenado</a></div>
			<div id="geral" style="margin-right:20px; border:0px solid #999; float:left">
			<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_numero.php" method="post" />
			<input type='hidden' name='botao' value='1' />
			&#8226; N&uacute;mero <input type="text" name="numero_compra_aux" maxlength="15" id="ok" style="color:#0000FF; width:40px; font-size:9px;" title="Busca por n&uacute;mero da compra" />
			</form>
			</div>

		</div>
		</div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:45px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:44px; margin-left:10px; border:0px solid #999">
	<form action='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_consolidado.php' method='post'>
	<input type='hidden' name='pagina_mae' value='relatorio_hoje'>
	<input type='hidden' name='botao' value='externo'>
	<input type='image' src='<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/rel_consolidado.jpg' border='0' /></form>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; margin-top:14px; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra realizada hoje</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras realizadas hoje</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; margin-top:14px; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
	else
	{ }
	?>
	</div>
</div>


<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>
<!-- ====================================================================================== -->
<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE filial='$filial' AND data_compra='$data_hoje' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND estado_registro!='EXCLUIDO'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE filial='$filial' AND data_compra='$data_hoje' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND estado_registro!='EXCLUIDO'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}

	if ($soma_compra_produto[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
		<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
			<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
			<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#009900'>
			<b>$aux_bp_geral[22]</b>	
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Quant. comprada: $quant_produto_print $aux_bp_geral[26]
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Valor total: R$ $soma_cp_print
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Pre&ccedil;o m&eacute;dio: R$ $media_produto_print / $aux_bp_geral[26]
			</div>
		</div>
	</div>
	<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
	";}


}
?>



<!-- ====================================================================================== -->
<?php include ('../../includes/relatorio_compras.php'); ?>


<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>