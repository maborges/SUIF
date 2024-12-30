<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");

$produtorid = $_POST['produtorid'];
$situacaoid = $_POST['situacaoid'];
$descricao = $_POST['descricao'];
$background = '';
$situacaotexto = '';

$sqlIns = "insert into situacao_compra_historico 
                    (fornecedor, situacao_compra, username, data_cadastro, descricao)
                    values  ($produtorid,$situacaoid,'$username',now(),'$descricao')";
                    
$sqlUpd = "update cadastro_pessoa set situacao_compra  = $situacaoid where codigo = $produtorid";

// Executa a consulta
if (mysqli_query($conexao, $sqlUpd)) {

    if (mysqli_query($conexao, $sqlIns)) {
        switch ($situacaoid) {
            case 0:
                $situacaotexto = 'LIBERADA';
                $background = '#7FFF00';
                break;
            case 1:
                $situacaotexto = 'ANÁLISE';
                $background = '#FFFF00';
                break;
            case 2:
                $situacaotexto = 'BLOQUEADA';
                $background = '#FF0000';
                break;
        }
        
        $res = [
            'status' => 200,
            'message' => 'Situação atualizada com sucesso',
            'situacao' => $situacaoid,
            'situacaotexto' => $situacaotexto,
            'background' => $background 
        ];
        echo json_encode($res);
        return;
    } else {
        echo "Erro na atualização do registro: " . mysqli_error($conexao) . " Erro: " . $sql;
    }
} else {
    echo "Erro na atualização do registro: " . mysqli_error($conexao) . " Erro: " . $sqlUpd;
}    

// Fecha a conexão com o banco de dados
mysqli_close($conexao);