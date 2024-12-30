<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");
include_once("../../sankhya/Sankhya.php");


$pagina = "filial_veiculos";
$titulo = "Cadastro de Veículos da Filial";
$modulo = "estoque";
$menu   = "filial_veiculos";

$erro = 0;
$msg = '';

$showEdit   = false;
$showList   = false;
$showDelete = false;

$sqlCondition = '';

$codigo   = $_POST['codigo'] ?? '';
$filial   = $_POST['filial'] ?? '';
$placa    = $_POST['placa'] ?? '';
$situacao = $_POST['situacao'] ?? 'ATV';
$idFilial = $_COOKIE['idFilial'];

$operacao = 'L';  // I-insert, D-delete, U-update and L-list (defalt)

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $showEdit = true;

    if (isset($_POST["btnUpdate"])) {
        $operacao = 'U';
        $sqlCondition = " and a.codigo = {$_POST['btnUpdate']}";
    } elseif (isset($_POST["btnInsert"])) {
        $operacao = 'I';
        $sqlCondition = " and a.placa = ''";
    } elseif (isset($_POST["btnDelete"])) {
        $operacao = 'D';
        $showDelete = true;
        $showEdit = true;
        $sqlCondition = " and a.codigo = {$_POST['btnDelete']}";
    } elseif (isset($_POST["btnCancel"]) or isset($_POST["btnCancelDialog"])) {
        $operacao = 'L';
        $showDelete = false;
        $showEdit = false;
        $showList = true;
        $sqlCondition = "";
    } elseif (isset($_POST["btnSave"]) or isset($_POST["btnSaveAndNew"]) or isset($_POST["btnConfirmDialog"])) {
        $operacao = $_POST["btnSave"] ?? $_POST["btnConfirmDialog"] ?? $_POST["btnSaveAndNew"];

        $placa = strtoupper(str_replace(' ', '', $placa));

        if (strlen($placa) < 7) {
            $erro = 1;
            $msg = 'Informe uma placa válida.';
        } else {
            switch ($operacao) {
                case 'I':
                    $sqlExecute = "insert 
                                     into filial_veiculo 
                                          (filial, placa, situacao)
                                   values($idFilial, '$placa', '$situacao')";
                    break;

                case 'D':
                    $sqlExecute = "Delete 
                                     from filial_veiculo
                                    where codigo = $codigo";
                    break;

                case 'U':
                    $sqlExecute = "update filial_veiculo 
                                      set situacao = '$situacao'
                                    where codigo   = $codigo";
                    break;
            }

            $resultUpdate = Sankhya::queryExecuteDB($sqlExecute);

            if ($resultUpdate['errorCode']) {
                $showDelete = false;
                $showEdit = false;
                $showList = true;
        
                $erro = $resultUpdate['errorCode'];
                if (strpos($resultUpdate['errorMessage'], "uplicate entry")) {
                    $msg = "Placa $placa já cadastrada para a filial $filial.";
                } else if (strpos($resultUpdate['errorMessage'], "foreign key")) {
                    $msg = "Placa $placa não pode ser excluída.";
                } else {
                    $msg = $resultUpdate['errorMessage'];
                }
            } else {
                $erro = 0;
                $msg  = 'Registro atualizado com sucesso!';

                if (isset($_POST["btnSaveAndNew"])) {
                    $operacao = 'I';
                    $sqlCondition = " and a.codigo = -1";
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

$sqlFilialVeiculo = "select *
                       from filial_veiculo a
                      where a.filial = $idFilial
                           $sqlCondition
                     order by placa";

$resultSet = Sankhya::queryExecuteDB($sqlFilialVeiculo);
$recorCount = count($resultSet['rows']);

if ($resultSet['errorCode']) {
    $erro = 1;
    $msg = $resultSet['errorMessage'];
} elseif (!$recorCount and $operacao == 'L') {
    $erro = 2;
    $msg = "Nenhum registro cadastrado.";
} elseif (!$erro) {
    $codigo   = $resultSet['rows'][0][0] ?? '';
    $idFilial = $resultSet['rows'][0][1] ?? '';
    $placa    = $resultSet['rows'][0][2] ?? '';
    $situacao = $resultSet['rows'][0][3] ?? '';
}

include_once("../../includes/head.php");

?>
<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/sankhya/Sankhya.css" />

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

                            <table class='brg-Table' display: flex width=650px>
                                <thead>
                                    <th>Placa do Veículo</th>
                                    <th>Situação</th>
                                    <th>Ação</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultSet['rows'] as $record) : ?>
                                        <tr>
                                            <td style="width: 150px"><?= $record[2] ?></td>
                                            <td style="width: 150px" class="brg-center"><span class="badge badge-<?= $record[3] == 'ATV' ? 'success' : 'warning' ?>"><?= $record[3] == 'ATV' ? 'Ativo' : 'Inativo' ?></span></td>

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

            <section name="secAlterar" style="width: auto" <?php if (!$showEdit) {
                                                                echo "hidden";
                                                            } ?>>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" class="brg-form-center" method="post" id="frmEdit" style="width: 850px">
                    <input hidden type="number" name="codigo" value="<?= $codigo ?>" readonly>

                    <div class="brg-flex">
                        <!-- ###### CAMPOS DE ENTRADA ###### -->
                        <div class="brg-form-group" style="width: 150px">
                            <label class="form_rotulo" for="placa">Placa do Veículo</label>
                            <input <?php
                                    if ($operacao != 'I') {
                                        echo "readonly";
                                    }
                                    ?> class="form_input" type="text" name="placa" id="placa" maxlength="7" value="<?= $placa ?>" oninput='this.value = this.value.toUpperCase()'>
                        </div>

                        <div class="brg-form-group" style="width: 150px">
                            <label class="form_rotulo" for="situacao">Situação</label>
                            <select class="form_select" name="situacao" id="situacao"
                                <?php
                                    if ($operacao == 'D') {
                                        echo "readonly='readonly' tabindex='-1' aria-disabled='true'";
                                    }
                                ?>>
                                <option value="ATV" <?= $situacao == 'ATV' ? 'selected' : '' ?>>Ativo</option>
                                <option value="INT" <?= $situacao == 'INT' ? 'selected' : '' ?>>Inativo</option>
                            </select>
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
            <input hidden type="text" name="placa" value="<?= $placa ?>" readonly>
            <input hidden type="text" name="situacao" value="<?= $situacao ?>" readonly>

            <h5>Confirma exclusão do registro? </h5>

            <p><?= $placa ?></p>

            <div>
                <button type="submit" name="btnConfirmDialog" id="btnConfirmDialog" value="<?= $operacao ?>" class="botao_1">Confirmar</button>
                <button type="submit" name="btnCancelDialog" id="btnCancelDialog" value="<?= $operacao ?>" style="margin: 0 30px;" class="botao_1">Cancelar</button>
            </div>
        </form>
    </dialog>

    <div id="rodape_geral">
		<?php include('../../includes/rodape.php'); ?>
	</div>

</body>