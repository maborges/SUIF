<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'usuarios_desbloqueio';
$titulo = 'Desbloqueio de Usu&aacute;rios';	
$menu = 'cadastro_usuarios';
$modulo = 'cadastros';

include ('../../includes/head.php'); 
?>

<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<?php
// =================================================================================================================
$username = $_POST["username"];
$botao = $_POST["botao"];

$filial = $filial_usuario;

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());



if ($botao == "desbloquear")
{
$desbloquear = mysqli_query ($conexao, "UPDATE usuarios SET contador_bloqueio='0' WHERE username='$username'");
}

elseif ($botao == "bloquear")
{
$desbloquear = mysqli_query ($conexao, "UPDATE usuarios SET contador_bloqueio='4' WHERE username='$username'");
}

else
{}


$busca_usuario_x = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
$linha_usuario_x = mysqli_num_rows ($busca_usuario_x);

?>


<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_cadastro.php'); ?>

<?php include ('../../includes/sub_menu_cadastro_usuario.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">

<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:65px">
    Desbloqueio de Usu&aacute;rios
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:65px">
    	<div id="titulo_form_4" style="margin-top:7px">
		<?php 
        if ($linha_usuario_x == 1)
        {echo"<i><b>$linha_usuario_x</b> Usu&aacute;rio</i>";}
        elseif ($linha_usuario_x == 0)
        {echo"";}
        else
        {echo"<i><b>$linha_usuario_x</b> Usu&aacute;rios</i>";}
        ?>
        </div>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_4" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:65px">
    O usu&aacute;rio ser&aacute; bloqueado automaticamente quando errar a senha 4 vezes consecutivas.
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_usuario_x == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:950px; margin:auto; border:1px solid #BCD2EE; border-radius:0px'>";}
?>

<div id="centro" style="height:20px; width:950px; border:0px solid #000; margin:auto"></div>


<div id='centro' style='height:26px; width:880px; border:0px solid #0099CC; background-color:#003466; color:#FFFFFF; font-size:10px; margin:auto; border-radius:3px'>
	<div id='geral' style='height:auto; width:80px; border:0px solid #000; float:left; margin-top:6px; text-align:left'></div>
	<div id='geral' style='height:auto; width:300px; border:0px solid #000; float:left; margin-top:6px; text-align:left'>Usu&aacute;rio</div>
	<div id='geral' style='height:auto; width:150px; border:0px solid #000; float:left; margin-top:6px; text-align:center'></div>
	<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:6px; text-align:center'>Desbloquear</div>
	<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:6px; text-align:center'>Bloquear</div>
</div>



<?php
for ($x=1 ; $x<=$linha_usuario_x ; $x++)
{
$aux_u_bloq = mysqli_fetch_row($busca_usuario_x);

$codigo_usuario = $aux_u_bloq[0];
$username = $aux_u_bloq[0];
$nome_usuario = $aux_u_bloq[2];
$contador_bloqueio = $aux_u_bloq[10];


// RELATORIO =========================
	if ($contador_bloqueio >= 4)
	{echo "
	<div id='centro' style='height:26px; width:880px; border:0px solid #0099CC; color:#FF0000; font-size:12px; margin:auto; 
	border-radius:3px; background-color:#EEE; margin-top:6px'>
		<div id='geral' style='height:auto; width:80px; border:0px solid #000; float:left; margin-top:6px; text-align:left'></div>
		<div id='geral' style='height:auto; width:300px; border:0px solid #000; float:left; margin-top:6px; text-align:left'>$username</div>
		<div id='geral' style='height:auto; width:150px; border:0px solid #000; float:left; margin-top:6px; text-align:center'></div>
		<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:3px; text-align:center'>
			<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_desbloqueio.php' method='post'>
			<input type='hidden' name='username' value='$aux_u_bloq[0]' />
			<input type='hidden' name='botao' value='desbloquear' />
			<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' title='Desbloquear usu&aacute;rio' />
			</form>
		</div>
		<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:3px; text-align:center'></div>
		<div id='geral' style='height:auto; width:90px; border:0px solid #000; float:left; margin-top:6px; text-align:center'>(Bloqueado)</div>
	</div>
	";}

	else
	{echo "<div id='centro' style='height:26px; width:880px; border:0px solid #0099CC; color:#0000FF; font-size:12px; margin:auto; 
	border-radius:3px; background-color:#EEE; margin-top:6px'>
		<div id='geral' style='height:auto; width:80px; border:0px solid #000; float:left; margin-top:6px; text-align:left'></div>
		<div id='geral' style='height:auto; width:300px; border:0px solid #000; float:left; margin-top:6px; text-align:left'>$username</div>
		<div id='geral' style='height:auto; width:150px; border:0px solid #000; float:left; margin-top:6px; text-align:center'></div>
		<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:3px; text-align:center'>
		</div>
		<div id='geral' style='height:auto; width:120px; border:0px solid #000; float:left; margin-top:3px; text-align:center'>
			<form action='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_desbloqueio.php' method='post'>
			<input type='hidden' name='username' value='$aux_u_bloq[0]' />
			<input type='hidden' name='botao' value='bloquear' />
			<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' title='Bloquear usu&aacute;rio' />
			</form>
		</div>
		<div id='geral' style='height:auto; width:90px; border:0px solid #000; float:left; margin-top:6px; text-align:center'></div>
	</div>
	";}
	

}


// =================================================================================================================

?>


<?php
if ($linha_usuario_x == 0)
{echo "
<div id='centro' style='height:26px; width:880px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum usu&aacute;rio encontrado.</i></div>";}
else
{}
?>




<div id="centro" style="height:20px; width:1080px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>