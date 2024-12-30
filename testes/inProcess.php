<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Process Example</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="batch_process.js"></script>
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
    <p> parm1 <?= $_get['parm1'] ?? '????' ?></p>
    <p> parm1 <?= $_POST['valor2'] ?? '????' ?></p>

    <!-- Elemento de loading no canto superior -->
    <div id="loadingSpinner">
        <img src="https://i.gifer.com/ZZ5H.gif" alt="Carregando...">
    </div>

    <script>
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