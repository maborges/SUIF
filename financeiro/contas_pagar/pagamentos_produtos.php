<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "pagamentos_produtos";
$titulo = "Relat&oacute;rio de Pagamentos (Por Produto)";
$menu = "contas_pagar";
$modulo = "financeiro";

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


<?php
// =================================================================================================================

$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$codigo_pagamento = $_POST["codigo_pagamento"];
$produto_list = $_POST["produto_list"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$monstra_todas = $_POST["monstra_todas"];		
$botao = $_POST["botao"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}



// ==================================================================================================================================================
if ($monstra_todas == "TODAS")
{

	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND forma_pagamento!='PREVISAO' AND data_pagamento>='$data_inicial' AND forma_pagamento!='PREVISAO' AND data_pagamento<='$data_final' AND produto='$produto_list' ORDER BY data_pagamento");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	
	// SOMAS COMPRAS  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND forma_pagamento!='PREVISAO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND produto='$produto_list'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	
}
// ==================================================================================================================================================

else
{
// ==================================================================================================================================================
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND forma_pagamento!='PREVISAO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial' AND produto='$produto_list' ORDER BY data_pagamento");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	
	// SOMAS COMPRAS  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND forma_pagamento!='PREVISAO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial' AND produto='$produto_list'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
}
// ==================================================================================================================================================
?>


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_financeiro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_financeiro_contas_pagar.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<!-- ======================================================================================================= -->

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:550px; border:0px solid #000; color:#003466; font-size:12px; float:left">
	<div id="geral" style="width:545px; height:8px; float:left; border:0px solid #999"></div>
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Pagamentos</b> - Por Produto
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/pagamentos_produtos.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>

		
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Produto:&#160;</i></div>

		<div id="centro" style="width:125px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:120px; height:3px; float:left; border:0px solid #999"></div>
		<select name="produto_list" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:120px; font-size:11px; text-align:left" />
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
		
		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_todas == "TODAS")
			{echo "<input type='checkbox' name='monstra_todas' value='TODAS' checked='checked'><i>Todas as Filiais</i>";}
			else
			{echo "<input type='checkbox' name='monstra_todas' value='TODAS'><i>Todas as Filiais</i>";}
			?>
		</div>


		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<button type='submit' class='botao_1' style='margin-left:10px'>Buscar</button>
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_pagamento >= 1)
	{echo"
	<!--
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/relatorio_pgtos_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='botao' value='1' />
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao' />
	<input type='hidden' name='monstra_todas' value='$monstra_todas' />	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>
	-->
	";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_pagamento == 1)
	{echo"<i><b>$linha_pagamento</b> Pagamento</i>";}
	elseif ($linha_pagamento == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_pagamento</b> Pagamentos</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_pagamento >= 1)
	{echo"TOTAL DE PAGAMENTOS: <b>R$ $soma_pagamentos_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->




<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linha_pagamento == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linha_pagamento == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='350px' align='center' bgcolor='#006699'>Favorecido</td>
<td width='75px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='65px' align='center' bgcolor='#006699'>Banco</td>
<td width='65px' align='center' bgcolor='#006699'>Ag&ecirc;ncia</td>
<td width='85px' align='center' bgcolor='#006699'>N&ordm; Conta</td>
<td width='50px' align='center' bgcolor='#006699'>Tipo Conta</td>
<td width='95px' align='center' bgcolor='#006699'>Valor</td>
<!--
<td width='54px' align='center' bgcolor='#006699'>Recibo</td>
<td width='54px' align='center' bgcolor='#006699'>Baixar</td>
<td width='54px' align='center' bgcolor='#006699'>Estornar</td>
-->
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($w=1 ; $w<=$linha_pagamento ; $w++)
{
	$aux_favorecido = mysqli_fetch_row($busca_pagamento);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);
	$num_compra = ($aux_favorecido[1]);
	$banco_cheque = ($aux_favorecido[6]);
	$num_cheque = ($aux_favorecido[18]);

	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$aux_favorecido[2]' ORDER BY nome");
	$aux_f2 = mysqli_fetch_row($busca_favorecido_2);
	
	$codigo_pessoa_2 = $aux_f2[1];
	$banco_2 = $aux_f2[2];
	$agencia_2 = $aux_f2[3];
	$conta_2 = $aux_f2[4];
	$tipo_conta_2 = $aux_f2[5];
	$conta_conjunta = $aux_f2[15];
	
	$busca_banco_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_2' ORDER BY apelido");
	$aux_b2 = mysqli_fetch_row($busca_banco_2);
	$banco_print_2 = $aux_b2[3];

	if ($tipo_conta_2 == "corrente")
	{$tipo_conta_print_2 = "C/C";}
	elseif ($tipo_conta_2 == "poupanca")
	{$tipo_conta_print_2 = "P";}
	else
	{$tipo_conta_print_2 = "C.";}

	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_p2[1];
	$tipo_pessoa_2 = $aux_p2[2];
		if ($tipo_pessoa_2 == "pf" or $tipo_pessoa_2 == "PF")
		{$cpf_cnpj_2 = $aux_p2[3];}
		else
		{$cpf_cnpj_2 = $aux_p2[4];}

	$valor_pagamento_print_2 = number_format($aux_favorecido[5],2,",",".");

// FORMA DE PAGAMENTO =========================
	if ($aux_favorecido[3] == "DINHEIRO")
	{$forma_pagamento_2 = "Dinheiro";}
	elseif ($aux_favorecido[3] == "CHEQUE")
	{$forma_pagamento_2 = "Cheque";}
	elseif ($aux_favorecido[3] == "TED")
	{$forma_pagamento_2 = "Transfer&ecirc;ncia";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$forma_pagamento_2 = "Outra";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$forma_pagamento_2 = "(PREVIS&Atilde;O)";}
	else
	{$forma_pagamento_2 = "-";}
	
// DADOS BANCARIOS =========================
	if ($aux_favorecido[3] == "CHEQUE")
	{$dados_bancarios_2 = "$aux_favorecido[6]";}
	elseif ($aux_favorecido[3] == "TED")
	{$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";}
	elseif ($aux_favorecido[3] == "DINHEIRO")
	{$dados_bancarios_2 = "";}
	else
	{$dados_bancarios_2 = "-";}



// RELATORIO =========================
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "<tr style='color:#333' title='N&ordm; Compra: $num_compra&#013;Observa&ccedil;&atilde;o: $obs_pgto'>";}
	else
	{echo "<tr style='color:#00F' title='N&ordm; Compra: $num_compra&#013;Observa&ccedil;&atilde;o: $obs_pgto'>";}

	if ($aux_favorecido[3] == "TED")
	{echo "
	<td width='70px' align='left'>&#160;$data_pagamento_print_2</td>";
	if ($conta_conjunta == "SIM")
	{echo "<td width='350px' align='left'>&#160;$nome_favorecido_2 (*)</td>";}
	else
	{echo "<td width='350px' align='left'>&#160;$nome_favorecido_2</td>";}
	echo "
	<td width='75px' align='center'>$forma_pagamento_2</td>
	<td width='65px' align='center'>$banco_print_2</td>
	<td width='65px' align='center'>$agencia_2</td>
	<td width='85px' align='center'>$conta_2</td>
	<td width='50px' align='center'>$tipo_conta_print_2</td>
	<td width='95px' align='right'>$valor_pagamento_print_2&#160;</td>";}

	elseif ($aux_favorecido[3] == "CHEQUE")
	{echo "
	<td width='70px' align='left'>&#160;$data_pagamento_print_2</td>
	<td width='350px' align='left'>&#160;$nome_favorecido_2</td>
	<td width='75px' align='center'>$forma_pagamento_2</td>
	<td width='65px' align='center'>$banco_cheque</td>
	<td width='65px' align='center'> </td>
	<td width='85px' align='center'>N&ordm; $num_cheque</td>
	<td width='50px' align='center'> </td>
	<td width='95px' align='right'>$valor_pagamento_print_2&#160;</td>";}

	else
	{echo "
	<td width='70px' align='left'>&#160;$data_pagamento_print_2</td>
	<td width='350px' align='left'>&#160;$nome_favorecido_2</td>
	<td width='75px' align='center'>$forma_pagamento_2</td>
	<td width='65px' align='center'> </td>
	<td width='65px' align='center'> </td>
	<td width='85px' align='center'> </td>
	<td width='50px' align='center'> </td>
	<td width='95px' align='right'>$valor_pagamento_print_2&#160;</td>";}




	
	
	echo "
	</tr>";

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_pagamento == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum pagamento encontrado.</i></div>";}
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