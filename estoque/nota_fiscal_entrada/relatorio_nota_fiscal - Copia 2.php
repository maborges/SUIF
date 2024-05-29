<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "relatorio_nota_fiscal";
$titulo = "Notas Fiscais de Entrada";
$modulo = "estoque";
$menu = "relatorios";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$filial = $filial_usuario;

if ($botao == "BUSCAR")
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = "ATIVO";
$seleciona_pessoa = $_POST["seleciona_pessoa"];
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $filial_usuario;
$status_busca = "ATIVO";
$seleciona_pessoa = "ROMANEIO";
}
// ================================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
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

$mysql_filtro_data = "nota_fiscal_entrada.data_emissao BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($fornecedor_pesquisa == "")
	{$mysql_fornecedor = "nota_fiscal_entrada.codigo_fornecedor IS NOT NULL";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}
elseif ($seleciona_pessoa == "ROMANEIO" and $fornecedor_pesquisa != "")
	{$mysql_fornecedor = "nota_fiscal_entrada.codigo_fornecedor_romaneio='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}
elseif ($seleciona_pessoa == "NOTA_FISCAL" and $fornecedor_pesquisa != "")
	{$mysql_fornecedor = "nota_fiscal_entrada.codigo_fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}
else
	{$mysql_fornecedor = "nota_fiscal_entrada.codigo_fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}


if ($cod_produto_busca == "" or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "nota_fiscal_entrada.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "nota_fiscal_entrada.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if ($filial_busca == "")
	{$mysql_filial = "nota_fiscal_entrada.filial='$filial'";
	$filial_busca = "$filial";}
elseif ($filial_busca == "GERAL")
	{$mysql_filial = "nota_fiscal_entrada.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "nota_fiscal_entrada.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


if ($status_busca == "" or $status_busca == "GERAL")
	{$mysql_status = "nota_fiscal_entrada.estado_registro IS NOT NULL";
	$status_busca = "GERAL";}
else
	{$mysql_status = "nota_fiscal_entrada.estado_registro='$status_busca'";
	$status_busca = $status_busca;}
// ================================================================================================================


// ====== ATUALIZA TABELA =========================================================================================
include ("../../includes/conecta_bd.php");

/*
Existe um erro no sistema do Adilson na hora de lançar as notas fiscais de entrada. O Sismeta dele não preenche o campo "codigo_fornecedor_romaneio" na tabela do BD.
O lançamento das NF pelo Suif funcionam normalmente.
Fiz um algoritimo chamado ATUALIZA_TABELA que atualiza o "codigo_fornecedor_romaneio" na tabela assim que gera o relatório.
*/

$at_busca_nf = mysqli_query ($conexao, "
SELECT nota_fiscal_entrada.codigo, estoque.fornecedor, estoque.filial, estoque.cod_produto, cadastro_pessoa.nome FROM nota_fiscal_entrada, estoque, cadastro_pessoa WHERE (nota_fiscal_entrada.filial IS NULL OR nota_fiscal_entrada.cod_produto IS NULL OR nota_fiscal_entrada.codigo_fornecedor_romaneio IS NULL OR nota_fiscal_entrada.fornecedor_romaneio_print IS NULL) AND (nota_fiscal_entrada.data_emissao BETWEEN '$data_inicial_busca' AND '$data_final_busca') AND (nota_fiscal_entrada.codigo_romaneio=estoque.numero_romaneio AND estoque.fornecedor=cadastro_pessoa.codigo) ORDER BY nota_fiscal_entrada.codigo");
$at_linha_nf = mysqli_num_rows ($at_busca_nf);

for ($n=1 ; $n<=$at_linha_nf ; $n++)
{
$at_aux_nf = mysqli_fetch_row($at_busca_nf);
$id_at = $at_aux_nf[0];
$fornecedor_at = $at_aux_nf[1];
$filial_at = $at_aux_nf[2];
$cod_produto_at = $at_aux_nf[3];
$nome_at = $at_aux_nf[4];

$atualiza_cod_forn = mysqli_query ($conexao, "UPDATE nota_fiscal_entrada SET filial='$filial_at', cod_produto='$cod_produto_at', codigo_fornecedor_romaneio='$fornecedor_at', fornecedor_romaneio_print='$nome_at' WHERE codigo='$id_at'");
}
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
$busca_nf = mysqli_query ($conexao, "SELECT nota_fiscal_entrada.codigo, nota_fiscal_entrada.codigo_romaneio, nota_fiscal_entrada.numero_nf, nota_fiscal_entrada.data_emissao, nota_fiscal_entrada.valor_unitario, nota_fiscal_entrada.quantidade, nota_fiscal_entrada.valor_total, nota_fiscal_entrada.observacao, nota_fiscal_entrada.usuario_cadastro, nota_fiscal_entrada.hora_cadastro, nota_fiscal_entrada.data_cadastro, nota_fiscal_entrada.estado_registro, nota_fiscal_entrada.fornecedor_romaneio_print, nota_fiscal_entrada.natureza_operacao, cadastro_produto.descricao, cadastro_produto.unidade_print, cadastro_pessoa.nome FROM nota_fiscal_entrada, cadastro_produto, cadastro_pessoa WHERE ($mysql_filtro_data AND $mysql_fornecedor AND $mysql_status AND $mysql_cod_produto AND $mysql_filial) AND (nota_fiscal_entrada.cod_produto=cadastro_produto.codigo AND nota_fiscal_entrada.codigo_fornecedor=cadastro_pessoa.codigo) ORDER BY nota_fiscal_entrada.codigo");
$linha_nf = mysqli_num_rows ($busca_nf);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT nota_fiscal_entrada.cod_produto, cadastro_produto.descricao, cadastro_produto.unidade_print, cadastro_produto.nome_imagem FROM nota_fiscal_entrada, cadastro_produto WHERE ($mysql_filtro_data AND $mysql_fornecedor AND $mysql_status AND $mysql_cod_produto AND $mysql_filial) AND nota_fiscal_entrada.cod_produto=cadastro_produto.codigo ORDER BY cadastro_produto.codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
// ================================================================================================================


include ("../../includes/desconecta_bd.php");
// ================================================================================================================



// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
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
<?php include ('../../includes/javascript.php'); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

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
<?php include ("../../includes/submenu_estoque_relatorios.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo"$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_nf == 1)
    {echo"$linha_nf Nota Fiscal";}
    elseif ($linha_nf > 1)
    {echo"$linha_nf Notas Fiscais";}
    else
    {echo"";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<div class="link_4">
	<!-- ========================= -->
    </div>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa" style="width:1350px; height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:20px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_entrada/relatorio_nota_fiscal.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
</div>
<!-- ================================================================================================================ -->

 <!-- ======= DATA INICIAL ========================================================================================== -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Inicial:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_inicial_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_inicial_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= DATA FINAL ============================================================================================ -->
<div style="width:135px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:130px; height:17px; border:1px solid transparent; float:left">
    Data Final:
    </div>

    <div style="width:130px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="data_final_busca" class="form_input" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:100px; text-align:left; padding-left:5px" value="<?php echo"$data_final_br"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= STATUS REGISTRO ======================================================================================= -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:198px; height:17px; border:1px solid transparent; float:left">
    Tipo de Pesquisa:
    </div>
    
    <div style="width:198px; height:25px; float:left; border:1px solid transparent">
    <select name="seleciona_pessoa" class="form_select" style="width:170px" />
    <?php
	if ($seleciona_pessoa == "NOTA_FISCAL")
	{echo "<option value='NOTA_FISCAL' selected='selected'>Produtor (Nota Fiscal)</option>";}
	else
	{echo "<option value='NOTA_FISCAL'>Produtor (Nota Fiscal)</option>";}

	if ($seleciona_pessoa == "ROMANEIO")
	{echo "<option value='ROMANEIO' selected='selected'>Fornecedor (Romaneio)</option>";}
	else
	{echo "<option value='ROMANEIO'>Fornecedor (Romaneio)</option>";}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= PRODUTO =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_busca" class="form_select" style="width:190px" />
    <?php
	include ("../../includes/cadastro_produto.php"); 

	if ($cod_produto_busca == "GERAL")
	{echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
	else
	{echo "<option value='GERAL'>(TODOS)</option>";}
	
	for ($i=0 ; $i<=count($cadastro_produto) ; $i++)
	{
        if ($cadastro_produto[$i]["codigo"] == $cod_produto_busca)
        {echo "<option selected='selected' value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
        else
        {echo "<option value='" . $cadastro_produto[$i]["codigo"] . "'>" . $cadastro_produto[$i]["descricao"] . "</option>";}
	}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


 <!-- ======= FILIAL =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="filial_busca" class="form_select" style="width:190px" />
    <?php
	include ("../../includes/filiais.php"); 
	
	if ($filial_busca == "GERAL")
	{echo "<option selected='selected' value='GERAL'>(TODAS)</option>";}
	else
	{echo "<option value='GERAL'>(TODAS)</option>";}
	
	for ($f=0 ; $f<=count($filiais) ; $f++)
	{
        if ($filiais[$f]["descricao"] == $filial_busca)
        {echo "<option selected='selected' value='" . $filiais[$f]["descricao"] . "'>" . $filiais[$f]["apelido"] . "</option>";}
        else
        {echo "<option value='" . $filiais[$f]["descricao"] . "'>" . $filiais[$f]["apelido"] . "</option>";}
	}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:right">
    <div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>

    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <a href="<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_entrada/nf_selec_fornecedor.php">
	<button type="submit" class="botao_1">Pesquisar por Fornecedor</button></a>
    </div>
</div>
<!-- ================================================================================================================ -->


	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
$numero_divs = ceil($linha_produto_distinct / 3);
$altura_div = ($numero_divs * 50) . "px";

echo "<div style='height:$altura_div; width:1350px; border:1px solid transparent; margin:auto'>";


for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

$cod_produto_t = $aux_bp_distinct[0];
$produto_print_t = $aux_bp_distinct[1];
$unidade_print_t = $aux_bp_distinct[2];
$nome_imagem_produto_t = $aux_bp_distinct[3];

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}

// ===========================================================================================================
include ("../../includes/conecta_bd.php");

$soma_valor_geral = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_entrada WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_status AND $mysql_cod_produto AND $mysql_filial AND cod_produto='$cod_produto_t'"));

$soma_quant_geral = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM nota_fiscal_entrada WHERE $mysql_filtro_data AND $mysql_fornecedor AND $mysql_status AND $mysql_cod_produto AND $mysql_filial AND cod_produto='$cod_produto_t'"));

include ("../../includes/desconecta_bd.php");
// ===========================================================================================================

$soma_valor_print = "R$ " . number_format($soma_valor_geral[0],2,",",".");
$soma_quant_print = number_format($soma_quant_geral[0],2,",","."). " $unidade_print_t";

echo "
<div style='height:42px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:15px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px'>$soma_valor_print</div>
	<div class='total_valor' style='width:150px; height:15px; border:0px solid #999; font-size:11px; margin-top:3px'>$soma_quant_print</div>
</div>
</div>";

}

echo "</div>";

?><!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php
if ($linha_nf == 0)
{echo "
<div style='height:400px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Visualizar</td>
<td width='90px'>Data Emiss&atilde;o</td>
<td width='270px'>Fornecedor (Romaneio)</td>
<td width='270px'>Produtor (Nota Fiscal)</td>
<td width='80px'>N&ordm; NF</td>
<td width='90px'>Natureza Op.</td>
<td width='70px'>Romaneio</td>
<td width='150px'>Produto</td>
<td width='100px'>Quantidade</td>
<td width='90px'>Valor Unit&aacute;rio</td>
<td width='130px'>Valor Total</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_nf ; $x++)
{
$aux_nf = mysqli_fetch_row($busca_nf);


// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_nf[0];
$numero_romaneio_w = $aux_nf[1];
$numero_nf_w = $aux_nf[2];
$data_emissao_w = $aux_nf[3];
$data_emissao_print = date('d/m/Y', strtotime($aux_nf[3]));
$valor_un_w = $aux_nf[4];
$valor_un_print = "R$ " . number_format($aux_nf[4],2,",",".");
$quantidade_w = number_format($aux_nf[5],0,",",".");
$valor_total_w = $aux_nf[6];
$valor_total_print = "R$ " . number_format($aux_nf[6],2,",",".");
$observacao_w = $aux_nf[7];
$usuario_cadastro_w = $aux_nf[8];
$hora_cadastro_w = $aux_nf[9];
$data_cadastro_w = $aux_nf[10];
$estado_registro_w = $aux_nf[11];
$nome_pessoa_rom = $aux_nf[12];
$natureza_operacao = $aux_nf[13];
$produto_print = $aux_nf[14];
$unidade_w = $aux_nf[15];
$nome_pessoa_nf = $aux_nf[16];


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w'>";}

else
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w'>";}


// ====== BOTAO VISUALIZAR ===============================================================================================
echo "
<td width='60px' align='center'>
<div style='height:22px; margin-top:2px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_visualizar.php' method='post'>
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='nota_fiscal_entrada'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='VISUALIZAR'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='hidden' name='status_busca' value='$status_busca'>
<input type='hidden' name='seleciona_pessoa' value='$seleciona_pessoa'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' />
</form>
</div>
</td>";
// =================================================================================================================

echo "
<td width='90px' align='center'>$data_emissao_print</td>
<td width='270px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_pessoa_rom</div></td>
<td width='270px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_pessoa_nf</div></td>
<td width='80px' align='center' style='font-size:10px'>$numero_nf_w</td>
<td width='90px' align='center' style='font-size:10px'>$natureza_operacao</td>
<td width='70px' align='center' style='font-size:10px'>$numero_romaneio_w</td>
<td width='150px' align='left'><div style='height:14px; margin-left:7px'>$produto_print</div></td>
<td width='100px' align='center'>$quantidade_w $unidade_w</td>
<td width='90px' align='right'><div style='height:14px; margin-right:7px'>$valor_un_print</div></td>
<td width='130px' align='right'><div style='height:14px; margin-right:7px'><b>$valor_total_print</b></div></td>";



}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_nf == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma nota fiscal encontrada.</i></div>";}
// =================================================================================================================
?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>