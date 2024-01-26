<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'preco_compra';
	$titulo = 'Pre&ccedil;o de Compra';
	$modulo = 'compras';
	$menu = 'produtos';


// ====== CONVERTE DATA ================================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ======================================================================================================


// ====== CONVERTE VALOR =================================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =======================================================================================================


// ====== DADOS PARA BUSCA =================================================================================
$valor_maximo = ConverteValor($_POST["valor_maximo"]);
$produto = $_POST["produto"];
$cod_produto = $_POST["cod_produto"];
$botao = $_POST["botao"];

$filial = $filial_usuario;

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());
// =======================================================================================================


// =================================================================================================================
if ($botao == "alterar")
{
$alterar = mysqli_query ($conexao, "UPDATE cadastro_produto SET preco_compra_maximo='$valor_maximo', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE codigo='$cod_produto'");
}

else
{}
// =================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' ORDER BY codigo");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// =======================================================================================================
	include ('../../includes/head.php'); 
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 3000); // 3 Segundos

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

<?php include ('../../includes/sub_menu_compras_produtos.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->

<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- =================== INÍCIO CENTRO GERAL ======================================== -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>

<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:306px; height:30px; float:left; border:0px solid #000; margin-left:60px">
    Pre&ccedil;o de compra
    </div>

	<div id="centro" style="height:30px; width:306px; border:0px solid #000; text-align:center; color:#003466; font-size:12px; float:left">
    	<div id='oculta' style="color:#00F; margin-top:8px">
        <?php
        if ($botao == "alterar")
		{echo "Pre&ccedil;o atualizado com sucesso!";}
		else
		{}
		?>
		</div>
	</div>


	<div style="width:306px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#666; margin-right:60px">
    	<?php 
        if ($linhas_bp_geral == 1)
        {echo"<i><b>$linhas_bp_geral</b> Produto</i>";}
        elseif ($linhas_bp_geral == 0)
        {echo"";}
        else
        {echo"<i><b>$linhas_bp_geral</b> Produtos</i>";}
        ?>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:60px; font-size:11px; color:#003466">
    Valor m&aacute;ximo por unidade
    </div>
</div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>
<!-- ====================================================================================== -->

<?php
if ($linhas_bp_geral == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<div style='height:33px; width:980px; border:0px solid #0099CC; color:#666; font-size:11px; margin:auto'>
    <div style='height:25px; width:50px; border:0px solid #000; float:left; margin-top:7px; text-align:left'></div>
    <div style='height:25px; width:220px; border:0px solid #000; float:left; margin-top:7px; text-align:left'>Produto</div>
    <div style='height:25px; width:350px; border:0px solid #000; float:left; margin-top:7px; text-align:left'>&Uacute;ltima atualiza&ccedil;&atilde;o</div>
    <div style='height:25px; width:120px; border:0px solid #000; float:left; margin-top:7px; text-align:center'>Pre&ccedil;o</div>
    <div style='height:25px; width:70px; border:0px solid #000; float:left; margin-top:7px; text-align:center'>Unidade</div>
    <div style='height:25px; width:150px; border:0px solid #000; float:left; margin-top:5px'></div>
</div>



<?php
for ($x=1 ; $x<=$linhas_bp_geral ; $x++)
{
$aux_produto = mysqli_fetch_row($busca_produto_geral);

$codigo = $aux_produto[0];
$descricao = $aux_produto[1];
$produto_print = $aux_produto[22];
$apelido = $aux_produto[20];
$unidade_print = $aux_produto[26];
$preco_maximo_aux = $aux_produto[21];
$preco_maximo_print = number_format($aux_produto[21],2,",",".");
$usuario_alteracao_print = $aux_produto[16];
$hora_alteracao_print = $aux_produto[17];
$data_alteracao_print = date('d/m/Y', strtotime($aux_produto[18]));


// RELATORIO =========================
	echo "
	<div id='centro' style='height:33px; width:980px; border:1px solid #0099CC; color:#0000FF; font-size:12px; margin:auto'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/preco_compra.php' method='post'>
		<input type='hidden' name='cod_produto' value='$codigo' />
		<input type='hidden' name='produto' value='$apelido' />
		<input type='hidden' name='botao' value='alterar' />
		<div id='geral' style='height:25px; width:50px; border:0px solid #000; float:left; margin-top:7px'></div>
		<div id='geral' style='height:25px; width:220px; border:0px solid #000; float:left; margin-top:7px'>$descricao</div>
		<div id='geral' style='height:25px; width:350px; border:0px solid #000; float:left; margin-top:7px; text-align:left'>
		$data_alteracao_print $hora_alteracao_print $usuario_alteracao_print</div>
		<div id='geral' style='height:25px; width:120px; border:0px solid #000; float:left; margin-top:7px; text-align:center'>
		<input type='text' name='valor_maximo' maxlength='15' onkeypress='mascara(this,mvalor)' style='color:#0000FF; width:100px; font-size:12px; text-align:center' value='$preco_maximo_print' /></div>
		<div id='geral' style='height:25px; width:70px; border:0px solid #000; float:left; margin-top:7px; text-align:center'>$unidade_print</div>
		<div id='geral' style='height:25px; width:150px; border:0px solid #000; float:left; margin-top:5px'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_confirmar.png' border='0' />
		</form>
		</div>
	</div>
	
	<div id='centro' style='height:12px; width:880px; border:0px solid #0099CC; color:#0000FF; font-size:12px; margin:auto; border-radius:2px;'>
	</div>
	";

}


// =================================================================================================================

?>


<?php
if ($linhas_bp_geral == 0)
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum produto encontrado.</i></div>";}
else
{}
?>




<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>