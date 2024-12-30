<?php

$parceiro = 1567;
// Monta o array representando o JSON
$body = array(
    'serviceName' => 'CACSP.incluirNota',
    'requestBody' => array(
        'nota' => array(
            'cabecalho' => array(
                'NUNOTA' => array("$" => ""),
                'CODPARC' => array('$' => "$produtorIdSankhya"),
                'DTNEG' => array('$' => "$dataCompra"),
                'CODTIPOPER' => array('$' => "$topsSankhya"),
                'CODTIPVENDA' => array('$' => '12'),
                'CODVEND' => array('$' => '0'),
                'CODEMP' => array('$' => "$empresaIdSankhya"),
                'TIPMOV' => array('$' => 'O')
            ),
            'itens' => array(
                'INFORMARPRECO' => 'True',
                'item' => array(
                    array(
                        'NUNOTA' => array("$" => ""),
                        'CODPROD' => array('$' => $produtoIdSankhya),
                        'QTDNEG' => array('$' => "$quantidade"),
                        'CODLOCALORIG' => array('$' => '0'),
                        'CODVOL' => array('$' => "$unidade"),
                        'PERCDESC' => array('$' => '0'),
                        'VLRUNIT' => array('$' => "$precoUnitario")
                    )
                )
            )
        )
    )
);

// Converte o array em JSON
$json = json_encode($body, JSON_PRETTY_PRINT);

// Exibe o JSON
var_dump($json);
?>