
<?php
include_once "../includes/valida_cookies.php";
include_once "../includes/config.php";
include_once "../includes/conecta_bd.php";
include_once "../helpers.php";
include_once "../Sankhya/sankhya.php";

//print_r(helpers::TiraAcentos("confirmaçao n 03077 Lorencini"));

//$teste = Sankhya::queryExecuteDB("select observacao  from compras where numero_compra = 261420");
// print_r($teste);

try {
    /*
    $resultado = mysqli_query($conexao, "select observacao  from compras where numero_compra = 303154");
    */
    $resultado = Sankhya::queryExecuteDBOnly("select observacao from compras where numero_compra = 303154");

    if (!$resultado['errorCode']) {

        $registro = $resultado[0]->fetch_assoc();
        var_dump('REGISTRO LIDO:',$resultado[0]);
        echo '<br>';

        $xx = $registro['observacao'];
        var_dump($xx);
        echo '<br>';
    
    
        echo 'Direto do banco: ' . $registro['observacao'];
        echo '<br>';
    
        echo "ORIGINAL: $xx";
        echo '<br>';
    
        echo 'TIRA ACENTO: ' . helpers::TiraAcentos($xx) ;
        $xx = mb_convert_encoding($xx,'UTF-8');
    
        echo '<br>';
        echo "CONVERTIDO: $xx";
    
        echo '<br>';
    

    } else {
        var_dump('resultado com erro:', $resultado['errorMessage']);

    }


} catch (Exception $e) {
   print_r($e);

}



/*
$resultado1 = mysqli_query($conexao, "select observacao  from compras where codigo = 190120");
$registro = $resultado1->fetch_all();
$xx = $registro[0][0];
echo $xx;
echo helpers::TiraAcentos($registro[0][0]);
*/

/*
// Loop através dos resultados da consulta
while ($linha = $resultado->fetch_assoc()) {
    // Adiciona os resultados ao array
    echo Helpers::TiraAcentos($linha['observacao']);
}
*/
/*
$cadastro_produto = $GLOBALS['cadastro_produto'] ?? '';
var_dump(Helpers::ConvertePeso('125,55'));



$resultado = mysqli_query($conexao, "SELECT codigo, descricao, produto_print, unidade_print, nome_imagem, cod_tipo_preferencial, quant_kg_saca FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY codigo");

$cod_produto_w = 2;
$cadastro_produto = array();

// Loop através dos resultados da consulta
while ($linha = $resultado->fetch_assoc()) {
    // Adiciona os resultados ao array
    $cadastro_produto[] = $linha;
}

for ($p = 0; $p <= count($cadastro_produto) - 1; $p++) {
    if ($cadastro_produto[$p]["codigo"] == $cod_produto_w) {
        $cod_produto_p = $cadastro_produto[$p]["codigo"];
        $produto_print_p = $cadastro_produto[$p]["descricao"];
        $nome_produto_p = $cadastro_produto[$p]["produto_print"];
        $unidade_print_p = $cadastro_produto[$p]["unidade_print"];
        $nome_imagem_produto_p = $cadastro_produto[$p]["nome_imagem"];
        $quant_kg_saca_p = $cadastro_produto[$p]["quant_kg_saca"];
    }
}

include "includes/desconecta_bd.php";
*/