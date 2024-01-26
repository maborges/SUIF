<?php
include ("includes/config.php");
include ("includes/valida_cookies_index.php");
$pagina = "troca_filial";
$titulo = "Trocar Filial - " . $nome_fantasia;
$modulo = "";
$menu = "";
// ================================================================================================================


// ================================================================================================================
include ("includes/head.php");
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
</div>

<div class="submenu">
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1030px; height:390px; margin:auto; border:1px solid transparent; color:#003466">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:30px; width:1030px; font-size:22px; color:#009900; text-align:center; border:1px solid transparent">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/troca_filial_enviar.php" method="post" />
Selecione uma filial:
</div>


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div style="height:30px; width:1030px; text-align:center; border:1px solid transparent">
<select name="filial" class="form_select" id="ok" maxlength="20" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px" />
<option></option>
<?php
// ====== BUSCA TABELA FILIAIS ===================================================================================
include ("includes/conecta_bd.php");
$busca_tabela_filial = mysqli_query ($conexao, "SELECT descricao, apelido FROM filiais WHERE estado_registro='ATIVO' ORDER BY codigo");
include ("includes/desconecta_bd.php");
// ===============================================================================================================

$linhas_tabela_filial = mysqli_num_rows ($busca_tabela_filial);

for ($f=1 ; $f<=$linhas_tabela_filial ; $f++)
{
	$aux_tabela_filial = mysqli_fetch_row($busca_tabela_filial);	
	echo "<option value='$aux_tabela_filial[0]'>$aux_tabela_filial[1]</option>";
}
?>
</select>
</div>
<!-- ========================================================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


<!-- =============================================================================================== -->
<div style="height:50px; width:1030px; border:1px solid transparent">
<button type='submit' class='botao_2' style='width:180px; margin-left:425px'>Confirmar</button>
</form>
</div>
<!-- =============================================================================================== -->









</div>
<!-- =============================================================================================== -->


</div>
<!-- ====== FIM DIV CT =========================================================================================== -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("includes/desconecta_bd.php"); ?>
</body>
</html>