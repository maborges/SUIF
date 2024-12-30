<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "acerto_quantidade_relatorio";
$titulo = "Relat&oacute;rio de Acerto de Quantidade";
$modulo = "compras";
$menu = "relatorio";


// ======== DADOS PARA BUSCA =========================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$botao = $_POST["botao"];
// =================================================================================================================


// ========  BUSCA COMRPA  =========================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND filial='$filial' AND (desconto_quantidade IS NOT NULL OR desconto_quantidade!='0') ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
// =================================================================================================================


// =================================================================================================================
include ("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
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
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Relat&oacute;rio de Acerto de Quantidade
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    Quebras / Des&aacute;gios
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div style="height:30px; width:940px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left; margin-left:70px">
	<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/acerto_quantidade_relatorio.php" method="post" />
	<input type='hidden' name='botao' value='1' />
		<div style="width:100px; float:left; border:0px solid #999; color:#666; text-align:right; margin-top:7px">
		<i>Data inicial:</i>
        </div>

		<div style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:4px; margin-left:10px">
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div style="width:100px; float:left; border:0px solid #999; color:#666; text-align:right; margin-top:7px; margin-left:10px">
		<i>Data Final:</i>
		</div>

		<div style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:4px; margin-left:10px">
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>


<!--		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "todas")
			{echo "<input type='radio' name='monstra_situacao' value='todas' checked='checked' /><i>Todas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todas' /><i>Todas</i>";}
			*/?>
		</div>
		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			*/?>
		</div>
		
		<div style="width:65px; float:left; border:0px solid #999; text-align:left; margin-top:3px">
			<?php /*
			if ($monstra_situacao == "pagas")
			{echo "<input type='radio' name='monstra_situacao' value='pagas' checked='checked' /><i>Pagas</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagas' /><i>Pagas</i>";}
			*/?>
		</div>
-->
		<div style="width:120px; float:left; border:0px solid #999; text-align:left; margin-top:3px; margin-left:10px">
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
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra alterada</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras alteradas</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	/*
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
	else
	{ }
	*/
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
<td width='340px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm; Compra</td>
<td width='110px' align='center' bgcolor='#006699'>Produto</td>
<td width='85px' align='center' bgcolor='#006699'>Quant. Anterior</td>
<td width='85px' align='center' bgcolor='#006699'>Quant. Atual</td>
<td width='85px' align='center' bgcolor='#006699'>Desconto</td>
<td width='120px' align='center' bgcolor='#006699'>Alterado por</td>
<td width='70px' align='center' bgcolor='#006699'>Data Alter.</td>
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
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$usuario_alteracao = $aux_compra[21];
if ($aux_compra[23] == "")
{$data_alteracao = "";}
else
{$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));}
$hora_alteracao = $aux_compra[22];
if ($aux_compra[44] == "")
{$usuario_altera_quant = $usuario_alteracao;}
else
{$usuario_altera_quant = $aux_compra[44];}
if ($aux_compra[45] == "")
{$data_altera_quant = $data_alteracao;}
else
{$data_altera_quant = date('d/m/Y', strtotime($aux_compra[45]));}
$hora_altera_quant = $aux_compra[46];

$motivo_alteracao_quant = $aux_compra[35];
$quantidade_original = number_format($aux_compra[36],2,",",".");
$desconto_quantidade = number_format($aux_compra[29],2,",",".");
$desconto_quantidade_2 = $aux_compra[29];
$valor_total_original = number_format($aux_compra[37],2,",",".");
$desconto_em_valor = ($aux_compra[29] * $aux_compra[6]);
$desc_em_valor_print = number_format($desconto_em_valor,2,",",".");
$quantidade_original_primaria = number_format($aux_compra[47],2,",",".");


// ======================================================================================================


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


// BUSCA PAGAMENTO  ==========================================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$numero_compra' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");

// CALCULO SALDO A PAGAR  ==========================================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");

// CALCULO QUANTIDADE A ENTREGAR  ==========================================================================================
$quant_entregar = $saldo_a_pagar_aux / $preco_unitario;
$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
$quant_entregar_aux_2 = number_format($quant_entregar_aux,2,".","");
$quant_entregar_print = number_format($quant_entregar_aux,2,",",".");

// RELATORIO =========================
	echo "
	<tr style='color:#000099' title='Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao&#013;Quant. alterada por: $usuario_altera_quant $data_altera_quant $hora_altera_quant&#013;Motivo da altera&ccedil;&atilde;o: $motivo_alteracao_quant&#013;Valor original: R$ $valor_total_original&#013;Desconto: $desconto_quantidade $unidade_print (R$ $desc_em_valor_print)&#013;Quantidade original: $quantidade_original_primaria $unidade_print'>";
	echo "
	<td width='70px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
	<td width='340px' align='left'><div style='margin-left:3px'>$fornecedor_print</div></td>
	<td width='60px' align='center'>$num_compra_print</td>
	<td width='110px' align='center'>$produto_print</td>
	<td width='85px' align='right'><div style='margin-right:3px'>$quantidade_original $unidade_print</div></td>
	<td width='85px' align='right'><div style='margin-right:3px'>$quantidade_print $unidade_print</div></td>
	<td width='85px' align='right'><div style='margin-right:3px; color:#F00'>$desconto_quantidade $unidade_print</div></td>
	<td width='120px' align='center'>$usuario_altera_quant</td>
	<td width='70px' align='center'>$data_altera_quant</td>";
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



<div id="centro" style="height:40px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:60px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:60px; width:1080px; border:0px solid #000; margin:auto"></div>

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