<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'talao_impressao';
$titulo = 'Tal&atilde;o Saldo de Produtor';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ================================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==========================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ========================================================================================================


// ====== RECEBE POST ==============================================================================================
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== SALDO PRODUTOR =================================================================================
// ------ SOMA QUANTIDADE DE ENTRADA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_e = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_e_print = number_format($soma_quant_total_produto_e[0],2,",",".");

// ------ SOMA QUANTIDADE DE SAÍDA (GERAL) -----------------------------------------------------------------------
$soma_quant_total_produto_s = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND cod_produto='$cod_produto' AND fornecedor='$fornecedor' AND filial='$filial'"));
$quant_produto_total_s_print = number_format($soma_quant_total_produto_s[0],2,",",".");

// ------ CALCULA SALDO GERAL POR PRODUTO -------------------------------------------------------------------------
$saldo_geral_produto = ($soma_quant_total_produto_e[0] - $soma_quant_total_produto_s[0]);
$saldo_geral_produto_print = number_format($saldo_geral_produto,2,",",".");

/*
// CALCULO SALDO  ==========================================================================================
	$soma_entrada = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='ENTRADA' OR movimentacao='TRANSFERENCIA_ENTRADA' OR movimentacao='ENTRADA_FUTURO') AND produto='$produto_list' AND fornecedor='$produtor_ficha' AND filial='$filial'"));

	$soma_saida = mysqli_fetch_row(mysqli_query($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND (movimentacao='COMPRA' OR movimentacao='TRANSFERENCIA_SAIDA' OR movimentacao='SAIDA' OR movimentacao='SAIDA_FUTURO') AND produto='$produto_list' AND fornecedor='$produtor_ficha' AND filial='$filial'"));

	$saldo = ($soma_entrada[0] - $soma_saida[0]);
	$saldo_print = number_format($saldo,2,",",".");
*/
// ======================================================================================================


// ====== BUSCA CONTROLE DE TALAO ========================================================================
$busca_talao = mysqli_query ($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$fornecedor' AND devolvido='N' AND filial='$filial' ORDER BY codigo");
$linha_talao = mysqli_num_rows ($busca_talao);
// ======================================================================================================


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
<div id="centro" style="height:450px; width:1080px; border:0px solid #000; margin:auto">


<div id="centro" style="height:40px; width:1080px; border:0px solid #000"></div>
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/ficha_produtor/talao_impressao.php" method="post" />
<input type='hidden' name='fornecedor' value='<?php echo"$fornecedor"; ?>' />
<input type='hidden' name='cod_produto' value='<?php echo"$cod_produto"; ?>' />
<input type='hidden' name='numero_compra' value='<?php echo"$numero_compra"; ?>' />


	<div id="centro" style="height:10px; width:100px; border:0px solid #000; color:#003466; font-size:12px; text-align:left; float:left">
	</div>

	<div id="centro" style="height:30px; width:500px; border:0px solid #000; color:#003466; font-size:12px; text-align:left; float:left">
	&#160;&#160;&#8226; <b>Tal&atilde;o Saldo de Produtor</b>
	</div>

	<div id="centro" style="height:10px; width:1080px; border:0px solid #000; color:#003466; font-size:12px; text-align:center; float:left">
	</div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #000; color:#999999; font-size:10px; text-align:right; float:left">
	Produtor:
	</div>

	<div id="centro" style="height:30px; width:530px; border:0px solid #000; color:#0000FF; font-size:12px; text-align:left; float:left; margin-left:10px">
	<b><?php echo "$fornecedor_print"; ?></b>
	</div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #000; color:#999999; font-size:10px; text-align:right; float:left">
	Produto:
	</div>

	<div id="centro" style="height:30px; width:530px; border:0px solid #000; color:#0000FF; font-size:12px; text-align:left; float:left; margin-left:10px">
	<b>
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "$produto_print";
	else
	echo "";
	?>
	</b>
	</div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #000; color:#999999; font-size:10px; text-align:right; float:left">
	Saldo:
	</div>

	<div id="centro" style="height:30px; width:530px; border:0px solid #000; color:#0000FF; font-size:12px; text-align:left; float:left; margin-left:10px">
	<b>
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "$saldo_geral_produto_print $unidade_print";
	else
	echo "";
	?>
	</b>
	</div>

	<div id="centro" style="height:30px; width:300px; border:0px solid #000; color:#999999; font-size:10px; text-align:right; float:left">
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "Observa&ccedil;&atilde;o:";
	else
	echo "";
	?>
	</div>


	<div id="centro" style="height:30px; width:530px; border:0px solid #000; color:#0000FF; font-size:12px; text-align:left; float:left; margin-left:10px">
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "<input type='text' name='observacao' maxlength='150' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:500px' id='ok' />";
	else
	echo "";
	?>
	</div>


	<div id="centro" style="height:30px; width:300px; border:0px solid #000; color:#999999; font-size:10px; text-align:right; float:left">
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "Quantidade de Vias:";
	else
	echo "";
	?>
	</div>


	<div id="centro" style="height:30px; width:530px; border:0px solid #000; color:#0000FF; font-size:12px; text-align:left; float:left; margin-left:10px">
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "<input type='radio' name='quant_via' value='1' checked='checked' /><font style='color:#0000FF; font-size:12px'>1 Via &#160;&#160;&#160;&#160;<input type='radio' name='quant_via' value='2' /><font style='color:#0000FF; font-size:12px'>2 Vias";
	else
	echo "";
	?>
	</div>





	<div id="centro" style="height:10px; width:1080px; border:0px solid #000; color:#003466; font-size:12px; text-align:center; float:left">
	</div>


	<div id="centro" style="height:30px; width:1080px; border:0px solid #000; color:#FF0000; font-size:12px; text-align:center; float:left">
	<?php
	if ($linha_talao == 0 or $permissao[44] == 'S')
	echo "
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/talao.jpg' border='0' />
	<!--
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/talao_1via.jpg' border='0' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/talao_2vias.jpg' border='0' />
	-->
	</form>";
	else
	echo "
	</form>
	<script>
	function enviar_formulario()
	{document.talao.submit()} 
	</script> 
	
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post' name='talao' />
	<input type='hidden' name='botao' value='ficha' />
	<input type='hidden' name='fornecedor' value='$produtor_ficha' />
	</form>

	<a href='javascript:enviar_formulario()' style='text-decoration:none; color:#FF0000'><b>&#10004; </b><i>Este produtor possui pend&ecirc;ncia de tal&atilde;o.</i></a>";
	
	?>
	</div>




	<div id="centro" style="height:10px; width:1080px; border:0px solid #000; color:#003466; font-size:12px; text-align:center; float:left">
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