<div style="width:1280px; height:7px"></div>

<div style="width:auto; height:35px">
<?php

	if ($menu == "cadastro_pessoas")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Pessoas</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Pessoas</button></a>";}

	if ($menu == "cadastro_favorecidos")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/favorecidos/index_favorecidos.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Favorecidos</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/favorecidos/index_favorecidos.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Favorecidos</button></a>";}

	if ($menu == "cadastro_usuarios")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Usu&aacute;rios</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Usu&aacute;rios</button></a>";}

	if ($menu == "cadastro_produtos")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Produtos</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/produtos/produtos_cadastro.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Produtos</button></a>";}

	if ($menu == "config")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/configuracoes/config_geral.php'>
	<button type='submit' class='botao_menu_on' style='margin-top:0px; margin-left:15px'><b>Configura&ccedil;&otilde;es</b></button></a>";}
	else
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/configuracoes/config_geral.php'>
	<button type='submit' class='botao_menu_off' style='margin-top:0px; margin-left:15px'>Configura&ccedil;&otilde;es</button></a>";}

?>
</div>