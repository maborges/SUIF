<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_relatorio_fornecedor_seleciona';
$titulo = 'Estoque - Relat&oacute;rio de Sa&iacute;das';
$modulo = 'estoque';
$menu = 'saida';


// ======= RECEBENDO POST =================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
// ========================================================================================================


// ========================================================================================================
include ('../../includes/head.php'); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:480px; margin:auto; background-color:#FFF; border-radius:20px; border:1px solid #999">
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Estoque - Relat&oacute;rio de Sa&iacute;das
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:14px">
	Selecione abaixo o fornecedor e o produto
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<div id="centro" style="height:36px; width:1250px; border:0px solid #000; margin:auto; background-color:#708090">
 
    <form name="popup" action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/saida_relatorio_fornecedor.php" method="post">
    <input type="hidden" name="botao" value="selecionar" />

	<div id="centro" style="height:36px; width:10px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:20px; width:145px; border:0px solid #999; float:left; margin-top:11px"></div>

    <div id="centro" style="height:20px; width:105px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:left; margin-top:11px">
        <i>Fornecedor (F2):</i>
    </div>

	<div id="centro" style="height:22px; width:46px; border:0px solid #999; float:left; text-align:left; margin-top:8px">
		<script type="text/javascript">
        function abrir(programa,janela)
        {
            if(janela=="") janela = "janela";
            window.open(programa,janela,'height=270,width=700');
        }
        
        </script>
        <script type="text/javascript" src="fornecedor_funcao.js"></script>

	    <img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" 
        onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Pesquisar fornecedor" />
	</div>
    
    <div id="centro" style="height:20px; width:65px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
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

		<input id="busca" type="text" name="fornecedor" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" 
        onkeydown="if (getKey(event) == 13) return false; " style="color:#0000FF; height:16px; width:50px; font-size:12px; text-align:center" 
        value="<?php echo"$fornecedor"; ?>" />
	</div>

	<div id="centro" style="height:22px; width:410px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
        <div id="tabela_1" style="width:6px; border:0px solid #000"></div>
        <div id="resultado" style="width:380px; overflow:hidden; height:18px; float:left; border:1px solid #999; background-color:#FFF; 
        color:#0000FF; font-size:12px; font-style:normal; padding-top:3px; padding-left:5px"></div>
	</div>

    <div id="centro" style="height:20px; width:70px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:right; margin-top:11px">
		<i>Produto:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:140px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
        <select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="height:21px; width:130px; color:#0000FF; font-size:11px" />
        <option value="TODOS">(TODOS)</option>
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



    <div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#FFF; font-size:11px; float:left; margin-left:20px; margin-top:8px">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
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