<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../helpers.php");

// Identifica o tipo de operação
$fieldName = filter_input(INPUT_GET, 'fieldName', FILTER_SANITIZE_STRING);

$conexao = ConnectDB();
try {
    // Verifica se há uma pesquisa recebida via AJAX
    // Pesquisa para campos específicos
    if ($fieldName) {
        $value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING);
    
        // Prepara a consulta SQL com LIKE para correspondências parciais
        // O campo a ser pesquisado sempre retornara nomeado com "value" também
        $stmt = "select codigo, descricao, unidade_print, quantidade_un, preco_compra_maximo, $fieldName as value
                   from cadastro_produto 
                  where estado_registro = 'ATIVO' and "
            . $fieldName . " like '%$value%' " .
            'order by descricao';
    
        $stmt = $conexao->prepare($stmt);
    
        // Executa a consulta
        if ($stmt->execute()) {
            $produtos = $stmt->get_result();
            $resultados = [];
    
            // Processa os resultados como objetos
            while ($produto = $produtos->fetch_object()) {
                $resultados[] = (object) [
                    'codigo' => htmlspecialchars($produto->codigo, ENT_QUOTES, 'UTF-8'),
                    'descricao' => htmlspecialchars($produto->descricao, ENT_QUOTES, 'UTF-8'),
                    'unidade' => htmlspecialchars($produto->unidade_print, ENT_QUOTES, 'UTF-8'),
                    'quantidadeUnidade' => htmlspecialchars($produto->quantidade_un, ENT_QUOTES, 'UTF-8'),
                    'precoCompraMaximo' => htmlspecialchars($produto->preco_compra_maximo, ENT_QUOTES, 'UTF-8'),
                    'value' => htmlspecialchars($produto->value, ENT_QUOTES, 'UTF-8')
                ];
            }

            $produtos->close();

            echo json_encode($resultados);
        } else {
            echo json_encode(['error' => 'Erro ao executar a consulta.']);
        }
    
    } else {
        $descricao  = $_GET['descricao'] ?? '';
        $conditions = " and codigo < 1";
    
        if ($descricao) {
            $conditions = " and descricao like '%$descricao%'";
        }
    
        $stmt = "select codigo, descricao, unidade_print, quantidade_un, preco_compra_maximo
                   from cadastro_produto 
                  where estado_registro = 'ATIVO' $conditions 
                order by descricao";
    
        $query = $conexao->prepare($stmt);
    
        // Executa a consulta
        if ($query->execute()) {
            $produtos = $query->get_result();
            $data = [];
    
            // Processa os resultados como objetos
            while ($produto = $produtos->fetch_object()) {
                $data[] = [
                    'codigo' => htmlspecialchars($produto->codigo, ENT_QUOTES, 'UTF-8'),
                    'descricao' => htmlspecialchars($produto->descricao, ENT_QUOTES, 'UTF-8'),
                    'unidade' => htmlspecialchars($produto->unidade_print, ENT_QUOTES, 'UTF-8'),
                    'quantidadeUnidade' => htmlspecialchars($produto->quantidade_un, ENT_QUOTES, 'UTF-8'),
                    'precoCompraMaximo' => htmlspecialchars($produto->preco_compra_maximo, ENT_QUOTES, 'UTF-8')
                ];
            }

            $produtos->close();
            echo json_encode(['data' => $data]);
        } else {
            echo json_encode(['error' => 'Erro ao executar a consulta.']);
        }
    }

} finally {
    DisconnectDB($conexao);
}

