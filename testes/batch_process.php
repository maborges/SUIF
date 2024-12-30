<?php
session_start();

// Define o estado do batch como 'running'
$_SESSION['batch_status'] = 'running';

// Simula o processamento do batch
sleep(10); // Simula um processo longo

// Após a conclusão, atualiza o status para 'completed'
$_SESSION['batch_status'] = 'completed';

// Retorna uma resposta de sucesso
echo json_encode([
    "status" => "success",
    "message" => "Processo concluído com sucesso!",
    "valor1" => $_GET['$varlor1'],
    "valor2" => $_GET['$varlor2']
]);



