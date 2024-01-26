<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "config_geral";
$titulo = "Configura&ccedil;&atilde;o Geral";
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$servidor_w = $_POST["servidor_w"];
$diretorio_servidor_w = $_POST["diretorio_servidor_w"];
$razao_social_w = $_POST["razao_social_w"];
$email_dest_exclusao = $_POST["email_dest_exclusao"];
$enviar_email_exclusao = $_POST["enviar_email_exclusao"];
$limite_dias_exclusao_reg = $_POST["limite_dias_exclusao_reg"];
$versao_impressao_compra = $_POST["versao_impressao_compra"];
$limite_dias_exclusao_romaneio = $_POST["limite_dias_exclusao_romaneio"];
$limite_dias_edicao_romaneio = $_POST["limite_dias_edicao_romaneio"];
$integracao_rovereti = $_POST["integracao_rovereti"];
$romaneio_automatico = $_POST["romaneio_automatico"];
$romaneio_filial_origem = $_POST["romaneio_filial_origem"];
$romaneio_motorista = $_POST["romaneio_motorista"];
$romaneio_cpf_motorista = $_POST["romaneio_cpf_motorista"];
$romaneio_placa_veiculo = $_POST["romaneio_placa_veiculo"];
$limite_dias_exc_venda = $_POST["limite_dias_exc_venda"];
$limite_dias_edi_venda = $_POST["limite_dias_edi_venda"];
$versao_impressao_venda = $_POST["versao_impressao_venda"];
$nome_fantasia_config = $_POST["nome_fantasia_config"];
$nome_fantasia_m_config = $_POST["nome_fantasia_m_config"];
$cpf_cnpj_config = $_POST["cpf_cnpj_config"];
$telefone_1_config = $_POST["telefone_1_config"];
$telefone_2_config = $_POST["telefone_2_config"];
$email_config = $_POST["email_config"];
$endereco_config = $_POST["endereco_config"];
$cidade_config = $_POST["cidade_config"];
$uf_config = $_POST["uf_config"];

$icones_select_produto = $_POST["icones_select_produto"];
$mostrar_preco_produto = $_POST["mostrar_preco_produto"];
$mostrar_outros_produtos = $_POST["mostrar_outros_produtos"];
$relat_consol_estoque = $_POST["relat_consol_estoque"];
$versao_impr_compra_f = $_POST["versao_impr_compra_f"];
$romaneio_bal_rodoviaria = $_POST["romaneio_bal_rodoviaria"];
$fechamento_romaneio_automatico = $_POST["fechamento_romaneio_automatico"];
$nome_fantasia_filial = $_POST["nome_fantasia_filial"];
$nome_fantasia_m_filial = $_POST["nome_fantasia_m_filial"];
$cpf_cnpj_filial = $_POST["cpf_cnpj_filial"];
$telefone_1_filial = $_POST["telefone_1_filial"];
$telefone_2_filial = $_POST["telefone_2_filial"];
$email_filial = $_POST["email_filial"];
$endereco_filial = $_POST["endereco_filial"];
$cidade_filial = $_POST["cidade_filial"];
$uf_filial = $_POST["uf_filial"];
$produtos_relatorio = $_POST["produtos_relatorio"];
// ========================================================================================================


// ====== SALVAR CONFIGURAÇÕES ============================================================================
if ($botao == "SALVAR_CONFIG" and $permissao[131] == "S")
{
// EDIÇÃO TABELA CONFIGURACOES
$editar = mysqli_query ($conexao, "UPDATE suif_grancafe.configuracoes SET razao_social='$razao_social_w', email_dest_exclusao='$email_dest_exclusao', enviar_email_exclusao='$enviar_email_exclusao', limite_dias_exclusao_reg='$limite_dias_exclusao_reg', versao_impressao_compra='$versao_impressao_compra', limite_dias_exclusao_romaneio='$limite_dias_exclusao_romaneio', limite_dias_exclusao_romaneio='$limite_dias_exclusao_romaneio', limite_dias_edicao_romaneio='$limite_dias_edicao_romaneio', integracao_rovereti='$integracao_rovereti', romaneio_automatico='$romaneio_automatico', romaneio_filial_origem='$romaneio_filial_origem', romaneio_motorista='$romaneio_motorista', romaneio_cpf_motorista='$romaneio_cpf_motorista', romaneio_placa_veiculo='$romaneio_placa_veiculo', limite_dias_exc_venda='$limite_dias_exc_venda', limite_dias_edi_venda='$limite_dias_edi_venda', versao_impressao_venda='$versao_impressao_venda', nome_fantasia='$nome_fantasia_config', nome_fantasia_m='$nome_fantasia_m_config', cpf_cnpj='$cpf_cnpj_config', telefone_1='$telefone_1_config', telefone_2='$telefone_2_config', email='$email_config', endereco='$endereco_config', cidade='$cidade_config', uf='$uf_config' WHERE codigo='1'");

// EDIÇÃO TABELA FILIAIS
$editar = mysqli_query ($conexao, "UPDATE suif_grancafe.filiais SET icones_select_produto='$icones_select_produto', mostrar_preco_produto='$mostrar_preco_produto', mostrar_outros_produtos='$mostrar_outros_produtos', relat_consol_estoque='$relat_consol_estoque', versao_impressao_compra='$versao_impr_compra_f', romaneio_bal_rodoviaria='$romaneio_bal_rodoviaria', fechamento_romaneio_automatico='$fechamento_romaneio_automatico', nome_fantasia='$nome_fantasia_filial', nome_fantasia_m='$nome_fantasia_m_filial', cpf_cnpj='$cpf_cnpj_filial', telefone_1='$telefone_1_filial', telefone_2='$telefone_2_filial', email='$email_filial', endereco='$endereco_filial', cidade='$cidade_filial', uf='$uf_filial', produtos_relatorio='$produtos_relatorio' WHERE descricao='$filial'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Configura&ccedil;&otilde;es editadas com sucesso!</div>";
}

elseif ($botao == "SALVAR_CONFIG" and $permissao[131] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar configura&ccedil;&otilde;es</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CONFIGURAÇÕES =============================================================================
$busca_config = mysqli_query ($conexao, "SELECT * FROM configuracoes WHERE codigo='1'");
$aux_config = mysqli_fetch_row($busca_config);

$servidor_w = $aux_config[1];
$diretorio_servidor_w = $aux_config[2];
$razao_social_w = $aux_config[3];
$email_dest_exclusao = $aux_config[16];
$enviar_email_exclusao = $aux_config[18];
$limite_dias_exclusao_reg = $aux_config[20];
$versao_impressao_compra = $aux_config[21];
$limite_dias_exclusao_romaneio = $aux_config[23];
$limite_dias_edicao_romaneio = $aux_config[24];
$integracao_rovereti = $aux_config[28];
$romaneio_automatico = $aux_config[32];
$romaneio_filial_origem = $aux_config[33];
$romaneio_motorista = $aux_config[34];
$romaneio_cpf_motorista = $aux_config[35];
$romaneio_placa_veiculo = $aux_config[36];
$limite_dias_exc_venda = $aux_config[40];
$limite_dias_edi_venda = $aux_config[41];
$versao_impressao_venda = $aux_config[42];
$nome_fantasia_config = $aux_config[45];
$nome_fantasia_m_config = $aux_config[46];
$cpf_cnpj_config = $aux_config[47];
$telefone_1_config = $aux_config[48];
$telefone_2_config = $aux_config[49];
$email_config = $aux_config[50];
$endereco_config = $aux_config[51];
$cidade_config = $aux_config[52];
$uf_config = $aux_config[53];
// ======================================================================================================


// ====== BUSCA CONFIGURAÇÕES FILIAIS ===================================================================
$busca_cf = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial'");
$aux_cf = mysqli_fetch_row($busca_cf);

$icones_select_produto = $aux_cf[4];
$mostrar_preco_produto = $aux_cf[5];
$mostrar_outros_produtos = $aux_cf[6];
$relat_consol_estoque = $aux_cf[7];
$versao_impr_compra_f = $aux_cf[8];
$romaneio_bal_rodoviaria = $aux_cf[9];
$fechamento_romaneio_automatico = $aux_cf[11];
$nome_fantasia_filial = $aux_cf[12];
$nome_fantasia_m_filial = $aux_cf[13];
$cpf_cnpj_filial = $aux_cf[14];
$telefone_1_filial = $aux_cf[15];
$telefone_2_filial = $aux_cf[16];
$email_filial = $aux_cf[17];
$endereco_filial = $aux_cf[18];
$cidade_filial = $aux_cf[19];
$uf_filial = $aux_cf[20];
$produtos_relatorio = $aux_cf[34];
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

<script>
// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

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
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_config.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo"$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO 1 =========== -->
<div style="width:1250px; height:130px; margin:auto; border:1px solid transparent">

<!-- ======= LOGOMARCA ============================================================================================== -->
	<div style="width:1240px; height:17px; border:1px solid transparent; margin-top:0px; float:left">
        <div class="form_rotulo" style="width:95px; height:15px; border:1px solid transparent; float:left">
		Logomarca:
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  LOGOMARCA ============================================================================================= -->
	<div style="width:300px; height:105px; border:1px solid transparent; margin-top:0px; float:left; text-align:center">
		<div style="margin-top:5px; margin-left:0px; width:290px; height:95px; border:1px solid #999; color:#999; text-align:center">
			<?php echo"<img src='$servidor/$diretorio_servidor/imagens/logomarca.png' style='height:85px' />" ?>
		</div>
	</div>
<!-- ================================================================================================================ -->

</div>
<!-- ===========  FIM DO FORMULÁRIO 1 =========== -->




<!-- ===========  INÍCIO DO FORMULÁRIO 2 =========== -->
<div style="width:1250px; height:320px; margin:auto; border:1px solid transparent">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/config_geral.php" method="post" />
<input type="hidden" name="botao" value="SALVAR_CONFIG" />


<!-- =======  ENDEREÇO SERVIDOR ===================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o Servidor:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:320px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
		<div style="margin-top:6px; margin-left:5px; width:310px; height:16px; color:#003466; text-align:left; overflow:hidden">
		<?php echo"$servidor_w"; ?></div>
        </div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  DIRETÓRIO SERVIDOR ==================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Diret&oacute;rio Servidor:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
		<?php echo"$diretorio_servidor_w" ?></div>
        </div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  RAZÃO SOCIAL ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Raz&atilde;o Social:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="razao_social_w" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$razao_social_w"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME FANTASIA ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Nome fantasia (Mai&uacute;sculo):
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="nome_fantasia_m_config" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$nome_fantasia_m_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME FANTASIA ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Nome fantasia (Min&uacute;sculo):
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="nome_fantasia_config" class="form_input" maxlength="70" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$nome_fantasia_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CNPJ =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		CNPJ:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="cpf_cnpj_config" class="form_input" maxlength="18" id="cnpj" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cpf_cnpj_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  EMAIL CONTATO ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail Principal:
        </div>

		<div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="email_config" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$email_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  EMAIL EXCLUSÃO ======================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail que recebe exclus&otilde;es:
        </div>
 
		<div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="email_dest_exclusao" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$email_dest_exclusao"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 1:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_1_config" class="form_input" id="telddd_1" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_1_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 2:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_2_config" class="form_input" id="telddd_2" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_2_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="endereco_config" class="form_input" maxlength="120" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$endereco_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CIDADE =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Cidade:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="cidade_config" class="form_input" maxlength="30" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cidade_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ESTADO =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Estado:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="uf_config" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$uf_config"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ENVIAR EMAIL EXCLUSAO ================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Enviar e-mail exclus&atilde;o:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="enviar_email_exclusao" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($enviar_email_exclusao == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($enviar_email_exclusao == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= LIMITE DIAS EXCLUSÃO COMPRAS =========================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Limite dias excl. compra:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="limite_dias_exclusao_reg" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$limite_dias_exclusao_reg"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= VERSÃO IMPRESSÃO COMPRA ================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Vers&atilde;o impres. compra:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="versao_impressao_compra" class="form_input" maxlength="10" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$versao_impressao_compra"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= INTEGRAÇÃO ROVERETI ==================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Integra&ccedil;&atilde;o Rovereti:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="integracao_rovereti" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($integracao_rovereti == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($integracao_rovereti == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= LIMITE DIAS EXCLUSÃO ROMANEIO ========================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Limite dias excl. romaneio:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="limite_dias_exclusao_romaneio" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$limite_dias_exclusao_romaneio"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= LIMITE DIAS EDIÇÃO ROMANEIO ============================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Limite dias edi. romaneio:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="limite_dias_edicao_romaneio" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$limite_dias_edicao_romaneio"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO AUTOMÁTIVO ==================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Romaneio Autom&aacute;tico:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_automatico" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_automatico == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_automatico == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO FILIAL ORIGEM ================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Romaneio Filial Origem:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_filial_origem" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_filial_origem == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_filial_origem == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO MOTORISTA ===================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Romaneio Motorista:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_motorista" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_motorista == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_motorista == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO CPF MOTORISTA ================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Romaneio CPF Mot.:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_cpf_motorista" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_cpf_motorista == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_cpf_motorista == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO PLACA ========================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Romaneio Placa:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_placa_veiculo" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_placa_veiculo == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_placa_veiculo == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= LIMITE DIAS EXCLUSÃO VENDA ========================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Limite dias excl. venda:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="limite_dias_exc_venda" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$limite_dias_exc_venda"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= LIMITE DIAS EDIÇÃO VENDA ============================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Limite dias edi. venda:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="limite_dias_edi_venda" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$limite_dias_edi_venda"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= VERSÃO IMPRESSÃO VENDA ================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Vers&atilde;o impres. venda:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="versao_impressao_venda" class="form_input" maxlength="10" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$versao_impressao_venda"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->






</div>
<!-- ===========  FIM DO FORMULÁRIO 2 =========== -->



<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Configura&ccedil;&atilde;o de Filial (<?php echo"$filial"; ?>)
    </div>

	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO 3 =========== -->
<div style="width:1250px; height:240px; margin:auto; border:1px solid transparent">


<!-- =======  NOME FANTASIA ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Nome fantasia (Mai&uacute;sculo):
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="nome_fantasia_m_filial" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$nome_fantasia_m_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME FANTASIA ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Nome fantasia (Min&uacute;sculo):
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="nome_fantasia_filial" class="form_input" maxlength="70" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$nome_fantasia_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CNPJ =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		CNPJ:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="cpf_cnpj_filial" class="form_input" maxlength="18" id="cnpj" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cpf_cnpj_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  EMAIL CONTATO ========================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail Filial:
        </div>

		<div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="email_filial" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$email_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 1:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_1_filial" class="form_input" id="telddd_1" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_1_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 2:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_2_filial" class="form_input" id="telddd_2" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_2_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="endereco_filial" class="form_input" maxlength="120" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$endereco_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CIDADE =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Cidade:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="cidade_filial" class="form_input" maxlength="30" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cidade_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ESTADO =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Estado:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="uf_filial" class="form_input" maxlength="4" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$uf_filial"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= MOSTRA PREÇO PRODUTO ================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Mostra pre&ccedil;o produto:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="mostrar_preco_produto" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($mostrar_preco_produto == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($mostrar_preco_produto == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= MOSTRA OUTROS PRODUTO ================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Mostra outros produtos:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="mostrar_outros_produtos" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($mostrar_outros_produtos == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($mostrar_outros_produtos == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= VERSÃO IMPRESSÃO COMPRA ================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Vers&atilde;o impres. compra:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="versao_impr_compra_f" class="form_input" maxlength="10" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$versao_impr_compra_f"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= RELATÓRIO CONSOLIDADE DE ESTOQUE ======================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Relat. Cons. Estoque:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="relat_consol_estoque" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($relat_consol_estoque == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($relat_consol_estoque == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ROMANEIO BALANÇA RODOVIÁRIA ============================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Rom. Balan&ccedil;a Rodo.:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="romaneio_bal_rodoviaria" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($romaneio_bal_rodoviaria == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($romaneio_bal_rodoviaria == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= FECHAMENTO ROMANEIO AUTOMÁTICO ========================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Fech. Romaneio Auto.:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="fechamento_romaneio_automatico" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($fechamento_romaneio_automatico == "S")
		{echo "<option value='S' selected='selected'>SIM</option>";}
		else
		{echo "<option value='S'>SIM</option>";}
		
		if ($fechamento_romaneio_automatico == "N")
		{echo "<option value='N' selected='selected'>N&Atilde;O</option>";}
		else
		{echo "<option value='N'>N&Atilde;O</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ICONE SELECIONA PRODUTO ================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		&Iacute;cone Selec. Produtos:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="icones_select_produto" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$icones_select_produto"; ?>" />
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= PRODUTOS RELATÓRIO ===================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Produtos Relat&oacute;rio:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="produtos_relatorio" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$produtos_relatorio"; ?>" />
		</div>
	</div>
<!-- ================================================================================================================ -->








</div>
<!-- ===========  FIM DO FORMULÁRIO 3 =========== -->




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














<div class="espacamento_10"></div>
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