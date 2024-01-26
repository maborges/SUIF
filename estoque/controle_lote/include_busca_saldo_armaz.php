<?php
$busca_saldo_armazenado = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE cod_armazem='$cod_armazem' AND codigo_lote='$lote_form' AND filial='$filial'");
$linhas_sa = mysqli_num_rows ($busca_saldo_armazenado);
$aux_sa = mysqli_fetch_row($busca_saldo_armazenado);

if ($linhas_sa == 0)
{$saldo_lote = 0;}
else
{$saldo_lote = $aux_sa[19];}
?>