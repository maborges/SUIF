<?php
echo "##################### Inicio dos testes<br>";
include("../includes/config.php");
include("../includes/conecta_bd.php");
include("../includes/valida_cookies.php");
require("../sankhya/Sankhya.php");
include_once("../helpers.php");


// Exemplo de uso
$sql = "SELECT CODBCO, AGENCIA, DVAGENCIA, CONTA, DVCONTA, TIPOCONTA, TIPOCHAVEPIX, CHAVEPIX, FAVORECIDO
                  FROM AD_CONTAPAG
                 WHERE CODPARC = 1113
                  AND ATIVO = 'S'
                ORDER BY SEQUENCIA";

$result = Sankhya::queryExecuteAPI($sql);
var_dump('Result 01: ', $result, '<br>');

sleep(2);
$result = Sankhya::queryExecuteAPI($sql);
var_dump('Result 02: ', $result, '<br>');

sleep(4);
$result = Sankhya::queryExecuteAPI($sql);
var_dump('Result 04: ', $result, '<br>');

sleep(6);
$result = Sankhya::queryExecuteAPI($sql);
var_dump('Result 06: ', $result, '<br>');

echo "##################### Fim dos testes<br>";
