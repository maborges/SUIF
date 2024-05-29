<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'excluir_romaneio';
$titulo = 'Excluir Romaneio';
$modulo = 'estoque';
$menu = 'movimentacao';

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

<?php

// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$num_romaneio_aux = $_POST["num_romaneio_aux"];

$mostra_cancelada = $_POST["mostra_cancelada"];		
$botao = $_POST["botao"];
$monstra_situacao = "todas";
//$monstra_situacao = $_POST["monstra_situacao"];




if ($monstra_situacao == "todas")
{	
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE numero_romaneio='$num_romaneio_aux' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
}

else
{
	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro='ATIVO' AND numero_romaneio='$num_romaneio_aux' AND filial='$filial' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
}
?>


<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>

<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:600px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:595px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Excluir Romaneio</b>
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:80px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:75px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/excluir_romaneio.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>N&uacute;mero:&#160;</i></div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="num_romaneio_aux" maxlength="15" id="ok" style="color:#0000FF; width:90px" />
		</div>

<!--		
		<div id="centro" style="width:200px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:195px; height:3px; float:left; border:0px solid #999"></div>
			<?php /*
			if ($monstra_situacao == "todas")
			{echo "<input type='checkbox' name='monstra_situacao' value='todas' checked='checked' /><i>Mostrar registros exclu&iacute;dos</i>";}
			else
			{echo "<input type='checkbox' name='monstra_situacao' value='todas' /><i>Mostrar registros exclu&iacute;dos</i>";}
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
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
	</div>
</div>
<!-- ====================================================================================== -->

<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_romaneio == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_romaneio == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='80px' align='center' bgcolor='#006699'>Data</td>
<td width='350px' align='center' bgcolor='#006699'>Fornecedor/Cliente</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='120px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='162px' align='center' bgcolor='#006699'>Movimenta&ccedil;&atilde;o</td>
<td width='90px' align='center' bgcolor='#006699'>Situa&ccedil;&atilde;o</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$data_romaneio = $aux_romaneio[3];
$data_romaneio_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$fornecedor = $aux_romaneio[2];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$tipo = $aux_romaneio[5];
$observacao = $aux_romaneio[18];
$estado_registro = $aux_romaneio[26];
$movimentacao = $aux_romaneio[13];
$usuario_cadastro = $aux_romaneio[19];
$hora_cadastro = $aux_romaneio[20];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$usuario_exclusao = $aux_romaneio[40];
$hora_exclusao = $aux_romaneio[41];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$motivo_exclusao = $aux_romaneio[43];


// PRODUTO PRINT  ==========================================================================================
$busca_produto_print = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND apelido='$produto' ORDER BY codigo");
$linha_produto_print = mysqli_num_rows ($busca_produto_print);
	for ($p=1 ; $p<=$linha_produto_print ; $p++)
	{
		$aux_produto_print = mysqli_fetch_row($busca_produto_print);
		if ($linha_produto_print == 0)
		{$produto_print = "-";}
		else
		{$produto_print = $aux_produto_print[22];}
	}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{	
		if ($quantidade <= 1)
			{$unidade_print = "Sc";}
		else	
		{$unidade_print = "Sc";}
	}
	elseif ($unidade == "KG")
	{
		if ($quantidade <= 1)
		{$unidade_print = "Kg";}
		else	
		{$unidade_print = "Kg";}
	}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}




// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "pf")
		{$cpf_cnpj = "CPF: " . $aux_pessoa[3];}
		else
		{$cpf_cnpj = "CNPJ: " . $aux_pessoa[4];}
	}

// BUSCA ENTRADA  ==========================================================================================
$busca_entrada = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$num_romaneio_aux' ORDER BY codigo");
$linha_entrada = mysqli_num_rows ($busca_entrada);



// RELATORIO =========================
	if ($estado_registro == "EXCLUIDO")
	{echo "<tr style='color:#F00' title=' Usu&aacute;rio Exclus&atilde;o: $usuario_exclusao&#013; Data Exclus&atilde;o: $data_exclusao&#013; Hora Exclus&atilde;o: $hora_exclusao&#013; Motivo Exclus&atilde;o: $motivo_exclusao&#013; Observa&ccedil;&atilde;o: $observacao'>";}
	else
	{echo "<tr style='color:#000099' title=' Usu&aacute;rio Cadastro: $usuario_cadastro&#013; Data Cadastro: $data_cadastro&#013; Hora Cadastro: $hora_cadastro&#013; Observa&ccedil;&atilde;o: $observacao'>";}
	
	echo "
	<td width='80px' align='left'>&#160;$data_romaneio_print</td>
	<td width='350px' align='left'>&#160;$fornecedor_print</td>
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='120px' align='center'>$quantidade_print $unidade_print</td>
	<td width='162px' align='center'>$movimentacao</td>
	<td width='90px' align='center'>$estado_registro</td>";
	
	if ($linha_entrada == 0 and $permissao[64] == 'S' and $estado_registro != 'EXCLUIDO')
		{
			if ($produto == "CAFE")
			{$edita_produto = "cafe";}
			elseif ($produto == "PIMENTA")
			{$edita_produto = "pimenta";}
			elseif ($produto == "CACAU")
			{$edita_produto = "cacau";}
			elseif ($produto == "CRAVO")
			{$edita_produto = "cravo";}
			else
			{$edita_produto = "-";}
			
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/excluir_romaneio_conf.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='movimentacao' value='$movimentacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' border='0' /></form>	
		</td>";}
	else
		{echo "
		<td width='54px' align='center'></td>";}

	echo "</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_romaneio == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum romaneio encontrado.</i></div>";}
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