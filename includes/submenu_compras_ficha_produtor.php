<div style="width:1280px; height:3px"></div>

<div style="width:auto; height:15px">
<?php

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_selec_fornecedor.php' >&#8226; Ficha do Produtor</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/seleciona_produtor.php' >&#8226; Ficha do Produtor (Antigo)</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona.php' >&#8226; Entrada Romaneio</a></div>";

if (isset($permissao[32]) && ($permissao[32] == "S"))
{
	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona_2.php' >&#8226; Entrada Direta</a></div>";
}

if (isset($permissao[62]) && ($permissao[62] == "S"))
{
	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona_3.php' >&#8226; Entrada</a></div>";
}


	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/transferencias/index_transferencia.php' >&#8226; Transfer&ecirc;ncia</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' >&#8226; Tal&otilde;es</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/ficha_produtor/saida_seleciona_3.php' >&#8226; Sa&iacute;da</a></div>";

?>
</div>
