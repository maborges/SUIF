<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	include ("../../helpers.php");
	$pagina = 'relatorio_tipo';
	$titulo = 'Relat&oacute;rio de Compras';
	$modulo = 'compras';
	$menu = 'relatorio';

// ====== DADOS PARA BUSCA =================================================================================
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$cod_tipo = $_POST["cod_tipo"];
$mostra_cancelada = $_POST["mostra_cancelada"];
$botao = $_POST["botao"];
// =======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

if ($cod_produto == "" or $linhas_bp == 0)
{$produto_print = "(Necess&aacute;rio selecionar um produto)";}
else
{$produto_print = $aux_bp[1];}

$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

if ($fornecedor == "" or $linhas_fornecedor == 0)
{$fornecedor_print = "(TODOS)";}
else
{$fornecedor_print = $aux_forn[1];}

$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


if ($fornecedor == "" or $linhas_fornecedor == 0)
{
// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
	
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================
}

else
{
// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
	
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");
// =======================================================================================================
}


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ===========================================================================================================
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

<?php include ('../../includes/sub_menu_compras_relatorio.php'); ?>
</div> <!-- FIM menu_geral -->


<!-- =============================================   C E N T R O   =============================================== -->


<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- INÍCIO CENTRO GERAL -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Relat&oacute;rio de Compras - Por Tipo
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->



<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>
	<div id="centro" style="height:25px; width:90px; border:0px solid #000; color:#666; font-size:12px; float:left">
	Fornecedor:
	</div>
	<div id="centro" style="height:25px; width:500px; border:0px solid #000; color:#00F; font-size:12px; float:left">
	<b><?php echo"$fornecedor_print"; ?></b>
	</div>
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; color:#666; font-size:12px; float:left">
	Produto:
	</div>
  	<?php 
		if ($cod_produto == "" or $linhas_bp == 0)
		{echo"<div id='centro' style='height:25px; width:250px; border:0px solid #000; color:#F00; font-size:12px; float:left'> $produto_print</div>";}
		else
		{echo"<div id='centro' style='height:25px; width:250px; border:0px solid #000; color:#00F; font-size:12px; float:left'><b> $produto_print</b></div>";}
	?>
</div>



<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:70px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:922px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_tipo.php" method="post" />
		<input type='hidden' name='botao' value='1' />
        <input type='hidden' name='fornecedor' value='<?php echo"$fornecedor"; ?>' />
        <input type='hidden' name='cod_produto' value='<?php echo"$cod_produto"; ?>' />

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Tipo:&#160;</i></div>

		<div id="centro" style="width:205px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:205px; height:3px; float:left; border:0px solid #999"></div>
        <select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:200px; font-size:12px; text-align:left" />
        <option></option>
        <?php
            $busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
            $linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);
        
        for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
        {
        $aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
        
            if ($aux_tipo_produto[0] == $cod_tipo)
            {echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
            else
            {echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
        }
        ?>
        </select>
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right; margin-left:20px">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_aux"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_aux"; ?>" />
		</div>


		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left; margin-left:20px">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" border="0" style="float:left" />
		</form>
		</div>
		
		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>


<!-- ================================================================================================================================ -->
    


<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_compra >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_tipo_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_compra == 1)
	{echo"<i><b>$linha_compra</b> Compra</i>";}
	elseif ($linha_compra == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_compra</b> Compras</i>";}
	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_compra >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->
<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);


if ($fornecedor == "" or $linhas_fornecedor == 0)
{
$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}
}

else
{
$soma_compra_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$soma_cp_print = number_format($soma_compra_produto[0],2,",",".");
$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND data_compra>='$data_inicial' AND data_compra<='$data_final' AND movimentacao='COMPRA' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND cod_tipo='$cod_tipo' AND filial='$filial'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compra_produto[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}
}




	if ($soma_compra_produto[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
		<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
			<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
			<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#009900'>
			<b>$aux_bp_geral[22]</b>	
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Quant. comprada: $quant_produto_print $aux_bp_geral[26]
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Valor total: R$ $soma_cp_print
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Pre&ccedil;o m&eacute;dio: R$ $media_produto_print / $aux_bp_geral[26]
			</div>
		</div>
	</div>
	<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
	";}


}
?>



<!-- ====================================================================================== -->
<?php include ('../../includes/relatorio_compras_2.php'); ?>


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