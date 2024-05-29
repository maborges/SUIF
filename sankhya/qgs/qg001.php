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
$naoEnviadoSankhya = $_POST['naoEnviadoSankhya'] ?? '';
$enviadoSankhya    = $_POST['enviadoSankhya'] ?? '';

$startDate    = $_POST['startDate'] ?? date('d/m/Y');
$endDate      = $_POST['endDate'] ?? date('d/m/Y');
$numeroCompra = $_POST['numeroCompra'] ?? '';
$naoEnviados  = $_POST['naoEnviados'] ?? "";
$enviados     = $_POST['enviados'] ?? "";
$Atualizando  = false;

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

        if ($numeroCompra) {
            if ($numeroCompra <= 0) {
                $numeroCompra = '';
                $erros['numeroCompra'] = 'Informe um número de compra válido';
            } else {
                $sqlWhere = "and a.numero_compra = $numeroCompra";
            }
        } else if (!filter_input(INPUT_POST, "startDate")) {
            $erros['startDate'] = 'Informe a data inicial';
        } elseif (!filter_input(INPUT_POST, "endDate")) {
            $erros['endDate'] = 'Informe a data final';
        } else {
            $startDate = $_POST['startDate'];
            $endDate   = $_POST['endDate'];
            $sqlWhere  = "and a.data_compra >= '$startDate' and data_compra <= '$endDate'";
        }
    }
}

$naoEnviadoSankhya = '';
$enviadoSankhya    = '';

if ($naoEnviados) {
    $naoEnviadoSankhya = "and a.pedido_confirmado_sankhya <> 'S'";
}

if ($enviados) {
    $naoEnviadoSankhya = "and a.pedido_confirmado_sankhya = 'S'";
}

$sqlCompra = "select a.codigo, a.data_compra, a.id_pedido_sankhya, a.numero_compra, a.fornecedor_print, a.produto 
                from compras a
               where a.movimentacao = 'COMPRA' 
                 and a.estado_registro = 'ATIVO'
                     $sqlWhere
                     $naoEnviadoSankhya
                     $enviadoSankhya
               order by a.data_compra, a.numero_compra";

$resultSet  = Sankhya::queryExecuteDB($sqlCompra);
$recorCount = count($resultSet['rows']);
$hidden     = 'hidden';

if ($resultSet['errorCode']) {
    $erro = 1;
    $msgEr  = $resultSet['errorMessage'];
} elseif (!$recorCount) {
    $erro = 2;
    $msgEr  = "Nenhuma compra encontrada.";
} else {
    $hidden = '';
}

function GeraContratos()
{
    global $msgOk, $msgEr, $sqlWhere, $naoEnviadoSankhya, $enviadoSankhya, $resultSet;

    $contador = 0;
    $msgOk = '';
    $msgEr = '';
    $msgAu = '';

    $sqlCompras = "select a.numero_compra, a.fornecedor, a.id_pedido_sankhya, a.pedido_confirmado_sankhya
                     from compras a 
                     where a.movimentacao    = 'COMPRA'
                       and a.estado_registro = 'ATIVO'
                           $sqlWhere
                           $naoEnviadoSankhya
                           $enviadoSankhya
                     order by a.data_compra, a.numero_compra";
    //  and a.id_pedido_sankhya is null


    $resultSet = Sankhya::queryExecuteDB($sqlCompras);
    $recorCount = count($resultSet['rows']);
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

        // Busca código Sankhya do favorecido do produtor
        $sql = "select distinct id_sankhya
                  from cadastro_favorecido 
                 where codigo_pessoa = $codigoPessoa";

        $resultSet = Sankhya::queryExecuteDB($sql);

        if ($resultSet['errorCode']) {
            $error = true;
            $msgAu .= "{$resultSet['errorCode']}: {$resultSet['errorMessage']} <br>";
        } else {
            $rowsCount = Count($resultSet['rows']);
            if ($rowsCount == 0) {   // Asume produtor Sankhya como favorecido Sankhya
                $favorecidoSankhya = $produtorSankhya;
            } else if ($rowsCount > 1) {
                $error = true;
                $msgAu .= "Existe mais de um código de favorecido Sanklya cadastrado para o produdor $codigoPessoa. <br>";
            } else {
                $favorecidoSankhya = $resultSet['rows'][0][0];
            }
        }

        if ($pedidoConfirmado == 'X') {
            $error = true;
            $msgAu .= "Fatura pendente de verificação <br>";
            $msgAu .= "Verifique se o contrato foi gerado corretamente no Sankhya e no SUIF <br>";

            // Só gera o pedido caso ainda não tenha sido gerado    
        } else if (!$pedidoSankhya) {
            // Status intermediário informado que Sankhya esta sendo gerado
            $sql = "update compras 
                       set pedido_confirmado_sankhya = 'X'
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
                                   pedido_confirmado_sankhya = 'S'
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
                if ($resultSet['errorMessage'] != 'Nota(s) já confirmada(s)') {
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
                    set pedido_confirmado_sankhya = '$pedidoConfirmado'
                    where numero_compra = $numeroCompra";

            $resultSet = Sankhya::queryExecuteDB($sql);

            if ($resultSet['errorCode']) {
                $error = true;
                $msgAu .= "{$resultSet['errorMessage']} <br>";
            }
        }

        if ($error) {
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
    global $msgOk, $msgEr, $sqlWhere, $naoEnviadoSankhya, $enviadoSankhya;


    $contador = 0;
    $msgOk = '';
    $msgEr = '';
    $msgAu = '';

    $sql =  "select a.numero_compra idCompra, 
                    a.fornecedor idProdutor, 
                    c.id_sankhya idProdutorSankhya,
                    b.codigo idPagamentoFavorecido,
                    b.codigo_favorecido idFavorecido,
                    f.id_sankhya idFavorecidoSankya,
                    a.id_pedido_sankhya idContratoSankhya,
                    b.id_pedido_sankhya idFaturaSankhya,
                    b.pedido_confirmado_sankhya faturaConfirmada,
                    TRUNCATE(ROUND(b.valor / a.preco_unitario,4),3) qtdeFaturada,
                    b.data_pagamento dataPagamento,
                    b.data_cadastro dataCadastro,
                    b.codigo_pessoa idPessoaFatura,
                    b.valor valorFaturado,
                    g.id_sankhya,
                    a.unidade,
                    a.preco_unitario
               from compras a
                    inner join favorecidos_pgto b
                            on b.codigo_compra   = a.numero_compra 
                           and b.estado_registro = 'ATIVO'
                           and b.pedido_confirmado_sankhya = 'N'
                    inner join cadastro_pessoa c
                            on c.codigo = a.fornecedor 
                    inner join cadastro_favorecido d
                            on d.codigo  = b.codigo_favorecido 
                    inner join cadastro_produto e
                            on e.codigo = b.cod_produto 
                    inner join cadastro_pessoa f 
                            on f.codigo_pessoa = b.codigo_pessoa
                    inner join cadastro_produto g
                            on g.codigo = a.cod_produto
              where a.movimentacao = 'COMPRA'
                and a.estado_registro = 'ATIVO'
                and a.id_pedido_sankhya is not null
                    $sqlWhere
                    $naoEnviadoSankhya
                    $enviadoSankhya
              order by a.data_compra, a.numero_compra";

    $faturas = Sankhya::queryExecuteDB($sql);

    if ($faturas['errorCode']) {
        $error = true;
        $msgEr .= "{$faturas['errorCode']}: {$faturas['errorMessage']} <br>";
    }

    $recorCount = count($faturas['rows']);

    $error      = false;
    $idCompraAu = '';

    foreach ($faturas['rows'] as $fatura) {
        $idCompra              = $fatura[0];
        $idProdutorSankhya     = $fatura[2];
        $idPagamentoFavorecido = $fatura[3];
        $idFavorecidoSankya    = $fatura[5];
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

        $msgAu = '';
        $error = false;
        $situacaoFatura = 'N';


        // Verifica se favorecido foi informado
        if (!$idFavorecidoSankya) {
            $error  = true;
            $msgAu  .= "Código Sankhya do favorecido '$idPessoaFatura' não informado <br>";
        }

        // Gera fatura no Sankhya caso ainda não tenha sido gerada
        if (!$error && !$idFaturaSankhya) {
            // Informa ao processamento que irá gerar a fatura
            $situacaoFatura = 'X';
            $sql = "update favorecidos_pgto 
                       set pedido_confirmado_sankhya = 'X'
                     where codigo = $idPagamentoFavorecido";
            $resultSet = Sankhya::queryExecuteDB($sql);

            $resultSet = Sankhya::faturaPedidoCompra($idCompra, $qtdeFaturada, $dataCadastro, $valorFaturado);

            if ($resultSet['errorCode']) {
                $error  = true;
                $msgAu  .= "{$resultSet['errorCode']}: {$resultSet['errorMessage']} <br>";
            } else {
                $idFaturaSankhya  = $resultSet['rows']['notas']['nota']['$'];
            }
        }

        if (!$error && $faturaConfirmada == 'N') {
            $resultItem = Sankhya::alteraItemNotaCarga(
                $idFaturaSankhya,
                $idProdutoSankhya,
                $valorFaturado,
                $qtdeFaturada,
                $unidadeProduto,
                $precoUnitario
            );
            if ($resultItem['errorCode']) {
                $error  = true;
                $msgAu .= $resultItem['errorMessage'];
            }
        }

        // Atualiza favorecido e confirma faturamento
        if (!$error && $faturaConfirmada == 'N') {
            $resultSet = Sankhya::alteraCabecalhoNotaCarga($idFaturaSankhya, $idProdutorSankhya, $idFavorecidoSankya, $dataFaturamento);

            if ($resultSet['errorCode']) {
                $error  = true;
                $msgAu  .= "{$resultSet['errorCode']}: {$resultSet['errorMessage']} <br>";
            } else {
                $situacaoFatura = 'S';

                // Confirma faturamento
                $resultSet = Sankhya::confirmaPedidoCompra($idFaturaSankhya);

                if ($resultSet['errorCode']) {
                    $error  = true;
                    $msgAu .= "{$resultSet['errorMessage']} <br>";

                    if ($resultSet['errorMessage'] != 'Nota(s) já confirmada(s)') {
                        $situacaoFatura = 'N';
                    }
                }
            }
        }

        // Atualiza favorecidos
        if ($idFaturaSankhya) {
            $sql = "update favorecidos_pgto 
                       set pedido_confirmado_sankhya = '$situacaoFatura',
                           id_pedido_sankhya         = $idFaturaSankhya 
                     where codigo = $idPagamentoFavorecido";

            $resultSet = Sankhya::queryExecuteDB($sql);

            if ($resultSet['errorCode']) {
                $error  = true;
                $msgAu  .= "Erro ao atualizar o processamento no SUIF. Avalie as informações geradas no SANKHYA <br>";
                $msgAu  .= "{$resultSet['errorCode']}: {$resultSet['errorMessage']} <br>";
            } else {
                $contador += 1;
            }
        }

        if ($error) {
            if ($idCompraAu <> $idCompra) {
                $idCompraAu = $idCompra;
                $msgEr .= "<b>Compra $idCompra </b><br>";
            }
            $msgEr .= $msgAu;
        }
    }

    if ($contador) {
        $msgOk = "Foram atualizadas $contador faturas.";
    } else {
        $msgOk = "Nenhuma fatura foi processada/atualizada.";
    }
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cria Compra no Sankhya</title>
        <link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao.css" />
        <link rel="shortcut icon" href="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/favicon_suif.ico" type="image/x-icon" />
        <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-1.8.2.js" ?>></script>
        <script src=<?= "$servidor/$diretorio_servidor/calendario_jquery/jquery-ui.js" ?>></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    </head>

<body>
    <div class="topo">
        <?php include("../../includes/topo.php"); ?>
    </div>

    <div class="container pt-3">

        <form action="#" method="post" id="frmAtualiza">
            <h3>Cria Contrato e Pedido no Sankhya</h3>
            <br>
            <div class="alert alert-success" role="alert" <?= !$msgOk ? 'hidden' : '' ?>>
                <?= $msgOk ?>
            </div>

            <div class="alert alert-danger" role="alert" <?= !$msgEr ? 'hidden' : '' ?>>
                <?= $msgEr ?>
            </div>

            <div class="form-row">
                <input type="hidden" name="sqlWhere" value="<?= $sqlWhere ?>">

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
                    <span class="badge badge-warning">
                        <?= $recorCount ?> compra(s)
                    </span>
                </div>


            </div>

            <div class="form-row">
                <button type="submit" form="frmAtualiza" value="submit" name="btnBuscar" class="btn btn-primary btn-sm mr-3">Buscar</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnContrato" class="btn btn-primary btn-sm mr-3" <?= $hidden ?>>Gera Contratos</button>
                <button type="submit" form="frmAtualiza" value="submit" name="btnPedido" class="btn btn-primary btn-sm mr-3" <?= $hidden ?>>Gera Pedidos</button>


            </div>
            <br>
            <table class="table table-hover table-striped table-sm" display: flex; width=900px>
                <thead>
                    <th>Código</th>
                    <th>Data</th>
                    <th>Sankhya</th>
                    <th>Compra</th>
                    <th>Produtor</th>
                    <th>Produto</th>
                </thead>
                <tbody>
                    <?php foreach ($resultSet['rows'] as $record) : ?>
                        <tr>
                            <td><?= $record[0] ?></td>
                            <td><?= $record[1] ?></td>
                            <td><?= $record[2] ?></td>
                            <td><?= $record[3] ?></td>
                            <td><?= $record[4] ?></td>
                            <td><?= $record[5] ?></td>
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