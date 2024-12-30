<?php
if ($saldo == 0)
{
$deleta_saldo = mysqli_query ($conexao, "DELETE FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
}

else
{
	if ($linhas_sa == 0)
	{
	$cria_saldo = mysqli_query ($conexao, "INSERT INTO saldo_armazenado (codigo, cod_fornecedor, fornecedor_print, filial, cod_produto, produto_print, tipo_produto, tipo_print, unidade_print, saldo) VALUES (NULL, '$fornecedor', '$fornecedor_print', '$filial', '$cod_produto', '$produto_print', '$cod_tipo', '$tipo_print', '$unidade_print', '$saldo')");
	}
	
	else
	{
	$altera_saldo = mysqli_query ($conexao, "UPDATE saldo_armazenado SET saldo='$saldo' WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
	}
}
?>