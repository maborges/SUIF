<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../helpers.php");


// Identifica o tipo de operação
$fieldName = filter_input(INPUT_GET, 'fieldName', FILTER_SANITIZE_STRING);
$value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING);

ConnectDB();

try {
    // Verifica se há uma pesquisa recebida via AJAX
    // Pesquisa para campos específicos
    if ($fieldName) {

        // Prepara a consulta SQL com LIKE para correspondências parciais
        // O campo a ser pesquisado sempre retornara nomeado com "value" também
        $sql = "select codigo, nome, tipo, telefone_1, cpfcnpj, email, situacao_compra, 
                        categoria, cadastro_validado, validado_serasa, embargado,
                        case 
                            when (63 in (classificacao_1,classificacao_2,classificacao_3,classificacao_4)) then 'PDT' 
                            else 'FVR' 
                        end as classificacao_pessoa, 
                        $fieldName as value
                    from cadastro_pessoa 
                where estado_registro = 'ATIVO' and "
            . $fieldName . " like '%$value%' " .
            'order by nome';

        $stmt = $conexao->prepare($sql);

        // Executa a consulta
        if ($stmt->execute()) {
            $produtores = $stmt->get_result();
            $resultados = [];

            // Processa os resultados como objetos
            while ($produtor = $produtores->fetch_object()) {
                $resultados[] = (object) [
                    'codigo' => htmlspecialchars($produtor->codigo, ENT_QUOTES, 'UTF-8'),
                    'nome' => htmlspecialchars($produtor->nome, ENT_QUOTES, 'UTF-8'),
                    'tipo' => htmlspecialchars($produtor->tipo, ENT_QUOTES, 'UTF-8'),
                    'telefone' => htmlspecialchars($produtor->telefone_1, ENT_QUOTES, 'UTF-8'),
                    'cpfcnpj' => htmlspecialchars($produtor->cpfcnpj, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($produtor->email, ENT_QUOTES, 'UTF-8'),
                    'situacaoCompra' => htmlspecialchars($produtor->situacao_compra, ENT_QUOTES, 'UTF-8'),
                    'categoria' => htmlspecialchars($produtor->categoria, ENT_QUOTES, 'UTF-8'),
                    'cadastroValidado' => htmlspecialchars($produtor->cadastro_validado, ENT_QUOTES, 'UTF-8'),
                    'validadoSerasa' => htmlspecialchars($produtor->validado_serasa, ENT_QUOTES, 'UTF-8'),
                    'embargado' => htmlspecialchars($produtor->embargado, ENT_QUOTES, 'UTF-8'),
                    'classificacaoPessoa' => htmlspecialchars($produtor->classificacao_pessoa, ENT_QUOTES, 'UTF-8'),
                    'value' => htmlspecialchars($produtor->value, ENT_QUOTES, 'UTF-8')
                ];
            }

            // Retorna os resultados em formato JSON
            $produtores->close();
            echo json_encode($resultados);
        } else {
            echo json_encode(['error' => 'Erro ao executar a consulta.']);
        }

    } else {
        $nome      = $_POST['nome'] ?? '';
        $telefone  = $_POST['telefone'] ?? '';
        $cpfcnpj   = $_POST['cpfcnpj'] ?? '';
        $conditions = " and codigo < 1";

        if ($nome or $telefone or $cpfcnpj) {
            $conditions = "";

            if ($nome) {
                $conditions .= " and nome like '%$nome%'";
            }

            if ($telefone) {
                $conditions .= " and telefone like '%$telefone%'";
            }

            if ($cpfcnpj) {
                $conditions .= " and cpfcnpj like '%$cpfcnpj%'";
            }
        }

        $stmt = "select codigo, nome, tipo, telefone_1, cpfcnpj, email, situacao_compra, 
                        categoria, cadastro_validado, validado_serasa, embargado,
                        case 
                            when (63 in (classificacao_1,classificacao_2,classificacao_3,classificacao_4)) then 'PDT' 
                            else 'FVR' 
                        end as classificacao_pessoa 
                   from cadastro_pessoa 
                  where estado_registro = 'ATIVO' 
                        $conditions 
                order by nome";

        $query = $conexao->prepare($stmt);

        // Executa a consulta
        if ($query->execute()) {
            $produtores = $query->get_result();
            $data = [];

            // Processa os resultados como objetos
            while ($produtor = $produtores->fetch_object()) {
                $data[] = [
                    'codigo' => htmlspecialchars($produtor->codigo, ENT_QUOTES, 'UTF-8'),
                    'nome' => htmlspecialchars($produtor->nome, ENT_QUOTES, 'UTF-8'),
                    'tipo' => htmlspecialchars($produtor->tipo, ENT_QUOTES, 'UTF-8'),
                    'telefone' => htmlspecialchars($produtor->telefone_1, ENT_QUOTES, 'UTF-8'),
                    'cpfcnpj' => htmlspecialchars($produtor->cpfcnpj, ENT_QUOTES, 'UTF-8'),
                    'email' => htmlspecialchars($produtor->email, ENT_QUOTES, 'UTF-8'),
                    'situacaoCompra' => htmlspecialchars($produtor->situacao_compra, ENT_QUOTES, 'UTF-8'),
                    'categoria' => htmlspecialchars($produtor->categoria, ENT_QUOTES, 'UTF-8'),
                    'cadastroValidado' => htmlspecialchars($produtor->cadastro_validado, ENT_QUOTES, 'UTF-8'),
                    'validadoSerasa' => htmlspecialchars($produtor->validado_serasa, ENT_QUOTES, 'UTF-8'),
                    'classificacaoPessoa' => htmlspecialchars($produtor->classificacao_pessoa, ENT_QUOTES, 'UTF-8'),
                    'embargado' => htmlspecialchars($produtor->embargado, ENT_QUOTES, 'UTF-8')
                ];
            }
            
            $produtores->close();

            echo json_encode(['data' => $data]);
        } else {
            echo json_encode(['error' => 'Erro ao executar a consulta.']);
        }
    }
} finally {
    DisconnectDB($conexao);
}
