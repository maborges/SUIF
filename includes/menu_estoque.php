<div style="width:1280px; height:7px"></div>

<div style="width:auto; height:35px">
<?php

	if ($menu == "estoque")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/index_estoque.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Estoque</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/estoque/index_estoque.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Estoque</button></a>";}

	if ($menu == "entrada")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/entrada/index_entrada.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Entrada</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/estoque/entrada/index_entrada.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Entrada</button></a>";}

	if ($menu == "saida")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/saida/index_saida.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Sa&iacute;da</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/estoque/saida/index_saida.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Sa&iacute;da</button></a>";}

	if ($menu == "relatorios")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/relatorios/index_relatorios.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Relat&oacute;rios</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/estoque/relatorios/index_relatorios.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Relat&oacute;rios</button></a>";}
?>
</div>