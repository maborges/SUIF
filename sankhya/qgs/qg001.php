<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");

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
$tipoDocumento     = $_POST['tipoDocumento'] ?? 1;
$situacaoProcesso  = $_POST['situacaoProcesso'] ?? '';
$geraContrato      = $_POST['geraContrato'] ?? '';

$startDate    = $_POST['startDate'] ?? date('d/m/Y');
$endDate      = $_POST['endDate'] ?? date('d/m/Y');
$numeroCompra = $_POST['numeroCompra'] ?? '';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gera Compra no Sankhya</title>
    <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="shortcut icon" href="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/favicon_suif.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/loading/loading.css" />
    <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-1.8.2.js" ?>></script>
    <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-ui.js" ?>></script>
    <script src=<?= "../../includes/loading/loading.js" ?>></script>
</head>

<body onload="loading();">
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

            if (isset($_POST["btnContrato"])) {
                // Clicou em atualizar dados
                GeraContratos();
            }

            if (isset($_POST["btnPedido"])) {
                // Clicou em atualizar dados
                GeraPedidos();
            }

            if (isset($_POST["btnExcluir"])) {
                // Clicou em exclusão de pendentes
                ExcluiPendentes($tipoDocumento, $sqlWhere );
            }

            if ($numeroCompra) {
                if ($numeroCompra <= 0) {
                    $numeroCompra = '';
                    $erros['numeroCompra'] = 'Informe um número de compra válido';
                } else {
                    if ($tipoDocumento == 1) {
                        $sqlWhere = "and a.numero_compra = $numeroCompra";
                    } else {
                        $sqlWhere = "and a.codigo_compra = $numeroCompra";
                    }
                }
            } else if (!filter_input(INPUT_POST, "startDate")) {
                $erros['startDate'] = 'Informe a data inicial';
            } elseif (!filter_input(INPUT_POST, "endDate")) {
                $erros['endDate'] = 'Informe a data final';
            } else {
                $startDate = $_POST['startDate'];
                $endDate   = $_POST['endDate'];

                if ($tipoDocumento == 1) {
                    $sqlWhere = "and a.data_compra >= '$startDate' and a.data_compra <= '$endDate'";
                } else {
                    $sqlWhere = "and a.data_cadastro >= '$startDate' and a.data_cadastro <= '$endDate'";
                }
            }
        }
    }

    $situacaoSankhya = '';

    if ($situacaoProcesso == 1) {
        $situacaoSankhya = "and a.pedido_confirmado_sankhya = 'S'";
    } else if ($situacaoProcesso == 2) {
        $situacaoSankhya = "and a.pedido_confirmado_sankhya = 'N'";
    } else if ($situacaoProcesso == 3) {
        $situacaoSankhya = "and a.pedido_confirmado_sankhya = 'X'";
    }

    if ($tipoDocumento == 1) {
        $sqlCompra = "select a.codigo, a.data_compra, a.id_pedido_sankhya, a.numero_compra, a.fornecedor_print, a.produto, 
                             a.valor_total, a.pedido_confirmado_sankhya, a.log_sankhya, a.id_pedido_sankhya
                        from compras a
                       where a.movimentacao = 'COMPRA' 
                         and a.estado_registro = 'ATIVO'
                             $sqlWhere
                             $situacaoSankhya
                     order by a.data_compra, a.numero_compra";
    } else {
        $sqlCompra = "select a.codigo, a.data_cadastro, a.id_pedido_sankhya, a.codigo_compra, a.favorecido_print, a.produto, 
                             a.valor, a.pedido_confirmado_sankhya, a.log_sankhya, b.id_pedido_sankhya
                        from favorecidos_pgto a
                             inner join compras b
                                on b.numero_compra   = a.codigo_compra
                               and b.movimentacao    = 'COMPRA'
                               and b.estado_registro = 'ATIVO'
                       where a.estado_registro = 'ATIVO'
                         and a.codigo_compra > 0
                             $sqlWhere
                             $situacaoSankhya
                      order by a.data_cadastro, a.codigo_compra";
    }

    $resultSet  = Sankhya::queryExecuteDB($sqlCompra);
    $recordCount = count($resultSet['rows']);
    $hidden         = 'hidden';
    $hiddenContrato = 'hidden';
    $hiddenFatura   = 'hidden';

    if ($resultSet['errorCode']) {
        $erro = 1;
        $msgEr  = $resultSet['errorMessage'];
    } elseif (!$recordCount) {
        $erro = 2;
        $msgEr  = "Nenhuma compra encontrada.";
    } else if ($tipoDocumento == 1) {
        $hiddenContrato = '';
    } else if ($tipoDocumento == 2) {
        $hiddenFatura = '';
    }
    ?>

    <!-- 
        Montagem da tela
    -->
    <div class="container pt-3">

        <form action="#" method="post" id="frmAtualiza" onsubmit="showLoading()">
            <h3>Gera Contrato/Pedido no Sankhya</h3>
            <br>
            <div class="alert alert-success" role="alert" <?= !$msgOk ? 'hidden' : '' ?>>
                <?= $msgOk ?>
            </div>
            <!--  Verificar se ainda énecessário apresentar os erros na tela
            <div class="alert alert-danger" role="alert" <?= !$msgEr ? 'hidden' : '' ?>>
                <?= $msgEr ?>
            </div>
            -->
            <div class="form-row">
                <input type="hidden" name="sqlWhere" value="<?= $sqlWhere ?>">

                <div class="form-group col-md-2">
                    <label for="startDate">Tipo de Documento?</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipoDocumento" id="checkContrato" value=1 <?= $tipoDocumento == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="checkContrato">Contrato</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipoDocumento" id="checkFatura" value=2 <?= $tipoDocumento == 2 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="checkFatura">Fatura</label>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <label for="startDate">Data Inicial da Compra</label>
                    <input type="date" class="form-control form-control-sm <?= $erros['startDate'] ? 'is-invalid' : '' ?>" id="startDate" name="startDate" placeholder="Data Inicial" value="<?= $startDate ?>">
                    <div class="invalid-feedback">
                        <?= $erros['startDate'] ?>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <label for="endDate">Data Final da Compra</label>
                    <input type="date" class="form-control form-control-sm <?= $erros['endDate'] ? 'is-invalid' : '' ?>" id="endDate" name="endDate" placeholder="Data Final" value="<?= $endDate ?>">
                    <div class="invalid-feedback">
                        <?= $erros['endDate'] ?>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <label for="endDate">Número da Compra</label>
                    <input type="number" class="form-control form-control-sm <?= $erros['numeroCompra'] ? 'is-invalid' : '' ?>" id="numeroCompra" name="numeroCompra" placeholder="Compra" value="<?= $numeroCompra ?>">
                    <div class="invalid-feedback">
                        <?= $erros['numeroCompra'] ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-check form-check-inline ml-1">
                    <input class="form-check-input" type="radio" name="situacaoProcesso" id="checkEnviado" value=1 <?= $situacaoProcesso == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkEnviado">Enviado</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="situacaoProcesso" id="checkNaoEnviado" value=2 <?= $situacaoProcesso == 2 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkNaoEnviado">Não Enviado</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="situacaoProcesso" id="checkVerificar" value=3 <?= $situacaoProcesso == 3 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkVerificar">Verificar</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="situacaoProcesso" id="checkTodos" value=0 <?= $situacaoProcesso == 0 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkVerificar">Todos</label>
                </div>

                <div class="form-check form-check-inline ml-5">
                    <span class="badge badge-success">
                        <?= $recordCount ?> registro(s)
                    </span>
                </div>

                <div class="form-check form-check-inline ml-5" <?= $tipoDocumento == 1 ? 'hidden' : '' ?>>
                    <input class="form-check-input" type="checkbox" name="geraContrato" id="geraContrato" value=1  <?= $geraContrato == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="checkVerificar">Gerar contrato se pendente</label>
                </div>

            </div>

            <div class="form-row mt-3 d-flex bd-highligh">
                <button type="submit" form="frmAtualiza" value="submit" name="btnBuscar" class="btn btn-primary btn-sm mr-3 bd-highlight">Buscar</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnContrato" class="btn btn-primary btn-sm mr-3 bd-highlight" <?= $hiddenContrato ?>>Gera Contratos</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnPedido" class="btn btn-primary btn-sm mr-3 bd-highlight" <?= $hiddenFatura ?>>Gera Pedidos</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnExcluir" class="btn btn-danger btn-sm ml-3 ml-auto bd-highlight">Efetiva Exclusões Pendentes</button>
            </div>
            <br>
            <table class="table table-hover table-striped table-sm" style="display: flex-row">
                <thead>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Sankhya</th>
                    <th>Compra</th>
                    <th>Produtor/Favorecido</th>
                    <th>Produto</th>
                    <th>Valor</th>
                    <th>Situação</th>
                </thead>
                <tbody>
                    <?php foreach ($resultSet['rows'] as $record) : ?>
                        <tr>
                            <td><?= $record[0] ?></td>
                            <td><?= date('d/m/Y', strtotime($record[1])) ?></td>
                            <td><?= $record[2] ?></td>
                            <td><?= $record[3] ?></td>
                            <td><?= $record[4] ?></td>
                            <td style="width:20%"><?= $record[5] ?></td>

                            <td style="text-align:right"><?= number_format($record[6], 2, ",", ".") ?></td>

                            <?php if ($tipoDocumento == 2 and !$record[9]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-warning">Contrato</span>
                                </td>
                            <?php elseif ($record[7] == 'X') : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-danger">Verificar</span>
                                </td>
                            <?php elseif ($record[7] == 'S') : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-light">Ok</span>
                                </td>
                            <?php elseif ($record[7] == 'N' && !$record[2]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-secondary">Enviar</span>
                                </td>
                            <?php elseif ($record[7] == 'N' && $record[2]) : ?>
                                <td style="text-align:center">
                                    <span class="badge badge-primary">Confirmar</span>
                                </td>
                            <?php endif; ?>
                        </tr>

                        <?php if ($record[8]) : ?>
                            <td></td>
                            <td class="text-danger" colspan="7"><?= $record[8] ?></td>
                        <?php endif; ?>

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
function GeraContratos($contrato = '')
{
    global $msgOk, $msgEr, $sqlWhere, $situacaoSankhya, $resultSet;

    $contador = 0;
    $msgOk = '';
    $msgEr = '';
    $msgAu = '';
    $sqlContrato = '';


    if ($contrato) {
        $sqlCompras = "select a.numero_compra, a.fornecedor, a.id_pedido_sankhya, a.pedido_confirmado_sankhya
                        from compras a 
                        where a.movimentacao    = 'COMPRA'
                        and a.estado_registro = 'ATIVO'
                        and a.pedido_confirmado_sankhya <> 'S'
                        and a.numero_compra = $contrato
                    order by a.data_compra, a.numero_compra";
    } else {
        $sqlCompras = "select a.numero_compra, a.fornecedor, a.id_pedido_sankhya, a.pedido_confirmado_sankhya
                        from compras a 
                        where a.movimentacao    = 'COMPRA'
                        and a.estado_registro = 'ATIVO'
                        and a.pedido_confirmado_sankhya <> 'S'
                            $sqlWhere
                            $situacaoSankhya
                            $sqlContrato
                    order by a.data_compra, a.numero_compra";
    }             

    $resultSet = Sankhya::queryExecuteDB($sqlCompras);
    $recordCount = count($resultSet['rows']);
    $idCompraAu = '';

    foreach ($resultSet['rows'] as $compra) {
        $error             = false;
        $numeroCompra      = $compra[0];
        $idProdutor        = $compra[1];
        $pedidoSankhya     = $compra[2] ?? '';
        $pedidoConfirmado  = $compra[3];

        $produtorSankhya   = 0;
        $codigoPessoa      = 0;
        $favorecidoSankhya = 0;

        // Busca código Sankhya do Produtor
        $sql = "select id_sankhya, 
                       codigo_pessoa
                  from cadastro_pessoa 
                 where codigo = $idProdutor";

        $resultSet = Sankhya::queryExecuteDB($sql);

        if ($resultSet['errorCode']) {
            $error  = true;
            $msgAu  .= "{$resultSet['errorCode']}: {$resultSet['errorMessage']} <br>";
        } else {
            $rowsCount = Count($resultSet['rows']);
            if ($rowsCount == 0) {
                $error = true;
                $msgAu .= "Produtor $idProdutor não cadastrado no SUIF. <br>";
            } else if ($rowsCount > 1) {
                $error = true;
                $msgAu .= "Existe mais de uma produtor cadastrado com o código $idProdutor. <br>";
            } else {
                $produtorSankhya = $resultSet['rows'][0][0];
                $codigoPessoa    = $resultSet['rows'][0][1];
            }
        }

        if (!$pedidoSankhya) {
            // Status intermediário informado que Sankhya esta sendo gerado
            $sql = "update compras 
                       set pedido_confirmado_sankhya = 'X',
                           log_sankhya = null
                     where numero_compra = $numeroCompra";

            $resultSet = Sankhya::queryExecuteDB($sql);

            // Faz a gravação do pedido no Sankhya 
            $resultSet = Sankhya::insertPedidoCompra($numeroCompra);

            if ($resultSet['errorCode']) {
                $error = true;
                $msgAu .= "{$resultSet['errorMessage']} <br>";
            } else {
                $pedidoSankhya = $resultSet['rows']['pk']['NUNOTA']['$'];

                // Atualiza o número do pedido Sankhya
                if ($pedidoSankhya) {
                    $sql = "update compras 
                               set id_pedido_sankhya         = $pedidoSankhya,
                                   pedido_confirmado_sankhya = 'S',
                                   log_sankhya = null
                             where numero_compra = $numeroCompra";

                    $resultSet = Sankhya::queryExecuteDB($sql);

                    if ($resultSet['errorCode']) {
                        $error = true;
                        $msgAu .= "Erro ao atualizar contrato $numeroCompra no SUIF como o código Sankhya $pedidoSankhya <br>";
                        $msgAu .= "Verifique se o contrato foi gerado corretamente no Sankhya e no SUIF <br>";
                        $msgAu .= "{$resultSet['errorMessage']} <br>";
                    }
                }
            }
        }

        // Faz a confirmação do pedido 
        if (!$error && $pedidoConfirmado == 'N') {
            $resultSet = Sankhya::confirmaPedidoCompra($pedidoSankhya);
            $pedidoConfirmado = 'S';

            if ($resultSet['errorCode']) {
                if (!strpos(strtolower($resultSet['errorMessage']), 'confirmada')) {
                    $msgAu .= "{$resultSet['errorMessage']} <br>";
                    $pedidoConfirmado = 'N';
                } else {
                    $contador += 1;
                }
            }
        }

        // Atualiza o número do pedido Sankhya e confirmação na compra do SUIF
        if ($pedidoSankhya) {
            $sql = "update compras 
                       set pedido_confirmado_sankhya = '$pedidoConfirmado',
                           log_sankhya = null
                      where numero_compra = $numeroCompra";

            $resultSet = Sankhya::queryExecuteDB($sql);

            if ($resultSet['errorCode']) {
                $error = true;
                $msgAu .= "{$resultSet['errorMessage']} <br>";
            }
        }

        if ($error) {
            $sql = "update compras 
                       set log_sankhya = '" . str_replace("<br>", " ", $msgAu) . "' where numero_compra = $numeroCompra";

            $resultSet = Sankhya::queryExecuteDB($sql);

            if ($idCompraAu <> $numeroCompra) {
                $idCompraAu = $numeroCompra;
                $msgEr .= "<b>Compra $numeroCompra </b><br>";
            }
            $msgEr .= $msgAu;
        }
    }

    if ($contador) {
        $msgOk = "Foram atualizadas $contador compras. <br>";
    } else {
        $msgOk = "Nenhuma compra foi processada/atualizada. <br>";
    }
}

function GeraPedidos()
{
    global $msgOk, $msgEr, $sqlWhere, $situacaoSankhya, $geraContrato;

    $contador = 0;
    $msgOk = '';
    $msgEr = '';

    $sql =  "select a.codigo_compra idCompra,
                    b.fornecedor idProdutor,
                    c.id_sankhya idProdutorSankhya,
                    a.codigo idPagamentoFavorecido,
                    a.codigo_favorecido idFavorecido,
                    f.id_sankhya idFavorecidoSankya,
                    b.id_pedido_sankhya idContratoSankhya,
                    a.id_pedido_sankhya idFaturaSankhya,
                    a.pedido_confirmado_sankhya faturaConfirmada,
                    ROUND(a.valor / b.preco_unitario, 2) qtdeFaturada,
                    a.data_pagamento dataPagamento,
                    a.data_cadastro dataCadastro,
                    a.codigo_pessoa idPessoaFatura,
                    a.valor valorFaturado,
                    g.id_sankhya,
                    b.unidade,
                    b.preco_unitario,
                    a.observacao,
                    a.nf_adto,
                    d.sequencia_cc_sankhya,
                    b.pedido_confirmado_sankhya
                from favorecidos_pgto a
                        inner join compras b 
                            on b.numero_compra   = a.codigo_compra
                           and b.movimentacao    = 'COMPRA'
                           and b.estado_registro = 'ATIVO'
                        inner join cadastro_pessoa c 
                           on c.codigo = b.fornecedor
                        inner join cadastro_favorecido d 
                           on d.codigo = a.codigo_favorecido
                        inner join cadastro_produto e 
                           on e.codigo = a.cod_produto
                        inner join cadastro_pessoa f 
                           on f.codigo_pessoa = a.codigo_pessoa
                        inner join cadastro_produto g 
                           on g.codigo = b.cod_produto
                where a.estado_registro = 'ATIVO'
                  and a.pedido_confirmado_sankhya <> 'S'
                    $sqlWhere
                    $situacaoSankhya
             order by b.numero_compra, b.data_cadastro";
    $faturas = Sankhya::queryExecuteDB($sql);

    if ($faturas['errorCode']) {
        $error = true;
        $msgEr .= "{$faturas['errorCode']}: {$faturas['errorMessage']} <br>";
    }

    $recordCount = count($faturas['rows']);

    $error      = false;
    $idCompraAu = '';

    foreach ($faturas['rows'] as $fatura) {
        $idCompra              = $fatura[0];

        $idProdutorSankhya     = $fatura[2];
        $idPagamentoFavorecido = $fatura[3];
        $idFavorecidoSankya    = $fatura[5];
        $idContratoSankhya     = $fatura[6];
        $idFaturaSankhya       = $fatura[7];
        $faturaConfirmada      = $fatura[8];
        $qtdeFaturada          = $fatura[9];
        $dataFaturamento       = date('d/m/Y', strtotime($fatura[10]));
        $dataCadastro          = date('d/m/Y', strtotime($fatura[11]));
        $idPessoaFatura        = $fatura[12];
        $valorFaturado         = $fatura[13];
        $idProdutoSankhya      = $fatura[14];
        $unidadeProduto        = $fatura[15];
        $precoUnitario         = $fatura[16];
        $observacao            = $fatura[17] ?? '';
        $referenciaPagto       = $fatura[18] ?? '';
        $idCCSankhya           = $fatura[19];
        $contratoConfirmado    = $fatura[20];

        // Double checking :)
        if ($faturaConfirmada == 'S') {
            continue;
        }

        if (!$idContratoSankhya) {
            if (!$geraContrato) {
                Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                        "Contrato não gerado ainda");
                continue;
            }

            GeraContratos($idCompra);

        } elseif ($idContratoSankhya and $contratoConfirmado <> 'S') {
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                      "Contrato gerado, mas ainda não confirmado");
            continue;
        }

        if (!$referenciaPagto) {
            $observacao = "(N/I)";
        } else if ($referenciaPagto == 'NF') {
            $observacao = "Nota Fiscal";
        } else if ($referenciaPagto == 'ADTO') {
            $observacao = "Adiantamento";
        }

        if ($observacao) {
            $observacao = "$referenciaPagto - $observacao";
        } else {
            $observacao = "$referenciaPagto";
        }

        $error = false;
        $situacaoFatura = $faturaConfirmada;

        // Verifica se favorecido foi informado
        if (!$idFavorecidoSankya) {
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                      "Código Sankhya do favorecido '$idPessoaFatura' não informado");
            continue;
        } else if (!$idCCSankhya) {
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                      "Código da Conta Corrente Sankhya do favorecido $idPessoaFatura não informado");
            continue;
        }

        // Gera fatura no Sankhya caso ainda não tenha sido gerada
        if (!$idFaturaSankhya) {
            // Informa ao processamento que irá gerar a fatura
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, 'X', null);

            // Cria a fatura
            $resultSet = Sankhya::faturaPedidoCompra($idCompra, $qtdeFaturada, $dataCadastro, $valorFaturado);

            $idFaturaSankhya  = $resultSet['rows']['notas']['nota']['$'] ?? null;

            if ($resultSet['errorCode']) {
                // Trata caso tenha dado erro e a fatura tenha sido criada
                $statusProcesso = $idFaturaSankhya ? 'X' : $faturaConfirmada;
                Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $statusProcesso, $resultSet['errorMessage']);
                continue;
            } 
        }

        $resultItem = Sankhya::alteraItemNota(
            $idFaturaSankhya,
            $idProdutoSankhya,
            $valorFaturado,
            $qtdeFaturada,
            $unidadeProduto,
            $precoUnitario,
            $observacao
        );

        if ($resultItem['errorCode']) {
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                        $resultItem['errorMessage']);
            continue;
        }

        // Atualiza favorecido e confirma faturamento
        $resultSet = Sankhya::alteraCabecalhoNota($idFaturaSankhya, $idProdutorSankhya, $idFavorecidoSankya, $dataFaturamento, $idCCSankhya);

        if ($resultSet['errorCode']) {
            Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $faturaConfirmada, 
                                                      'Erro ao tentar alterar o cabeçalho da nota');
            continue;
        } 

        // Confirma faturamento
        $resultSet = Sankhya::confirmaPedidoCompra($idFaturaSankhya);
        $contador++;
        $situacaoFatura = 'S';
        $msgConfirma = null;

        if ($resultSet['errorCode']) {
            if (!strpos(strtolower($resultSet['errorMessage']),'confirmada')) {
                $msgConfirma = $resultSet['errorMessage'];
                $situacaoFatura = 'X';
            }
        }

        Sankhya::atualizaDadosPagamentoFavorecido($idPagamentoFavorecido, $idFaturaSankhya, $situacaoFatura, $msgConfirma);
    }

    if ($contador) {
        $msgOk = "Foram atualizadas $contador faturas.";
    } else {
        $msgOk = "Nenhuma fatura foi processada/atualizada.";
    }
}

function ExcluiPendentes($tipoDocumento = 2, $filtro = '')
{
    global $sqlWhere, $msgOk;

    $contador = 0;
    $msgOk    = '';
    $sqlExcludir = '';

    if ($tipoDocumento == 1) {
        $sqlExcludir = "select a.codigo, a.id_pedido_sankhya, a.pedido_confirmado_sankhya, 
                               a.numero_compra
                          from compras a 
                         where a.movimentacao    = 'COMPRA'
                           and a.estado_registro = 'EXCLUIDO'
                           and a.id_pedido_sankhya is not null
                             $filtro
                        order by a.data_compra, a.numero_compra";
    } elseif ($tipoDocumento == 2) {
        $sqlExcludir = "select a.codigo, a.id_pedido_sankhya, a.pedido_confirmado_sankhya, 
                               a.codigo_compra 
                          from favorecidos_pgto a
                         where a.estado_registro = 'EXCLUIDO'
                           and a.id_pedido_sankhya is not null
                             $filtro
                        order by a.data_cadastro, a.codigo_compra";
    }


    $pedidos = Sankhya::queryExecuteDB($sqlExcludir);

    foreach ($pedidos['rows'] as $pedido) {
        $idCompra         = $pedido[0];
        $idPedidoSankhya  = $pedido[1];
        $faturaConfirmada = $pedido[2];

        // Cancela pedido de faturamento no Sankhya
		$resultCancela = Sankhya::cancelaDocumento($idPedidoSankhya);

        if ($resultCancela['errorCode']) {
            if ($tipoDocumento == 1) {
                Sankhya::atualizaDadosCompra($idCompra, $idPedidoSankhya, $faturaConfirmada, 
                                             $resultCancela['errorMessage']);
             } else {
                Sankhya::atualizaDadosPagamentoFavorecido($idCompra, $idPedidoSankhya, $faturaConfirmada, 
                                                          $resultCancela['errorMessage']);
            }
            continue;
        } 

        Sankhya::atualizaDadosPagamentoFavorecido($idCompra, $idPedidoSankhya, $faturaConfirmada, 
                                                 'Excluido no Sankhya');

        $contador += 1;
    }

    if ($contador) {
        $msgOk = "Foram atualizadas $contador compras. <br>";
    } else {
        $msgOk = "Nenhuma compra foi processada/atualizada. <br>";
    }

}


?>