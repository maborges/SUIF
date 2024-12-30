<?php
include("../includes/config.php");
include("../includes/conecta_bd.php");
include("../includes/valida_cookies.php");
$pagina = "index_cadastro";
$titulo = "Cadastros";
$modulo = "cadastros";
$menu = "";
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT count(*) FROM cadastro_pessoa WHERE estado_registro='ATIVO'");
//$linha_pessoa = mysqli_num_rows ($busca_pessoa);
$linha_pessoa = $busca_pessoa->fetch_row()[0];

$busca_favorecido = mysqli_query($conexao, "SELECT count(*) FROM cadastro_favorecido WHERE estado_registro='ATIVO'");
$linha_favorecido = $busca_favorecido->fetch_row()[0];

$busca_usuario = mysqli_query($conexao, "SELECT count(*) FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S'");
$linha_usuario = $busca_usuario->fetch_row()[0];

$busca_usuario_a = mysqli_query($conexao, "SELECT count(*) FROM usuarios WHERE estado_registro='ATIVO' AND usuario_interno!='S'");
$linha_usuario_a = $busca_usuario_a->fetch_row()[0];

$busca_usuario_b = mysqli_query($conexao, "SELECT count(*) FROM usuarios WHERE estado_registro='BLOQUEADO' AND usuario_interno!='S'");
$linha_usuario_b = $busca_usuario_b->fetch_row()[0];

$busca_produto = mysqli_query($conexao, "SELECT count(*) FROM cadastro_produto WHERE estado_registro='ATIVO'");
$linha_produto = $busca_produto->fetch_row()[0];

$busca_pessoa_sankhya = mysqli_query($conexao, "SELECT count(*) FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND id_sankhya is not null");
$linha_pessoa_sankhya = $busca_pessoa_sankhya->fetch_row()[0];

$busca_favorecido_sankhya = mysqli_query($conexao, "SELECT count(*) FROM cadastro_favorecido WHERE estado_registro='ATIVO' AND id_sankhya is not null");
$linha_favorecido_sankhya = $busca_favorecido_sankhya->fetch_row()[0];



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
        <?php include("../includes/menu_cadastro.php"); ?>
    </div>

    <div class="submenu">
    </div>


    <!-- ====== CENTRO ================================================================================================= -->
    <div class="ct_auto">



        <!-- ============================================================================================================= -->
        <div style="width:auto; height:560px; border:1px solid transparent; margin:auto">





            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Cadastros de Pessoas</div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Total de Cadastro:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_pessoa" ?></div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/pessoas/cadastro_1_formulario.php">&#8226; Cadastrar Pessoa</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/pessoas/pesquisar_pessoa.php">&#8226; Pesquisar Cadastro</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/pessoas/relatorio_pessoa.php">&#8226; Relat&oacute;rio</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/pessoas/classificacao_pessoa.php">&#8226; Classifica&ccedil;&atilde;o/Fun&ccedil;&atilde;o</a>
                    </div>
                </div>

            </div>




            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Cadastros de Favorecidos</div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Total de Favorecidos:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_favorecido" ?></div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/favorecidos/cadastro_1_selec_fornecedor.php">&#8226; Cadastrar Favorecido</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/favorecidos/pesquisar_favorecido.php">&#8226; Pesquisar Favorecido</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/favorecidos/relatorio_favorecido.php">&#8226; Relat&oacute;rio</a>
                    </div>
                </div>

            </div>



            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Usu&aacute;rios</div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Total de Usu&aacute;rios:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_usuario" ?></div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Usu&aacute;rios Ativos:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#0000FF">
                    <?php echo "$linha_usuario_a" ?></div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Usu&aacute;rios Bloqueados:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#FF0000">
                    <?php echo "$linha_usuario_b" ?></div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/usuarios/usuarios_cadastro.php">&#8226; Cadastrar Usu&aacute;rio</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/usuarios/usuarios_troca_senha.php">&#8226; Trocar de Senha</a>
                    </div>
                </div>

            </div>






            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Produtos</div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Total de Produtos:</div>

                <div style="width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_produto" ?></div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <?php
                $busca_cafe = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='2'");
                $linha_cafe = mysqli_num_rows($busca_cafe);
                $aux_cafe = mysqli_fetch_row($busca_cafe);
                $preco_maximo_cafe = "R$ " . number_format($aux_cafe[21], 2, ",", ".");
                if ($linha_cafe == 1) {
                    echo "
    <div style='width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Caf&eacute; Conilon:</div>

    <div style='width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $preco_maximo_cafe</div>";
                }


                $busca_pimenta = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='3'");
                $linha_pimenta = mysqli_num_rows($busca_pimenta);
                $aux_pimenta = mysqli_fetch_row($busca_pimenta);
                $preco_maximo_pimenta = "R$ " . number_format($aux_pimenta[21], 2, ",", ".");
                if ($linha_pimenta == 1) {
                    echo "
    <div style='width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta do Reino:</div>

    <div style='width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $preco_maximo_pimenta</div>";
                }


                $busca_pimenta_ma = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='13'");
                $linha_pimenta_ma = mysqli_num_rows($busca_pimenta_ma);
                $aux_pimenta_ma = mysqli_fetch_row($busca_pimenta_ma);
                $preco_maximo_pimenta_ma = "R$ " . number_format($aux_pimenta_ma[21], 2, ",", ".");
                if ($linha_pimenta_ma == 1) {
                    echo "
    <div style='width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Pimenta Madura:</div>

    <div style='width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $preco_maximo_pimenta_ma</div>";
                }


                $busca_cacau = mysqli_query($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND codigo='4'");
                $linha_cacau = mysqli_num_rows($busca_cacau);
                $aux_cacau = mysqli_fetch_row($busca_cacau);
                $preco_maximo_cacau = "R$ " . number_format($aux_cacau[21], 2, ",", ".");
                if ($linha_cacau == 1) {
                    echo "
    <div style='width:146px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#009900'>
    Cacau:</div>

    <div style='width:100px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#003466'>
    $preco_maximo_cacau</div>";
                }
                ?>


                <div style="width:253px; height:20px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>



                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/produtos/preco_compra.php">&#8226; Alterar Pre&ccedil;o de Compra</a>
                    </div>
                </div>

            </div>




            <!-- ################## SANKHYA    ###################  -->
            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">ERP Sankhya</div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>

                <div style="width:160px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Pessoas Atualizadas:</div>


                <div style="width:86px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_pessoa_sankhya" ?></div>

                <div style="width:170px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    Favorecidos Atualizados:</div>


                <div style="width:76px; height:20px; margin-left:7px; margin-top:10px; text-align:right; float:left; font-size:13px; color:#009900">
                    <?php echo "$linha_favorecido_sankhya" ?></div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href=<?= $permissao[151] == 'S' ? "$servidor/$diretorio_servidor/sankhya/filial/filiais.php" : '#' ?>>&#8226; Atualizar Filiais</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href=<?= $permissao[151] == 'S' ? "$servidor/$diretorio_servidor/sankhya/produto/produtos.php" : '#' ?>>&#8226; Atualizar Produtos</a>
                    </div>
                </div>

                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href=<?= $permissao[151] == 'S' ? "$servidor/$diretorio_servidor/sankhya/centro_custo/centro_custo.php" : '#' ?>>&#8226; Atualizar Centro de Custo</a>
                    </div>
                </div>
                <div style="width:253px; height:20px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href=<?= $permissao[151] == 'S' ? "$servidor/$diretorio_servidor/sankhya/tops/tops.php" : '#' ?>>&#8226; Cadastrar TOP</a>
                    </div>
                </div>
            </div>



            <div style="width:293px; height:250px; border:1px solid #999; margin-left:40px; margin-top:20px; float:left">
                <div style="width:253px; height:26px; border:1px solid transparent; margin-left:20px; background-color:#999; float:left; text-align:center">
                    <div style="margin-top:4px; font-size:14px; color:#FFF">Configura&ccedil;&otilde;es</div>
                </div>

                <div style="width:253px; height:70px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466; text-align:center">
                    <?php echo "<img src='$servidor/$diretorio_servidor/imagens/logomarca.png' style='height:70px' />" ?>
                </div>

                <div style="width:253px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                    Raz&atilde;o Social:</div>

                <div style="width:253px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900">
                    <?php echo "$config[3]" ?></div>

                <div style="width:253px; height:15px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>


                <div style="width:253px; height:15px; margin-left:20px; margin-top:10px; text-align:left; float:left; font-size:13px; color:#003466">
                    CNPJ:</div>

                <div style="width:253px; height:15px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#009900">
                    <?php echo "$config[47]" ?></div>

                <div style="width:253px; height:10px; margin-left:20px; margin-top:0px; text-align:left; float:left; font-size:13px; color:#003466">
                </div>




                <div style="width:253px; height:20px; margin-left:20px; margin-top:5px; text-align:left; float:left; font-size:13px; color:#003466">
                    <div class="link_4" style="float:left; width:auto; height:18px; border:0px solid #000; font-size:13px">
                        <a href="<?php echo "$servidor/$diretorio_servidor" ?>/cadastros/configuracoes/config_geral.php">&#8226; Alterar Configura&ccedil;&otilde;es</a>
                    </div>
                </div>


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