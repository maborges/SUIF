<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "usuarios_resetar_senha";
$titulo = "Resetar Senha de Usu&aacute;rio";	
$modulo = "cadastros";
$menu = "cadastro_usuarios";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$username_w = $_POST["username_w"];

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIADOR DE SENHA =========================================================================================
$monta_senha = "@W9y." . $diretorio_servidor;
$senha_provisoria = md5($monta_senha);
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CONFIRMA_RESETAR")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Deseja realmente resetar a senha deste usu&aacute;rio?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== RESETAR SENHA USUÁRIO ======================================================================================
if ($botao == "RESETAR" and $erro == 0 and $permissao[129] == "S")
{
// RESETAR
$editar = mysqli_query ($conexao, "UPDATE usuarios SET senha='$senha_provisoria', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE username='$username_w'");

// MONTA MENSAGEM
$msg = "<div style='color:#0000FF'>Senha do usu&aacute;rio <b>$username_w</b> resetada com sucesso! Senha provis&oacute;ria: <b>$monta_senha</b></div>";
$username_w = "";
}

elseif ($botao == "RESETAR" and $permissao[129] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para resetar senha</div>";
$username_w = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
$linha_registro = mysqli_num_rows ($busca_registro);
// ==================================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

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
<?php include ("../../includes/submenu_cadastro_usuarios.php"); ?>
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
    if ($linha_registro == 1)
    {echo"<i>$linha_registro usu&aacute;rio cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo"";}
    else
    {echo"<i>$linha_registro usu&aacute;rios cadastrados</i>";}
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
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<div class="pqa" style="height:63px">
<!-- ======================================= FORMULARIO ========================================================== -->


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:30px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/usuarios/usuarios_resetar_senha.php" method="post" />
    <?php
	if ($botao == "CONFIRMA_RESETAR")
	{echo "
	<input type='hidden' name='botao' value='RESETAR' />
	<input type='hidden' name='username_w' value='$username_w' />";}

	else
	{}
	?>
</div>
<!-- ================================================================================================================ -->


<!-- ======= USERNAME =============================================================================================== -->
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
    Usu&aacute;rio:
    </div>
    
    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <?php
	echo "
	<input type='text' name='username_form' class='form_input' onkeydown='if (getKey(event) == 13) return false;' 
	style='width:191px; text-align:left; padding-left:5px; color:#999' disabled='disabled' value='$username_w' />";
	?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($botao == "CONFIRMA_RESETAR")
	{echo "<button type='submit' class='botao_1'>Resetar</button>";}

	else
	{}
	?>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO CANCELAR ========================================================================================= -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($botao == "CONFIRMA_RESETAR")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_resetar_senha.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	else
	{}
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
if ($linha_registro == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='300px'>Usu&aacute;rio</td>
<td width='350px'>Nome</td>
<td width='200px'>Telefone</td>
<td width='150px'>Status</td>
<td width='60px'>Resetar</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO USUÁRIO ============================================================================
$username_w = $aux_registro[0];
$primeiro_nome_w = $aux_registro[2];
$nome_completo_w = $aux_registro[3];
$email_w = $aux_registro[4];
$usuario_interno_w = $aux_registro[5];
$telefone_w = $aux_registro[9];
$estado_registro_w = $aux_registro[11];
$filial_w = $aux_registro[12];

$usuario_cadastro_w = $aux_registro[15];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[16]));
$hora_cadastro_w = $aux_registro[17];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BLOQUEIO PARA RESETAR ========================================================================
$permite_resetar = "SIM";
/*
if ($permissao[129] == "S")
{$permite_resetar = "SIM";}
else
{$permite_resetar = "NAO";}
*/
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' height='34px' title=' Filial: $filial_w &#13; E-mail: $email_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_5' height='34px' title=' Filial: $filial_w &#13; E-mail: $email_w &#13; $dados_cadastro_w'>";}

echo "
<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$username_w</div></td>
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$primeiro_nome_w</div></td>
<td width='200px' align='center'>$telefone_w</td>
<td width='150px' align='center'>$estado_registro_w</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_resetar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_resetar_senha.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='CONFIRMA_RESETAR'>
		<input type='hidden' name='username_w' value='$username_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/atualizar.png' height='20px' border='0' />
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
if ($linha_registro == 0)
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum usu&aacute;rio cadastrado.</i></div>";}
// =================================================================================================================
?>




<div class="espacamento_30"></div>


</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px; color:#FF0000">
	Aten&ccedil;&atilde;o:
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	Oriente o usu&aacute;rio que troque por uma senha pessoal assim que acessar o sistema.
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
    <?php
	if ($permissao[129] == "S")
	{echo "Ap&oacute;s resetar a senha do usu&aacute;rio, a senha provis&oacute;ria ser&aacute;: <b>$monta_senha</b>";}
	?>
	</div>
</div>
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