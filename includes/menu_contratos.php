<div id='menu'>
<div id='menu' style='height:5px; width:940; border:0px solid #000'></div>
<?php

if ($menu == 'adiantamento')
	{echo "<a href='$servidor/$diretorio_servidor/contrato_adiantamento/novo_contrato_adto.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_adiantamento_1.png' border='0' style='float:left; margin-left:3px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/contrato_adiantamento/novo_contrato_adto.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_adiantamento_2.png' border='0' style='float:left; margin-left:3px' /></a>";}
	


if ($menu == 'futuro')
	{echo "<a href='$servidor/$diretorio_servidor/contrato_futuro/contrato_futuro_cadastro.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_futuro_1.png' border='0' style='float:left; margin-left:3px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/contrato_futuro/contrato_futuro_cadastro.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_futuro_2.png' border='0' style='float:left; margin-left:3px' /></a>";}
	


if ($menu == 'tratado')
	{echo "<a href='$servidor/$diretorio_servidor/contrato_tratado/contrato_tratado_cadastro.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_tratado_1.png' border='0' style='float:left; margin-left:3px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/contrato_tratado/contrato_tratado_cadastro.php'>
	<img src='$servidor/$diretorio_servidor/imagens/botoes/menu_tratado_2.png' border='0' style='float:left; margin-left:3px' /></a>";}



?>
</div>