<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "index_contas_pagar";
$titulo = "Relat&oacute;rio - Contas a Pagar";
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
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">

<?php

// =================================================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;
$codigo_pagamento = $_POST["codigo_pagamento"];
$botao = $_POST["botao"];

if ($botao == "")
{
$data_inicial_aux = $data_hoje_br;
$data_inicial = Helpers::ConverteData($data_hoje_br);
$data_final_aux = $data_hoje_br;
$data_final = Helpers::ConverteData($data_hoje_br);
}
else
{
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
}

if ($_POST["forma_pagamento"] == "")
{$forma_pagamento = "GERAL";}
else
{$forma_pagamento = $_POST["forma_pagamento"];}

if ($_POST["status_pagamento"] == "")
{$status_pagamento = "GERAL";}
else
{$status_pagamento = $_POST["status_pagamento"];}


// ==================================================================================================================================================
if ($botao == "baixar")
{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE codigo='$codigo_pagamento'");}
else
{}


// ==================================================================================================================================================
if ($botao == "baixar_todos")
{
	if ($forma_pagamento == "GERAL")
	{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE filial='$filial' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento!='PREVISAO'");}
	else
	{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='PAGO' WHERE filial='$filial' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND forma_pagamento!='PREVISAO'");}
}
else
{}

// ==================================================================================================================================================
if ($botao == "estornar_todos")
{
	if ($forma_pagamento == "GERAL")
	{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE filial='$filial' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento!='PREVISAO'");}
	else
	{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE filial='$filial' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND forma_pagamento!='PREVISAO'");}
}
else
{}



// ==================================================================================================================================================
if ($botao == "estornar")
{$baixar = mysqli_query ($conexao, "UPDATE favorecidos_pgto SET situacao_pagamento='EM_ABERTO' WHERE codigo='$codigo_pagamento'");}
else
{}

// ==================================================================================================================================================
if ($status_pagamento == "EM_ABERTO")
{

	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='EM_ABERTO' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='EM_ABERTO' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='EM_ABERTO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}

// ==================================================================================================================================================
elseif ($status_pagamento == "PAGO")
{
	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='PAGO' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='PAGO' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND situacao_pagamento='PAGO' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}

// ==================================================================================================================================================
else
{
	if ($forma_pagamento == "GERAL")
	{	
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
	else
	{
	$busca_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND filial='$filial' ORDER BY data_pagamento, codigo");
	$linha_pagamento = mysqli_num_rows ($busca_pagamento);
	// ========= SOMAS COMPRAS =========
	$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND data_pagamento>='$data_inicial' AND data_pagamento<='$data_final' AND forma_pagamento='$forma_pagamento' AND filial='$filial'"));
	$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
	}
}
// ==================================================================================================================================================
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



<!-- ====== CENTRO ================================================================================================= -->


<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- INÍCIO CENTRO GERAL -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Relat&oacute;rio de Pagamentos
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px">
	Por Per&iacute;odo
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:15px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1050px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/contas_pagar/index_contas_pagar.php" method="post" />
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

		<div id="centro" style="width:115px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:110px; height:8px; float:left; border:0px solid #999"></div>
		<i>Forma de PGTO:&#160;</i></div>

		<div id="centro" style="width:170px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:165px; height:3px; float:left; border:0px solid #999"></div>
        <select name="forma_pagamento" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:160px" />
		<?php
            $busca_forma_pgto = mysqli_query ($conexao, "SELECT * FROM forma_pagamento WHERE estado_registro='ATIVO' AND tipo='D' ORDER BY codigo");
            $linhas_forma_pgto = mysqli_num_rows ($busca_forma_pgto);
        
		if ($forma_pagamento == "GERAL")
		{echo "<option value='GERAL' selected='selected'>(Geral)</option>";}
		else
		{echo "<option value='GERAL'>(Geral)</option>";}
		
        for ($d=1 ; $d<=$linhas_forma_pgto ; $d++)
        {
        $aux_forma_pgto = mysqli_fetch_row($busca_forma_pgto);	
	  
			if ($forma_pagamento == "$aux_forma_pgto[2]")
			{echo "<option value='$aux_forma_pgto[2]' selected='selected'>$aux_forma_pgto[1]</option>";}
			else
			{echo "<option value='$aux_forma_pgto[2]'>$aux_forma_pgto[1]</option>";}

        }
        ?>
        </select>
		</div>


		<div id="centro" style="width:115px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:110px; height:8px; float:left; border:0px solid #999"></div>
		<i>Status do PGTO:&#160;</i></div>

		<div id="centro" style="width:110px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:105px; height:3px; float:left; border:0px solid #999"></div>
        <select name="status_pagamento" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:100px" />
        <?php
		if ($status_pagamento == "GERAL")
		{echo "<option value='GERAL' selected='selected'>(Geral)</option>";}
		else
		{echo "<option value='GERAL'>(Geral)</option>";}

		if ($status_pagamento == "EM_ABERTO")
		{echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
		else
		{echo "<option value='EM_ABERTO'>Em Aberto</option>";}

		if ($status_pagamento == "PAGO")
		{echo "<option value='PAGO' selected='selected'>Pago</option>";}
		else
		{echo "<option value='PAGO'>Pago</option>";}
		?>
        </select>
		</div>



		<div id="centro" style="width:130px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:125px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height='20px' style="float:left" />
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
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/relatorio_pgtos_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='botao' value='1' />
	<input type='hidden' name='forma_pagamento' value='$forma_pagamento' />
	<input type='hidden' name='status_pagamento' value='$status_pagamento' />	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='20px' /></form>
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
<td width='54px' align='center' bgcolor='#006699'>Baixar</td>
<td width='54px' align='center' bgcolor='#006699'>Estornar</td>
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



	
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/index_contas_pagar.php' method='post'>
	<input type='hidden' name='codigo_pagamento' value='$aux_favorecido[0]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='baixar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='status_pagamento' value='$status_pagamento'>
	<input type='hidden' name='forma_pagamento' value='$forma_pagamento'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/ok.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='54px' align='center'></td>";}


	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "<td width='54px' align='center'></td>";}
	else
	{echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/index_contas_pagar.php' method='post'>
	<input type='hidden' name='codigo_pagamento' value='$aux_favorecido[0]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='estornar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='status_pagamento' value='$status_pagamento'>
	<input type='hidden' name='forma_pagamento' value='$forma_pagamento'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' /></form>	
	</td>";}

	
	
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




<div id="centro" style="height:70px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:50px; border:0px solid #000; margin:auto; float:left">
	</div>


	<?php
	if ($linha_pagamento == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:50px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/index_contas_pagar.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='baixar_todos'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='status_pagamento' value='$status_pagamento'>
	<input type='hidden' name='forma_pagamento' value='$forma_pagamento'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/ok.png' height='20px' />
	</form>
	</div>
	<div id='centro' style='height:25px; width:200px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:2px'>
	Baixar Todos
	</div>";}
	?>	


	<?php
	if ($linha_pagamento == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:50px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px'>
	<form action='$servidor/$diretorio_servidor/financeiro/contas_pagar/index_contas_pagar.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='estornar_todos'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='status_pagamento' value='$status_pagamento'>
	<input type='hidden' name='forma_pagamento' value='$forma_pagamento'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' />
	</form>
	</div>
	<div id='centro' style='height:25px; width:200px; border:0px solid #000; margin:auto; float:left; color:#666; font-size:12px; margin-top:2px'>
	Estornar Todos
	</div>";}
	?>

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