<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "acerto_alterar_enviar";
$titulo = "Acerto de Quantidade (Quebra / Des&aacute;gio)";
$modulo = "compras";
$menu = "compras";

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
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php
// =================================================================================================================
$filial = $filial_usuario;

$numero_compra = $_POST["numero_compra"];

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$cod_produto = $_POST["cod_produto"];
$cod_tipo = $_POST["cod_tipo"];
$fornecedor = $_POST["fornecedor"];
$monstra_situacao = $_POST["monstra_situacao"];
$num_compra_aux = $_POST["num_compra_aux"];
$motivo_alteracao = $_POST["motivo_alteracao"];
$nova_quantidade = $_POST["nova_quantidade"];
$novo_valor = $_POST["novo_valor"];
$quantidade_desconto = $_POST["quantidade_desconto"];
$quantidade_original = $_POST["quantidade_original"];
$valor_original = $_POST["valor_original"];
$motivo_alteracao_obs = $_POST["motivo_alteracao"];

$novo_saldo_pagar = $_POST["novo_saldo_pagar"];

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y/m/d', time());


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
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


// ====== BUSCA TIPO PRODUTO ===================================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro='ATIVO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


$alterar = mysqli_query ($conexao, "UPDATE compras SET quantidade='$nova_quantidade', valor_total='$novo_valor', desconto_quantidade='$quantidade_desconto', usuario_altera_quant='$usuario_alteracao', data_altera_quant='$data_alteracao', hora_altera_quant='$hora_alteracao', motivo_alteracao_quantidade='$motivo_alteracao_obs', quantidade_original='$quantidade_original', valor_total_original='$valor_original', saldo_pagar='$novo_saldo_pagar' WHERE numero_compra='$numero_compra'");



// ==================================================================
// ATUALIZA SALDO ===================================================
include ('../../includes/busca_saldo_armaz.php');

$saldo = $saldo_produtor + $quantidade_desconto;

include ('../../includes/atualisa_saldo_armaz.php');
// ==================================================================
// ==================================================================

		



		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Quantidade da compra alterada com sucesso!</div>
		<div id='centro' style='float:left; height:130px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:920px; color:#000066; text-align:center; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; da compra: $numero_compra</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:320px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";

if ($pagina_filha == "movimentacao")
{
echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
		</form>
		</div>";
}
else
{
echo "
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='num_compra_aux' value='$num_compra_aux'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
		</form>
		</div>";

}


echo "

		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";	





?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>