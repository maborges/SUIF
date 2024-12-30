<?php
// ====== ROMANEIO DE SAIDA AUTOMATICO - JAGUARE =======================================================================
if ($fornecedor == "900")
{

$busca_n_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_n_romaneio = mysqli_fetch_row($busca_n_romaneio);
$n_romaneio = $aux_n_romaneio[8];
$contador_n_romaneio = $n_romaneio + 1;
$altera_cont = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_n_romaneio'");

$inserir_saida = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, 
peso_final, desconto_sacaria, desconto, quantidade, unidade, tipo_sacaria, movimentacao, situacao, situacao_romaneio, placa_veiculo, motorista, 
observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, quantidade_sacaria, motorista_cpf, 
num_romaneio_manual, filial_origem, cod_produto, cod_unidade, fornecedor_print, cod_lote, lote_print, 
usuario_finalizacao, hora_finalizacao, data_finalizacao, transferencia_filiais, transferencia_numero) 
VALUES (NULL, '$n_romaneio', '491', '$data', '$produto_apelido', 
'$peso_final', '$peso_inicial', '$desconto_sacaria', '$desconto', '$quantidade', 'KG', '$t_sacaria', 'SAIDA', '$situacao', '$situacao_romaneio', 
'$placa_veiculo', '$motorista', '$obs_form', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'JAGUARE', '$estado_registro', 
'$quant_sacaria', '$motorista_cpf', '$num_romaneio_manual', 'JAGUARE', '$cod_produto', '$cod_un_aux', '$fornecedor_print_2', 
'$cod_lote', '$lote_descricao', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'SIM', '$numero_romaneio')");

$msg = "<div style='color:#0000FF'><b>Transfer&ecirc;ncia interna entre filiais: </b> de JAGUARE para LINHARES</div>";
}

// ====== ROMANEIO DE SAIDA AUTOMATICO - SAO MATEUS =======================================================================
elseif ($fornecedor == "3137")
{

$busca_n_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_n_romaneio = mysqli_fetch_row($busca_n_romaneio);
$n_romaneio = $aux_n_romaneio[8];
$contador_n_romaneio = $n_romaneio + 1;
$altera_cont = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_n_romaneio'");

$inserir_saida = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, 
peso_final, desconto_sacaria, desconto, quantidade, unidade, tipo_sacaria, movimentacao, situacao, situacao_romaneio, placa_veiculo, motorista, 
observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, quantidade_sacaria, motorista_cpf, 
num_romaneio_manual, filial_origem, cod_produto, cod_unidade, fornecedor_print, cod_lote, lote_print, 
usuario_finalizacao, hora_finalizacao, data_finalizacao, transferencia_filiais, transferencia_numero) 
VALUES (NULL, '$n_romaneio', '491', '$data', '$produto_apelido', 
'$peso_final', '$peso_inicial', '$desconto_sacaria', '$desconto', '$quantidade', 'KG', '$t_sacaria', 'SAIDA', '$situacao', '$situacao_romaneio', 
'$placa_veiculo', '$motorista', '$obs_form', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'SAO_MATEUS', '$estado_registro', 
'$quant_sacaria', '$motorista_cpf', '$num_romaneio_manual', 'SAO_MATEUS', '$cod_produto', '$cod_un_aux', '$fornecedor_print_2', 
'$cod_lote', '$lote_descricao', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'SIM', '$numero_romaneio')");

$msg = "<div style='color:#009900'><b>Transfer&ecirc;ncia interna entre filiais:</b> de SAO_MATEUS para LINHARES</div>";
}

// ====== ROMANEIO DE SAIDA AUTOMATICO - LINHARES/POLO =======================================================================
elseif ($fornecedor == "6856")
{

$busca_n_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_n_romaneio = mysqli_fetch_row($busca_n_romaneio);
$n_romaneio = $aux_n_romaneio[8];
$contador_n_romaneio = $n_romaneio + 1;

$altera_cont = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_n_romaneio'");

$inserir_saida = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, 
peso_final, desconto_sacaria, desconto, quantidade, unidade, tipo_sacaria, movimentacao, situacao, situacao_romaneio, placa_veiculo, motorista, 
observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, quantidade_sacaria, motorista_cpf, 
num_romaneio_manual, filial_origem, cod_produto, cod_unidade, fornecedor_print, cod_lote, lote_print, 
usuario_finalizacao, hora_finalizacao, data_finalizacao, transferencia_filiais, transferencia_numero) 
VALUES (NULL, '$n_romaneio', '491', '$data', '$produto_apelido', 
'$peso_final', '$peso_inicial', '$desconto_sacaria', '$desconto', '$quantidade', 'KG', '$t_sacaria', 'SAIDA', '$situacao', '$situacao_romaneio', 
'$placa_veiculo', '$motorista', '$obs_form', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'LINHARES_POLO', '$estado_registro', 
'$quant_sacaria', '$motorista_cpf', '$num_romaneio_manual', 'LINHARES_POLO', '$cod_produto', '$cod_un_aux', '$fornecedor_print_2', 
'$cod_lote', '$lote_descricao', '$usuario_finalizacao', '$hora_finalizacao', '$data_finalizacao', 'SIM', '$numero_romaneio')");

$msg = "<div style='color:#009900'><b>Transfer&ecirc;ncia interna entre filiais:</b> de LINHARES_POLO para LINHARES</div>";
}


else
{}

?>