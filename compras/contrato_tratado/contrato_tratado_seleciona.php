<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'contrato_tratado_seleciona';
$titulo = 'Contrato Tratado';
$modulo = 'compras';
$menu = 'contratos';


// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
// ========================================================================================================


// ========================================================================================================
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

<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">
<form name="popup" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/contrato_tratado_cadastro.php" method="post">
<input type="hidden" name="botao" value="selecionar" />

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:185px">
    Contrato Tratado - Cadastro
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
    <img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" height="18px" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar fornecedor" />
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
</div>
<!-- ============================================================================================================= -->














<div style="width:1080px; height:50px; float:left; border:0px solid #000; text-align:center"></div>


<div style="width:1080px; height:15px; float:left; border:0px solid #000; text-align:center">
<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Confirmar</button>
</form>
</div>








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