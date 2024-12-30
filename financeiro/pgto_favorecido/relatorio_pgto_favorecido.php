<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "relatorio_pgto_favorecido";
$titulo = "Relat&oacute;rio de Pagamentos (Por Favorecido)";
$modulo = "financeiro";
$menu = "contas_pagar";

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

$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$produto_list = $_POST["produto_list"];
$fornecedor = $_POST["representante"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];



	$busca_cod_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO' ORDER BY codigo");
	$linha_cod_pessoa = mysqli_num_rows ($busca_cod_pessoa);

		for ($c=1 ; $c<=$linha_cod_pessoa ; $c++)
		{
		$aux_cod = mysqli_fetch_row($busca_cod_pessoa);
		$cod_pessoa = $aux_cod[35];
		}




	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND codigo_pessoa='$cod_pessoa' AND filial='$filial' ORDER BY codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);


	// SOMA PAGAMENTOS  ==========================================================================================
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND codigo_pessoa='$cod_pessoa' AND filial='$filial' AND situacao_pagamento='PAGO'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");



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
	&#160;&#160;&#8226; <b>Relat&oacute;rio de Pagamentos</b> - Por Favorecido
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
		<form name="compra_cafe" action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/pgto_favorecido/relatorio_pgto_favorecido.php" method="post" />
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

		<div id="centro" style="width:110px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:105px; height:8px; float:left; border:0px solid #999"></div>
		<i>Favorecido (F2):&#160;</i></div>

		<div id="centro" style="width:480px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:470px; height:3px; float:left; border:0px solid #999"></div>

			<!-- ========================================================================================================== -->
			<script type="text/javascript">
			function abrir(programa,janela)
				{
					if(janela=="") janela = "janela";
					window.open(programa,janela,'height=270,width=700');
				}
			
			</script>
			<script type="text/javascript" src="representante_funcao.js"></script>
			
			<!-- ========================================================================================================== -->
			<div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
			<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar produtor" />
			</div>

			<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">
			&#160;

			<!-- ========================================================================================================== -->
			<script type="text/javascript">
			document.onkeyup=function(e)
				{
					if(e.which == 113)
					{
						//Pressionou F2, aqui vai a função para esta tecla.
						//alert(tecla F2);
						var aux_f2 = document.compra_cafe.representante.value;
						javascript:foco('busca');
						javascript:abrir('busca_pessoa_popup.php');
						//javascript:buscarNoticias(aux_f2);
					}
				}
			</script>

			<!-- ========================================================================================================== -->
			<input id="busca" type="text" name="representante" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; width:50px; font-size:12px" value="<?php echo"$fornecedor"; ?>" />&#160;</div>
			<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
			<div id="resultado" style="width:280px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:10px; font-style:normal; padding-top:3px; padding-left:5px"></div>


			</div>


		
			<div id="centro" style="width:60px; float:left; height:22px; border:0px solid #999; text-align:left">
			<div id="geral" style="width:55px; height:3px; float:left; border:0px solid #999"></div>
			<button type='submit' class='botao_1' style='margin-left:10px'>Buscar</button>
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
    <?php
	// BUSCA PESSOA  ==========================================================================================
	$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
	$linha_fornecedor = mysqli_num_rows ($busca_fornecedor);
		for ($f=1 ; $f<=$linha_fornecedor ; $f++)
		{
		$aux_fornecedor = mysqli_fetch_row($busca_fornecedor);
		$forn_print = $aux_fornecedor[1];
		}

	echo"Favorecido: <b>$forn_print</b>";
	?>
	</div>
	
	<div id="centro" style="width:250px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
	</div>
	
	<div id="centro" style="width:30px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:left">
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
<td width='90px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='300px' align='center' bgcolor='#006699'>Preposto</td>
<td width='100px' align='center' bgcolor='#006699'>N&ordm; Compra</td>
<td width='100px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='270px' align='center' bgcolor='#006699'>Dados Banc&aacute;rios</td>
<td width='100px' align='center' bgcolor='#006699'>Valor Pago</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linha_pagamento ; $x++)
{
$aux_favorecido = mysqli_fetch_row($busca_pagamento);
$preposto = $aux_favorecido[21];

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);
	$num_compra = ($aux_favorecido[1]);

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
	{$tipo_conta_print_2 = "C/P";}
	else
	{$tipo_conta_print_2 = "C.";}
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$preposto' ORDER BY nome");
	$aux_p2 = mysqli_fetch_row($busca_pessoa_2);
	$linha_busca_pessoa_2 = mysqli_num_rows ($busca_pessoa_2);
	if ($linha_busca_pessoa_2 >= 1)
	{
	$nome_favorecido_2 = $aux_p2[1];
	$tipo_pessoa_2 = $aux_p2[2];
		if ($tipo_pessoa_2 == "pf" or $tipo_pessoa_2 == "PF")
		{$cpf_cnpj_2 = $aux_p2[3];}
		else
		{$cpf_cnpj_2 = $aux_p2[4];}
	}
	else
	{
	$nome_favorecido_2 = "(SOLICITA&Ccedil;&Atilde;O DE PGTO)";
	}
		
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
	{$dados_bancarios_2 = " $aux_favorecido[6] ( N&ordm; cheque: $aux_favorecido[18] )";}
	elseif ($aux_favorecido[3] == "TED")
	{$dados_bancarios_2 = "$banco_print_2 Ag. $agencia_2 $tipo_conta_print_2 $conta_2";}
	elseif ($aux_favorecido[3] == "DINHEIRO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "PREVISAO")
	{$dados_bancarios_2 = "";}
	elseif ($aux_favorecido[3] == "OUTRA")
	{$dados_bancarios_2 = "$obs_pgto";}
	else
	{$dados_bancarios_2 = "-";}







// RELATORIO =========================
	echo "
	<tr style='color:#00F' title='Observa&ccedil;&atilde;o: $obs_pgto'>
	<td width='90px' align='left'><div style='margin-left:6px; margin-right:0px'>$data_pagamento_print_2</div></td>";
	
	if ($conta_conjunta == "SIM")
	{echo "<td width='300px' align='left'><div style='margin-left:6px; margin-right:0px'>$nome_favorecido_2 (*)</div></td>";}
	else
	{echo "<td width='300px' align='left'><div style='margin-left:6px; margin-right:0px'>$nome_favorecido_2</div></td>";}

	
	
	echo "
	<td width='100px' align='center'><div style='margin-left:0px; margin-right:0px'>$num_compra</div></td>
	<td width='100px' align='left'><div style='margin-left:6px; margin-right:0px'>$forma_pagamento_2</div></td>
	<td width='270px' align='left'><div style='margin-left:6px; margin-right:0px'>$dados_bancarios_2</div></td>
	<td width='100px' align='right'><div style='margin-left:0px; margin-right:6px'>$valor_pagamento_print_2</div></td>";
				
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



<!-- =========================================================================================================== -->
<div id="centro" style="height:8px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	</div>
	
	<div id="centro" style="width:320px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:center">
    <?php
	if ($linha_pagamento >= 1)
	{echo"TOTAL DE PAGAMENTOS: <b>R$ $soma_pagamentos_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- =========================================================================================================== -->




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