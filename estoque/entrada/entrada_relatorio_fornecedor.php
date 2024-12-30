<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_relatorio_fornecedor';
$titulo = 'Estoque - Relat&oacute;rio de Entradas';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================


// ================================================================================================================
include ('include_comando.php'); 
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
<?php include ('../../includes/submenu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Estoque - Relat&oacute;rio de Entradas
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:645px; float:left; text-align:left">
	<?php 
		if ($fornecedor_busca == "" or $linhas_forne == 0)
		{echo"<div style='height:28px; width:120px; border:0px solid #000; color:#F00; float:left'>
		<a href='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_fornecedor_seleciona.php'>
		<button type='submit' class='botao_1'>Voltar</button></a>
		</div>
		<div style='height:28px; width:420px; border:0px solid #000; color:#F00; float:left; margin-top:2px'>
		$forne_print</div>";}
		else
		{echo"<div style='height:28px; width:640px; border:0px solid #000; color:#003466; float:left'>Fornecedor: <b> $forne_print</b></div>";}
	?>
    </div>

	<div class="ct_subtitulo_1" style="width:445px; float:right; text-align:right">
	<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_fornecedor.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />
    <input type='hidden' name='fornecedor_busca' value='<?php echo"$fornecedor_busca"; ?>' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Data inicial:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_inicial_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:80px; text-align:center" value="<?php echo"$data_inicial_br"; ?>" />
	</div>

	<div class="pqa_rotulo" style="height:20px; width:85px; border:0px solid #000">Data final:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_final_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:80px; text-align:center" value="<?php echo"$data_final_br"; ?>" />
	</div>

    <div class="pqa_rotulo" style="height:20px; width:90px; border:0px solid #000">Produto:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_produto_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option value="TODOS">(TODOS)</option>
    <?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_busca)
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

    <div class="pqa_rotulo" style="height:20px; width:135px; border:0px solid #000">Forma de Pesagem:</div>

	<div style="height:34px; width:105px; border:0px solid #999; float:left">
    <select class="pqa_select" name="forma_pesagem_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:100px" />
    <?php
	if ($forma_pesagem_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
	else
	{echo "<option value='GERAL'>(GERAL)</option>";}

	if ($forma_pesagem_busca == "BALANCA")
	{echo "<option value='BALANCA' selected='selected'>Balan&ccedil;a</option>";}
	else
	{echo "<option value='BALANCA'>Balan&ccedil;a</option>";}

	if ($forma_pesagem_busca == "ENTRADA_DIRETA")
	{echo "<option value='ENTRADA_DIRETA' selected='selected'>Entrada Direta</option>";}
	else
	{echo "<option value='ENTRADA_DIRETA'>Entrada Direta</option>";}
    ?>
    </select>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:150px; border:0px solid #000">Situa&ccedil;&atilde;o do Romaneio:</div>

	<div style="height:34px; width:95px; border:0px solid #999; float:left">
    <select class="pqa_select" name="situacao_romaneio_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:85px" />
    <?php
    if ($situacao_romaneio_busca == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
    else
    {echo "<option value='GERAL'>(GERAL)</option>";}

    if ($situacao_romaneio_busca == "EM_ABERTO")
    {echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
    else
    {echo "<option value='EM_ABERTO'>Em Aberto</option>";}

    if ($situacao_romaneio_busca == "FECHADO")
    {echo "<option value='FECHADO' selected='selected'>Fechado</option>";}
    else
    {echo "<option value='FECHADO'>Fechado</option>";}
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
	if ($permissao[17] == "S")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/entrada/cadastro_1_selec_produto.php'>
	<button type='submit' class='botao_1'>Novo Romaneio</button></a>";}
	else
	{echo "<button type='submit' class='botao_1' style='color:#BBB'>Novo Romaneio</button>";}
	
	if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
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
        {echo"Total de Entrada: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('include_totalizador.php'); ?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('include_relatorio_estoque_entrada.php'); ?>
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