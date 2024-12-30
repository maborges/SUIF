<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Div Centralizada</title>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f8ff;">

<!-- Div centralizada -->
<div style='width: 300px; background-color: #add8e6; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); padding: 20px;'>
    <h2 style="margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #ccc;">Mensagem de Erro</h2>
    
</div>

<script>
// Função para fechar a mensagem e encerrar a execução
function fecharMensagem() {
    document.body.innerHTML = '';
    document.write('<h1 style="text-align: center; margin-top: 50px;">A mensagem foi fechada.</h1>');
    // Aqui você pode adicionar qualquer outra ação desejada antes de encerrar a execução
    // Exemplo: window.close();
}
</script>

<div class="container" style="display: flex">
    <h4>Teste de navegação</h4>

    <button onclick="window.history.back();" class="btn btn-primary btn-lg" name="btnVolta"> Volta</button>

</div> -->