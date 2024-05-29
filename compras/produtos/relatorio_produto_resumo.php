<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	include ("../../helpers.php");
	$pagina = 'relatorio_produto_resumo';
	$titulo = 'Relat&oacute;rio de Compras';
	$modulo = 'compras';
	$menu = 'relatorio';


// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$cod_produto = $_POST["cod_produto"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// =======================================================================================================


// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT DISTINCT fornecedor FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
$aux_bp_geral = mysqli_fetch_row ($busca_produto_geral);	
$unidade_print = $aux_bp_geral[26];
// =======================================================================================================


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

<?php include ('../../includes/sub_menu_compras_relatorio.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:250px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:245px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Compras</b> - Resumo
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_produto_resumo.php" method="post" />
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

		<div id="centro" style="width:180px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:175px; height:3px; float:left; border:0px solid #999"></div>
   		<select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:170px; height:21px; font-size:11px; text-align:left" />
		<option></option>
		<?php
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


		

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" style="float:left" />
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
	<!--
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_produto_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>
	-->";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 

	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Produtor</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Produtores</i>";}

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

$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
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







<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_compra == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_compra == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='400px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='140px' align='center' bgcolor='#006699'>Produto</td>
<td width='100px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='100px' align='center' bgcolor='#006699'>Valor Total</td>
<!-- <td width='54px' align='center' bgcolor='#006699'>Visualizar</td> -->
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$cod_produto = $_POST["cod_produto"];
$fornecedor = $aux_compra[0];


// FAZ A SOMA POR PRODUTOR ======================================
$soma_valor_produtor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial' AND fornecedor='$aux_compra[0]'"));
$soma_valor_produtor_print = number_format($soma_valor_produtor[0],2,",",".");

$soma_quant_produtor = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial' AND fornecedor='$aux_compra[0]'"));
$soma_quant_produtor_print = number_format($soma_quant_produtor[0],2,",",".");
// ==============================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================



// RELATORIO =========================
	echo "<tr style='color:#000099'>";
	
	echo "
	<td width='400px' align='left'><div style='margin-left:10px'>$fornecedor_print</div></td>
	<td width='140px' align='center'>$produto_print</td>
	<td width='100px' align='right'><div style='margin-right:5px'>$soma_quant_produtor_print $unidade_print</div></td>
	<td width='100px' align='right'><div style='margin-right:5px'>$soma_valor_produtor_print</div></td>

	<!--	
	<td width='54px' align='center'>

	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_visualizar.php' method='post'>
	<input type='hidden' name='numero_compra' value='$numero_compra_print'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>
	</td>
	-->";
	

	echo "</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_compra == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma compra encontrada.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




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