<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");

/**
 * Retorna os resultados em formato JSON.
 * @param array $dados
 */
function retornarJson(array $dados)
{
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}

/**
 * Sanitiza entrada e protege contra SQL Injection.
 * @param mysqli $conexao
 * @param string $valor
 * @return string
 */
function sanitizar(mysqli $conexao, string $valor): string
{
    return htmlspecialchars($conexao->real_escape_string($valor), ENT_QUOTES, 'UTF-8');
}

/**
 * Executa a lógica do autocomplete.
 * @param mysqli $conexao
 * @param string $fieldName
 * @param string $value
 */
function executarAutocomplete(mysqli $conexao, string $fieldName, string $value)
{
    $conexao = ConnectDB();
    try {

        $camposPermitidos = ['nome', 'telefone'];
    
        if (!in_array($fieldName, $camposPermitidos)) {
            retornarJson(['error' => 'Campo de pesquisa inválido.']);
        }
    
        $sql = "
            SELECT 
                codigo, nome, telefone, email, criado_em, 
                situacao, criado_por,
                $fieldName AS value
            FROM 
                cotacao_produto
            WHERE 
                estado_registro = 'ATIVO' AND $fieldName LIKE '%$value%'
        ";
    
        $stmt = $conexao->prepare($sql);
    
        if (!$stmt) {
            retornarJson(['error' => 'Erro ao preparar a consulta de autocomplete.']);
        }
    
        if ($stmt->execute()) {
            $resultados = [];
            $resultado = $stmt->get_result();
    
            while ($contato = $resultado->fetch_assoc()) {
                $resultados[] = [
                    'codigo' => sanitizar($conexao, $contato['codigo']),
                    'nome' => sanitizar($conexao, $contato['nome']),
                    'telefone' => sanitizar($conexao, $contato['telefone']),
                    'email' => sanitizar($conexao, $contato['email']),
                    'criadoEm' => sanitizar($conexao, $contato['criado_em']),
                    'value' => sanitizar($conexao, $contato['value'])
                ];
            }

            $resultado->close();
            retornarJson($resultados);
        } else {
            retornarJson(['error' => 'Erro ao executar consulta de autocomplete.']);
        }
    } finally {
        DisconnectDB($conexao);
    }
   
}

/**
 * Executa a lógica da pesquisa principal.
 * @param mysqli $conexao
 * @param array $filtros
 */
function executarPesquisaPrincipal(mysqli $conexao, array $filtros)
{
    $where = 'and a.codigo > 0';

    if (!empty($filtros['nome']) or !empty($filtros['telefone'])) {
        $where = ''; 
    }
    
    if (!empty($filtros['nome'])) {
        $where .= " and a.nome LIKE '%" . $filtros['nome'] . "%'";
    }

    if (!empty($filtros['telefone'])) {
        $where .= " and a.telefone LIKE '%" . $filtros['telefone'] . "%'";
    }

    $sql = "
        SELECT 
            a.codigo, a.nome, a.telefone, a.email, a.produtor, a.produto,
            a.quantidade, a.valor_unitario_pedido, a.valor_unitario_oferecido, a.situacao, a.observacao,
            a.criado_em, a.criado_por, a.alterado_em, a.alterado_por, a.criado_em, a.criado_por, a.excluido_em,
            a.nome AS nomeProdutor, a.classificacao_pessoa, 
            CONCAT(c.descricao, ' (', c.unidade_print, ')') as nomeProduto,
            c.preco_compra_maximo
        FROM 
            cotacao_produto a
        LEFT JOIN 
            cadastro_pessoa b ON b.codigo = a.produtor
        INNER JOIN 
            cadastro_produto c on c.codigo = a.produto
        WHERE a.estado_registro = 'ATIVO' 
        $where
        ORDER BY a.criado_em DESC
    ";

    $conexao = ConnectDB();

    try {
        $stmt = $conexao->prepare($sql);

        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            $resultados = [];
    
            while ($contato = $resultado->fetch_object()) {
                $resultados[] = (object) [
                    'codigo' => sanitizar($conexao, $contato->codigo),
                    'nome' => sanitizar($conexao, $contato->nome),
                    'telefone' => sanitizar($conexao, $contato->telefone),
                    'email' => sanitizar($conexao, $contato->email),
                    'produtor' => $contato->produtor,
                    'nomeProdutor' => $contato->nomeProdutor,
                    'classificacaoPessoa' => $contato->classificacao_pessoa,
                    'produto' => $contato->produto,
                    'nomeProduto' => sanitizar($conexao, $contato->nomeProduto),
                    'precoCompraMaximo' => $contato->preco_compra_maximo,
                    'quantidade' => $contato->quantidade,
                    'valorUnitarioPedido' => $contato->valor_unitario_pedido,
                    'valorUnitarioOferecido' => $contato->valor_unitario_oferecido,
                    'situacao' => sanitizar($conexao, $contato->situacao),
                    'observacao' => sanitizar($conexao, $contato->observacao),
                    'criadoEm' => $contato->criado_em,
                    'criadoPor' => $contato->criado_por,
                    'alteradoEm' => $contato->alterado_em,
                    'alteradoPor' => $contato->alterado_por,
                    'excluidoEm' => $contato->excluido_em
                    
                ];
            }

            $resultado->close();
            retornarJson(['data' => $resultados]);
    
        } else {
            retornarJson(['error' => 'Erro ao executar consulta principal.']);
        }
    } finally {
        DisconnectDB($conexao);
    }

}

// Identifica o tipo de operação
$fieldName = filter_input(INPUT_GET, 'fieldName', FILTER_SANITIZE_STRING);
$value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING);

if ($fieldName && $value) {
    executarAutocomplete($conexao, $fieldName, $value);
} else {
    $filtros = [
        'nome' => filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_STRING),
        'telefone' => filter_input(INPUT_GET, 'telefone', FILTER_SANITIZE_STRING)
    ];
    
    executarPesquisaPrincipal($conexao, $filtros);
}

// Fecha a conexão
DisconnectDB($conexao);
