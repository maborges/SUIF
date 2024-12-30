<?php
$busca_saldo = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_pesquisa' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
$linhas_s = mysqli_num_rows ($busca_saldo);
$aux_s = mysqli_fetch_row($busca_saldo);

if ($linhas_s == 0)
{$saldo_produtor = 0;}
else
{$saldo_produtor = $aux_s[9];}
?>