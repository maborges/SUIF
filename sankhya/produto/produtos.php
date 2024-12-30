<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");

include_once("../Sankhya.php");

$pagina = "produtos";
$titulo = "Informa Código Sankhya dos Produtos";
$modulo = "sankhya";
$menu = "cadastro_sankhya";

$erro = 0;
$msg = '';

$showEdit = false;
$sqlCondition = '';

$codigo    = $_POST['codigo'] ?? '';
$idSankhya = $_POST['idSankhya'] ?? '';
$descricao = $_POST['descricao'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnLinha"])) {
        $showEdit = true;
        $sqlCondition = " where codigo = {$_POST['btnLinha']}";
    } elseif (isset($_POST["btnSalvar"])) {
        if ($_POST['idSankhya'] <= 0) {
            $erro = 1;
            $msg = 'Informe o código Sankhya do produto!';
            $showEdit = true;
        } else {
            $sqlUpdate = "update cadastro_produto set id_sankhya = {$_POST['idSankhya']} where codigo = {$_POST['codigo']}";
            $resultUpdate = Sankhya::queryExecuteDB($sqlUpdate);

            if ($resultUpdate['errorCode']) {
                $erro = $resultUpdate['errorCode'];
                $msg = $resultUpdate['errorMessage'];
            } else {
                $erro = 0;
                $msg  = 'Produto alterado com sucesso!';
                $showEdit = false;
            }
        }
    } elseif (isset($_POST["btnCancelar"])) {
        $showEdit = false;
    }
    $_POST["btnLinha"]    = '0';
}

$sqlProduto = "select codigo, id_sankhya, descricao from cadastro_produto $sqlCondition";
$resultSet = Sankhya::queryExecuteDB($sqlProduto);
$recorCount = count($resultSet['rows']);

if ($resultSet['errorCode']) {
    $erro = 1;
    $msg = $resultSet['errorMessage'];
} elseif (!$recorCount) {
    $erro = 2;
    $msg = "Nenhum produto encontrado.";
} elseif ($recorCount == 1) {
    $codigo    = $resultSet['rows'][0][0];
    $idSankhya = $resultSet['rows'][0][1];
    $descricao = $resultSet['rows'][0][2];
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
        <?php include("../../includes/menu_sankhya.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_sankhya_cadastro.php"); ?>
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
                if (!$erro and !$msg and $showEdit) {
                    echo "Alterando produto " . $resultSet['rows'][0][2];
                } elseif (!$erro and !$msg and !$showEdit) {
                    echo "Total de produtos cadastrados: " . $recorCount;
                }
                ?>

            </div>
        </div>

        <div class="espacamento_30"></div>


        <div class="ct_relatorio">

            <section <?php
                        if ($showEdit) {
                            echo "hidden";
                        }
                        ?> name="secPesquisar">

                <form action="#" method="post" id="frmFilial">
                    <table class='brg-Table' display: flex width=700px>
                        <thead>
                            <th>Código</th>
                            <th>Sankhya</th>
                            <th>Descrição</th>
                            <th>Editar</th>
                        </thead>
                        <tbody>
                            <?php foreach ($resultSet['rows'] as $record) : ?>
                                <tr>
                                    <td><?= $record[0] ?></td>
                                    <td><?= $record[1] ?></td>
                                    <td><?= $record[2] ?></td>
                                    <td class="brg-center">
                                        <button type="submit" name="btnLinha" value=<?= $record[0] ?> formaction="#" style="padding: 0; border: 0;">
                                            <img src=<?= "$servidor/$diretorio_servidor/imagens/botoes/editar.png" ?> style="height: 20px; padding: 0; border: 0;">
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </form>

            </section>

            <section <?PHP
                        if (!$showEdit) {
                            echo "hidden";
                        }
                        ?> name="secAlterar" style="width: auto">

                <form action="#" class="brg-form-center" method="post" id="frmEdicao" style="width: 700px">
                    <div class="brg-flex">
                        <div class="brg-form-group" style="width: 95px">
                            <label class="form_rotulo" for="codigo">Código</label>
                            <input class="form_input brg-input-no-foco" type="text" name="codigo" value="<?= $codigo ?>" readonly>
                        </div>

                        <div class="brg-form-group" style="width: 95px">
                            <label class="form_rotulo" for="idSankhya">Sankhya</label>
                            <input class="form_input" type="text" name="idSankhya" value="<?= $idSankhya ?>">
                        </div>

                        <div class="brg-form-group" style="width: 480px">
                            <label class="form_rotulo" for="nome">Nome</label>
                            <input class="form_input brg-input-no-foco" type="text" name="descricao" value="<?= $descricao ?>" readonly>
                        </div>
                    </div>

                    <div class="brg-flex brg-flex-center brg-gap-30" style="padding-top: 25px">
                        <button class="brg-action-button" type="submit" value="1" name="btnSalvar">Salvar</button>
                        <button class="brg-action-button" type="submit" value="1" name="btnCancelar">Cancelar</button>
                    </div>
                </form>
            </section>

        </div>

    </div>
</body>