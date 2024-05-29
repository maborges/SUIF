<?php

require_once('SankhyaKeys.php');
require_once("../../helpers.php");


/**
 * Classe Helper - Classe auxiliar responsável por prover interface como Sankhya..
 *
 * @author Borgus
 * @copyright Copyright (c) 2024, BORGUS Software
 */
class Sankhya
{

    public static function login(): array
    {

        $curl = curl_init();
        curl_setopt_array($curl, $GLOBALS['CurlLogin']);

        try {
            $result = curl_exec($curl);
        } catch (Exception $e) {
            return array(
                "rows" => $result,
                "errorCode" => 1,
                "errorMessage" => $e->getMessage()
            );
        } finally {
            curl_close($curl);
        }

        if (!$result) {
            return  array(
                "rows" => $result,
                "errorCode" => 1,
                "errorMessage" => "Erro ao tentar comunicação com o servidor Sankhya.
                                   Verifique suas credênciais e a disponibilidade do serviço."
            );
        }

        $token = json_decode($result, true)['bearerToken'];

        return  array(
            "rows" => $token,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }

    public static function insertPedidoCompra($idCompra)
    {
        $error = 0;
        $msg   = 'ok';

        $estadoRegistro     = '';

        $filial             = '';
        $empresaIdSankhya   = '';
        $centroCustoSankhya = '';

        $produtorSUIF       = '';
        $produtorIdSankhya  = '';

        $produtoSUIF        = '';
        $produtoIdSankhya   = '';
        $compradorIdSankhya = '';
        $idUsuarioCadastro  = '';

        $topsRequisicao             = '';
        $tipoMovimentoRequisicao    = '';
        $naturezaOperacaoRequisicao = '';

        $movimentacao          = '';
        $idPedidoSankhya       = '';
        $situacaoPedidoSankhya = '';

        $tipoVenda         = 11;

        $dataCompra        = '';
        $quantidade        = 0;
        $precoUnitario     = 0;
        $valorTotal        = 0;
        $unidade           = '';
        $dataHoraTipoOperacao = '';
        $dataHoraTipoVenda = '';
        $safra          = 0;
        $impureza       = 0;
        $umidade        = 0;
        $observacao     = '';
        $codigoTipo     = 0;
        $broca          = 0;


        // Obtém a compra do SUIF
        $sql = "select * from compras where numero_compra = $idCompra";
        $resultSetCompra = self::queryExecuteDB($sql);
        $rowsCount = 0;

        if ($resultSetCompra['errorCode']) {
            $error  = 1;
            $msg  = "Erro: {$resultSetCompra['errorCode']}: {$resultSetCompra['errorMessage']}";
        } else {
            $rowsCount = Count($resultSetCompra['rows']);

            if ($rowsCount == 0) {
                $error = 2;
                $msg   = "Número da compra $idCompra não encontrado no SUIF.";
            } else if ($rowsCount > 1) {
                $error  = 3;
                $msg   = "Existe mais de uma compra cadastada com o número $idCompra.";
            } else {
                $produtorSUIF       = $resultSetCompra['rows'][0][2];
                $produtoSUIF        = $resultSetCompra['rows'][0][39];
                $estadoRegistro     = $resultSetCompra['rows'][0][24];
                $filial             = $resultSetCompra['rows'][0][25];
                $dataCompra         = date('d/m/Y', strtotime($resultSetCompra['rows'][0][4]));

                $quantidade         = $resultSetCompra['rows'][0][5];
                $precoUnitario      = $resultSetCompra['rows'][0][6];
                $valorTotal         = $resultSetCompra['rows'][0][7];
                $unidade            = $resultSetCompra['rows'][0][8];
                $safra              = $resultSetCompra['rows'][0][9];

                $impureza = str_replace('%', '', $resultSetCompra['rows'][0][43] ?? 0);

                if (!is_numeric($impureza)) {
                    $impureza = 0;    
                }

                $umidade = str_replace('%', '', $resultSetCompra['rows'][0][12] ?? 0);
                if (!is_numeric($umidade)) {
                    $umidade = 0;    
                }

                $broca = str_replace('%', '', $resultSetCompra['rows'][0][11] ?? 0);
                if (!is_numeric($broca)) {
                    $broca = 0;    
                }

                $observacao         = $resultSetCompra['rows'][0][13];
                $codigoTipo         = $resultSetCompra['rows'][0][41];
                $idUsuarioCadastro  = $resultSetCompra['rows'][0][18];
                $movimentacao       = $resultSetCompra['rows'][0][16];
                $idPedidoSankhya       = $resultSetCompra['rows'][0][55];
                $situacaoPedidoSankhya = $resultSetCompra['rows'][0][56];
                
                if ($idPedidoSankhya) {
                    $error  = 4;
                    $msg   = "Compra $idCompra já tem o número de contrato $idPedidoSankhya criado no Sankhya.";
                } else  if ($estadoRegistro <> 'ATIVO') {
                    $error  = 4;
                    $msg   = "Compra $idCompra não está ativa.";
                } else if ($movimentacao <> 'COMPRA') {
                    $error  = 4;
                    $msg   = "Registro com código $idCompra não é uma compra.";
                }
            }
        }

        // Obtém código do comprador Sankhya
        if (!$error) {

            $sql = "SELECT id_sankhya FROM usuarios WHERE username = '$idUsuarioCadastro'";

            $resultSet = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSet['errorCode']) {
                $error  = 5;
                $msg  = "Erro: {$resultSet['errorCode']}: {$resultSet['errorMessage']}";
            } else {
                $rowsCount = Count($resultSet['rows']);
                if ($rowsCount == 0) {
                    $error = 6;
                    $msg   = "Comprador $idUsuarioCadastro não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 7;
                    $msg   = "Existe mais de um comprador com o código $idUsuarioCadastro cadastada no SUIF.";
                } else {
                    $compradorIdSankhya = $resultSet['rows'][0][0];

                    if (!$compradorIdSankhya) {
                        $error  = 8;
                        $msg   = "Código Sankhya não cadastrado para o comprador $idUsuarioCadastro.";
                    }
                }
            }
        }

        // Obtém a filial do SUIF
        if (!$error) {
            $sql = "select a.id_sankhya, b.centro_custo 
                      from filiais a
                          left join centro_custo_sankhya b
                                 on b.filial  = a.codigo
                                and b.produto = $produtoSUIF
                     where a.descricao = '$filial'";

            $resultSetFilial = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetFilial['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetFilial['errorCode']}: {$resultSetFilial['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetFilial['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código da filial $filial não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de uma filial cadastada como $filial no SUIF.";
                } else {
                    $empresaIdSankhya   = $resultSetFilial['rows'][0][0];
                    $centroCustoSankhya = $resultSetFilial['rows'][0][1];

                    if (!$filial) {
                        $error  = 6;
                        $msg   = "Código Sankhya não cadastrado para a filial $filial.";
                    } elseif (!$centroCustoSankhya) {
                        $error  = 7;
                        $msg   = "Centro de custo não cadastrado para a Filial/Produto.";
                    }
                }
            }
        }

        // Obtém o produtor do SUIF
        if (!$error) {

            $sql = "select id_sankhya from cadastro_pessoa where codigo = $produtorSUIF";
            $resultSetProdutor = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetProdutor['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetProdutor['errorCode']}: {$resultSetProdutor['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetProdutor['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código do produtor $produtorSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um produtor cadastado com o número $produtorSUIF no SUIF.";
                } else {
                    $produtorIdSankhya = $resultSetProdutor['rows'][0][0];

                    if (!$produtorIdSankhya) {
                        $error  = 6;
                        $msg   = "Código de parceiro Sankhya não informado em pessoa no SUIF para o produtor $produtorSUIF.";
                    }
                }
            }
        }

        // Obtém o produto do SUIF
        if (!$error) {

            $sql = "select id_sankhya from cadastro_produto where codigo = $produtoSUIF";
            $resultSetProduto = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetProduto['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetProduto['errorCode']}: {$resultSetProduto['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetProduto['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código do produto $produtoSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um produto cadastadao com o número $produtoSUIF no SUIF.";
                } else {
                    $produtoIdSankhya = $resultSetProduto['rows'][0][0];

                    if (!$produtoIdSankhya) {
                        $error  = 6;
                        $msg   = "Código do produto Sankhya não informado em produtos no SUIF para o produto $produtoSUIF.";
                    }
                }
            }
        }

        // Obtém o TOPS do SUIF
        if (!$error) {

            $sql = "select tops_requisicao, tipo_movimento_requisicao, natureza_operacao_requisicao 
                      from tipo_operacao_produto 
                     where tipo_operacao = 'ECPR' and produto_sankhya = $produtoIdSankhya";

            $resultSetTOPS = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetTOPS['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetTOPS['errorCode']}: {$resultSetTOPS['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetTOPS['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Tipo de Operação de Produto (TOPS) do produto $produtoSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um Tipo de Operação de Produto Sankhya cadastrado no SUIF para o produto $produtoSUIF.";
                } else {
                    $topsRequisicao             = $resultSetTOPS['rows'][0][0];
                    $tipoMovimentoRequisicao    = $resultSetTOPS['rows'][0][1];
                    $naturezaOperacaoRequisicao = $resultSetTOPS['rows'][0][2];

                    if (!$topsRequisicao) {
                        $error  = 6;
                        $msg   = "Código do Tipo de Operação de Produto Sankhya inválido para o produto $produtoSUIF.";
                    }
                }
            }
        }

        // Busca o histórico dos tipos de operação
        if (!$error) {
            $sql = "SELECT MAX(TGFTOP.DHALTER) TGFTOP_DHALTER, 
                           MAX(TGFTPV.DHALTER) TGFTPV_DHALTER 
                      FROM TGFTOP, TGFTPV 
                     WHERE CODTIPOPER = $topsRequisicao AND CODTIPVENDA = $tipoVenda";

            $resultSetOperacao = self::queryExecuteAPI($sql);

            if ($resultSetOperacao['errorCode']) {
                $erro = 1;
                $msg  = "Erro: {$resultSetOperacao['errorCode']}: {$resultSetOperacao['errorMessage']}";
            } else if (Count($resultSetOperacao['rows']) == 0) {
                $erro = 1;
                $msg  = "Histórico não encontrado para o tipo de operação e/ou tipo de venda.";
            } else {
                $dataHoraTipoOperacao = DateTime::createFromFormat(
                    "dmY H:i:s",
                    $resultSetOperacao['rows'][0][0]
                )->format('d/m/Y H:i:s');

                $dataHoraTipoVenda    = DateTime::createFromFormat(
                    "dmY H:i:s",
                    $resultSetOperacao['rows'][0][1]
                )->format('d/m/Y H:i:s');
            }
        }

        // Monta JSON para enviar os dados do pedido
        if (!$error) {
            $body = array(
                'nota' => array(
                    'cabecalho' => array(
                        'NUNOTA' => array("$" => ''),
                        'CODPARC' => array('$' => "$produtorIdSankhya"),
                        'DTNEG' => array('$' => "$dataCompra"),
                        'CODTIPOPER' => array('$' => "$topsRequisicao"),
                        'DHTIPOPER' => array('$' => "$dataHoraTipoOperacao"),
                        'CODTIPVENDA' => array('$' => $tipoVenda),
                        'DHTIPVENDA' => array('$' => "$dataHoraTipoVenda"),
                        'CODVEND' => array('$' => '0'),
                        'CODEMP' => array('$' => "$empresaIdSankhya"),
                        'TIPMOV' => array('$' => "$tipoMovimentoRequisicao"),
                        'CIF_FOB' => array('$' => "C"),
                        'ISSRETIDO' => array('$' => "N"),
                        'CODNAT' => array('$' => "$naturezaOperacaoRequisicao"),
                        'AD_PEDIDO_SUIF' => array('$' => "$idCompra"),
                        'CODUSU' => array('$' => "{$_COOKIE['u_sankhya']}"),
                        'CODUSUINC' => array('$' => "{$_COOKIE['u_sankhya']}"),
                        'CODUSUCOMPRADOR' => array('$' => "$compradorIdSankhya"),
                        'CODCENCUS' => array('$' => "$centroCustoSankhya"),
                        'AD_SAFRA' => array('$' => "$safra"),
                        'AD_PERCIMPUREZA' => array('$' => "$impureza"),
                        'AD_PERCUMIDADE' => array('$' => "$umidade"),
                        'AD_PERCBROCA' => array('$' => "$broca"),
                        'AD_TIPOPRODUTO' => array('$' => "$codigoTipo"),
                        'OBSERVACAO' => array('$' => $observacao),
                        'IRFRETIDO' => array('$' => 'S')
                    ),
                    'itens' => array(
                        'INFORMARPRECO' => 'True',
                        'item' => array(
                            array(
                                'NUNOTA' => array("$" => ""),
                                'CODPROD' => array('$' => $produtoIdSankhya),
                                'CODVOL' => array('$' => "$unidade"),
                                'CODLOCALORIG' => array('$' => '0'),
                                'CONTROLE' => array('$' => ''),
                                'QTDNEG' => array('$' => "$quantidade"),
                                'PERCDESC' => array('$' => '0'),
                                'VLRUNIT' => array('$' => "$precoUnitario"),
                                'VLRTOT' => array('$' => "$valorTotal")
                            )
                        )
                    )
                )
            );


            $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiCriaPedido'], $body);

            if ($resultServiceAPI['errorCode']) {
                $error  = $resultServiceAPI['errorCode'];
                $msg  = "Erro: {$resultServiceAPI['errorCode']}: {$resultServiceAPI['errorMessage']}";
            }
        }

        if ($error) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $error,
                "errorMessage" => $msg
            );
        }

        return array(
            "rows" => $resultServiceAPI['rows'],
            "effectedRows" => 0,
            "errorCode" => $error,
            "errorMessage" => $msg
        );
    }

    public static function confirmaPedidoCompra($pedidoSankhya)
    {
        $body = array(
            "notas" => array(
                "nunota" => array(
                    array("$" => $pedidoSankhya)
                ),
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiConfirmaPedido'], $body);

        if ($resultServiceAPI['errorCode']) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $resultServiceAPI['errorCode'],
                "errorMessage" => $resultServiceAPI['errorMessage']
            );
        } else {
            return array(
                "rows" => $resultServiceAPI['rows'],
                "effectedRows" => 0,
                "errorCode" => "",
                "errorMessage" => ""
            );
        }
    }

    public static function cancelaPedidoCompra($pedidoSankhya)
    {
        $body = array(
            "notasCanceladas" => array(
                "nunota" => array(
                    array("$" => $pedidoSankhya)
                ),
                "justificativa" => "lançamento indevido",
                "validarProcessosWmsEmAndamento" => "true"
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiCancelaPedido'], $body);

        if ($resultServiceAPI['errorCode']) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $resultServiceAPI['errorCode'],
                "errorMessage" => $resultServiceAPI['errorMessage']
            );
        } else {
            return array(
                "rows" => $resultServiceAPI['rows'],
                "effectedRows" => 0,
                "errorCode" => "",
                "errorMessage" => ""
            );
        }
    }

    public static function alteraCabecalhoNota($numNota, $idParceiro, $idParceiroNota)
    {
        $body = "<?xml version='1.0'?>
                <serviceRequest serviceName='CACSP.incluirAlterarCabecalhoNota'>
                    <requestBody>
                        <nota>
                            <cabecalho>
                                <NUNOTA>$numNota</NUNOTA>
                                <AD_CODPARCFICHA>$idParceiro</AD_CODPARCFICHA>
                                <AD_CODPARCNOTA>$idParceiroNota</AD_CODPARCNOTA>
                            </cabecalho>
                        </nota>
                    </requestBody>
                </serviceRequest>";

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiAlteraCabecalhoNota'], $body, false);

        if ($resultServiceAPI['errorCode']) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $resultServiceAPI['errorCode'],
                "errorMessage" => "Fatura: $numNota - " . $resultServiceAPI['errorMessage']
            );
        } else {
            return array(
                "rows" => $resultServiceAPI['rows'],
                "effectedRows" => 0,
                "errorCode" => "",
                "errorMessage" => ""
            );
        }
    }

    // apagar depois de fazer a carga, pos não será mais necessario
    public static function alteraCabecalhoNotaCarga($numNota, $idParceiro, $idParceiroNota, $dataNegociacao)
    {
        $body = "<?xml version='1.0'?>
                <serviceRequest serviceName='CACSP.incluirAlterarCabecalhoNota'>
                    <requestBody>
                        <nota>
                            <cabecalho>
                                <NUNOTA>$numNota</NUNOTA>
                                <AD_CODPARCFICHA>$idParceiro</AD_CODPARCFICHA>
                                <AD_CODPARCNOTA>$idParceiroNota</AD_CODPARCNOTA>
                                <DTNEG>$dataNegociacao</DTNEG>
                                <DTENTSAI>$dataNegociacao</DTENTSAI>
                                <DTMOV>$dataNegociacao</DTMOV>
                                <DTFATUR>$dataNegociacao</DTFATUR>
                            </cabecalho>
                        </nota>
                    </requestBody>
                </serviceRequest>";

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiAlteraCabecalhoNota'], $body, false);

        if ($resultServiceAPI['errorCode']) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $resultServiceAPI['errorCode'],
                "errorMessage" => "Fatura: $numNota Produtor: $idParceiro Favorecido: $idParceiroNota Faturamento: $dataNegociacao  <br>" . 
                                $resultServiceAPI['errorMessage']
            );
        } else {
            return array(
                "rows" => $resultServiceAPI['rows'],
                "effectedRows" => 0,
                "errorCode" => "",
                "errorMessage" => "alteraCabecalhoNotaCarga ok"
            );
        }
    }

    // apagar depois de fazer a carga, pos não será mais necessario
    public static function alteraItemNotaCarga($numNota, $idProduto, $valorFatura, $qtdeNegociada, $codigoVolume, $valorUnitario)
    {
        $error = 0;
        $msg   = '';
        $body = array(
            'nota' => array(
                'NUNOTA' => $numNota,
                'itens' => array(
                    'item' => array (
                        'CODPROD' => array('$' => $idProduto),
                        'NUNOTA' => array('$' => $numNota),
                        'SEQUENCIA' => array('$' =>'1'),
                        'QTDNEG' => array('$' => $qtdeNegociada),
                        'CODVOL' => array('$' => $codigoVolume),
                        'VLRUNIT' => array('$' => $valorUnitario),
                        'VLRTOT' => array('$' => $valorFatura)
                    ),
                )
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiAlteraItemNota'], $body);

        if ($resultServiceAPI['errorCode']) {
            $error = $resultServiceAPI['errorCode'];
            $msg = "Erro: {$resultServiceAPI['errorCode']}: {$resultServiceAPI['errorMessage']}";
        }

        if ($error) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $error,
                "errorMessage" => $msg
            );
        }

        return array(
            "rows" => $resultServiceAPI['rows'],
            "effectedRows" => 0,
            "errorCode" => $error,
            "errorMessage" => $msg
        );

    }

    public static function faturaPedidoCompra($idCompra, $qtdeItem, $dataFaturamento, $valorFaturado)
    {
        $error = "";
        $msg   = "";

        $qtdeItem = round($qtdeItem,3);

        // Obtém pedido de compra Sankhya no SUIF
        $sql = "select id_pedido_sankhya, estado_registro, data_cadastro, cod_produto, fornecedor, pedido_confirmado_sankhya, preco_unitario, unidade
                  from compras 
                 where numero_compra = $idCompra
                   and movimentacao  = 'COMPRA'";
        $resultSetCompra = self::queryExecuteDB($sql);
        $rowsCount = 0;

        if ($resultSetCompra['errorCode']) {
            $error = $resultSetCompra['errorCode'];
            $msg   = $resultSetCompra['errorMessage'];
        } else {
            $rowsCount = Count($resultSetCompra['rows']);

            if ($rowsCount == 0) {
                $error = 2;
                $msg   = "Compra $idCompra não encontrado no SUIF.";
            } else if ($rowsCount > 1) {
                $error = 3;
                $msg   = "Existe mais de uma compra cadastada com o número $idCompra.";
            } else if (!$resultSetCompra['rows'][0]) {
                $error = 4;
                $msg   = "Compra $idCompra ainda não tem pedido gerado no Sankhya.";
            } else if ($resultSetCompra['rows'][0][1] <> 'ATIVO') {
                $error = 5;
                $msg   = "Compra $idCompra não está ativa.";
            } else if ($resultSetCompra['rows'][0][5] <> 'S') {
                $error = 6;
                $msg   = "Compra $idCompra não está confirmada no Sankhya.";
            } else {
                $precoUnitario  = $resultSetCompra['rows'][0][6];
                $unidadeProduto = $resultSetCompra['rows'][0][7];
            }
        }

        // Obtém o produtor do SUIF
        if (!$error) {
            $produtorSUIF = $resultSetCompra['rows'][0][4];
            $sql = "select id_sankhya from cadastro_pessoa where codigo = $produtorSUIF";
            $resultSetProdutor = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetProdutor['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetProdutor['errorCode']}: {$resultSetProdutor['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetProdutor['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código do produtor $produtorSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um produtor cadastado com o número $produtorSUIF no SUIF.";
                } else {
                    $produtorIdSankhya = $resultSetProdutor['rows'][0][0];

                    if (!$produtorIdSankhya) {
                        $error  = 6;
                        $msg   = "Código de parceiro Sankhya não informado em pessoa no SUIF para o produtor $produtorSUIF.";
                    }
                }
            }
        }

        // Obtém o produto do SUIF
        if (!$error) {
            $produtoSUIF = $resultSetCompra['rows'][0][3];

            $sql = "select id_sankhya from cadastro_produto a where codigo = $produtoSUIF";

            $resultSetProduto = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetProduto['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetProduto['errorCode']}: {$resultSetProduto['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetProduto['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código do produto $produtoSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um produto cadastadao com o número $produtoSUIF no SUIF.";
                } else {
                    $produtoIdSankhya = $resultSetProduto['rows'][0][0];

                    if (!$produtoIdSankhya) {
                        $error  = 6;
                        $msg   = "Código do produto Sankhya $produtoSUIF não informado no SUIF.";
                    }
                }
            }
        }

        // Obtém o TOPS do SUIF
        if (!$error) {
            $sql = "select tops_compra, tipo_movimento_compra
                      from tipo_operacao_produto 
                     where tipo_operacao = 'ECPR' and produto_sankhya = $produtoIdSankhya";

            $resultSetTOPS = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetTOPS['errorCode']) {
                $error  = 3;
                $msg  = "Erro: {$resultSetTOPS['errorCode']}: {$resultSetTOPS['errorMessage']}";
            } else {
                $rowsCount = Count($resultSetTOPS['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Tipo de Operação de Produto (TOPS) do produto $produtoSUIF não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de um Tipo de Operação de Produto Sankhya cadastrado no SUIF para o produto $produtoSUIF.";
                } else {
                    $topsCompra = $resultSetTOPS['rows'][0][0];
                    $topsMovto  = $resultSetTOPS['rows'][0][1];

                    if (!$topsCompra) {
                        $error  = 6;
                        $msg   = "Código do Tipo de Operação de Produto Sankhya (Compra) inválido para o produto $produtoSUIF.";
                    }
                }
            }
        }

        // Gera pedido de faturamento no Sankhya
        if (!$error) {
            $pedidoSankhya = $resultSetCompra['rows'][0][0];

            $body = array(
                "notas" => array(
                    "codTipOper" => $topsCompra,
                    "TIPMOV" => $topsMovto,
                    "dtFaturamento" => "$dataFaturamento",
                    "tipoFaturamento" => "FaturamentoNormal",
                    "dataValidada" => true,
                    "notasComMoeda" => array("$" => ""),
                    "nota" => array(
                        "NUNOTA" => $pedidoSankhya,
                        "itens" => array(
                            "item" => array(
                                array(
                                    "$" => 1,
                                    "QTDFAT" => $qtdeItem,
                                    "VLRTOT" => $valorFaturado,
                                    "CODVOL" => $unidadeProduto
                                )
                            )
                        )
                    ),
                    "codLocalDestino" => "",
                    "faturarTodosItens" => false,
                    "umaNotaParaCada" => false,
                    "ehWizardFaturamento" => true,
                    "dtFixaVenc" => "",
                    "ehPedidoWeb" => false,
                    "nfeDevolucaoViaRecusa" => false
                )
            );
            $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiFaturamentoPedido'], $body);

            if ($resultServiceAPI['errorCode']) {
                $error  = $resultServiceAPI['errorCode'];
                $msg  = $resultServiceAPI['errorMessage'];
            } else {
                $idFaturaSankhya = $resultServiceAPI['rows']['notas']['nota']['$'];

            }
        }

        if ($error) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $error,
                "errorMessage" => $msg
            );
        }

        return array(
            "rows" => $resultServiceAPI['rows'],
            "effectedRows" => 0,
            "errorCode" => $error,
            "errorMessage" => $msg
        );
    }

    public static function getParceiroById($id): array
    {
        $sql =  "SELECT A.CODPARC, DECODE(A.TIPPESSOA,'F','PF','J','PJ'), A.NOMEPARC, A.RAZAOSOCIAL, A.CGC_CPF, 
                        A.IDENTINSCESTAD IE_RG, A.TELEFONE, A.TIMTELEFONE02,
                        DECODE(B.TIPO, null,B.NOMEEND, B.TIPO || ' ' || B.NOMEEND), 
                        A.NUMEND, C.NOMEBAI, A.CEP, A.EMAIL, D.UF, D.NOMECID, E.UF, 
                        A.DTNASC, DECODE(A.SEXO,'M','MASCULINO','FEMININO') SEXO, 
                        A.AD_TIPOPRODFAV, A.CODBCO, A.CODAGE, A.CODCTABCO, A.COMPLEMENTO, A.AD_FAVORECIDO
                   from TGFPAR A 
                        LEFT JOIN TSIEND B 
                            ON B.CODEND = A.CODEND
                        LEFT JOIN TSIBAI C 
                            ON C.CODBAI = A.CODBAI
                        LEFT JOIN TSICID D
	                        ON D.CODCID = A.CODCID
                        LEFT JOIN TSIUFS E
	                        ON E.CODUF = D.UF
                  WHERE A.CODPARC = $id";

        return self::queryExecuteAPI($sql);
    }

    public static function getIdEstadoDeParaSankhya($UF): array
    {
        $sql = "SELECT est_id FROM cad_estados WHERE est_sigla = '$UF'";
        return self::queryExecuteDB($sql);
    }

    public static function getIdCidadeDeParaSankhya($Estado, $Cidade): array
    {
        $sql = "SELECT cid_id, cid_nome FROM cad_cidades WHERE est_id = $Estado AND cid_nome = '$Cidade'";
        return self::queryExecuteDB($sql);
    }

    public static function getIdBancoDeParaSankhya($banco): array
    {
        $sql = "select numero, nome from cadastro_banco where numero = $banco";
        return self::queryExecuteDB($sql);
    }

    public static function getFavorecidoSUIF($idSankhya = 0, $Sequencia = 0): array
    {
        $sql = "select nome from cadastro_favorecido where id_sankhya = $idSankhya and sequencia_cc_sankhya = $Sequencia";
        Helpers::consoleLog($sql);
        return self::queryExecuteDB($sql);
    }

    public static function getContaCorrenteByParceiro($idParceiro, $Sequencia): array
    {
        $sql = "SELECT CODBCO, AGENCIA, DVAGENCIA, CONTA, DVCONTA, TIPOCONTA, TIPOCHAVEPIX, CHAVEPIX, FAVORECIDO
                  FROM AD_CONTAPAG
                 WHERE CODPARC = $idParceiro
                  AND SEQUENCIA = $Sequencia
                  AND ATIVO = 'S'";

        return self::queryExecuteAPI($sql);
    }

    public static function listContaCorrenteByParceiro($idParceiro): array
    {
        $sql = "SELECT CODBCO, AGENCIA, DVAGENCIA, CONTA, DVCONTA, TIPOCONTA, TIPOCHAVEPIX, CHAVEPIX, FAVORECIDO
                  FROM AD_CONTAPAG
                 WHERE CODPARC = $idParceiro
                  AND ATIVO = 'S'
                ORDER BY SEQUENCIA";

        return self::queryExecuteAPI($sql);
    }

    public static function queryExecuteAPI($sqlExecute): array
    {
        $tokenSankhya = Sankhya::login();

        // Verifica se a API executou
        if ($tokenSankhya['errorCode']) {
            return array(
                "rows" => $tokenSankhya['rows'],
                "errorCode" => $tokenSankhya['errorCode'] ?? 1,
                "errorMessage" => $tokenSankhya['errorMessage']
            );
        }

        $jsonData = json_encode(array(
            'serviceName' => $GLOBALS['serviceNameQuery'],
            'requestBody' => array(
                'sql' => "$sqlExecute"
            )
        ), JSON_PRETTY_PRINT);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $GLOBALS['urlApiQuery'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                $GLOBALS['contentType'] . 'json', // Se a requisição for JSON
                $GLOBALS['Bearer'] . $tokenSankhya['rows'],
            ),
            CURLOPT_POSTFIELDS => $jsonData,
        ]);

        $result = curl_exec($curl);
        $jsonResult = json_decode($result, true);

        // Verifica se a API executou
        if ($jsonResult['error'] ?? '') {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['error']['descricao']
            );
        }

        if (!$jsonResult['status']) {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['statusMessage']
            );
        }

        // Fechar a sessão cURL
        curl_close($curl);
        $rows = json_decode($result, true)['responseBody']['rows'];

        return array(
            "rows" => $rows,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }

    public static function serviceExecuteAPI($url, $requestBody, $json = true): array
    {
        $tokenSankhya = Sankhya::login();

        // Verifica se a API executou
        if ($tokenSankhya['errorCode']) {
            return array(
                "rows" => $tokenSankhya['rows'],
                "errorCode" => $tokenSankhya['errorCode'] ?? 1,
                "errorMessage" => $tokenSankhya['errorMessage']
            );
        }

        if ($json) {
            $body = json_encode(array(
                'requestBody' => $requestBody
            ), JSON_UNESCAPED_SLASHES);
            $contentType = $GLOBALS['contentType'] . 'json';
        } else {
            $body = $requestBody;
            $contentType = $GLOBALS['contentType'] . 'xml';
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                $contentType,
                $GLOBALS['Bearer'] . $tokenSankhya['rows'],
            ),
            CURLOPT_POSTFIELDS => $body,
        ]);

        $result = curl_exec($curl);
        // Fechar a sessão cURL
        curl_close($curl);

        if (!$json) {
            $string = str_replace('"', "'", $result);     // substitui aspas dupla po simples
            $string = str_replace(array("\r", "\n"),'', $string); // tira o cr/lf

            try {
                $xml = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA);

            } catch (Exception $e) {
                if (!$xml['status']) {
                    return array(
                        "rows" => [],
                        "errorCode" => 1,
                        "errorMessage" => "Erro ao tentar ler XML de retorno do Sankhya na execução da API $url. <br> {$e->getMessage()}"
                    );
                }
    
            }
            
            if ("{$xml['status']}" == '0') {
                return array(
                    "rows" => [],
                    "errorCode" => 1,
                    "errorMessage" => base64_decode($xml->statusMessage)
                );
            }

            return array(
                "rows" => [],
                "errorCode" => 0,
                "errorMessage" => 'ok'
            );
        }

        $jsonResult = json_decode($result, true);

        // Verifica se a API executou
        if ($jsonResult['error'] ?? '') {
            return array(
                "rows" => [],
                "errorCode" => 1,
                "errorMessage" => $jsonResult['error']['descricao']
            );
        }

        if (!$jsonResult['status']) {
            return array(
                "rows" => [],
                "errorCode" => 2,
                "errorMessage" => $jsonResult['statusMessage']
            );
        }

        $rows = $jsonResult['responseBody'];

        return array(
            "rows" => $rows,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }

    public static function queryExecuteDB($sql): array
    {
        $rows = [];

        // trata a conexão com o banco
        try {
            $conexao = ConnectDB();

            // verifica se a conexão foi ok
            if ($conexao->connect_errno) {
                return array(
                    "rows" => [],
                    "errorCode" => $conexao->connect_errno,
                    "errorMessage" => $conexao->connect_error
                );
            }

            // Executa SQL    
            $result = mysqli_query($conexao, $sql);

            if (!$result) {
                return array(
                    "rows" => $rows,
                    "effectedRows" => 0,
                    "errorCode" => 1,
                    "errorMessage" => mysqli_error($conexao),
                    "count" => 0
                );
            }

            // Monta array com todos os registro, se houver quando de um SELECT
            if (mysqli_field_count($conexao) > 0) {
                $rows = mysqli_fetch_all($result);
                $effectedRows = mysqli_num_rows($result);
            } else {
                $effectedRows = mysqli_affected_rows($conexao);
                $rows = [];
            }

            $count = $effectedRows;

            return array(
                "rows" => $rows,
                "effectedRows" => $effectedRows,
                "errorCode" => 0,
                "errorMessage" => 'ok',
                "count" => $count
            );
        } catch (Exception $e) {
            return array(
                "rows" => $rows,
                "effectedRows" => 0,
                "errorCode" => 1,
                "errorMessage" => mysqli_error($conexao),
                "count" => 0
            );
        } finally {
            DisconnectDB($conexao);
        }
    }

    public static function queryExecuteDBOnly($sql) 
    {
        // trata a conexão com o banco
        $conexao = ConnectDB();

        try {
            // Executa SQL    
            $result = mysqli_query($conexao, $sql);

            // Monta array com todos os registro, se houver quando de um SELECT
            if (mysqli_field_count($conexao) > 0) {
                $effectedRows = mysqli_num_rows($result);
            } else {
                $effectedRows = mysqli_affected_rows($conexao);
            }

            return array(
                $result,
                "effectedRows" => $effectedRows,
                "errorCode" => $conexao->errno,
                "errorMessage" => $conexao->error,
            );


        } catch (Exception $e) {
            return array(
                "rows" => null,
                "effectedRows" => 0,
                "errorCode" => $conexao->errno,
                "errorMessage" => $conexao->error
            );
        } finally {
            DisconnectDB($conexao);
        }
    }

    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;

        /* TIPO DE MASCARA
            $cnpj = '11222333000199';
            $cpf = '00100200300';
            $cep = '08665110';
            $data = '10102010';
            $hora = '021050';

            echo mask($cnpj, '##.###.###/####-##').'<br>';
            echo mask($cpf, '###.###.###-##').'<br>';
            echo mask($cep, '#####-###').'<br>';
            echo mask($data, '##/##/####').'<br>';
            echo mask($data, '##/##/####').'<br>';
            echo mask($data, '[##][##][####]').'<br>';
            echo mask($data, '(##)(##)(####)').'<br>';
            echo mask($hora, 'Agora são ## horas ## minutos e ## segundos').'<br>';
            echo mask($hora, '##:##:##');
        */
    }
}
