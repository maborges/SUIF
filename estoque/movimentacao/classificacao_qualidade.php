<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'classificacao_qualidade';
$titulo = 'Romaneios - Classifica&ccedil;&atilde;o de Qualidade';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ================================================================================================================
include ('classificacao_include_comando.php'); 
// ================================================================================================================


// ================================================================================================================
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
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Romaneios - Classifica&ccedil;&atilde;o de Qualidade
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->








<!-- ============================================================================================================= -->
<div class="pqa" style="height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:10px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/classificacao_qualidade.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />

</div>
<!-- ================================================================================================================ -->

 <!-- ======= DATA INICIAL ========================================================================================== -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Inicial:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_inicial_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_inicial_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= DATA FINAL ============================================================================================ -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Final:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_final_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_final_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= PRODUTO =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_busca" class="form_select" style="width:190px" />
    <?php
    if ($cod_produto_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
    else
    {echo "<option value='GERAL'>(TODOS)</option>";}

    $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
    $linhas_produto_list = mysqli_num_rows ($busca_produto_list);

    for ($i=1 ; $i<=$linhas_produto_list ; $i++)
    {
    $aux_produto_list = mysqli_fetch_row($busca_produto_list);	

        if ($aux_produto_list[0] == $cod_produto_busca)
        {echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
        else
        {echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= FILIAL ARMAZENAGEM ==================================================================================== -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Filial Armazenagem:
    </div>
    
    <div style="width:195px; height:25px; float:left; border:1px solid transparent">
    <select name="filial_armazem_pesq" class="form_select" style="width:175px" />
    <?php
    if ($filial_armazem_pesq == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
    else
    {echo "<option value='GERAL'>(TODAS)</option>";}

	$busca_filial_armazem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
	$linhas_filial_armazem = mysqli_num_rows ($busca_filial_armazem);

    for ($fa=1 ; $fa<=$linhas_filial_armazem ; $fa++)
    {
    $aux_filial_armazem = mysqli_fetch_row($busca_filial_armazem);

        if ($filial_armazem_pesq == $aux_filial_armazem[1])
        {echo "<option selected='selected' value='$aux_filial_armazem[1]'>$aux_filial_armazem[2]</option>";}
        else
        {echo "<option value='$aux_filial_armazem[1]'>$aux_filial_armazem[2]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->



 <!-- ======= FILIAL ORIGEM ========================================================================================= -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Filial Origem:
    </div>
    
    <div style="width:195px; height:25px; float:left; border:1px solid transparent">
    <select name="filial_origem_pesq" class="form_select" style="width:175px" />
    <?php
    if ($filial_origem_pesq == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
    else
    {echo "<option value='GERAL'>(TODAS)</option>";}

	$busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
	$linhas_filial_origem = mysqli_num_rows ($busca_filial_origem);
    
    for ($f=1 ; $f<=$linhas_filial_origem ; $f++)
    {
    $aux_filial_origem = mysqli_fetch_row($busca_filial_origem);

        if ($filial_origem_pesq == $aux_filial_origem[1])
        {echo "<option selected='selected' value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
        else
        {echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->



 <!-- ======= ROMANEIOS ============================================================================================= -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Romaneios:
    </div>
    
    <div style="width:195px; height:25px; float:left; border:1px solid transparent">
    <select name="classificacao_romaneio_pesq" class="form_select" style="width:175px" />
    <?php
    if ($classificacao_romaneio_pesq == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(TODOS)</option>";}
    else
    {echo "<option value='GERAL'>(TODOS)</option>";}

    if ($classificacao_romaneio_pesq == "SIM")
    {echo "<option value='SIM' selected='selected'>Classificados</option>";}
    else
    {echo "<option value='SIM'>Classificados</option>";}

    if ($classificacao_romaneio_pesq == "NAO")
    {echo "<option value='NAO' selected='selected'>N&atilde;o Classificados</option>";}
    else
    {echo "<option value='NAO'>N&atilde;o Classificados</option>";}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
    <button type='submit' class='botao_1'>Buscar</button>
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
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	<?php
  if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/movimentacao/classificacao_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='numero_romaneio_form' value='$numero_romaneio_form'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_pesq' value='$fornecedor_pesq'>
	<input type='hidden' name='filial_armazem_pesq' value='$filial_armazem_pesq'>
	<input type='hidden' name='filial_origem_pesq' value='$filial_origem_pesq'>
	<input type='hidden' name='classificacao_romaneio_pesq' value='$classificacao_romaneio_pesq'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php 
        if ($linha_romaneio == 0)
        {}
        elseif ($linha_romaneio == 1)
        {echo"$linha_romaneio Romaneio";}
        else
        {echo"$linha_romaneio Romaneios";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_romaneio >= 1)
        {echo"Quantidade Total: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('classificacao_include_totalizador.php'); ?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('classificacao_include_relatorio.php'); ?>
<!-- ============================================================================================================= -->


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