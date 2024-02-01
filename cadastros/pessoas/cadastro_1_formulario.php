<?php

require_once("sankhya/Sankhya");
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");

$pagina = "cadastro_1_formulario";
$titulo = "Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$tipo_pessoa_form = $_POST["tipo_pessoa_form"];
$nome_form = $_POST["nome_form"];
$cpf_form = $_POST["cpf_form"];
$cnpj_form = $_POST["cnpj_form"];
$rg_form = $_POST["rg_form"];
$data_nascimento_form = $_POST["data_nascimento_form"];
$sexo_form = $_POST["sexo_form"];
$nome_fantasia_form = $_POST["nome_fantasia_form"];
$telefone_1_form = $_POST["telefone_1_form"];
$telefone_2_form = $_POST["telefone_2_form"];
$endereco_form = $_POST["endereco_form"];
$numero_residencia_form = $_POST["numero_residencia_form"];
$bairro_form = $_POST["bairro_form"];
$estado = $_POST["estado"];
$cidade = $_POST["cidade"];
$complemento_form = $_POST["complemento_form"];
$cep_form = $_POST["cep_form"];
$email_form = $_POST["email_form"];
$obs_form = $_POST["obs_form"];
$classificacao_1_form = $_POST["classificacao_1_form"];
$banco_form = $_POST["banco_form"];
$agencia_form = $_POST["agencia_form"];
$numero_conta_form = $_POST["numero_conta_form"];
$tipo_conta_form = $_POST["tipo_conta_form"];
$tipo_chave_pix_form = $_POST["tipo_chave_pix_form"];
$chave_pix_form = $_POST["chave_pix_form"];

$result = Sankhya::login();

// ========================================================================================================

if(array_key_exists('login', $_POST)) {
	echo "<script>console.log('testestestesetse')</script>";
	$result = Sankhya::login();
    echo "<script>console.log('sasasasasasasas')</script>";
}

// ====== MONTA MENSAGEM ===================================================================================
if ($permissao[5] != "S")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastro de pessoa</div>";}
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

<script>
function buscar_cidades(){
	var estado = $('#estado').val();
	if(estado){
	var url = 'ajax_buscar_cidades.php?estado='+estado;
	$.get(url, function(dataReturn) {
	$('#load_cidades').html(dataReturn);
	});
	}
}

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 2500); // 2,5 Segundos

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
<?php include ("../../includes/submenu_cadastro_pessoas.php"); ?>
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


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:370px; margin:auto; border:1px solid transparent">


<!-- =======  TIPO PESSOA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Tipo de Pessoa:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <form name="tipo_pessoa" action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php" method="post" />
        <input type="hidden" name="botao" value="TIPO_PESSOA" />

        <select name="tipo_pessoa_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" onchange="document.tipo_pessoa.submit()" style="width:154px" />
        <option></option>
		<?php
		if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
        {echo "<option selected='selected' value='PF'>Pessoa F&iacute;sica</option>";}
		else
        {echo "<option value='PF'>Pessoa F&iacute;sica</option>";}
		
        if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "<option selected='selected' value='PJ'>Pessoa Jur&iacute;dica</option>";}
		else
        {echo "<option value='PJ'>Pessoa Jur&iacute;dica</option>";}
        ?>
        </select>
        </form>
        </div>
	</div>
<!-- ================================================================================================================ -->


<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_2_enviar.php" method="post" />
<input type="hidden" name="botao" value="NOVO_CADASTRO" />
<input type="hidden" name="tipo_pessoa_form" value="<?php echo"$tipo_pessoa_form"; ?>" />


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
	<div style="width:510px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "Raz&atilde;o Social:";}
		else
        {echo "Nome:";}
		?>
        </div>
        
        <div style="width:500px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "PJ")
        {echo "
        <input type='text' name='nome_form' class='form_input' id='ok' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:486px; text-align:left; padding-left:5px' value='$nome_form' />";}
		else
        {echo "
        <input type='text' name='nome_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:486px; text-align:left; padding-left:5px' 
		disabled='disabled' title='Selecione o tipo de pessoa' value='$nome_form' />";}
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "CNPJ:";}
		else
        {echo "CPF:";}
		?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "<input type='text' name='cnpj_form' class='form_input' maxlength='18' id='cnpj' onkeypress='mascara(this,num_cnpj)' onBlur='mascara(this,num_cnpj)'
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$cnpj_form' />";}
		else
        {echo"<input type='text' name='cpf_form' class='form_input' maxlength='14' id='cpf' onkeypress='mascara(this,num_cpf)' onBlur='mascara(this,num_cpf)' 
		onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$cpf_form' />";}
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= RG / IE ================================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "IE:";}
		else
        {echo "RG:";}
		?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="rg_form" class="form_input" maxlength="20" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$rg_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= DATA NASCIMENTO ======================================================================================== -->
	<?php
    if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
    {echo "
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
		Data de Nascimento:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<input type='text' name='data_nascimento_form' class='form_input' id='data' maxlength='10' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$data_nascimento_form' />
        </div>
	</div>";}
    ?>
<!-- ================================================================================================================ -->


<!-- ======= SEXO =================================================================================================== -->
	<?php
    if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
    {echo "
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
		Sexo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<select name='sexo_form' class='form_select' onkeydown='if (getKey(event) == 13) return false;' style='width:154px' />";
		if ($sexo_form == "MASCULINO")
        {echo "
		<option></option>
		<option selected='selected' value='MASCULINO'>MASCULINO</option>
		<option value='FEMININO'>FEMININO</option>";}
        elseif ($sexo_form == "FEMININO")
        {echo "
		<option></option>
		<option value='MASCULINO'>MASCULINO</option>
		<option selected='selected' value='FEMININO'>FEMININO</option>";}
		else
        {echo "
		<option></option>
		<option value='MASCULINO'>MASCULINO</option>
		<option value='FEMININO'>FEMININO</option>";}

        echo "
		</select>
		</div>
	</div>";}
    ?>
<!-- ================================================================================================================ -->


<!-- ======= NOME FANTASIA ========================================================================================== -->
	<?php
    if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
    {echo "
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		Nome Fantasia:
        </div>
        
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='nome_fantasia_form' class='form_input' maxlength='70' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:315px; text-align:left; padding-left:5px' value='$nome_fantasia_form' />
        </div>
	</div>";}
    ?>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 1:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_1_form" class="form_input" id="telddd_1" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_1_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 2:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="telefone_2_form" class="form_input" id="telddd_2" maxlength="15" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$telefone_2_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="endereco_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$endereco_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO ================================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&uacute;mero:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="numero_residencia_form" class="form_input" maxlength="10" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$numero_residencia_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= BAIRRO ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Bairro/Distrito:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="bairro_form" class="form_input" maxlength="40" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$bairro_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ESTADO =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Estado:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="estado" id="estado" class="form_select" onchange="buscar_cidades()" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
            $busca_estado = mysqli_query ($conexao, "SELECT * FROM cad_estados ORDER BY est_sigla");
            $linhas_estado = mysqli_num_rows ($busca_estado);
        
        for ($i = 0; $i < $linhas_estado; $i++)
        {
            $aux_estado = mysqli_fetch_array($busca_estado);
            $arrEstados[$aux_estado['est_id']] = $aux_estado['est_sigla'];
        }
        
        foreach ($arrEstados as $value => $name)
            {
            if ($estado == $name)
            {echo "<option selected='selected' value='{$value}'>{$name}</option>";}
            else
            {echo "<option value='{$value}'>{$name}</option>";}
            }
        
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CIDADE =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Cidade:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div id="load_cidades">
		<select name="cidade" id="cidade" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
        <option></option>
        <?php

		if ($estado != "")
		{

            $busca_cidade = mysqli_query ($conexao, "SELECT * FROM cad_cidades ORDER BY cid_nome");
            $linhas_cidade = mysqli_num_rows ($busca_cidade);
        
			for ($i=1 ; $i<=$linhas_cidade ; $i++)
			{
				$aux_cidade = mysqli_fetch_row($busca_cidade);
				if ($aux_cidade[1] == $cidade)
				{echo "<option selected='selected' value='$aux_cidade[0]'>$aux_cidade[1]</option>";}
				else
				{echo "<option value='$aux_cidade[0]'>$aux_cidade[1]</option>";}
			}

		}

        ?>
		</select>
		</div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= COMPLEMENTO ============================================================================================ -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Complemento:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="complemento_form" class="form_input" maxlength="70" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$complemento_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CEP ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		CEP:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="cep_form" class="form_input" maxlength="10" id="cep" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$cep_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= E-MAIL ================================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="email_form" class="form_input" maxlength="70" onBlur="alteraMinusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$email_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= OBSERVAÇÃO ============================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Observa&ccedil;&atilde;o:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="obs_form" class="form_input" maxlength="150" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:315px; text-align:left; padding-left:5px" value="<?php echo"$obs_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CLASSIFICACAO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Classifica&ccedil;&atilde;o:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="classificacao_1_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE tipo='classificacao' AND estado_registro='ATIVO' ORDER BY codigo");
		$linhas_classificacao = mysqli_num_rows ($busca_classificacao);
	
		for ($i=1 ; $i<=$linhas_classificacao ; $i++)
		{
		$aux_classificacao = mysqli_fetch_row($busca_classificacao);	
	
			if ($aux_classificacao[0] == $classificacao_1_form)
			{echo "<option selected='selected' value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";}
			else
			{echo "<option value='$aux_classificacao[0]'>$aux_classificacao[1]</option>";}
		}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1020px; height:10px; float:left"></div>


<div style="width:1020px; height:16px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:15px; border:1px solid transparent; float:left">
    <b>Dados Banc&aacute;rios:</b>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BANCO ================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Banco:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="banco_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		$busca_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco ORDER BY preferencia, apelido");
		$linhas_banco = mysqli_num_rows ($busca_banco);
	
		for ($j=1 ; $j<=$linhas_banco ; $j++)
		{
		$aux_banco = mysqli_fetch_row($busca_banco);	
	
			if ($aux_banco[2] == $banco_form)
			{echo "<option selected='selected' value='$aux_banco[2]'>$aux_banco[3] ($aux_banco[2])</option>";}
			else
			{echo "<option value='$aux_banco[2]'>$aux_banco[3] ($aux_banco[2])</option>";}
		}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= AGÊNCIA ================================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Ag&ecirc;ncia:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="agencia_form" class="form_input" maxlength="8" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$agencia_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= Nº DA CONTA ============================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&ordm; da Conta:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="numero_conta_form" class="form_input" maxlength="13" id="conta_bancaria" onBlur="alteraMaiusculo(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$numero_conta_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO DE CONTA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Conta:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="tipo_conta_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($tipo_conta_form == "corrente")
		{echo "<option value='corrente' selected='selected'>Conta Corrente</option>";}
		else
		{echo "<option value='corrente'>Conta Corrente</option>";}
		
		if ($tipo_conta_form == "poupanca")
		{echo "<option value='poupanca' selected='selected'>Conta Poupan&ccedil;a</option>";}
		else
		{echo "<option value='poupanca'>Conta Poupan&ccedil;a</option>";}
		
		if ($tipo_conta_form == "salario")
		{echo "<option value='salario' selected='selected'>Conta Sal&aacute;rio</option>";}
		else
		{echo "<option value='salario'>Conta Sal&aacute;rio</option>";}
		
		if ($tipo_conta_form == "aplicacao")
		{echo "<option value='aplicacao' selected='selected'>Conta Aplica&ccedil;&atilde;o</option>";}
		else
		{echo "<option value='aplicacao'>Conta Aplica&ccedil;&atilde;o</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->



<!-- ======= TIPO DE CHAVE PIX ====================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Chave Pix:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<select name="tipo_chave_pix_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
        <option></option>
        <?php
		if ($tipo_chave_pix_form == "cpf_cnpj")
		{echo "<option value='cpf_cnpj' selected='selected'>CPF/CNPJ</option>";}
		else
		{echo "<option value='cpf_cnpj'>CPF/CNPJ</option>";}
		
		if ($tipo_chave_pix_form == "celular")
		{echo "<option value='celular' selected='selected'>Celular</option>";}
		else
		{echo "<option value='celular'>Celular</option>";}
		
		if ($tipo_chave_pix_form == "email")
		{echo "<option value='email' selected='selected'>E-mail</option>";}
		else
		{echo "<option value='email'>E-mail</option>";}
		
		if ($tipo_chave_pix_form == "aleatoria")
		{echo "<option value='aleatoria' selected='selected'>Chave Aleat&oacute;ria</option>";}
		else
		{echo "<option value='aleatoria'>Chave Aleat&oacute;ria</option>";}
        ?>
		</select>
		</div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CHAVE PIX ============================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Pix:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<input type="text" name="chave_pix_form" class="form_input" maxlength="50" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$chave_pix_form"; ?>" />
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
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php' method='post'>
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
	<a href='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</a>
	</div>";}
	

	?>
    <form method="post" action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_1_formulario.php">
        <input type="submit" name="login"
                class="button" value="login" />
          
        <input type="submit" name="button2"
                class="button" value="Button2" />
    </form>	

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