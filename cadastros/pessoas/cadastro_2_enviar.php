<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_2_enviar";
$titulo = "Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ====== CONVERTE DATA, VALOR e PESO =============================================================================
include ("../../includes/converte.php");
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d');
$data_hoje_br = date('d/m/Y');
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
if ($banco_form == "")
{$banco_aux = "000";}
else
{$banco_aux = $_POST["banco_form"];}
$tipo_chave_pix_form = $_POST["tipo_chave_pix_form"];
$chave_pix_form = $_POST["chave_pix_form"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y-m-d', time());
// ================================================================================================================


// ======= ALTERA DATA ==========================================================================================
$data_nascimento_aux = ConverteData($data_nascimento_form);

if ($data_nascimento_aux == "" or $data_nascimento_aux <= 1900-01-01)
{$data_nascimento_aux = "1900-01-01";}
// ================================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{$tipo_pessoa_print = "PESSOA F&Iacute;SICA";
$cpf_cnpj = $cpf_form;}
elseif ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
{$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";
$cpf_cnpj = $cnpj_form;}
else
{$tipo_pessoa_print = "";}
// ================================================================================================================


// ======= TIPO DE CONTA ==========================================================================================
if ($tipo_conta_form == "corrente")
{$tipo_conta_print = "Conta Corrente";}
elseif ($tipo_conta_form == "poupanca")
{$tipo_conta_print = "Conta Poupan&ccedil;a";}
elseif ($tipo_conta_form == "salario")
{$tipo_conta_print = "Conta Sal&aacute;rio";}
elseif ($tipo_conta_form == "aplicacao")
{$tipo_conta_print = "Conta Aplica&ccedil;&atilde;o";}
else
{$tipo_conta_print = "";}
// ================================================================================================================


// ======= TIPO DE CHAVE PIX ======================================================================================
if ($tipo_chave_pix_form == "cpf_cnpj")
{$tipo_chave_pix_print = "CPF/CNPJ";}
elseif ($tipo_chave_pix_form == "celular")
{$tipo_chave_pix_print = "Celular";}
elseif ($tipo_chave_pix_form == "email")
{$tipo_chave_pix_print = "E-mail";}
elseif ($tipo_chave_pix_form == "aleatoria")
{$tipo_chave_pix_print = "Chave Aleat&oacute;ria";}
else
{$tipo_chave_pix_print = "";}
// ================================================================================================================


// ======= BUSCA BANCO ============================================================================================
$busca_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_form'");
$aux_bco = mysqli_fetch_row($busca_banco);
$banco_print = $aux_bco[3];
// ================================================================================================================


// ======= BUSCA ESTADO E CIDADE ==================================================================================
$busca_estado = mysqli_query ($conexao, "SELECT * FROM cad_estados WHERE est_id='$estado'");
$aux_be = mysqli_fetch_row($busca_estado);
$estado_aux = $aux_be[1];

$busca_cidade = mysqli_query ($conexao, "SELECT * FROM cad_cidades WHERE cid_id='$cidade'");
$aux_bc = mysqli_fetch_row($busca_cidade);
$cidade_aux = $aux_bc[1];
// ================================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_form'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ======= BUSCA CPF E CNPJ =======================================================================================
$busca_cpf = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND cpf='$cpf_form' AND cpf!=''");
$achou_cpf = mysqli_num_rows ($busca_cpf);

$busca_cnpj = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND cnpj='$cnpj_form' AND cnpj!=''");
$achou_cnpj = mysqli_num_rows ($busca_cnpj);
// ================================================================================================================


// ======= VALIDADOR DE CPF =======================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{
// limpar caracteres como ponto e traços (máscaras)
$cpf_validar = str_replace("-", "", $cpf_form);
$cpf_validar = str_replace(".", "", $cpf_validar);
$cpf_aux = $cpf_validar;

// verificar se a quantidade de caracteres está correta
if ( strlen( $cpf_validar ) != 11 )
{$valida_cpf = "erro";}

// Verifica se nenhuma das sequências foi digitada
elseif ($cpf_validar == '00000000000' or $cpf_validar == '11111111111' 
or $cpf_validar == '22222222222' or $cpf_validar == '33333333333' 
or $cpf_validar == '44444444444' or $cpf_validar == '55555555555' 
or $cpf_validar == '66666666666' or $cpf_validar == '77777777777' 
or $cpf_validar == '88888888888' or $cpf_validar == '99999999999')
{$valida_cpf = "erro";}

else
{
	// pegando apenas os digito a serem verificados
	$cod = substr($cpf_validar, 0, 9);
	
	// cálculando o primeiro dígito
	$soma = 0;
	$numero_calculo = 10;
	for ($i=0; $i < 9; $i++)
	{$soma += ( $cod[$i]*$numero_calculo-- );}

	$resto = $soma%11;
	if($resto < 2)
	{$cod .= "0";}
	else
	{$cod .= (11-$resto);}
	
	// calculando o segundo dígito
	$soma = 0;
	$numero_calculo = 11;
	for ($i=0; $i < 10; $i++)
	{$soma += ( $cod[$i]*$numero_calculo-- );}
	$resto = $soma%11;
	
	if($resto < 2)
	{$cod .= "0";}
	else
	{$cod .= (11-$resto);}
	
	// Se forem os mesmos é porque está correto
	if ( $cod === $cpf_validar )
	{$valida_cpf = "ok";}
	else
	{$valida_cpf = "erro";}
}
}
// ================================================================================================================


// ======= VALIDADOR DE CNPJ ======================================================================================
if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
{
// limpar caracteres como ponto e traços (máscaras)
$cnpj_validar = str_replace("-", "", $cnpj_form);
$cnpj_validar = str_replace(".", "", $cnpj_validar);
$cnpj_validar = str_replace("/", "", $cnpj_validar);
$cnpj_aux = $cnpj_validar;

// verificar se a quantidade de caracteres está correta
if ( strlen( $cnpj_validar ) != 14 )
{$valida_cnpj = "erro";}

// Verifica se nenhuma das sequências foi digitada
elseif ($cnpj_validar == '00000000000000' or $cnpj_validar == '11111111111111' or 
$cnpj_validar == '22222222222222' or $cnpj_validar == '33333333333333' or 
$cnpj_validar == '44444444444444' or $cnpj_validar == '55555555555555' or 
$cnpj_validar == '66666666666666' or $cnpj_validar == '77777777777777' or 
$cnpj_validar == '88888888888888' or $cnpj_validar == '99999999999999')
{$valida_cnpj = "erro";}

else
{

// Calcula os números para verificar se o CNPJ é verdadeiro
function verificaCNPJ($cnpj_x) { 
if (strlen($cnpj_x) <> 18) return 0; 
$soma1 = ($cnpj_x[0] * 5) + 

($cnpj_x[1] * 4) + 
($cnpj_x[3] * 3) + 
($cnpj_x[4] * 2) + 
($cnpj_x[5] * 9) + 
($cnpj_x[7] * 8) + 
($cnpj_x[8] * 7) + 
($cnpj_x[9] * 6) + 
($cnpj_x[11] * 5) + 
($cnpj_x[12] * 4) + 
($cnpj_x[13] * 3) + 
($cnpj_x[14] * 2); 
$resto = $soma1 % 11; 
$digito1 = $resto < 2 ? 0 : 11 - $resto; 
$soma2 = ($cnpj_x[0] * 6) + 

($cnpj_x[1] * 5) + 
($cnpj_x[3] * 4) + 
($cnpj_x[4] * 3) + 
($cnpj_x[5] * 2) + 
($cnpj_x[7] * 9) + 
($cnpj_x[8] * 8) + 
($cnpj_x[9] * 7) + 
($cnpj_x[11] * 6) + 
($cnpj_x[12] * 5) + 
($cnpj_x[13] * 4) + 
($cnpj_x[14] * 3) + 
($cnpj_x[16] * 2); 
$resto = $soma2 % 11; 
$digito2 = $resto < 2 ? 0 : 11 - $resto; 
return (($cnpj_x[16] == $digito1) && ($cnpj_x[17] == $digito2)); 
} 

if (!verificaCNPJ($cnpj_form))
	{$valida_cnpj = "erro";} 
else
	{$valida_cnpj = "ok";} 
}
}
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ====================================================================================
if ($permissao[69] == "S")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ================================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =================================================================================
if ($permissao[72] == "S")
{$permite_imprimir = "SIM";}
else
{$permite_imprimir = "NAO";}
// ================================================================================================================

// ====== BLOQUEIO PARA NOVO CADASTRO =============================================================================
if ($permissao[5] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ================================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "NOVO_CADASTRO")
{
	if ($tipo_pessoa_form == "")
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione o tipo de pessoa.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PF" and $nome_form == "")
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Digite o nome da pessoa.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PJ" and $nome_form == "")
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Digite a raz&atilde;o social da empresa.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PF" and $cpf_form == "")
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Informe o CPF da pessoa.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PF" and $valida_cpf == "erro")
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>CPF inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PF" and $achou_cpf >= 1)
	{$erro = 6;
	$msg = "<div style='color:#FF0000'>CPF j&aacute; cadastrado.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PJ" and $cnpj_form == "")
	{$erro = 7;
	$msg = "<div style='color:#FF0000'>Informe o CNPJ da empresa.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PJ" and $valida_cnpj == "erro")
	{$erro = 8;
	$msg = "<div style='color:#FF0000'>CNPJ inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($tipo_pessoa_form == "PJ" and $achou_cnpj >= 1)
	{$erro = 9;
	$msg = "<div style='color:#FF0000'>CNPJ j&aacute; cadastrado.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($estado == "")
	{$erro = 10;
	$msg = "<div style='color:#FF0000'>Selecione o estado.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($cidade == "")
	{$erro = 11;
	$msg = "<div style='color:#FF0000'>Selecione a cidade.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	elseif ($classificacao_1_form == "")
	{$erro = 12;
	$msg = "<div style='color:#FF0000'>Classifica&ccedil;&atilde;o &eacute; obrigat&oacute;rio para o cadastro.</div>";
	$msg_titulo = "<div style='color:#009900'>$titulo</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Cadastro Realizado com Sucesso!</div>";


	// ====== CONTADOR CÓDIGO PESSOA ==========================================================================
	$busca_codigo_pessoa = mysqli_query ($conexao, "SELECT * FROM configuracoes");
	$aux_bcp = mysqli_fetch_row($busca_codigo_pessoa);
	$codigo_pessoa = $aux_bcp[4];
	
	$contador_codigo_pessoa = $codigo_pessoa + 1;
	$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_codigo_pessoa='$contador_codigo_pessoa'");

	// ====== TABELA CADASTRO_PESSOA ==========================================================================
	$inserir = mysqli_query ($conexao, "INSERT INTO cadastro_pessoa (codigo, nome, tipo, cpf, cnpj, rg, sexo, data_nascimento, endereco, bairro, cidade, cep, estado, telefone_1, telefone_2, email, classificacao_1, observacao, nome_fantasia, numero_residencia, complemento, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, codigo_pessoa) VALUES (NULL, '$nome_form', '$tipo_pessoa_form', '$cpf_form', '$cnpj_form', '$rg_form', '$sexo_form', '$data_nascimento_aux', '$endereco_form', '$bairro_form', '$cidade_aux', '$cep_form', '$estado_aux', '$telefone_1_form', '$telefone_2_form', '$email_form', '$classificacao_1_form', '$obs_form', '$nome_fantasia_form', '$numero_residencia_form', '$complemento_form', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$codigo_pessoa')");

	// ====== TABELA CADASTRO_FAVORECIDO ======================================================================
	$inserir_favorecido = mysqli_query ($conexao, "INSERT INTO cadastro_favorecido (codigo, codigo_pessoa, banco, agencia, conta, tipo_conta, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, nome, tipo_chave_pix, chave_pix, cpf_cnpj, nome_banco) VALUES (NULL, '$codigo_pessoa', '$banco_aux', '$agencia_form', '$numero_conta_form', '$tipo_conta_form', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$nome_form', '$tipo_chave_pix_form', '$chave_pix_form', '$cpf_cnpj', '$banco_print')");

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
<div class="ct_fixo" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$msg_titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
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
<div style="width:1030px; height:370px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  TIPO PESSOA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Tipo de Pessoa:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($erro == 1)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$tipo_pessoa_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
	<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
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
		if ($erro == 2 or $erro == 3)
		{echo"<div style='width:495px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:485px; height:16px; text-align:left; overflow:hidden'>$nome_form</div></div>";}
		else
		{echo"<div style='width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:485px; height:16px; text-align:left; overflow:hidden'>$nome_form</div></div>";}
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
		{
			if ($erro == 7 or $erro == 8 or $erro == 9)
			{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cnpj_form</div></div>";}
			else
			{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cnpj_form</div></div>";}
		}
		else
		{
			if ($erro == 4 or $erro == 5 or $erro == 6)
			{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cpf_form</div></div>";}
			else
			{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
			<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cpf_form</div></div>";}
		}
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
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$rg_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= DATA NASCIMENTO ======================================================================================== -->
<?php
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{echo"

	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Data de Nascimento:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$data_nascimento_form</div></div>
		</div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= SEXO =================================================================================================== -->
<?php
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{echo"

	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Sexo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$sexo_form</div></div>
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
        <div style='width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden'>$nome_fantasia_form</div></div>
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
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_1_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 2:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_2_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$endereco_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO ================================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&uacute;mero:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$numero_residencia_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= BAIRRO ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Bairro/Distrito:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$bairro_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ESTADO =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Estado:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		if ($erro == 10)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$estado_aux</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$estado_aux</div></div>";}
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CIDADE =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Cidade:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		if ($erro == 11)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$cidade_aux</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cidade_aux</div></div>";}
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  COMPLEMENTO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Complemento:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$complemento_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CEP ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		CEP:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$cep_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= E-MAIL ================================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$email_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= OBSERVAÇÃO ============================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Observa&ccedil;&atilde;o:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$obs_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CLASSIFICACAO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Classifica&ccedil;&atilde;o:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		if ($erro == 12)
		{echo"<div style='width:153px; height:25px; border:1px solid #F00; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden; color:#F00'>$classificacao_print</div></div>";}
		else
		{echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$classificacao_print</div></div>";}
		?>
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
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$banco_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= AGÊNCIA ================================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Ag&ecirc;ncia:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$agencia_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= Nº DA CONTA ============================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&ordm; da Conta:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$numero_conta_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO DE CONTA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Conta:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$tipo_conta_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO DE CHAVE PIX ====================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Chave Pix:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$tipo_chave_pix_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CHAVE PIX ============================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Pix:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$chave_pix_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->









</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:335px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_1_formulario.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Novo Cadastro</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO EDITAR ========================================================================================================
    if ($permite_editar == "SIM")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/editar_1_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='tipo_pessoa_form' value='$tipo_pessoa_form' />
		<input type='hidden' name='nome_form' value='$nome_form' />
		<input type='hidden' name='cpf_form' value='$cpf_form' />
		<input type='hidden' name='cnpj_form' value='$cnpj_form' />
		<input type='hidden' name='rg_form' value='$rg_form' />
		<input type='hidden' name='data_nascimento_form' value='$data_nascimento_form' />
		<input type='hidden' name='sexo_form' value='$sexo_form' />
		<input type='hidden' name='nome_fantasia_form' value='$nome_fantasia_form' />
		<input type='hidden' name='telefone_1_form' value='$telefone_1_form' />
		<input type='hidden' name='telefone_2_form' value='$telefone_2_form' />
		<input type='hidden' name='endereco_form' value='$endereco_form' />
		<input type='hidden' name='numero_residencia_form' value='$numero_residencia_form' />
		<input type='hidden' name='bairro_form' value='$bairro_form' />
		<input type='hidden' name='estado' value='$estado' />
		<input type='hidden' name='cidade' value='$cidade' />
		<input type='hidden' name='complemento_form' value='$complemento_form' />
		<input type='hidden' name='cep_form' value='$cep_form' />
		<input type='hidden' name='email_form' value='$email_form' />
		<input type='hidden' name='obs_form' value='$obs_form'>
		<input type='hidden' name='classificacao_1_form' value='$classificacao_1_form'>
		<input type='hidden' name='banco_form' value='$banco_form'>
		<input type='hidden' name='agencia_form' value='$agencia_form'>
		<input type='hidden' name='numero_conta_form' value='$numero_conta_form'>
		<input type='hidden' name='tipo_conta_form' value='$tipo_conta_form'>
		<input type='hidden' name='tipo_chave_pix_form' value='$tipo_chave_pix_form'>
		<input type='hidden' name='chave_pix_form' value='$chave_pix_form'>
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


// ====== BOTAO IMPRIMIR =======================================================================================================
/*
    if ($permite_imprimir == "SIM" and $tipo_pessoa_form == "PF")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_pf_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='codigo_pessoa' value='$codigo_pessoa'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir</button>
		</form>
    </div>";
	}

    elseif ($permite_imprimir == "SIM" and $tipo_pessoa_form == "PJ")
    {	
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_pj_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='codigo_pessoa' value='$codigo_pessoa'>
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
*/
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/index_pessoas.php' method='post'>
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
	<form name='voltar' action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_1_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='tipo_pessoa_form' value='$tipo_pessoa_form' />
	<input type='hidden' name='nome_form' value='$nome_form' />
	<input type='hidden' name='cpf_form' value='$cpf_form' />
	<input type='hidden' name='cnpj_form' value='$cnpj_form' />
	<input type='hidden' name='rg_form' value='$rg_form' />
	<input type='hidden' name='data_nascimento_form' value='$data_nascimento_form' />
	<input type='hidden' name='sexo_form' value='$sexo_form' />
	<input type='hidden' name='nome_fantasia_form' value='$nome_fantasia_form' />
	<input type='hidden' name='telefone_1_form' value='$telefone_1_form' />
	<input type='hidden' name='telefone_2_form' value='$telefone_2_form' />
	<input type='hidden' name='endereco_form' value='$endereco_form' />
	<input type='hidden' name='numero_residencia_form' value='$numero_residencia_form' />
	<input type='hidden' name='bairro_form' value='$bairro_form' />
	<input type='hidden' name='estado' value='$estado_aux' />
	<input type='hidden' name='cidade' value='$cidade_aux' />
	<input type='hidden' name='complemento_form' value='$complemento_form' />
	<input type='hidden' name='cep_form' value='$cep_form' />
	<input type='hidden' name='email_form' value='$email_form' />
	<input type='hidden' name='obs_form' value='$obs_form'>
	<input type='hidden' name='classificacao_1_form' value='$classificacao_1_form'>
	<input type='hidden' name='banco_form' value='$banco_form'>
	<input type='hidden' name='agencia_form' value='$agencia_form'>
	<input type='hidden' name='numero_conta_form' value='$numero_conta_form'>
	<input type='hidden' name='tipo_conta_form' value='$tipo_conta_form'>
	<input type='hidden' name='tipo_chave_pix_form' value='$tipo_chave_pix_form'>
	<input type='hidden' name='chave_pix_form' value='$chave_pix_form'>
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
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>