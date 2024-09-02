<?php

require_once('SankhyaKeys.php');
require_once("../../helpers.php");



class SankhyaTeste
{


    public static function IncluiArmazenagem($idEntradaNF)
    {
        $error = 0;
        $msg   = 'ok';

        $codigoRomaneio        = null;
        $dataFaturamento       = null;
        $produtorNFSankhya     = null;

        $tipoVenda         = 11;

        // Obtém a compra do SUIF
        $sql = "select b.numero_romaneio  codigoRomaneio,
                       b.data dataEntrada, 
                       d.codigo produtorNFSuif,
                       d.id_sankhya produtorNFSankhya,
                       c.codigo produtorRomaneioSuif,
                       c.id_sankhya produtorRomaneioSankhya,
                       e.codigo produtoNFSuif,
                       e.id_sankhya produtoNFSankhya,
                       g.tops_requisicao, 
                       g.tipo_movimento_requisicao, 
                       g.natureza_operacao_requisicao,
                       a.filial filialSuif, 
                       h.id_sankhya filialSankhya,
                       i.centro_custo centroCusto,
                       a.quantidade qtdeItem,
                       a.valor_total valorFaturado,
                       a.unidade unidadeProduto,
                       a.valor_unitario vlrUnit,
                       a.observacao,
                       a.id_pedido_sankhya pedidoSankhya,
                       a.pedido_confirmado_sankhya pedidoConfirmadoSankhya,
                       b.tipo
                  from nota_fiscal_entrada a
                       left join estoque b       
                              on b.numero_romaneio   = a.codigo_romaneio
                             and b.estado_registro   = 'ATIVO'
                             and b.movimentacao      = 'ENTRADA'
                       left join cadastro_pessoa c
                              on c.codigo = b.fornecedor
                       left join cadastro_pessoa d
                              on d.codigo = a.codigo_fornecedor
                       left join cadastro_produto e 
                              on e.codigo = a.cod_produto
                       left join tipo_operacao_produto g
                              on g.tipo_operacao   = 'EARM'
                             and g.produto_sankhya = e.id_sankhya  
                       left join filiais h 
                              on h.descricao = a.filial 
                       left join centro_custo_sankhya i
                              on i.filial = h.codigo 
                             and i.produto = e.codigo 
                 where a.codigo            = $idEntradaNF
                   and a.estado_registro   = 'ATIVO'
                   and a.natureza_operacao = 'ARMAZENAGEM'
                order by a.codigo_romaneio";
        
        $resultSetNotaFiscal = self::queryExecuteDB($sql);
        $rowsCount = 0;

        if ($resultSetNotaFiscal['errorCode']) {
            $error  = 1;
            $msg  = "Erro: {$resultSetNotaFiscal['errorCode']}: {$resultSetNotaFiscal['errorMessage']}";
        } else {
            $rowsCount = Count($resultSetNotaFiscal['rows']);

            if ($rowsCount == 0) {
                $error = 2;
                $msg   = "Número da entrada de nota fiscal $idEntradaNF não encontrado no SUIF.";
            } else {
                $codigoRomaneio             = $resultSetNotaFiscal['rows'][0][0];
                $dataEntrada                = date('d/m/Y', strtotime($resultSetNotaFiscal['rows'][0][1]));
                $produtorNFSuif             = $resultSetNotaFiscal['rows'][0][2];
                $produtorNFSankhya          = $resultSetNotaFiscal['rows'][0][3];
                $produtorRomaneioSuif       = $resultSetNotaFiscal['rows'][0][4];
                $produtorRomaneioSankhya    = $resultSetNotaFiscal['rows'][0][5];
                $produtoSuif                = $resultSetNotaFiscal['rows'][0][6];
                $produtoSankhya             = $resultSetNotaFiscal['rows'][0][7];
                $topsRequisicao             = $resultSetNotaFiscal['rows'][0][8];
                $tipoMovimentoRequisicao    = $resultSetNotaFiscal['rows'][0][9];
                $naturezaOperacaoRequisicao = $resultSetNotaFiscal['rows'][0][10];
                $filialSuif                 = $resultSetNotaFiscal['rows'][0][11];  
                $filialSankhya              = $resultSetNotaFiscal['rows'][0][!2];
                $centroCusto                = $resultSetNotaFiscal['rows'][0][!3]; 
                $qtdeItem                   = $resultSetNotaFiscal['rows'][0][14];
                $valorFaturado              = $resultSetNotaFiscal['rows'][0][15];
                $unidadeProduto             = $resultSetNotaFiscal['rows'][0][16];
                $vlrUnit                    = $resultSetNotaFiscal['rows'][0][17];
                $observacao                 = $resultSetNotaFiscal['rows'][0][18];
                $pedidoSankhya              = $resultSetNotaFiscal['rows'][0][19]; 
                $pedidoConfirmadoSankhya    = $resultSetNotaFiscal['rows'][0][20]; 
                $codigoTipo                 = $resultSetNotaFiscal['rows'][0][21]; 

                if (!$produtorNFSankhya) {
                    $error = 1;
                    $msg   = "Codigo do produtor Sankhya do romaneio não informado.";
                } else if (!$produtorRomaneioSankhya) {
                    $error = 2;
                    $msg   = "Codigo do produtor Sankhya da Nota Fiscal não informado.";
                } else if (!$produtoSankhya) {
                    $error = 3;
                    $msg   = "Codigo do produto Sankhya não informado.";
                } else if (!$produtoSankhya) {
                    $error = 4;
                    $msg   = "Codigo do produto Sankhya não informado.";
                } else if (!$topsRequisicao) {
                    $error = 5;
                    $msg   = "Código TOP não encontrado para o produto $produtoSuif.";
                } else if (!$filialSankhya) {
                    $error = 6;
                    $msg   = "Código da filial Sankhya não informado.";
                } else if (!$centroCusto) {
                    $error = 7;
                    $msg   = "Código do centro de custo não informado.";
                } else if ($pedidoConfirmadoSankhya == 'S') {
                    $error = 8;
                    $msg   = "Pedido $pedidoSankhya já confirmado.";
                } else if ($pedidoSankhya) {
                    $error = 9;
                    $msg   = "Nota Fiscal $idEntradaNF já tem o número de contrato $pedidoSankhya criado no Sankhya.";
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
                        'CODPARC' => array('$' => "$produtorNFSankhya"),
                        'DTNEG' => array('$' => "$dataEntrada"),
                        'CODTIPOPER' => array('$' => "$topsRequisicao"),
                        'DHTIPOPER' => array('$' => "$dataHoraTipoOperacao"),
                        'CODTIPVENDA' => array('$' => $tipoVenda),
                        'DHTIPVENDA' => array('$' => "$dataHoraTipoVenda"),
                        'CODVEND' => array('$' => '0'),
                        'CODEMP' => array('$' => "$filialSankhya"),
                        'TIPMOV' => array('$' => "$tipoMovimentoRequisicao"),
                        'CIF_FOB' => array('$' => "C"),
                        'ISSRETIDO' => array('$' => "N"),
                        'CODNAT' => array('$' => "$naturezaOperacaoRequisicao"),
                        'AD_PEDIDO_SUIF' => array('$' => "$idEntradaNF"),
                        'CODUSU' => array('$' => "{$_COOKIE['u_sankhya']}"),
                        'CODUSUINC' => array('$' => "{$_COOKIE['u_sankhya']}"),
                        'CODUSUCOMPRADOR' => array('$' => ""),
                        'CODCENCUS' => array('$' => "$centroCusto"),
                        'AD_SAFRA' => array('$' => ""),
                        'AD_PERCIMPUREZA' => array('$' => ""),
                        'AD_PERCUMIDADE' => array('$' => ""),
                        'AD_PERCBROCA' => array('$' => ""),
                        'AD_TIPOPRODUTO' => array('$' => "$codigoTipo"),
                        'OBSERVACAO' => array('$' => $observacao),
                        'IRFRETIDO' => array('$' => 'S')
                    ),
                    'itens' => array(
                        'INFORMARPRECO' => 'True',
                        'item' => array(
                            array(
                                'NUNOTA' => array("$" => ""),
                                'CODPROD' => array('$' => $produtoSankhya),
                                'CODVOL' => array('$' => "$unidadeProduto"),
                                'CODLOCALORIG' => array('$' => '0'),
                                'CONTROLE' => array('$' => ''),
                                'QTDNEG' => array('$' => "$qtdeItem"),
                                'PERCDESC' => array('$' => '0'),
                                'VLRUNIT' => array('$' => "$vlrUnit"),
                                'VLRTOT' => array('$' => "$valorFaturado")
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
            $string = str_replace(array("\r", "\n"), '', $string); // tira o cr/lf

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
}
