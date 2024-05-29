<?php
// MUDAR ESTA VARIÁVEL CONFORME O AMBIENTE
$dbc_environment = 'HOM';
$db_environment = $GLOBALS['$db_environment'] ?? '';

$dbc_host = '';
$dbc_user = 'suif_user';
$dbc_pass = '';
$dbc_base = '';
$dbc_port = '3306';

$rovereti_api_IncluirContaPagar = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';
$rovereti_api_IncluirPrePagamentoTransferencia = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/IncluirPrePagamentoTransferencia';
$rovereti_api_CancelarContaPagar = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/CancelarContaPagar';
$key_rovereti = '25482';
$usuario_rovereti = "INTEGRADOR.GRANCAFE";

switch ($dbc_environment) {
    case 'PRO':
        $dbc_host = "172.17.16.18";
        $dbc_pass = "asRf455TtykgQ7X";
        $dbc_base = "suif_grancafe";

        // Dados de acesso ao Rovereti
        $rovereti_api_IncluirContaPagar = 'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';
        $rovereti_api_IncluirPrePagamentoTransferencia = 'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirPrePagamentoTransferencia';
        $rovereti_api_CancelarContaPagar = 'http://appservice.rovereti.com.br/Api/ContaPagar/CancelarContaPagar';
        break;

    case 'HOM':
        $dbc_host = "172.17.16.19";
        $dbc_pass = "asRf455TtykgQ7X";
        $dbc_base = "suif_grancafe";
        break;

    default:
        $dbc_host = "localhost";
        $dbc_pass = "numsey01";
        $dbc_base = "suif_local";
        $dbc_port = '3307';
        break;
}

// Faz conexão com o banCo de dados 
if (!function_exists('ConnectDB')) {
    function ConnectDB()
    {
        global $dbc_host, $dbc_user, $dbc_pass, $dbc_base, $dbc_port, $dbc_environment;

        try {
            //            $conexao = new mysqli($dbc_host, $dbc_user, $dbc_pass, $dbc_base, $dbc_port);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conexao = mysqli_connect($dbc_host, $dbc_user, $dbc_pass, $dbc_base, $dbc_port);
            mysqli_set_charset($conexao, "utf8");

        } catch (Exception $e) {
            echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh'>
                <div style='width: 700px; color: white; background-color: #ff0000; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); padding: 20px;'>
                <p><b>SUIF</b> - ambiente $dbc_environment<p>
                 <h3>Erro ao tentar conexão com o banco de dados.</h3>
                 <h4>Informe a mensagem abaixo para o administrador:</h4>
                 <p><b>Exceção capturada: $conexao->connect_error </b></p>
                 </div>
                 </div>";
        }
        return $conexao;
    }
}

// Desconecta banco de dados
if (!function_exists('DisconnectDB')) {
    function DisconnectDB($connection)
    {
        mysqli_close($connection);
    }
}

$conexao = ConnectDB();

if ($db_environment == '') {

    $configRecord = mysqli_query($conexao, "select ambiente from configuracoes where codigo = 1");

    if (!$configRecord) {
        echo "
        <div style='display: flex; justify-content: center; align-items: center; height: 100vh'>
            <div style='width: 700px; color: white; background-color: #ff0000; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); padding: 10px;'>
                <p><b>SUIF</b> - ambiente $dbc_environment<p>
                <h3>Erro ao tentar acesar a tabela de conficurações.</h3>
                <h4>Informe a mensagem abaixo para o administrador:</h4>
                <p><b>Exceção capturada: $conexao->connect_error </b></p>
            </div>
         </div>";

        die();
    }

    $db_environment = mysqli_fetch_row($configRecord)[0];
}

if ($db_environment <> $dbc_environment) {
    echo "
    <div style='display: flex; justify-content: center; align-items: center; height: 100vh'>
        <div style='color: white; background-color: #ff0000; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); padding: 10px;'>
            <p><b>SUIF</b> - ambiente $dbc_environment<p>
            <h3>O banco de dados que está sendo acessado ($db_environment) é diferente do ambiente setado ($dbc_environment).</h3>
            <h4>Verifique!!</h4>
        </div>
     </div>";

    die();
}

