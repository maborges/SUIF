<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_cadastro_3';
$titulo = 'Entrada';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ==========================================================================================================


// ====== CONTADOR NÚMERO ROMANEIO ==========================================================================
$busca_numero_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnr = mysqli_fetch_row($busca_numero_romaneio);
$numero_romaneio = $aux_bnr[8];

$contador_num_romaneio = $numero_romaneio + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_num_romaneio'");
// ========================================================================================================



$aux_cod_produtor = $_POST["aux_cod_produtor"];
$num_romaneio_manual = $_POST["num_romaneio_manual"];
$produto_list = $_POST["produto_list"];


// PRODUTO PRINT  ==========================================================================================
	if ($produto_list == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto_list == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto_list == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto_list == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}


// BUSCA NUMERO DE ROMANEIO MANUAL  ========================================================================================
	$busca_num_romaneio_manual = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND num_romaneio_manual='$num_romaneio_manual'");
	$achou_num_romaneio_manual = mysqli_num_rows ($busca_num_romaneio_manual);
// ==================================================================================================================


// BUSCA NUMERO DE ROMANEIO  ========================================================================================
	$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND num_romaneio_manual='$num_romaneio_manual'");
	$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
	for ($r=1 ; $r<=$achou_num_romaneio ; $r++)
	{
	$aux_romaneio = mysqli_fetch_row($busca_num_romaneio);
	$aux_romaneio_estoque = $aux_romaneio[1];
	}
// ==================================================================================================================


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
<body onload="javascript:foco('busca');">

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_ficha_produtor.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:400px; width:1000px; border:0px solid #000; margin:auto">

<div id="espaco_2" style="width:995px"></div>

<div id="centro" style="height:50px; width:995px; border:0px solid #000; color:#003466; font-size:12px">
<div id="centro" style="height:30px; width:500px; border:0px solid #000; color:#003466; font-size:14px; margin-left:180px; margin-top:18px"><b>&bull; Entrada</b></div>
</div>


<form name="compra_cafe" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_enviar_3.php" method="post">
<input type="hidden" name="unidade" value="KG" />

<!-- ====================================================================================== -->
<div style="width:190px; height:235px; border:0px solid #000; float:left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/estoque_entrada.jpg" border="0" alt="entrada" title="Estoque - Entrada" style="margin-top:20px" />
</div>

<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero do Romaneio:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:600px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:595px; height:5px; border:0px solid #000"></div>Produtor (F2):</div>





<!-- =========================================  CODIGO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="numero_romaneio_aux" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$num_romaneio_manual"; ?>" disabled="disabled" />
<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />
<input type="hidden" name="num_romaneio_manual" value="<?php echo "$num_romaneio_manual"; ?>" />
<input type="hidden" name="pagina" value="<?php echo "$pagina"; ?>" />
<input type="hidden" name="produto_list" value="<?php echo "$produto_list"; ?>" />

</div>

<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:525px; border:0px solid #000">

<script type="text/javascript">
function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=270,width=700');
	}

</script>
<script type="text/javascript" src="representante_funcao.js"></script>

<!-- ========================================================================================================== -->
<div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar produtor" />
<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/pessoas/cadastro_pessoa_fisica.php" target="_blank">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_editar.png" border="0" height="18px" title="Cadastrar novo fornecedor" /></a>
</div>

<div id="centro" style="float:left; border:0px solid #000; margin-top:0px; font-size:12px">
&#160;

<!-- ========================================================================================================== -->
<script type="text/javascript">
document.onkeyup=function(e)
	{
		if(e.which == 113)
		{
			//Pressionou F2, aqui vai a função para esta tecla.
			//alert(tecla F2);
			var aux_f2 = document.compra_cafe.representante.value;
			javascript:foco('busca');
			javascript:abrir('busca_pessoa_popup.php');
			//javascript:buscarNoticias(aux_f2);
		}
	}
</script>

<!-- ========================================================================================================== -->
<input id="busca" type="text" name="representante" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; width:50px; font-size:12px" value="<?php echo"$aux_cod_produtor"; ?>" />&#160;</div>
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="resultado" style="width:325px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>


</div>




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Peso Liquido (Kg):</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Desconto (Kg):</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Produto:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Tipo:</div>

<!-- =========================================  PESO INICIAL ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="peso_inicial" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" /></div>

<!-- =========================================  DESCONTO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="desconto" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="0" />
</div>

<!-- ========================================= PRODUTO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="produto" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$produto_print"; ?>" disabled="disabled" />

</div>


<!-- ========================================= TIPO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<?php
if ($produto_list == "CAFE" or $produto_list == "CAFE_ARABICA")
{
	echo "
	<select name='tipo' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:149px; font-size:12px; text-align:left' />
	<option></option>";

	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE produto='cafe' AND (estado_registro!='INATIVO' OR estado_registro IS NULL) ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

	for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
	{
	$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
	
		if ($aux_tipo_produto[1] == "7/8")
		{echo "<option selected='selected' value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
		else
		{echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
	}
	echo "</select>";
}

elseif ($produto_list == "CACAU")
{
	echo "
	<select name='tipo' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:149px; font-size:12px; text-align:left' />
	<option></option>";

	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE produto='cacau' AND (estado_registro!='INATIVO' OR estado_registro IS NULL) ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

	for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
	{
	$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
	echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";
/*	
		if ($aux_tipo_produto[1] == "Tipo I")
		{echo "<option selected='selected' value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
		else
		{echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
*/
	}
	echo "</select>";
}

elseif ($produto_list == "PIMENTA")
{
	echo "
	<select name='tipo' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:149px; font-size:12px; text-align:left' />
	<option></option>";

	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE produto='pimenta' AND (estado_registro!='INATIVO' OR estado_registro IS NULL) ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

	for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
	{
	$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);
	echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";
/*	
		if ($aux_tipo_produto[1] == "B1")
		{echo "<option selected='selected' value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
		else
		{echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
*/
	}
	echo "</select>";
}

elseif ($produto_list == "CRAVO")
{
	echo "
	<select name='tipo' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:149px; font-size:12px; text-align:left' />
	<option></option>";

	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE produto='cravo' AND (estado_registro!='INATIVO' OR estado_registro IS NULL) ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

	for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
	{
	$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
	
		if ($aux_tipo_produto[1] == "BAHIA 1")
		{echo "<option selected='selected' value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
		else
		{echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
	}
	echo "</select>";
}

elseif ($produto_list == "RESIDUO_CACAU")
{
	echo "
	<select name='tipo' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:149px; font-size:12px; text-align:left' />
	<option></option>";

	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE produto='residuo_cacau' AND (estado_registro!='INATIVO' OR estado_registro IS NULL) ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

	for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
	{
	$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
	
		if ($aux_tipo_produto[1] == "Grinders - Nibs")
		{echo "<option selected='selected' value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
		else
		{echo "<option value='$aux_tipo_produto[1]'>$aux_tipo_produto[1]</option>";}
	}
	echo "</select>";
}



else
{
echo "<input type='text' name='tipo' maxlength='30' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:145px' value='-' disabled='disabled' />";	
}
?>

</div>





<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<!-- =========================================  OBSERVAÇÃO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:689px" /></div>

<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!--Placa do Ve&iacute;culo:--></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div><!--Placa do Ve&iacute;culo:--></div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:430px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:425px; height:5px; border:0px solid #000"></div><!--Motorista:--></div>



<!-- =========================================  MOVIMENTAÇÃO DE ESTOQUE ====================================== -->
<div id="tabela_2" style="width:300px; border:0px solid #000">
<input type='checkbox' name='movimenta_estoque' value='SIM' >Movimenta&ccedil;&atilde;o - Entrada no estoque.
</div>



<!-- ========================================= SITUAÇÃO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:420px; border:0px solid #000">
<!-- <input type="text" name="motorista" maxlength="50" onkeydown="if (getKey(event) == 13) return false;" onBlur="alteraMaiusculo(this)" style="color:#0000FF; width:145px; font-size:12px; text-align:left" /> -->
</div>





<!-- =========================================  MENSAGEM ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<?php
if ($num_romaneio_manual == "")
{echo "<b style='color:#FF0000; font-size:12px'>Informe o n&uacute;mero do romaneio manual.</b>";}

elseif ($produto_list == "")
{echo "<b style='color:#FF0000; font-size:12px'>Selecione um produto.</b>";}

elseif ($achou_num_romaneio_manual >= 1)
{echo "<b style='color:#FF0000; font-size:12px'>Aten&ccedil;&atilde;o: J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio.</b>";}
/*
elseif ($achou_num_romaneio >= 1)
{echo "<b style='color:#FF0000; font-size:12px'>Aten&ccedil;&atilde;o: J&aacute; existe um romaneio da balan&ccedil;a para esta entrada. </b><b style='color:#0000FF; font-size:12px'>N&ordm; $aux_romaneio_estoque</b>";}
*/
else
{echo "";}
?>
</div>



<!-- =============================================================================================== -->
<div id="espaco_2" style="width:925px"></div>

<div id="tabela_2" style="width:930px; text-align:center; border:0px solid #000">

<?php
if ($num_romaneio_manual == "")
{echo "";}

elseif ($produto_list == "")
{echo "";}

elseif ($achou_num_romaneio_manual >= 1)
{echo "";}
/*
elseif ($achou_num_romaneio >= 1)
{echo "";}
*/
else
{echo "<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_confirmar_2.png' border='0' />";}
?>


<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_seleciona_3.php"><img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/voltar_3.png" border="0" alt="Voltar" /></a>
</div>



</form>






</div>
</div>




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>