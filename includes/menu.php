<div id='menu'>
<div id='menu' style='height:5px; width:940; border:0px solid #000'></div>
<?php
if ($menu == 'compras')
	{echo "<a href='$servidor/$diretorio_servidor/compras/index_compras.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_compras_1.png' alt='Compras' border='0' style='float:left; margin-left:7px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/compras/index_compras.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_compras_2.png' alt='Compras' border='0' style='float:left; margin-left:7px' /></a>";}

echo "<div id='menu' style='height:30px; float:left; width:9px; border:0px solid #000'></div>";

if ($menu == 'produtores')
	{echo "<a href='$servidor/$diretorio_servidor/produtor/index_produtor.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_produtor_1.png' alt='Produtor' border='0' style='float:left; margin-left:7px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/produtor/index_produtor.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_produtor_2.png' alt='Produtor' border='0' style='float:left; margin-left:7px' /></a>";}
	
echo "<div id='menu' style='height:30px; float:left; width:9px; border:0px solid #000'></div>";

if ($menu == 'config')
	{echo "<a href='$servidor/$diretorio_servidor/configuracoes/index_configuracoes.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_config_1.png' alt='Configurações' border='0' style='float:left; margin-left:7px' /></a>";}
else
	{echo "<a href='$servidor/$diretorio_servidor/configuracoes/index_configuracoes.php'><img src='$servidor/$diretorio_servidor/imagens/botoes/menu_config_2.png' alt='Configurações' border='0' style='float:left; margin-left:7px' /></a>";}
	
?>
</div>