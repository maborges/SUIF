<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_1_selec_produto';
$titulo = 'Movimenta&ccedil;&atilde;o Interna de Estoque';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== CONTADOR NÚMERO MOVIMENTAÇÃO ==========================================================================
$busca_numero_movimentacao = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnm = mysqli_fetch_row($busca_numero_movimentacao);
$numero_movimentacao = $aux_bnm[27];

$contador_num_movimentacao = $numero_movimentacao + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_movimentacao='$contador_num_movimentacao'");
// ================================================================================================================


// ====== BUSCA PREÇO CAFE ===================================================================================
$busca_cafe = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='2'");
$aux_cafe = mysqli_fetch_row($busca_cafe);

$cod_cafe = $aux_cafe[0];
$cafe_print = $aux_cafe[22];
$preco_max_cafe = $aux_cafe[21];
$preco_max_cafe_print = number_format($aux_cafe[21],2,",",".");
// ======================================================================================================


// ====== BUSCA PREÇO PIMENTA ===================================================================================
$busca_pimenta = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='3'");
$aux_pimenta = mysqli_fetch_row($busca_pimenta);

$cod_pimenta = $aux_pimenta[0];
$pimenta_print = $aux_pimenta[22];
$preco_max_pimenta = $aux_pimenta[21];
$preco_max_pimenta_print = number_format($aux_pimenta[21],2,",",".");
// ======================================================================================================


// ====== BUSCA PREÇO CACAU ===================================================================================
$busca_cacau = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='4'");
$aux_cacau = mysqli_fetch_row($busca_cacau);

$cod_cacau = $aux_cacau[0];
$cacau_print = $aux_cacau[22];
$preco_max_cacau = $aux_cacau[21];
$preco_max_cacau_print = number_format($aux_cacau[21],2,",",".");
// ======================================================================================================


// ================================================================================================================
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
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Selecione um produto
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:272px; width:1270px; border:0px solid #0000FF; margin:auto">

    <div style="height:250px; width:165px; border:0px solid #0000FF; float:left"></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/cadastro_2_formulario.php" method="post" name="produto_cafe" />
    <input type="hidden" name="cod_produto_form" value="<?php echo"$cod_cafe"; ?>" />
    <input type="hidden" name="numero_movimentacao" value="<?php echo"$numero_movimentacao"; ?>" />
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class="produto" style="height:220px; width:280px; float:left; margin-left:0px; margin-top:26px">
	    <div style="height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px">
    	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/produto_cafe.png" style="width:240px">
    	</div>
	    <div style="height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466">
    	<b><?php echo"$cafe_print"; ?></b>
    	</div>
	    <div style="height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999">
		<?php // echo"R$ $preco_max_cafe_print"; ?>
    	</div>
    </div>
    </a>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/cadastro_2_formulario.php" method="post" name="produto_pimenta" />
    <input type="hidden" name="cod_produto_form" value="<?php echo"$cod_pimenta"; ?>" />
    <input type="hidden" name="numero_movimentacao" value="<?php echo"$numero_movimentacao"; ?>" />
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class="produto" style="height:220px; width:280px; float:left; margin-left:50px; margin-top:26px">
	    <div style="height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px">
    	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/produto_pimenta.png" style="width:240px">
    	</div>
	    <div style="height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466">
    	<b><?php echo"$pimenta_print"; ?></b>
    	</div>
	    <div style="height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999">
		<?php // echo"R$ $preco_max_pimenta_print"; ?>
    	</div>
    </div>
    </a>
 
 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/cadastro_2_formulario.php" method="post" name="produto_cacau" />
    <input type="hidden" name="cod_produto_form" value="<?php echo"$cod_cacau"; ?>" />
    <input type="hidden" name="numero_movimentacao" value="<?php echo"$numero_movimentacao"; ?>" />
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class="produto" style="height:220px; width:280px; float:left; margin-left:50px; margin-top:26px">
	    <div style="height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px">
    	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/produto_cacau.png" style="width:240px">
    	</div>
	    <div style="height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466">
    	<b><?php echo"$cacau_print"; ?></b>
    	</div>
	    <div style="height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999">
		<?php // echo"R$ $preco_max_cacau_print"; ?>
    	</div>
    </div>
    </a>


</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:36px; width:1270px; border:0px solid #0000FF; margin:auto">
 
    

	<div style="height:36px; width:525px; border:0px solid #000; float:left"></div>

	<div style="height:34px; width:250px; border:0px solid #999; float:left">
        <div style="margin-top:0px; margin-left:0px">
        <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/cadastro_2_formulario.php" method="post" name="produtos" />
        <input type="hidden" name="numero_movimentacao" value="<?php echo"$numero_movimentacao"; ?>" />
        
        <select name="cod_produto_form" class="produto_2" onchange="document.produtos.submit()" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:220px; height:32px; font-size:14px; text-align:center" />
        <option>(Outros Produtos)</option>
        <?php
        $busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
        $linhas_produto_list = mysqli_num_rows ($busca_produto_list);
        
        for ($j=1 ; $j<=$linhas_produto_list ; $j++)
        {
        $aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
        if ($aux_produto_list[0] == $cod_produto_form)
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
        </form>
        </div>
	</div>

</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->







<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->




</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>