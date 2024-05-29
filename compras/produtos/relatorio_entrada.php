<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include("../../helpers.php");
$pagina = "relatorio_entrada";
$titulo = "Relat&oacute;rio de Entrada";
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
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// GERAL  ==========================================================================================
if ($monstra_situacao == "todas")
{	
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA') AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA') AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");

	// SOMAS DESCONTOS  =======
	$soma_descontos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(desconto_quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA') AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_descontos_print = number_format($soma_descontos[0],2,",",".");


}



// ENTRADA  ==========================================================================================
elseif ($monstra_situacao == "entrada")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");
	
	// SOMAS DESCONTOS  =======
	$soma_descontos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(desconto_quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_descontos_print = number_format($soma_descontos[0],2,",",".");

}



// TRANSFERECIA ENTRADA  ==========================================================================================
elseif ($monstra_situacao == "transferencia")
{
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='TRANSFERENCIA_ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY data_compra");
	$linha_compra = mysqli_num_rows ($busca_compra);
	
	// SOMAS ENTRADAS  =======
	$soma_entrada = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='TRANSFERENCIA_ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_entrada_print = number_format($soma_entrada[0],2,",",".");

	// SOMAS DESCONTOS  =======
	$soma_descontos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(desconto_quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='TRANSFERENCIA_ENTRADA' AND cod_produto='$cod_produto' AND filial='$filial'"));
	$soma_descontos_print = number_format($soma_descontos[0],2,",",".");

}




else
{}



$soma_bruta = $soma_entrada[0] + $soma_descontos[0];
$soma_bruta_print = number_format($soma_bruta,2,",",".");



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
    Relat&oacute;rio de Entradas
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px">
	Por produto
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1200px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:30px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1042px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:35px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right"></div>
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="todas" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_entrada.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="todas" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="todas" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Produto:&#160;</i></div>

		<div id="centro" style="width:180px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:175px; height:3px; float:left; border:0px solid #999"></div>
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



	
		<div id="centro" style="width:65px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:60px; height:3px; float:left; border:0px solid #999"></div>
			<?php 
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todas</i>";}
			?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "entrada")
			{echo "<input type='radio' name='monstra_situacao' value='entrada' checked='checked' /><i>Entradas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='entrada' /><i>Entradas</i>";}
			?>
		</div>
		
		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:118px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "transferencia")
			{echo "<input type='radio' name='monstra_situacao' value='transferencia' checked='checked' /><i>Transfer&ecirc;ncias</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='transferencia' /><i>Transfer&ecirc;ncias</i>";}
			?>
		</div>



		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="todas" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>





<div id="centro" style="height:28px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_entrada_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='imprimir'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='mostra_cancelada' value='$mostra_cancelada'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='20px' /></form>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Registro</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Registros</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php

	if ($linha_compra >= 1)
	{echo"Total Quantidade Bruta: <b>$soma_bruta_print $unidade_print</b>";}
	else
	{ }

	?>
	</div>
</div>



<!-- ====================================================================================== -->
<div id="centro" style="height:28px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<!-- xxxxxxxxxxxxxxxxxxx -->
	</div>
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	<!-- xxxxxxxxxxxxxxxxxxx -->
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#990000; text-align:right">
    <?php

	if ($linha_compra >= 1)
	{echo"Total de Descontos: <b>$soma_descontos_print $unidade_print</b>";}
	else
	{ }

	?>
	</div>

</div>


<!-- ====================================================================================== -->
<div id="centro" style="height:28px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	</div>
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php

	if ($linha_compra >= 1)
	{echo"Total Quantidade L&iacute;quida: <b>$soma_entrada_print $unidade_print</b>";}
	else
	{ }

	?>
	</div>

</div>


<!-- ====================================================================================== -->
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
<td width='70px' align='center' bgcolor='#006699'>Data</td>
<td width='270px' align='center' bgcolor='#006699'>Produtor</td>
<td width='55px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='95px' align='center' bgcolor='#006699'>Produto</td>
<td width='85px' align='center' bgcolor='#006699'>Tipo</td>
<td width='100px' align='center' bgcolor='#006699'>Quant. Bruta</td>
<td width='100px' align='center' bgcolor='#006699'>Quant. L&iacute;quida</td>
<td width='80px' align='center' bgcolor='#006699'>Desconto</td>
<td width='95px' align='center' bgcolor='#006699'>Movimenta&ccedil;&atilde;o</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$numero_compra = $aux_compra[1];
$num_compra_print = $aux_compra[1];
$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $cod_tipo[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];

$des = $aux_compra[29];
if ($des > 0)
{$desconto = number_format($aux_compra[29],2,",",".");
$desconto_print = $desconto . " " . $unidade_print;}
else
{$desconto_print = "";}

$quant_bruta = $quantidade + $des;
$quant_bruta_print = number_format($quant_bruta,2,",",".");

if ($aux_compra[16] == "TRANSFERENCIA_ENTRADA")
{$movimentacao = "TRANSFER&Ecirc;NCIA";}
elseif ($aux_compra[16] == "ENTRADA")
{$movimentacao = "ENTRADA";}
else
{}



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
if ($aux_pessoa[2] == "pf" or $aux_pessoa[2] == "PF")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================



// RELATORIO =========================
	if ($situacao == "ENTRADA")
	{echo "<tr style='color:#000099' title='$cpf_cnpj&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";}
	else
	{echo "<tr style='color:#000099' title='$cpf_cnpj&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";}
	
	echo "
	<td width='70px' align='left'>&#160;$data_compra_print</td>
	<td width='270px' align='left'>&#160;$fornecedor_print</td>
	<td width='55px' align='center'>$num_compra_print</td>
	<td width='95px' align='center'>$produto_print</td>
	<td width='85px' align='center'>$tipo</td>
	<td width='100px' align='center'>$quant_bruta_print $unidade_print</td>
	<td width='100px' align='center'>$quantidade_print $unidade_print</td>
	<td width='80px' align='center'>$desconto_print</td>
	<td width='95px' align='center'>$movimentacao</td>";
	
	if ($linha_acha_pagamento == 0 and $permissao[30] == 'S')
		{
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' /></form>	
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
if ($linha_compra == 0 and $botao == "1")
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