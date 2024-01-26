<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'nota_fiscal';
$titulo = 'Estoque - Nota Fiscal de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'saida';
// ========================================================================================================


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


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ================================================================================================================


// ====== CONVERTE PESO ==========================================================================================
function ConvertePeso($peso){
	$peso_1 = str_replace(".", "", $peso);
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao_relatorio = $_POST["botao_relatorio"];
$botao = $_POST["botao"];
$botao_mae = $_POST["botao_mae"];
$numero_romaneio = $_POST["numero_romaneio"];
$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$filial = $filial_usuario;

$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
if ($pagina_mae == "cadastro_4_enviar" or $pagina_mae == "editar_4_enviar")
{$pagina_mae = "entrada_relatorio_produto";}
else
{$pagina_mae = $pagina_mae;}

$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];

$filial_nf = $filial_usuario;
$codigo_nf = $_POST["codigo_nf"];
$fornecedor_nf = $_POST["fornecedor"];
$data_emissao_nf = ConverteData($_POST["data_emissao"]);
$numero_nf = $_POST["numero_nf"];
$unidade_nf = $_POST["unidade"];
$quantidade_nf = ConvertePeso($_POST["quantidade"]);
$valor_unitario_nf = ConverteValor($_POST["valor_unitario"]);
$valor_un_nf = $_POST["valor_unitario"];
$observacao_nf = $_POST["observacao"];

$valor_total_nf = ($quantidade_nf * $valor_unitario_nf);

$usuario_cadastro_nf = $nome_usuario_print;
$hora_cadastro_nf = date('G:i:s', time());
$data_cadastro_nf = date('Y/m/d', time());
$usuario_alteracao_nf = $nome_usuario_print;
$hora_alteracao_nf = date('G:i:s', time());
$data_alteracao_nf = date('Y/m/d', time());
$motivo_exclusao_nf = $_POST["motivo_exclusao_nf"];
// ================================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

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
$peso_bruto = ($peso_final - $peso_inicial);
$peso_bruto_print = number_format($peso_bruto,0,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],0,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],0,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],0,",",".");
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
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
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$usuario_cadastro = $aux_romaneio[19];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
$usuario_alteracao = $aux_romaneio[22];
if ($aux_romaneio[24] == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}
// ================================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

for ($s=1 ; $s<=$linha_sacaria ; $s++)
{
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
}
$tipo_sacaria = $aux_sacaria[1];
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$quant_kg_saca = $aux_bp[27];
$nome_imagem_produto = $aux_bp[28];
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
// =======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[2];
// ======================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
$quantidade_real = ($quantidade_nf / $quant_kg_saca);
$quantidade_real_print = number_format($quantidade_real,2,",",".");
// ================================================================================================================


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

if ($linhas_fornecedor == 0)
{$cidade_uf_fornecedor = "";}
else
{$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";}
// ======================================================================================================


// ====== BUSCA PESSOA NOTA FISCAL ======================================================================
$busca_pessoa_nf = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_nf' AND estado_registro!='EXCLUIDO'");
$aux_pessoa_nf = mysqli_fetch_row($busca_pessoa_nf);
$linhas_pessoa_nf = mysqli_num_rows ($busca_pessoa_nf);

$fornecedor_print_nf = $aux_pessoa_nf[1];
$codigo_pessoa_nf = $aux_pessoa_nf[35];
$cidade_fornecedor_nf = $aux_pessoa_nf[10];
$estado_fornecedor_nf = $aux_pessoa_nf[12];
$telefone_fornecedor_nf = $aux_pessoa_nf[14];
if ($aux_pessoa_nf[2] == "pf")
{$cpf_cnpj_nf = $aux_pessoa_nf[3];}
else
{$cpf_cnpj_nf = $aux_pessoa_nf[4];}
// ======================================================================================================


// ====== INCLUIR NOTA FISCAL ============================================================================
if ($botao == "INCLUIR_NF")
{
	if ($fornecedor_nf == "")
	{$erro = 1;
	$msg_erro = "* Selecione o produtor da nota fiscal";}

	elseif ($linhas_pessoa_nf == 0)
	{$erro = 2;
	$msg_erro = "* Produtor da nota fiscal inv&aacute;lido";}

	elseif ($data_emissao_nf == "")
	{$erro = 3;
	$msg_erro = "* Informe a data de emiss&atilde;o da nota fiscal";}

	elseif ($numero_nf == "")
	{$erro = 4;
	$msg_erro = "* Informe o n&uacute;mero da nota fiscal";}

	elseif ($unidade_nf == "")
	{$erro = 5;
	$msg_erro = "* Selecione a unidade de medida";}

	elseif ($quantidade_nf == "" or $quantidade_nf <= 0)
	{$erro = 6;
	$msg_erro = "* Informe a quantidade";}

	elseif (!is_numeric($quantidade_nf))
	{$erro = 7;
	$msg_erro = "* Quantidade inv&aacute;lida";}
	
	elseif ($valor_unitario_nf == '' or $valor_unitario_nf <= 0)
	{$erro = 8;
	$msg_erro = "* Informe o valor unit&aacute;rio";}

	else
	{
	$inserir = mysqli_query ($conexao, "INSERT INTO nota_fiscal_saida (codigo, codigo_romaneio, codigo_fornecedor, numero_nf, data_emissao, valor_unitario, unidade, quantidade, valor_total, observacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, fornecedor_print, cod_produto, codigo_fornecedor_romaneio, fornecedor_romaneio_print) VALUES (NULL, '$numero_romaneio', '$fornecedor_nf', '$numero_nf',	'$data_emissao_nf', '$valor_unitario_nf', '$unidade_nf', '$quantidade_nf', '$valor_total_nf', '$observacao_nf', '$usuario_cadastro_nf', '$hora_cadastro_nf', '$data_cadastro_nf', 'ATIVO', '$filial_nf', '$fornecedor_print_nf', '$cod_produto', '$fornecedor', '$fornecedor_print')");

	$fornecedor_nf = "";
	$numero_nf = "";
	$quantidade_nf = "";
	$valor_un_nf = "";
	$observacao_nf = "";
	}

}
// ======================================================================================================


// ====== EXCLUIR NOTA FISCAL ============================================================================
elseif ($botao == "EXCLUIR_NF")
{
$excluir_nf = mysqli_query ($conexao, "UPDATE nota_fiscal_saida SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_alteracao_nf', hora_exclusao='$hora_alteracao_nf', data_exclusao='$data_alteracao_nf', motivo_exclusao='$motivo_exclusao_nf' WHERE codigo='$codigo_nf'");
	
//$delete = mysqli_query ($conexao, "DELETE FROM nota_fiscal_saida WHERE codigo='$codigo_nf'");
}
// ======================================================================================================


// ======================================================================================================
else
{
$fornecedor_nf = "";
}
// ======================================================================================================


// ====== SOMA NOTAS FISCAIS ======================================================================
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio'"));
$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0],2,",",".");

$soma_quantidade_nf = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio'"));
$soma_quantidade_nf_print = number_format($soma_quantidade_nf[0],0,",",".") . " $un_descricao";
// ======================================================================================================


// ======================================================================================================
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
<?php include ('../../includes/sub_menu_estoque_entrada.php'); ?>
</div>




<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_25"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Romaneio de Sa&iacute;da
    </div>

	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    N&ordm; <?php echo"$numero_romaneio"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
    <!-- Romaneio de Sa&iacute;da -->
	</div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
	<?php echo"$data_print"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:220px; width:1080px; border:0px solid #0000FF; margin:auto">


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
    <div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
    Cliente:
    </div>
</div>

<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>
    
	<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div>
	</div>
    
	<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div>
	</div>

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

    <div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
        <div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cidade_uf_fornecedor</b>" ?></div>
    </div>
    
    <div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
		<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div>
	</div>

</div>
<!-- ======================================================================================================================= -->


<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
    Produto:
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
    <!-- xxxxxxxxxxxxxx: -->
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
	<!-- xxxxxxxxxxxxxx: -->
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>
-->
<!-- ======================================================================================================================= -->


<!-- =============================================== DADOS DO ROMANEIO ============================================================================= -->
		<div id="centro" style="width:1055px; height:60px; border:0px solid #999; color:#003466; overflow:hidden; margin-left:25px">

			<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:0px">
			<div class="form_rotulo" style="margin-top:5px">Peso Inicial:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Peso Final:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Peso Bruto:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Desconto Sacaria:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:21px">
			<div class="form_rotulo" style="margin-top:5px">Outros Descontos:</div></div>
			<div style="width:153px; height:20px; border:1px solid #FFF; border-radius:5px; float:left; text-align:left; margin-left:20px">
			<div class="form_rotulo" style="margin-top:5px">Peso L&iacute;quido:</div></div>

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:0px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:21px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
			<div style="width:153px; height:25px; border:1px solid #999; border-radius:0px; float:left; font-size:12px; text-align:center; margin-left:20px; background-color:#EEE">
			<div style="margin-top:5px"><?php echo"<b>$quantidade_print</b> Kg" ?></div></div>

		</div>





<!-- ============================================================================================================== -->
</div>


<!-- ============================================================================================================== -->
<div class="espacamento_5"></div>


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left; color:#F00">
    <?php echo "$msg_erro"; ?>
	</div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
	<!-- xxxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:490px; float:left; text-align:left; border:0px solid #000; color:#00F">
    Cadastro de Nota Fiscal de Sa&iacute;da
    </div>

	<div class="ct_titulo_2" style="width:300px; float:left; text-align:right; border:0px solid #000; color:#444">
    Quantidade <?php echo"$soma_quantidade_nf_print"; ?>
    </div>

	<div class="ct_titulo_2" style="width:300px; float:right; text-align:right; border:0px solid #000; color:#444">
    Total R$ <?php echo"$soma_nota_fiscal_print"; ?>
    </div>

	<form name="popup" action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_saida/nota_fiscal.php" method="post">
    <input type="hidden" name="numero_romaneio" value="<?php echo"$numero_romaneio"; ?>" />
    <input type="hidden" name="botao" value="INCLUIR_NF" />
	<input type="hidden" name="botao_mae" value="<?php echo"$botao_mae"; ?>" />
    <input type="hidden" name="botao_relatorio" value="<?php echo"$botao_relatorio"; ?>" />
    <input type='hidden' name='pagina_mae' value='<?php echo"$pagina_mae"; ?>'>
    <input type='hidden' name='pagina_filha' value='<?php echo"$pagina_filha"; ?>'>
    <input type='hidden' name='data_inicial_busca' value='<?php echo"$data_inicial_busca"; ?>'>
    <input type='hidden' name='data_final_busca' value='<?php echo"$data_final_busca"; ?>'>
    <input type='hidden' name='cod_produto_busca' value='<?php echo"$cod_produto_busca"; ?>'>
    <input type='hidden' name='fornecedor_busca' value='<?php echo"$fornecedor_busca"; ?>'>
    <input type='hidden' name='situacao_romaneio_busca' value='<?php echo"$situacao_romaneio_busca"; ?>'>
    <input type='hidden' name='numero_romaneio_busca' value='<?php echo"$numero_romaneio_busca"; ?>'>
    <input type='hidden' name='forma_pesagem_busca' value='<?php echo"$forma_pesagem_busca"; ?>'>
    
    <script type="text/javascript">
	function abrir(programa,janela)
	{
		if(janela=="") janela = "janela";
		window.open(programa,janela,'height=370,width=900');
	}

	</script>

	<script type="text/javascript" src="fornecedor_funcao.js"></script>

	<script type="text/javascript">
    document.onkeyup=function(e)
    {
    if(e.which == 113)
    {
        //Pressionou F2, aqui vai a função para esta tecla.
        //alert(tecla F2);
        var aux_f2 = document.popup.fornecedor.value;
        javascript:foco('busca');
        javascript:abrir('busca_pessoa_popup.php');
        //javascript:buscarNoticias(aux_f2);
    }
    }
    </script>

</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
	<div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">
        <div class="link_2" onclick="javascript:abrir('busca_pessoa_popup.php'); javascript:foco('busca');" title="Buscar Produtor"
        style="width:auto; height:auto; float:left" />
        Produtor (F2):</div>
	</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Produto:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Data de Emiss&atilde;o da NF:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">N&uacute;mero da NF:</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:99px; height:auto; float:left; border:1px solid transparent">
    <input id="busca" type="text" class="form_input" name="fornecedor" onClick="buscarNoticias(this.value)" onBlur="buscarNoticias(this.value)" 
    onkeydown="if (getKey(event) == 13) return false; " style="width:75px; text-align:center" value="<?php echo"$fornecedor_nf"; ?>" />
    </div>

	<div style="width:425px; height:auto; float:left; border:1px solid transparent">
    <div class="form_input" style="width:403px; height:24px;">
	<div id="resultado" style="width:390px; height:16px; overflow:hidden; margin-left:7px; margin-top:3px"></div>
    </div>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="produto_disabled" onkeydown="if (getKey(event) == 13) return false;" 
    style="width:144px; text-align:left; color:#999; padding-left:7px" value="<?php echo"$produto_print"; ?>" disabled="disabled" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="data_emissao" maxlength="10" onkeypress="mascara(this,data)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo date('d/m/Y')?>" id="calendario" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="numero_nf" maxlength="15" onkeydown="if (getKey(event) == 13) return false;" 
    onBlur="alteraMaiusculo(this)" onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:left" value="<?php echo"$numero_nf"; ?>" />
    </div>
 
</div>
<!-- ============================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ============================================================================================================= -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Quantidade:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Unidade:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Valor Unit&aacute;rio:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Observa&ccedil;&atilde;o:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="quantidade" maxlength="11" onkeypress="mascara(this,m_quantidade)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$quantidade_nf"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="unidade" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
    <option value="SC">SC</option>
    <option value="KG">KG</option>
    <option value="CX">CX</option>
    <option value="UN">UN</option>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="valor_unitario" maxlength="15" onkeypress="mascara(this,mvalor)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$valor_un_nf"; ?>" />
    </div>

	<div style="width:351px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="observacao" maxlength="100" onkeypress="troca(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:322px; text-align:left; padding-left:5px" value="<?php echo"$observacao_nf"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <button type='submit' class='botao_1' style="width:152px">Salvar</button>
    </form>
    </div>
 
</div>
<!-- ============================================================================================================= -->



<div class="form" style="height:30px; border:1px solid transparent"></div>




<!-- ================== INICIO DO RELATORIO ================= -->
<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:0px;">

<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
<?php
$busca_nota_fiscal = mysqli_query ($conexao, "SELECT * FROM nota_fiscal_saida WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio' ORDER BY data_emissao");
$linha_nota_fiscal = mysqli_num_rows ($busca_nota_fiscal);


if ($linha_nota_fiscal == 0)
{echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem notas fiscais para este romaneio.</i></div>";}
else
{echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='90px' height='20px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='380px' align='center' bgcolor='#006699'>Produtor</td>
<td width='120px' align='center' bgcolor='#006699'>N&ordm; Nota Fiscal</td>
<td width='122px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='60px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>
</div>";}

echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table class='tabela_geral'>";

for ($w=1 ; $w<=$linha_nota_fiscal ; $w++)
{
	$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

// DADOS DO FAVORECIDO =========================
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
	$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
	$codigo_pessoa_2 = $aux_busca_favorecido_2[35];
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_busca_pessoa_2[1];
	$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
		if ($tipo_pessoa_2 == "pf")
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[3];}
		else
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[4];}

	$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));		
	$numero_nf_print_2 = $aux_nota_fiscal[3];
	$valor_unitario_print_2 = number_format($aux_nota_fiscal[5],2,",",".");
	$valor_total_print_2 = number_format($aux_nota_fiscal[8],2,",",".");
	$unidade_print_2 = $aux_nota_fiscal[6];
	$quantidade_print_2 = number_format($aux_nota_fiscal[7],0,",",".");
	$observacao_print_2 = $aux_nota_fiscal[9];
	

// RELATORIO =========================
	echo "
	<tr class='tabela_1' title=' CPF/CNPJ: $cpf_cnpj_2 &#13; Observa&ccedil;&atilde;o: $observacao_print_2'>
	<td width='90px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$data_nf_print_2</div></td>
	<td width='380px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_favorecido_2</div></td>
	<td width='120px' align='center'><div style='height:14px; overflow:hidden'>$numero_nf_print_2</div></td>
	<td width='122px' align='center'><div style='height:14px; overflow:hidden'>$quantidade_print_2 $unidade_print_2</div></td>
	<td width='122px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$valor_unitario_print_2</div></td>
	<td width='122px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$valor_total_print_2</div></td>
	<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_saida/nota_fiscal.php' method='post'>
		<input type='hidden' name='botao' value='EXCLUIR_NF' />
		<input type='hidden' name='botao_mae' value='BUSCAR'>
		<input type='hidden' name='botao_relatorio' value='$botao_relatorio'>
		<input type='hidden' name='codigo_nf' value='$aux_nota_fiscal[0]' />
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
		<input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_excluir.png' height='20px' border='0' />
		</form>
	</td>						
	</tr>";
}
echo "
</table>
</div>
<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->


<div class="espacamento_15"></div>

<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>
	<?php
	echo "
		<div id='centro' style='float:left; height:55px; width:200px; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/$pagina_mae.php' method='post'>
		<input type='hidden' name='botao' value='$botao_relatorio'>
		<input type='hidden' name='botao_mae' value='$botao_mae'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
		<input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
		</form>
		</div>";
	
		
	echo "
		<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Romaneio</button>
		</form>
		</div>";
	?>
</div>




<div class="espacamento_30"></div>

<div id="centro" style="width:1050px; height:25px; border:1px solid #999; color:#003466; overflow:hidden; margin:auto; background-color:#EEE">
    <div style="margin-left:20px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo"<i>$dados_cadastro</i>" ?></div>
    <div style="margin-left:40px; margin-top:5px; color:#999; font-size:11px; float:left"><?php echo"<i>$dados_alteracao</i>" ?></div>
</div>

<!-- 
============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->





</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>