<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_2_formulario";
$titulo = "Recibo";
$modulo = "compras";
$menu = "contratos";
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================

// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace("R$ ", "", $valor_x); //tira o símbolo
	$valor_2 = str_replace(".", "", $valor_1); //tira o ponto
	$valor_3 = str_replace(",", ".", $valor_2); //troca vírgula por ponto
	return $valor_3;
}
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;

$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];

$valor_form = $_POST["valor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
// ========================================================================================================


// ====== CONTADOR NÚMERO RECIBO ==========================================================================
if ($botao == "FORMULARIO")
{
$busca_num_recibo = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnr = mysqli_fetch_row($busca_num_recibo);
$numero_recibo = $aux_bnr[5];

$contador_num_recibo = $numero_recibo + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_contrato_adto='$contador_num_recibo'");
}

else
{
$numero_recibo = $_POST["numero_recibo"];
}
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

if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{$cpf_cnpj_print = "$cpf_pessoa";}
else
{$cpf_cnpj_print = "$cnpj_pessoa";}


if ($botao == "FORMULARIO")
{
$nome_emissor_form = $nome_pessoa;
$telefone_emissor_form = $telefone_pessoa;
$cpf_cnpj_emissor_form = $cpf_cnpj_print;
$cidade_emissor_form = $cidade_pessoa;
$nome_pagador_form = $config[45];
$cpf_cnpj_pagador_form = $config[47];
$cidade_pagador_form = $filial_config[2];
$referente_form = "referente &agrave; venda de";
$data_recibo_form = $data_hoje;
}

else
{
$nome_emissor_form = $_POST["nome_emissor_form"];
$telefone_emissor_form = $_POST["telefone_emissor_form"];
$cpf_cnpj_emissor_form = $_POST["cpf_cnpj_emissor_form"];
$cidade_emissor_form = $_POST["cidade_emissor_form"];
$nome_pagador_form = $_POST["nome_pagador_form"];
$cpf_cnpj_pagador_form = $_POST["cpf_cnpj_pagador_form"];
$cidade_pagador_form = $_POST["cidade_pagador_form"];
$referente_form = $_POST["referente_form"];
$data_recibo_form = $_POST["data_recibo_form"];
}

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
<body onload="javascript:foco('valor_money');">

<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
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
    <?php echo"N&ordm; $numero_recibo"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php //echo "$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:300px; margin:auto; border:1px solid transparent">


<!-- =======  NOME DO EMISSOR ======================================================================================= -->
<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
<form name="calcula_total" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/recibo/cadastro_3_enviar.php" method="post" />
<input type="hidden" name="botao" value="NOVO_RECIBO" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="numero_recibo" value="<?php echo"$numero_recibo"; ?>" />

    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Nome do Emissor:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_emissor_form" class="form_input" maxlength="200" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:486px; text-align:left; padding-left:5px" value="<?php echo"$nome_emissor_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CPF / CNPJ EMISSOR ==================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    CPF / CNPJ do Emissor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="cpf_cnpj_emissor_form" class="form_input" maxlength="20" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cpf_cnpj_emissor_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  TELEFONE EMISSOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone do Emissor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="telefone_emissor_form" class="form_input" id="telddd_1" maxlength="20" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_emissor_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  CIDADE EMISSOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade do Emissor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="cidade_emissor_form" class="form_input" maxlength="100" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cidade_emissor_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->



<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  NOME DO PAGADOR ======================================================================================= -->
<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Nome do Pagador:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_pagador_form" class="form_input" maxlength="150" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:486px; text-align:left; padding-left:5px" value="<?php echo"$nome_pagador_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CPF / CNPJ PAGADOR ==================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    CPF / CNPJ do Pagador:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="cpf_cnpj_pagador_form" class="form_input" maxlength="20" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cpf_cnpj_pagador_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  CIDADE PAGADOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade do Pagador:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="cidade_pagador_form" class="form_input" maxlength="100" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cidade_pagador_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ======= VALOR ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Valor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="valor_form" class="form_input" id="valor_money" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$valor_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  DATA EMISSÃO =========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Data de Emiss&atilde;o:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_recibo_form" class="form_input" id="calendario" maxlength="10" onkeypress="mascara(this,data)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$data_recibo_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================= -->


<!-- =======  REFERENTE A ============================================================================================ -->
<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Referente &agrave;:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="referente_form" class="form_input" maxlength="150" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:486px; text-align:left; padding-left:5px" value="<?php echo"$referente_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PRODUTO =========================================================================================== --><div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_form" class="form_select" style="width:160px" />
    <option>(Selecione o produto)</option>
    <?php
    $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
    $linhas_produto_list = mysqli_num_rows ($busca_produto_list);

    for ($i=1 ; $i<=$linhas_produto_list ; $i++)
    {
    $aux_produto_list = mysqli_fetch_row($busca_produto_list);	

        if ($aux_produto_list[0] == $cod_produto_form)
        {echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[22]</option>";}
        else
        {echo "<option value='$aux_produto_list[0]'>$aux_produto_list[22]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================= -->





</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">
	
	<?php
	echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/index.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</a>
	</div>";

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