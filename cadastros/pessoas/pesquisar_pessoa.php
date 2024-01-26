<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "pesquisar_pessoa";
$titulo = "Pesquisar Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$id_w = $_POST["id_w"];
$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$pagina_mae = $_POST["pagina_mae"];

$pesquisar_por_busca = $_POST["pesquisar_por_busca"];
$nome_busca = $_POST["nome_busca"];
$cpf_busca = $_POST["cpf_busca"];
$cnpj_busca = $_POST["cnpj_busca"];
$fantasia_busca = $_POST["fantasia_busca"];

$nome_w = $_POST["nome_w"];
$cpf_cnpj_w = $_POST["cpf_cnpj_w"];
$telefone_1_w = $_POST["telefone_1_w"];
$cidade_w = $_POST["cidade_w"];

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
$motivo_exclusao = $_POST["motivo_exclusao"];
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if ($pesquisar_por_busca == "NOME")
{$mysql_busca = "nome LIKE '%$nome_busca%' ORDER BY nome";}

elseif ($pesquisar_por_busca == "CPF")
{$mysql_busca = "cpf='$cpf_busca' ORDER BY nome";}

elseif ($pesquisar_por_busca == "CNPJ")
{$mysql_busca = "cnpj='$cnpj_busca' ORDER BY nome";}

elseif ($pesquisar_por_busca == "FANTASIA")
{$mysql_busca = "nome_fantasia LIKE '%$fantasia_busca%' ORDER BY nome";}

else
{$pesquisar_por_busca = "NOME";}
// ================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao_2 == "EXCLUSAO")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir este cadastro?</div>";
}
// ================================================================================================================


// ====== ATIVAR / INATIVAR CADASTRO ==============================================================================
if ($botao_2 == "ATIVAR" and $permissao[69] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cadastro_pessoa SET estado_registro='ATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE codigo='$id_w'");

$ativar_favorecido = mysqli_query ($conexao, "UPDATE cadastro_favorecido SET estado_registro='ATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE codigo_pessoa='$codigo_pessoa_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro ativado com sucesso!</div>";
}

elseif ($botao_2 == "INATIVAR" and $permissao[69] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cadastro_pessoa SET estado_registro='INATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE codigo='$id_w'");

$inativar_favorecido = mysqli_query ($conexao, "UPDATE cadastro_favorecido SET estado_registro='INATIVO', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE codigo_pessoa='$codigo_pessoa_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro inativado com sucesso!</div>";
}

elseif (($botao_2 == "INATIVAR" or $botao_2 == "ATIVAR") and $permissao[69] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar cadastro.</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR CADASTRO ==========================================================================================
if ($botao_2 == "EXCLUIR" and $permissao[70] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cadastro_pessoa SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', data_exclusao='$data_alteracao', hora_exclusao='$hora_alteracao', motivo_exclusao='$motivo_exclusao' WHERE codigo='$id_w'");

$excluir_favorecido = mysqli_query ($conexao, "UPDATE cadastro_favorecido SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao', data_exclusao='$data_alteracao', hora_exclusao='$hora_alteracao' WHERE codigo_pessoa='$codigo_pessoa_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro exclu&iacute;do com sucesso!</div>";
}

elseif ($botao_2 == "EXCLUIR" and $permissao[70] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir cadastro.</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTRO ============================================================================================
if ($botao == "BUSCAR" and ($nome_busca != "" or $cpf_busca != "" or $cnpj_busca != "" or $fantasia_busca != ""))
{
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE $mysql_busca");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
}
// ==================================================================================================================


// ==================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

</script>

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskedinput-1.3.min.js"></script>

<script>
jQuery(function($){
	// MASK
	$("#cpf").mask("999.999.999-99");
	$("#cnpj").mask("99.999.999/9999-99");
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
	<?php 
    if ($linha_pessoa == 1)
    {echo"$linha_pessoa Cadastro";}
    elseif ($linha_pessoa > 1)
    {echo"$linha_pessoa Cadastros";}
    else
    {echo"";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->





<?php
if ($botao_2 == "EXCLUSAO")
{
echo "
<!-- ======================================= FORMULARIO ========================================================== -->
<div class='pqa' style='height:63px; border:1px solid #FF0000'>


<!-- ======= ESPAÇAMENTO ========================================================================================= -->
<div style='width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/pesquisar_pessoa.php' method='post' />
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='botao_2' value='EXCLUIR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:325px; height:17px; border:1px solid transparent; float:left'>
    Nome:
    </div>
    
    <div style='width:325px; height:25px; float:left; border:1px solid transparent'>

	<input type='text' name='aux_nome' class='form_input' onkeydown='if (getKey(event) == 13) return false;' 
	style='width:300px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$nome_w' />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style='width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:215px; height:17px; border:1px solid transparent; float:left'>
    CPF/CNPJ:
    </div>
    
    <div style='width:215px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='aux_cpf_cnpj' class='form_input' maxlength='50'
    onkeydown='if (getKey(event) == 13) return false;' style='width:191px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$cpf_cnpj_w' />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style='width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:150px; height:17px; border:1px solid transparent; float:left'>
    Telefone:
    </div>
    
    <div style='width:150px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='aux_telefone' class='form_input' maxlength='15' id='telddd_1'
    onkeydown='if (getKey(event) == 13) return false;' style='width:125px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$telefone_1_w' />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style='width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:215px; height:17px; border:1px solid transparent; float:left'>
    Cidade:
    </div>
    
    <div style='width:215px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='aux_cidade' class='form_input' maxlength='100' onBlur='alteraMinusculo(this)'
    onkeydown='if (getKey(event) == 13) return false;' style='width:191px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$cidade_w' />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style='width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:95px; height:17px; border:1px solid transparent; float:left'>
    <!-- Botão: -->
    </div>
    
    <div style='width:95px; height:25px; float:left; border:1px solid transparent'>
	<button type='submit' class='botao_1'>Excluir</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO CANCELAR ========================================================================================= -->
<div style='width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:95px; height:17px; border:1px solid transparent; float:left'>
    <!-- Botão: -->
    </div>
    
    <div style='width:95px; height:25px; float:left; border:1px solid transparent'>
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/pesquisar_pessoa.php' method='post' />
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>
    </div>
</div>
<!-- ================================================================================================================ -->



</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class='espacamento_20'></div>
<!-- ============================================================================================================= -->
";} ?>








<!-- ============================================================================================================= -->
<div class="pqa" style="height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form name="tipo_pesquisa" action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/pesquisar_pessoa.php" method="post" />
<input type="hidden" name="botao" value="TIPO_PESQUISA" />
</div>
<!-- ================================================================================================================ -->

 <!-- ======= PESQUISAR POR ========================================================================================= -->
<div style="width:190px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:188px; height:17px; border:1px solid transparent; float:left">
    Pesquisar por:
    </div>
    
    <div style="width:188px; height:25px; float:left; border:1px solid transparent">
    <select name="pesquisar_por_busca" class="form_select" onkeydown="if (getKey(event) == 13) return false;" onchange="document.tipo_pesquisa.submit()" style="width:160px" />
    <?php
	if ($pesquisar_por_busca == "NOME")
	{echo "<option value='NOME' selected='selected'>Nome / Raz&atilde;o Social</option>";}
	else
	{echo "<option value='NOME'>Nome / Raz&atilde;o Social</option>";}

	if ($pesquisar_por_busca == "CPF")
	{echo "<option value='CPF' selected='selected'>CPF</option>";}
	else
	{echo "<option value='CPF'>CPF</option>";}

	if ($pesquisar_por_busca == "CNPJ")
	{echo "<option value='CNPJ' selected='selected'>CNPJ</option>";}
	else
	{echo "<option value='CNPJ'>CNPJ</option>";}

	if ($pesquisar_por_busca == "FANTASIA")
	{echo "<option value='FANTASIA' selected='selected'>Nome Fantasia</option>";}
	else
	{echo "<option value='FANTASIA'>Nome Fantasia</option>";}

    ?>
    </select>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/pesquisar_pessoa.php" method="post" />
<input type='hidden' name='botao' value='BUSCAR' />
<input type='hidden' name='pesquisar_por_busca' value='<?php echo"$pesquisar_por_busca"; ?>' />
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<?php
if ($pesquisar_por_busca == "NOME")
{echo "
<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
    Nome / Raz&atilde;o Social:
    </div>
    
    <div style='width:328px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='nome_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
	style='width:300px; text-align:left; padding-left:5px' value='$nome_busca' />
    </div>
</div>";}

elseif ($pesquisar_por_busca == "CPF")
{echo "
<div style='width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:178px; height:17px; border:1px solid transparent; float:left'>
    CPF:
    </div>
    
    <div style='width:178px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='cpf_busca' class='form_input' maxlength='14' id='cpf' 
	style='width:150px; text-align:left; padding-left:5px' value='$cpf_busca' />
    </div>
</div>";}

elseif ($pesquisar_por_busca == "CNPJ")
{echo "
<div style='width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:178px; height:17px; border:1px solid transparent; float:left'>
    CNPJ:
    </div>
    
    <div style='width:178px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='cnpj_busca' class='form_input' maxlength='18' id='cnpj' 
	style='width:150px; text-align:left; padding-left:5px' value='$cnpj_busca' />
    </div>
</div>";}

elseif ($pesquisar_por_busca == "FANTASIA")
{echo "
<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
    Nome Fantasia:
    </div>
    
    <div style='width:328px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='fantasia_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
	style='width:300px; text-align:left; padding-left:5px' value='$fantasia_busca' />
    </div>
</div>";}

else
{echo "
<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
    <div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
    Nome / Raz&atilde;o Social:
    </div>
    
    <div style='width:328px; height:25px; float:left; border:1px solid transparent'>
    <input type='text' name='nome_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
	style='width:300px; text-align:left; padding-left:5px' value='$nome_busca' />
    </div>
</div>";}

?>
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
	if ($linha_pessoa >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/relatorio_pessoa_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<button type='submit' class='botao_1'>Imprimir</button>
	</form>";}
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
if ($linha_pessoa == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='70px'>C&oacute;digo</td>
<td width='340px'>Nome</td>
<td width='160px'>CPF/CNPJ</td>
<td width='130px'>Telefone</td>
<td width='250px'>Cidade/UF</td>
<td width='60px'>Visualizar</td>
<td width='60px'>Editar</td>
<td width='60px'>Inativar</td>
<td width='60px'>Excluir</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
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

if ($tipo_w == "PF" or $tipo_w == "pf")
{$cpf_cnpj_print = $cpf_w;}
else
{$cpf_cnpj_print = $cnpj_w;}

$usuario_cadastro_w = $aux_pessoa[28];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_pessoa[30]));
$hora_cadastro_w = $aux_pessoa[29];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_pessoa[31];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_pessoa[33]));
$hora_alteracao_w = $aux_pessoa[32];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_pessoa[36];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_pessoa[37]));
$hora_exclusao_w = $aux_pessoa[38];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_w'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
$permite_ativar = "SIM";
/*
if ($permissao[69] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
*/
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($estado_registro_w != "EXCLUIDO")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "INATIVO")
{echo "<tr class='tabela_4' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
elseif ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_5' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
else
{echo "<tr class='tabela_1' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

echo "
<td width='70px' align='left'><div style='height:14px; margin-left:7px'>$id_w</div></td>
<td width='340px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_w</div></td>
<td width='160px' align='center'>$cpf_cnpj_print</td>
<td width='130px' align='center'>$telefone_1_w</td>
<td width='250px' align='center'>$cidade_w/$estado_w</td>";

// ====== BOTAO VISUALIZAR ===============================================================================================
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/visualizar_cadastro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='VISUALIZAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
		<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
		<input type='hidden' name='nome_busca' value='$nome_busca'>
		<input type='hidden' name='cpf_busca' value='$cpf_busca'>
		<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
		<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' border='0' />
		</form>	
		</td>";
// =================================================================================================================


// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/editar_1_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDITAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
		<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
		<input type='hidden' name='nome_busca' value='$nome_busca'>
		<input type='hidden' name='cpf_busca' value='$cpf_busca'>
		<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
		<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================

// ====== BOTAO ATIVAR / INATIVAR ==================================================================================
	if ($permite_ativar == "SIM" and $estado_registro_w == "INATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/pesquisar_pessoa.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='BUSCAR'>
		<input type='hidden' name='botao_2' value='ATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
		<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
		<input type='hidden' name='nome_busca' value='$nome_busca'>
		<input type='hidden' name='cpf_busca' value='$cpf_busca'>
		<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
		<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/pesquisar_pessoa.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='BUSCAR'>
		<input type='hidden' name='botao_2' value='INATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
		<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
		<input type='hidden' name='nome_busca' value='$nome_busca'>
		<input type='hidden' name='cpf_busca' value='$cpf_busca'>
		<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
		<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================

// ====== BOTAO EXCLUIR ===================================================================================================
	if ($permite_excluir == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/pessoas/pesquisar_pessoa.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='BUSCAR'>
		<input type='hidden' name='botao_2' value='EXCLUSAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
		<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
		<input type='hidden' name='nome_busca' value='$nome_busca'>
		<input type='hidden' name='cpf_busca' value='$cpf_busca'>
		<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
		<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
		<input type='hidden' name='nome_w' value='$nome_w'>
		<input type='hidden' name='cpf_cnpj_w' value='$cpf_cnpj_print'>
		<input type='hidden' name='telefone_1_w' value='$telefone_1_w'>
		<input type='hidden' name='cidade_w' value='$cidade_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================


}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_pessoa == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro encontrado.</i></div>";}
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
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>