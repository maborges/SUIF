<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'solicitacao_pagamento';
$titulo = 'Solicita&ccedil;&atilde;o de Remessa';
$menu = 'contas_pagar';
$modulo = 'financeiro';

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
//$numero_compra = $_POST["numero_compra"];

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao_relatorio = $_POST["botao_relatorio"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];
$num_compra_aux = $_POST["num_compra_aux"];

$codigo_pgto_favorecido = $_POST["codigo_pgto_favorecido"];

$codigo_favorecido = $_POST["representante"];
$forma_pagamento = $_POST["forma_pagamento"];
$data_pagamento = Helpers::ConverteData($_POST["data_pagamento"]);
$data_pagamento_print = $_POST["data_pagamento"];	
$valor_pagamento = Helpers::ConverteValor($_POST["valor_pagamento"]);
//ERRO
//$valor_pagamento_print = number_format($valor_pagamento,2,",",".");
$valor_pagamento_print = number_format($_POST["valor_pagamento"],2,",",".");
$banco_cheque = $_POST["banco_cheque"];
$numero_cheque = $_POST["numero_cheque"];
$obs_pgto = $_POST["obs_pgto"];
$botao = $_POST["botao"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());

$filial = $filial_usuario;


$hoje = date ("d/m/Y", time());
$hoje_bd = date ("Y-m-d", time());
	if ($botao == "incluir")
	{$data_print = $data_pagamento_print;}
	else
	{$data_print = $hoje;}

// =============================================================================================================
// =============================================================================================================


// ACHA FAVORECIDO  ==========================================================================================
$acha_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$codigo_favorecido' ORDER BY nome");
$linha_acha_favorecido = mysqli_num_rows ($acha_favorecido);
for ($f=1 ; $f<=$linha_acha_favorecido ; $f++)
{
	$aux_fav = mysqli_fetch_row($acha_favorecido);
	$cod_pessoa_1 = $aux_fav[1];
	$banco_ted = $aux_fav[2];
}

	if ($forma_pagamento == "PREVISAO")
	{
		$acha_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo_pessoa='$codigo_pessoa_fav' ORDER BY nome");
		$linha_acha_favorecido_2 = mysqli_num_rows ($acha_favorecido_2);
		for ($e=1 ; $e<=$linha_acha_favorecido_2 ; $e++)
		{
			$aux_fav_2 = mysqli_fetch_row($acha_favorecido_2);
			$codigo_fav_aux = $aux_fav_2[0];
		}
	}





// =================================================================================================================
	
if ($botao == "incluir")
{
		if ($forma_pagamento == '')
		{
		$mensagem_erro = "&#10033; <i>Selecione a forma de pagamento</i>";
		$erro = 1;
		// header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_1.php");
		}
		
		elseif ($data_pagamento == '')
		{
		$mensagem_erro = "&#10033; <i>Informe a data de pagamento</i>";
		$erro = 2;
		// header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_2.php");
		}
		
		elseif ($valor_pagamento == '' or $valor_pagamento == 0)
		{
		$mensagem_erro = "&#10033; <i>Informe o valor do pagamento</i>";
		$erro = 3;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_3.php");
		}
		
		elseif ($codigo_favorecido == '')
		{
		$mensagem_erro = "&#10033; <i>Informe o favorecido do pagamento</i>";
		$erro = 4;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_4.php");
		}
	
		elseif ($linha_acha_favorecido == 0)
		{
		$mensagem_erro = "&#10033; <i>Favorecido inexistente</i>";
		$erro = 6;
		//header ("Location: $servidor/$diretorio_servidor/compras/forma_pagamento/aviso_6.php");
		}
		
		else
		{
			$inserir = mysqli_query ($conexao, "INSERT INTO favorecidos_pgto (codigo, codigo_favorecido, forma_pagamento, data_pagamento, valor, banco_cheque, observacao, usuario_cadastro, 
			hora_cadastro, data_cadastro, usuario_alteracao, hora_alteracao, data_alteracao, estado_registro, situacao_pagamento, filial, codigo_pessoa, numero_cheque, banco_ted, origem_pgto) VALUES 
			(NULL, '$codigo_favorecido', '$forma_pagamento', '$data_pagamento', '$valor_pagamento', '$banco_cheque', '$obs_pgto', '$usuario_cadastro', '$hora_cadastro', 
			'$data_cadastro', '$usuario_alteracao', '$hora_alteracao', '$data_alteracao', 'ATIVO', 'EM_ABERTO', '$filial', '$cod_pessoa_1', '$numero_cheque', '$banco_ted', 'SOLICITACAO')");
		
				if ($forma_pagamento == "CHEQUE")
				{
				$inserir_cheque = mysqli_query ($conexao, "INSERT INTO cheques (codigo, codigo_compra, codigo_favorecido, forma_pagamento, data_pagamento, valor, banco_cheque, usuario_cadastro, 
				hora_cadastro, data_cadastro, usuario_alteracao, hora_alteracao, data_alteracao, estado_registro, situacao_pagamento, filial, codigo_pessoa, numero_cheque, comp_cheque) VALUES 
				(NULL, '$numero_compra', '$codigo_favorecido', '$forma_pagamento', '$data_pagamento', '$valor_pagamento', '$banco_cheque', '$usuario_cadastro', '$hora_cadastro', 
				'$data_cadastro', '$usuario_alteracao', '$hora_alteracao', '$data_alteracao', 'ATIVO', 'EM_ABERTO', '$filial', '$cod_pessoa_1', '$numero_cheque', 'N')");
				}

				$banco_cheque = "";
				$numero_cheque = "";
				$codigo_favorecido = "";
		}
}

elseif ($botao == "excluir")
{
$delete = mysqli_query ($conexao, "DELETE FROM favorecidos_pgto WHERE codigo='$codigo_pgto_favorecido'");
}

else
{}





// =============================================================================================================
// =============================================================================================================
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
<div id="centro_geral">




<div id="centro" style="height:250px; width:1080px; border:0px solid #0000FF; margin:auto">

<div id="espaco_2" style="width:1050px"></div>

<div id="centro" style="height:15px; width:1050px; border:0px solid #000; color:#003466; font-size:12px"></div>

<div id="centro" style="height:10px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:200px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<div id="centro" style="height:30px; width:690px; border:0px solid #000; color:#003466; font-size:14px; float:left">
<b>&#160;&#160;&#8226; Solicita&ccedil;&atilde;o de Remessa</b>
</div>

<div id="centro" style="height:5px; width:1050px; border:0px solid #000; color:#003466; font-size:14px; float:left"></div>

<form name="forma_pagamento" action="<?php echo"$servidor/$diretorio_servidor"; ?>/financeiro/solicitacao_pagamento/solicitacao_pagamento.php" method="post">
<input type="hidden" name="botao" value="incluir" />
<input type="hidden" name="botao_relatorio" value="<?php echo"$botao_relatorio"; ?>" />
<input type='hidden' name='pagina_mae' value='<?php echo"$pagina_mae"; ?>'>
<input type='hidden' name='pagina_filha' value='<?php echo"$pagina_filha"; ?>'>
<input type='hidden' name='botao_relatorio' value='<?php echo"$botao_relatorio"; ?>' />
<input type='hidden' name='data_inicial' value='<?php echo"$data_inicial"; ?>'>
<input type='hidden' name='data_final' value='<?php echo"$data_final"; ?>'>
<input type='hidden' name='produto_list' value='<?php echo"$produto_list"; ?>'>
<input type='hidden' name='produtor_ficha' value='<?php echo"$produtor_ficha"; ?>'>
<input type='hidden' name='monstra_situacao' value='<?php echo"$monstra_situacao"; ?>'>
<input type='hidden' name='num_compra_aux' value='<?php echo"$num_compra_aux"; ?>'>




<div style="width:200px; height:180px; border:0px solid #000; float:left"></div>

<!-- ========================================================================================================== -->
<div id="tabela_2" style="width:180px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:179px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 1)
{echo "Forma de Pagamento <b style='color:#FF0000;'>*</b>";}
else
{echo "Forma de Pagamento";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:125px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:124px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 2)
{echo "Data de Pagamento <b style='color:#FF0000;'>*</b>";}
else
{echo "Data de Pagamento";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:120px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:118px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 3 or $erro == 5)
{echo "Valor <b style='color:#FF0000;'>*</b>";}
else
{echo "Valor";}
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:130px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:158px; height:5px; border:0px solid #000"></div><!-- Cheque Banco: --></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:90px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:88px; height:5px; border:0px solid #000"></div><!-- N&ordm; Cheque: --></div>


<!-- =================================  FORMA PAGAMENTO ====================================== -->
<div id="tabela_2" style="width:180px; border:0px solid #000">
<select name='forma_pagamento' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:178px; height:21px; font-size:11px' id='ok' />
<option></option>
<?php
if ($botao == "incluir" and $forma_pagamento == "DINHEIRO")
{echo "<option value='DINHEIRO' selected='selected'>DINHEIRO</option>";}
else
{echo "<option value='DINHEIRO'>DINHEIRO</option>";}

if ($botao == "incluir" and $forma_pagamento == "CHEQUE")
{echo "<option value='CHEQUE' selected='selected'>CHEQUE</option>";}
else
{echo "<option value='CHEQUE'>CHEQUE</option>";}

if ($botao == "incluir" and $forma_pagamento == "TED")
{echo "<option value='TED' selected='selected'>TRANSFER&Ecirc;NCIA BANC&Aacute;RIA</option>";}
else
{echo "<option value='TED'>TRANSFER&Ecirc;NCIA BANC&Aacute;RIA</option>";}

if ($botao == "incluir" and $forma_pagamento == "OUTRA")
{echo "<option value='OUTRA' selected='selected'>OUTRA FORMA DE PGTO</option>";}
else
{echo "<option value='OUTRA'>OUTRA FORMA DE PGTO</option>";}

?>

</select>
</div>

<!-- =========================================  DATA PAGAMENTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:125px; border:0px solid #000">
<input type='text' name='data_pagamento' value='<?php echo "$data_print"; ?>' size='14' maxlength='10' id='calendario' onkeypress='mascara(this,data)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:118px; font-size:12px' />
</div>

<!-- =========================================  VALOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:120px; border:0px solid #000">
<input type='text' name='valor_pagamento' value='' maxlength='15' onkeypress='mascara(this,mvalor)' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:118px; font-size:12px' />

</div>

<!-- =========================================  BANCO CHEQUE ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:130px; border:0px solid #000">

</div>


<!-- =========================================  NUMERO CHEQUE ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:90px; border:0px solid #000">

</div>





<!-- ====================================================================================== -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>
<?php
if ($erro == 4 or $erro == 6)
{echo "Favorecido (F2) <b style='color:#FF0000;'>*</b>";}
else
{echo "Favorecido (F2)";}
?>
</div>


<!-- =========================================  FAVORECIDO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">

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
<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_pessoa_fisica.php" target="_blank">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/editar.png" border="0" height="18px" title="Cadastrar novo fornecedor" /></a>
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
			var aux_f2 = document.forma_pagamento.representante.value;
			javascript:foco('busca');
			javascript:abrir('busca_pessoa_popup.php');
			//javascript:buscarNoticias(aux_f2);
		}
	}
</script>

<!-- ========================================================================================================== -->
<input id='busca' type='text' name='representante' onClick='buscarNoticias(this.value)' onBlur='buscarNoticias(this.value)' onkeydown='if (getKey(event) == 13) return false; ' style='color:#0000FF; width:50px; font-size:12px' value='<?php echo "$codigo_favorecido"; ?>' />
&#160;</div>
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="resultado" style="width:415px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


</div>


</div>
<!-- ========================================================================================================== -->




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o</div>


<!-- ====================================================================================== -->
<div id="geral" style="width:730px; height:25px; border:0px solid #000; font-size:12px; color:#FF0000; float:left">
<input type="text" name="obs_pgto" maxlength="200" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:600px; float:left" />
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/ok.png" height="20px" style="margin-left:0px" />
</form>
</div>



<!-- ====================================================================================== -->
<div id="geral" style="width:850px; height:20px; border:0px solid #000; font-size:10px; color:#999; float:left; margin-top:6px">
</div>

<div id="geral" style="width:850px; height:20px; border:0px solid #000; font-size:12px; color:#FF0000; float:left; margin-top:6px">
<?php echo "$mensagem_erro"; ?>
</div>


</div>





<!-- ================== INICIO DO RELATORIO ================= -->
<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:5px;">

<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
<?php
$busca_favorecidos_pgto = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND origem_pgto='SOLICITACAO' AND data_pagamento='$hoje_bd' AND filial='$filial' ORDER BY codigo");
$linha_favorecidos_pgto = mysqli_num_rows ($busca_favorecidos_pgto);


if ($linha_favorecidos_pgto == 0)
{echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem pagamentos solicitados para hoje</i></div>";}
else
{echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='90px' align='center' bgcolor='#006699'>Data Pgto</td>
<td width='300px' align='center' bgcolor='#006699'>Favorecido</td>
<td width='100px' align='center' bgcolor='#006699'>Forma Pgto</td>
<td width='270px' align='center' bgcolor='#006699'>Dados Banc&aacute;rios</td>
<td width='100px' align='center' bgcolor='#006699'>Valor (R$)</td>
<td width='60px' align='center' bgcolor='#006699'>Estornar</td>
</tr>
</table>
</div>
<div id='centro' style='height:10px; width:1030px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";

for ($w=1 ; $w<=$linha_favorecidos_pgto ; $w++)
{
	$aux_favorecido = mysqli_fetch_row($busca_favorecidos_pgto);

// DADOS DO FAVORECIDO =========================
	$data_pagamento_print_2 = date('d/m/Y', strtotime($aux_favorecido[4]));
	$obs_pgto = ($aux_favorecido[7]);

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
	<td width='90px' align='left'>&#160;&#160;$data_pagamento_print_2</td>
	<td width='300px' align='left'>&#160;&#160;$nome_favorecido_2 ($aux_favorecido[2])</td>
	<td width='100px' align='left'>&#160;&#160;$forma_pagamento_2</td>
	<td width='270px' align='left'>&#160;&#160;$dados_bancarios_2</td>
	<td width='100px' align='right'>$valor_pagamento_print_2&#160;&#160;</td>";
	
	if ($aux_favorecido[15] == "EM_ABERTO")
	{echo "
	<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/financeiro/solicitacao_pagamento/solicitacao_pagamento.php' method='post'>
		<input type='hidden' name='botao' value='excluir' />
		<input type='hidden' name='codigo_pgto_favorecido' value='$aux_favorecido[0]' />
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='botao_relatorio' value='$botao_relatorio' />
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/excluir.png' height='20px' /></form>
	</td>";}
	else
	{echo "
	<td width='60px' align='center'>
	</td>";}
		
							
	echo "
	</tr>";
}
echo "
</table>
</div>
<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->


<div id="centro" style="height:15px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center"></div>
<div id="centro" style="height:60px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center">

<?php
// SOMA PAGAMENTOS  ==========================================================================================
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE origem_pgto='SOLICITACAO' AND data_pagamento='$hoje_bd' AND filial='$filial' AND estado_registro='ATIVO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");


	echo "
		<div id='centro' style='float:left; height:55px; width:222px; color:#00F; text-align:center; font-size:11px; border:0px solid #000'>
		Total Solicitado: R$ $soma_pagamentos_print
		</div>
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";
?>
</div>

</div> <!-- ================================== FIM DA DIV CENTRO GERAL ======================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>