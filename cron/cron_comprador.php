<?php
include_once("cron_path.php");

$sqlUpdate = "update cadastro_pessoa 
                    set comprador = null 
                    where codigo in (
                                    select cp.codigo 
                                    from cadastro_pessoa cp 
                                    where cp.estado_registro = 'ATIVO'
                                    and cp.comprador is not null
                                    and cp.codigo not in (select distinct cpi.codigo 
                                                            from compras cps
                                                                    inner join cadastro_pessoa cpi 
                                                                            on cps.fornecedor = cpi.codigo 
                                                                        and cpi.comprador   is not null,
                                                                    configuracoes cnf
                                                            where cps.movimentacao    = 'COMPRA'
                                                                and cps.estado_registro = 'ATIVO'
                                                                and cps.data_compra     >= DATE_SUB(CURDATE(), INTERVAL cnf.dias_cliente_ativo DAY)
                                                            )   
                                )";


// Início do processo
logMessage("Iniciando processo", $log_comprador);


try {
    require_once($base_path . "/includes/conecta_bd.php");
} catch (Exception $e) {
    logMessage("Erro ao conectar ao banco de dados: " . $e->getMessage(), $log_comprador);
    exit();         
}

try {
    $conexao->begin_transaction();

    // Executar as atualizações
    if ($conexao->query($sqlUpdate)) {
        // Obter o número de registros alterados
        $affected_rows = $conexao->affected_rows;
        $conexao->commit();
        logMessage("Quantidade de registros atualizados para a carteira de disponiveis: " . $affected_rows, $log_comprador);
    } else {
        // Em caso de erro na execução da query
        logMessage("Erro ao conectar a atualização: " . $conexao->error, $log_comprador);
        exit();
    }

} catch (Exception $e) {
    $conexao->rollback();
    logMessage("Erro ao executar atualizações: " . $e->getMessage(), $log_comprador);
    exit();

} finally {
    $conexao->close();
    logMessage("Processo finalizado", $log_comprador);
}


// Função para registrar no log
function logMessage($message, $file)
{
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($file, "[$timestamp] $message\n", FILE_APPEND);
}

exit();
?>