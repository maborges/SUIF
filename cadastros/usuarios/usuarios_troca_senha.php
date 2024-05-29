<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "usuarios_troca_senha";
$titulo = "Trocar de Senha";	
$modulo = "cadastros";
$menu = "cadastro_usuarios";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"] ?? '';
//$username = $_SESSION["u_suif"];
$username = $_COOKIE["u_suif"] ?? '';
$nova_senha = $_POST["nova_senha"] ?? '';
$confirma_senha = $_POST["confirma_senha"] ?? '';

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIPTOGRAFIA DA SENHA =========================================================================================
$senha_cript = md5($nova_senha);
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "TROCAR" and $nova_senha == "")
{$erro = 1;
$msg = "<div id='oculta' style='color:#FF0000'>A senha n&atilde;o pode ficar em branco</div>";}

elseif ($botao == "TROCAR" and $confirma_senha == "")
{$erro = 2;
$msg = "<div id='oculta' style='color:#FF0000'>A senha n&atilde;o pode ficar em branco.</div>";}

elseif ($botao == "TROCAR" and ($nova_senha != $confirma_senha))
{$erro = 3;
$msg = "<div id='oculta' style='color:#FF0000'>Voc&ecirc; digitou senhas diferentes</div>";}
 
else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== TROCAR SENHA ==============================================================================================
if ($botao == "TROCAR" and $erro == 0)
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE usuarios SET senha='$senha_cript', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE username='$username'");

// MONTA MENSAGEM
$msg = "<div style='color:#0000FF'>Sua senha foi alterada com sucesso! Para concluir esse processo saia do sistema e fa&ccedil;a o login com a nova senha.</div>";

setcookie ("s_suif", $senha_cript, time()+43200);
}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_usuario_x = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND username='$username' ORDER BY username");
$linha_usuario_x = mysqli_num_rows ($busca_usuario_x);

// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_usuario_x ; $x++)
{
$aux_usuario_x = mysqli_fetch_row($busca_usuario_x);

// ====== DADOS DO USUÁRIO ============================================================================
$username_w = $aux_usuario_x[0];
$primeiro_nome_w = $aux_usuario_x[2];
$nome_completo_w = $aux_usuario_x[3];
$email_w = $aux_usuario_x[4];
$usuario_interno_w = $aux_usuario_x[5];
$telefone_w = $aux_usuario_x[9];
$estado_registro_w = $aux_usuario_x[11];
$filial_w = $aux_usuario_x[12];
// ================================================================================================================
}


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


<!-- ============================================================================================================= -->
<div style="width:1250px; height:100px; margin:auto; border:1px solid transparent">


<!-- =======  USERNAME =========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
	Usu&aacute;rio:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
    <?php echo "<b>$username_w</b>" ?></div>
    </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME ================================================================================================== -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:337px; height:17px; border:1px solid transparent; float:left">
	Nome:
	</div>
    
    <div style="width:337px; height:25px; float:left; border:1px solid transparent">
	<div style="width:323px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:313px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo "$primeiro_nome_w"; ?></div>
    </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  EMAIL ================================================================================================= -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:337px; height:17px; border:1px solid transparent; float:left">
	E-mail:
	</div>
    
    <div style="width:337px; height:25px; float:left; border:1px solid transparent">
	<div style="width:323px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:313px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo "$email_w"; ?></div>
    </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE =============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
    <?php echo"$telefone_pessoa" ?></div>
    </div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= FILIAL ================================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
    <?php echo"$filial_w" ?></div>
    </div>
    </div>
</div>
<!-- ================================================================================================================ -->




</div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:195px; margin:auto">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/usuarios/usuarios_troca_senha.php" method="post" />
<input type="hidden" name="botao" value="TROCAR" />


	<div style="width:436px; height:50px; border:1px solid transparent; margin-top:10px; float:left"></div>


<!-- ======= NOVA SENHA ============================================================================================ -->
	<div style="width:580px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:575px; height:17px; border:1px solid transparent; float:left">
        Nova senha:
        </div>
        
        <div style="width:575px; height:25px; float:left; border:1px solid transparent">
        <input type="password" name="nova_senha" class="form_input" id="ok" maxlength="20" onkeydown="if (getKey(event) == 13) return false;" style="width:150px" />
        </div>
	</div>
<!-- ================================================================================================================ -->


	<div style="width:436px; height:50px; border:1px solid transparent; margin-top:10px; float:left"></div>


<!-- ======= CONFIRMAR SENHA ============================================================================================ -->
	<div style="width:580px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:575px; height:17px; border:1px solid transparent; float:left">
        Confirmar nova senha:
        </div>
        
        <div style="width:575px; height:25px; float:left; border:1px solid transparent">
        <input type="password" name="confirma_senha" class="form_input" id="ok" maxlength="20" onkeydown="if (getKey(event) == 13) return false;" style="width:150px" />
        </div>
	</div>
<!-- ================================================================================================================ -->


	<div style="width:424px; height:50px; border:1px solid transparent; margin-top:10px; float:left"></div>


<!-- ======= BOTÃO ================================================================================================== -->
	<div style="width:580px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div style="width:575px; height:48px; float:left; border:1px solid transparent">
		<button type='submit' class='botao_2' style='width:180px'>Alterar Senha</button>
		</form>
        </div>
	</div>
<!-- ================================================================================================================ -->



</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->




<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:900px; float:left; margin-left:25px; text-align:left; font-size:12px; color:#FF0000">
	Aten&ccedil;&atilde;o:
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:900px; float:left; margin-left:25px; text-align:left; font-size:12px">
	Crie uma senha que tenha pelo menos 8 caracteres. Inclua uma mistura de letras mai&uacute;sculas, min&uacute;sculas, n&uacute;meros e s&iacute;mbolos como @#!*.
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:900px; float:left; margin-left:25px; text-align:left; font-size:12px">
    Evite o uso de palavras muito comuns. Elas s&atilde;o alvos f&aacute;ceis para programas de quebra de senha. Tamb&eacute;m evite o uso de palavras e n&uacute;meros que s&atilde;o facilmente associadas a voc&ecirc;, como nomes e datas.
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