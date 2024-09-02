<?php
include("../includes/config.php");
include("../includes/valida_cookies.php");
$pagina = "index_estoque";
$titulo = "Estoque";
$modulo = "estoque";
$menu = "estoque";
// ================================================================================================================


// ================================================================================================================
$data_hoje = date('Y-m-d', time());
$mes_atual = date("m", time());
$ano_atual = date("Y", time());
$meses = array("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

$mysql_filtro_data = "estoque.data='$data_hoje'";

$mysql_filial = '';

$botao = $_POST["botao"] ?? '';
$relatorio_conso = $_POST["relatorio_conso"] ?? '';
// ================================================================================================================

$soma_entrada_cafe = 0;
$soma_saida_cafe = 0;
$soma_entrada_pimenta = 0;
$soma_saida_pimenta = 0;


// ================================================================================================================
include("../includes/conecta_bd.php");

// ====== BUSCA CONFIGURAÇÕES DE FILIAL ==========================================================================
$busca_filial_config = mysqli_query($conexao, "SELECT estoque_relat_conso, produtos_relatorio FROM filiais WHERE descricao='$filial_usuario'");
$filial_config = mysqli_fetch_row($busca_filial_config);

$relatorio_consolidado_w = $filial_config[0];
$produtos_relatorio_w = $filial_config[1];
// ===============================================================================================================

// ====== RELATORIO CONSOLIDADO ===================================================================================
if ($botao == "CONSOLIDADO") {
    $editar = mysqli_query($conexao, "UPDATE filiais SET estoque_relat_conso='$relatorio_conso' WHERE descricao='$filial'");
    $relatorio_consolidado_w = $relatorio_conso;
}

if ($relatorio_consolidado_w == "S") {
    $mysql_filial = "estoque.filial IS NOT NULL ";
} else {
    $mysql_filial = "estoque.filial='$filial_usuario' ";
}
// ================================================================================================================

// ================================================================================================================
$soma_entrada_geral = mysqli_query($conexao, "SELECT estoque.cod_produto, estoque.produto, cadastro_produto.produto_print, cadastro_produto.unidade_print, cadastro_produto.quantidade_un, cadastro_produto.nome_imagem, SUM(estoque.quantidade) FROM estoque, cadastro_produto WHERE $mysql_filial AND estoque.movimentacao='ENTRADA' AND estoque.estado_registro='ATIVO' AND estoque.cod_produto=cadastro_produto.codigo GROUP BY estoque.cod_produto, estoque.produto, cadastro_produto.produto_print, cadastro_produto.unidade_print, cadastro_produto.quantidade_un, cadastro_produto.nome_imagem");
$linha_entrada_geral = mysqli_num_rows($soma_entrada_geral);

$soma_saida_geral = mysqli_query($conexao, "SELECT estoque.cod_produto, estoque.produto, cadastro_produto.produto_print, cadastro_produto.unidade_print, cadastro_produto.quantidade_un, cadastro_produto.nome_imagem, SUM(estoque.quantidade) FROM estoque, cadastro_produto WHERE $mysql_filial AND estoque.movimentacao='SAIDA' AND estoque.estado_registro='ATIVO' AND estoque.cod_produto=cadastro_produto.codigo GROUP BY estoque.cod_produto, estoque.produto, cadastro_produto.produto_print, cadastro_produto.unidade_print, cadastro_produto.quantidade_un, cadastro_produto.nome_imagem");
$linha_saida_geral = mysqli_num_rows($soma_saida_geral);
// ================================================================================================================

include("../includes/desconecta_bd.php");
// ================================================================================================================


$soma_entrada_cafe = 0;
$soma_entrada_pimenta = 0;
$soma_entrada_cacau = 0;
$soma_entrada_res_pimenta = 0;


// ====== FUNÇÃO FOR ENTRADA ======================================================================================
for ($e = 1; $e <= $linha_entrada_geral; $e++) {
    $aux_entrada_geral = mysqli_fetch_row($soma_entrada_geral);

    // ================================================================================================================
    $cod_produto_e = $aux_entrada_geral[0];
    $produto_e = $aux_entrada_geral[1];
    $produto_print_e = $aux_entrada_geral[2];
    $unidade_print_e = $aux_entrada_geral[3];
    $quantidade_un_e = $aux_entrada_geral[4];
    $link_img_produto_e = $aux_entrada_geral[5];
    $quantidade_entrada = $aux_entrada_geral[6];

    if ($cod_produto_e == "2") {
        $soma_entrada_cafe += $quantidade_entrada;
    } elseif ($cod_produto_e == "3") {
        $soma_entrada_pimenta += $quantidade_entrada;
    } elseif ($cod_produto_e == "4") {
        $soma_entrada_cacau = $quantidade_entrada;
    } elseif ($cod_produto_e == "11") {
        $soma_entrada_res_pimenta += $quantidade_entrada;
    } else {
    }
}
// ================================================================================================================

$soma_saida_cafe = 0;
$soma_saida_pimenta = 0;
$soma_saida_cacau = 0;
$soma_saida_res_pimenta = 0;

// ====== FUNÇÃO FOR SAÍDA ========================================================================================
for ($s = 1; $s <= $linha_saida_geral; $s++) {
    $aux_saida_geral = mysqli_fetch_row($soma_saida_geral);

    // ================================================================================================================
    $cod_produto_s = $aux_saida_geral[0];
    $produto_s = $aux_saida_geral[1];
    $produto_print_s = $aux_saida_geral[2];
    $unidade_print_s = $aux_saida_geral[3];
    $quantidade_un_s = $aux_saida_geral[4];
    $link_img_produto_s = $aux_saida_geral[5];
    $quantidade_saida = $aux_saida_geral[6];

    if ($cod_produto_s == "2") {
        $soma_saida_cafe += $quantidade_saida;
    } elseif ($cod_produto_s == "3") {
        $soma_saida_pimenta += $quantidade_saida;
    } elseif ($cod_produto_s == "4") {
        $soma_saida_cacau += $quantidade_saida;
    } elseif ($cod_produto_s == "11") {
        $soma_saida_res_pimenta += $quantidade_saida;
    } else {
    }
}
// ================================================================================================================


// ================================================================================================================
$quant_cafe = ($soma_entrada_cafe - $soma_saida_cafe);
$quant_cafe_convert = ($quant_cafe / 60);
$quant_cafe_print = number_format($quant_cafe_convert, 2, ",", ".") . " SC";
$quant_pimenta = ($soma_entrada_pimenta - $soma_saida_pimenta);
$quant_pimenta_print = number_format($quant_pimenta, 0, ",", ".") . " KG";
$quant_cacau = ($soma_entrada_cacau - $soma_saida_cacau);
$quant_cacau_print = number_format($quant_cacau, 0, ",", ".") . " KG";
// Estes valore são estão sendo usado no relatório
// $quant_pimenta_ma = ($soma_entrada_pimenta_ma - $soma_saida_pimenta_ma);
$quant_pimenta_ma = 0; // linha incluida somente para deixar o relatório compatível
$quant_pimenta_ma_print = number_format($quant_pimenta_ma, 0, ",", ".") . " KG";
$quant_res_pimenta = ($soma_entrada_res_pimenta - $soma_saida_res_pimenta);
$quant_res_pimenta_print = number_format($quant_res_pimenta, 0, ",", ".") . " KG";
// ================================================================================================================

// ================================================================================================================
include("../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
    <?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
    <?php include("../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->

<body>


    <!-- ====== TOPO ================================================================================================== -->
    <div class="topo">
        <?php include("../includes/topo.php"); ?>
    </div>


    <!-- ====== MENU ================================================================================================== -->
    <div class="menu">
        <?php include("../includes/menu_estoque.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../includes/submenu_estoque_estoque.php"); ?>
    </div>


    <!-- ====== CENTRO ================================================================================================= -->
    <div class="ct_auto">




        <!-- ============================================================================================================= -->
        <div style="width:auto; height:560px; border:1px solid transparent; margin:auto">




            <!-- ====== ESTOQUE ATUAL ====================================================================================== -->
            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Estoque Atual</div>
                </div>

                <div style="width:253px; height:5px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>


                <?php

                if ($produtos_relatorio_w == "CAFE_PIMENTA_CACAU") {

                    // CAFÉ CONILON ================================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Caf&eacute; Conilon:</div>

                        <div style='width:100px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_cafe_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // PIMENTA DO REINO ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Pimenta do Reino:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_pimenta_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // CACAU =======================================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Cacau:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_cacau_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";
                } elseif ($produtos_relatorio_w == "CAFE_PIMENTA_MADURA") {
                    // CAFÉ CONILON ================================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Caf&eacute; Conilon:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_cafe_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // PIMENTA DO REINO ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Pimenta do Reino:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_pimenta_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // PIMENTA MADURA ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Pimenta Madura:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_pimenta_ma_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";
                } elseif ($produtos_relatorio_w == "CAFE_PIMENTA") {
                    // CAFÉ CONILON ================================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Caf&eacute; Conilon:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_cafe_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // PIMENTA DO REINO ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Pimenta do Reino:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_pimenta_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";
                } elseif ($produtos_relatorio_w == "PIMENTA_CACAU") {
                    // PIMENTA DO REINO ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Pimenta do Reino:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_pimenta_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // CACAU =======================================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Cacau:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_cacau_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";


                    // RESIDUO PIMENTA ============================================================================================
                    echo "
                        <div style='width:253px; height:30px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
                        <img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='height:30px'></div>

                        <div style='width:146px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900'>
                        Res&iacute;duo de Pimenta:</div>

                        <div style='width:110px; height:15px; margin-left:0px; margin-top:0px; text-align:right; float:left; font-size:13px; color:#003466'>
                        $quant_res_pimenta_print</div>
                        
                        <div style='width:253px; height:1px; margin-left:20px; margin-top:5px; float:left; border-top:1px solid #999'>
                        </div>";
                } else {
                }

                ?>
            </div>
            <!-- ============================================================================================================= -->
































        </div>
        <!-- ============================================================================================================= -->










        <!-- ============================================================================================================= -->
        <div style="width:auto; height:40px; border:1px solid transparent; margin:auto">
            <div class="form_rotulo" style="width:140px; height:20px; float:left; margin-left:40px; margin-top:4px; color:#999">
                Relat&oacute;rio Consolidado:
            </div>
            <div style="width:100px; height:25px; float:left; border:1px solid transparent">
                <form name="relat_consolidado" action="<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/index_estoque.php" method="post" />
                <input type="hidden" name="botao" value="CONSOLIDADO" />

                <select name="relatorio_conso" class="form_select" onchange="document.relat_consolidado.submit()" style="width:60px; height:20px; font-size:12px; color:#999" />
                <?php
                if ($relatorio_consolidado_w == "S") {
                    echo "
	<option value='S' selected='selected'>SIM</option>
	<option value='N'>N&Atilde;O</option>";
                } else {
                    echo "
	<option value='S'>SIM</option>
	<option value='N' selected='selected'>N&Atilde;O</option>";
                }
                ?>
                </select>
            </div>
        </div>
        <!-- ============================================================================================================= -->





    </div>
    <!-- ====== FIM DIV CT =========================================================================================== -->




    <!-- ====== RODAPÉ =============================================================================================== -->
    <div class="rdp_1">
        <?php include("../includes/rodape.php"); ?>
    </div>


    <!-- ====== FIM ================================================================================================== -->
    <?php include("../includes/desconecta_bd.php"); ?>
</body>

</html>