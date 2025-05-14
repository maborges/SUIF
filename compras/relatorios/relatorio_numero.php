<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../includes/desconecta_bd.php");
$pagina = "relatorio_numero";
$titulo = "Buscar Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());

$numero_compra_busca = $_POST["numero_compra_busca"] ?? '';
// ================================================================================================================

// Inicializa variaveis do relatório
$dados_exclusao_w = $_POST['dados_exclusao_w'] ?? '';
$data_inicial_br = $_POST['data_inicial_br'] ?? '';
$data_final_br = $_POST['data_final_br'] ?? '';
$fornecedor_pesquisa = $_POST['fornecedor_pesquisa'] ?? '';
$nome_fornecedor = $_POST['nome_fornecedor'] ?? '';
$cod_produto_busca = $_POST['cod_produto_busca'] ?? '';
$cod_tipo_busca = $_POST['cod_tipo_busca'] ?? '';
$filial_busca = $_POST['filial_busca'] ?? '';
$fornecedor_busca = $_POST['fornecedor_busca'] ?? '';
$cod_seleciona_produto = $_POST['cod_seleciona_produto'] ?? '';
$dados_alteracao_w = $_POST['dados_alteracao_w'] ?? '';


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
$mysql_numero = "numero_compra='$numero_compra_busca'";
$mysql_status = "estado_registro='ATIVO'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_compra = mysqli_query ($conexao, 
				"SELECT 
					codigo,
					numero_compra,
					fornecedor,
					produto,
					data_compra,
					quantidade,
					preco_unitario,
					valor_total,
					unidade,
					tipo,
					observacao,
					data_pagamento,
					usuario_cadastro,
					hora_cadastro,
					data_cadastro,
					usuario_alteracao,
					hora_alteracao,
					data_alteracao,
					estado_registro,
					filial,
					fornecedor_print,
					forma_entrega,
					usuario_exclusao,
					hora_exclusao,
					data_exclusao,
					id_pedido_sankhya,
					filial_faturamento
				FROM 
					compras
				WHERE 
					$mysql_numero AND
					$mysql_status AND
					movimentacao='COMPRA'
				ORDER BY 
					codigo");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
// ================================================================================================================


// ================================================================================================================
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


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/relatorios/relatorio_numero.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="nome_fornecedor" value="<?php echo"$nome_fornecedor"; ?>" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    N&uacute;mero da Compra:
    </div>

    <div class="pqa_campo">
    <input type="text" name="numero_compra_busca" class="pqa_input" id="ok" maxlength="12" value="<?php echo $numero_compra_busca; ?>" style="width:120px" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo">
    <button type='submit' class='botao_1' style='float:left'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->

	
</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php if ($linha_compra == 0): ?>
	<div class='espacamento' style='height:400px'>
   <div class='espacamento' style='height:30px'></div>
<?php endif; ?>

<?php if ($linha_compra != 0): ?>
	<div class='ct_relatorio'>

	<table class='tabela_cabecalho'>
		<tr>
			<td width='60px'>Visualizar</td>
			<td width='60px'>Editar</td>
			<td width='60px'>Pgto</td>
			<td width='100px'>Data</td>
			<td width='350px'>Fornecedor</td>
			<td width='80px'>Número</td>
			<td width='80px'>Sankhya</td>
			<td width='160px'>Produto</td>
			<td width='80px'>Tipo</td>
			<td width='120px'>Quantidade</td>
			<td width='100px'>Preço Unitário</td>
			<td width='140px'>Valor Total</td>
		</tr>
	</table>

<?php endif; ?>

<table class='tabela_geral'>


<?php





// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
	$aux_compra = mysqli_fetch_row($busca_compra);

	// ====== DADOS DO CADASTRO ============================================================================
	$id_w = $aux_compra[0];
	$numero_compra_w = $aux_compra[1];
	$cod_fornecedor_w = $aux_compra[2];
	$produto_print_w = $aux_compra[3];
	$data_compra_w = $aux_compra[4];
	$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
	$quantidade_w = $aux_compra[5];
	$quantidade_print = number_format($aux_compra[5],2,",",".");
	$preco_unitario_w = $aux_compra[6];
	$preco_unitario_print = number_format($aux_compra[6],2,",",".");
	$total_geral_w = $aux_compra[7];
	$total_geral_print = "R$ " . number_format($aux_compra[7],2,",",".");
	$unidade_w = $aux_compra[8];
	$tipo_w = $aux_compra[9];
	$observacao_w = $aux_compra[10];
	$data_pagamento_w = $aux_compra[11];
	$data_pagamento_print = date('d/m/Y', strtotime($aux_compra[11]));
	$usuario_cadastro_w = $aux_compra[12];
	$hora_cadastro_w = $aux_compra[13];
	$data_cadastro_w = $aux_compra[14];
	$usuario_alteracao_w = $aux_compra[15];
	$hora_alteracao_w = $aux_compra[16];
	$data_alteracao_w = $aux_compra[17];
	$estado_registro_w = $aux_compra[18];
	$filial_w = $aux_compra[19];
	$fornecedor_print_w = $aux_compra[20];
	$forma_entrega_w = $aux_compra[21];
	$usuario_exclusao_w = $aux_compra[22];
	$hora_exclusao_w = $aux_compra[23];
	$data_exclusao_w = $aux_compra[24];
	$pedidoSankhya_w = $aux_compra[25];
	$filialFaturamento_w = $aux_compra[26];


	if (!empty($usuario_cadastro_w)) {
		$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;
	}

	if (!empty($usuario_alteracao_w)) {
		$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;
	}

	if (!empty($usuario_exclusao_w)) {
		$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;
	}
	// ======================================================================================================


	// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
	$permite_visualizar = "SIM";
	// ========================================================================================================


	// ====== RELATORIO =======================================================================================
	if ($estado_registro_w == "INATIVO") 	{
		echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
	} elseif ($estado_registro_w == "EXCLUIDO") {
		echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
	} else {
		echo "<tr class='tabela_1' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";
	}



	// ====== BOTAO VISUALIZAR ==================================================================================
	if ($permite_visualizar == "SIM") {	
		echo "
		<td width='60px' align='center'>
		<div style='height:24px; margin-top:0px; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/compras/compra_visualizar.php' method='post' />
		<input type='hidden' name='modulo_mae' value='$modulo'>
		<input type='hidden' name='menu_mae' value='relatorios'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='VISUALIZAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_compra' value='$numero_compra_w'>
		<input type='hidden' name='pedidoSankhya' value='$pedidoSankhya_w'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
		<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
		<input type='hidden' name='filial_busca' value='$filial_busca'>
		<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
		<input type='hidden' name='filialFaturamento' value='$filialFaturamento_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
		</form>
		</div>
		</td>";
	} else {
		echo "<td width='60px' align='center'></td>";
	}
	// =================================================================================================================

	// =================================================================================================================
	if ($permissao[65] == "S") {	
		echo "
		<td width='60px' align='center'>
		<div style='height:24px; margin-top:0px; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post' />
		<input type='hidden' name='modulo_mae' value='$modulo'>
		<input type='hidden' name='menu_mae' value='$menu'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDITAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_compra' value='$numero_compra_w'>
		<input type='hidden' name='pedidoSankhya' value='$pedidoSankhya_w'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='numero_venda_busca' value='$numero_compra_busca'>
		<input type='hidden' name='filial_busca' value='$filial_busca'>
		<input type='hidden' name='filialFaturamento' value='$filialFaturamento_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='18px' style='margin-top:3px' />
		</form>
		</div>
		</td>";
	} else {
		echo "<td width='60px' align='center'></td>";
	}
	// =================================================================================================================

	// =================================================================================================================
	echo "
		<td width='60px' align='center'>
		<div style='height:24px; margin-top:0px; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post' />
		<input type='hidden' name='modulo_mae' value='$modulo'>
		<input type='hidden' name='menu_mae' value='$menu'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUIR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_compra' value='$numero_compra_w'>
		<input type='hidden' name='pedidoSankhya' value='$pedidoSankhya_w'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
		<input type='hidden' name='filial_busca' value='$filial_busca'>
		<input type='hidden' name='filialFaturamento' value='$filialFaturamento_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/financeiro_2.png' height='18px' style='margin-top:3px' />
		</form>
		</div>
		</td>";
	// =================================================================================================================




	// =================================================================================================================
	echo "
		<td width='100px' align='center'>$data_compra_print</td>
		<td width='350px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$fornecedor_print_w</div></td>
		<td width='80px' align='center'>$numero_compra_w</td>
		<td width='80px' align='center'>$pedidoSankhya_w</td>
		<td width='160px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produto_print_w</div></td>
		<td width='80px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$tipo_w</div></td>
		<td width='120px' align='right'><div style='height:14px; margin-right:10px'>$quantidade_print $unidade_w</div></td>
		<td width='100px' align='right'><div style='height:14px; margin-right:10px'>$preco_unitario_print</div></td>
		<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$total_geral_print</div></td>";
	// =================================================================================================================

}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_compra == 0 and $botao == "BUSCAR") {
	echo "
		<div class='espacamento' style='height:30px'></div>
		<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
		<i>Nenhuma compra encontrada.</i></div>";
}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php
//include ("../../includes/rodape.php");
?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>



