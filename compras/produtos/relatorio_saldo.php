<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'relatorio_saldo';
	$titulo = 'Relat&oacute;rio de Saldo dos Produtores';
	$modulo = 'compras';
	$menu = 'relatorio';
	
	include ('../../includes/head.php');
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>


// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 6000); // 6 Segundos


</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<?php
// ============================================== CONVERTE DATA ====================================================	
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql

function ConverteData($data){

	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
//echo ConverteData($data_emissao);
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){

	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================




// =================================================================================================================
$filial = $filial_usuario;
$produto_list = $_POST["produto_list"];	
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "geral";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}


?>


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
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Saldo dos Produtores</b>
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:600px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id='oculta' style="color:#FF0000; margin-top:8px">(Este relat&oacute;rio pode demorar um pouco para ser gerado.)</div>
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">

		<div id="centro" style="width:40px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:35px; height:3px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_saldo.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		</div>


		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Produto:&#160;</i></div>

		<div id="centro" style="width:160px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:155px; height:3px; float:left; border:0px solid #999"></div>
		<select name="produto_list" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:150px; font-size:12px; text-align:left" />
		<option></option>
		<?php
			$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY codigo");
			$linhas_produto_list = mysqli_num_rows ($busca_produto_list);
		
			for ($j=1 ; $j<=$linhas_produto_list ; $j++)
			{
				$aux_produto_list = mysqli_fetch_row($busca_produto_list);	
				if ($aux_produto_list[20] == $produto_list)
				{
				echo "<option selected='selected' value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
				}
				else
				{
				echo "<option value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
				}
			}
		?>
		</select>
		</div>

		<div id="centro" style="width:70px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:65px; height:3px; float:left; border:0px solid #999"></div>
		</div>	
	
		
		<div id="centro" style="width:70px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:65px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "geral")
			{echo "<input type='radio' name='monstra_situacao' value='geral' checked='checked' /><i>Geral</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='geral' /><i>Geral</i>";}
			?>
		</div>
		
		<div id="centro" style="width:110px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:105px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "devedores")
			{echo "<input type='radio' name='monstra_situacao' value='devedores' checked='checked' /><i>Devedores</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='devedores' /><i>Devedores</i>";}
			?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "credores")
			{echo "<input type='radio' name='monstra_situacao' value='credores' checked='checked' /><i>Credores</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='credores' /><i>Credores</i>";}
			?>
		</div>




		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
		/*
			if ($monstra_ted == "TED")
			{echo "<input type='checkbox' name='monstra_ted' value='TED' checked='checked'><i>TED</i>";}
			else
			{echo "<input type='checkbox' name='monstra_ted' value='TED'><i>TED</i>";}
		*/	
			?>
		</div>


		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:20px; width:1080px; border:0px solid #000; margin:auto">
<div style="margin-left:80px">
<?php
	if ($botao == 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_saldo_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='filial' value='$filial'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>";}
	else
	{echo"";}
?>
</div>
</div>









<!-- ====================================================================================== -->

<?php
if ($botao != 1)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:0px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($botao != 1)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='100px' align='center' bgcolor='#006699'>C&oacute;digo</td>
<td width='500px' align='center' bgcolor='#006699'>Produtor</td>
<td width='200px' align='center' bgcolor='#006699'>Saldo</td>
<td width='100px' align='center' bgcolor='#006699'>Ficha</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:12px">


<?php
// =================================================================================================================
if ($botao != 1)
{}

else
{
// =================================================================================================================

// =================================================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);

if ($produto_list == "CAFE")
{$unidade_print = "Sc";}
else
{$unidade_print = "Kg";}

// =================================================================================================================

for ($w=1 ; $w<=$linha_pessoa ; $w++)
{
	$aux = mysqli_fetch_row($busca_pessoa);
	$cod_forne = $aux[0];

	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' 
	AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND produto='$produto_list' 
	AND fornecedor='$cod_forne' AND filial='$filial'"));
	$soma_saida = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' 
	AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') 
	AND produto='$produto_list' AND fornecedor='$cod_forne' AND filial='$filial'"));
	
	$saldo = ($soma_entrada[0] - $soma_saida[0]);

	$saldo_print = number_format($saldo,2,",",".");



// ==================================================================
// ATUALIZA SALDO ===================================================
//include ('../../includes/atualisa_saldo_armaz.php');
// ==================================================================
// ==================================================================








// RELATORIO =========================
	if ($saldo == 0 and $botao != 1)
	{

	}
	
	elseif ($saldo > 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'credores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_credor = $soma_credor + $saldo;


	echo "
	<tr style='color:#00F' title=''>
	<td width='100px' align='left'>&#160;$aux[0]</td>
	<td width='500px' align='left'>&#160;$aux[1]</td>
	<td width='200px' align='center'>$saldo_print $unidade_print</td>
	<td width='100px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
	<input type='hidden' name='representante' value='$aux[0]'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
	</td>
	</tr>";}

	elseif ($saldo < 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_devedor = $soma_devedor + $saldo;



	echo "
	<tr style='color:#F00' title=''>
	<td width='100px' align='left'>&#160;$aux[0]</td>
	<td width='500px' align='left'>&#160;$aux[1]</td>
	<td width='200px' align='center'>$saldo_print $unidade_print</td>
	<td width='100px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
	<input type='hidden' name='representante' value='$aux[0]'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
	</td>
	</tr>";}

	else
	{}


}


$soma_credor_print = number_format($soma_credor,2,",",".");
$soma_devedor_print = number_format($soma_devedor,2,",",".");


// =================================================================================================================
}
// =================================================================================================================

?>
</table>

<?php
if ($linha_pagamento == 0 and $botao == "1")
{
//echo "
//<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum pagamento encontrado.</i></div>";
}
else
{}
?>



<div id="centro" style="height:10px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:10px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->


<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:250px; float:left; height:25px; margin-left:0px; border:0px solid #999; font-size:12px; color:#666; text-align:center">
	<?php 
	if ($conta_produtor == 1)
	{echo"<i><b>$conta_produtor</b> Produtor</i>";}
	elseif ($conta_produtor > 1)
	{echo"<i><b>$conta_produtor</b> Produtores</i>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:450px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:370px; float:right; height:25px; border:0px solid #999; font-size:12px; text-align:left">
    <?php
	if ($botao != 1)
	{}
	elseif ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores')
	{echo"<font style='color:#666'>TOTAL DEVEDORES: </font><b style='color:#F00'>$soma_devedor_print $unidade_print</b>";}
	else
	{echo"<font style='color:#666'>TOTAL CREDORES: </font><b style='color:#00F'>$soma_credor_print $unidade_print</b>";}
	?>
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:250px; float:left; height:25px; margin-left:0px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>
	
	<div id="centro" style="width:450px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>
	
	<div id="centro" style="width:370px; float:right; height:25px; border:0px solid #999; font-size:12px; text-align:left">
    <?php
	if ($monstra_situacao == 'geral' and $botao == 1)
	{echo"<font style='color:#666'>TOTAL CREDORES: </font><b style='color:#00F'>$soma_credor_print $unidade_print</b>";}
	else
	{ }
	?>
	</div>	
</div>

<div id="centro" style="height:60px; width:1080px; border:0px solid #000; margin:auto"></div>

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