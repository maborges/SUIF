<div style="width:1280px; height:7px"></div>

<div style="width:auto; height:35px">
<?php

	if ($menu == "compras")
	{echo "<a href='$servidor/$diretorio_servidor/compras/compras/index_compras.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Compras</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/compras/compras/index_compras.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Compras</button></a>";}

	if ($menu == "ficha_produtor")
	{echo "<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_selec_fornecedor.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Ficha do Produtor</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_selec_fornecedor.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Ficha do Produtor</button></a>";}

	if ($menu == "contratos")
	{echo "<a href='$servidor/$diretorio_servidor/compras/contrato_futuro/index_contratos.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Contratos</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/compras/contrato_futuro/index_contratos.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Contratos</button></a>";}

	if ($menu == "relatorios")
	{echo "<a href='$servidor/$diretorio_servidor/compras/relatorios/index_relatorios.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Relat&oacute;rios</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/compras/relatorios/index_relatorios.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Relat&oacute;rios</button></a>";}
?>
</div>