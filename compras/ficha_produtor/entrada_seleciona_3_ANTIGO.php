<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_seleciona_3';
$titulo = 'Entrada de Mercadoria';
$menu = 'ficha_produtor';
$modulo = 'compras';

$aux_cod_produtor = $_POST["aux_cod_produtor"];

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


<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_cadastro_3.php" method="post">
<input type="hidden" name="aux_cod_produtor" value="<?php echo"$aux_cod_produtor"; ?>" />

<div style="width:190px; height:235px; border:0px solid #000; float:left">
<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/estoque_entrada.jpg" border="0" alt="entrada" title="Estoque - Entrada" style="margin-top:20px" />
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
N&uacute;mero do romaneio manual:
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
<input type="text" name="num_romaneio_manual" id="ok" maxlength="20" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px" />
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
Produto:
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#003466; font-size:12px; text-align:center; float:left">
<select name="produto_list" id="ok" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row($busca_produto_list);	
		if ($aux_produto_list[20] == $produto_list)
		{
		echo "<option selected='selected' value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
		}
		else
		{
		echo "<option value='$aux_produto_list[20]'>$aux_produto_list[1]</option>";
		}
	}
?>
</select>

</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
</div>

<div id="centro" style="height:25px; width:700px; border:0px solid #000; color:#666; font-size:12px; text-align:center; float:left">
<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/botao_confirmar_2.png" border="0" />
</form>
</div>



<!-- =============================================================================================== -->










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