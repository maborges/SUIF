<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "index_usuarios";
$titulo = "Cadastros de Usu&aacute;rios";	
$modulo = "cadastros";
$menu = "cadastro_usuarios";
// ================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_usuario_x = mysqli_query ($conexao, "SELECT * FROM usuarios WHERE estado_registro!='EXCLUIDO' AND usuario_interno!='S' ORDER BY username");
$linha_usuario_x = mysqli_num_rows ($busca_usuario_x);
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
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_usuario_x == 1)
    {echo"<i>$linha_usuario_x usu&aacute;rio cadastrado</i>";}
    elseif ($linha_usuario_x == 0)
    {echo"";}
    else
    {echo"<i>$linha_usuario_x usu&aacute;rios cadastrados</i>";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	<?php
	if ($permissao[55] == "S")
	{echo "<a href='$servidor/$diretorio_servidor/cadastros/usuarios/usuarios_cadastro.php'>
	<button type='submit' class='botao_1'>Cadastrar Novo Usu&aacute;rio</button></a>";}
	else
	{echo "<button type='submit' class='botao_1' style='color:#BBB'>Cadastrar Novo Usu&aacute;rio</button>";}
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->

<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_usuario_x == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_30'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='300px'>Usu&aacute;rio</td>
<td width='350px'>Nome</td>
<td width='200px'>Telefone</td>
<td width='150px'>Status</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


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

$usuario_cadastro_w = $aux_usuario_x[15];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_usuario_x[16]));
$hora_cadastro_w = $aux_usuario_x[17];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


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

}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_usuario_x == 0)
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