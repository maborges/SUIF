<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../helpers.php"); // Helper para funções de sanitização e validação

// verifica se é uma exclusão
$codigo = $_REQUEST['codigo'] ?? '';
$conexao = ConnectDB();

try {
    if (isset($_REQUEST['excluiRegistro'])) {

        $sql =
            "UPDATE cotacao_produto SET estado_registro = 'EXCLUIDO', alterado_por = '$username', alterado_em = NOW()
          WHERE codigo = $codigo";

        $stmt = mysqli_query($conexao, $sql);

        if (!$stmt) {
            echo json_encode(['error' => true, 'message' => 'Erro ao executar a exclusão no banco de dados.']);
            exit;
        }

        echo json_encode(['error' => false, 'message' => 'Dados atualizados com sucesso.']);
        exit;
    }

    // Inicializando variáveis de erro
    $error = '';

    // Dados enviados via POST
    $nome = $_REQUEST['nomeProdutor'] ?? '';
    $telefone = $_REQUEST['telefone'] ?? '';
    $email = $_REQUEST['email'] ?? '';
    $produto = $_REQUEST['produto'] ?? '';
    $produtor = $_REQUEST['produtor'] ?? 'null';
    $classificacaoPessoa = $_REQUEST['classificacaoPessoa'] ?? 'NCD';
    $precoCompraMaximo = $_REQUEST['precoCompraMaximo'] ?? 0;
    $quantidade = $_REQUEST['quantidade'] ?? 0;
    $valorUnitarioPedido = $_REQUEST['valorUnitarioPedido'] ?? 0;
    $valorUnitarioOferecido = $_REQUEST['valorUnitarioOferecido'] ?? 0;
    $situacao = $_REQUEST['situacao'] ?? '';
    $observacao = $_REQUEST['observacao'] ?? '';

    // Validação dos campos
    if (empty($nome)) {
        $error = "Informe o nome do produtor.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido.";
    } elseif (empty($produto)) {
        $error = "Informe o produto.";
    } elseif (empty($quantidade) || !is_numeric($quantidade) || $quantidade <= 0) {
        $error = "O campo Quantidade deve ser maior que zero.";
    } elseif (empty($valorUnitarioPedido) || !is_numeric($valorUnitarioPedido) || $valorUnitarioPedido <= 0) {
        $error = "O campo Valor Pedido deve ser maior que zero.";
    } elseif (empty($valorUnitarioOferecido)) {
        $error = "O campo Valor Oferecido deve ser informado.";
    } elseif (empty($situacao)) {
        $error = "O campo Situação deve ser informado.";
    }

    // Se houver erros, retorna-os ao cliente e encerra o script
    if (!empty($error)) {
        echo json_encode(['error' => true, 'message' => $error]);
        exit;
    }

    $sql = "";

    // Verifica se é uma atualização ou uma nova inserção
    if (empty($codigo)) {
        if (!$produtor) {
            $auxProdutor = 'null';
        } else {
            $auxProdutor = $produtor;
        }

        $sql = "
            INSERT INTO cotacao_produto 
            (nome, telefone, email, produtor, produto, preco_compra_maximo, quantidade, valor_unitario_pedido, valor_unitario_oferecido, situacao, observacao, criado_em, criado_por, estado_registro, classificacao_pessoa) 
            VALUES ('$nome', '$telefone', '$email', $auxProdutor, $produto, $precoCompraMaximo, $quantidade, $valorUnitarioPedido, $valorUnitarioOferecido, '$situacao', '$observacao', NOW(), '$username', 'ATIVO', '$classificacaoPessoa')
        ";

        $stmt = mysqli_query($conexao, $sql);

        if (!$stmt) {
            echo json_encode(['error' => true, 'message' => 'Erro ao executar a atualização no banco de dados.', 'sql' => $sql]);
            exit;
        }

        echo json_encode(['error' => false, 'message' => 'Dados gravados com sucesso.']);
    } else {
        if (!$produtor) {
            $auxProdutor = 'null';
        } else {
            $auxProdutor = $produtor;
        }
        // Atualização no banco de dados
        $sql = "
            UPDATE cotacao_produto SET 
                        nome = '$nome', telefone = '$telefone', 
                        email = '$email', produtor = $auxProdutor, classificacao_pessoa = '$classificacaoPessoa',
                        produto = $produto, preco_compra_maximo = $precoCompraMaximo, 
                        quantidade = $quantidade, valor_unitario_pedido = $valorUnitarioPedido, 
                        valor_unitario_oferecido = $valorUnitarioOferecido,
                        situacao = '$situacao',
                        observacao = '$observacao',
                        alterado_em = NOW(), 
                        alterado_por = '$username' 
            WHERE codigo = $codigo
        ";

        $stmt = mysqli_query($conexao, $sql);

        if (!$stmt) {
            echo json_encode(['error' => true, 'message' => 'Erro ao executar a atualização no banco de dados.']);
            exit;
        }

        echo json_encode(['error' => false, 'message' => 'Dados atualizados com sucesso.']);
    }
} finally {
    DisconnectDB($conexao);
}
