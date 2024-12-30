<?php
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
$pagina = 'entrada_cadastro_3';
$titulo = 'Entrada de Mercadoria';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ========================================================================================================


// ====== CONTADOR NÚMERO ROMANEIO ==========================================================================
$busca_numero_romaneio = mysqli_query($conexao, "SELECT * FROM configuracoes");
$aux_bnr = mysqli_fetch_row($busca_numero_romaneio);
$numero_romaneio = $aux_bnr[8];

$contador_num_romaneio = $numero_romaneio + 1;
$altera_contador = mysqli_query($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_num_romaneio'");
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$aux_cod_produtor = $_POST["aux_cod_produtor"];
$num_romaneio_manual = $_POST["num_romaneio_manual"];
$cod_produto = $_POST["cod_produto"] ?? '';
$fornecedor = $_POST["fornecedor"] ?? '';
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows($busca_produto);

$produto_print = $aux_bp[1] ?? '';
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// =============================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA ROMANEIO MANUAL - COMPRAS ======================================================================
$busca_rmc = mysqli_query($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND num_romaneio_manual='$num_romaneio_manual'");
$linhas_rmc = mysqli_num_rows($busca_rmc);
$aux_rmc = mysqli_fetch_row($busca_rmc);

$numero_rmc = $aux_rmc[1];
// =============================================================================================================


// ====== BUSCA ROMANEIO MANUAL - ESTOQUE  ============================================================================
$busca_rme = mysqli_query($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND num_romaneio_manual='$num_romaneio_manual'");
$linhas_rme = mysqli_num_rows($busca_rme);
$aux_rme = mysqli_fetch_row($busca_rme);

$numero_rme = $aux_rme[1];
// ==================================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
$produto_print_titulo = '';
if ($num_romaneio_manual == "") {
    $erro = 1;
    $msg_erro = "* Informe o n&uacute;mero do romaneio manual.";
} elseif ($cod_produto == "") {
    $erro = 2;
    $msg_erro = "* Selecione um produto.";
} elseif ($linhas_rmc >= 1) {
    $erro = 3;
    $msg_erro = "* J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio manual. (N&ordm; Registro: $numero_rmc)";
} elseif ($linhas_rme >= 1) {
    $erro = 4;
    $msg_erro = "* J&aacute; existe um romaneio da balan&ccedil;a para esta entrada. (N&ordm; Romaneio: $numero_rme)";
} else {
    $erro = 0;
    $msg_erro = "";
    $produto_print_titulo = " - $produto_print_2";
}
// =================================================================================================================


// ==================================================================================================================
include('../../includes/head.php');
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
    <?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
    <?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body onload="javascript:foco('busca');">

    <!-- ====== TOPO ================================================================================================== -->
    <div class="topo">
        <?php include("../../includes/topo.php"); ?>
    </div>




    <!-- ====== MENU ================================================================================================== -->
    <div class="menu">
        <?php include("../../includes/menu_compras.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_compras_ficha_produtor.php"); ?>
    </div>


    <!-- =============================================   C E N T R O   =============================================== -->
    <div id="centro_geral">
        <div id="centro" style="height:440px; width:950px; border:0px solid #000; margin:auto">
            <form name="popup" action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_enviar_3.php" method="post">
                <input type="hidden" name="unidade" value="KG" />

                <div style="width:950px; height:15px; float:left; border:0px solid #000"></div>
                <!-- ============================================================================================================= -->



                <!-- ============================================================================================================= -->
                <div style="width:950px; height:30px; float:left; border:0px solid #000">
                    <div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:140px">
                        Entrada <?php echo "$produto_print_titulo"; ?>
                    </div>
                </div>

                <div style="width:950px; height:10px; float:left; border:0px solid #000"></div>
                <!-- ============================================================================================================= -->


                <!-- ============================================================================================================= -->
                <div style="width:950px; height:20px; float:left; border:0px solid #000">
                    <div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:140px">
                        <?php echo "$msg_erro"; ?>
                    </div>
                </div>

                <div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
                <!-- ============================================================================================================= -->


                <!-- ====================================================================================== -->
                <div style="width:140px; height:235px; border:0px solid #000; float:left">
                    <!-- IMAGEM -->
                </div>


                <!-- ====================================================================================== -->
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&ordm; Romaneio Manual:
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:600px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:595px; height:5px; border:0px solid #000"></div>Fornecedor (F2):
                </div>





                <!-- =========================================  CODIGO ====================================== -->
                <div id="tabela_2" style="width:150px; border:0px solid #000">
                    <input type="text" name="numero_romaneio_aux" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$num_romaneio_manual"; ?>" disabled="disabled" />
                    <input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />
                    <input type="hidden" name="num_romaneio_manual" value="<?php echo "$num_romaneio_manual"; ?>" />
                    <input type="hidden" name="pagina" value="<?php echo "$pagina"; ?>" />
                    <input type="hidden" name="cod_produto" value="<?php echo "$cod_produto"; ?>" />

                </div>

                <!-- =========================================  FORNECEDOR ====================================== -->
                <div id="tabela_1" style="width:30px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:525px; border:0px solid #000">

                    <script type="text/javascript">
                        function abrir(programa, janela) {
                            if (janela == "") janela = "janela";
                            window.open(programa, janela, 'height=270,width=700');
                        }
                    </script>
                    <script type="text/javascript" src="fornecedor_funcao.js"></script>

                    <!-- ========================================================================================================== -->
                    <div id="centro" style="float:left; border:0px solid #000; margin-top:3px">
                        <img src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar fornecedor" />
                    </div>

                    <div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">
                        &#160;

                        <!-- ========================================================================================================== -->
                        <script type="text/javascript">
                            document.onkeyup = function(e) {
                                if (e.which == 113) {
                                    //Pressionou F2, aqui vai a função para esta tecla.
                                    //alert(tecla F2);
                                    var aux_f2 = document.popup.fornecedor.value;
                                    javascript: foco('busca');
                                    javascript: abrir('busca_pessoa_popup.php');
                                    //javascript:buscarNoticias(aux_f2);
                                }
                            }
                        </script>

                        <!-- ========================================================================================================== -->
                        <input id="busca" type="text" name="fornecedor" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; width:50px; font-size:12px; text-align:center" value="<?= $fornecedor ?>" />
                    </div>


                    <div id="tabela_1" style="width:30px; border:0px solid #000"></div>
                    <div id="resultado" style="width:368px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


                </div>




                <!-- ====================================================================================== -->
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Peso Liquido (Kg):
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Desconto (Kg):
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Produto:
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Tipo:
                </div>

                <!-- =========================================  PESO INICIAL ====================================== -->
                <div id="tabela_2" style="width:150px; border:0px solid #000">
                    <input type="text" name="peso_inicial" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" />
                </div>

                <!-- =========================================  DESCONTO ====================================== -->
                <div id="tabela_1" style="width:30px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:150px; border:0px solid #000">
                    <input type="text" name="desconto" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="0" />
                </div>

                <!-- ========================================= PRODUTO  ====================================== -->
                <div id="tabela_1" style="width:30px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:150px; border:0px solid #000">
                    <input type="text" name="produto" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$produto_print"; ?>" disabled="disabled" />

                </div>


                <!-- ========================================= TIPO  ====================================== -->
                <div id="tabela_1" style="width:30px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:240px; border:0px solid #000">
                    <select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
                    <option></option>
                    <?php
                    $busca_tipo_produto = mysqli_query($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
                    $linhas_tipo_produto = mysqli_num_rows($busca_tipo_produto);

                    for ($t = 1; $t <= $linhas_tipo_produto; $t++) {
                        $aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);

                        if ($aux_tipo_produto[0] == $cod_tipo) {
                            echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
                        } else {
                            echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";
                        }
                    }
                    ?>
                    </select>
                </div>




                <!-- ============================================================================================ -->
                <div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:
                </div>

                <!-- =========================================  OBSERVAÇÃO ====================================== -->
                <div id="tabela_2" style="width:730px; border:0px solid #000">
                    <input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:689px" />
                </div>


                <!-- ====================================================================================== -->
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!--Placa do Ve&iacute;culo:-->
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!--Placa do Ve&iacute;culo:-->
                </div>

                <div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
                <div id="tabela_2" style="width:430px; height:19px; border:0px solid #000">
                    <div id="espaco_1" style="width:425px; height:5px; border:0px solid #000"></div><!--Motorista:-->
                </div>



                <!-- =========================================  MOVIMENTAÇÃO DE ESTOQUE ====================================== -->
                <div id="tabela_2" style="width:300px; border:0px solid #000">
                    <input type='checkbox' name='movimenta_estoque' value='SIM'>Movimenta&ccedil;&atilde;o - Entrada no estoque.
                </div>


                <!-- =============================================================================================== -->

                <div style="display: flex;  width:930px; justify-content: center; border:0px solid #000">
                    <?php if (!($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4)) : ?>
                        <button type='submit' class='botao_2' style='margin-right:25px; width:120px'>Confirmar</button>
                    <?php endif; ?>

                    <a href="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_seleciona_3.php">
                        <button type='submit' class='botao_2' style='width:120px'>Voltar</button></a>
                </div>



            </form>






        </div>
    </div>




    <!-- =============================================   R O D A P É   =============================================== -->
    <div id="rodape_geral">
        <?php include('../../includes/rodape.php'); ?>
    </div>

    <!-- =============================================   F  I  M   =================================================== -->
    <?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>