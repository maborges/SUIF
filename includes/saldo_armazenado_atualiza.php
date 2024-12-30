<?php
if ($saldo == 0)
{
$deleta_saldo = mysqli_query ($conexao, "DELETE FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_pesquisa' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
}

else
{
	if ($linhas_s == 0)
	{
	$cria_saldo = mysqli_query ($conexao, "INSERT INTO saldo_armazenado (codigo, cod_fornecedor, fornecedor_print, filial, cod_produto, produto_print, tipo_produto, tipo_print, unidade_print, saldo) VALUES (NULL, '$fornecedor_pesquisa', '$nome_pessoa', '$filial', '$cod_seleciona_produto', '$produto_print', '$cod_tipo_produto_form', '$tipo_print', '$unidade_abreviacao', '$saldo')");
	}
	
	else
	{
	$altera_saldo = mysqli_query ($conexao, "UPDATE saldo_armazenado SET saldo='$saldo' WHERE cod_fornecedor='$fornecedor_pesquisa' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
	}
}
?>