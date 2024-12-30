<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_seleciona_3';
$titulo = 'Entrada de Mercadoria';
$menu = 'ficha_produtor';
$modulo = 'compras';

$aux_cod_produtor = $_POST["aux_cod_produtor"] ?? '';

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


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">

<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>




<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>


<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/entrada_cadastro_3.php" method="post">
<input type="hidden" name="aux_cod_produtor" value="<?php echo"$aux_cod_produtor"; ?>" />

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:185px">
    Entrada
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:185px">
    Informe o n&uacute;mero de romaneio manual e o produto:
    </div>
</div>

<div style="width:1080px; height:50px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000; font-size:11px; color:#666; text-align:center">
N&ordm; Romaneio Manual:
</div>
<!-- ============================================================================================================= -->


<!-- =========================================  NUMERO ROMANEIO ====================================== -->
<div id="geral" style="width:1080px; height:30px; border:0px solid #000; float:left; text-align:center">
<input type="text" name="num_romaneio_manual" id="ok" maxlength="20" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px" />
</div>

<div style="width:1080px; height:20px; float:left; border:0px solid #000; text-align:center"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000; font-size:11px; color:#666; text-align:center">
Produto:
</div>
<!-- ============================================================================================================= -->


<!-- =========================================  PRODUTO ========================================================== -->
<div id="geral" style="width:1080px; height:30px; border:0px solid #000; float:left; text-align:center">
<select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:12px; text-align:left" />
<option></option>
<?php
//    $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND (codigo='3' OR codigo='4' OR codigo='9') ORDER BY descricao");
    $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
    $linhas_produto_list = mysqli_num_rows ($busca_produto_list);

    for ($j=1 ; $j<=$linhas_produto_list ; $j++)
    {
        $aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
        if ($aux_produto_list[0] == $cod_produto)
        {
        echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
        }
        else
        {
        echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
        }
    }
?>
</select>
</div>

<div style="width:1080px; height:30px; float:left; border:0px solid #000; text-align:center"></div>
<!-- ============================================================================================================= -->


<!-- =============================================================================================== -->
<div id="geral" style="width:1080px; height:30px; border:0px solid #000; float:left; text-align:center">
<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Confirmar</button>
</form>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
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