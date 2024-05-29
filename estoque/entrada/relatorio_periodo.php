<?php
include("../../includes/config.php");
include("../../includes/valida_cookies.php");
$pagina = "relatorio_periodo";
$titulo = "Romaneios de Entrada";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================

// ====== BUSCAS =================================================================================================
include("include_comando.php");
// ================================================================================================================


// ================================================================================================================
include("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
    <?php echo $titulo; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
    <?php include("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body onload="javascript:foco('ok');">


    <!-- ====== TOPO ================================================================================================== -->
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


    <!-- ====== CENTRO ================================================================================================= -->
    <div class="ct_auto">


        <!-- ============================================================================================================= -->
        <div class="espacamento" style="height:15px"></div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="ct_topo_1">
            <div class="ct_titulo_1">
                <?php echo $titulo; ?>
            </div>

            <div class="ct_subtitulo_right">
                <?php
                if ($linha_romaneio == 1) {
                    echo "$linha_romaneio Romaneio";
                } elseif ($linha_romaneio > 1) {
                    echo "$linha_romaneio Romaneios";
                } else {
                    echo "";
                }
                ?>
            </div>
        </div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="ct_topo_2">
            <div class="ct_subtitulo_left">
                <?php echo $msg; ?>
            </div>

            <div class="ct_subtitulo_right">
                <?php echo $soma_romaneio_print; ?>
            </div>
        </div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="pqa">


            <!-- ======= ESPAÇAMENTO ============================================================================================ -->
            <div class="pqa_caixa">
                <form action="<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorio_periodo.php" method="post" />
                <input type="hidden" name="botao" value="BUSCAR" />
                <input type="hidden" name="fornecedor_pesquisa" value="<?php echo "$fornecedor_pesquisa"; ?>" />
                <input type="hidden" name="nome_fornecedor" value="<?php echo "$nome_fornecedor"; ?>" />
            </div>
            <!-- ================================================================================================================ -->

            <!-- ================================================================================================================ -->
            <div class="pqa_caixa">
                <div class="pqa_rotulo">
                    Data Inicial:
                </div>

                <div class="pqa_campo">
                    <input type="text" name="data_inicial_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" id="calendario" value="<?php echo "$data_inicial_br"; ?>" style="width:100px" />
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= DATA FINAL ============================================================================================ -->
            <div class="pqa_caixa">
                <div class="pqa_rotulo">
                    Data Final:
                </div>

                <div class="pqa_campo">
                    <input type="text" name="data_final_busca" class="pqa_input" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" value="<?php echo "$data_final_br"; ?>" style="width:100px" />
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= PRODUTO =========================================================================================== -->
            <div class="pqa_caixa">
                <div class="pqa_rotulo">
                    Produto:
                </div>

                <div class="pqa_campo">
                    <select name="cod_produto_busca" class="pqa_select" style="width:190px" />
                    <?php
                    include("../../includes/cadastro_produto.php");

                    if ($cod_produto_busca == "GERAL") {
                        echo "<option selected='selected' value='GERAL'>(TODOS)</option>";
                    } else {
                        echo "<option value='GERAL'>(TODOS)</option>";
                    }

                    for ($i = 0; $i <= count($cadastro_produto); $i++) {
                        if ($cadastro_produto[$i]["codigo"] == $cod_produto_busca) {
                            echo "<option selected='selected' value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";
                        } else {
                            echo "<option value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= BOTAO ================================================================================================== -->
            <div class="pqa_caixa">
                <div class="pqa_rotulo">
                </div>

                <div class="pqa_campo">
                    <button type='submit' class='botao_1' style='float:left'>Buscar</button>
                    </form>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= BOTAO ================================================================================================== -->
            <div class="pqa_caixa" style="float:right">
                <div class="pqa_rotulo">
                </div>

                <div class="pqa_campo" style="margin-left:0px; margin-right:30px">
                    <?php
                    if ($linha_romaneio >= 1) {
                        echo "
                            <form action='$servidor/$diretorio_servidor/estoque/entrada/relatorio_selec_fornecedor.php' method='post' />
                            <input type='hidden' name='botao' value='BUSCAR'>
                            <input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
                            <input type='hidden' name='data_final_busca' value='$data_final_br'>
                            <input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
                            <input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
                            <input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
                            <button type='submit' class='botao_1' style='float:right'>Filtrar por Fornecedor</button>
                            </form>";
                    }
                    ?>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= BOTAO ================================================================================================== -->
            <div class="pqa_caixa" style="float:right">
                <div class="pqa_rotulo">
                </div>

                <div class="pqa_campo" style="margin-left:0px; margin-right:30px">
                    <?php
                    if ($linha_romaneio >= 1) {
                        echo "
                            <form action='$servidor/$diretorio_servidor/estoque/entrada/buscar_romaneio.php' method='post' />
                            <button type='submit' class='botao_1' style='float:right'>Buscar por N&uacute;mero</button>
                            </form>";
                    }
                    ?>
                </div>
            </div>
            <!-- ================================================================================================================ -->


        </div>
        <!-- ====== FIM DIV PQA ============================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="espacamento" style="height:5px"></div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <?php include("include_totalizador.php"); ?>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <?php include("include_relatorio.php"); ?>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="espacamento" style="height:30px"></div>
        <!-- ============================================================================================================= -->


    </div>
    <!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


    <!-- ============================================================================================================= -->
    <div class="espacamento" style="height:30px"></div>
    <!-- ============================================================================================================= -->


    </div>
    <!-- ====== FIM DIV CT ========================================================================================= -->



    <!-- ====== RODAPÉ =============================================================================================== -->
    <div class="rdp_1">
        <?php
        if ($linha_romaneio >= 1) {
            include("../../includes/rodape.php");
        }
        ?>
    </div>


    <!-- ====== FIM ================================================================================================== -->
    <?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>