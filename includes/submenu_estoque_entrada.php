<div style="width:1280px; height:3px"></div>

<div style="width:auto; height:15px">
<?php

    global $permissao;

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/cadastro_1_selec_produto.php' >&#8226; Novo Romaneio</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/buscar_romaneio.php' >&#8226; Buscar Romaneio</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/excluir_romaneio_1.php' >&#8226; Excluir Romaneio</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/relatorio_periodo.php' >&#8226; Relat&oacute;rio por Per&iacute;odo</a></div>";

	echo "
	<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
	<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/relatorio_selec_fornecedor.php' >&#8226; Relat&oacute;rio por Fornecedor</a></div>";

	if ($permissao[148] == 'S') {
		echo "
		<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
		<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/estoque/entrada/filial_veiculos.php' >&#8226; Veículos da Filial</a></div>";
	}

	if ($permissao[149] == 'S') {
		echo "
		<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
		<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/estoque/entrada/averbacao_vigencia.php' >&#8226; Vigência de Averbação</a></div>";
	}

	if ($permissao[150] == 'S') {
		echo "
		<div style='float:left; width:20px; height:15px; border:0px solid #000'></div>
		<div class='link_4' style='float:left; width:auto; height:18px; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/estoque/entrada/averbacao_relatorio.php' >&#8226; Relatório de Averbação</a></div>";
	}
?>
</div>
