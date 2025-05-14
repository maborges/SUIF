<?php
include_once("cron_path.php");
require_once($base_path . "/sankhya/Sankhya.php"); 


$contador = 0;
$msgOk = '';
$msgEr = '';

$sqlArmazenameto =  "select b.codigo,
                            a.numero_romaneio
                        from estoque a
                            inner join nota_fiscal_entrada b
                                    on b.codigo_romaneio   = a.numero_romaneio  
                                    and b.estado_registro   = 'ATIVO'
                                    and b.natureza_operacao = 'ARMAZENAGEM'
                                    and b.fatura_confirmada_sankhya <> 'S'
                        where a.estado_registro = 'ATIVO'
                        and a.movimentacao = 'ENTRADA'
                        and b.pedido_confirmado_sankhya <> 'S'
                        and a.data >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) and a.data <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)
                    order by a.data, a.numero_romaneio";

// Início do processo
logMessage("Iniciando processo", $log_armazenagem);

try {
    require_once($base_path . "/includes/conecta_bd.php");
} catch (Exception $e) {
    logMessage("Erro ao conectar ao banco de dados: " . $e->getMessage(), $log_armazenagem);
    exit();
}

try {
    // Executar as atualizações
    $faturas = Sankhya::queryExecuteDB($sqlArmazenameto);

    if ($faturas['errorCode']) {
        logMessage("{$faturas['errorCode']}: {$faturas['errorMessage']}", $log_armazenagem);
        exit(0);
    }

    foreach ($faturas['rows'] as $fatura) {
        $notaFiscalEntrada = $fatura[0];

        // Faz a gravação do pedido no Sankhya 
        Sankhya::IncluiArmazenagem($notaFiscalEntrada);

        $contador = +1;
    }

    if ($contador) {
        logMessage("Foram atualizadas $contador faturas.", $log_armazenagem);
    } else {
        logMessage("Nenhuma fatura foi processada/atualizada.", $log_armazenagem);
    }
} catch (Exception $e) {
    logMessage("Erro ao executar atualizações: " . $e->getMessage(), $log_armazenagem);
    exit();
} finally {
    $conexao->close();
    logMessage("Processo finalizado", $log_armazenagem);
}

// Função para registrar no log
function logMessage($message, $file)
{
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($file, "[$timestamp] $message\n", FILE_APPEND);
}

exit();
