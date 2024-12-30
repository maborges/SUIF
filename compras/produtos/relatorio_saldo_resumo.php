<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_saldo";
$titulo = "Relat&oacute;rio de Saldo de Armazenado (Saldo dos Produtores)";
$modulo = "compras";
$menu = "relatorios";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$filial = $filial_usuario;
$cod_produto = $_POST["cod_produto"];	
$botao = $_POST["botao"];
$ordenar_busca = $_POST["ordenar_busca"];
if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "geral";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}

if ($ordenar_busca == "PRODUTOR")
{$mysql_ordenar_busca = "fornecedor_print";}
elseif ($ordenar_busca == "SALDO_MAIOR")
{$mysql_ordenar_busca = "saldo DESC";}
elseif ($ordenar_busca == "SALDO_MENOR")
{$mysql_ordenar_busca = "saldo ASC";}
else
{$mysql_ordenar_busca = "fornecedor_print";}
// ================================================================================================================


// ====== BUSCA SALDO ARMAZENADO =================================================================================
$busca_saldo = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE filial='$filial' AND cod_produto='$cod_produto' ORDER BY $mysql_ordenar_busca");
$linhas_saldo = mysqli_num_rows ($busca_saldo);
// ================================================================================================================


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
}, 5000); // 5 Segundos


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


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Relat&oacute;rio de Saldo de Armazenado
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Saldo dos Produtores
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_saldo_resumo.php" method="post" />
    <input type='hidden' name='botao' value='1' />

	<div style="height:36px; width:60px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Produto:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
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

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Saldo:</div>

	<div style="height:34px; width:105px; border:0px solid #999; float:left">
    <select class="pqa_select" name="monstra_situacao" onkeydown="if (getKey(event) == 13) return false;" style="width:100px" />
    <?php
	if ($monstra_situacao == "geral")
	{echo "<option value='geral' selected='selected'>GERAL</option>";}
	else
	{echo "<option value='geral'>GERAL</option>";}

	if ($monstra_situacao == "devedores")
	{echo "<option value='devedores' selected='selected'>DEVEDOR</option>";}
	else
	{echo "<option value='devedores'>DEVEDOR</option>";}

	if ($monstra_situacao == "credores")
	{echo "<option value='credores' selected='selected'>CREDOR</option>";}
	else
	{echo "<option value='credores'>CREDOR</option>";}
    ?>
    </select>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:120px; border:0px solid #000">Ordenar por:</div>

	<div style="height:34px; width:190px; border:0px solid #999; float:left">
    <select class="pqa_select" name="ordenar_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:175px" />
    <?php
    if ($ordenar_busca == "PRODUTOR")
    {echo "<option value='PRODUTOR' selected='selected'>Nome do Produtor</option>";}
    else
    {echo "<option value='PRODUTOR'>Nome do Produtor</option>";}

    if ($ordenar_busca == "SALDO_MAIOR")
    {echo "<option value='SALDO_MAIOR' selected='selected'>Saldo (do maior para o menor)</option>";}
    else
    {echo "<option value='SALDO_MAIOR'>Saldo (do maior para o menor)</option>";}

    if ($ordenar_busca == "SALDO_MENOR")
    {echo "<option value='SALDO_MENOR' selected='selected'>Saldo (do menor para o maior)</option>";}
    else
    {echo "<option value='SALDO_MENOR'>Saldo (do menor para o maior)</option>";}
    ?>
    </select>
	</div>


	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>
	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	<?php
        if ($botao == 1)
        {echo"
        <form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_saldo_impressao.php' target='_blank' method='post'>
        <input type='hidden' name='filial' value='$filial'>
        <input type='hidden' name='cod_produto' value='$cod_produto'>
        <input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
        <input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
		<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
		</form>";}
        else
        {echo"";}
    ?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->





<!-- ====================================================================================== -->

<?php
if ($botao != 1)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:0px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($botao != 1)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='120px' height='20px' align='center' bgcolor='#006699'>C&oacute;digo do Produtor</td>
<td width='450px' align='center' bgcolor='#006699'>Produtor</td>
<td width='200px' align='center' bgcolor='#006699'>Saldo</td>
<td width='70px' align='center' bgcolor='#006699'>Ficha</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";

?>

<!--<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:11px">-->



<?php

// =================================================================================================================
if ($botao != 1)
{}


else
{

// =================================================================================================================
for ($w=1 ; $w<=$linhas_saldo ; $w++)
{
	$aux_saldo = mysqli_fetch_row($busca_saldo);

	$cod_fornecedor = $aux_saldo[1];
	$fornecedor_print = $aux_saldo[2];
	$cod_tipo = $aux_saldo[6];
	$tipo_print = $aux_saldo[7];
	$unidade_print = $aux_saldo[8];
	$saldo = $aux_saldo[9];
	$saldo_print = number_format($saldo,2,",",".");


// RELATORIO ======================================================================================================
	if ($saldo == 0 and $botao != 1)
	{}
	
	elseif ($saldo > 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'credores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_credor = $soma_credor + $saldo;

	echo "
	<tr class='tabela_1' title=''>
	<td width='120px' align='center'>$cod_fornecedor</td>
	<td width='450px' align='left'><div style='margin-left:10px'>$fornecedor_print</div></td>
	<td width='200px' align='center'>$saldo_print $unidade_print</td>
	<td width='70px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
	<input type='hidden' name='fornecedor' value='$cod_fornecedor'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
	</td>
	</tr>";}

	elseif ($saldo < 0 and ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores'))
	{
	$conta_produtor = $conta_produtor + 1;
	$soma_devedor = $soma_devedor + $saldo;
	
	echo "
	<tr class='tabela_5' title=''>
	<td width='120px' align='center'>$cod_fornecedor</td>
	<td width='450px' align='left'><div style='margin-left:10px'>$fornecedor_print</div></td>
	<td width='200px' align='center'>$saldo_print $unidade_print</td>
	<td width='70px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
	<input type='hidden' name='fornecedor' value='$cod_fornecedor'>
	<input type='hidden' name='botao' value='seleciona'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
	</td>
	</tr>";}

	else
	{}


}


$soma_credor_print = number_format($soma_credor,2,",",".");
$soma_devedor_print = number_format($soma_devedor,2,",",".");


// =================================================================================================================
}
// =================================================================================================================

?>
</table>


<div id="centro" style="height:10px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:10px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->


<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:250px; float:left; height:25px; margin-left:0px; border:0px solid #999; font-size:12px; color:#666; text-align:center">
	<?php 
	if ($conta_produtor == 1)
	{echo"<i><b>$conta_produtor</b> Produtor</i>";}
	elseif ($conta_produtor > 1)
	{echo"<i><b>$conta_produtor</b> Produtores</i>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:450px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>

	<div id="centro" style="width:370px; float:right; height:25px; border:0px solid #999; font-size:12px; text-align:left">
    <?php
	if ($botao != 1)
	{}
	elseif ($monstra_situacao == 'geral' or $monstra_situacao == 'devedores')
	{echo"<font style='color:#666'>TOTAL DEVEDORES: </font><b style='color:#F00'>$soma_devedor_print $unidade_print</b>";}
	else
	{echo"<font style='color:#666'>TOTAL CREDORES: </font><b style='color:#00F'>$soma_credor_print $unidade_print</b>";}
	?>
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:250px; float:left; height:25px; margin-left:0px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>
	
	<div id="centro" style="width:450px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
	</div>
	
	<div id="centro" style="width:370px; float:right; height:25px; border:0px solid #999; font-size:12px; text-align:left">
    <?php
	if ($monstra_situacao == 'geral' and $botao == 1)
	{echo"<font style='color:#666'>TOTAL CREDORES: </font><b style='color:#00F'>$soma_credor_print $unidade_print</b>";}
	else
	{ }
	?>
	</div>	
</div>

<div id="centro" style="height:60px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->




</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>