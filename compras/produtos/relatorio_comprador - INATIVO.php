<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "relatorio_comprador";
$titulo = "Relat&oacute;rio de Compras";
$modulo = "compras";
$menu = "relatorios";


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$cod_produto = $_POST["cod_produto"];
$usuario_comprador = $_POST["usuario_comprador"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
// =======================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if ($cod_produto == "" or $cod_produto == "GERAL")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$mysql_produto = "codigo IS NOT NULL";
	$cod_produto = "GERAL";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto'";
	$mysql_produto = "codigo='$cod_produto'";
	$cod_produto = $_POST["cod_produto"];}


if ($usuario_comprador == "" or $usuario_comprador == "GERAL")
	{$mysql_usuario_comprador = "usuario_cadastro IS NOT NULL";
	$usuario_comprador = "GERAL";}
else
	{$mysql_usuario_comprador = "usuario_cadastro='$usuario_comprador'";
	$usuario_comprador = $_POST["usuario_comprador"];}
// =======================================================================================================


// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND $mysql_cod_produto AND filial='$filial' AND $mysql_usuario_comprador ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND $mysql_cod_produto AND filial='$filial' AND $mysql_usuario_comprador"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND $mysql_produto");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ===========================================================================================================
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


<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- INÍCIO CENTRO GERAL -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Relat&oacute;rio de Compras
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px">
	Por Comprador
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1180px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1000px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_comprador.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Produto:&#160;</i></div>

		<div id="centro" style="width:150px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:145px; height:3px; float:left; border:0px solid #999"></div>
        
   		<select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:11px; text-align:left" />
		<?php
		if ($cod_produto == "GERAL")
		{echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
		else
		{echo "<option value='GERAL'>(TODOS)</option>";}

			$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
			$linhas_produto_list = mysqli_num_rows ($busca_produto_list);
		
			for ($j=1 ; $j<=$linhas_produto_list ; $j++)
			{
				$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
				if ($aux_produto_list[0] == $cod_produto)
				{
				echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
				}
				else
				{
				echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
				}
			}
		?>
		</select>
		</div>



		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Comprador:&#160;</i></div>

		<div id="centro" style="width:150px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:145px; height:3px; float:left; border:0px solid #999"></div>
        
   		<select name="usuario_comprador" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:11px; text-align:left" />
		<?php
		if ($usuario_comprador == "GERAL")
		{echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
		else
		{echo "<option value='GERAL'>(TODOS)</option>";}

			$busca_comprador = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro='ATIVO' AND filial='$filial' AND usuario_interno='N' ORDER BY username");
			$linhas_comprador = mysqli_num_rows ($busca_comprador);
		
			for ($c=1 ; $c<=$linhas_comprador ; $c++)
			{
				$aux_comprador = mysqli_fetch_row ($busca_comprador);	
				if ($aux_comprador[0] == $usuario_comprador)
				{
				echo "<option selected='selected' value='$aux_comprador[0]'>$aux_comprador[0]</option>";
				}
				else
				{
				echo "<option value='$aux_comprador[0]'>$aux_comprador[0]</option>";
				}
			}
		?>
		</select>
		</div>


		
<!--		
		<div id="centro" style="width:65px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:60px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todas</i>";}
			*/?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			*/?>
		</div>
		
		<div id="centro" style="width:65px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:60px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "pagas")
			{echo "<input type='radio' name='monstra_situacao' value='pagas' checked='checked' /><i>Pagas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagas' /><i>Pagas</i>";}
			*/?>
		</div>
-->

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_comprador_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='usuario_comprador' value='$usuario_comprador'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='20px' /></form>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
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

$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial' AND $mysql_usuario_comprador"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial' AND $mysql_usuario_comprador"));
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

<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>