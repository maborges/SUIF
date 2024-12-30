<?php
include_once("../../Sankhya.php");

// Verifica se a requisição é uma requisição AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSelecionaParceiro'])) {
    // Obtém os parâmetros da requisição
    $SankhyaRecord = $_POST['SankhyaRecord'];

    // Chama a função PHP e retorna a resposta como JSON
    $resposta = AtualizaCamposSUIF($SankhyaRecord);
}


$datatable = $_REQUEST;
$datatable = filter_var_array($datatable, FILTER_SANITIZE_SPECIAL_CHARS);

$limite = $datatable['length'];  // Limite de registros
$offset = $datatable['start'];   // a partir de qual registro
$busca = $datatable['search']['value']; // mecanismo de busca

$colunas = [
    0 => 'CODPAR',
    1 => 'NOMEPARC',
    2 => 'RAZAOSOCIAL',
    3 => 'CGC_CPF',
];

$ordem  = " " . $colunas[$datatable['order'][0]['column']] . " ";
$ordem .= " " . $datatable['order'][0]['dir'] . " ";

// faz login e obtém o token de acesso0
$tokenSankhya = Sankhya::login();

$urlApi = 'https://api.sankhya.com.br/gateway/v1/mge/service.sbr?serviceName=DbExplorerSP.executeQuery&outputType=json';

$total = TotaRegistros();

$jsonData = array(
    'serviceName' => 'DbExplorerSP.executeQuery',
    'requestBody' => array(
        'sql' => "SELECT A.CODPARC, A.NOMEPARC, A.RAZAOSOCIAL, A.CGC_CPF FROM TGFPAR A WHERE A.NOMEPARC LIKE '%{$busca}%' OR A.RAZAOSOCIAL LIKE '%{$busca}%' OR A.CGC_CPF LIKE '%{$busca}%' order by $ordem offset $offset rows fetch next $limite rows only"
    )
);

// Converter o array associativo em uma string JSON
$jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $urlApi,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json', // Se a requisição for JSON
        'Authorization: Bearer ' . $tokenSankhya,
    ),
    CURLOPT_POSTFIELDS => $jsonString,
]);

$result = curl_exec($curl);

// Verificar por erros
if (curl_errno($curl)) {
    echo 'Erro ao fazer a requisição cURL: ' . curl_error($curl);
}

// Fechar a sessão cURL
curl_close($curl);

$dados = json_decode($result, true)['responseBody']['rows'];

// Variaveis que devem ser informadas no array do datatable
$retorno = [
    "draw" => $datatable['draw'],
    "recordsTotal" => $total,
    "recordsFiltered" => $total,
    "data" => $dados
];


echo json_encode($retorno);

function AtualizaCamposSUIF($SankhyaRecord): string
{
    return 'ok';
}

function TotaRegistros(): int
{
    global $urlApi;
    global $tokenSankhya;
    global $busca;

    $jsonData = array(
        'serviceName' => 'DbExplorerSP.executeQuery',
        'requestBody' => array(
            'sql' => "SELECT count(CODPARC) from TGFPAR WHERE NOMEPARC LIKE '%{$busca}%' OR RAZAOSOCIAL LIKE '%{$busca}%' OR CGC_CPF LIKE '%{$busca}%'"
        )
    );

    // Converter o array associativo em uma string JSON
    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $urlApi,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json', // Se a requisição for JSON
            'Authorization: Bearer ' . $tokenSankhya,
        ),
        CURLOPT_POSTFIELDS => $jsonString,
    ]);

    $result = curl_exec($curl);

    // Verificar por erros
    if (curl_errno($curl)) {
        echo 'Erro ao fazer a requisição cURL: ' . curl_error($curl);
    }

    // Fechar a sessão cURL
    curl_close($curl);

    $dados = json_decode($result, true)['responseBody']['rows'];

    //print_r($dados[0][0]);
    //     echo (int)$dados[0][0];

    $total =  (int)$dados[0][0];

    return $total;
}
