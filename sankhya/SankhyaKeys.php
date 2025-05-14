<?php
$msgErroSankhya = '';
$erroCodeSankhya = 0;

const TOKEN_EXPIRY_SECONDS = 1740; // 29 minutos (pode ser ajustado conforme INATSESSTIMEOUT)

static $urlApiLogin             = 'https://api.sankhya.com.br/login';

static $urlApiQuery             = 'https://api.sankhya.com.br/gateway/v1/mge/service.sbr?serviceName=DbExplorerSP.executeQuery&outputType=json';

static $urlApiUpdate             = 'https://api.sankhya.com.br/gateway/v1/mge/service.sbr?serviceName=DatasetSP.save&outputType=json';

static $urlApiCriaPedido        = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=CACSP.incluirNota&outputType=json";

static $urlApiAlteraCabecalhoNota = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=CACSP.incluirAlterarCabecalhoNota&outputType=xlm";

static $urlApiAlteraItemNota = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=CACSP.incluirAlterarItemNota&outputType=json";

static $urlApiConfirmaPedido    = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=ServicosNfeSP.confirmarNotas&mgeSession=JSESSIONID&outputType=json";

static $urlApiCancelaPedido    = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=CACSP.cancelarNota&outputType=json";

static $urlApiFaturamentoPedido = "https://api.sankhya.com.br/gateway/v1/mgecom/service.sbr?serviceName=SelecaoDocumentoSP.faturar&outputType=json";

static $serviceNameQuery        = 'DbExplorerSP.executeQuery';
static $serviceNameUpdate       = 'DatasetSP.save';

static $contentType = 'Content-Type: application/';
static $Bearer = 'Authorization: Bearer ';

static $headersLogin = [
    "token: 1569e9fb-e884-470b-9ba3-1cb34f4ff5bd",
    "appkey: 665cd3ae-b474-4515-8bcf-920bbd6984e3",
    "username: marcos.borges@borgus.com.br",
    "password: &Xpert01"
];

$CurlLogin =
    [
        CURLOPT_URL => $urlApiLogin,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headersLogin
    ];
