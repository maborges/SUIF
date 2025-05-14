<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
$pagina = "carga_armazenagem";
$titulo = "Sankhya";
$modulo = "sankhya";
$menu   = "integracao_sankhya";

require_once("../Sankhya.php");

set_time_limit(0);
ini_set('memory_limit', '512M');

$erros = array(
    "startDate" => '',
    "endDate" => ''
);

$msgOk             = $_POST['msgOk'] ?? '';
$msgEr             = $_POST['msgEr'] ?? '';
$sqlWhere          = $_POST['sqlWhere'] ?? 'and 1 = 2';
$naoEnviadoSankhya = $_POST['naoEnviadoSankhya'] ?? '';
$enviadoSankhya    = $_POST['enviadoSankhya'] ?? '';

$startDate    = $_POST['startDate'] ?? date('d/m/Y');
$endDate      = $_POST['endDate'] ?? date('d/m/Y');
$numeroRomaneio = $_POST['numeroRomaneio'] ?? '';
$naoEnviados  = $_POST['naoEnviados'] ?? "";
$enviados     = $_POST['enviados'] ?? "";
$Atualizando  = false;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cria Compra no Sankhya</title>

    <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao_bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao.css" />
    <link rel="shortcut icon" href="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/favicon_suif.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/loading/loading.css" />
    <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-1.8.2.js" ?>></script>
    <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-ui.js" ?>></script>
    <script src=<?= "../../includes/loading/loading.js" ?>></script>
</head>

<body onload="loading();">
    <!-- ====== TOPO ================================================================================================== -->
    <div class="topo">
        <?php include("../../includes/topo.php"); ?>
    </div>

    <!-- ====== MENU ================================================================================================== -->
    <div class="menu">
        <?php include("../../includes/menu_sankhya.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_sankhya_integracao.php"); ?>
    </div>


    <?php
    // Informações do loadgin
    $loadTitle = 'Aguarde...';
    $loadMessage = 'Processando carga SUIF X SANKHYA.';
    require("../../includes/loading/loading.php");

    // Início do processamtno das informações
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (count($_POST) > 0) {
            $msgOk     = '';
            $msgEr     = '';

            if (isset($_POST["btnPedido"])) {
                // Clicou em atualizar dados
                GeraPedidos();
            }

            if ($numeroRomaneio) {
                if ($numeroRomaneio <= 0) {
                    $numeroRomaneio = '';
                    $erros['numeroRomaneio'] = 'Informe um número do romaneio válido';
                } else {
                    $sqlWhere = "and a.numero_romaneio = $numeroRomaneio";
                }
            } else if (!filter_input(INPUT_POST, "startDate")) {
                $erros['startDate'] = 'Informe a data inicial';
            } elseif (!filter_input(INPUT_POST, "endDate")) {
                $erros['endDate'] = 'Informe a data final';
            } else {
                $startDate = $_POST['startDate'];
                $endDate   = $_POST['endDate'];
                $sqlWhere  = "and a.data >= '$startDate' and a.data <= '$endDate'";
            }
        }
    }

    $naoEnviadoSankhya = '';
    $enviadoSankhya    = '';

    if ($naoEnviados) {
        $naoEnviadoSankhya = "and b.pedido_confirmado_sankhya <> 'S'";
    }

    if ($enviados) {
        $naoEnviadoSankhya = "and b.pedido_confirmado_sankhya = 'S'";
    }

    $sqlArmazenameto = "select b.codigo, 
                               a.data, 
                               b.codigo_romaneio, 
                               a.filial,
                               b.id_pedido_sankhya, 
                               b.fornecedor_print, 
                               a.produto, 
                               b.numero_nf, 
                               b.valor_total,
                               b.pedido_confirmado_sankhya, 
                               b.id_fatura_sankhya,
                               b.fatura_confirmada_sankhya
                          from estoque a,
                               nota_fiscal_entrada b
                         where a.estado_registro   = 'ATIVO'
                           and b.codigo_romaneio   = a.numero_romaneio  
                           and b.estado_registro   = 'ATIVO'
                           and b.natureza_operacao = 'ARMAZENAGEM'
                           and movimentacao        = 'ENTRADA'
                               $sqlWhere
                               $naoEnviadoSankhya
                               $enviadoSankhya
                        order by a.data, a.numero_romaneio";

    $resultSet  = Sankhya::queryExecuteDB($sqlArmazenameto);
    $recordCount = count($resultSet['rows']);
    $hidden     = 'hidden';

    if ($resultSet['errorCode']) {
        $erro = 1;
        $msgEr  = $resultSet['errorMessage'];
    } elseif (!$recordCount) {
        $erro = 2;
        $msgEr  = "Nenhum romaneio de armazenamento encontrado.";
    } else {
        $hidden = '';
    }
    ?>

    <!-- 
        Montagem da tela
    -->
    <div class="container-fluid pt-5 pr-5 pl-5">
        <form action="#" method="post" id="frmAtualiza" onsubmit="showLoading()">
            <h3>Cria Pedido de Armazenamento no Sankhya</h3>
            <br>
            <div class="alert alert-success" role="alert" <?= !$msgOk ? 'hidden' : '' ?>>
                <?= $msgOk ?>
            </div>

            <div class="alert alert-danger" role="alert" <?= !$msgEr ? 'hidden' : '' ?>>
                <?= $msgEr ?>
            </div>

            <div class="form-row mb-2">
                <input type="hidden" name="sqlWhere" value="<?= $sqlWhere ?>">

                <div class="form-group col-md-2">
                    <label for="startDate">Data do Romaneio Inicial</label>
                    <input type="date" class="form-control form-control-sm <?= $erros['startDate'] ? 'is-invalid' : '' ?>" id="startDate" name="startDate" placeholder="Data Inicial" value="<?= $startDate ?>">
                    <div class="invalid-feedback">
                        <?= $erros['startDate'] ?>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <label for="endDate">Data do Romaneio Final</label>
                    <input type="date" class="form-control form-control-sm <?= $erros['endDate'] ? 'is-invalid' : '' ?>" id="endDate" name="endDate" placeholder="Data Final" value="<?= $endDate ?>">
                    <div class="invalid-feedback">
                        <?= $erros['endDate'] ?>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <label for="endDate">Número do Romaneio</label>
                    <input type="number" class="form-control form-control-sm <?= $erros['numeroRomaneio'] ? 'is-invalid' : '' ?>" id="numeroRomaneio" name="numeroRomaneio" placeholder="Romaneio" value="<?= $numeroRomaneio ?>">
                    <div class="invalid-feedback">
                        <?= $erros['numeroRomaneio'] ?>
                    </div>
                </div>
            </div>

            <div class="form-row mb-2 ml-3">
                <input type="hidden" name="naoEnviadoSankhya" value="<?= $naoEnviadoSankhya ?>" />
                <input type="hidden" name="enviadoSankhya" value="<?= $enviadoSankhya ?>" />

                <div class="form-group col-md-3 align-bottom">
                    <input class="form-check-input" type="checkbox" value="S" name="naoEnviados" <?= $naoEnviados ? "checked" : "" ?>>
                    <label class="form-check-label" for="naoEnviados">
                        Somente <b>não enviados</b>
                    </label>
                </div>

                <div class="form-group col-md-3 align-bottom">
                    <input class="form-check-input" type="checkbox" value="S" name="enviados" <?= $enviados ? "checked" : "" ?>>
                    <label class="form-check-label" for="enviados">
                        Somente <b>enviados</b>
                    </label>
                </div>

                <div>
                    <span class="badge badge-success">
                        <?= $recordCount ?> entrada(s) para armazenamento
                    </span>
                </div>
            </div>

            <div class="form-row mt-3">
                <button type="submit" form="frmAtualiza" value="submit" name="btnBuscar" class="btn btn-outline-secondary btn-sm mr-3">Buscar</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnPedido" class="btn btn-outline-secondary btn-sm mr-3" <?= $hidden ?>>Gera Pedidos</button>
            </div>
            
            <table class="table table-hover table-striped table-sm mt-3" display: flex; width=900px>
                <thead>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Romaneio</th>
                    <th>Filial</th>
                    <th>Contrato</th>
                    <th>Fatura</th>
                    <th>Produtor</th>
                    <th>Produto</th>
                    <th>Nota Fiscal</th>
                    <th>Valor Total</th>
                    <th>Situação</th>
                </thead>
                <tbody>
                    <?php foreach ($resultSet['rows'] as $record) : ?>
                        <tr>
                            <td><?= $record[0] ?></td>
                            <td><?= date('d/m/Y', strtotime($record[1]))  ?></td>
                            <td><?= $record[2] ?></td>
                            <td><?= $record[3] ?></td>
                            <td><?= $record[4] ?></td>
                            <td><?= $record[10] ?></td>
                            <td><?= $record[5] ?></td>
                            <td><?= $record[6] ?></td>
                            <td><?= $record[7] ?></td>
                            <td style="text-align:right"><?= number_format($record[8], 2, ",", ".") ?></td>

                            <?php if (!$record[3]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-warning">Contrato</span>
                                </td>
                            <?php elseif ($record[4] and $record[9] == 'X') : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-danger">Verificar</span>
                                </td>
                            <?php elseif (!$record[10]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-warning">Fatura</span>
                                </td>
                            <?php elseif ($record[10] and $record[10] == 'X') : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-danger">Verificar</span>
                                </td>
                            <?php elseif ($record[11] == 'S') : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-light">Ok</span>
                                </td>
                            <?php elseif ($record[11] == 'N' && !$record[10]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-secondary">Enviar</span>
                                </td>
                            <?php elseif ($record[11] == 'N' && $record[10]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-primary">Confirmar</span>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>

                <?php if (!count($resultSet['rows'])) : ?>
                    <tfoot>
                        <th class="text-secondary text-center fs-6 m-5" scope="row" colspan="6">Nenhum registro encontrado</th>
                    </tfoot>
                <?php endif; ?>
            </table>

        </form>
    </div>

</body>

</html>

<?php

function GeraPedidos()
{
    global $msgOk, $msgEr, $sqlWhere, $naoEnviadoSankhya, $enviadoSankhya;

    $contador = 0;
    $msgOk = '';
    $msgEr = '';

    $sqlArmazenameto = "select b.codigo,
                               a.numero_romaneio
                          from estoque a
                               inner join nota_fiscal_entrada b
                                       on b.codigo_romaneio   = a.numero_romaneio  
                                      and b.estado_registro   = 'ATIVO'
                                      and b.natureza_operacao = 'ARMAZENAGEM'
                                      and b.fatura_confirmada_sankhya <> 'S'
                         where a.estado_registro   = 'ATIVO'
                           and a.movimentacao      = 'ENTRADA'
                               $sqlWhere
                               $naoEnviadoSankhya
                               $enviadoSankhya
                        order by a.data, a.numero_romaneio";

    $faturas = Sankhya::queryExecuteDB($sqlArmazenameto);

    if ($faturas['errorCode']) {
        $msgEr = "{$faturas['errorCode']}: {$faturas['errorMessage']}";
        return;
    }

    foreach ($faturas['rows'] as $fatura) {
        $notaFiscalEntrada = $fatura[0];

        // Faz a gravação do pedido no Sankhya 
        Sankhya::IncluiArmazenagem($notaFiscalEntrada);

        $contador = +1;
    }

    if ($contador) {
        $msgOk = "Foram atualizadas $contador faturas.";
    } else {
        $msgOk = "Nenhuma fatura foi processada/atualizada.";
    }
}
?>