<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "cadastro_4_enviar";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================

// ====== RETIRA ACENTUAÇÃO ===============================================================================
$comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
$semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
//$teste = str_replace($comAcentos, $semAcentos, $exemplo);
// ========================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$data_compra = date('Y-m-d', time());
$filial = $filial_usuario;

$numero_compra = $_POST["numero_compra"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];

$quantidade_form = Helpers::ConvertePeso($_POST["quantidade_form"]);
$quantidade_print = $_POST["quantidade_form"];
$preco_form = Helpers::ConverteValor($_POST["preco_form"]);
$preco_print = $_POST["preco_form"];
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$forma_entrega_form = $_POST["forma_entrega_form"];
$data_pagamento_form = $_POST["data_pagamento_form"];
$umidade_form = $_POST["umidade_form"];
$broca_form = $_POST["broca_form"];
$impureza_form = $_POST["impureza_form"];
$obs_form = $_POST["obs_form"];
$safra = date('Y');

$movimentacao = "COMPRA";
$situacao_pagamento = "A_PAGAR";
$estado_registro = "ATIVO";

if ($quantidade_form == "")
{$quantidade = 0;}
else
{$quantidade = $quantidade_form;}


$sub_total = ($quantidade * $preco_form);
$sub_total_print = "R$ " . number_format($sub_total,2,",",".");
$total_geral = ($quantidade * $preco_form);
$total_geral_print = "R$ " . number_format($total_geral,2,",",".");


$usuario_cadastro = $nome_usuario_print;
$data_cadastro = date('Y-m-d', time());
$hora_cadastro = date('G:i:s', time());
// ================================================================================================================


// ======= ALTERA DATA ==========================================================================================
$data_pagamento_aux = Helpers::ConverteData($data_pagamento_form);

if ($data_pagamento_aux == "" or $data_pagamento_aux <= 1900-01-01)
{$data_pagamento_aux = $data_compra;}
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_pesquisa'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linha_pessoa = mysqli_num_rows ($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];
if ($tipo_pessoa == "PF")
{$cpf_cnpj = $cpf_pessoa;}
else
{$cpf_cnpj = $cnpj_pessoa;}

// ------ INTEGRAÇÃO ROVERETI ---------------------------------------------------------------------------
$cpf_aux = Helpers::limpa_cpf_cnpj($cpf_cnpj);
$fornecedor_rovereti = str_replace($comAcentos, $semAcentos, $nome_pessoa);
// ======================================================================================================


// ====== BUSCA PRODUTO =================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_seleciona_produto'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = "R$ " . number_format($aux_bp[21],2,",",".");
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

if ($botao == "FORMULARIO" or $botao == "CALCULA_TOTAL")
{
	$cod_tipo_produto_form = $cod_tipo_preferencial;
	$umidade_form = $umidade_preferencial;
	$broca_form = $broca_preferencial;
	$impureza_form = $impureza_preferencial;
}

// ------ INTEGRAÇÃO ROVERETI ---------------------------------------------------------------------------
$produto_rovereti = str_replace($comAcentos, $semAcentos, $produto_print); // ATENÇÃO: REVERETI não aceita "acentos"
$cod_class_gerencial = $aux_bp[24];
$cod_centro_custo = $aux_bp[25];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA =======================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// =======================================================================================================


// ====== BUSCA NUMERO DE COMPRA (DUPLICIDADE) ===========================================================
$busca_num_venda = mysqli_query ($conexao, "SELECT * FROM vendas WHERE estado_registro!='EXCLUIDO' and numero_compra='$numero_compra'");
$achou_venda_duplicidade = mysqli_num_rows ($busca_num_venda);
// =======================================================================================================


// ====== BUSCA TIPO PRODUTO =============================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// =======================================================================================================


// ====== BUSCA FORMA DE ENTREGA =========================================================================
$busca_forma_entrega = mysqli_query ($conexao, "SELECT * FROM select_forma_entrega WHERE codigo='$forma_entrega_form'");
$aux_fe = mysqli_fetch_row($busca_forma_entrega);

$forma_entrega_print = $aux_fe[1];
// ===========================================================================================================


// ====== BUSCA PLANO DE CONTA MÃE ===========================================================================
$busca_pc_mae = mysqli_query ($conexao, "SELECT * FROM plano_conta WHERE codigo='$plano_conta' ORDER BY codigo");
$aux_pc_mae = mysqli_fetch_row($busca_pc_mae);	
$plano_conta_mae = $aux_pc_mae[3];
// ===========================================================================================================


// ====== DESCRIÇÃO CONTAS A PAGAR =========================================================================
$descricao_conta = "COMPRA DE " . $produto_print . " (" . $quantidade . " " . $unidade_abreviacao . " X " . $preco_print . ")";
// ===========================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
if ($permissao[65] == "S")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOTA FISCAL =======================================================================
if ($permissao[84] == "S")
{$permite_nf = "SIM";}
else
{$permite_nf = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =========================================================================
if ($permissao[84] == "S")
{$permite_imprimir = "SIM";}
else
{$permite_imprimir = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOVA COMPRA ========================================================================
if ($permissao[84] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ========================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "NOVA_COMPRA")
{
	if ($fornecedor_pesquisa == "" or $linha_pessoa == 0)
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um fornecedor.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif ($cod_seleciona_produto == "" or $linhas_bp == 0)
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif (!is_numeric($quantidade) or $quantidade <= 0)
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Quantidade inv&aacute;lida.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif (!is_numeric($preco_form) or $preco_form <= 0)
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Pre&ccedil;o inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif ($cod_tipo_produto_form == "")
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Selecione o tipo do produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif ($forma_entrega_form == "")
	{$erro = 6;
	$msg = "<div style='color:#FF0000'>Selecione a forma de entrega.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif ($achou_venda_duplicidade >= 1)
	{$erro = 7;
	$msg = "<div style='color:#FF0000'>N&uacute;mero de compra j&aacute; existente. Compra em duplicidade.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	elseif ($total_geral < 0)
	{$erro = 8;
	$msg = "<div style='color:#FF0000'>Valor total n&atilde;o pode ser menor que zero.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Compra</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_compra</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Compra Cadastrada com Sucesso!</div>";
	$num_romaneio_print = "<div style='color:#0000FF'>N&ordm; $numero_compra</div>";
	

/*
// ==================================================================
// ATUALIZA SALDO ARMAZENADO ----------------------------------------
include ('../../includes/saldo_armazenado_busca.php');
$saldo = $saldo_produtor - $quantidade;
include ('../../includes/saldo_armazenado_atualiza.php');
// ==================================================================
 */
 
 /*
if ($config[28] == "S")
{include ('../../includes/rovereti_compra.php');}
*/
/*
$inserir_compra = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, preco_unitario, valor_total, unidade, safra, tipo, broca, umidade, observacao, data_pagamento, situacao_pagamento, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, cod_produto, cod_unidade, cod_tipo, fornecedor_print, impureza, quantidade_original_primaria, forma_entrega, retorno_rovereti) VALUES (NULL, '$numero_compra', '$fornecedor_pesquisa', '$produto_print', '$data_compra', '$quantidade', '$preco_form', '$total_geral', '$unidade_abreviacao', '$safra', '$tipo_print', '$broca_form', '$umidade_form', '$obs_form', '$data_pagamento_aux', '$situacao_pagamento', '$movimentacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$estado_registro', '$filial', '$cod_seleciona_produto', '$cod_unidade', '$cod_tipo_produto_form', '$nome_pessoa', '$impureza_form', '$quantidade', '$forma_entrega_form', '$retorno_rovereti')");


$inserir_c_pagar = mysqli_query ($conexao, "INSERT INTO contas_a_pagar (codigo, descricao, fornecedor, valor, num_documento, plano_conta, data_emissao, data_vencimento, observacao, usuario_cadastro, hora_cadastro, data_cadastro, situacao, estado_registro, autorizacao, tipo_documento, total_pago, saldo_pagar, plano_conta_mae, valor_original, sub_total, filial, numero_compra, origem_lancamento, nome_fornecedor) VALUES (NULL, '$descricao_conta', '$fornecedor_pesquisa', '$total_geral', '$numero_compra', '$plano_conta', '$data_compra', '$data_pagamento_aux', '$obs_form', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$situacao_pagamento', '$estado_registro', 'SIM', '$movimentacao', '0', '$total_geral', '$plano_conta_mae', '$total_geral', '$sub_total', '$filial', '$numero_compra', '$movimentacao', '$nome_pessoa')");
*/

	}
}
// ======================================================================================================



// ======================================================================================================
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
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_fixo" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$msg_titulo"; ?>
    </div>

	<div class="ct_titulo_2">
    N&ordm; <?php echo"$numero_compra"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php echo"$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:330px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa == "PJ")
    {echo "Raz&atilde;o Social:";}
    else
    {echo "Nome:";}
    ?>
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa == "PJ")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa == "PJ")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa</div></div>";
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
	<?php echo"$telefone_pessoa" ?></div></div>
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
	$cidade_uf = $cidade_pessoa . "/" . $estado_pessoa;
	$conta_caracter = strlen($cidade_uf);
	if ($conta_caracter <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PRODUTO ============================================================================================= -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "$link_imagem_produto"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#009900; text-align:center">
                <?php echo "<b>$produto_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PREÇO DO DIA =========================================================================================== -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; margin-left:56px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Pre&ccedil;o do dia:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "<img src='$servidor/$diretorio_servidor/imagens/preco.png' style='width:60px'>"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#009900; text-align:center">
                <?php echo "<b>$preco_maximo_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= SALDO PRODUTOR ========================================================================================= -->
<?php
// ====== BUSCA SALDO ARMAZENADO ========================================================================
$busca_saldo_arm = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_pesquisa' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
if ($aux_saldo_arm[9] < 0)
{$saldo_armazenado_print = "<div style='color:#FF0000'>" . number_format($aux_saldo_arm[9],2,",",".") . " $unidade_abreviacao </div>";}
else
{$saldo_armazenado_print = "<div style='color:#009900'>" . number_format($aux_saldo_arm[9],2,",",".") . " $unidade_abreviacao </div>";}
// ======================================================================================================
?>

<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; margin-left:57px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Saldo do Produtor:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "<img src='$servidor/$diretorio_servidor/imagens/produtor.png' style='width:60px'>"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; text-align:center">
                <?php echo "<b>$saldo_armazenado_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<div style="width:1000px; height:30px; border:1px solid transparent; float:left"></div>



<!-- ======= QUANTIDADE ============================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Quantidade (<?php echo"$unidade_apelido"; ?>):
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 3)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$quantidade_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$quantidade_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= PREÇO ================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Pre&ccedil;o:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 4)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$preco_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$preco_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= SUB TOTAL =============================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Sub Total:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$sub_total_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  TIPO PRODUTO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Tipo do Produto:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 5)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= FORMA DE ENTREGA ======================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Forma de Entrega:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 6)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$forma_entrega_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$forma_entrega_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA PAGAMENTO ======================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Data Pagamento:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$data_pagamento_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= UMIDADE ================================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Umidade:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$umidade_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= BROCA ================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Broca:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$broca_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= IMPUREZA ================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Impureza:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$impureza_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
	<div style="width:340px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:336px; height:17px; border:1px solid transparent; float:left">
        Observa&ccedil;&atilde;o:
        </div>
        
        <div style="width:336px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:313px; height:14px; overflow:hidden"><?php echo"$obs_form" ?></div></div>
        </div>
	</div>
<!-- ============================================================================================================== -->


<!-- ======= VALOR TOTAL ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Valor Total:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 8)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><b>$total_geral_print</b></div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><b>$total_geral_print</b></div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->





</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div class="espacamento_25"></div>






<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:135px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/compras/cadastro_1_selec_produto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nova Compra</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
    if ($permite_imprimir == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/compras/compra_impressao_1.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir 1 Via</button>
		</form>
    </div>";
	}

	else
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir 1 Via</button>
		</div>";
	}
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
    if ($permite_imprimir == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/compras/compra_impressao_3.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir 2 Vias</button>
		</form>
    </div>";
	}

	else
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir 2 Vias</button>
		</div>";
	}
// =============================================================================================================================


// ====== FICHA DO PRODUTOR ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
		<input type='hidden' name='fornecedor' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='botao' value='seleciona'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Ficha do Produtor</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/compras/index_compras.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
		</form>
    </div>";
// =============================================================================================================================
}

else
{
// ====== BOTAO VOLTAR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/compras/cadastro_3_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_compra' value='$numero_compra' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto' />
	<input type='hidden' name='quantidade_form' value='$quantidade_form' />
	<input type='hidden' name='preco_form' value='$preco_print' />
	<input type='hidden' name='cod_tipo_produto_form' value='$cod_tipo_produto_form' />
	<input type='hidden' name='forma_entrega_form' value='$forma_entrega_form' />
	<input type='hidden' name='desconto_form' value='$desconto_print' />
	<input type='hidden' name='data_pagamento_form' value='$data_pagamento_form' />
	<input type='hidden' name='obs_form' value='$obs_form' />
	<input type='hidden' name='umidade_form' value='$umidade_form' />
	<input type='hidden' name='broca_form' value='$broca_form' />
	<input type='hidden' name='impureza_form' value='$impureza_form' />
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
// =============================================================================================================================
}

?>
</div>








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