<?php
/*
Export to PHP Array plugin for PHPMyAdmin
@version 4.9.7
Database `suif_grancafe`
Table `select_tipo_produto`
SELECT codigo, descricao, cod_produto FROM select_tipo_produto WHERE estado_registro='ATIVO' ORDER BY codigo
Exportar formato PHP Array
*/

/* /* VERSÃO ANTIGA QUE ESTAVA FIXA
$select_tipo_produto = array(
  array('codigo' => '1','descricao' => '4','cod_produto' => '2'),
  array('codigo' => '2','descricao' => '6','cod_produto' => '2'),
  array('codigo' => '3','descricao' => '6/7','cod_produto' => '2'),
  array('codigo' => '4','descricao' => '7','cod_produto' => '2'),
  array('codigo' => '5','descricao' => '7/8','cod_produto' => '2'),
  array('codigo' => '6','descricao' => '8','cod_produto' => '2'),
  array('codigo' => '7','descricao' => '600 Defeitos','cod_produto' => '2'),
  array('codigo' => '8','descricao' => '800 Defeitos','cod_produto' => '2'),
  array('codigo' => '9','descricao' => 'Escolha 50%','cod_produto' => '2'),
  array('codigo' => '10','descricao' => 'Escolha 70%','cod_produto' => '2'),
  array('codigo' => '11','descricao' => 'Escolha 80%','cod_produto' => '2'),
  array('codigo' => '12','descricao' => 'Tipo I','cod_produto' => '4'),
  array('codigo' => '13','descricao' => 'Tipo II','cod_produto' => '4'),
  array('codigo' => '14','descricao' => 'Tipo III','cod_produto' => '4'),
  array('codigo' => '15','descricao' => 'Fora de Tipo','cod_produto' => '4'),
  array('codigo' => '16','descricao' => 'BAHIA 1','cod_produto' => '5'),
  array('codigo' => '17','descricao' => 'BAHIA 2','cod_produto' => '5'),
  array('codigo' => '42','descricao' => 'Coladas - Resíduo','cod_produto' => '9'),
  array('codigo' => '43','descricao' => 'Coladas - Amendoa','cod_produto' => '9'),
  array('codigo' => '44','descricao' => 'Grinders - Resíduo','cod_produto' => '9'),
  array('codigo' => '45','descricao' => 'Grinders - Nibs','cod_produto' => '9'),
  array('codigo' => '46','descricao' => 'SOL','cod_produto' => '3'),
  array('codigo' => '47','descricao' => 'SECADOR','cod_produto' => '3'),
  array('codigo' => '48','descricao' => 'TIPO UTZ','cod_produto' => '4'),
  array('codigo' => '49','descricao' => '4','cod_produto' => '10'),
  array('codigo' => '50','descricao' => '6','cod_produto' => '10'),
  array('codigo' => '51','descricao' => '6/7','cod_produto' => '10'),
  array('codigo' => '52','descricao' => '7','cod_produto' => '10'),
  array('codigo' => '53','descricao' => '7/8','cod_produto' => '10'),
  array('codigo' => '54','descricao' => '8','cod_produto' => '10'),
  array('codigo' => '55','descricao' => '600 Defeitos','cod_produto' => '10'),
  array('codigo' => '56','descricao' => '800 Defeitos','cod_produto' => '10'),
  array('codigo' => '57','descricao' => 'Escolha 50%','cod_produto' => '10'),
  array('codigo' => '58','descricao' => 'Escolha 70%','cod_produto' => '10'),
  array('codigo' => '59','descricao' => 'Escolha 80%','cod_produto' => '10'),
  array('codigo' => '60','descricao' => 'Pó','cod_produto' => '11'),
  array('codigo' => '61','descricao' => 'Talos','cod_produto' => '11'),
  array('codigo' => '62','descricao' => 'Pedras','cod_produto' => '11'),
  array('codigo' => '63','descricao' => 'Pó','cod_produto' => '12'),
  array('codigo' => '64','descricao' => 'MADURA','cod_produto' => '13'),
  array('codigo' => '65','descricao' => 'G4G','cod_produto' => '3')
);

*/

include "conecta_bd.php";

$resultado = mysqli_query($conexao, "SELECT codigo, descricao, cod_produto 
                                       FROM select_tipo_produto 
                                      WHERE estado_registro='ATIVO' 
                                    ORDER BY codigo");


$select_tipo_produto = array();

// Loop através dos resultados da consulta
while ($linha = $resultado->fetch_assoc()) {
    // Adiciona os resultados ao array
    $select_tipo_produto[] = $linha;
}

include "desconecta_bd.php";

?>