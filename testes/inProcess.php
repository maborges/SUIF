<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Process Example</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Spinner loading no canto superior */
        #loadingSpinner {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            display: none;
        }

        #loadingSpinner img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>

    <button id="startBatch">Executar Batch</button>

    <!-- Elemento de loading no canto superior -->
    <div id="loadingSpinner">
        <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando...">
    </div>

    <script>
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
                    text: 'O processo anterior ainda está em execução. Por favor, aguarde.',
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

        // Verifica o estado do batch ao carregar a página
        $(document).ready(function() {
            // Se um batch estiver em execução, mostra o loading
            if (localStorage.getItem('batchRunning') === 'true') {
                $('#loadingSpinner').show();
                // Continuamente verifica o status do batch
                setInterval(checkBatchStatus, 5000); // Checa a cada 5 segundos
            }

            // Evento click no botão para iniciar o batch
            $('#startBatch').click(function() {
                const params = {
                    param1: 'valor1',
                    param2: 'valor2'
                };
                runBatchProcess(params);
            });
        });
    </script>

</body>

</html>