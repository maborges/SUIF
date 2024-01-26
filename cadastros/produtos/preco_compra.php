<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "preco_compra";
$titulo = "Pre&ccedil;o de Compra";	
$modulo = "cadastros";
$menu = "cadastro_produtos";
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace("R$ ", "", $valor_x); //tira o símbolo
	$valor_2 = str_replace(".", "", $valor_1); //tira o ponto
	$valor_3 = str_replace(",", ".", $valor_2); //troca vírgula por ponto
	return $valor_3;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$codigo_w = $_POST["codigo_w"];
$valor_maximo_form = ConverteValor($_POST["valor_maximo_form"]);
$preco_print = $_POST["valor_maximo_form"];
$nome_form = $_POST["nome_form"];
$nome_imagem_produto = $_POST["nome_imagem_produto"];

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== ALTERAR PREÇO ============================================================================================
if ($botao == "ALTERAR_PRECO" and $permissao[40] == "S")
{

	if ($valor_maximo_form == "")
	{$valor_maximo_form = 0;}

// ALTERAR
$alterar = mysqli_query ($conexao, "UPDATE cadastro_produto SET preco_compra_maximo='$valor_maximo_form', usuario_alteracao='$usuario_cadastro_form', hora_alteracao='$hora_cadastro_form', data_alteracao='$data_cadastro_form' WHERE codigo='$codigo_w'");

$preco_print = "";
$nome_form = "";
$nome_imagem_produto = "";

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Pre&ccedil;o alterado com sucesso!</div>";
}

elseif ($botao == "ALTERAR_PRECO" and $permissao[40] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para alterar pre&ccedil;o</div>";

$preco_print = "";
$nome_form = "";
$nome_imagem_produto = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' ORDER BY descricao");
$linha_registro = mysqli_num_rows ($busca_registro);
// ==================================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

</script>

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskMoney.js"></script>

<script>
jQuery(function($){

	// VALOR MONETÁRIO (R$ 8.888,88)
	$("#valor_money").maskMoney({
		symbol:'R$ ', //Símbolo a ser usado antes de os valores do usuário. padrão: ‘EUA $’
		showSymbol:true, //definir se o símbolo deve ser exibida ou não. padrão: false
		thousands:'.', //Separador de milhares. padrão: ‘,’
		decimal:',', //Separador do decimal. padrão: ‘.’
		precision:2, //Quantas casas decimais são permitidas. Padrão: 2
		symbolStay:true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
	});

});
</script>


</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_produtos.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_registro == 1)
    {echo "<i>$linha_registro produto cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro produtos cadastrados</i>";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<div class="pqa" style="height:63px">
<!-- ======================================= FORMULARIO ========================================================== -->


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:130px; height:50px; border:1px solid transparent; margin-top:6px; float:left">

    <div style="width:105px; height:40px; margin-top:5px; margin-left:20px; float:left; font-size:12px; color:#003466">
    <?php
	if ($nome_imagem_produto == "")
	{$link_imagem_produto = "";}
	else
	{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:100px'>";}
	
	echo"$link_imagem_produto" ?>
    </div>


    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/produtos/preco_compra.php" method="post" />
    <?php
	if ($botao == "ALTERACAO")
	{echo "
	<input type='hidden' name='botao' value='ALTERAR_PRECO' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";}
	?>

</div>
<!-- ================================================================================================================ -->


<!-- ======= NOME PRODUTO =========================================================================================== -->
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_form" class="form_input" maxlength="30" id="ok" onBlur='alteraMaiusculo(this)' 
    onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px; color:#999" 
    disabled='disabled' value="<?php echo"$nome_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PREÇO MAXIMO UNIDADE ===================================================================================== -->
<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
    Pre&ccedil;o:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="valor_maximo_form" class="form_input" maxlength="12" id='valor_money' 
    onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo"$preco_print"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($botao == "ALTERACAO")
	{echo "<button type='submit' class='botao_1'>Salvar</button>";}
	?>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_registro == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='100px'>C&oacute;digo</td>
<td width='350px'>Nome</td>
<td width='130px'>Pre&ccedil;o</td>
<td width='80px'>Unidade</td>
<td width='350px'>&Uacute;ltima atualiza&ccedil;&atilde;o</td>
<td width='100px'>Alterar Pre&ccedil;o</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO USUÁRIO ============================================================================
$codigo_w = $aux_registro[0];
$nome_w = $aux_registro[1];
$cod_unidade_w = $aux_registro[7];
$preco_maximo_w = "R$ " . number_format($aux_registro[21],2,",",".");
$quant_unidade_w = $aux_registro[23];
$quant_saca_w = $aux_registro[27];
$tipo_w = $aux_registro[29];
$estado_registro_w = $aux_registro[19];
$bloqueio_w = $aux_registro[40];
$nome_imagem_produto = $aux_registro[28];


$usuario_alteracao_w = $aux_registro[16];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[18]));
$hora_alteracao_w = $aux_registro[17];
$dados_alteracao_w = "$usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade_w' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[2];
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
$permite_editar = "SIM";
/*
if ($permissao[40] == "S")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
*/
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' height='34px'>";}
else
{echo "<tr class='tabela_4' height='34px'>";}


echo "
<td width='100px' align='center'>$codigo_w</td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_w</div></td>
<td width='130px' align='center'>$preco_maximo_w</td>
<td width='80px' align='center'>$un_descricao</td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$dados_alteracao_w</div></td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='100px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/preco_compra.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ALTERACAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='nome_form' value='$nome_w'>
		<input type='hidden' name='valor_maximo_form' value='$preco_maximo_w'>
		<input type='hidden' name='nome_imagem_produto' value='$nome_imagem_produto'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='100px' align='center'></td>";
	}
// =================================================================================================================




}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_registro == 0)
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum produto cadastrado.</i></div>";}
// =================================================================================================================
?>




<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>