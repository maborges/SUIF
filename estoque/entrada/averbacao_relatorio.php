<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../sankhya/Sankhya.php");

$pagina = "averbacao_relatorio";
$titulo = "Relatório de Averbação";
$modulo = "estoque";
$menu   = "averbacao_relatorio";

$idFilial  = $_COOKIE['idFilial'];
$nomeFilial = '';
$cnpjEmpresa = '';
$startDate = $_POST['startDate'] ?? date('Y-m-d');
$endDate   = $_POST['endDate'] ?? date('Y-m-d');

$paginaAtual    = $_SERVER['PHP_SELF'];
$paginaPlanilha = "$servidor/$diretorio_servidor/estoque/entrada/averbacao_planilha.php";

$error = false;
$msg   = '';

$dataSetVeiculos = array('rows' => []);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($startDate > $endDate) {
        $error = true;
        $msg   = "Informe um período válido.";
    }
}

$dataSetFiliais = getFilial($idFilial);

if ($dataSetFiliais['errorCode']) {
    $error = true;
    $msg   = "Erro ao tentar localizar a filial $idFilial. <br> 
              {$dataSetFiliais['errorMessage']}";
} else if (!$dataSetFiliais['count']) {
    $error = true;
    $msg   = "Filial $idFilial não cadastrada";
} else {
    $cnpjEmpresa = $dataSetFiliais['rows'][0][0];
    $nomeFilial  = $dataSetFiliais['rows'][0][3];
}

if (!$error) {
    $dataSetVeiculos = getVeiculos($idFilial, $startDate, $endDate);
    $recorCount = $dataSetVeiculos['count'];

    if ($dataSetVeiculos['errorCode']) {
        $error = true;
        $msg   = "Erro ao tentar dados dos veículos/placas. <br> 
              {$dataSetVeiculos['errorMessage']}";
    } else if (!$dataSetVeiculos['count']) {
        $msg = "Nenhum veículo/placa foi encontrado para o período informado. <br>";
    }

}

if (!$error) {
    $resultSetValorTotalVeiculos = somaVeiculos($idFilial, $startDate, $endDate);
}

function getFilial($idFilial)
{
    $sql = "select if(a.cpf_cnpj is not null, a.cpf_cnpj, b.cpf_cnpj), 
                   a.numero_apolice, 
                   a.uf, 
                   b.razao_social
              from filiais a, 
                   configuracoes b
             where a.codigo = $idFilial";

    $result = Sankhya::queryExecuteDB($sql);
    return $result;
}

function getVeiculos($idFilial, $startDate, $endDate)
{
    $sql =  "select a.numero_apolice 'Apolice', 
                    '' SubGrupo,
                    b.serie_nf Serie,
                    b.numero_nf Documento,
                    DATE_FORMAT(c.data,'%d/%m/%Y') 'DataSaida',
                    c.placa_veiculo 'Veiculo',
                    'TERRESTRE' as 'MeioTransporte',
                    d.estado as 'EstadoOrigem',
                    a.uf as 'EstadoDestino',
                    '' as 'PercursoUrano',
                    b.valor_total 'ValorSegurado',
                    c.produto 'Mercadoria',
                    '' Despesas,
                    '' Frete
                from configuracoes x,
                    filiais a,
                    nota_fiscal_entrada b,
                    estoque c,
                    cadastro_pessoa d,
                    filial_veiculo e,
                    averbacao_vigencia f
                where a.codigo = $idFilial
                and b.estado_registro = 'ATIVO'
                and c.numero_romaneio = b.codigo_romaneio  
                and c.estado_registro != 'EXCLUIDO'
                and c.data between '$startDate'
                               and '$endDate'
                and d.codigo = b.codigo_fornecedor 
                and e.filial = a.codigo 
                and e.placa  = replace(c.placa_veiculo,' ', '')
                and e.situacao = 'ATV'
                and f.filial_veiculo = e.codigo
                and f.situacao = 'ATV'
                and c.data >= f.data_inicio 
                and c.data <= f.data_fim";

    $result = Sankhya::queryExecuteDB($sql);
    return $result;
}

function somaVeiculos($idFilial, $startDate, $endDate)
{
    $sql =  "select sum(b.valor_total)
                from configuracoes x,
                    filiais a,
                    nota_fiscal_entrada b,
                    estoque c,
                    cadastro_pessoa d,
                    filial_veiculo e,
                    averbacao_vigencia f
                where a.codigo = $idFilial
                and b.estado_registro = 'ATIVO'
                and c.numero_romaneio = b.codigo_romaneio  
                and c.estado_registro != 'EXCLUIDO'
                and c.data between '$startDate'
                               and '$endDate'
                and d.codigo_pessoa = c.cod_produto 
                and e.filial = a.codigo 
                and e.placa  = replace(c.placa_veiculo,' ', '')
                and e.situacao = 'ATV'
                and f.filial_veiculo = e.codigo
                and f.situacao = 'ATV'
                and c.data >= f.data_inicio 
                and c.data <= f.data_fim";

    $result = Sankhya::queryExecuteDB($sql);
    return $result;
}

include_once("../../includes/head.php");

?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/sankhya/Sankhya.css" />
<script>
    function carregaPagina(pagina) {
        document.getElementById('frmRelatorio').action = pagina;
    }
</script>


</head>

<body>

    <div class="topo">
        <?php include("../../includes/topo.php"); ?>
    </div>

    <!-- ====== MENU ================================================================================================== -->
    <div class="menu">
        <?php include("../../includes/menu_estoque.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_estoque_entrada.php"); ?>
    </div>

    <div class="ct_auto">
        <div class="espacamento_15"></div>

        <div class="ct_topo_1">
            <div class="ct_titulo_1">
                <?= $titulo ?>
            </div>
        </div>

        <div class="ct_topo_2 ">
            <div class="ct_subtitulo_left">
                <?php if ($error) : ?>
                    <div style='color:#FF0000'><?= $msg ?></div>
                <?php endif; ?>

                <?php if (!$error and $msg) : ?>
                    <div style='color:#0000FF'><?= $msg ?></div>
                <?php endif; ?>

                <div style='display: flex; flex-direction: row; justify-content: space-between;'>
                    <?php if (!$error and !$msg) : ?>
                        <div>
                            <span class="badge badge-success">
                                <?= $recorCount ?> registro(s)
                            </span>
                        </div>

                        <div>
                            <span class="badge badge-secondary">
                                Total: R$ <?= number_format($resultSetValorTotalVeiculos['rows'][0][0], 2, ',', '.') ?>
                            </span>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="espacamento_30"></div>

        <div class="ct_relatorio">
            <div class="brg-flex brg-flex-center brg-gap-30">
                <div class="brg-flex-column brg-flex-right brg-gap-10">

                    <form method="post" id="frmRelatorio">
                        <input type='hidden' name='idFilial' value=<?= $idFilial ?>>
                        <input type='hidden' name='startDate' value=<?= "'$startDate'" ?>>
                        <input type='hidden' name='endDate' value=<?= "'$endDate'" ?>>
                        <input type='hidden' name='cnpjEmpresa' value=<?= "'$cnpjEmpresa'" ?>>
                        <input type='hidden' name='nomeFilial' value=<?= "'$nomeFilial'" ?>>


                        <div class="pqa" style='width:1200px'>
                            <div class="pqa_caixa">
                                <div class="pqa_rotulo">
                                    Data Inicial:
                                </div>

                                <div class="pqa_campo">
                                    <input type="date" name="startDate" class="pqa_input" id="startDate" value=<?= $startDate ?>>
                                </div>
                            </div>

                            <div class="pqa_caixa">
                                <div class="pqa_rotulo">
                                    Data Final:
                                </div>

                                <div class="pqa_campo">
                                    <input type="date" name="endDate" class="pqa_input" id="endDate" value=<?= $endDate ?>>
                                </div>
                            </div>

                            <div class="pqa_caixa">
                                <div class="pqa_rotulo"></div>
                                <div class="brg-flex brg-flex-right" style="padding-left: 20px;">
                                    <button class="botao_1" type="submit" onclick=<?= "carregaPagina('$paginaAtual')" ?> name="Buscar">Buscar</button>
                                </div>
                            </div>

                            <div class="pqa_caixa">
                                <div class="pqa_rotulo"></div>
                                <div class="brg-flex brg-flex-right" style="padding-left: 20px;">
                                    <button class="botao_1" type="submit" onclick=<?= "carregaPagina('$paginaPlanilha')" ?> name="gerarPlanilha">Gerar Planilha</button>
                                </div>
                            </div>
                        </div>

                        <div class="espacamento_30">
                        </div>

                        <table class='brg-Table' display: flex; width=1200px>
                            <thead>
                                <th>Apólice</th>
                                <th>SubGrupo</th>
                                <th>Série</th>
                                <th>Documento</th>
                                <th>Data de Saída</th>
                                <th>Veículo</th>
                                <th>Meio de Transporte</th>
                                <th>Estado de Origem</th>
                                <th>Estado de Destino</th>
                                <th>Percurso Urbano</th>
                                <th>Valor Segurado</th>
                                <th>Mercadoria</th>
                                <th>Despesa</th>
                                <th>Frete</th>
                            </thead>
                            <tbody>
                                <?php foreach ($dataSetVeiculos['rows'] as $record) : ?>
                                    <tr>
                                        <td><?= $dataSetFiliais['rows'][0][1] ?></td>
                                        <td><?= $record[1] ?></td>
                                        <td><?= $record[2] ?></td>
                                        <td><?= $record[3] ?></td>
                                        <td><?= $record[4] ?></td>
                                        <td><?= $record[5] ?></td>
                                        <td><?= $record[6] ?></td>
                                        <td><?= $dataSetFiliais['rows'][0][2] ?></td>
                                        <td><?= $record[8] ?></td>
                                        <td><?= $record[9] ?></td>
                                        <td style='text-align: right'> <?= number_format($record[10], 2, ',', '.') ?> </td>
                                        <td><?= $record[11] ?></td>
                                        <td><?= $record[12] ?></td>
                                        <td><?= $record[13] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>    
</body>