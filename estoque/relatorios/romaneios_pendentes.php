<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "romaneios_pendentes";
$titulo = "Romaneios Pendentes";
$modulo = "estoque";
$menu = "estoque";
// ================================================================================================================


// ====== BUSCA PERMISSÕES DE USUÁRIOS ===========================================================================
include ("../../includes/conecta_bd.php");
$busca_permissao = mysqli_query ($conexao, "SELECT romaneio_pendente FROM usuarios_permissoes WHERE username='$username'");
include ("../../includes/desconecta_bd.php");

$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================


// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);

$numero_romaneio = $_POST["numero_romaneio"];

if ($botao == "BUSCAR" or $botao == "TIRAR_PENDENCIA")
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$pendente_busca = $_POST["pendente_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = "ATIVO";
}

else
{
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$pendente_busca = "SIM";
$filial_busca = $filial_usuario;
$status_busca = "ATIVO";
}
// ================================================================================================================


// ======= TIRAR PENDÊNCIA DO ROMANEIO  ===========================================================================
if ($botao == "TIRAR_PENDENCIA")
{
include ("../../includes/conecta_bd.php");
$tirar_pendencia = mysqli_query ($conexao, "UPDATE estoque SET pendente='N' WHERE numero_romaneio='$numero_romaneio'");
include ("../../includes/desconecta_bd.php");
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
	$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "estoque.data BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if (empty($fornecedor_pesquisa) or $fornecedor_pesquisa == "GERAL")
	{$mysql_fornecedor = "estoque.fornecedor IS NOT NULL";
	$fornecedor_pesquisa = "GERAL";}
else
	{$mysql_fornecedor = "estoque.fornecedor='$fornecedor_pesquisa'";
	$fornecedor_pesquisa = $fornecedor_pesquisa;}


if (empty($cod_produto_busca) or $cod_produto_busca == "GERAL")
	{$mysql_cod_produto = "estoque.cod_produto IS NOT NULL";
	$cod_produto_busca = "GERAL";}
else
	{$mysql_cod_produto = "estoque.cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $cod_produto_busca;}


if (empty($pendente_busca) or $pendente_busca == "GERAL")
	{$mysql_pendente = "(estoque.pendente IS NOT NULL OR estoque.pendente IS NULL)";
	$pendente_busca = "GERAL";}
elseif ($pendente_busca == "SIM")
	{$mysql_pendente = "(estoque.pendente!='N' OR estoque.pendente IS NULL)";
	$pendente_busca = "SIM";}
else
	{$mysql_pendente = "estoque.pendente='N'";
	$pendente_busca = "NAO";}


if (empty($filial_busca))
	{$mysql_filial = "estoque.filial='$filial_usuario'";
	$filial_busca = $filial_usuario;}
elseif ($filial_busca == "GERAL")
	{$mysql_filial = "estoque.filial IS NOT NULL";
	$filial_busca = "GERAL";}
else
	{$mysql_filial = "estoque.filial='$filial_busca'";
	$filial_busca = $filial_busca;}


$mysql_status = "estoque.estado_registro='ATIVO'";

$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";


// FORNECEDOR: 100 (GERAL)
// FORNECEDOR: 491 (GRANCAFÉ - MATRIZ)
// FORNECEDOR: 900 (GRANCAFÉ - FILIAL JAGUARÉ)
// FORNECEDOR: 3137 (GRANCAFÉ - FILIAL SÃO MATEUS)
// FORNECEDOR: 5681 (GRANCAFÉ - FILIAL BAHIA)
// FORNECEDOR: 6856 (GRANCAFÉ - FILIAL MOVELAR)
$mysql_exceto_filiais = "estoque.fornecedor NOT IN (491, 900, 3137, 5681, 6856)";
// ================================================================================================================


// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");
$busca_romaneio = mysqli_query ($conexao, "SELECT estoque.codigo, estoque.numero_romaneio, estoque.fornecedor, estoque.data, estoque.produto, estoque.peso_inicial, estoque.peso_final, estoque.desconto_sacaria, estoque.desconto, estoque.quantidade, estoque.unidade, estoque.tipo_sacaria, estoque.movimentacao, estoque.placa_veiculo, estoque.motorista, estoque.observacao, estoque.usuario_cadastro, estoque.hora_cadastro, estoque.data_cadastro, estoque.usuario_alteracao, estoque.hora_alteracao, estoque.data_alteracao, estoque.filial, estoque.estado_registro, estoque.quantidade_prevista, estoque.quantidade_sacaria, estoque.numero_compra, estoque.motorista_cpf, estoque.num_romaneio_manual, estoque.filial_origem, estoque.quant_volume_sacas, estoque.cod_produto, cadastro_pessoa.nome, select_tipo_sacaria.descricao, select_tipo_sacaria.peso, estoque.pendente FROM estoque, cadastro_pessoa, select_tipo_sacaria WHERE ($mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_pendente AND $mysql_movimentacao AND $mysql_fornecedor AND $mysql_exceto_filiais AND $mysql_cod_produto) AND estoque.fornecedor=cadastro_pessoa.codigo AND estoque.tipo_sacaria=select_tipo_sacaria.codigo ORDER BY estoque.codigo");
include ("../../includes/desconecta_bd.php");

include ("../../includes/conecta_bd.php");
$busca_produto_distinct = mysqli_query ($conexao, "SELECT estoque.cod_produto, cadastro_produto.descricao, cadastro_produto.unidade_print, cadastro_produto.nome_imagem, SUM(estoque.quantidade) FROM estoque, cadastro_produto WHERE ($mysql_filtro_data AND $mysql_filial AND $mysql_status AND $mysql_pendente AND $mysql_movimentacao AND $mysql_fornecedor AND $mysql_exceto_filiais AND $mysql_cod_produto) AND estoque.cod_produto=cadastro_produto.codigo GROUP BY estoque.cod_produto ORDER BY cadastro_produto.codigo");
include ("../../includes/desconecta_bd.php");

$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);
// ================================================================================================================


// ====== MONTA MENSAGEM ==========================================================================================
if(!empty($nome_fornecedor))
{$msg = "Fornecedor: <b>$nome_fornecedor</b>";}
else
{$msg = "Romaneios que ainda faltam entrar na ficha do produtor";}
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
<?php include ("../../includes/submenu_estoque_estoque.php"); ?>
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
    if ($linha_romaneio == 1)
    {echo"$linha_romaneio Romaneio";}
    elseif ($linha_romaneio > 1)
    {echo"$linha_romaneio Romaneios";}
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
    <!-- ========================== -->
    </div>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="pqa" style="width:1400px; height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:20px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/relatorios/romaneios_pendentes.php" method="post" />
<input type="hidden" name="botao" value="BUSCAR" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="nome_fornecedor" value="<?php echo"$nome_fornecedor"; ?>" />
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


 <!-- ======= MOVIMENTAÇÃO =========================================================================================== -->
<div style="width:215px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:210px; height:17px; border:1px solid transparent; float:left">
    Pend&ecirc;ncia:
    </div>
    
    <div style="width:210px; height:25px; float:left; border:1px solid transparent">
    <select name="pendente_busca" class="form_select" style="width:190px" />
    <?php
    if ($pendente_busca == "GERAL")
    {echo "<option selected='selected' value='GERAL'>(TODOS)</option>";}
    else
    {echo "<option value='GERAL'>(TODOS)</option>";}

    if ($pendente_busca == "SIM")
    {echo "<option selected='selected' value='SIM'>Com Pend&ecirc;ncia</option>";}
    else
    {echo "<option value='SIM'>Com Pend&ecirc;ncia</option>";}

    if ($pendente_busca == "NAO")
    {echo "<option selected='selected' value='NAO'>Sem Pend&ecirc;ncia</option>";}
    else
    {echo "<option value='NAO'>Sem Pend&ecirc;ncia</option>";}
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
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>

    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <a href="<?php echo "$servidor/$diretorio_servidor"; ?>/estoque/relatorios/pendentes_selec_fornecedor.php">
	<button type="submit" class="botao_1">Filtrar por Fornecedor</button></a>
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

echo "<div style='height:$altura_div; width:1400px; border:1px solid transparent; margin:auto'>";


for ($sc=1 ; $sc<=$linha_produto_distinct ; $sc++)
{
$aux_bp_distinct = mysqli_fetch_row($busca_produto_distinct);

$cod_produto_t = $aux_bp_distinct[0];
$produto_print_t = $aux_bp_distinct[1];
//$unidade_print_t = $aux_bp_distinct[2];
$unidade_print_t = "KG";
$nome_imagem_produto_t = $aux_bp_distinct[3];
$soma_quantidade_geral = $aux_bp_distinct[4];

if (empty($nome_imagem_produto_t))
{$link_img_produto_t = "";}
else
{$link_img_produto_t = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_t.png' style='width:60px'>";}

$soma_quantidade_print = number_format($soma_quantidade_geral,2,",",".") . " $unidade_print_t";

echo "
<div style='height:50px; width:414px; border:0px solid #000; float:left'>
<div class='total' style='height:40px; width:384px; margin-top:0px' title=''>
	<div class='total_valor' style='width:60px; height:28px; border:0px solid #999; font-size:11px; margin-top:7px'>$link_img_produto_t</div>
	<div class='total_nome' style='width:160px; height:20px; border:0px solid #999; font-size:11px; margin-top:14px'><b>$produto_print_t</b></div>
	<div class='total_valor' style='width:150px; height:20px; border:0px solid #999; font-size:12px; margin-top:14px'>$soma_quantidade_print</div>
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
if ($linha_romaneio == 0)
{echo "
<div style='height:400px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Visualizar</td>
<td width='60px'>Tirar Pend&ecirc;ncia</td>
<td width='100px'>Data</td>
<td width='300px'>Fornecedor</td>
<td width='80px'>N&uacute;mero</td>
<td width='160px'>Produto</td>
<td width='160px'>Quantidade</td>
<td width='120px'>N&deg; Registro Ficha</td>
<td width='160px'>Filial Armaz&eacute;m</td>
<td width='160px'>Filial Origem</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_romaneio[0];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor_w = $aux_romaneio[2];
$data_w = $aux_romaneio[3];
$produto_w = $aux_romaneio[4];
$peso_inicial_w = $aux_romaneio[5];
$peso_final_w = $aux_romaneio[6];
$desconto_sacaria_w = $aux_romaneio[7];
$desconto_w = $aux_romaneio[8];
$quantidade_w = $aux_romaneio[9];
$unidade_w = $aux_romaneio[10];
$tipo_sacaria_w = $aux_romaneio[11];
$movimentacao_w = $aux_romaneio[12];
$placa_veiculo_w = $aux_romaneio[13];
$motorista_w = $aux_romaneio[14];
$observacao_w = $aux_romaneio[15];
$usuario_cadastro_w = $aux_romaneio[16];
$hora_cadastro_w = $aux_romaneio[17];
$data_cadastro_w = $aux_romaneio[18];
$usuario_alteracao_w = $aux_romaneio[19];
$hora_alteracao_w = $aux_romaneio[20];
$data_alteracao_w = $aux_romaneio[21];
$filial_w = $aux_romaneio[22];
$estado_registro_w = $aux_romaneio[23];
$quantidade_prevista_w = $aux_romaneio[24];
$quantidade_sacaria_w = $aux_romaneio[25];
$numero_compra_w = $aux_romaneio[26];
$motorista_cpf_w = $aux_romaneio[27];
$num_romaneio_manual_w = $aux_romaneio[28];
$filial_origem_w = $aux_romaneio[29];
$quant_volume_sacas_w = $aux_romaneio[30];
$cod_produto_w = $aux_romaneio[31];
$fornecedor_print_w = $aux_romaneio[32];
$nome_sacaria_w = $aux_romaneio[33];
$peso_sacaria_w = $aux_romaneio[34];
$pendente_w = $aux_romaneio[35];

$peso_bruto = ($peso_inicial_w - $peso_final_w);

$data_print = date('d/m/Y', strtotime($data_w));
$peso_inicial_print = number_format($peso_inicial_w,0,",",".") . " " . $unidade_w;
$peso_final_print = number_format($peso_final_w,0,",",".") . " " . $unidade_w;
$peso_bruto_print = number_format($peso_bruto,0,",",".") . " " . $unidade_w;
$desconto_sacaria_print = number_format($desconto_sacaria_w,0,",",".") . " " . $unidade_w;
$desconto_print = number_format($desconto_w,0,",",".") . " " . $unidade_w;
$quantidade_print = "<b>" . number_format($quantidade_w,0,",",".") . "</b> " . $unidade_w;
$quantidade_sacaria_print = number_format($quantidade_sacaria_w,0,",",".");


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}
// ======================================================================================================



// ====== BLOQUEIO PARA TIRAR PENDENCIA ===================================================================
if ($permissao[0] == "S" and $pendente_w != "N")
{$permite_tirar_pendencia = "SIM";}
else
{$permite_tirar_pendencia = "NAO";}
// ========================================================================================================


// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "ATIVO")
{
if ($pendente_w != "N")
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_w &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
}

else
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Observa&ccedil;&atilde;o: $observacao_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}


// ====== BOTAO VISUALIZAR ===============================================================================================
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_visualizar.php' method='post' />
<input type='hidden' name='modulo_mae' value='$modulo'>
<input type='hidden' name='menu_mae' value='$menu'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='VISUALIZAR'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
<input type='hidden' name='pendente_busca' value='$pendente_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='hidden' name='status_busca' value='$status_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
// =================================================================================================================


// ====== BOTAO TIRAR PENDENCIA ====================================================================================
if ($permite_tirar_pendencia == "SIM")
{
echo "
<td width='60px' align='center'>
<div style='height:24px; margin-top:0px; border:0px solid #000'>
<form action='$servidor/$diretorio_servidor/estoque/relatorios/romaneios_pendentes.php' method='post' />
<input type='hidden' name='botao' value='TIRAR_PENDENCIA'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='numero_romaneio' value='$numero_romaneio_w'>
<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
<input type='hidden' name='data_final_busca' value='$data_final_br'>
<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
<input type='hidden' name='pendente_busca' value='$pendente_busca'>
<input type='hidden' name='filial_busca' value='$filial_busca'>
<input type='hidden' name='status_busca' value='$status_busca'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='18px' style='margin-top:3px' />
</form>
</div>
</td>";
}

else
{
echo "
<td width='60px' align='center'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='18px' style='margin-top:3px' />
</td>";
}
// =================================================================================================================


// =================================================================================================================
echo "
<td width='100px' align='center'>$data_print</td>
<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='80px' align='center'>$numero_romaneio_w</td>
<td width='160px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$produto_w</div></td>
<td width='160px' align='center'>$quantidade_print</td>
<td width='120px' align='center'>$numero_compra_w</td>
<td width='160px' align='left'><div style='margin-left:7px'>$filial_w</div></td>
<td width='160px' align='left'><div style='margin-left:7px'>$filial_origem_w</div></td>";
// =================================================================================================================

}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_romaneio == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum romaneio encontrado.</i></div>";}
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