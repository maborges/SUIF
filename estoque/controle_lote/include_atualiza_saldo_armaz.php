<?php
if ($botao == "NOVA_MOVIMENTACAO" or $botao == "EXCLUIR")
{
$altera_saldo = mysqli_query ($conexao, "UPDATE cadastro_lote SET saldo_armazenado='$saldo', nome_produto='$produto_print', cod_tipo_producao='$tipo_prod_form', umidade='$umidade_form', densidade='$densidade_form', impureza='$impureza_form', broca='$broca_form', numero_romaneio='$numero_romaneio_form', cod_fornecedor='$fornecedor_form' WHERE cod_armazem='$cod_armazem' AND codigo_lote='$lote_form' AND filial='$filial'");
}
else
{
$altera_saldo = mysqli_query ($conexao, "UPDATE cadastro_lote SET nome_produto='$produto_print', cod_tipo_producao='$tipo_prod_form', umidade='$umidade_form', densidade='$densidade_form', impureza='$impureza_form', broca='$broca_form' WHERE cod_armazem='$cod_armazem' AND codigo_lote='$lote_form' AND filial='$filial'");
}

?>