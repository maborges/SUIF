<?php
/*
Export to PHP Array plugin for PHPMyAdmin
@version 4.9.7
Database `suif_grancafe`
Table `cadastro_produto`
SELECT codigo, descricao, produto_print, unidade_print, nome_imagem, cod_tipo_preferencial, quant_kg_saca FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY codigo
Exportar formato PHP Array
*/


/* VERSÃO ANTIGA QUE ESTAVA FIXA
$cadastro_produto = array(
  array('codigo' => '2', 'descricao' => 'CAFÉ CONILON', 'produto_print' => 'Café Conilon', 'unidade_print' => 'SC', 'nome_imagem' => 'produto_cafe', 'cod_tipo_preferencial' => '5', 'quant_kg_saca' => '60'),
  array('codigo' => '3', 'descricao' => 'PIMENTA DO REINO', 'produto_print' => 'Pimenta do Reino', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_pimenta', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '50'),
  array('codigo' => '4', 'descricao' => 'CACAU', 'produto_print' => 'Cacau', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_cacau', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '60'),
  array('codigo' => '5', 'descricao' => 'CRAVO DA ÍNDIA', 'produto_print' => 'Cravo da Índia', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_cravo', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '50'),
  array('codigo' => '9', 'descricao' => 'RESÍDUO DE CACAU', 'produto_print' => 'Resíduo de Cacau', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_cacau', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '60'),
  array('codigo' => '10', 'descricao' => 'CAFÉ ARÁBICA', 'produto_print' => 'Café Arábica', 'unidade_print' => 'SC', 'nome_imagem' => 'produto_cafe', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '60'),
  array('codigo' => '11', 'descricao' => 'RESÍDUO DE PIMENTA', 'produto_print' => 'Resíduo de Pimenta', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_pimenta', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '50'),
  array('codigo' => '12', 'descricao' => 'RESÍDUO DE CAFÉ', 'produto_print' => 'Resíduo de Café', 'unidade_print' => 'SC', 'nome_imagem' => 'produto_cafe', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '60'),
  array('codigo' => '14', 'descricao' => 'PIMENTA ROSA (AROEIRA)', 'produto_print' => 'Pimenta Rosa', 'unidade_print' => 'KG', 'nome_imagem' => 'produto_pimenta', 'cod_tipo_preferencial' => NULL, 'quant_kg_saca' => '5')
);
*/

include "conecta_bd.php";

$resultado = mysqli_query($conexao, "SELECT codigo, descricao, produto_print, unidade_print, 
                                            nome_imagem, cod_tipo_preferencial, quant_kg_saca 
                                       FROM cadastro_produto 
                                      WHERE estado_registro='ATIVO' 
                                   ORDER BY codigo");


$cadastro_produto = array();

// Loop através dos resultados da consulta
while ($linha = $resultado->fetch_assoc()) {
    // Adiciona os resultados ao array
    $cadastro_produto[] = $linha;
}

include "desconecta_bd.php";



