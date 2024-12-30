<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../helpers.php");


header('Content-Type: application/json');

$action = $_POST['action'] ?? 'fetch';

$conexao = ConnectDB();
try {
    if ($action === 'fetch') {
        $query = "SELECT * FROM cotacao_produto_situacao WHERE estado_registro = 'ATIVO' ORDER BY descricao";
        $result = $conexao->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);
    
        echo json_encode(['data'=> $data]);
    } elseif ($action === 'save') {
        $codigo = $_POST['codigo'];
        $descricao = $_POST['descricao'];
    
        if (empty($codigo) || empty($descricao)) {
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
            exit;
        }
    
        $query = "INSERT INTO cotacao_produto_situacao (codigo, descricao) VALUES (?, ?) ON DUPLICATE KEY UPDATE descricao = ?, estado_registro = 'ATIVO'";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('sss', $codigo, $descricao, $descricao);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar o registro.']);
        }
    } elseif ($action === 'get') {
        $codigo = $_POST['codigo'];
    
        $query = "SELECT * FROM cotacao_produto_situacao WHERE codigo = ? AND estado_registro = 'ATIVO'";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('s', $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
    
        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registro não encontrado.']);
        }

        $result->close();
    } elseif ($action === 'delete') {
        $codigo = $_POST['codigo'];
    
        $query = "UPDATE cotacao_produto_situacao SET estado_registro = 'EXCLUIDO' WHERE codigo = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param('s', $codigo);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir o registro.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} finally {
    DisconnectDB($conexao);
}


