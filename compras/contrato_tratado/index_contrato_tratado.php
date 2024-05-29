<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "index_contrato_tratado";
$titulo = "Contratos Tratados";
$modulo = "compras";
$menu = "contratos";	

// ====== RECEBE POST ===========================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;

$codigo_contrato = $_POST["codigo_contrato"];
$numero_contrato = $_POST["numero_contrato"];
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$botao = $_POST["botao"];
$monstra_situacao = $_POST["monstra_situacao"];

$produtor = $_POST["produtor"];
$produto = $_POST["produto"];
$produto_list = $_POST["produto"];
$data_contrato = $_POST["data_contrato"];
$quantidade_adquirida = $_POST["quantidade_adquirida"];
$quantidade_a_entregar = $_POST["quantidade_a_entregar"];
$unidade = $_POST["unidade"];
$tipo = $_POST["tipo"];
$obs = $_POST["obs"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
// =================================================================================================================


// ======== BAIXA CONTRATO ====================================================================================================
if ($botao == "baixar")
{
$obs_aux = "N&ordm; Contrato: " . $numero_contrato . " - " . $obs;
$baixar = mysqli_query ($conexao, "UPDATE contrato_tratado SET situacao_contrato='PAGO' WHERE codigo='$codigo_contrato'");
}

else
{}
// =================================================================================================================


// ======== ESTORNA CONTRATO ====================================================================================================
if ($botao == "estornar")
{
$estornar = mysqli_query ($conexao, "UPDATE contrato_tratado SET situacao_contrato='EM_ABERTO' WHERE codigo='$codigo_contrato'");
}

else
{}
// =================================================================================================================


// ======== EXCLUI CONTRATO ====================================================================================================

if ($botao == "excluir")
{
$excluir = mysqli_query ($conexao, "UPDATE contrato_tratado SET estado_registro='EXCLUIDO' WHERE codigo='$codigo_contrato'");
}

else
{}

// =================================================================================================================


// ======== TIRAR PENDENCIA DE CONTRATO ============================================================================
if ($botao == "tirar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_tratado SET pendencia='NAO' WHERE codigo='$codigo_contrato'");
}
elseif ($botao == "colocar_pendencia")
{
$tirar_pendencia = mysqli_query ($conexao, "UPDATE contrato_tratado SET pendencia='SIM' WHERE codigo='$codigo_contrato'");
}
else
{}
// =================================================================================================================




// ======== BUSCA CONTRATOS ====================================================================================================
if ($monstra_situacao == "todos")
{	
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

elseif ($monstra_situacao == "aberto")
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='EM_ABERTO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

elseif ($monstra_situacao == "pagos")
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND filial='$filial' AND situacao_contrato='PAGO' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}

else
{
$busca_cont_tratado = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND situacao_contrato='EM_ABERTO' AND filial='$filial' ORDER BY data, nome_produtor");
$linha_cont_tratado = mysqli_num_rows ($busca_cont_tratado);
}
// ================================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ================================================================================================================================


// ================================================================================================================================
	include ('../../includes/head.php');
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
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>

<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Contratos Tratados
    </div>

	<div style="width:460px; height:30px; float:left; border:0px solid #000; text-align:right; font-size:12px; color:#003466">
    	<div id="menu_atalho_3" style="margin-top:7px">
    	<!--
        <a href='<?php //echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/relatorios.php' >
        &#8226; Relat&oacute;rios de contratos tratados</a>
        -->
        </div>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<!--
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    <?php // echo "$msg"; ?>
    </div>
</div>

<div style="width:1080px; height:5px; border:0px solid #000; margin:auto"></div>
-->
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:85px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:80px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/index_contrato_tratado.php" method="post" />
		<input type='hidden' name='botao' value='busca' />
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

		
		<div id="centro" style="width:70px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:65px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "todos")
			{echo "<input type='radio' name='monstra_situacao' value='todos' checked='checked' /><i>Todos</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='todos' /><i>Todos</i>";}
			?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "aberto")
			{echo "<input type='radio' name='monstra_situacao' value='aberto' checked='checked' /><i>Em aberto</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='aberto' /><i>Em aberto</i>";}
			?>
		</div>
		
		<div id="centro" style="width:90px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:85px; height:3px; float:left; border:0px solid #999"></div>
			<?php
			if ($monstra_situacao == "pagos")
			{echo "<input type='radio' name='monstra_situacao' value='pagos' checked='checked' /><i>Pagos</i>";}
			else
			{echo "<input type='radio' name='monstra_situacao' value='pagos' /><i>Pagos</i>";}
			?>
		</div>
<!--
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
-->

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height='20px' style="float:left" />
		</form>
		</div>
	
		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>


        <!-- =====================================================================================================================
        
		ALTERAÇÃO DO MODELO DE CONTRATO Tratado
		CONTRATO Tratado MODELO 1 - SEM PREÇO FIXADO (../contrato_tratado_cadastro_1.php)
		CONTRATO Tratado MODELO 2 - COM PREÇO FIXADO (../contrato_tratado_cadastro_2.php)
        OBS.: Mudar no link "2ª via" linha 745 -->
        
		<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/contrato_tratado_seleciona.php'>
        
        <!-- ===================================================================================================================== -->
        
        <input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/novo.jpg" border="0" style="float:left" />
		</a>
		</div>	
		
		
	</div>
	
</div>

<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>



<div id="centro" style="height:26px; width:1250px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:250px; float:left; height:26px; margin-left:10px; border:0px solid #999">
		<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/contrato_tratado_seleciona.php">
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Novo Contrato</button>
		</a>
	</div>
	
	<div id="centro" style="width:250px; float:left; height:26px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
		<?php 
		if ($linha_cont_tratado >= 1)
		{echo"
		<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_relatorio_impressao.php' target='_blank' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='imprimir'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Imprimir</button></form>";}
        ?>
	</div>

	<div id="centro" style="width:250px; float:right; height:26px; border:0px solid #999; font-size:12px; color:#003466; text-align:right; margin-right:10px">
		<?php 
		if ($linha_cont_tratado == 1)
		{echo"<i><b>$linha_cont_tratado</b> Contrato</i>";}
		elseif ($linha_cont_tratado == 0)
		{echo"";}
		else
		{echo"<i><b>$linha_cont_tratado</b> Contratos</i>";}
        ?>
	</div>
</div>
<!-- ====================================================================================== -->





<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto; border-radius:5px"></div>

<?php
	if ($linha_cont_tratado == 0 and $botao == "busca")
	{echo"
	<div id='centro' style='height:auto; width:1080px; border:0px solid #999; margin:auto; border-radius:5px'>
	";}
	else
	{echo"
	<div id='centro' style='height:auto; width:1080px; border:0px solid #999; margin:auto; border-radius:5px'>
	<div style='height:12px; width:240px; border:0px solid #911FB0; font-size:11px; color:#666; text-align:left; margin-top:8px; margin-left:35px'>
		Total de Contratos Tratados:
	</div>
	";}



for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	if ($monstra_situacao == "todos")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}

	elseif ($monstra_situacao == "aberto")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}
	
	elseif ($monstra_situacao == "pagos")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND data_entrega_i>='$data_inicial' AND data_entrega_f<='$data_final' AND situacao_contrato='PAGO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}

	elseif ($monstra_situacao == "produtor")
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND filial='$filial' AND produto='$aux_bp_geral[20]' AND produtor='$fornecedor' AND situacao_contrato='EM_ABERTO'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}
	
	else
	{$soma_tratados = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade_total) FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' 
	AND situacao_contrato='EM_ABERTO' AND filial='$filial' AND produto='$aux_bp_geral[20]'"));
	$soma_tratados_print = number_format($soma_tratados[0],2,",",".");}


	if ($soma_tratados[0] == 0)
	{}
	else
	{echo "
	<div style='height:20px; width:240px; border:1px solid #999; border-radius:7px; background-color:#EEE; margin-left:25px; margin-top:8px'>
    	<div style='font-size:10px; color:#009900; float:left; margin-left:8px; margin-top:2px'>$aux_bp_geral[22]: </div>
		<div style='font-size:10px; color:#666; float:left; margin-left:8px; margin-top:2px'><b>$soma_tratados_print</b> $aux_bp_geral[26]</div>
    </div>";}

}
?>

	
</div>
<!-- ====================================================================================== -->



<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->
<?php
if ($linha_cont_tratado == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php

if ($linha_cont_tratado == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='275px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='65px' align='center' bgcolor='#006699'>N&ordm; Contrato</td>
<td width='70px' align='center' bgcolor='#006699'>Data Entrega</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='82px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='50px' align='center' bgcolor='#006699'>Unidade</td>
<td width='82px' align='center' bgcolor='#006699'>Pre&ccedil;o Tratado</td>
<td width='52px' align='center' bgcolor='#006699'>2&ordf; Via</td>
<td width='52px' align='center' bgcolor='#006699'>Baixar</td>
<td width='52px' align='center' bgcolor='#006699'>Estornar</td>
<td width='52px' align='center' bgcolor='#006699'>Excluir</td>
<!-- <td width='52px' align='center' bgcolor='#006699'>Pend&ecirc;ncia</td> -->
</tr>
</table>";}


?>


<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php

for ($w=1 ; $w<=$linha_cont_tratado ; $w++)
{
	$aux_contrato = mysqli_fetch_row($busca_cont_tratado);

// DADOS DO CONTRATO =========================
	$produtor = $aux_contrato[1];
	$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
	$produto = $aux_contrato[5];
	$quantidade = $aux_contrato[6];
	$quantidade_total = number_format($aux_contrato[23],2,",",".");
	$valor_total = number_format($aux_contrato[22],2,",",".");
	$valor_un = number_format($aux_contrato[9],2,",",".");
	$unidade = $aux_contrato[21];
	$descricao_produto = $aux_contrato[8];
	$data_entrega_i = date('d/m/Y', strtotime($aux_contrato[3]));
	$data_entrega_f = date('d/m/Y', strtotime($aux_contrato[4]));
	$fiador_1 = $aux_contrato[12];
	$fiador_2 = $aux_contrato[13];
	$safra = $aux_contrato[10];
	$prazo_pgto = $aux_contrato[11];
	$observacao = $aux_contrato[14];
	$estado_registro = $aux_contrato[15];
	$quantidade_fracao = $aux_contrato[7];
	$situacao_contrato = $aux_contrato[16];
	$numero_contrato = $aux_contrato[20];
	$usuario_cadastro = $aux_contrato[24];
	$hora_cadastro = $aux_contrato[26];
	$data_cadastro = date('d/m/Y', strtotime($aux_contrato[25]));
	$filial = $aux_contrato[24];


// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$produtor' ORDER BY nome");
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

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_contrato == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_contrato == "PAGO")
	{$situacao_print = "Liquidado";}
	else
	{$situacao_print = "-";}




// RELATORIO =========================
	if ($situacao_contrato == "EM_ABERTO")
	{echo "<tr style='color:#00F' title='N&ordm; Contrato: $numero_contrato&#013;Data da Entrega: $data_entrega_i at&eacute; $data_entrega_f&#013;Pre&ccedil;o Tratado: R$ $valor_un&#013;Valor Total: R$ $valor_total&#013;Safra: $safra&#013;Prazo de PGTO: $prazo_pgto dias&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";}
	else
	{echo "<tr style='color:#333' title='N&ordm; Contrato: $numero_contrato&#013;Data da Entrega: $data_entrega_i at&eacute; $data_entrega_f&#013;Pre&ccedil;o Tratado: R$ $valor_un&#013;Valor Total: R$ $valor_total&#013;Safra: $safra&#013;Prazo de PGTO: $prazo_pgto dias&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro'>";}

	echo "
	<td width='70px' align='left'>&#160;$data_contrato_print</td>
	<td width='275px' align='left'>&#160;$fornecedor_print</td>
	<td width='65px' align='center'>$numero_contrato</td>
	<td width='70px' align='center'>$data_entrega_i</td>
	<td width='100px' align='center'>$produto_print</td>
	<td width='82px' align='center'>$quantidade_total</td>
	<td width='50px' align='center'>$unidade</td>
	<td width='82px' align='center'>$valor_un</td>";

	if ($permissao[47] == 'S')
	{echo "	
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_impressao.php' method='post' target='_blank'>
	<input type='hidden' name='numero_contrato' value='$numero_contrato' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='18px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}
	
	if ($situacao_contrato == "EM_ABERTO" and $permissao[48] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/index_contrato_tratado.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='baixar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='hidden' name='produtor' value='$produtor'>
	<input type='hidden' name='produto' value='$produto'>
	<input type='hidden' name='data_contrato' value='$aux_contrato[2]'>
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida'>
	<input type='hidden' name='quantidade_a_entregar' value='$quantidade_a_entregar'>
	<input type='hidden' name='unidade' value='$unidade'>
	<input type='hidden' name='tipo' value='$tipo'>
	<input type='hidden' name='obs' value='$observacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ok.png' height='18px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}


	if ($situacao_contrato == "PAGO" and $permissao[49] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/index_contrato_tratado.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='estornar'>
	<input type='hidden' name='produtor' value='$produtor'>
	<input type='hidden' name='produto' value='$produto'>
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida'>
	<input type='hidden' name='quantidade_a_entregar' value='$quantidade_a_entregar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='18px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}

	
	if ($situacao_contrato == "EM_ABERTO" and $permissao[50] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/index_contrato_tratado.php' method='post'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='excluir'>
	<input type='hidden' name='produtor' value='$produtor'>
	<input type='hidden' name='produto' value='$produto'>
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida'>
	<input type='hidden' name='quantidade_a_entregar' value='$quantidade_a_entregar'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='18px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}

	
	echo "
	</tr>";

}


// =================================================================================================================

?>
</table>

<?php

if ($linha_cont_tratado == 0 and $botao == "busca")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum contrato encontrado.</i></div>";}
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