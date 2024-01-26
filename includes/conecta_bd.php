<?php

$conexao = mysqli_connect("localhost", "suif_user", "numsey01", "suif_local", 3307);

mysqli_set_charset($conexao, "utf8");

$rovereti_api_IncluirContaPagar = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';

$rovereti_api_IncluirPrePagamentoTransferencia = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/IncluirPrePagamentoTransferencia';

$rovereti_api_CancelarContaPagar = 'http://apihomolog.rovereti.com.br/Api/ContaPagar/CancelarContaPagar';

$key_rovereti = '25482';
$usuario_rovereti = "INTEGRADOR.GRANCAFE";


/*
$conexao = mysqli_connect("172.17.16.18", "suif_user", "asRf455TtykgQ7X", "suif_grancafe");

mysqli_set_charset($conexao, "utf8");

$rovereti_api_IncluirContaPagar = 'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirContaPagar';

$rovereti_api_IncluirPrePagamentoTransferencia = 'http://appservice.rovereti.com.br/Api/ContaPagar/IncluirPrePagamentoTransferencia';

$rovereti_api_CancelarContaPagar = 'http://appservice.rovereti.com.br/Api/ContaPagar/CancelarContaPagar';

$key_rovereti = '25482';
$usuario_rovereti = "INTEGRADOR.GRANCAFE";
*/

?>

