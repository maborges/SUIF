<?php
// MOVIMENTACAO PRINT  ==========================================================================================
if ($movimentacao == "COMPRA")
{$movimentacao_print = "Compra";}
elseif ($movimentacao == "ENTRADA")
{$movimentacao_print = "Entrada";}
elseif ($movimentacao == "TRANSFERENCIA_ENTRADA")
{$movimentacao_print = "Transfer&ecirc;ncia - Entrada";}
elseif ($movimentacao == "ENTRADA_FUTURO")
{$movimentacao_print = "Entrada - Contrato Futuro";}
elseif ($movimentacao == "TRANSFERENCIA_SAIDA")
{$movimentacao_print = "Transfer&ecirc;ncia - Sa&iacute;da";}
elseif ($movimentacao == "SAIDA_FUTURO")
{$movimentacao_print = "Sa&iacute;da - Pgto Contrato Futuro";}
elseif ($movimentacao == "SAIDA")
{$movimentacao_print = "Sa&iacute;da";}
else
{$movimentacao_print = "-";}
// ======================================================================================================
?>