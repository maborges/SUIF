<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "transferencia_cadastro";
$titulo = "Transfer&ecirc;ncia entre Produtores";
$modulo = "compras";
$menu = "ficha_produtor";
// ========================================================================================================

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];

$numero_compra = $_POST["numero_compra"];
$motivo_exclusao = $_POST["motivo_exclusao"];
$motivo_obrigatorio = "NAO";

$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];
$numero_compra_busca = $_POST["numero_compra_busca"];
$filial_busca = $_POST["filial_busca"];
$movimentacao_busca = $_POST["movimentacao_busca"];
$status_busca = $_POST["status_busca"];

$usuario_exclusao = $nome_usuario_print;
$data_exclusao = date('Y-m-d', time());
$hora_exclusao = date('G:i:s', time());
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_compra_1 = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra_1 = mysqli_num_rows ($busca_compra_1);
$aux_compra_1 = mysqli_fetch_row($busca_compra_1);
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
$numero_transferencia = $aux_compra_1[30];
$data_compra_w = $aux_compra_1[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra_1[4]));
$estado_registro_w = $aux_compra_1[24];
$observacao_w = $aux_compra_1[13];
$filial_w = $aux_compra_1[25];

$usuario_cadastro_w = $aux_compra_1[18];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_compra_1[20]));
$hora_cadastro_w = $aux_compra_1[19];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_compra_1[21];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_compra_1[23]));
$hora_alteracao_w = $aux_compra_1[22];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_compra_1[31];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_compra_1[32]));
$hora_exclusao_w = $aux_compra_1[33];
$motivo_exclusao_w = $aux_compra_1[34];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// =============================================================================================================


// ========================================================================================================
$busca_compra_e = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_transferencia='$numero_transferencia' AND movimentacao='TRANSFERENCIA_ENTRADA' ORDER BY codigo");
$linha_compra_e = mysqli_num_rows ($busca_compra_e);
$aux_compra_e = mysqli_fetch_row($busca_compra_e);

$numero_compra_e = $aux_compra_e[1];
$fornecedor_e = $aux_compra_e[2];
$cod_produto_e = $aux_compra_e[39];
$quantidade = $aux_compra_e[5];
$quantidade_print = number_format($aux_compra_e[5],2,",",".");
$cod_tipo = $aux_compra_e[41];
// ========================================================================================================


// ========================================================================================================
$busca_compra_s = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_transferencia='$numero_transferencia' AND movimentacao='TRANSFERENCIA_SAIDA' ORDER BY codigo");
$linha_compra_s = mysqli_num_rows ($busca_compra_s);
$aux_compra_s = mysqli_fetch_row($busca_compra_s);

$numero_compra_s = $aux_compra_s[1];
$fornecedor_s = $aux_compra_s[2];
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_e'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$nome_imagem_produto = $aux_bp[28];
$usuario_alteracao_prod = $aux_bp[16];
$data_alteracao_prod = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
$plano_conta = $aux_bp[35];
if ($nome_imagem_produto == "")
{$link_imagem_produto = "";}
else
{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>";}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ======================================================================================================


// ====== BUSCA TIPO PRODUTO ==================================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA PESSOA ENTRADA  ==============================================================================
$busca_pessoa_e = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_e' ORDER BY nome");
$aux_pessoa_e = mysqli_fetch_row($busca_pessoa_e);

$nome_pessoa_e = $aux_pessoa_e[1];
$tipo_pessoa_e = $aux_pessoa_e[2];
$cpf_pessoa_e = $aux_pessoa_e[3];
$cnpj_pessoa_e = $aux_pessoa_e[4];
$cidade_pessoa_e = $aux_pessoa_e[10];
$estado_pessoa_e = $aux_pessoa_e[12];
$telefone_pessoa_e = $aux_pessoa_e[14];
$codigo_pessoa_e = $aux_pessoa_e[35];
// ===========================================================================================================


// ====== BUSCA PESSOA SAIDA  ================================================================================
$busca_pessoa_s = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_s' ORDER BY nome");
$aux_pessoa_s = mysqli_fetch_row($busca_pessoa_s);

$nome_pessoa_s = $aux_pessoa_s[1];
$tipo_pessoa_s = $aux_pessoa_s[2];
$cpf_pessoa_s = $aux_pessoa_s[3];
$cnpj_pessoa_s = $aux_pessoa_s[4];
$cidade_pessoa_s = $aux_pessoa_s[10];
$estado_pessoa_s = $aux_pessoa_s[12];
$telefone_pessoa_s = $aux_pessoa_s[14];
$codigo_pessoa_s = $aux_pessoa_s[35];
// ========================================================================================================


// ====== EXCLUI VENDA E MONTA MENSAGEM ===================================================================

if ($botao == "EXCLUIR")
{
	if ($motivo_obrigatorio == "SIM" and ($motivo_exclusao == "" or $motivo_exclusao == " "))
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Motivo da exclus&atilde;o &eacute; obrigat&oacute;rio.</div>";
	$msg_titulo = "<div style='color:#009900'>Exclus&atilde;o de Transfer&ecirc;ncia</div>";
	}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Transfer&ecirc;ncia Exclu&iacute;da com Sucesso!</div>";
	

$excluir_transferencia_e = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', data_exclusao='$data_exclusao', hora_exclusao='$hora_exclusao', motivo_exclusao='$motivo_exclusao' WHERE numero_compra='$numero_compra_e'");

$excluir_transferencia_s = mysqli_query ($conexao, "UPDATE compras SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', data_exclusao='$data_exclusao', hora_exclusao='$hora_exclusao', motivo_exclusao='$motivo_exclusao' WHERE numero_compra='$numero_compra_s'");


// ------ ATUALIZA SALDO ARMAZENADO DESTINO ----------------------------------------
$fornecedor_pesquisa = $fornecedor_e;
$cod_seleciona_produto = $cod_produto_e;
$cod_tipo_produto_form = $cod_tipo;
$nome_pessoa = $nome_pessoa_e;

include ('../../includes/saldo_armazenado_busca.php');
$saldo = $saldo_produtor - $quantidade;

include ('../../includes/saldo_armazenado_atualiza.php');
// ---------------------------------------------------------------------------------


// ------ ATUALIZA SALDO ARMAZENADO ORIGEM -----------------------------------------
$fornecedor_pesquisa = $fornecedor_s;
$cod_seleciona_produto = $cod_produto_e;
$cod_tipo_produto_form = $cod_tipo;
$nome_pessoa = $nome_pessoa_s;

include ('../../includes/saldo_armazenado_busca.php');
$saldo = $saldo_produtor + $quantidade;
include ('../../includes/saldo_armazenado_atualiza.php');
// ---------------------------------------------------------------------------------


	}
}

// ======================================================================================================


// ========================================================================================================
include ("../../includes/head.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>





<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$msg_titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
    <?php echo"N&ordm; $numero_transferencia"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php echo"$data_compra_print"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:390px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Produtor Origem:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa_s</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa_s</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa_s</div></div>";
    }
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_pessoa_s" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE / UF ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	$cidade_uf_s = $cidade_pessoa_s . "/" . $estado_pessoa_s;
	$conta_caracter_s = strlen($cidade_uf_s);
	if ($conta_caracter_s <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_s</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_s</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Produtor Destino:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa_e</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_e == "PJ" or $tipo_pessoa_e == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa_e == "PJ" or $tipo_pessoa_e == "pj")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa_e</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa_e</div></div>";
    }
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_pessoa_e" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE / UF ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	$cidade_uf_e = $cidade_pessoa_e . "/" . $estado_pessoa_e;
	$conta_caracter_e = strlen($cidade_uf_e);
	if ($conta_caracter_e <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_e</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_e</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->




<!-- ======= PRODUTO ============================================================================================= -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:336px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:336px; height:34px; float:left; border:1px solid transparent">
        <div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo"$link_imagem_produto" ?>
            </div>
        
            <div style="width:177px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#003466; overflow:hidden">
                <?php echo"<b>$produto_print</b>" ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ======= QUANTIDADE ============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Quantidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"<b>$quantidade_print $unidade_abreviacao</b>"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; margin-top:0px; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Observa&ccedil;&atilde;o:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <div style="width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; overflow:hidden"><?php echo"$observacao_w" ?></div></div>
    </div>
</div>
<!-- ============================================================================================================== -->


<!-- ======= FILIAL ===================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$filial_w" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO REGISTRO ======================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    N&ordm; Registro:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$numero_compra" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->



<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
echo"
<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================



?>
</div>




<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<div id="centro" style="width:1030px; height:35px; margin:auto">
<!-- ======= DADOS CADASTRO ========================================================================================= -->
<?php
if ($dados_cadastro_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden'><i>$dados_cadastro_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= DADOS EDIÇÃO =========================================================================================== -->
<?php
if ($dados_alteracao_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden'><i>$dados_alteracao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= DADOS EXCLUSÃO ========================================================================================= -->
<?php
if ($usuario_exclusao_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden' title='Motivo da Exclus&atilde;o: $motivo_exclusao_w'>
		<i>$dados_exclusao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->
</div>


<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->






</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>