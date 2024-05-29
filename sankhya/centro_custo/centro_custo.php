<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");

include_once("../Sankhya.php");

$pagina = "centro_custo";
$titulo = "Centro de Resultado - Sankhya";
$modulo = "sankhya";
$menu = "centro_custo"; 

$erro = 0;
$msg = '';

$showEdit   = false;
$showList   = false;
$showDelete = false;

$sqlCondition = '';

$codigo      = $_POST['codigo'] ?? '';
$filial      = $_POST['filial'] ?? '';
$produto     = $_POST['produto'] ?? '';
$centroCusto = $_POST['centroCusto'] ?? '';
$nomeFilial  = $_POST['nomeFilial'] ?? '';
$nomeProduto = $_POST['nomeProduto'] ?? '';

$operacao = 'L';  // I-insert, D-delete, U-update and L-list (defalt)

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $showEdit = true;

    if (isset($_POST["btnUpdate"])) {
        $operacao = 'U';
        $sqlCondition = " where a.codigo = {$_POST['btnUpdate']}";
    } elseif (isset($_POST["btnInsert"])) {
        $operacao = 'I';
        $sqlCondition = " where a.codigo = -1";
    } elseif (isset($_POST["btnDelete"])) {
        $operacao = 'D';
        $showDelete = true;
        $showEdit = true;
        $sqlCondition = " where a.codigo = {$_POST['btnDelete']}";
    } elseif (isset($_POST["btnCancel"]) or isset($_POST["btnCancelDialog"])) {
        $operacao = 'L';
        $showDelete = false;
        $showEdit = false;
        $showList = true;
        $sqlCondition = "";
    } elseif (isset($_POST["btnSave"]) or isset($_POST["btnSaveAndNew"]) or isset($_POST["btnConfirmDialog"])) {
        $operacao = $_POST["btnSave"] ?? $_POST["btnConfirmDialog"] ?? $_POST["btnSaveAndNew"];

        if ($filial == '') {
            $erro = 1;
            $msg = 'Filial inválida.';
        } elseif ($produto <= 0) {
            $erro = 2;
            $msg = 'Produto inválido.';
        } elseif ($centroCusto <= 0) {
            $erro = 5;
            $msg = 'Centro de Resultado inválido.';
        } else {
            switch ($operacao) {
                case 'I':
                    $sqlExecute = "insert 
                                     into centro_custo_sankhya 
                                          (filial, produto, centro_custo)
                                   values($filial, $produto, '$centroCusto')";
                    break;

                case 'D':
                    $sqlExecute = "Delete 
                                     from centro_custo_sankhya
                                    where codigo = $codigo";
                    break;

                case 'U':
                    $sqlExecute = "update centro_custo_sankhya 
                                      set filial       = $filial,
                                          produto      = $produto,
                                          centro_custo = '$centroCusto'
                                    where codigo = $codigo";
                    break;
            }

            $resultUpdate = Sankhya::queryExecuteDB($sqlExecute);

            if ($resultUpdate['errorCode']) {
                $erro = $resultUpdate['errorCode'];
                if (strpos($resultUpdate['errorMessage'], "uplicate entry")) {
                    $msg = "Centro de Resultado já cadastrado para filial/produto.";
                } else {
                    $msg = $resultUpdate['errorMessage'];
                }
            } else {
                $erro = 0;
                $msg  = 'Registro atualizado com sucesso!';

                if (isset($_POST["btnSaveAndNew"])) {
                    $operacao = 'I';
                    $sqlCondition = " where a.codigo = -1";
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

$sqlTipoOperacao = "select a.*, 
                           b.descricao, 
                           c.descricao
                      from centro_custo_sankhya a
                           inner join filiais b
                              on b.codigo = a.filial
                           inner join cadastro_produto c
                             on c.codigo = a.produto 
                        $sqlCondition";

$resultSet = Sankhya::queryExecuteDB($sqlTipoOperacao);
$recorCount = count($resultSet['rows']);

$sqlFiliais = 'select codigo, descricao from filiais';
$filiais = Sankhya::queryExecuteDB($sqlFiliais);

$sqlProdutos = 'select codigo, descricao from cadastro_produto';
$produtos = Sankhya::queryExecuteDB($sqlProdutos);

if ($resultSet['errorCode']) {
    $erro = 1;
    $msg = $resultSet['errorMessage'];
} elseif (!$recorCount and $operacao == 'L') {
    $erro = 2;
    $msg = "Nenhum registro cadastrado.";
} elseif (!$erro) {
    $codigo      = $resultSet['rows'][0][0] ?? '';
    $filial      = $resultSet['rows'][0][1] ?? '';
    $produto     = $resultSet['rows'][0][2] ?? '';
    $centroCusto = $resultSet['rows'][0][3] ?? '';
    $nomeFilial  = $resultSet['rows'][0][4] ?? '';
    $nomeProduto = $resultSet['rows'][0][5] ?? '';
    
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

                <?php

                if (!$erro and !$msg and !$showEdit and !$showDelete) {
                    echo "Total de registros cadastrados: " . $recorCount;
                }
                ?>

            </div>
        </div>

        <div class="espacamento_30"></div>

        <div class="ct_relatorio">
            <div class="brg-flex brg-flex-center brg-gap-30">
                <section name="secPesquisar" 
                    <?php
                        if (!$showList) {
                            echo "hidden";
                        }
                    ?>>

                    <div class="brg-flex-column brg-flex-right brg-gap-10">

                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="frmList">
                            <div class="brg-flex brg-flex-right" style="padding-bottom: 10px;">
                                <button class="botao_1" type="submit" value="<?= $operacao ?>" name="btnInsert">Incluir</button>
                            </div>

                            <table class='brg-Table' display: flex width=650px>
                                <thead>
                                    <th>Filial</th>
                                    <th>Produto SUIF</th>
                                    <th>Centro Resultado</th>
                                    <th>Ação</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultSet['rows'] as $record) : ?>
                                        <tr>
                                            <td style="width: 200px"><?= $record[4] ?></td>
                                            <td style="width: 250px"><?= $record[5] ?></td>
                                            <td style="width: 120px"><?= $record[3] ?></td>
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
                     <input hidden type="number" name="codigo" value="<?= $codigo ?>" readonly>

                    <div class="brg-flex">

                        <!-- ###### CAMPOS DE ENTRADA ###### -->
                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="filial">Filial</label>
                            <select class="form_select" name="filial" id="filial" 
                                <?php
                                    if ($operacao !== 'I') {
                                        echo "readonly='readonly' tabindex='-1' aria-disabled='true'";
                                    }
                                ?>>

                                <?php foreach($filiais['rows'] as $redcord) : ?>
                                    <option value=<?= $redcord[0] ?> <?= $redcord[0]==$filial ? 'selected' : '' ?>><?=$redcord[1]?></option>
                                <?php endforeach ?>  
                            </select>
                        </div>
                        
                        <div class="brg-form-group" style="width: 200px">
                            <label class="form_rotulo" for="produto">Produto SUIF</label>
                            <select class="form_select" name="produto" id="produto" 
                                <?php
                                    if ($operacao !== 'I') {
                                        echo "readonly='readonly' tabindex='-1' aria-disabled='true'";
                                    }
                                ?>>

                                <?php foreach($produtos['rows'] as $redcord) : ?>
                                    <option value=<?= $redcord[0] ?> <?= $redcord[0]==$produto ? 'selected' : '' ?>><?=$redcord[1]?></option>
                                <?php endforeach ?>  
                            </select>
                        </div>

                        <div class="brg-form-group" style="width: 410px">
                            <label class="form_rotulo" for="descricao">Centro de Resultado</label>
                            <input <?php
                                    if ($operacao == 'D') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="number" name="centroCusto" id="centroCusto" maxlength="10" value="<?= $centroCusto ?>">
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
            <input hidden type="number" name="filial" value="<?= $filial ?>" readonly>
            <input hidden type="number" name="produto" value="<?= $produto ?>" readonly>
            <input hidden type="number" name="centroCusto" value="<?= $centroCusto ?>" readonly>

            <h5>Confirma exclusão do registro? </h5>

            <p><?= $centroCusto ?></p>

            <div>
                <button type="submit" name="btnConfirmDialog" id="btnConfirmDialog" value="<?= $operacao ?>" class="botao_1">Confirmar</button>
                <button type="submit" name="btnCancelDialog" id="btnCancelDialog" value="<?= $operacao ?>" style="margin: 0 30px;" class="botao_1">Cancelar</button>
            </div>
        </form>

    </dialog>
</body>