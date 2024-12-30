<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "buscar_romaneio";
$titulo = "Buscar Romaneio";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"] ?? '';;
$pagina_mae = $_POST["pagina_mae"] ?? '';;
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());

$numero_romaneio_busca = $_POST["numero_romaneio_busca"] ?? '';;
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
$mysql_filial = "estoque.filial='$filial_usuario'";

$mysql_status = "estoque.estado_registro='ATIVO'";

$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");


$busca_romaneio = mysqli_query ($conexao,
"SELECT
	estoque.codigo,
	estoque.numero_romaneio,
	estoque.fornecedor,
	estoque.data,
	estoque.produto,
	estoque.peso_inicial,
	estoque.peso_final,
	estoque.desconto_sacaria,
	estoque.desconto,
	estoque.quantidade,
	estoque.unidade,
	estoque.tipo_sacaria,
	estoque.movimentacao,
	estoque.placa_veiculo,
	estoque.motorista,
	estoque.observacao,
	estoque.usuario_cadastro,
	estoque.hora_cadastro,
	estoque.data_cadastro,
	estoque.usuario_alteracao,
	estoque.hora_alteracao,
	estoque.data_alteracao,
	estoque.filial,
	estoque.estado_registro,
	estoque.quantidade_prevista,
	estoque.quantidade_sacaria,
	estoque.numero_compra,
	estoque.motorista_cpf,
	estoque.num_romaneio_manual,
	estoque.filial_origem,
	estoque.quant_volume_sacas,
	estoque.cod_produto,
	estoque.usuario_exclusao,
	estoque.hora_exclusao,
	estoque.data_exclusao,
	cadastro_pessoa.nome,
	select_tipo_sacaria.descricao,
	select_tipo_sacaria.peso
FROM
	estoque,
	cadastro_pessoa,
	select_tipo_sacaria
WHERE
	(estoque.numero_romaneio='$numero_romaneio_busca' AND
	$mysql_filial AND
	$mysql_status AND
	$mysql_movimentacao) AND
	estoque.fornecedor=cadastro_pessoa.codigo AND
	estoque.tipo_sacaria=select_tipo_sacaria.codigo
ORDER BY
	estoque.codigo");


include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo $titulo; ?>
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
<?php include ("../../includes/menu_estoque.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_estoque_entrada.php"); ?>
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
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/romaneio_visualizar_2.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    N&uacute;mero do Romaneio:
    </div>

    <div class="pqa_campo">
    <input type="text" name="numero_romaneio_busca" class="form_input" id="ok" maxlength="10" 
    value="<?php echo"$numero_romaneio_busca"; ?>" style="width:145px" />
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
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ("include_relatorio.php"); ?>
<!-- ============================================================================================================= -->


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
<?php //include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>