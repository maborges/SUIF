<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'talao_relatorio';
$titulo = 'Tal&atilde;o Saldo de Produtor';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ========================================================================================================


// ========================================================================================================
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

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$fornecedor = $_POST["fornecedor"];
$codigo_talao = $_POST["codigo_talao"];
$botao = $_POST["botao"];
$taloes_divergentes = $_POST["taloes_divergentes"];
$usuario_cadastro = $nome_usuario_print;
$data_cadastro = date('Y/m/d', time());




// BAIXAR  ==========================================================================================
if ($botao == "baixar")
{	
	$baixar = mysqli_query($conexao, "UPDATE talao_controle SET devolvido='S', data_baixa='$data_cadastro', usuario_baixa='$usuario_cadastro' WHERE codigo_talao='$codigo_talao'");
}
else
{}



// GERAL  ==========================================================================================
if ($botao == "busca")
{	
	$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_talao='$codigo_talao' AND filial='$filial' ORDER BY codigo");
	$linha_talao = mysqli_num_rows ($busca_talao);
}

elseif ($botao == "ficha")
{	
	$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$fornecedor' AND filial='$filial' ORDER BY codigo");
	$linha_talao = mysqli_num_rows ($busca_talao);
}

elseif ($botao == "baixados")
{	
	$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND devolvido='S' AND filial='$filial' ORDER BY codigo");
	$linha_talao = mysqli_num_rows ($busca_talao);
}


else
{
	$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND devolvido='N' AND filial='$filial' ORDER BY codigo");
	$linha_talao = mysqli_num_rows ($busca_talao);
}
?>


<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_ficha_produtor.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:250px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="todas" style="width:245px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Relat&oacute;rio de tal&otilde;es em aberto</b>
	</div>
	




	<div id="centro" style="height:30px; width:20px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:320px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:10px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right"></div>
		<div id="centro" style="width:130px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="todas" style="width:125px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/talao_relatorio.php" method="post" />
		<input type='hidden' name='botao' value='busca' />
		<i>Buscar por n&ordm; tal&atilde;o:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="codigo_talao" style="color:#0000FF; width:90px" />
		</div>

		<div id="centro" style="width:60px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:55px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" title="Buscar por n&ordm; tal&atilde;o" style="float:left" />
		</form>
		</div>
	</div>


	<div id="centro" style="height:30px; width:20px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:350px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:5px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right"></div>
		<div id="centro" style="width:280px; float:left; height:20px; color:#F00; border:0px solid #999; text-align:left">
		<div id="todas" style="width:275px; height:5px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/talao_relatorio.php" method="post" />
		<input type='hidden' name='botao' value='taloes_divergentes' />
        
		<?php
        if ($taloes_divergentes == "sim")
        {echo "<input type='checkbox' name='taloes_divergentes' value='sim' checked='checked'>
		<i>Mostrar somente tal&otilde;es com pend&ecirc;ncias</i>";}
        else
        {echo "<input type='checkbox' name='taloes_divergentes' value='sim'>
		<i>Mostrar somente tal&otilde;es com pend&ecirc;ncias</i>";}
        ?>

        
		</div>



		<div id="centro" style="width:50px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:45px; height:6px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
		</form>
		</div>
	</div>





</div>

<div id="centro" style="height:20px; width:1080px; border:0px solid #000; margin:auto"></div>





<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_talao >= 1)
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
    <?php 
	if ($linha_talao == 1)
	{echo"<i><b>$linha_talao</b> Tal&atilde;o emitido</i>";}
	elseif ($linha_talao == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_talao</b> Tal&otilde;es emitidos</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
	</div>
</div>
<!-- ====================================================================================== -->






<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_talao == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_talao == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='60px' align='center' bgcolor='#006699'>N&ordm; Tal&atilde;o</td>
<td width='300px' align='center' bgcolor='#006699'>Produtor</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='100px' align='center' bgcolor='#006699'>Saldo do Tal&atilde;o</td>
<td width='100px' align='center' bgcolor='#006699'>Saldo da Ficha</td>
<td width='90px' align='center' bgcolor='#006699'>Data Impress&atilde;o</td>
<td width='160px' align='center' bgcolor='#006699'>Usu&aacute;rio Impress&atilde;o</td>
<td width='50px' align='center' bgcolor='#006699'>Baixar</td>
<td width='50px' align='center' bgcolor='#006699'>Ficha</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_talao ; $x++)
{
$aux_talao = mysqli_fetch_row($busca_talao);

$num_talao = $aux_talao[1];
$produto = $aux_talao[3];
$produto_list = $aux_talao[3];
$data_talao = $aux_talao[6];
$data_talao_print = date('d/m/Y', strtotime($aux_talao[6]));
$hora_impressao = $aux_talao[7];
$unidade = $aux_talao[5];
$fornecedor = $aux_talao[2];
$cod_forne = $aux_talao[2];
$quantidade = $aux_talao[4];
$quantidade_print = number_format($aux_talao[4],2,",",".");
$observacao = $aux_talao[10];
$filial_talao = $aux_talao[13];
$devolvido = $aux_talao[9];
$usuario_impressao = $aux_talao[8];
	if ($aux_talao[13] == "")
	{$data_baixa = "";}
	else
	{$data_baixa = date('d/m/Y', strtotime($aux_talao[13]));}
$usuario_baixa = $aux_talao[14];


// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}


// DEVOLVIDO PRINT  ==========================================================================================
	if ($devolvido == "S")
	{$devolvido_print = "SIM";}
	elseif ($devolvido == "N")
	{$devolvido_print = "N&Atilde;O";}
	else
	{$devolvido_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
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


// BUSCA SALDO ARMAZENADO  =================================================================================
include ('../../includes/busca_saldo_armaz.php'); 

$saldo_produtor_print = number_format($saldo_produtor,2,",",".");




// BUSCA CONTROLE DE TALAO  ==========================================================================================
$busca_talao_2 = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$fornecedor' AND devolvido='N' AND filial='$filial' ORDER BY codigo");
$linha_talao_2 = mysqli_num_rows ($busca_talao_2);




// RELATORIO =========================
if ($botao == "taloes_divergentes" and $taloes_divergentes == "sim")
{
	if ($quantidade <> $saldo_produtor)
	{
	if ($devolvido == "S")
	{echo "<tr style='color:#666' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}
	else
	{
		if ($quantidade <> $saldo_produtor)
		{echo "<tr style='color:#F00' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}
		else
		{echo "<tr style='color:#00F' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}		
	}

	echo "	
	<td width='60px' align='left'><div style='margin-left:3px'>$num_talao</div></td>";
		if ($linha_talao_2 > 1)
		{echo "<td width='300px' align='left'><div style='margin-left:3px'>$fornecedor_print&#160;&#160;&#160;&#160;<b>( $linha_talao_2 )</b></div></td>";}
		else
		{echo "<td width='300px' align='left'><div style='margin-left:3px'>$fornecedor_print</div></td>";}
	echo "
	<td width='100px' align='center'>$produto_print</td>
	<td width='100px' align='center'>$quantidade_print $unidade</td>
	<td width='100px' align='center'>$saldo_produtor_print $unidade</td>
	<td width='90px' align='center'>$data_talao_print</td>
	<td width='160px' align='center'>$usuario_impressao</td>";
	
	if ($devolvido == "N" and $permissao[43] == 'S')
		{
		echo "
		<td width='50px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_baixar.php' method='post'>
		<input type='hidden' name='codigo_talao' value='$num_talao'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='taloes_divergentes' value='$taloes_divergentes'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' /></form>	
		</td>";}
	else
		{echo "
		<td width='50px' align='center'></td>";}

	echo "
		<td width='50px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='representante' value='$fornecedor'>
		<input type='hidden' name='botao' value='seleciona'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
		</td>
	
	</tr>";
	}

	else
	{}

}

else
{
	if ($devolvido == "S")
	{echo "<tr style='color:#666' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}
	else
	{
		if ($quantidade <> $saldo_produtor)
		{echo "<tr style='color:#F00' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}
		else
		{echo "<tr style='color:#00F' title='Observa&ccedil;&atilde;o: $observacao&#013;Data da Baixa: $data_baixa&#013;Usu&aacute;rio: $usuario_baixa'>";}		
	}
	echo "	
	<td width='60px' align='left'><div style='margin-left:3px'>$num_talao</div></td>";
		if ($linha_talao_2 > 1)
		{echo "<td width='300px' align='left'><div style='margin-left:3px'>$fornecedor_print&#160;&#160;&#160;&#160;<b>( $linha_talao_2 )</b></div></td>";}
		else
		{echo "<td width='300px' align='left'><div style='margin-left:3px'>$fornecedor_print</div></td>";}
	echo "
	<td width='100px' align='center'>$produto_print</td>
	<td width='100px' align='center'>$quantidade_print $unidade</td>
	<td width='100px' align='center'>$saldo_produtor_print $unidade</td>
	<td width='90px' align='center'>$data_talao_print</td>
	<td width='160px' align='center'>$usuario_impressao</td>";
	
	if ($devolvido == "N" and $permissao[43] == 'S')
		{
		echo "
		<td width='50px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_baixar.php' method='post'>
		<input type='hidden' name='codigo_talao' value='$num_talao'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='taloes_divergentes' value='$taloes_divergentes'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' /></form>	
		</td>";}
	else
		{echo "
		<td width='50px' align='center'></td>";}

	echo "
		<td width='50px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='representante' value='$fornecedor'>
		<input type='hidden' name='botao' value='seleciona'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
		</td>
	
	</tr>";
	
}



}


// =================================================================================================================

?>
</table>

<?php
if ($linha_talao == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum registro encontrado.</i></div>";}
else
{}

?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->


</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:20px; width:1080px; border:0px solid #000; margin:auto; font-size:12px">
<?php
echo "
	<script>
	function enviar_formulario()
	{document.talao.submit()} 
	</script> 
	
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post' name='talao' />
	<input type='hidden' name='botao' value='baixados' />
	</form>

	<a href='javascript:enviar_formulario()' style='text-decoration:none; color:#666'><b>&#10004; </b><i>Tal&otilde;es baixados.</i></a>";
?>
</div>

<div id="centro" style="height:20px; width:1080px; border:0px solid #000; margin:auto">
</div>

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