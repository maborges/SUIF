<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "cadastro_1_formulario";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================

// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');

$nome_form = $_POST["nome_form"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_pessoa_w = $_POST["nome_pessoa_w"];
$tipo_pessoa_w = $_POST["tipo_pessoa_w"];
$cpf_cnpj_w = $_POST["cpf_cnpj_w"];
$telefone_pessoa_w = $_POST["telefone_pessoa_w"];
$cidade_pessoa_w = $_POST["cidade_pessoa_w"];
$estado_pessoa_w = $_POST["estado_pessoa_w"];
$cod_produto_form = $_POST["cod_produto_form"];

$quantidade_form = $_POST["quantidade_form"];
$preco_form = $_POST["preco_form"];
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$forma_entrega_form = $_POST["forma_entrega_form"];
$data_pagamento_form = $_POST["data_pagamento_form"];
$umidade_form = $_POST["umidade_form"];
$broca_form = $_POST["broca_form"];
$impureza_form = $_POST["impureza_form"];
$obs_form = $_POST["obs_form"];
// ========================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==========================================================================
if (empty($botao))
{
include ("../../includes/conecta_bd.php");
$busca_num_compra = mysqli_query ($conexao, "SELECT contador_numero_compra FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[0];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
include ("../../includes/desconecta_bd.php");
}

else
{
$numero_compra = $_POST["numero_compra"];
}
// ================================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($botao == "BUSCA_FORNECEDOR")
{
include ("../../includes/conecta_bd.php");

$busca_pessoa_geral = mysqli_query ($conexao, 
"SELECT 
	codigo, 
	nome, 
	tipo, 
	cpf, 
	cnpj, 
	cidade, 
	estado, 
	telefone_1,
	codigo_pessoa,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	usuario_alteracao,
	hora_alteracao,
	data_alteracao
FROM 
	cadastro_pessoa 
WHERE 
	estado_registro='ATIVO' AND 
	nome LIKE '%$nome_form%' 
ORDER BY 
	nome");

include ("../../includes/desconecta_bd.php");

$linha_pessoa_geral = mysqli_num_rows ($busca_pessoa_geral);
}
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
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
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ======================================================================================================


// ====== BUSCA SALDO ARMAZENADO ========================================================================
$busca_saldo_arm = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_pesquisa' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
if ($aux_saldo_arm[9] < 0)
{$saldo_armazenado_print = "<div style='color:#FF0000'>" . number_format($aux_saldo_arm[9],2,",",".") . " $unidade_abreviacao </div>";}
else
{$saldo_armazenado_print = "<div style='color:#009900'>" . number_format($aux_saldo_arm[9],2,",",".") . " $unidade_abreviacao </div>";}
// ======================================================================================================


// ====== CALCULA SUB TOTAL =============================================================================
if ($botao == "CALCULA_TOTAL")
{
$quantidade_aux = Helpers::ConvertePeso($_POST["quantidade_form"], $config[30]);
$preco_aux = Helpers::ConverteValor($_POST["preco_form"]);

$sub_total = ($quantidade_aux * $preco_aux);
$sub_total_print = "R$ " . number_format($sub_total,2,",",".");
}
// ======================================================================================================


// ====== MONTA MENSAGEM =================================================================================
if ($fornecedor_pesquisa == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Selecione um fornecedor</div>";}
elseif ($linha_pessoa == 0)
{$erro = 2;
$msg = "<div style='color:#FF0000'>Fornecedor inv&aacute;lido</div>";}
elseif ($cod_seleciona_produto == "" or $linhas_bp == 0)
{$erro = 3;
$msg = "<div style='color:#FF0000'>Selecione um produto</div>";}
else
{$erro = 0;
$msg = "";}
// ======================================================================================================


// =============================================================================
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

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/maskbrphone.js"></script>
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskMoney.js"></script>
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.mask_2.js"></script>

<script>
jQuery(function($){
	// MASK
	$("#cpf").mask("999.999.999-99");
	$("#cnpj").mask("99.999.999/9999-99");
	$("#rg").mask("99.999.999-9");
	$("#data").mask("99/99/9999");
	$("#hora").mask("99:99:99");
	$("#cep").mask("99.999-999");
	$("#letra").mask("aaaaaa");
	$("#letra_num").mask("*****");
	// "9" = Somente Número
	// "a" = Somente Letra
	// "*" = Letra e Números
	
	// MASK_2
	$("#conta_bancaria").mask_2("#.##A-A" , { reverse:true});
	$("#cpf_2").mask_2("000.000.000-00");
	$("#data_2").mask_2("00/00/0000", { placeholder: "__/__/____" });
	$("#hora_2").mask_2("00:00:00");
	$("#data_hora_2").mask_2("00/00/0000 00:00:00");
	$("#dinheiro").mask_2("000.000.000,00" , { reverse : true});
	// "0" = Um digito obrigatório
	// "9" = Um digito opcional
	// "#" = Um digito com recurção
	// "A" = Uma letra de a até z (maiúsculas ou minusculas) ou um digito
	// "S" = Uma letra de a até z (maiúsculas ou minusculas) sem digito 

	// VALOR MONETÁRIO (R$ 8.888,88)
	$("#valor_money").maskMoney({
		symbol:'R$ ', //Símbolo a ser usado antes de os valores do usuário. padrão: ‘EUA $’
		showSymbol:true, //definir se o símbolo deve ser exibida ou não. padrão: false
		thousands:'.', //Separador de milhares. padrão: ‘,’
		decimal:',', //Separador do decimal. padrão: ‘.’
		precision:2, //Quantas casas decimais são permitidas. Padrão: 2
		symbolStay:true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
	});

	// VALOR MONETÁRIO (R$ 8.888,88)
	$("#valor_money_2").maskMoney({
		symbol:'R$ ', //Símbolo a ser usado antes de os valores do usuário. padrão: ‘EUA $’
		showSymbol:true, //definir se o símbolo deve ser exibida ou não. padrão: false
		thousands:'.', //Separador de milhares. padrão: ‘,’
		decimal:',', //Separador do decimal. padrão: ‘.’
		precision:2, //Quantas casas decimais são permitidas. Padrão: 2
		symbolStay:true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
	});

	// QUANTIDADE 2 CASAS DECIMAIS (8.888,88)
	$("#quant_2").maskMoney({
		thousands:'.', //Separador de milhares. padrão: ‘,’
		decimal:',', //Separador do decimal. padrão: ‘.’
		precision:2, //Quantas casas decimais são permitidas. Padrão: 2
		symbolStay:true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
	});

	// TELEFONE COM DDD 1
	$("#telddd_1").maskbrphone({
	  useDdd: true,
	  useDddParenthesis: true,
	  dddSeparator: ' ',
	  numberSeparator: '-'
	});

	// TELEFONE COM DDD 2
	$("#telddd_2").maskbrphone({
	  useDdd: true,
	  useDddParenthesis: true,
	  dddSeparator: ' ',
	  numberSeparator: '-'
	});

});
</script>

</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco
<?php
if ($botao == "CALCULA_TOTAL")
{echo"('select_tipo')";}
else
{echo"('quant')";}
?>
;">

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
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1" style="width:1030px">
	<div class="ct_titulo_1" style="width:500px">
	<?php echo "$titulo"; ?>
    </div>

	<div class="ct_titulo_2" style="width:500px">
    N&ordm; <?php echo"$numero_compra"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2" style="width:1030px">
	<div class="ct_subtitulo_left" style="width:500px">
	<?php // echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:500px">
    <?php echo"$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1030px; height:70px; margin:auto; border:1px solid transparent; color:#003466">

<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Fornecedor:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
	if (empty($fornecedor_pesquisa))
	{
	echo "
	<form action='$servidor/$diretorio_servidor/compras/compras/cadastro_1_formulario.php' method='post' />
	<input type='hidden' name='botao' value='BUSCA_FORNECEDOR' />
	<input type='hidden' name='numero_compra' value='$numero_compra' />
	<input type='text' name='nome_form' id='ok' onkeyup='alteraMaiusculo(this)'
	style='width:495px; height:23px; border:1px solid #009900; float:left; font-size:12px; text-align:left; color:#003466; background-color:#EEE; padding-left:5px; font-weight:bold' />
	</form>";
	}

	else
	{	
    echo "<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa_w</b></div></div>";
	}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
 
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_w == "PJ" or $tipo_pessoa_w == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_cnpj_w</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ======= TELEFONE ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_pessoa_w" ?></div></div>
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
	if (!empty($fornecedor_pesquisa))
	{$cidade_uf = $cidade_pessoa_w . "/" . $estado_pessoa_w;}
	else
	{$cidade_uf = "";}
	
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

</div>
<!-- ============================================================================================================= -->



<!-- ========= LISTA DE FORNECEDORES ============================================================================= -->
<?php
if ($linha_pessoa_geral >= 1 and $botao == "BUSCA_FORNECEDOR")
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='80px'>C&oacute;digo</td>
<td width='420px'>Nome</td>
<td width='180px'>CPF/CNPJ</td>
<td width='150px'>Telefone</td>
<td width='180px'>Cidade/UF</td>
</tr>
</table>";


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($p=1 ; $p<=$linha_pessoa_geral ; $p++)
{
$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

// ====== DADOS DO CADASTRO ============================================================================
$codigo_w = $aux_pessoa_geral[0];
$nome_pessoa_w = $aux_pessoa_geral[1];
$tipo_pessoa_w = $aux_pessoa_geral[2];
$cpf_pessoa_w = $aux_pessoa_geral[3];
$cnpj_pessoa_w = $aux_pessoa_geral[4];
$cidade_pessoa_w = $aux_pessoa_geral[5];
$estado_pessoa_w = $aux_pessoa_geral[6];
$telefone_pessoa_w = $aux_pessoa_geral[7];
$codigo_pessoa_w = $aux_pessoa_geral[8];
$usuario_cadastro_w = $aux_pessoa_geral[9];
$hora_cadastro_w = $aux_pessoa_geral[10];
$data_cadastro_w = $aux_pessoa_geral[11];
$usuario_alteracao_w = $aux_pessoa_geral[12];
$hora_alteracao_w = $aux_pessoa_geral[13];
$data_alteracao_w = $aux_pessoa_geral[14];


if ($tipo_pessoa_w == "PF" or $tipo_pessoa_w == "pf")
{$cpf_cnpj_w = $cpf_pessoa_w;}
else
{$cpf_cnpj_w = $cnpj_pessoa_w;}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = "Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	echo "
	<tr class='tabela_1'>
	<td width='80px' align='left'><div style='margin-left:15px'>$codigo_w</div></td>
	<td width='420px' height='24px' align='left'>
		<div style='margin-left:10px'>
		<form action='$servidor/$diretorio_servidor/compras/compras/cadastro_1_formulario.php' method='post'>
		<input type='hidden' name='botao' value='SELECIONA_FORNECEDOR' />
		<input type='hidden' name='fornecedor_pesquisa' value='$codigo_pessoa_w' />
		<input type='hidden' name='nome_pessoa_w' value='$nome_pessoa_w' />
		<input type='hidden' name='tipo_pessoa_w' value='$tipo_pessoa_w' />
		<input type='hidden' name='cpf_cnpj_w' value='$cpf_cnpj_w' />
		<input type='hidden' name='telefone_pessoa_w' value='$telefone_pessoa_w' />
		<input type='hidden' name='cidade_pessoa_w' value='$cidade_pessoa_w' />
		<input type='hidden' name='estado_pessoa_w' value='$estado_pessoa_w' />
		<input type='hidden' name='numero_compra' value='$numero_compra' />
		<input class='tabela_1' type='submit' style='width:410px; height:22px; text-align:left; border:0px solid #000; background-color:transparent' value='$nome_pessoa_w'>
		</form>
		</div>
	</td>
	<td width='180px' align='center'>$cpf_cnpj_w</td>
	<td width='150px' align='center'>$telefone_pessoa_w</td>
	<td width='180px' align='center'>$cidade_pessoa_w/$estado_pessoa_w</td>
	</tr>";

}

echo "</table>";

echo "</div>";
}
// =================================================================================================================

// =================================================================================================================
if ($linha_pessoa_geral == 0 and $botao == "BUSCA_FORNECEDOR")
{echo "
<div style='height:30px; width:1030; border:0px solid #000; color:#666; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum fornecedor encontrado.</i></div>";}
// =================================================================================================================

?>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:330px; margin:auto; border:1px solid transparent; color:#003466">




<!-- ======= PRODUTO ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/compras/cadastro_1_formulario.php" method="post" name="seleciona_produto" />
<input type="hidden" name="botao" value="SELECIONA_PRODUTO" />
<input type="hidden" name="numero_compra" value="<?php echo"$numero_compra"; ?>" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="nome_pessoa_w" value="<?php echo"$nome_pessoa_w"; ?>" />
<input type="hidden" name="tipo_pessoa_w" value="<?php echo"$tipo_pessoa_w"; ?>" />
<input type="hidden" name="cpf_cnpj_w" value="<?php echo"$cpf_cnpj_w"; ?>" />
<input type="hidden" name="telefone_pessoa_w" value="<?php echo"$telefone_pessoa_w"; ?>" />
<input type="hidden" name="cidade_pessoa_w" value="<?php echo"$cidade_pessoa_w"; ?>" />
<input type="hidden" name="estado_pessoa_w" value="<?php echo"$estado_pessoa_w"; ?>" />

    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_form" class="form_select" onchange="document.seleciona_produto.submit()" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:154px; font-size:12px" />
    <?php
	include ("../../includes/cadastro_produto.php"); 

	for ($i=0 ; $i<=count($cadastro_produto) ; $i++)
	{
        if ($cadastro_produto[$i]["codigo"] == $cod_produto_form)
        {echo "<option selected='selected' value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
        else
        {echo "<option value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
	}
    ?>
    </select>
</form>
    </div>
</div>
<!-- ================================================================================================================ -->







<!-- ======= QUANTIDADE ============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
<form name="calcula_total" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/compras/cadastro_1_formulario.php" method="post" />
<input type="hidden" name="botao" value="CALCULA_TOTAL" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="cod_seleciona_produto" value="<?php echo"$cod_seleciona_produto"; ?>" />
<input type="hidden" name="numero_compra" value="<?php echo"$numero_compra"; ?>" />
<input type="hidden" name="cod_tipo_produto_form" value="<?php echo"$cod_tipo_produto_form"; ?>" />
<input type="hidden" name="forma_entrega_form" value="<?php echo"$forma_entrega_form"; ?>" />
<input type="hidden" name="desconto_form" value="<?php echo"$desconto_form"; ?>" />
<input type="hidden" name="data_pagamento_form" value="<?php echo"$data_pagamento_form"; ?>" />
<input type="hidden" name="umidade_form" value="<?php echo"$umidade_form"; ?>" />
<input type="hidden" name="broca_form" value="<?php echo"$broca_form"; ?>" />
<input type="hidden" name="impureza_form" value="<?php echo"$impureza_form"; ?>" />
<input type="hidden" name="obs_form" value="<?php echo"$obs_form"; ?>" />

    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Quantidade (<?php echo"$unidade_apelido"; ?>):
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="quantidade_form" id="quant" class="form_input" maxlength="12" onkeypress="<?php echo"$config[43]"; ?>" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$quantidade_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  PREÇO ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Pre&ccedil;o:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="preco_form" class="form_input" id="valor_money" maxlength="15" onblur="document.calcula_total.submit()" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$preco_form"; ?>" />
    </form>
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- ======= SUB TOTAL =============================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Sub Total:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden">
		<?php echo"<b>$sub_total_print</b>" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  TIPO PRODUTO ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/compras/cadastro_4_enviar.php" method="post" />
<input type="hidden" name="botao" value="NOVA_COMPRA" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="cod_seleciona_produto" value="<?php echo"$cod_seleciona_produto"; ?>" />
<input type="hidden" name="numero_compra" value="<?php echo"$numero_compra"; ?>" />
<input type="hidden" name="quantidade_form" value="<?php echo"$quantidade_form"; ?>" />
<input type="hidden" name="preco_form" value="<?php echo"$preco_form"; ?>" />

    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Tipo do Produto:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_tipo_produto_form" class="form_select" id="select_tipo" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
    $busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_seleciona_produto' AND estado_registro='ATIVO' ORDER BY codigo");
    $linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);
    
    for ($tp=1 ; $tp<=$linhas_tipo_produto ; $tp++)
    {
    $aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
    
    if ($aux_tipo_produto[0] == $cod_tipo_produto_form)
    {echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
    else
    {echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= FORMA DE ENTREGA ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Forma de Entrega:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="forma_entrega_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
    $busca_forma_entrega = mysqli_query ($conexao, "SELECT * FROM select_forma_entrega WHERE estado_registro='ATIVO' ORDER BY codigo");
    $linhas_forma_entrega = mysqli_num_rows ($busca_forma_entrega);
    
    for ($e=1 ; $e<=$linhas_forma_entrega ; $e++)
    {
    $aux_forma_entrega = mysqli_fetch_row($busca_forma_entrega);	
    
    if ($aux_forma_entrega[0] == $forma_entrega_form)
    {echo "<option selected='selected' value='$aux_forma_entrega[0]'>$aux_forma_entrega[1]</option>";}
    else
    {echo "<option value='$aux_forma_entrega[0]'>$aux_forma_entrega[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA PAGAMENTO ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Data Pagamento:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_pagamento_form" class="form_input" id="calendario" maxlength="10" onkeypress="mascara(this,data)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$data_pagamento_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- ======= UMIDADE ================================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Umidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="umidade_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($p=1 ; $p<=$linhas_porcentagem ; $p++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
    
    if ($aux_porcentagem[1] == $umidade_form)
    {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    else
    {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BROCA ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Broca:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="broca_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($p=1 ; $p<=$linhas_porcentagem ; $p++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
    
    if ($aux_porcentagem[1] == $broca_form)
    {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    else
    {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= IMPUREZA =============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Impureza:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="impureza_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($p=1 ; $p<=$linhas_porcentagem ; $p++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
    
    if ($aux_porcentagem[1] == $impureza_form)
    {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    else
    {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Observa&ccedil;&atilde;o:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="obs_form" class="form_input" maxlength="150" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:486px; text-align:left; padding-left:5px" value="<?php echo"$obs_form"; ?>" />
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






</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">
	
	<?php
	if ($erro != 0)
	{echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/compras/compras/cadastro_1_selec_produto.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
	</div>";}


	else
	{echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/compras/compras/index_compras.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</a>
	</div>";}

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