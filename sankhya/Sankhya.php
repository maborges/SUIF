<?php

require_once('SankhyaKeys.php');

/**
 * Classe Helper - Classe auxiliar responsável por prover interface como Sankhya..
 *
 * @author Borgus
 * @copyright Copyright (c) 2024, BORGUS Software
 */
class Sankhya
{

    public static function bearerToken()
    {
        // Verifica se o token existe e não expirou
        if (isset($_SESSION['bearer_token']) && isset($_SESSION['token_expiry']) && $_SESSION['token_expiry'] > time()) 
        {
            return  array(
                "rows" => $_SESSION['bearer_token'],
                "errorCode" => 0,
                "errorMessage" => "ok"
            );
        }

        // Token expirado ou inexistente, faz login
        return Sankhya::login();
    }

    public static function login(): array
    {

        $curl = curl_init();
        curl_setopt_array($curl, $GLOBALS['CurlLogin']);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);

            if (!empty($data['bearerToken'])) {

                if (session_status() === PHP_SESSION_NONE) {
                    // Se não estiver iniciada, inicia a sessão
                    session_start();
                }
                
                // Armazena o token e o tempo de expiração na sessão
                $_SESSION['bearer_token'] = $data['bearerToken'];
                $_SESSION['token_expiry'] = time() + TOKEN_EXPIRY_SECONDS;

                return  array(
                    "rows" => $data['bearerToken'],
                    "errorCode" => 0,
                    "errorMessage" => "ok"
                );                
                
            }
        }

        return  array(
            "rows" => $response,
            "errorCode" => 1,
            "errorMessage" => 'Falha ao autenticar do Sankhya: ' . ($response ?: 'Sem resposta')
        );

    }

    public static function DadosArmazenagem($idNFEntrada)
    {
        $result = [
            'errorCode'    => 0,
            'errorMessage' => '',
            'nf'           => null
        ];

        $result = (object)$result;

        // Obtém a armazenamento do SUIF
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
                       g.tops_compra, 
                       g.tipo_movimento_compra, 
                       g.natureza_operacao_requisicao,
                       a.filial filialSuif, 
                       h.id_sankhya filialSankhya,
                       i.centro_custo centroCusto,
                       a.quantidade qtdeItem,
                       a.valor_total valorFaturado,
                       a.unidade unidadeProduto,
                       a.valor_unitario vlrUnit,
                       a.observacao,
                       '' tipo,
                       j.id_Sankhya comprador,
                       a.id_pedido_sankhya contratoSankhya,
                       a.pedido_confirmado_sankhya contratoConfirmadoSankhya,
                       a.id_fatura_sankhya faturaSankhya,
                       a.fatura_confirmada_sankhya faturaConfirmadaSankhya,
                       h.filial_armazenamento
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
                              on h.descricao = b.filial 
                       left join centro_custo_sankhya i
                              on i.filial = h.codigo 
                             and i.produto = e.codigo 
                       left join usuarios j
                              on j.username = b.usuario_cadastro
                 where a.codigo            = $idNFEntrada
                   and a.estado_registro   = 'ATIVO'
                   and a.natureza_operacao = 'ARMAZENAGEM'
                order by a.codigo_romaneio";

        $resultSetNotaFiscal = Self::queryExecuteDB($sql);
        $rowsCount = 0;

        if ($resultSetNotaFiscal['errorCode']) {
            $result->errorCode    = 1;
            $result->errorMessage = $resultSetNotaFiscal['errorMessage'];
            return $result;
        }

        $rowsCount = Count($resultSetNotaFiscal['rows']);

        if ($rowsCount <> 1) {
            $result->errorCode    = 2;
            $result->errorMessage = "Nota fiscal de entrada $idNFEntrada não encontrado, ou duplicada, ou não é de armazenagem.";
            return $result;
        }

        $nf = [
            'nfEntrada'                  => $idNFEntrada,
            'codigoRomaneio'             => $resultSetNotaFiscal['rows'][0][0],
            'dataEntrada'                => date('d/m/Y', strtotime($resultSetNotaFiscal['rows'][0][1])),
            'produtorNFSuif'             => $resultSetNotaFiscal['rows'][0][2],
            'produtorNFSankhya'          => $resultSetNotaFiscal['rows'][0][3],
            'produtorRomaneioSuif'       => $resultSetNotaFiscal['rows'][0][4],
            'produtorRomaneioSankhya'    => $resultSetNotaFiscal['rows'][0][5],
            'produtoSuif'                => $resultSetNotaFiscal['rows'][0][6],
            'produtoSankhya'             => $resultSetNotaFiscal['rows'][0][7],
            'topsContrato'               => $resultSetNotaFiscal['rows'][0][8],
            'tipoMovimentoContrato'      => $resultSetNotaFiscal['rows'][0][9],
            'topsFatura'                 => $resultSetNotaFiscal['rows'][0][10],
            'tipoMovimentoFatura'        => $resultSetNotaFiscal['rows'][0][11],
            'naturezaOperacao'           => $resultSetNotaFiscal['rows'][0][12],
            'filialSuif'                 => $resultSetNotaFiscal['rows'][0][13],
            'filialSankhya'              => $resultSetNotaFiscal['rows'][0][14],
            'centroCusto'                => $resultSetNotaFiscal['rows'][0][15],
            'qtdeItem'                   => $resultSetNotaFiscal['rows'][0][16],
            'valorFaturado'              => $resultSetNotaFiscal['rows'][0][17],
            'unidadeProduto'             => $resultSetNotaFiscal['rows'][0][18],
            'vlrUnit'                    => $resultSetNotaFiscal['rows'][0][19],
            'observacao'                 => $resultSetNotaFiscal['rows'][0][20],
            'codigoTipo'                 => $resultSetNotaFiscal['rows'][0][21],
            'comprador'                  => $resultSetNotaFiscal['rows'][0][22],
            'contratoSankhya'            => $resultSetNotaFiscal['rows'][0][23],
            'contratoConfirmadoSankhya'  => $resultSetNotaFiscal['rows'][0][24],
            'faturaSankhya'              => $resultSetNotaFiscal['rows'][0][25],
            'faturaConfirmadaSankhya'    => $resultSetNotaFiscal['rows'][0][26],
            'filialArmazenamento'        => $resultSetNotaFiscal['rows'][0][27],
            'tipoVenda'                  => 999,
            'dhTOPContrato'              => null,
            'dhTipoContrato'             => null,
            'dhTOPFatura'                => null,
            'dhTipoFatura'               => null,
        ];

        $nfEntrada = (object)$nf;

        if (!$nfEntrada->produtorNFSankhya) {
            $result->errorCode    = 3;
            $result->errorMessage = "Codigo do produtor Sankhya da nota fiscal não informado.";
            return $result;
        } else if (!$nfEntrada->produtorRomaneioSankhya) {
            $result->errorCode    = 4;
            $result->errorMessage = "Codigo do produtor Sankhya do Romaneio não informado.";
            return $result;
        } else if (!$nfEntrada->produtoSankhya) {
            $result->errorCode    = 5;
            $result->errorMessage = "Codigo do produto Sankhya não informado.";
            return $result;
        } else if (!$nfEntrada->topsContrato || !$nfEntrada->topsFatura) {
            $result->errorCode    = 6;
            $result->errorMessage = "Código TOP Fatura não encontrado para o produto $nfEntrada->produtoSuif.";
            return $result;
        } else if (!$nfEntrada->filialSankhya) {
            $result->errorCode    = 7;
            $result->errorMessage = "Código da filial Sankhya não informado.";
            return $result;
        } else if (!$nfEntrada->centroCusto) {
            $result->errorCode    = 8;
            $result->errorMessage = "Código do centro de custo não informado.";
            return $result;
        } else if (!$nfEntrada->comprador) {
            $result->errorCode    = 9;
            $result->errorMessage = "Código do comprador Sankhya não informado no SUIF.";
            return $result;
        } else if ($nfEntrada->faturaSankhya) {
            $result->errorMessage = "Nota Fiscal $nfEntrada->nfEntrada já tem o número de fatura $nfEntrada->faturaSankhya criado no Sankhya.";
        }

        // Busca o histórico dos tipos de operação - Contrato
        $tipoOperacao = self::BuscaHistoricoTOP($nfEntrada->topsContrato, $nfEntrada->tipoVenda);

        if ($tipoOperacao->errorCode) {
            $result->errorCode    = $tipoOperacao->errorCode;
            $result->errorMessage = $tipoOperacao->errorMessage;
            return $result;
        }

        $nfEntrada->dhTOPContrato  = $tipoOperacao->dhTOP;
        $nfEntrada->dhTipoContrato = $tipoOperacao->dhTipo;

        // Busca o histórico dos tipos de operação - Fatura
        $tipoOperacao = self::BuscaHistoricoTOP($nfEntrada->topsFatura, $nfEntrada->tipoVenda);

        if ($tipoOperacao->errorCode) {
            $result->errorCode    = $tipoOperacao->errorCode;
            $result->errorMessage = $tipoOperacao->errorMessage;
            return $result;
        }

        $nfEntrada->dhTOPFatura  = $tipoOperacao->dhTOP;
        $nfEntrada->dhTipoFatura = $tipoOperacao->dhTipo;

        $result->nf = $nfEntrada;

        return $result;
    }

    public static function IncluiArmazenagem($idNFEntrada)
    {
        $result = [
            'errorCode'       => 0,
            'errorMessage'    => '',
            'contratoSankhya' => 0,
            'faturaSankhya'   => 0,
        ];

        $result = (object) $result;

        $resultSet = Self::DadosArmazenagem($idNFEntrada);

        if ($resultSet->errorCode) {
            $result->errorCode    = $resultSet->errorCode;
            $result->errorMessage = $resultSet->errorMessage;
            Self::informaProcessoContratoArmazenagem($idNFEntrada, null, null, $result->errorMessage);
            return $result;
        }

        $nfEntrada = $resultSet->nf;

        /* **
            Contrato de armazenamento
        */
        if (is_null($nfEntrada->contratoSankhya) or $nfEntrada->contratoConfirmadoSankhya <> 'S') {
            // Informa que está processando contrato
            Self::informaProcessoContratoArmazenagem($nfEntrada->nfEntrada, $nfEntrada->contratoSankhya, 'X');
            $contrato = Self::CriaContratoArmazenagem($nfEntrada);

            $nfEntrada->contratoSankhya = $contrato->contratoSankhya;

            if ($contrato->errorCode) {
                $result->errorCode       = $contrato->errorCode;
                $result->errorMessage    = $contrato->errorMessage;
                $result->contratoSankhya = $contrato->contratoSankhya;

                // Avisa quando ocorreu erro mas conseguiu cria o contrato
                if ($nfEntrada->contratoSankhya) {
                    Self::informaProcessoContratoArmazenagem($nfEntrada->nfEntrada, $nfEntrada->contratoSankhya, 'X', $result->errorMessage);
                }

                return $result;
            }

            $nfEntrada->contratoSankhya = $contrato->contratoSankhya;

            // Confirma contrato
            $resultSet = Self::ConfirmaContratoArmazenagem($nfEntrada);

            if ($resultSet->errorCode) {
                $result->errorCode    = 9;
                $result->errorMessage = $resultSet->errorMessage;
                Self::informaProcessoContratoArmazenagem($nfEntrada->nfEntrada, $nfEntrada->contratoSankhya, 'X', $resultSet->errorMessage);
                return $result;
            }

            $nfEntrada->contratoConfirmadoSankhya = 'S';

            // Informa que confirmou no Sankhya
            Self::informaProcessoContratoArmazenagem(
                $nfEntrada->nfEntrada,
                $nfEntrada->contratoSankhya,
                $nfEntrada->contratoConfirmadoSankhya,
                $result->errorMessage
            );
        }


        /*
            Fatura de armazenamento
        */

        // Cria fatura quando da inclusão do contrato
        // Para criar a fatura, o contrato já deverá estar criado e confirmado
        if (($nfEntrada->contratoSankhya && $nfEntrada->contratoConfirmadoSankhya == 'S') and
            (is_null($nfEntrada->faturaSankhya) or $nfEntrada->faturaConfirmadaSankhya <> 'S')
        ) {

            Self::informaProcessoFaturaArmazenagem($idNFEntrada, $nfEntrada->faturaSankhya, 'X');

            $fatura = Self::CriaFaturaArmazenagem($nfEntrada);
            $nfEntrada->faturaSankhya = $fatura->faturaSankhya;

            if ($fatura->errorCode) {
                $result->errorCode     = $fatura->errorCode;
                $result->errorMessage  = $fatura->errorMessage;

                // Avisa qdo conseguiu cria a fatura mesmo com erro
                if ($nfEntrada->faturaSankhya) {
                    Self::informaProcessoFaturaArmazenagem($idNFEntrada, $nfEntrada->faturaSankhya, 'X', $result->errorMessage);
                }

                return $result;
            }

            $resultItem = Self::alteraItemNota(
                $nfEntrada->faturaSankhya,
                $nfEntrada->produtoSankhya,
                $nfEntrada->valorFaturado,
                $nfEntrada->qtdeItem,
                $nfEntrada->unidadeProduto,
                $nfEntrada->vlrUnit,
                $nfEntrada->observacao
            );


            if ($resultItem['errorCode']) {
                $result->errorCode    = $resultItem['errorCode'];
                $result->errorMessage = $resultItem['errorMessage'];
                return $result;
            }

            $resultSet = Self::alteraCabecalhoNota(
                $nfEntrada->faturaSankhya,
                $nfEntrada->produtorRomaneioSankhya,
                $nfEntrada->produtorNFSankhya,
                $nfEntrada->dataEntrada,
                null,
                $nfEntrada->tipoVenda,
                null,
                null
            );

            if ($resultSet['errorCode']) {
                $result->errorCode    = $resultSet['errorCode'];
                $result->errorMessage = $resultSet['errorMessage'];
                Self::informaProcessoFaturaArmazenagem($nfEntrada->nfEntrada, $nfEntrada->faturaSankhya, 'X', $result->errorMessage);
                return $result;
            }

            $resultSet = Self::ConfirmaFaturaArmazenagem($nfEntrada);

            if ($resultSet->errorCode) {
                $result->errorCode    = 11;
                $result->errorMessage = "{$resultSet->errorCode}: {$resultSet->errorMessage}";
                return $result;
            }

            // Informa que confirmou no Sankhya
            Self::informaProcessoFaturaArmazenagem($nfEntrada->nfEntrada, $nfEntrada->faturaSankhya, 'S');
        }

        return $result;
    }

    public static function CriaContratoArmazenagem($nfEntrada)
    {
        $result = [
            'errorCode' => null,
            'errorMessage' => null,
            'contratoSankhya' => $nfEntrada->contratoSankhya
        ];

        $result = (object) $result;

        if ($nfEntrada->contratoSankhya) {
            // retorna erro se já existe contrato
            $result->errorMessage    = "Contrato já gerado para a NF $nfEntrada->nfEntrada.";
            return $result;
        }

        $body = array(
            'nota' => array(
                'cabecalho' => array(
                    'NUNOTA' => array("$" => ''),
                    'CODPARC' => array('$' => "$nfEntrada->produtorNFSankhya"),
                    'DTNEG' => array('$' => "$nfEntrada->dataEntrada"),
                    'CODTIPOPER' => array('$' => "$nfEntrada->topsContrato"),
                    'DHTIPOPER' => array('$' => "$nfEntrada->dhTOPContrato"),
                    'CODTIPVENDA' => array('$' => $nfEntrada->tipoVenda),
                    'DHTIPVENDA' => array('$' => "$nfEntrada->dhTipoContrato"),
                    'CODVEND' => array('$' => '0'),
                    'CODEMP' => array('$' => "$nfEntrada->filialArmazenamento"),
                    'TIPMOV' => array('$' => "$nfEntrada->tipoMovimentoContrato"),
                    'CIF_FOB' => array('$' => "C"),
                    'ISSRETIDO' => array('$' => "N"),
                    'CODNAT' => array('$' => "$nfEntrada->naturezaOperacao"),
                    'AD_PEDIDO_SUIF' => array('$' => "$nfEntrada->codigoRomaneio"),
                    'CODUSU' => array('$' => "{$_COOKIE['u_sankhya']}"),
                    'CODUSUINC' => array('$' => "{$_COOKIE['u_sankhya']}"),
                    'CODUSUCOMPRADOR' => array('$' => $nfEntrada->comprador),
                    'CODCENCUS' => array('$' => "$nfEntrada->centroCusto"),
                    'AD_CODPARCFICHA' => array('$' => "$nfEntrada->produtorRomaneioSankhya"),
                    'AD_CODPARCNOTA' => array('$' => "$nfEntrada->produtorNFSankhya"),
                    'AD_SAFRA' => array('$' => ""),
                    'AD_PERCIMPUREZA' => array('$' => ""),
                    'AD_PERCUMIDADE' => array('$' => ""),
                    'AD_PERCBROCA' => array('$' => ""),
                    'AD_TIPOCOMPRASUIF' => array('$' => 2), // para contrato de armazenagem é 2
                    'AD_TIPOPRODUTO' => array('$' => "$nfEntrada->codigoTipo"),
                    'OBSERVACAO' => array('$' => $nfEntrada->observacao),
                    'IRFRETIDO' => array('$' => 'S')
                ),
                'itens' => array(
                    'INFORMARPRECO' => 'True',
                    'item' => array(
                        array(
                            'NUNOTA' => array("$" => ""),
                            'CODPROD' => array('$' => $nfEntrada->produtoSankhya),
                            'CODVOL' => array('$' => "$nfEntrada->unidadeProduto"),
                            'CODLOCALORIG' => array('$' => '105000'),
                            'CONTROLE' => array('$' => ''),
                            'QTDNEG' => array('$' => "$nfEntrada->qtdeItem"),
                            'PERCDESC' => array('$' => '0'),
                            'VLRUNIT' => array('$' => "$nfEntrada->vlrUnit"),
                            'VLRTOT' => array('$' => "$nfEntrada->valorFaturado")
                        )
                    )
                )
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiCriaPedido'], $body);

        // Pode retornar um erro e com a fatura gerada
        $result->contratoSankhya = $resultServiceAPI['rows']['pk']['NUNOTA']['$'] ?? null;

        if ($resultServiceAPI['errorCode']) {
            $result->errorCode    = $resultServiceAPI['errorCode'];
            $result->errorMessage = $resultServiceAPI['errorMessage'];
            return $result;
        }

        return $result;
    }

    public static function CriaFaturaArmazenagem($nfEntrada)
    {
        $result = [
            'errorCode' => null,
            'errorMessage' => null,
            'faturaSankhya' => $nfEntrada->faturaSankhya,
            'faturaConfirmadaSankhya' => $nfEntrada->faturaConfirmadaSankhya
        ];

        $result = (object) $result;

        if ($nfEntrada->faturaSankhya) {
            // retorna erro que já existe contrato
            $result->errorMessage  = "Fatura já gerada para a NF $nfEntrada->nfEntrada.";
            $result->faturaSankhya = $nfEntrada->faturaSankhya;
            return $result;
        }

        $body = array(
            "notas" => array(
                "codTipOper" => "$nfEntrada->topsFatura",
                "TIPMOV" => "$nfEntrada->tipoMovimentoFatura",
                "dtFaturamento" => "$nfEntrada->dataEntrada",
                "tipoFaturamento" => "FaturamentoNormal",
                "dataValidada" => true,
                "notasComMoeda" => array("$" => ""),
                "nota" => array(
                    "NUNOTA" => $nfEntrada->contratoSankhya,
                    "itens" => array(
                        "item" => array(
                            array(
                                "$" => 1,
                                "QTDFAT" => $nfEntrada->qtdeItem,
                                "VLRTOT" => $nfEntrada->valorFaturado,
                                "CODVOL" => $nfEntrada->unidadeProduto
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

        // pode ter criado a fatura e dado erro no retorno da API
        $result->faturaSankhya = $resultServiceAPI['rows']['notas']['nota']['$'] ?? null;

        if ($resultServiceAPI['errorCode']) {
            $result->errorCode    = $resultServiceAPI['errorCode'];
            $result->errorMessage = $resultServiceAPI['errorMessage'];
            return $result;
        }

        return $result;
    }

    public static function ConfirmaContratoArmazenagem($nfEntrada)
    {
        $result = [
            'errorCode' => null,
            'errorMessage' => null,
        ];

        $result = (object) $result;


        if ($nfEntrada->contratoConfirmadoSankhya == 'S') {
            $result->errorMessage = "Contrato de armazenamento $nfEntrada->contratoSankhya já confirmado.";
            return $result;
        }

        $body = array(
            "notas" => array(
                "nunota" => array(
                    array("$" => $nfEntrada->contratoSankhya)
                ),
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiConfirmaPedido'], $body);

        if ($resultServiceAPI['errorCode'] and !strpos($resultServiceAPI['errorMessage'], 'confirmada(s)')) {
            $result->errorCode    = 1;
            $result->errorMessage = $resultServiceAPI['errorMessage'];
            return $result;
        }

        return $result;
    }

    public static function ConfirmaFaturaArmazenagem($nfEntrada)
    {
        $result = [
            'errorCode' => null,
            'errorMessage' => null,
        ];

        $result = (object) $result;

        if ($nfEntrada->faturaConfirmadaSankhya == 'S') {
            $result->errorMessage = "Fatura de armazenamento $nfEntrada->faturaSankhya já confirmado.";
            return $result;
        }

        $body = array(
            "notas" => array(
                "nunota" => array(
                    array("$" => $nfEntrada->faturaSankhya)
                ),
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiConfirmaPedido'], $body);

        if ($resultServiceAPI['errorCode'] and !strpos($resultServiceAPI['errorMessage'], 'confirmada(s)')) {
            $result->errorCode    = 1;
            $result->errorMessage = $resultServiceAPI['errorMessage'];
        }

        return $result;
    }

    public static function CancelaContratoArmazenagem($contrato, $motivo = '')
    {
        $result = [
            'errorCode'       => null,
            'errorMessage'    => null,
            'contratoSankhya' => null,
            'faturaSankhya'   => null,
        ];

        $result = (object) $result;

        if (!$contrato) {
            $result->errorMessage = 'Nenhum contrato informado para ser cancelado';
            return $result;
        }

        $cancela = Self::cancelaDocumento($contrato, $motivo);

        if ($cancela['errorCode']) {
            $result->errorCode    = $cancela['errorCode'];
            $result->errorMessage = $cancela['errorMessage'];
        }

        return $result;
    }

    public static function CancelaFaturaArmazenagem($fatura, $motivo = '')
    {
        $result = [
            'errorCode'       => null,
            'errorMessage'    => null,
            'contratoSankhya' => null,
            'faturaSankhya'   => null,
        ];

        $result = (object) $result;

        if (!$fatura) {
            $result->errorMessage = 'Nenhuma fatura informada para ser cancelada';
            return $result;
        }

        $cancela = Self::cancelaDocumento($fatura, $motivo);

        if ($cancela['errorCode']) {
            $result->errorCode    = $cancela['errorCode'];
            $result->errorMessage = $cancela['errorMessage'];
        }

        return $result;
    }


    /***
     * Processos para tratamento de compras (contratos e faturas)
     */
    public static function insertPedidoCompra($idCompra)
    {
        $error = 0;
        $msg   = 'ok';

        $estadoRegistro     = '';

        $filial             = '';
        $filialFaturamento  = '';
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
        $tipoCompra     = 0;


        // Obtém a compra do SUIF
        $sql = "select * from compras where numero_compra = $idCompra";
        $resultSetCompra = self::queryExecuteDB($sql);
        $rowsCount = 0;

        if ($resultSetCompra['errorCode']) {
            $error  = 1;
            $msg  = $resultSetCompra['errorMessage'];
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
                $filialFaturamento  = $resultSetCompra['rows'][0][61];
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
                $tipoCompra            = $resultSetCompra['rows'][0][57];

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
                $msg  = $resultSet['errorMessage'];
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
                     where a.descricao = '$filialFaturamento'";

            $resultSetFilial = self::queryExecuteDB($sql);
            $rowsCount = 0;

            if ($resultSetFilial['errorCode']) {
                $error  = 3;
                $msg  = $resultSetFilial['errorMessage'];
            } else {
                $rowsCount = Count($resultSetFilial['rows']);
                if ($rowsCount == 0) {
                    $error = 4;
                    $msg   = "Código da filial $filialFaturamento não encontrado no SUIF.";
                } else if ($rowsCount > 1) {
                    $error  = 5;
                    $msg   = "Existe mais de uma filial cadastada como $filialFaturamento no SUIF.";
                } else {
                    $empresaIdSankhya   = $resultSetFilial['rows'][0][0];
                    $centroCustoSankhya = $resultSetFilial['rows'][0][1];

                    if (!$filialFaturamento) {
                        $error  = 6;
                        $msg   = "Código Sankhya não cadastrado para a filial de faturamento $filialFaturamento.";
                    } elseif (!$centroCustoSankhya) {
                        $error  = 7;
                        $msg   = "Centro de custo não cadastrado para a Filial de Faturamento/Produto.";
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
                $msg  = $resultSetProdutor['errorMessage'];
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
                $msg  = $resultSetProduto['errorMessage'];
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
                $msg  = $resultSetTOPS['errorMessage'];
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
                $error = 1;
                $msg  = $resultSetOperacao['errorMessage'];
            } else if (Count($resultSetOperacao['rows']) == 0) {
                $error = 1;
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
                        'AD_TIPOCOMPRASUIF' => array('$' => $tipoCompra),
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
                                'CODLOCALORIG' => array('$' => '101000'),
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
                $msg  = $resultServiceAPI['errorMessage'];
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

    public static function cancelaDocumento($pedidoSankhya, $motivo = 'SUIF-lançamento indevido')
    {
        $body = array(
            "notasCanceladas" => array(
                "nunota" => array(
                    array("$" => $pedidoSankhya)
                ),
                "justificativa" => $motivo,
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
        } else if ($resultServiceAPI['rows']['resultadoCancelamento']["totalNotasCanceladas"] == 0) {
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => 1,
                "errorMessage" => 'Sankhya não cancelou nenhum documento. Verifique se o mesmo já foi cancelado ou se o número está correto.'
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

    public static function alteraCabecalhoNota($numNota, $idParceiro, $idParceiroNota, $dataNegociacao, $idCCSankhya = null, $tipoVenda, $bancoPagamento = '', $numeroCheque = '')
    {

        $body = "<?xml version='1.0'?>
                <serviceRequest serviceName='CACSP.incluirAlterarCabecalhoNota'>
                    <requestBody>
                        <nota>
                            <cabecalho>
                                <NUNOTA>$numNota</NUNOTA>
                                <AD_CODPARCFICHA>$idParceiro</AD_CODPARCFICHA>
                                <AD_CODPARCNOTA>$idParceiroNota</AD_CODPARCNOTA>
                                <AD_CODCTAPAG>$idCCSankhya</AD_CODCTAPAG>
                                <AD_CODBCOSUIF>$bancoPagamento</AD_CODBCOSUIF>
                                <AD_NUMCHEQUESUIF>$numeroCheque</AD_NUMCHEQUESUIF>
                                <DTNEG>$dataNegociacao</DTNEG>
                                <DTENTSAI>$dataNegociacao</DTENTSAI>
                                <DTMOV>$dataNegociacao</DTMOV>
                                <DTFATUR>$dataNegociacao</DTFATUR>
                                <CODTIPVENDA>$tipoVenda</CODTIPVENDA>

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
                "errorMessage" => str_replace('"', '', str_replace("'", "", $resultServiceAPI['errorMessage']))
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


    public static function alteraValorDesconto($numNota, $valor)
    {

        $body = "<?xml version='1.0'?>
                <serviceRequest serviceName='CACSP.incluirAlterarCabecalhoNota'>
                    <requestBody>
                        <nota>
                            <cabecalho>
                                <NUNOTA>$numNota</NUNOTA>
                                <AD_DESCONTOSUIF>$valor</AD_DESCONTOSUIF>
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
                "errorMessage" => str_replace('"', '', str_replace("'", "", $resultServiceAPI['errorMessage']))
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

    // apagar depois de fazer a carga, pois não será mais necessário
    public static function alteraItemNota($numNota, $idProduto, $valorFatura, $qtdeNegociada, $codigoVolume, $valorUnitario, $observacao = '')
    {
        $error = 0;
        $msg   = '';
        $body = array(
            'nota' => array(
                'NUNOTA' => $numNota,
                'itens' => array(
                    'item' => array(
                        'CODPROD' => array('$' => $idProduto),
                        'NUNOTA' => array('$' => $numNota),
                        'SEQUENCIA' => array('$' => '1'),
                        'QTDNEG' => array('$' => $qtdeNegociada),
                        'CODVOL' => array('$' => $codigoVolume),
                        'VLRUNIT' => array('$' => $valorUnitario),
                        'VLRTOT' => array('$' => $valorFatura),
                        'OBSERVACAO' => array('$' => $observacao)
                    ),
                )
            )
        );

        $resultServiceAPI = Self::serviceExecuteAPI($GLOBALS['urlApiAlteraItemNota'], $body);

        if ($resultServiceAPI['errorCode']) {
            $error = $resultServiceAPI['errorCode'];
            $msg = $resultServiceAPI['errorMessage'];
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

    public static function geraFaturaSankhya(array $data)
    {
        $body = array(
            "notas" => array(
                "codTipOper" => $data['codTipOper'],
                "TIPMOV" => $data['TipMov'],
                "dtFaturamento" => $data['dtFaturamento'],
                "tipoFaturamento" => "FaturamentoNormal",
                "dataValidada" => true,
                "notasComMoeda" => array("$" => ""),
                "nota" => array(
                    "NUNOTA" =>  $data['NumNota'],
                    "itens" => array(
                        "item" => array(
                            array(
                                "$" => 1,
                                "QTDFAT" =>  $data['qtdeItem'],
                                "VLRTOT" =>  $data['valorFaturado'],
                                "CODVOL" =>  $data['unidadeProduto']
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
            return array(
                "rows" => [],
                "effectedRows" => 0,
                "errorCode" => $resultServiceAPI['errorCode'],
                "errorMessage" => $resultServiceAPI['errorMessage']
            );
        }

        return array(
            "rows" => $resultServiceAPI['rows'],
            "effectedRows" => 0,
            "errorCode" => '',
            "errorMessage" => 'ok'
        );
    }

    public static function faturaPedidoCompra($idCompra, $qtdeItem, $dataFaturamento, $valorFaturado)
    {
        $error = "";
        $msg   = "";

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
                $msg  = $resultSetProdutor['errorMessage'];
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
                $msg  = $resultSetProduto['errorMessage'];
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
                $msg  = $resultSetTOPS['errorMessage'];
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

    /** 
     * Atualiza os daddos do contrato do Sankhya na tabela de compras
     * @param integer $idCompra Código da compra
     * @param integer $idContratoSankhya Número do contrato retornado pelo Sankhya
     * @param integer $situacaoContrato Situação do contrato após processo do Sankhya
     * (N - Não processado) (S - Processado) (X - Falha no processamento)
     * 
     * @return array
     */
    public static function atualizaDadosCompra($idCompra, $idContratoSankhya, $situacaoContrato, $logSankhya = ''): array
    {
        $contrato = $idContratoSankhya <> '' ? $idContratoSankhya : 'id_pedido_sankhya';
        $textLog = str_replace(array("\r", "\n"), '', str_replace('"', '', str_replace("'", "", $logSankhya)));

        $sql = "update compras 
                   set id_pedido_sankhya         = $contrato,
                       pedido_confirmado_sankhya = '$situacaoContrato',
                       log_sankhya               = '$textLog'
                 where numero_compra             = $idCompra";

        return Sankhya::queryExecuteDB($sql);
    }

    /** 
     * Atualiza os daddos de pagamento do Sankhya na tabela de faturamento (favorecido_pagto)
     * @param integer $idPagamento Código do pagamento
     * @param integer $idFaturaSankhya Número da fatura gerada pelo Sankhya
     * @param integer $situacaoFatura Situação do pagamento quando retornado o processamento
     * (N - Não processado) (S - Processado) (X - Falha no processamento)
     * 
     * @return array
     */
    public static function atualizaDadosPagamentoFavorecido($idPagamento, $idFaturaSankhya, $situacaoFatura, $logSankhya = ''): array
    {
        $fatura = $idFaturaSankhya ?? 'id_pedido_sankhya';
        $textLog = str_replace(array("\r", "\n"), '', str_replace('"', '', str_replace("'", "", $logSankhya)));

        if (substr($textLog, 0, 21) == 'ORA-20101: Parceiro n') {
            $textLog = 'Parceiro não está ativo';
        }

        $sql = "update favorecidos_pgto 
                   set pedido_confirmado_sankhya = '$situacaoFatura',
                       id_pedido_sankhya         = $fatura,
                       log_sankhya               = '$textLog'
                 where codigo                    = $idPagamento";

        $result = Sankhya::queryExecuteDB($sql);

        return $result;
    }

    public static function getParceiroById($id): array
    {
        $sql =  "SELECT A.CODPARC, 
                        DECODE(A.TIPPESSOA,'F','PF','J','PJ'), 
                        A.NOMEPARC, 
                        A.RAZAOSOCIAL, 
                        A.CGC_CPF, 
                        A.IDENTINSCESTAD IE_RG, 
                        A.TELEFONE, 
                        A.TIMTELEFONE02,
                        DECODE(B.TIPO, null,B.NOMEEND, B.TIPO || ' ' || B.NOMEEND), 
                        A.NUMEND, 
                        C.NOMEBAI, 
                        A.CEP, 
                        A.EMAIL, 
                        D.UF, 
                        D.NOMECID, 
                        E.UF, 
                        A.DTNASC, 
                        DECODE(A.SEXO,'M','MASCULINO','FEMININO') SEXO, 
                        A.AD_TIPOPRODFAV, 
                        A.CODBCO, 
                        A.CODAGE, 
                        A.CODCTABCO, 
                        A.COMPLEMENTO, 
                        A.AD_FAVORECIDO, 
                        A.AD_CADASTROREVISADO, 
                        A.AD_SERASA_EMBARGO, 
                        A.AD_CONS_SERASA
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
        $tokenSankhya = Sankhya::bearerToken();

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
        $tokenSankhya = Sankhya::bearerToken();

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

            $xml = [];

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
        $conexao = ConnectDB();

        // trata a conexão com o banco
        try {
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
                "errorMessage" => '',
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

    public static function informaProcessoContratoArmazenagem($entradaNF, $contratoSankhya = '', $situacao = '', $logMessage = '')
    {
        $textLog = str_replace(array("\r", "\n"), '', str_replace('"', '', str_replace("'", "", $logMessage)));
        $setPedido   = $contratoSankhya ? "id_pedido_sankhya = $contratoSankhya," : "";
        $setSituacao = $situacao ? "pedido_confirmado_sankhya = '$situacao'," : "";

        $sql = "update nota_fiscal_entrada 
                   set $setPedido
                       $setSituacao
                       log_sankhya = '$textLog'
                 where codigo = $entradaNF";

        $resultSet = Sankhya::queryExecuteDB($sql);

        return $resultSet['errorMessage'];
    }

    public static function informaProcessoFaturaArmazenagem($entradaNF, $faturaSankhya = '', $situacao = '', $logMessage = '')
    {
        $textLog = str_replace(array("\r", "\n"), '', str_replace('"', '', str_replace("'", "", $logMessage)));
        $setFatura   = $faturaSankhya ? "id_fatura_sankhya = $faturaSankhya," : "";
        $setSituacao = $situacao ? "fatura_confirmada_sankhya = '$situacao'," : "";

        $fatura = isset($faturaSankhya) ? $faturaSankhya : "null";

        $sql = "update nota_fiscal_entrada 
                   set $setFatura
                       $setSituacao
                       log_sankhya = '$textLog'
                 where codigo = $entradaNF";
        $resultSet = Sankhya::queryExecuteDB($sql);
        return $resultSet['errorMessage'];
    }

    public static function BuscaHistoricoTOP($tops, $tipo)
    {
        $result = [
            'errorCode' => null,
            'errorMessage' => null,
            'dhTOP' => null,
            'dhTipo' => null,
        ];

        $result = (object) $result;

        // Busca o histórico dos tipos de operação
        $sql = "SELECT MAX(TGFTOP.DHALTER) TGFTOP_DHALTER, 
                           MAX(TGFTPV.DHALTER) TGFTPV_DHALTER 
                      FROM TGFTOP, TGFTPV 
                     WHERE CODTIPOPER = $tops AND CODTIPVENDA = $tipo";

        $resultSet = self::queryExecuteAPI($sql);

        if ($resultSet['errorCode']) {
            $result->errorCode = 1;
            $result->errorMessage = $resultSet['errorMessage'];
        } else if (Count($resultSet['rows']) == 0) {
            $result->errorCode = 2;
            $result->errorMessage = "Histórico não encontrado para o tipo de operação $tops e/ou tipo de venda $tipo.";
        } else {
            $result->dhTOP = DateTime::createFromFormat(
                "dmY H:i:s",
                $resultSet['rows'][0][0]
            )->format('d/m/Y H:i:s');

            $result->dhTipo = DateTime::createFromFormat(
                "dmY H:i:s",
                $resultSet['rows'][0][1]
            )->format('d/m/Y H:i:s');
        }

        return (object) $result;
    }

    public static function atualizaSERASASankhya($idParceiro, $validado, $embargado): array
    {
        $tokenSankhya = Sankhya::bearerToken();

        // Verifica se a API executou
        if ($tokenSankhya['errorCode']) {
            return array(
                "rows" => $tokenSankhya['rows'],
                "errorCode" => $tokenSankhya['errorCode'] ?? 1,
                "errorMessage" => $tokenSankhya['errorMessage']
            );
        }

        $jsonData = json_encode(array(
            'serviceName' => $GLOBALS['serviceNameUpdate'],
            'requestBody' => array(
                'entityName' => "Parceiro",
                'standAlone' => false,
                'fields' => array(
                    'CODPARC',
                    'AD_CONS_SERASA',
                    'AD_SERASA_EMBARGO'
                ),
                'records' => array(
                    array(
                        'pk' => array(
                            'CODPARC' => $idParceiro
                        ),
                        'values' => array(
                            '1' => $validado,
                            '2' => $embargado
                        ),
                    ),
                ),
            )
        ), JSON_PRETTY_PRINT);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $GLOBALS['urlApiUpdate'],
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
        $rows = json_decode($result, true)['responseBody'];

        return array(
            "rows" => $rows,
            "errorCode" => 0,
            "errorMessage" => "ok"
        );
    }
}
