<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");
$pagina = "relatorio_pessoa";
$titulo = "Relat&oacute;rio - Cadastros de Pessoas";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';

$tipo_pessoa_busca = $_POST["tipo_pessoa_busca"] ?? '';
$classificacao_busca = $_POST["classificacao_busca"] ?? '';
$cidade_busca = $_POST["cidade_busca"] ?? '';
$status_busca = $_POST["status_busca"] ?? '';
$msg = '';
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if ($tipo_pessoa_busca == "" or $tipo_pessoa_busca == "GERAL") {
    $mysql_tipo_pessoa = "tipo IS NOT NULL";
    $tipo_pessoa_busca = "GERAL";
} else {
    $mysql_tipo_pessoa = "tipo='$tipo_pessoa_busca'";
    $tipo_pessoa_busca = $_POST["tipo_pessoa_busca"];
}

if ($classificacao_busca == "" or $classificacao_busca == "GERAL") {
    $mysql_classificacao = "classificacao_1 IS NOT NULL";
    $classificacao_busca = "GERAL";
} else {
    $mysql_classificacao = "classificacao_1='$classificacao_busca'";
    $classificacao_busca = $_POST["classificacao_busca"];
}

if ($cidade_busca == "" or $cidade_busca == "GERAL") {
    $mysql_cidade = "cidade IS NOT NULL";
    $cidade_busca = "GERAL";
} else {
    $mysql_cidade = "cidade='$cidade_busca'";
    $cidade_busca = $_POST["cidade_busca"];
}

if ($status_busca == "" or $status_busca == "GERAL") {
    $mysql_status = "estado_registro IS NOT NULL";
    $status_busca = "GERAL";
} else {
    $mysql_status = "estado_registro='$status_busca'";
    $status_busca = $_POST["status_busca"];
}
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$linha_pessoa = 0;
if ($botao == "BUSCAR") {
    $busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE $mysql_tipo_pessoa AND $mysql_classificacao AND $mysql_cidade AND $mysql_status ORDER BY nome");
    $linha_pessoa = mysqli_num_rows($busca_pessoa);
}
// ================================================================================================================


// ================================================================================================================
include("../../includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
    <?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
    <?php include('../../includes/javascript.php'); ?>
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
        <?php include("../../includes/menu_cadastro.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_cadastro_pessoas.php"); ?>
    </div>


    <!-- ====== CENTRO ================================================================================================= -->
    <div class="ct_auto">


        <!-- ============================================================================================================= -->
        <div class="espacamento_15"></div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="ct_topo_1">
            <div class="ct_titulo_1">
                <?php echo "$titulo"; ?>
            </div>

            <div class="ct_subtitulo_right" style="margin-top:8px">
                <?php
                if ($linha_pessoa == 1) {
                    echo "$linha_pessoa Cadastro";
                } elseif ($linha_pessoa > 1) {
                    echo "$linha_pessoa Cadastros";
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
                <?php echo "$msg"; ?>
            </div>

            <div class="ct_subtitulo_right">
                <!-- xxxxxxxxxxxxxxxxx -->
            </div>
        </div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="pqa" style="height:63px">


            <!-- ======= ESPAÇAMENTO ============================================================================================ -->
            <div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <form action="<?php echo "$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/relatorio_pessoa.php" method="post" />
                <input type="hidden" name="botao" value="BUSCAR" />
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= TIPO PESSOA =========================================================================================== -->
            <div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
                    Tipo Pessoa:
                </div>

                <div style="width:218px; height:25px; float:left; border:1px solid transparent">
                    <select name="tipo_pessoa_busca" class="form_select" style="width:190px" />
                    <?php
                    if ($tipo_pessoa_busca == "GERAL") {
                        echo "<option selected='selected' value='GERAL'>(Todos os Tipos)</option>";
                    } else {
                        echo "<option value='GERAL'>(Todos os Tipos)</option>";
                    }

                    if ($tipo_pessoa_busca == "PF") {
                        echo "<option selected='selected' value='PF'>Pessoa F&iacute;sica</option>";
                    } else {
                        echo "<option value='PF'>Pessoa F&iacute;sica</option>";
                    }

                    if ($tipo_pessoa_busca == "PJ") {
                        echo "<option selected='selected' value='PJ'>Pessoa Jur&iacute;dica</option>";
                    } else {
                        echo "<option value='PJ'>Pessoa Jur&iacute;dica</option>";
                    }
                    ?>
                    </select>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= CLASSIFICAÇÃO =========================================================================================== -->
            <div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
                    Classifica&ccedil;&atilde;o:
                </div>

                <div style="width:218px; height:25px; float:left; border:1px solid transparent">
                    <select name="classificacao_busca" class="form_select" style="width:190px" />
                    <?php
                    if ($classificacao_busca == "GERAL") {
                        echo "<option selected='selected' value='GERAL'>(Todas as Classifica&ccedil;&otilde;es)</option>";
                    } else {
                        echo "<option value='GERAL'>(Todas as Classifica&ccedil;&otilde;es)</option>";
                    }

                    $busca_classificacao = mysqli_query($conexao, "SELECT * FROM classificacao_pessoa WHERE tipo='classificacao' AND estado_registro='ATIVO' ORDER BY codigo");
                    $linhas_classificacao = mysqli_num_rows($busca_classificacao);

                    for ($i = 1; $i <= $linhas_classificacao; $i++) {
                        $aux_classificacao = mysqli_fetch_row($busca_classificacao);

                        if ($aux_classificacao[0] == $classificacao_busca) {
                            echo "<option selected='selected' value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";
                        } else {
                            echo "<option value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= CIDADE =========================================================================================== -->
            <div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
                    Cidade:
                </div>

                <div style="width:218px; height:25px; float:left; border:1px solid transparent">
                    <select name="cidade_busca" class="form_select" style="width:190px" />
                    <?php
                    if ($cidade_busca == "GERAL") {
                        echo "<option selected='selected' value='GERAL'>(Todas as Cidades)</option>";
                    } else {
                        echo "<option value='GERAL'>(Todas as Cidades)</option>";
                    }

                    $busca_cidade = mysqli_query($conexao, "SELECT DISTINCT cidade FROM cadastro_pessoa ORDER BY cidade");
                    $linhas_cidade = mysqli_num_rows($busca_cidade);

                    for ($c = 1; $c <= $linhas_cidade; $c++) {
                        $aux_cidade = mysqli_fetch_row($busca_cidade);

                        if ($aux_cidade[0] == $cidade_busca) {
                            echo "<option selected='selected' value='$aux_cidade[0]'>$aux_cidade[0]</option>";
                        } else {
                            echo "<option value='$aux_cidade[0]'>$aux_cidade[0]</option>";
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>
            <!-- ================================================================================================================ -->


            <!-- ======= STATUS CADASTRO ======================================================================================= -->
            <div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:218px; height:17px; border:1px solid transparent; float:left">
                    Status dos Cadastros:
                </div>

                <div style="width:218px; height:25px; float:left; border:1px solid transparent">
                    <select name="status_busca" class="form_select" style="width:190px" />
                    <?php
                    if ($status_busca == "GERAL") {
                        echo "<option value='GERAL' selected='selected'>(Todos os Cadastros)</option>";
                    } else {
                        echo "<option value='GERAL'>(Todos os Cadastros)</option>";
                    }

                    if ($status_busca == "ATIVO") {
                        echo "<option value='ATIVO' selected='selected'>ATIVOS</option>";
                    } else {
                        echo "<option value='ATIVO'>ATIVOS</option>";
                    }

                    if ($status_busca == "INATIVO") {
                        echo "<option value='INATIVO' selected='selected'>INATIVOS</option>";
                    } else {
                        echo "<option value='INATIVO'>INATIVOS</option>";
                    }

                    if ($status_busca == "EXCLUIDO") {
                        echo "<option value='EXCLUIDO' selected='selected'>EXCLU&Iacute;DOS</option>";
                    } else {
                        echo "<option value='EXCLUIDO'>EXCLU&Iacute;DOS</option>";
                    }
                    ?>
                    </select>
                </div>
            </div>
            <!-- ================================================================================================================ -->




            <!-- ======= BOTAO ================================================================================================== -->
            <div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
                    <!-- Botão: -->
                </div>

                <div style="width:95px; height:25px; float:left; border:1px solid transparent">
                    <button type='submit' class='botao_1'>Buscar</button>
                    </form>
                </div>
            </div>
            <!-- ================================================================================================================ -->




            <!-- ======= IMPRIMIR ================================================================================================== -->
            <div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
                <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
                    <!-- Botão: -->
                </div>

                <div style="width:95px; height:25px; float:left; border:1px solid transparent">
                    <?php
                    if ($linha_pessoa >= 1) {
                        echo "
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/relatorio_pessoa_impressao.php' target='_blank' method='post' />
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='pesquisar_por_busca' value='RELATORIO'>
	<input type='hidden' name='tipo_pessoa_busca' value='$tipo_pessoa_busca'>
	<input type='hidden' name='classificacao_busca' value='$classificacao_busca'>
	<input type='hidden' name='cidade_busca' value='$cidade_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_1'>Imprimir</button>
	</form>";
                    }
                    ?>
                </div>
            </div>
            <!-- ================================================================================================================ -->



        </div>
        <!-- ============================================================================================================= -->


        <!-- ============================================================================================================= -->
        <div class="espacamento_20"></div>
        <!-- ============================================================================================================= -->



        <!-- ============================================================================================================= -->
        <?php
        if ($linha_pessoa == 0) {
            echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";
        } else {
            echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='80px'>C&oacute;digo</td>
<td width='350px'>Nome</td>
<td width='200px'>CPF/CNPJ</td>
<td width='150px'>Telefone</td>
<td width='250px'>Cidade/UF</td>
<td width='130px'>Status Cadastro</td>
</tr>
</table>";
        }


        echo "<table class='tabela_geral' style='font-size:12px'>";


        // ====== FUNÇÃO FOR ===================================================================================
        for ($x = 1; $x <= $linha_pessoa; $x++) {
            $aux_pessoa = mysqli_fetch_row($busca_pessoa);

            // ====== DADOS DO CADASTRO ============================================================================
            $id_w = $aux_pessoa[0];
            $nome_w = $aux_pessoa[1];
            $tipo_w = $aux_pessoa[2];
            $cpf_w = $aux_pessoa[3];
            $cnpj_w = $aux_pessoa[4];
            $rg_w = $aux_pessoa[5];
            $sexo_w = $aux_pessoa[6];
            $data_nascimento_w = $aux_pessoa[7];
            $endereco_w = $aux_pessoa[8];
            $bairro_w = $aux_pessoa[9];
            $cidade_w = $aux_pessoa[10];
            $cep_w = $aux_pessoa[11];
            $estado_w = $aux_pessoa[12];
            $ponto_referencia_w = $aux_pessoa[13];
            $telefone_1_w = $aux_pessoa[14];
            $telefone_2_w = $aux_pessoa[15];
            $email_w = $aux_pessoa[17];
            $classificacao_1_w = $aux_pessoa[18];
            $observacao_w = $aux_pessoa[22];
            $nome_fantasia_w = $aux_pessoa[24];
            $numero_residencia_w = $aux_pessoa[25];
            $complemento_w = $aux_pessoa[26];
            $estado_registro_w = $aux_pessoa[34];
            $codigo_pessoa_w = $aux_pessoa[35];

            if ($tipo_w == "PF" or $tipo_w == "pf") {
                $cpf_cnpj_print = $cpf_w;
            } else {
                $cpf_cnpj_print = $cnpj_w;
            }

            $usuario_cadastro_w = $aux_pessoa[28];
            if ($usuario_cadastro_w == "") {
                $dados_cadastro_w = "";
            } else {
                $data_cadastro_w = date('d/m/Y', strtotime($aux_pessoa[30]));
                $hora_cadastro_w = $aux_pessoa[29];
                $dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
            }

            $usuario_alteracao_w = $aux_pessoa[31];
            if ($usuario_alteracao_w == "") {
                $dados_alteracao_w = "";
            } else {
                $data_alteracao_w = date('d/m/Y', strtotime($aux_pessoa[33]));
                $hora_alteracao_w = $aux_pessoa[32];
                $dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
            }

            $usuario_exclusao_w = $aux_pessoa[36];
            if ($usuario_exclusao_w == "") {
                $dados_exclusao_w = "";
            } else {
                $data_exclusao_w = date('d/m/Y', strtotime($aux_pessoa[37]));
                $hora_exclusao_w = $aux_pessoa[38];
                $dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
            }
            // ======================================================================================================


            // ======= BUSCA CLASSIFICAÇÃO ==================================================================================
            $busca_classificacao = mysqli_query($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_w'");
            $aux_bcl = mysqli_fetch_row($busca_classificacao);
            $classificacao_print = $aux_bcl[1];
            // ================================================================================================================


            // ====== RELATORIO ========================================================================================
            if ($estado_registro_w == "INATIVO") {
                echo "<tr class='tabela_4' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
            } elseif ($estado_registro_w == "EXCLUIDO") {
                echo "<tr class='tabela_5' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
            } else {
                echo "<tr class='tabela_1' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
            }

            echo "
<td width='80px' align='left'><div style='height:14px; margin-left:7px'>$id_w</div></td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_w</div></td>
<td width='200px' align='center'>$cpf_cnpj_print</td>
<td width='150px' align='center'>$telefone_1_w</td>
<td width='250px' align='center'>$cidade_w/$estado_w</td>
<td width='130px' align='center'>$estado_registro_w</td>";
        }

        echo "</tr></table>";
        // =================================================================================================================



        // =================================================================================================================
        if ($linha_pessoa == 0 and $botao == "BUSCAR") {
            echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro encontrado.</i></div>";
        }
        // =================================================================================================================
        ?>


        <!-- ============================================================================================================= -->
        <div class="espacamento_30"></div>
        <!-- ============================================================================================================= -->



    </div>
    <!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



    <!-- ============================================================================================================= -->
    <div class="espacamento_40"></div>
    <!-- ============================================================================================================= -->


    <!-- ============================================================================================================= -->
    <div class="contador">
        <div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
            <!-- ======== Observações ============= -->
        </div>
    </div>

    <div class="contador">
        <div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
            <!-- ======== Observações ============= -->
        </div>
    </div>

    <div class="contador">
        <div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
            <!-- ======== Observações ============= -->
        </div>
    </div>
    <!-- ============================================================================================================= -->


    <!-- ============================================================================================================= -->
    <div class="espacamento_10"></div>
    <!-- ============================================================================================================= -->



    </div>
    <!-- ====== FIM DIV CT ========================================================================================= -->



    <!-- ====== RODAPÉ =============================================================================================== -->
    <div class="rdp_1">
        <?php include("../../includes/rodape.php"); ?>
    </div>


    <!-- ====== FIM ================================================================================================== -->
    <?php include("../../includes/desconecta_bd.php"); ?>
</body>

</html>