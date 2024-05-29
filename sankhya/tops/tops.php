<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");

include_once("../Sankhya.php");

$pagina = "tops";
$titulo = "Tipo de Operação de Produtos - Sankhya";
$modulo = "sankhya";
$menu = "tops";

$erro = 0;
$msg = '';

$showEdit   = false;
$showList   = false;
$showDelete = false;

$sqlCondition = '';

$codigo                     = $_POST['codigo'] ?? '';
$tipoOperacao               = $_POST['tipoOperacao'] ?? '';
$produto                    = $_POST['produto'] ?? '';
$descricao                  = $_POST['descricao'] ?? '';
$tops_requisicao            = $_POST['tops_requisicao'] ?? '';
$tipoMovimentoRequisicao    = $_POST['tipoMovimentoRequisicao'] ?? 'J';
$naturezaOperacaoRequisicao = $_POST['naturezaOperacaoRequisicao'] ?? '';
$topsCompra                 = $_POST['topsCompra'] ?? '';
$tipoMovimentoCompra        = $_POST['tipoMovimentoCompra'] ?? 'O';


$operacao = 'L';  // I-insert, D-delete, U-update and L-list (defalt)

$operacoesPermitidas = "ECPREARM";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $showEdit = true;

    if (isset($_POST["btnUpdate"])) {
        $operacao = 'U';
        $sqlCondition = " where codigo = {$_POST['btnUpdate']}";
    } elseif (isset($_POST["btnInsert"])) {
        $operacao = 'I';
        $sqlCondition = " where codigo = -1";
    } elseif (isset($_POST["btnDelete"])) {
        $operacao = 'D';
        $showDelete = true;
        $showEdit = true;
        $sqlCondition = " where codigo = {$_POST['btnDelete']}";
    } elseif (isset($_POST["btnCancel"]) or isset($_POST["btnCancelDialog"])) {
        $operacao = 'L';
        $showDelete = false;
        $showEdit = false;
        $showList = true;
        $sqlCondition = "";
    } elseif (isset($_POST["btnSave"]) or isset($_POST["btnSaveAndNew"]) or isset($_POST["btnConfirmDialog"])) {
        $operacao = $_POST["btnSave"] ?? $_POST["btnConfirmDialog"] ?? $_POST["btnSaveAndNew"];

        if ($tipoOperacao == '') {
            $erro = 1;
            $msg = 'Tipo de operação inválida.';
        } elseif (strpos($operacoesPermitidas, $tipoOperacao) === false) {
            $erro = 2;
            $msg = 'Tipo de operação deve ser ECPR (Entrada Compra) ou EARM (Entrada para armazenamento).';
        } elseif ($produto <= 0) {
            $erro = 3;
            $msg = 'Código Sankhya do produto inválido.';
        } elseif (trim($descricao) == '') {
            $erro = 4;
            $msg = 'Descrição inválida.';
        } elseif ($tops_requisicao <= 0) {
            $erro = 5;
            $msg = 'TOP da requisição inválido.';
        } elseif ($tipoMovimentoRequisicao == '') {
            $erro = 6;
            $msg = 'Tipo de movimento da requisição inválido.';
        } elseif ($naturezaOperacaoRequisicao <= 0) {
            $erro = 7;
            $msg = 'Natureza da operação da requisição inválida.';
        } elseif ($topsCompra <= 0) {
            $erro = 8;
            $msg = 'TOP da compra inválido.';
        } elseif ($tipoMovimentoCompra == '') {
            $erro = 6;
            $msg = 'Tipo de movimento da compra inválido.';
        } else {
            switch ($operacao) {
                case 'I':
                    $sqlExecute = "insert 
                                        into tipo_operacao_produto 
                                                (tipo_operacao, produto_sankhya, tops_requisicao, descricao, tipo_movimento_requisicao, natureza_operacao_requisicao, 
                                                tops_compra, tipo_movimento_compra)
                                        values('$tipoOperacao',$produto,$tops_requisicao,'$descricao',
                                                '$tipoMovimentoRequisicao', '$naturezaOperacaoRequisicao', 
                                                '$topsCompra', '$tipoMovimentoCompra')";
                    break;

                case 'D':
                    $sqlExecute = "Delete 
                                     from tipo_operacao_produto
                                    where codigo = $codigo";
                    break;

                case 'U':
                    $sqlExecute = "update tipo_operacao_produto 
                                      set tipo_operacao   = '$tipoOperacao',
                                          produto_sankhya = '$produto',
                                          tops_requisicao = '$tops_requisicao',
                                          descricao = '$descricao',
                                          tipo_movimento_requisicao = '$tipoMovimentoRequisicao', 
                                          natureza_operacao_requisicao = '$naturezaOperacaoRequisicao', 
                                          tops_compra                  = '$topsCompra', 
                                          tipo_movimento_compra        ='$tipoMovimentoCompra'
                                    where codigo = $codigo";
                    break;
            }

            $resultUpdate = Sankhya::queryExecuteDB($sqlExecute);

            if ($resultUpdate['errorCode']) {
                $erro = $resultUpdate['errorCode'];
                if (strpos($resultUpdate['errorMessage'], "uplicate entry")) {
                    $msg = "Registro já cadastrado com este tipo de operação/produto.";
                } else {
                    $msg = $resultUpdate['errorMessage'];
                }
            } else {
                $erro = 0;
                $msg  = 'Registro atualizado com sucesso!';

                if (isset($_POST["btnSaveAndNew"])) {
                    $operacao = 'I';
                    $sqlCondition = " where codigo = -1";
                    $showEdit = true;
                } else {
                    $showEdit = false;
                    $showList = true;
                }
            }
        }
    } elseif (isset($_POST["btnCancel"])) {
        $showEdit = false;
        $showEdit = true;
    }
} else {
    $showList = true;
}

// Sempre mostra lista quando nenhuma view for setada
//$showList = !$showList and !$showEdit and !$showDelete;

$sqlTipoOperacao = "select * from tipo_operacao_produto $sqlCondition";

$resultSet = Sankhya::queryExecuteDB($sqlTipoOperacao);
$recorCount = count($resultSet['rows']);

if ($resultSet['errorCode']) {
    $erro = 1;
    $msg = $resultSet['errorMessage'];
} elseif (!$recorCount and $operacao == 'L') {
    $erro = 2;
    $msg = "Nenhum registro cadastrado.";
} elseif (!$erro) {
    $codigo                     = $resultSet['rows'][0][0] ?? '';
    $tipoOperacao               = $resultSet['rows'][0][1] ?? '';
    $produto                    = $resultSet['rows'][0][2] ?? '';
    $tops_requisicao            = $resultSet['rows'][0][3] ?? '';
    $descricao                  = $resultSet['rows'][0][4] ?? '';
    $tipoMovimentoRequisicao    = $resultSet['rows'][0][5] ?? '';
    $naturezaOperacaoRequisicao = $resultSet['rows'][0][6] ?? '';
    $topsCompra                 = $resultSet['rows'][0][7] ?? '';
    $tipoMovimentoCompra        = $resultSet['rows'][0][8] ?? '';
}

include_once("../../includes/head.php");

?>
<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/sankhya/Sankhya.css" />

</head>

<body>

    <div class="topo">
        <?php include("../../includes/topo.php"); ?>
    </div>

    <div class="menu">
        <?php include("../../includes/menu_cadastro.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_cadastro_config.php"); ?>
    </div>

    <div class="ct_auto">
        <div class="espacamento_15"></div>

        <div class="ct_topo_1">
            <div class="ct_titulo_1">
                <?php echo "$titulo"; ?>
            </div>
        </div>

        <div class="ct_topo_2 ">

            <div class="ct_subtitulo_left">
                <?php if ($erro) : ?>
                    <div style='color:#FF0000'><?= $msg ?></div>
                <?php endif; ?>

                <?php if (!$erro and $msg) : ?>
                    <div style='color:#0000FF'><?= $msg ?></div>
                <?php endif; ?>

                <?php if (!$erro and !$msg and !$showEdit and !$showDelete) : ?>
                    <div>
                        <span class="badge badge-success">
                            <?= $recorCount ?> registro(s)
                        </span>
                     </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="espacamento_30"></div>

        <div class="ct_relatorio">
            <div class="brg-flex brg-flex-center brg-gap-30">
                <section name="secPesquisar" <?php
                                                if (!$showList) {
                                                    echo "hidden";
                                                }
                                                ?>>

                    <div class="brg-flex-column brg-flex-right brg-gap-10">

                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="frmList">
                            <div class="brg-flex brg-flex-right" style="padding-bottom: 10px;">
                                <button class="botao_1" type="submit" value="<?= $operacao ?>" name="btnInsert">Incluir</button>
                            </div>

                            <table class='brg-Table' display: flex width=850px>
                                <thead>
                                    <th>Tipo Operação</th>
                                    <th>Produto Sankhya</th>
                                    <th>Descrição</th>
                                    <th>Ação</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultSet['rows'] as $record) : ?>
                                        <tr>
                                            <td style="width: 170px"><?= $record[1] == 'ECPR' ? 'ENTRADA-COMPRA' : 'ENTRADA-ARMAZENAMENTO'?></td>
                                            <td style="width: 130px"><?= $record[2] ?></td>
                                            <td style="width: 390px"><?= $record[4] ?></td>
                                            <td style="width: 90px" class="brg-center">
                                                <button type="submit" name="btnUpdate" value="<?= $record[0] ?>" style="padding: 0; border: 0;">
                                                    <img src=<?= "$servidor/$diretorio_servidor/imagens/botoes/editar.png" ?> style="height: 20px; padding: 0; border: 0;">
                                                </button>

                                                <button type="submit" name="btnDelete" value="<?= $record[0] ?>" style="padding: 0; border: 0;">
                                                    <img src=<?= "$servidor/$diretorio_servidor/imagens/botoes/excluir.png" ?> style="height: 20px; padding: 0; border: 0;">
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </form>
                    </div>


                </section>
            </div>

            <section name="secAlterar" style="width: auto" <?php if (!$showEdit) {echo "hidden";}?>>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" class="brg-form-center" method="post" id="frmEdit" style="width: 850px">
                    <div class="brg-flex">
                        <input hidden type="number" name="codigo" value="<?= $codigo ?>" readonly>

                        <!-- ###### CAMPOS DE ENTRADA ###### -->
                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="tipoOperacao">Tipo Operação</label>

                            <?php if ($operacao !== 'I') : ?>
                                    <select readonly="readonly" class="form_select" name="tipoOperacao" 
                                            id="tipoOperacao" tabindex='-1' aria-disabled='true'>
                            <?php endif; ?>

                            <?php if ($operacao == 'I') : ?>
                                     <select class="form_select" name="tipoOperacao" id="tipoOperacao">
                            <?php endif; ?>

                            <option value="ECPR" 
                                    <?php if ($tipoOperacao == 'ECPR') {
                                            echo 'Selected';
                                          } 
                                    ?>>
                                ENTRADA-COMPRA
                            </option>

                            <option value="EARM" 
                                    <?php if ($tipoOperacao == 'EARM') {
                                            echo 'Selected';
                                          } 
                                    ?>
                                >ENTRADA-ARMAZENAMENTO
                            </option>

                            </select>
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="produto">Produto Sankhya</label>
                            <input <?php if ($operacao != 'I') {
                                             echo "readonly class='form_input brg-input-no-foco' ";
                                         } ?> 
                                         type="number" name="produto" id="produto" value="<?= $produto ?>">
                        </div>

                        <div class="brg-form-group" style="width: 410px">
                            <label class="form_rotulo" for="descricao">Descrição</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="text" name="descricao" id="descricao" maxlength="30" value="<?= $descricao ?>">
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="tops_requisicao">TOP Requisição</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="number" name="tops_requisicao" id="tops_requisicao" value="<?= $tops_requisicao ?>">
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="tipoMovimentoRequisicao">Tipo Movimento Requisição</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="text" name="tipoMovimentoRequisicao" id="tipoMovimentoRequisicao" maxlength="1" value="<?= $tipoMovimentoRequisicao ?>">
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="tops_requisicao">Natureza Operação Requisição</label>
                            <input <?php if ($operacao == 'D') 
                                            {echo "readonly";}
                                        ?> 
                                        class="form_input" type="number" name="naturezaOperacaoRequisicao" id="naturezaOperacaoRequisicao" value="<?= $naturezaOperacaoRequisicao ?>">
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                        </div>
                       
                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="topsCompra">TOP Compra</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="number" name="topsCompra" id="topsCompra" value="<?= $topsCompra ?>">
                        </div>

                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="tipoMovimentoCompra">Tipo Movimento Compra</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="text" name="tipoMovimentoCompra" id="tipoMovimentoCompra" maxlength="1" value="<?= $tipoMovimentoCompra ?>">
                        </div>                        

                    </div>
                    
                     <!-- ###### BOTÕES ###### -->
                    <div class="brg-flex brg-flex-left brg-gap-30" style="padding-top: 15px">
                        <?php if ($operacao != 'D') : ?>
                            <button class="brg-action-button" type="submit" value="<?= $operacao ?>" name="btnSave">Salvar</button>
                        <?php endif ?>

                        <?php if ($operacao == 'I') : ?>
                            <button class="brg-action-button" type="submit" value="<?= $operacao ?>" name="btnSaveAndNew">Salvar e Novo</button>
                        <?php endif ?>

                        <?php if ($operacao != 'D') : ?>
                            <button class="brg-action-button" type="submit" value="<?= $operacao ?>" name="btnCancel">Cancelar</button>
                        <?php endif ?>
                    </div>
                </form>
            </section>

        </div>

    </div>

    <dialog <?PHP
            if ($showDelete) {
                echo "open";
            }
            ?> id="dlgConfirmation" class="dialog">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="frmConfirmation">
            <input hidden type="number" name="codigo" value="<?= $codigo ?>" readonly>
            <input hidden type="text" name="tipoOperacao" value="<?= $tipoOperacao ?>" readonly>
            <input hidden type="number" name="produto" value="<?= $produto ?>" readonly>
            <input hidden type="number" name="tops_requisicao" value="<?= $tops_requisicao ?>" readonly>
            <input hidden type="text" name="descricao" value="<?= $descricao ?>" readonly>
            <input hidden type="text" name="tipoMovimentoRequisicao" value="<?= $tipoMovimentoRequisicao ?>" readonly>
            <input hidden type="number" name="naturezaOperacaoRequisicao" value="<?= $naturezaOperacaoRequisicao ?>" readonly>
            <input hidden type="number" name="topsCompra" value="<?= $topsCompra ?>" readonly>
            <input hidden type="text" name="tipoMovimentoCompra" value="<?= $tipoMovimentoCompra ?>" readonly>

            <h5>Confirma exclusão do registro? </h5>

            <p><?= $descricao ?></p>

            <div>
                <button type="submit" name="btnConfirmDialog" id="btnConfirmDialog" value="<?= $operacao ?>" class="botao_1">Confirmar</button>
                <button type="submit" name="btnCancelDialog" id="btnCancelDialog" value="<?= $operacao ?>" style="margin: 0 30px;" class="botao_1">Cancelar</button>
            </div>
        </form>

    </dialog>
</body>