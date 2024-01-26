<?php
$busca_saldo_armazenado = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor' AND filial='$filial' AND cod_produto='$cod_produto'");
$linhas_sa = mysqli_num_rows ($busca_saldo_armazenado);
$aux_sa = mysqli_fetch_row($busca_saldo_armazenado);

if ($linhas_sa == 0)
{$saldo_produtor = 0;}
else
{$saldo_produtor = $aux_sa[9];}
?>