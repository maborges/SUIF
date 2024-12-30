<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");
$pagina = 'relatorio_romaneios_excluidos';
$titulo = 'Relat&oacute;rio de Romaneios Exclu&iacute;dos';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$filial = $filial_usuario;

$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$numero_romaneio_w = $_POST["numero_romaneio_w"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());


if ($botao == "BUSCAR")
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);}
else
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}


$mysql_filtro_data = "data_exclusao BETWEEN '$data_inicial_busca' AND '$data_final_busca'";


if ($situacao_romaneio_busca == "" or $situacao_romaneio_busca == "GERAL")
	{$mysql_situacao_romaneio = "situacao_romaneio IS NOT NULL";
	$situacao_romaneio_busca = "GERAL";}
else
	{$mysql_situacao_romaneio = "situacao_romaneio='$situacao_romaneio_busca'";
	$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];}


if ($forma_pesagem_busca == "" or $forma_pesagem_busca == "GERAL")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao='ENTRADA_DIRETA')";
	$forma_pesagem_busca = "GERAL";}
elseif ($forma_pesagem_busca == "BALANCA")
	{$mysql_forma_pesagem = "(situacao IS NULL OR situacao='' OR situacao!='ENTRADA_DIRETA')";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}
else
	{$mysql_forma_pesagem = "situacao='ENTRADA_DIRETA'";
	$forma_pesagem_busca = $_POST["forma_pesagem_busca"];}


if ($fornecedor_busca == "" or $fornecedor_busca == "GERAL")
	{$mysql_fornecedor = "(fornecedor IS NOT NULL OR fornecedor IS NULL OR fornecedor='')";
	$fornecedor_busca = "GERAL";}
else
	{$mysql_fornecedor = "fornecedor='$fornecedor_busca'";
	$fornecedor_busca = $_POST["fornecedor_busca"];}


if ($cod_produto_busca == "" or $cod_produto_busca == "TODOS")
	{$mysql_cod_produto = "(cod_produto IS NOT NULL OR cod_produto IS NULL OR cod_produto='')";
	$cod_produto_busca = "TODOS";}
else
	{$mysql_cod_produto = "cod_produto='$cod_produto_busca'";
	$cod_produto_busca = $_POST["cod_produto_busca"];}
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque WHERE estado_registro='EXCLUIDO' AND movimentacao='ENTRADA' 
AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial' ORDER BY codigo");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_fornecedor AND $mysql_cod_produto AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
// ================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ====== BUSCA PRODUTO FORM ==============================================================================
$busca_prod = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_busca' AND estado_registro='EXCLUIDO'");
$aux_prod = mysqli_fetch_row($busca_prod);
$linhas_prod = mysqli_num_rows ($busca_prod);

$prod_print = $aux_prod[1];
// ======================================================================================================


// ====== BUSCA FORNECEDOR ===================================================================================
$busca_forne = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_busca' AND estado_registro='EXCLUIDO'");
$aux_forne = mysqli_fetch_row($busca_forne);
$linhas_forne = mysqli_num_rows ($busca_forne);

if ($fornecedor_busca == "")
{$forne_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_forne == 0)
{$forne_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$forne_print = $aux_forne[1];}
// =================================================================================================================


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
// ==================================================================================================================


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
<?php include ('../../includes/submenu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Relat&oacute;rio de Romaneios Exclu&iacute;dos
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorio_romaneios_excluidos.php" method="post" />
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

<!--
    <div class="pqa_rotulo" style="height:20px; width:135px; border:0px solid #000">Forma de Pesagem:</div>

	<div style="height:34px; width:105px; border:0px solid #999; float:left">
    <select class="pqa_select" name="forma_pesagem_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:100px" />
    <?php
	/*
	if ($forma_pesagem_busca == "GERAL")
	{echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
	else
	{echo "<option value='GERAL'>(GERAL)</option>";}

	if ($forma_pesagem_busca == "BALANCA")
	{echo "<option value='BALANCA' selected='selected'>Balan&ccedil;a</option>";}
	else
	{echo "<option value='BALANCA'>Balan&ccedil;a</option>";}

	if ($forma_pesagem_busca == "SAIDA_DIRETA")
	{echo "<option value='SAIDA_DIRETA' selected='selected'>Sa&iacute;da Direta</option>";}
	else
	{echo "<option value='SAIDA_DIRETA'>Sa&iacute;da Direta</option>";}
	*/
    ?>
    </select>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:150px; border:0px solid #000">Situa&ccedil;&atilde;o do Romaneio:</div>

	<div style="height:34px; width:95px; border:0px solid #999; float:left">
    <select class="pqa_select" name="situacao_romaneio_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:85px" />
    <?php
	/*
    if ($situacao_romaneio_busca == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
    else
    {echo "<option value='GERAL'>(GERAL)</option>";}

    if ($situacao_romaneio_busca == "EM_ABERTO")
    {echo "<option value='EM_ABERTO' selected='selected'>Em Aberto</option>";}
    else
    {echo "<option value='EM_ABERTO'>Em Aberto</option>";}

    if ($situacao_romaneio_busca == "FECHADO")
    {echo "<option value='FECHADO' selected='selected'>Fechado</option>";}
    else
    {echo "<option value='FECHADO'>Fechado</option>";}
	*/
    ?>
    </select>
	</div>
-->


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
	echo "<a href='$servidor/$diretorio_servidor/estoque/entrada/excluir_romaneio_1.php'><button type='submit' class='botao_1'>Excluir Romaneio</button></a>";	
	if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/relatorio_romaneios_excluidos_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
		<?php 
        if ($linha_romaneio == 0)
        {}
        elseif ($linha_romaneio == 1)
        {echo"$linha_romaneio Romaneio exclu&iacute;do";}
        else
        {echo"$linha_romaneio Romaneios exclu&iacute;dos";}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->




<!-- ====================================================================================== -->
<?php
// ======================================================================================================
if ($linha_romaneio == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_romaneio == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='300px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='125px' align='center' bgcolor='#006699'>Produto</td>
<td width='99px' align='center' bgcolor='#006699'>Peso L&iacute;quido</td>
<td width='80px' align='center' bgcolor='#006699'>Status</td>
<td width='160px' align='center' bgcolor='#006699'>Cadastrado por</td>
<td width='160px' align='center' bgcolor='#006699'>Exclu&iacute;do por</td>
<td width='100px' align='center' bgcolor='#006699'>Data Exclus&atilde;o</td>
</tr>
</table>";}

echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO ROMANEIO ============================================================================
$num_romaneio_print = $aux_romaneio[1];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],0,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],0,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,0,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],0,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],0,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],0,",",".");
$unidade = $aux_romaneio[11];
$unidade_print = "Kg";
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],0,",",".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];

$usuario_cadastro = $aux_romaneio[19];
if ($usuario_alteracao == "")
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";

$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}

$usuario_exclusao = $aux_romaneio[40];
if ($usuario_exclusao == "")
{
$dados_exclusao = "";
$motivo_exclusao = $aux_romaneio[43];
$data_exclusao = "";
$hora_exclusao = "";
$dados_exclusao = "Exclu&iacute;do por:";
}
else
{
$usuario_exclusao = $aux_romaneio[40];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$hora_exclusao = $aux_romaneio[41];
$motivo_exclusao = $aux_romaneio[43];
$dados_exclusao = "Exclu&iacute;do por: $usuario_exclusao $data_exclusao $hora_exclusao";
}
// ======================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descrisao_sacaria = "(Sem sacaria)";}
else
{$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
if ($produto == "CAFE")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CAFE_ARABICA")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "PIMENTA")
{$quantidade_real = ($quantidade / 50);}
elseif ($produto == "CACAU")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "CRAVO")
{$quantidade_real = ($quantidade / 60);}
elseif ($produto == "RESIDUO_CACAU")
{$quantidade_real = ($quantidade / 60);}
else
{$quantidade_real = 0;}

$quantidade_real_print = number_format($quantidade_real,2,",",".");
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


// ====== SITUAÇÃO PRINT ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($situacao_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($situacao_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
echo "<tr class='tabela_5' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao&#013; Exclu&iacute;do por: $usuario_exclusao $data_exclusao  $hora_exclusao&#013; Motivo da Exclus&atilde;o: $motivo_exclusao&#013;'>";
	
	echo "
	<td width='70px' align='center'>$data_print</td>";
	if ($situacao == "ENTRADA_DIRETA")
	{echo "<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>";}
	else
	{echo "<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>";}	
	echo "
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='125px' align='center'>$produto_print</td>
	<td width='99px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'><b>$quantidade_print</b> $unidade_print</div></td>
	<td width='80px' align='center'>$situacao_print</td>
	<td width='160px' align='center'>$usuario_cadastro</td>
	<td width='160px' align='center'>$usuario_exclusao</td>
	<td width='100px' align='center'>$data_exclusao</td>";

	echo "</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_romaneio == 0 and $botao == "BUSCAR")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum romaneio encontrado.</i></div>";}
else
{}
// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================

?>
<!-- ====================================================================================== -->




<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->




</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>