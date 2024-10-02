// Função para verificar o estado do batch no servidor
async function checkBatchStatus() {
    const response = await $.ajax({
        url: 'batch_status.php',
        method: 'GET'
    });

    const result = JSON.parse(response);

    if (result.status === 'running') {
        $('#loadingSpinner').show();
        return true; // O processo ainda está em execução
    } else if (result.status === 'completed') {
        $('#loadingSpinner').hide();
        Swal.fire({
            title: 'Concluído!',
            text: 'O processo foi concluído com sucesso!',
            icon: 'success'
        });
        localStorage.removeItem('batchRunning'); // Limpa o estado de execução do localStorage
        return false; // O processo foi concluído
    }
    return false;
}

// Função para iniciar o batch process
async function runBatchProcess(params) {
    if (localStorage.getItem('batchRunning') === 'true') {
        Swal.fire({
            title: 'Aguarde!',
            text: 'Já existe um processamento sendo executado. Por favor, aguarde.',
            icon: 'warning'
        });
        return; // Impede que outro processo seja iniciado
    }

    // Define o estado de execução no localStorage
    localStorage.setItem('batchRunning', 'true');

    try {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Processo está sendo executado no servidor!',
            showConfirmButton: false,
            timer: 2000
        });

        $('#loadingSpinner').show();

        await $.ajax({
            url: 'batch_process.php',
            method: 'POST',
            data: params
        });

    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro ao executar o processo.',
            icon: 'error'
        });
    } finally {
        checkBatchStatus(); // Verifica e atualiza o estado do batch
    }
}