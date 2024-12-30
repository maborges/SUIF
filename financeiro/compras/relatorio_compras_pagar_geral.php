<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'relatorio_compras_pagar_geral';
$titulo = 'Relat&oacute;rio de Compras a Pagar';
$modulo = 'financeiro';
$menu = 'contas_pagar';

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
$data_hoje = date('d/m/Y', time());
$filial = $filial_usuario;

$cod_compra = $_POST["cod_compra"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$data_vencimento_aux = $_POST["data_vencimento"];
$data_vencimento = Helpers::ConverteData($_POST["data_vencimento"]);
$produto_list = $_POST["produto_list"];
$fornecedor = $_POST["representante"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
$filial_pgto = $_POST["filial_pgto"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "todas";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}


// ==================================================================================================================================================
if ($botao == "altera_todos" and $data_vencimento_aux != "" and $data_vencimento != "0000-00-00")
{
$alterar_vencimento = mysqli_query ($conexao, "UPDATE compras SET data_pagamento='$data_vencimento' WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND movimentacao='COMPRA' AND situacao_pagamento='EM_ABERTO' AND filial='$filial_pgto'");
}
else
{}




// ==================================================================================================================================================
if ($filial_pgto == "TODAS")
{	
	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND estado_registro!='EXCLUIDO' AND movimentacao='COMPRA' ORDER BY data_pagamento");
	$linha_compra = mysqli_num_rows ($busca_compra);
}

else
{
//	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND filial='$filial_pgto' AND situacao_pagamento='EM_ABERTO' ORDER BY data_pagamento");

	$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND movimentacao='COMPRA' AND situacao_pagamento='EM_ABERTO' AND filial='$filial_pgto' ORDER BY data_pagamento");
	$linha_compra = mysqli_num_rows ($busca_compra);

}

?>

<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
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
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Compras a Pagar</b>
	</div>
	
	<div id="centro" style="height:25px; width:72px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
	</div>
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:10px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1060px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:left; margin-left:10px">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<form name="compra_cafe" action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/compras/relatorio_compras_pagar_geral.php" method="post" />
		<input type='hidden' name='botao' value='1' />
        <input type='hidden' name='data_vencimento' value='<?php echo"$data_vencimento_aux"; ?>'>
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


		<div id="centro" style="width:60px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:55px; height:8px; float:left; border:0px solid #999"></div>
		<i>Filial:&#160;</i></div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<select name="filial_pgto" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:115px; font-size:12px" />
        <?php
            $busca_filial = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
            $linhas_filial = mysqli_num_rows ($busca_filial);
        
        for ($f=1 ; $f<=$linhas_filial ; $f++)
        {
        $aux_filial = mysqli_fetch_row($busca_filial);
			if ($filial_pgto == "$aux_filial[1]")
			{echo "<option value='$aux_filial[1]' selected='selected'>$aux_filial[2]</option>";}
			else
            {echo "<option value='$aux_filial[1]'>$aux_filial[2]</option>";}
        }
        ?>
        </select>

		</div>



		<div id="centro" style="width:60px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:55px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" height='20px' style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:50px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
	</div>
	
	<div id="centro" style="width:400px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
	<!-- xxxxxxxxxxxxxxxx -->
	</div>
	
	<div id="centro" style="width:250px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	<!-- xxxxxxxxxxxxxxxx -->
	</div>

	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
	<!-- xxxxxxxxxxxxxxxx -->
	</div>
	
	<div id="centro" style="width:30px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
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
<td width='80px' align='center' bgcolor='#006699'>Vencimento</td>
<td width='350px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='95px' align='center' bgcolor='#006699'>Valor a Pagar</td>
<td width='54px' align='center' bgcolor='#006699'>Alterar</td>
<!--
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
<td width='54px' align='center' bgcolor='#006699'>Pgto</td>
-->
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$num_compra_print = $aux_compra[1];
$produto = $aux_compra[3];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$data_pgto = $aux_compra[14];
$data_pgto_print = date('d/m/Y', strtotime($aux_compra[14]));
$unidade = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];


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

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao == "ENTREGUE")
	{$situacao_print = "ENTREGUE";}
	elseif ($situacao == "A_ENTREGAR")
	{$situacao_print = "A ENTREGAR";}
	elseif ($situacao == "ARMAZENADO")
	{$situacao_print = "ARMAZENADO";}
	else
	{$situacao_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf")
		{$cpf_cnpj = "CPF: " . $aux_pessoa[3];}
		else
		{$cpf_cnpj = "CNPJ: " . $aux_pessoa[4];}
	}

// BUSCA PAGAMENTO  ==========================================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");

// CALCULO SALDO A PAGAR  ==========================================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");




// RELATORIO =========================

		// CALCULO QUANTIDADE A ENTREGAR  ==========================================================================================
		$quant_entregar = $saldo_a_pagar_aux / $preco_unitario;
		$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
		$quant_entregar_print = number_format($quant_entregar_aux,2,",",".");



	if ($soma_pagamentos[0] < $valor_total)
	{
	echo "<tr style='color:#003466' title='Quantidade: $quantidade_print $unidade_print&#013;Pre&ccedil;o Un: $preco_unitario_print&#013;Valor Total: $valor_total_print&#013;Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";
	echo "
	<td width='80px' align='left'><div style='margin-left:3px'>$data_pgto_print</div></td>
	<td width='350px' align='left'><div style='margin-left:3px'>$fornecedor_print</div></td>
	<td width='60px' align='center'>$num_compra_print</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='95px' align='right'><div style='margin-right:3px'>$saldo_a_pagar</div></td>

	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/compras/altera_vencimento.php' method='post' target='_blank'>
	<input type='hidden' name='cod_compra' value='$aux_compra[0]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='alterar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='data_vencimento' value='$data_vencimento_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='filial_pgto' value='$filial_pgto'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' /></form>	
	</td>";
	
	echo "</tr>";
	$alterar_status = mysqli_query ($conexao, "UPDATE compras SET situacao_pagamento='EM_ABERTO', total_pago='$soma_pagamentos[0]', saldo_pagar='$saldo_a_pagar_aux' WHERE codigo='$aux_compra[0]'");
	}
	
	else
	{
	$alterar_status = mysqli_query ($conexao, "UPDATE compras SET situacao_pagamento='PAGO', total_pago='$soma_pagamentos[0]', saldo_pagar='$saldo_a_pagar_aux' WHERE codigo='$aux_compra[0]'");
	}

}


// =================================================================================================================

?>
</table>

<?php
if ($linha_compra == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma compra encontrada.</i></div>";}
else
{echo "";}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->



<!-- =========================================================================================================== -->
<div id="centro" style="height:8px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<?php
	if ($linha_compra == 0)
	{echo "<div id='centro' style='height:25px; width:350px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:2px; margin-left:10px'></div>";}
	else
	{echo "
	<div id='centro' style='height:25px; width:170px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:2px; margin-left:10px'>
	Alterar vencimento para:
	</div>
	<div id='centro' style='height:25px; width:110px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:0px'>
	<form action='$servidor/$diretorio_servidor/financeiro/compras/relatorio_compras_pagar_geral.php' method='post'>
	<input type='text' name='data_vencimento' value='$data_vencimento_aux' maxlength='10' onkeypress='mascara(this,data)' id='calendario_3' style='color:#0000FF; width:90px' />
	</div>
	<div id='centro' style='height:25px; width:60px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:2px;'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='altera_todos'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='monstra_ted' value='$monstra_ted'>
	<input type='hidden' name='filial_pgto' value='$filial_pgto'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/ok.png' height='20px' />
	</form>
	</div>
	";}
	?>	


	

	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS A PAGAR: <b>R$ $saldo_pagar_total_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- =========================================================================================================== -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:50px; border:0px solid #000; margin:auto; float:left">
	</div>




</div>


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