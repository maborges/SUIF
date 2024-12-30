<?php
$botao = $_POST["botao"];
if ($botao == "TROCA")
{
	$filial = $_POST["filial"];

	// ====== BUSCA TABELA FILIAIS ==========================================================================	
	include ("includes/conecta_bd.php");
	$busca_tabela_filial = mysqli_query ($conexao, "SELECT apelido FROM filiais WHERE descricao='$filial' ORDER BY codigo");
	include ("includes/desconecta_bd.php");
	// ===============================================================================================================
	
	$aux_tabela_filial = mysqli_fetch_row($busca_tabela_filial);

	$nome_filial = $aux_tabela_filial[0];
	
	if($filial == "")
	{
	}
	else
	{
		setcookie ("filial_suif", $filial, time()+43200);
		setcookie ("nome_filial", $nome_filial, time()+43200);
	echo "
	<script>
	window.self.close();
	</script>";
	exit;
	}
}
// ================================================================================================================


// ================================================================================================================
include ("includes/config.php");
include ("includes/valida_cookies.php");
$pagina = "troca_filial";
$titulo = "Trocar Filial - " . $nome_fantasia;
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
<body>



<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px; width:auto; margin:auto;"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:30px; margin:auto; border:1px solid transparent; text-align:center; font-size:22px; color:#009900">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/troca_filial_2.php" method="post" />
<input type="hidden" name="botao" value="TROCA" />
Selecione uma filial:
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px; width:auto; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:30px; margin:auto; border:1px solid transparent; text-align:center">
<select name="filial" class="pqa_select" id="ok" maxlength="20" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px" />
<option></option>
<?php
// ====== BUSCA TABELA FILIAIS ==========================================================================
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
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px; width:auto; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- =============================================================================================== -->
<div style="height:50px; margin:auto; border:1px solid transparent; text-align:center">
<button type='submit' class='botao_2' style='width:180px; margin-left:35px'>Confirmar</button>
</form>
</div>
<!-- =============================================================================================== -->







</div>
<!-- ====== FIM DIV CT =========================================================================================== -->



<!-- ====== FIM ================================================================================================== -->
<?php include ("includes/desconecta_bd.php"); ?>
</body>
</html>