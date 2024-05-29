<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_4_formulario";
$titulo = "Transfer&ecirc;ncia entre Produtores";
$modulo = "compras";
$menu = "ficha_produtor";
// ================================================================================================================

// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;

$fornecedor_origem = $_POST["fornecedor_pesquisa"];
$fornecedor_destino = $_POST["fornecedor_2_pesquisa"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];

$quantidade_form = $_POST["quantidade_form"];
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$obs_form = $_POST["obs_form"];
// ========================================================================================================


// ====== CONTADOR NÚMERO TRANSFERENCIA ===================================================================
if ($botao == "FORMULARIO")
{
$busca_numero_transferencia = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnt = mysqli_fetch_row($busca_numero_transferencia);
$numero_transferencia = $aux_bnt[9];

$contador_num_transferencia = $numero_transferencia + 1;
$altera_contador_t = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_transferencia='$contador_num_transferencia'");
}

else
{
$numero_transferencia = $_POST["numero_transferencia"];
}
// ================================================================================================================


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


if ($botao == "FORMULARIO")
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


// ====== BUSCA PESSOA ENTRADA  ==============================================================================
$busca_pessoa_e = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_destino' ORDER BY nome");
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


// ====== BUSCA SALDO ARMAZENADO DESTINO =====================================================================
$busca_saldo_arm_e = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_destino' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
$aux_saldo_arm_e = mysqli_fetch_row($busca_saldo_arm_e);
if ($aux_saldo_arm_e[9] < 0)
{$saldo_armazenado_e_print = "<div style='color:#FF0000'>" . number_format($aux_saldo_arm_e[9],2,",",".") . " $unidade_abreviacao </div>";}
else
{$saldo_armazenado_e_print = "<div style='color:#0000FF'>" . number_format($aux_saldo_arm_e[9],2,",",".") . " $unidade_abreviacao </div>";}
// ======================================================================================================


// ====== BUSCA PESSOA SAIDA  ================================================================================
$busca_pessoa_s = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_origem' ORDER BY nome");
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


// ====== BUSCA SALDO ARMAZENADO DESTINO =====================================================================
$busca_saldo_arm_s = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_origem' AND filial='$filial' AND cod_produto='$cod_seleciona_produto'");
$aux_saldo_arm_s = mysqli_fetch_row($busca_saldo_arm_s);
if ($aux_saldo_arm_s[9] < 0)
{$saldo_armazenado_s_print = "<div style='color:#FF0000'>" . number_format($aux_saldo_arm_s[9],2,",",".") . " $unidade_abreviacao </div>";}
else
{$saldo_armazenado_s_print = "<div style='color:#0000FF'>" . number_format($aux_saldo_arm_s[9],2,",",".") . " $unidade_abreviacao </div>";}
// ======================================================================================================



// ====== MONTA MENSAGEM =================================================================================
if ($fornecedor_origem == "" or $fornecedor_destino == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Selecione um fornecedor</div>";}
elseif ($cod_seleciona_produto == "" or $linhas_bp == 0)
{$erro = 2;
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
<body onload="javascript:foco('quant');">

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
<div class="ct_fixo" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
    N&ordm; <?php echo"$numero_transferencia"; ?>
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
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "$link_imagem_produto"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#003466; text-align:center">
                <?php echo "<b>$produto_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= SALDO PRODUTOR ORIGEM ========================================================================================= -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; margin-left:57px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Saldo do Produtor Origem:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "<img src='$servidor/$diretorio_servidor/imagens/produtor.png' style='width:60px'>"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; text-align:center">
                <?php echo "<b>$saldo_armazenado_s_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= SALDO PRODUTOR DESTINO ========================================================================================= -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; margin-left:57px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Saldo do Produtor Destino:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "<img src='$servidor/$diretorio_servidor/imagens/produtor.png' style='width:60px'>"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; text-align:center">
                <?php echo "<b>$saldo_armazenado_e_print</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<div style="width:1000px; height:30px; border:1px solid transparent; float:left"></div>


<!-- ======= QUANTIDADE ============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/transferencias/cadastro_5_enviar.php" method="post" />
<input type="hidden" name="botao" value="NOVA_TRANSFERENCIA" />
<input type="hidden" name="fornecedor_origem" value="<?php echo"$fornecedor_origem"; ?>" />
<input type="hidden" name="fornecedor_destino" value="<?php echo"$fornecedor_destino"; ?>" />
<input type="hidden" name="cod_seleciona_produto" value="<?php echo"$cod_seleciona_produto"; ?>" />
<input type="hidden" name="numero_transferencia" value="<?php echo"$numero_transferencia"; ?>" />

	<div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Quantidade (<?php echo"$unidade_apelido"; ?>):
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="quantidade_form" id="quant" class="form_input" maxlength="12" onkeypress="<?php echo"$config[43]"; ?>" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$quantidade_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  TIPO PRODUTO ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
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
	<form action='$servidor/$diretorio_servidor/compras/transferencias/cadastro_1_selec_produto.php' method='post'>
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