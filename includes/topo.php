<div style="width:auto; height:65px; margin:auto">

<!-- ====== LOGOMARCA ============================================================================================= -->
<div style="width:200px; height:65px; border:0px solid #FFF; float:left">
    <a href="<?php echo"$servidor/$diretorio_servidor"; ?>/">
    <img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_suif.png" border="0" height="65px" style="margin-top:0px" />
    </a>
</div>
<!-- ============================================================================================================== -->


<!-- ============================================================================================================== -->
<div style="width:auto; height:40px; border:0px solid #FFF; margin:auto">


<!-- ====== M�DULOS =============================================================================================== -->
<div style="width:700px; height:30px; border:0px solid #FFF; text-align:left; float:left">

<?php
	if ($modulo == 'cadastros')
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/index_cadastros.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:0px'>Cadastros</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/index_cadastros.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:0px'>Cadastros</button></a>";}


	if ($modulo == 'compras')
	{echo "<a href='$servidor/$diretorio_servidor/compras/compras/index_compras.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:10px'>Compras</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/compras/compras/index_compras.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Compras</button></a>";}

/*
	if ($modulo == 'vendas')
	{echo "<a href='$servidor/$diretorio_servidor/vendas/index_vendas.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:10px'>Vendas</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/vendas/index_vendas.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Vendas</button></a>";}
*/

	if ($modulo == 'estoque')
	{echo "<a href='$servidor/$diretorio_servidor/estoque/index_estoque.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:10px'>Estoque</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/estoque/index_estoque.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Estoque</button></a>";}


	if ($modulo == 'financeiro')
	{echo "<a href='$servidor/$diretorio_servidor/financeiro/index_financeiro.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:10px'>Financeiro</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/financeiro/index_financeiro.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Financeiro</button></a>";}

	{echo "<a href='$servidor/$diretorio_servidor/certificacao/'>
		<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Certificacao</button></a>";}
	
/*
	if ($modulo == 'diversos')
	{echo "<a href='$servidor/$diretorio_servidor/diversos/index_diversos.php'>
	<button type='submit' class='botao_modulo_on' style='margin-top:0px; margin-left:10px'>Diversos</button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/diversos/index_diversos.php'>
	<button type='submit' class='botao_modulo_off' style='margin-top:0px; margin-left:10px'>Diversos</button></a>";}
*/
?>
</div>


<!-- ====== SAIR ================================================================================================== -->
<div style="width:70px; height:30px; border:0px solid #FFF; text-align:right; float:right; margin-right:20px">
<a href='<?php echo"$servidor/$diretorio_servidor"; ?>/logout.php'>
<button type='submit' class='botao_modulo_off' style='width:70px; margin-top:0px; margin-left:0px'>Sair</button>
</a>
</div>
<!-- ============================================================================================================== -->


<!-- ====== NOME USU�RIO ========================================================================================== -->
<div style="width:250px; height:30px; border:0px solid #FFF; text-align:right; float:right">
	<div style="width:230px; height:25px; text-align:right; float:right; margin-top:5px; margin-right:20px; color:#EEE; font-size:14px">
	<?php echo "$nome_usuario"; ?>
    </div>
</div>
<!-- ============================================================================================================== -->



</div>
<!-- ============================================================================================================== -->

<!-- ====== NOME CLIENTE ========================================================================================== -->
<div style="width:700px; height:20px; border:0px solid #FFF; text-align:left; float:left">
	<div style="width:230px; height:25px; text-align:left; float:left; margin-top:0px; margin-left:0px; color:#EEE; font-size:14px">
	<?php echo "$nome_fantasia"; ?>
    </div>
</div>
<!-- ============================================================================================================== -->


<!-- ============================================================================================================== -->
<div style="width:70px; height:20px; border:0px solid #FFF; text-align:right; float:right; margin-right:20px">
</div>
<!-- ============================================================================================================== -->


<!-- ====== FILIAL ================================================================================================ -->
<div style="width:250px; height:20px; border:0px solid #FFF; text-align:left; float:right">
	<div style="width:230px; height:25px; text-align:right; float:right; margin-top:0px; margin-right:20px; font-size:14px">
	<div class='link_3'>
    <?php
	if(empty($nome_filial))
	{echo "";}
	else
    {echo "<a href='$servidor/$diretorio_servidor/troca_filial.php'>Filial: $nome_filial</a>";}
    ?>
    </div>
    </div>
</div>
<!-- ============================================================================================================== -->





</div>

