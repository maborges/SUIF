<div style="width:1280px; height:3px"></div>

<div style="width:auto; height:15px">
<?php

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php' >&#8226; Cadastros de Usu&aacute;rios</a></div>";

if ($permissao[56] == "S")
{
	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_permissoes.php' >&#8226; Permiss&otilde;es de Usu&aacute;rios</a></div>";
}

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_troca_senha.php' >&#8226; Trocar de Senha</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_resetar_senha.php' >&#8226; Resetar Senha de Usu&aacute;rio</a></div>";

?>
</div>