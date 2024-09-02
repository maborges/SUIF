<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "entrada_relatorio_placa";
$titulo = "Estoque - Relat&oacute;rio de Entradas";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$botao_mae = $_POST["botao_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$mes_atras_br = date ('d/m/Y', strtotime('-30 days'));
$filial = $filial_usuario;

$cod_produto_busca = $_POST["cod_produto_busca"];
$placa_veiculo_busca = $_POST["placa_veiculo_busca"];

$numero_romaneio_w = $_POST["numero_romaneio_w"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());

if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($cod_produto_busca == "" or $cod_produto_busca == "TODOS")
	{$mysql_cod_produto = "cod_produto IS NOT NULL";
	$cod_produto_busca = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}

// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_cod_produto AND placa_veiculo LIKE '%$placa_veiculo_busca%' AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_cod_produto AND placa_veiculo LIKE '%$placa_veiculo_busca%' AND filial='$filial' ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_cod_produto AND placa_veiculo LIKE '%$placa_veiculo_busca%' AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  ===============================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ===============================================================================================================


// ====== BUSCA PRODUTO FORM ======================================================================================
$busca_prod = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_busca' AND estado_registro!='EXCLUIDO'");
$aux_prod = mysqli_fetch_row($busca_prod);
$linhas_prod = mysqli_num_rows ($busca_prod);

$prod_print = $aux_prod[1];
// ==============================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_romaneio == 0)
{$print_quant_reg = "";}
elseif ($linha_romaneio == 1)
{$print_quant_reg = "$linha_romaneio ROMANEIO";}
else
{$print_quant_reg = "$linha_romaneio ROMANEIOS";}

if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
{$print_fornecedor = "";}
else
{$print_fornecedor = "$aux_forne[1]";}

/*
if ($botao_mae != "BUSCAR" and ($pagina_mae == "index_entrada" or $pagina_mae == "entrada_relatorio_fornecedor"))
{$print_periodo = "";}
else
{$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";}
*/
$print_periodo = "PER&Iacute;ODO: $data_inicial_br AT&Eacute; $data_final_br";
// ================================================================================================================


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
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ("../../includes/menu_estoque.php"); ?>
<?php include ("../../includes/submenu_estoque_entrada.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Estoque - Relat&oacute;rio de Entradas
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Por Placa do Ve&iacute;culo
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/entrada_relatorio_placa.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Data inicial:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_inicial_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:80px; text-align:center" value="<?php echo"$data_inicial_br"; ?>" />
	</div>

	<div class="pqa_rotulo" style="height:20px; width:85px; border:0px solid #000">Data final:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_final_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:80px; text-align:center" value="<?php echo"$data_final_br"; ?>" />
	</div>

    <div class="pqa_rotulo" style="height:20px; width:90px; border:0px solid #000">Produto:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_produto_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option value="TODOS">(TODOS)</option>
    <?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_busca)
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

    <div class="pqa_rotulo" style="height:20px; width:135px; border:0px solid #000">Placa do Ve&iacute;culo:</div>

	<div style="height:34px; width:110px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="placa_veiculo_busca" maxlength="20" onBlur='alteraMaiusculo(this)' 
    style="width:100px; text-align:left" value="<?php echo"$placa_veiculo_busca"; ?>" />
	</div>


	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>
	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	<?php
	/*
	if ($permissao[17] == "S")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/entrada/cadastro_1_selec_produto.php'>
	<button type='submit' class='botao_1'>Novo Romaneio</button></a>";}
	else
	{echo "<button type='submit' class='botao_1' style='color:#BBB'>Novo Romaneio</button>";}
	
	if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
	*/
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php 
        if ($linha_romaneio == 0)
        {}
        elseif ($linha_romaneio == 1)
        {echo"$linha_romaneio Romaneio";}
        else
        {echo"$linha_romaneio Romaneios";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_romaneio >= 1)
        {echo"Total de Entrada: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
$numero_divs = ceil($linha_produto_distinct / 4);
$alt_div = $numero_divs * 37;
$altura_div = $alt_div . "px";

echo "<div style='height:$altura_div; width:1250px; border:0px solid #000; margin:auto'>";


for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]' AND placa_veiculo LIKE '%$placa_veiculo_busca%' 
AND filial='$filial'"));
$soma_quant_print = number_format($soma_quant_produto[0],0,",",".");

	if ($soma_quant_produto[0] == 0)
	{echo "";}
	else
	{
	echo "
	<div style='height:35px; width:285px; border:0px solid #000; float:left; margin-left:25px'>
	<div class='total' style='height:26px; width:280px; margin-top:6px'>
		<div class='total_nome' style='width:140px; height:15px; border:0px solid #999'><b>$aux_bp_geral[22]</b></div>
		<div class='total_valor' style='width:120px; height:15px; border:0px solid #999'>$soma_quant_print Kg</div>
	</div>
	</div>";
	}

}


echo "</div>";
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('include_relatorio_estoque_entrada.php'); ?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
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