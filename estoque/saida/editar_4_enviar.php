<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'editar_4_enviar';
$titulo = 'Editar Romaneio de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$data_romaneio = date('Y-m-d', time());
$filial = $filial_usuario;

$numero_romaneio = $_POST["numero_romaneio"];
$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$peso_form = Helpers::ConvertePeso($_POST["peso_form"]);
$peso_inicial_form = Helpers::ConvertePeso($_POST["peso_inicial_form"]);
$peso_final_form = Helpers::ConvertePeso($_POST["peso_final_form"]);
$cod_sacaria_form = $_POST["cod_sacaria_form"];
$quant_sacaria_form = Helpers::ConvertePeso($_POST["quant_sacaria_form"]);
$desconto_form = Helpers::ConvertePeso($_POST["desconto_form"]);
$quant_volume_form = Helpers::ConvertePeso($_POST["quant_volume_form"]);
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$romaneio_manual_form = $_POST["romaneio_manual_form"];
$filial_origem_form = $_POST["filial_origem_form"];
$motorista_form = $_POST["motorista_form"];
$motorista_cpf_form = $_POST["motorista_cpf_form"];
$placa_veiculo_form = $_POST["placa_veiculo_form"];
$obs_form = $_POST["obs_form"];

$movimentacao = "SAIDA";
$situacao_romaneio = "FECHADO";
$situacao = "BALANCA";
$estado_registro = "ATIVO";
$un_aux = "KG";
$cod_un_aux = "20";

if ($peso_form == "")
{$peso = 0;}
else
{$peso = $peso_form;}

if ($peso_inicial_form == "")
{$peso_inicial = 0;}
else
{$peso_inicial = $peso_inicial_form;}

if ($peso_final_form == "")
{$peso_final = 0;}
else
{$peso_final = $peso_final_form;}

if ($desconto_form == "")
{$desconto = 0;}
else
{$desconto = $desconto_form;}

if ($quant_sacaria_form == "")
{$quant_sacaria = 0;}
else
{$quant_sacaria = $quant_sacaria_form;}

if ($quant_volume_form == "")
{$quant_volume = 0;}
else
{$quant_volume = $quant_volume_form;}

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());

$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];
// ================================================================================================================


// ================================================================================================================
if ($numero_romaneio == "")
{header ("Location: $servidor/$diretorio_servidor/estoque/saida/romaneio_nao_localizado_2.php");
exit;}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
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
$usuario_alteracao_preco = $aux_bp[16];
$data_alteracao_preco = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_form' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}

if ($linhas_fornecedor == 0)
{$cidade_uf_fornecedor = "";}
else
{$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA ROMANEIO MANUAL - COMPRAS ======================================================================
$busca_rmc = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND num_romaneio_manual='$romaneio_manual_form' AND numero_romaneio!='$numero_romaneio' AND num_romaneio_manual!='' AND num_romaneio_manual!='0'");
$linhas_rmc = mysqli_num_rows ($busca_rmc);
$aux_rmc = mysqli_fetch_row($busca_rmc);

$numero_rmc = $aux_rmc[1];
// =============================================================================================================


// ====== BUSCA ROMANEIO MANUAL - ESTOQUE  ============================================================================
$busca_rme = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio!='$numero_romaneio' AND num_romaneio_manual!='' AND num_romaneio_manual!='0' AND (numero_romaneio='$romaneio_manual_form' OR num_romaneio_manual='$romaneio_manual_form')");
$linhas_rme = mysqli_num_rows ($busca_rme);
$aux_rme = mysqli_fetch_row($busca_rme);

$numero_rme = $aux_rme[1];
// ==================================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$cod_sacaria_form' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descricao_sacaria = "";}
else
{$descricao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
// ================================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA FILIAL ORIGEM =====================================================================================
$busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial_origem_form'");
$aux_filial_origem = mysqli_fetch_row($busca_filial_origem);

$apelido_filial_origem = $aux_filial_origem[2];
// ======================================================================================================


// ====== CALCULA QUANTIDADE ======================================================================================
// round = arredondamento automático Ex.: 2,5 = 3  2,4 = 2
// ceil = arredondamento pra cima Ex.: 2,2 = 3
// floor = arredondamento pra baixo Ex.: 2,8 = 2
$desconto_sacaria = round($peso_sacaria * $quant_sacaria);

if ($filial_config[9] == "S")
{$quantidade = ($peso_final - $peso_inicial - $desconto_sacaria - $desconto);}

else
{$quantidade = ($peso - $desconto_sacaria - $desconto);
$peso_final = $peso;}
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
if ($permissao[16] == "S")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA FINALIZAR ========================================================================
if ($permissao[77] == "S")
{$permite_finalizar = "SIM";}
else
{$permite_finalizar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOTA FISCAL =======================================================================
if ($permissao[82] == "S")
{$permite_nf = "SIM";}
else
{$permite_nf = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =======================================================================
if ($permissao[79] == "S")
{$permite_imprimir = "SIM";}
else
{$permite_imprimir = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOVO ROMANEIO =====================================================================
if ($permissao[17] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ========================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "EDITAR_ROMANEIO")
{
	if ($fornecedor_form == "" or $linhas_fornecedor == 0)
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um fornecedor.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($cod_produto_form == "" or $linhas_bp == 0)
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($filial_config[9] == "S" and ($peso_inicial < 0 or !is_numeric($peso_inicial)))
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Peso inicial inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($filial_config[9] == "S" and ($peso_final <= 0 or !is_numeric($peso_final)))
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Peso final inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($filial_config[9] == "N" and ($peso <= 0 or !is_numeric($peso)))
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Peso inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($cod_sacaria_form == "")
	{$erro = 6;
	$msg = "<div style='color:#FF0000'>Selecione o Tipo de Sacaria</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif (!is_numeric($quant_sacaria) or $quant_sacaria < 0)
	{$erro = 7;
	$msg = "<div style='color:#FF0000'>Quantidade de sacaria inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif (!is_numeric($desconto) or $desconto < 0)
	{$erro = 8;
	$msg = "<div style='color:#FF0000'>Desconto inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif (!is_numeric($quant_volume) or $quant_volume < 0)
	{$erro = 9;
	$msg = "<div style='color:#FF0000'>Quantidade de volume de sacaria inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif (!is_numeric($quantidade) or $quantidade <= 0)
	{$erro = 10;
	$msg = "<div style='color:#FF0000'>Peso L&iacute;quido inv&aacute;lido. A quantidade não pode ser menor ou igual a zero.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($romaneio_manual_form != "" and ($linhas_rmc >= 1 or $linhas_rme >= 1))
	{$erro = 11;
	$msg = "<div style='color:#FF0000'>N&uacute;mero de romaneio manual j&aacute; usado em outro romaneio.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($config[33] == "S" and $filial_origem_form == "")
	{$erro = 12;
	$msg = "<div style='color:#FF0000'>Filial origem &eacute; obrigat&oacute;rio para o cadastro.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	elseif ($achou_romaneio_duplicidade >= 1)
	{$erro = 13;
	$msg = "<div style='color:#FF0000'>N&uacute;mero de romaneio j&aacute; existente. Romaneio em duplicidade.</div>";
	$msg_titulo = "<div style='color:#009900'>Editar Romaneio de Sa&iacute;da</div>";
	$num_romaneio_print = "<div style='color:#009900'>N&ordm; $numero_romaneio</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Romaneio editado com Sucesso!</div>";
	$num_romaneio_print = "<div style='color:#0000FF'>N&ordm; $numero_romaneio</div>";
	
	$editar = mysqli_query ($conexao, "UPDATE estoque SET fornecedor='$fornecedor_form', produto='$produto_print', tipo='$tipo_print', peso_inicial='$peso_inicial', peso_final='$peso_final', desconto_sacaria='$desconto_sacaria', desconto='$desconto', quantidade='$quantidade', tipo_sacaria='$cod_sacaria_form', placa_veiculo='$placa_veiculo_form', motorista='$motorista_form', observacao='$obs_form', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', quantidade_sacaria='$quant_sacaria', motorista_cpf='$motorista_cpf_form', num_romaneio_manual='$romaneio_manual_form', filial_origem='$filial_origem_form', quant_volume_sacas='$quant_volume', cod_produto='$cod_produto_form', cod_tipo='$cod_tipo_produto_form', fornecedor_print='$fornecedor_print' WHERE numero_romaneio='$numero_romaneio'");

//	$editar = mysqli_query ($conexao, "UPDATE estoque SET fornecedor='$fornecedor_form', produto='$produto_print', tipo='$tipo_print', peso_inicial='$peso_inicial', peso_final='$peso_final', desconto_sacaria='$desconto_sacaria', desconto='$desconto', quantidade='$quantidade', tipo_sacaria='$cod_sacaria_form', placa_veiculo='$placa_veiculo_form', motorista='$motorista_form', observacao='$obs_form', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', quantidade_sacaria='$quant_sacaria', motorista_cpf='$motorista_cpf_form', num_romaneio_manual='$romaneio_manual_form', filial_origem='$filial_origem_form', quant_volume_sacas='$quant_volume', cod_produto='$cod_produto_form', cod_tipo='$cod_tipo_produto_form', fornecedor_print='$fornecedor_print' WHERE numero_romaneio='$numero_romaneio'");

	}
}
// ======================================================================================================


// ======================================================================================================
$peso_print = number_format($peso,$config[31],",",".");
$peso_inicial_print = number_format($peso_inicial,$config[31],",",".");
$peso_final_print = number_format($peso_final,$config[31],",",".");
$desconto_print = number_format($desconto,$config[31],",",".");
$quant_sacaria_print = number_format($quant_sacaria,0,",",".");
$quant_volume_print = number_format($quant_volume,0,",",".");
$peso_liquido_print = number_format($quantidade,$config[31],",",".");
// ======================================================================================================


// ======================================================================================================
include ('../../includes/head.php'); 
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>




<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
	<?php echo"$msg_titulo"; ?>
    </div>


	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
	<?php echo"$num_romaneio_print"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
    <?php echo"$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:160px; width:1080px; border:0px solid #0000FF; margin:auto">


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
    <div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
    Cliente:
    </div>
</div>

<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>
    
	<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div>
	</div>
    
	<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div>
	</div>

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

    <div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
        <div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cidade_uf_fornecedor</b>" ?></div>
    </div>
    
    <div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
		<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div>
	</div>

</div>
<!-- ======================================================================================================================= -->


<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
    Produto:
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
    <!-- xxxxxxxxxxxxxx: -->
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
	<!-- xxxxxxxxxxxxxx: -->
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>
-->
<!-- ======================================================================================================================= -->


</div>
<!-- ======================================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:195px; margin:auto; color:#003466; border:1px solid transparent">


<?php
if ($filial_config[9] == "S")
{echo"
<!-- =======  PESO INICIAL =========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Inicial:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>";
        if ($erro == 3)
        {echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_inicial_print</div></div>";}
        else
        {echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_inicial_print Kg</div></div>";}
        echo"
		</div>
	</div>
<!-- ================================================================================================================ -->



<!-- =======  PESO FINAL ============================================================================================ -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Final:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>";
        if ($erro == 4)
        {echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_final_print</div></div>";}
        else
        {echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_final_print Kg</div></div>";}
        echo"
		</div>
	</div>
<!-- ================================================================================================================ -->";
}

else
{echo"
<!-- =======  PESO ================================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>";
        if ($erro == 5)
        {echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_print</div></div>";}
        else
        {echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$peso_print Kg</div></div>";}
        echo"
		</div>
	</div>
<!-- ================================================================================================================ -->
";}

?>



<!-- =======  TIPO SACARIA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Tipo Sacaria:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 6)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$descricao_sacaria</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$descricao_sacaria</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= QUANTIDADE SACARIA =========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Quant. de Sacaria (Un):"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 7)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_sacaria_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_sacaria_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= OUTROS DESCONTOS =========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Outros Descontos (Kg):"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 8)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$desconto_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$desconto_print Kg</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= QUANTIDADE VOLUME SACAS ================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Quant. Volume de Sacas:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 9)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_volume_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$quant_volume_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->



<!-- =======  TIPO PRODUTO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Tipo do Produto:"; ?>
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$tipo_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= NUMERO ROMANEIO MANUAL ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"N&ordm; Romaneio Manual:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 11)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$romaneio_manual_form</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$romaneio_manual_form</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->



<?php
if ($config[34] == "S")
{echo"
<!-- ======= MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$motorista_form</div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[35] == "S")
{echo"
<!-- ======= CPF MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        CPF Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$motorista_cpf_form</div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[36] == "S")
{echo"
<!-- ======= PLACA VEICULO ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Placa do Ve&iacute;culo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$placa_veiculo_form</div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[33] == "S")
{echo"
<!-- =======  FILIAL ORIGEM ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Filial Origem:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>";

		if ($erro == 12)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$apelido_filial_origem</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$apelido_filial_origem</div></div>";}

		echo "
        </div>
	</div>
<!-- ================================================================================================================ -->
";}

?>



<!-- =======  OBSERVAÇÃO ============================================================================================ -->
	<div style="width:682px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:674px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Observa&ccedil;&atilde;o:"; ?>
        </div>
        
        <div style="width:674px; height:25px; float:left; border:1px solid transparent">
        <div style="width:666px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
        <div style="margin-top:5px; margin-left:5px; width:656px; height:14px; overflow:hidden"><?php echo"$obs_form" ?></div></div>
        </div>
	</div>
<!-- ============================================================================================================== -->



<!-- ======= PESO LIQUIDO ========================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Peso L&iacute;quido:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 10)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'><b>$peso_liquido_print</b></div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'><b>$peso_liquido_print Kg</b></div></div>";}
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
		<form action='$servidor/$diretorio_servidor/estoque/saida/cadastro_1_selec_produto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Novo Romaneio</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO EDITAR ========================================================================================================
    if ($permite_editar == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/editar_3_formulario.php' method='post'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
		<input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
		</form>
    </div>";
	}

	else
	{
        echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Editar</button>
		</div>";
	}
// =============================================================================================================================


// ====== BOTAO NOTA FISCAL =================================================================================================
    if ($permite_nf == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_saida/nota_fiscal.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_br'>
		<input type='hidden' name='data_final' value='$data_final_br'>
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
		<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
		<input type='hidden' name='numero_romaneio_form' value='$numero_romaneio_form'>
		<input type='hidden' name='situacao_romaneio_form' value='$situacao_romaneio_form'>
		<input type='hidden' name='forma_pesagem_form' value='$forma_pesagem_form'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nota Fiscal</button>
		</form>
    </div>";
	}

	else
	{
        echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Nota Fiscal</button>
		</div>";
	}
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
    if ($permite_imprimir == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$numero_romaneio'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir</button>
		</form>
    </div>";
	}

	else
	{
	echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir</button>
		</div>";
	}
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/saida_relatorio_produto.php' method='post'>
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
	<form name='voltar' action='$servidor/$diretorio_servidor/estoque/saida/editar_3_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form' />
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
	<input type='hidden' name='peso_inicial_form' value='$peso_inicial_form' />
	<input type='hidden' name='peso_final_form' value='$peso_final_form' />
	<input type='hidden' name='peso_form' value='$peso_form' />
	<input type='hidden' name='cod_sacaria_form' value='$cod_sacaria_form' />
	<input type='hidden' name='cod_tipo_produto_form' value='$cod_tipo_produto_form' />
	<input type='hidden' name='desconto_form' value='$desconto_form' />
	<input type='hidden' name='quant_sacaria_form' value='$quant_sacaria_form' />
	<input type='hidden' name='quant_volume_form' value='$quant_volume_form' />
	<input type='hidden' name='filial_origem_form' value='$filial_origem_form' />
	<input type='hidden' name='motorista_form' value='$motorista_form' />
	<input type='hidden' name='motorista_cpf_form' value='$motorista_cpf_form' />
	<input type='hidden' name='placa_veiculo_form' value='$placa_veiculo_form' />
	<input type='hidden' name='romaneio_manual_form' value='$romaneio_manual_form' />
	<input type='hidden' name='cod_lote_form' value='$cod_lote_form' />
	<input type='hidden' name='obs_form' value='$obs_form' />

	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>

    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
// =============================================================================================================================
}

?>
</div>








</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>