<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "seleciona_produtor";
$titulo = "Movimenta&ccedil;&atilde;o Ficha do Produtor";
$modulo = "compras";
$menu = "ficha_produtor";
// ========================================================================================================


// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
// ========================================================================================================


// ========================================================================================================
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
<form name="popup" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/movimentacao_produtor.php" method="post" />
<input type="hidden" name="botao" value="seleciona" />

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:185px">
    Ficha do Produtor
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:185px">
    Selecione abaixo o fornecedor e o produto
    </div>
</div>

<div style="width:1080px; height:50px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:525px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Fornecedor (F2):
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Produto:
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= CODIGO E FORNECEDOR =============================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:525px; height:35px; float:left; border:0px solid #000; margin-left:185px">
	<script type="text/javascript">
    function abrir(programa,janela)
        {
            if(janela=="") janela = "janela";
            window.open(programa,janela,'height=270,width=700');
        }
    
    </script>
    <script type="text/javascript" src="fornecedor_funcao.js"></script>
    
    <!-- ========================================================================================================== -->
    <div id="centro" style="float:left; border:0px solid #000; margin-top:3px" >
    <img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/buscar.png" height="20px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar fornecedor" />
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
                var aux_f2 = document.popup.fornecedor.value;
                javascript:foco('busca');
                javascript:abrir('busca_pessoa_popup.php');
                //javascript:buscarNoticias(aux_f2);
            }
        }
    </script>
    
    <!-- ========================================================================================================== -->
    <input id="busca" type="text" name="fornecedor" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; width:50px; font-size:12px; text-align:center" value="<?php echo"$fornecedor"; ?>" />&#160;</div>
 
    <div id="tabela_1" style="width:6px; border:0px solid #000"></div>
    <div id="resultado" style="width:380px; overflow:hidden; height:16px; float:left; border:1px solid #999; color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>

    </div>

	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:12px; text-align:left" />
    <option></option>
    <?php
	include ("../../includes/cadastro_produto.php"); 

	for ($i=0 ; $i<=count($cadastro_produto) ; $i++)
	{
        if ($cadastro_produto[$i]["codigo"] == $cod_produto_busca)
        {echo "<option selected='selected' value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
        else
        {echo "<option value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
	}
    ?>
    </select>
    </div>
</div>
<!-- ============================================================================================================= -->














<div style="width:1080px; height:50px; float:left; border:0px solid #000; text-align:center"></div>


<div style="width:1080px; height:30px; float:left; border:0px solid #000; text-align:center">
<button type="submit" class="botao_2" style="margin-left:400px; width:180px">Buscar</button>
</form>
</div>








</div>
</div>





<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>