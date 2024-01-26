<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'relatorio_periodo';
$titulo = 'Relat&oacute;rios de Contratos Futuros';
$modulo = 'compras';
$menu = 'contratos';
// ================================================================================================================


include ('include_comando.php');


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
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>
<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:auto; margin:auto; background-color:#FFF; border-radius:10px; border:1px solid #999">
<div style="width:1250px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1100px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:0px">
    Contratos Futuros
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:0px">
    	<div id="menu_atalho_3" style="margin-top:10px">
    	<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorios.php' >
        &#8226; Outros relat&oacute;rios de contratos futuros</a>
        </div>
    </div>
</div>

<div style="width:1250px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1100px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:0px; font-size:14px">
	Relat&oacute;rio Por Per&iacute;odo
    </div>
</div>

<div style="width:1100px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<!--
<div style="width:1250px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px; color:#003466">
    <?php // echo "$msg"; ?>
    </div>
</div>

<div style="width:1080px; height:5px; border:0px solid #000; margin:auto"></div>
-->
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:36px; width:1250px; border:1px solid #999; margin:auto; background-color:#EEE">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/relatorio_periodo.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />

	<div id="centro" style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div id="centro" style="height:20px; width:75px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:left; margin-top:11px">
	<i>Data inicial:&#160;</i>
    </div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_inicial_aux"; ?>" />
	</div>

    <div id="centro" style="height:20px; width:85px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Data final:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_final_aux"; ?>" />
	</div>

    <div id="centro" style="height:20px; width:90px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Filtrar por:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:160px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <select name="filtro_data" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:12px" />
    <?php
    if ($filtro_data == "vencimento")
    {echo "<option selected='selected' value='vencimento'>Data de Vencimento</option>";}
    else
    {echo "<option value='vencimento'>Data de Vencimento</option>";}
    if ($filtro_data == "data")
    {echo "<option selected='selected' value='data'>Data de Emiss&atilde;o</option>";}
    else
    {echo "<option value='data'>Data de Emiss&atilde;o</option>";}
    if ($filtro_data == "data_cadastro")
    {echo "<option selected='selected' value='data_cadastro'>Data de Cadastro</option>";}
    else
    {echo "<option value='data_cadastro'>Data de Cadastro</option>";}
    if ($filtro_data == "data_baixa")
    {echo "<option selected='selected' value='data_baixa'>Data de Baixa</option>";}
    else
    {echo "<option value='data_baixa'>Data de Baixa</option>";}
    ?>
    </select>
    </div>
    
    <div id="centro" style="height:20px; width:135px; border:0px solid #999; color:#666; font-size:11px; float:left; text-align:right; margin-top:11px">
	<i>Situa&ccedil;&atilde;o do Contrato:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:95px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
    <select name="situacao_contrato" onkeydown="if (getKey(event) == 13) return false;" style="height:21px; width:85px; color:#0000FF; font-size:11px" />
    <?php
    if ($situacao_contrato == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(Geral)</option>";}
    else
    {echo "<option value='GERAL'>(Geral)</option>";}

    if ($situacao_contrato == "EM_ABERTO")
    {echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
    else
    {echo "<option value='EM_ABERTO'>Em Aberto</option>";}

    if ($situacao_contrato == "PAGO")
    {echo "<option value='PAGO' selected='selected'>Quitado</option>";}
    else
    {echo "<option value='PAGO'>Quitado</option>";}
    ?>
    </select>
	</div>

	<div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:20px; margin-top:8px">
    <input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
    </form>
	</div>
	
</div>

<div id="centro" style="height:15px; width:1250px; border:0px solid #000; margin:auto"></div>


<div id="centro" style="height:26px; width:1250px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:400px; float:left; height:26px; margin-left:10px; border:0px solid #999">
		<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_futuro/contrato_futuro_seleciona.php">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/novo.jpg" border="0" style="float:left" />
		</a>
	</div>
	
	<div id="centro" style="width:400px; float:left; height:26px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
		<?php 
        if ($linha_cont_futuro >= 1)
        {echo"
        <form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_relatorio_impressao.php' target='_blank' method='post'>
        <input type='hidden' name='pagina_mae' value='$pagina'>
        <input type='hidden' name='botao' value='IMPRIMIR'>
        <input type='hidden' name='botao_mae' value='$botao'>
        <input type='hidden' name='data_inicial' value='$data_inicial_aux'>
        <input type='hidden' name='data_final' value='$data_final_aux'>
        <input type='hidden' name='situacao_contrato' value='$situacao_contrato'>
		<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
		<input type='hidden' name='filtro_data' value='$filtro_data'>
        <input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>";}
        else
        {}
        ?>
	</div>

	<div id="centro" style="width:400px; float:right; height:26px; border:0px solid #999; font-size:12px; color:#003466; text-align:right; margin-right:10px">
		<?php 
        if ($linha_cont_futuro == 1)
        {echo"<i><b>$linha_cont_futuro</b> Contrato</i>";}
        elseif ($linha_cont_futuro == 0)
        {echo"";}
        else
        {echo"<i><b>$linha_cont_futuro</b> Contratos</i>";}
        ?>
	</div>
</div>
<!-- ====================================================================================== -->

<div id="centro" style="height:10px; width:1250px; border:0px solid #000; margin:auto; border-radius:0px"></div>


<!-- ====================================================================================== -->
<?php include ('include_totalizador.php'); ?>
<!-- ====================================================================================== -->


<!-- ====================================================================================== -->
<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>


<!-- ====================================================================================== -->
<?php include ('include_relatorio_contrato_futuro.php'); ?>
<!-- ====================================================================================== -->


<!-- ====================================================================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<!-- FIM CENTRO GERAL (depois do menu geral) -->
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>