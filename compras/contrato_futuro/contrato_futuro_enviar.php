<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'contrato_futuro_enviar';
$menu = 'contratos';
$titulo = 'Contrato Futuro';
$modulo = 'compras';
	
	
// ====== CONTADOR NÚMERO COMPRA ==========================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ========================================================================================================

	
// ============================================== CONVERTE DATA ====================================================	
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data){

	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================


// ======= RECEBENDO POST =================================================================================
$filial = $filial_usuario;
$numero_contrato = $_POST["numero_contrato"];
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$data_contrato = ConverteData($_POST["data_contrato"]);
$data_contrato_2 = $_POST["data_contrato"];
$vencimento = ConverteData($_POST["vencimento"]);
$vencimento_2 = $_POST["vencimento"];	
$quantidade = intval($_POST["quantidade"]);
$quant_quilo_aux = intval($_POST["quantidade_quilo"]);
$quantidade_adquirida = $_POST["quantidade_adquirida"];
$cod_tipo = $_POST["cod_tipo"];
$preco_produto = ConverteValor($_POST["preco_produto"]);
$preco_produto_print = $_POST["preco_produto"];

if ($_POST["umidade"] == "")
{$umidade = 0;}
else
{$umidade = $_POST["umidade"];}

if ($_POST["broca"] == "")
{$broca = 0;}
else
{$broca = $_POST["broca"];}

if ($_POST["impureza"] == "")
{$impureza = 0;}
else
{$impureza = $_POST["impureza"];}

if ($_POST["multa"] == "")
{$multa = 0;}
else
{$multa = $_POST["multa"];}


if ($_POST["fiador_1"] == "")
{$fiador_1 = 0;}
else
{$fiador_1 = $_POST["fiador_1"];}

if ($_POST["fiador_2"] == "")
{$fiador_2 = 0;}
else
{$fiador_2 = $_POST["fiador_2"];}

if ($_POST["fiador_3"] == "")
{$fiador_3 = 0;}
else
{$fiador_3 = $_POST["fiador_3"];}

$observacao = $_POST["observacao"];
$obs = "(ENTRADA REFERENTE CONTRATO FUTURO N&ordm; " . $numero_contrato . ") " . $observacao;
if ($quant_quilo_aux == "")
{$quantidade_quilo = 0;}
else
{$quantidade_quilo = $quant_quilo_aux;}

$entrada_ficha_produtor = "SIM";

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
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
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
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
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
$unidade = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== DESCRIÇÃO =======================================================================================
if ($umidade == "" or $umidade == "N/A" or $umidade == 0)
{$umidade_desc = ", COM AT&Eacute; 0% DE UMIDADE";}
else
{$umidade_desc = ", COM AT&Eacute; " . $umidade . " DE UMIDADE";}

if ($broca == "" or $broca == "N/A" or $broca == 0)
{$broca_desc = "";}
else
{$broca_desc = ", " . $broca . " DE BROCA";}

if ($impureza == "" or $impureza == "N/A" or $impureza == 0)
{$impureza_desc = "";}
else
{$impureza_desc = ", " . $impureza . " DE IMPUREZA";}

$descricao = "TIPO " . $tipo_print . $umidade_desc . $broca_desc . $impureza_desc;
// ===========================================================================================================


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

<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div> <!-- FIM menu_geral -->





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->

<?php
if ($fornecedor == '' or $cod_produto == '' or $quantidade == '' or $quantidade_adquirida == '' or $preco_produto == '' or $cod_tipo == '')
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Formul&aacute;rio incompleto.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Campos obrigat&oacute;rios: Tipo, Quantidade, Quant. Adquirida e Preço do Produto.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_contrato' value='$data_contrato_2' />
	<input type='hidden' name='vencimento' value='$vencimento_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida' />
	<input type='hidden' name='preco_produto' value='$preco_produto_print' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='multa' value='$multa' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='fiador_3' value='$fiador_3' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}

elseif (!is_numeric($quantidade) or !is_numeric($quantidade_quilo) or !is_numeric($quantidade_adquirida) or !is_int($quantidade) or !is_int($quantidade_quilo))
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade inv&aacute;lida.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Verifique o que foi digitado no campo quantidade.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_contrato' value='$data_contrato_2' />
	<input type='hidden' name='vencimento' value='$vencimento_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida' />
	<input type='hidden' name='preco_produto' value='$preco_produto_print' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='multa' value='$multa' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='fiador_3' value='$fiador_3' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}

elseif ($quantidade <= 0 or $quantidade_quilo < 0 or $quantidade_adquirida <= 0)
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade inv&aacute;lida.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade e Quant. Adquirida n&atilde;o podem ser menor ou igual a zero.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_contrato' value='$data_contrato_2' />
	<input type='hidden' name='vencimento' value='$vencimento_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida' />
	<input type='hidden' name='preco_produto' value='$preco_produto_print' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='multa' value='$multa' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='fiador_3' value='$fiador_3' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}


elseif ($preco_produto <= 0)
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Pre&ccedil;o do produto inv&aacute;lido.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Pre&ccedil;o do produto n&atilde;o pode ser menor ou igual a zero.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_contrato' value='$data_contrato_2' />
	<input type='hidden' name='vencimento' value='$vencimento_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='quantidade_adquirida' value='$quantidade_adquirida' />
	<input type='hidden' name='preco_produto' value='$preco_produto_print' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='multa' value='$multa' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='fiador_3' value='$fiador_3' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}


else
{

// CALCULO PORCENTAGEM JUROS
if ($unidade == "SC")
	{
	$quant_aux_1 = ($quantidade * 60) + $quantidade_quilo;
	$quant_aux_2 = $quant_aux_1 / 60;
	$quant_aux_3 = $quantidade_adquirida;
	$quant_aux_4 = $quant_aux_2 / $quant_aux_3;
	$porcentagem_juros = ($quant_aux_4 - 1) * 100;
	$quant_entregar_print = number_format($quant_aux_2,2,".","");
	}
else
	{
	$quant_aux_2 = $quantidade;
	$quant_aux_3 = $quantidade_adquirida;
	$quant_aux_4 = $quant_aux_2 / $quant_aux_3;
	$porcentagem_juros = ($quant_aux_4 - 1) * 100;
	$quant_entregar_print = number_format($quant_aux_2,2,".","");
	}


	$inserir = mysqli_query ($conexao, "INSERT INTO contrato_futuro (codigo, produtor, data, produto, quantidade, quantidade_adquirida, unidade, descricao_produto, vencimento, observacao, estado_registro, quantidade_fracao, porcentagem_juros, situacao_contrato, quantidade_a_entregar, numero_contrato, usuario_cadastro, hora_cadastro, data_cadastro, filial, nome_produtor, tipo, preco_produto, multa, cod_produto, cod_unidade, cod_tipo) VALUES (NULL, '$fornecedor', '$data_contrato', '$produto_apelido', '$quantidade', '$quantidade_adquirida', '$unidade', '$descricao', '$vencimento', '$observacao', 'ATIVO', '$quantidade_quilo', '$porcentagem_juros', 'EM_ABERTO', '$quant_aux_2', '$numero_contrato', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$filial', '$fornecedor_print', '$tipo_print', '$preco_produto', '$multa', '$cod_produto', '$cod_unidade', '$cod_tipo')");

	
	if ($entrada_ficha_produtor == "SIM")
	{
	$inserir_entrada = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, broca, umidade, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_transferencia, cod_produto, cod_unidade, cod_tipo, fornecedor_print, impureza) VALUES (NULL, '$numero_compra', '$fornecedor', '$produto_apelido', '$data_contrato', '$quantidade_adquirida', '$unidade', '$tipo_print', '$broca', '$umidade', '$obs', 'ENTRADA_FUTURO', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_contrato', '$cod_produto', '$cod_unidade', '$cod_tipo', '$fornecedor_print', '$impureza')");

	// ==================================================================
	// ATUALIZA SALDO ===================================================
	include ('../../includes/busca_saldo_armaz.php');
	$saldo = $saldo_produtor + $quantidade_adquirida;
	include ('../../includes/atualisa_saldo_armaz.php');
	// ==================================================================
	// ==================================================================
	
	}
	
	else
	{}
	
	
		
echo "
<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
<div id='centro' style='float:left; height:25px; width:1080px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
Cadastro de contrato futuro realizado com sucesso!
</div>
<div id='centro' style='float:left; height:180px; width:1080px; color:#00F; text-align:center; border:0px solid #000'>
	<div style='float:left; width:200px; height:175px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
	
	<!-- =========  PRODUTO ============================================================================= -->
	<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
	Produto:</div>
	<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	</div>
	<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:681px; height:20px; border:1px solid #999; float:left; color:#009900; text-align:left; font-size:14px; 
	border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'><b>$produto_print_2</b></div></div>
	<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>
	
	
	<!-- =========  NUMERO DA COMPRA E FORNECEDOR ============================================================================= -->
	<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
	N&uacute;mero do contrato:</div>
	<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Fornecedor:</div>
	<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$numero_contrato</div></div>
	<div style='width:504px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$fornecedor_print</div></div>
	<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>
	
	
	<!-- =========  QUANTIDADE, PREÇO, SAFRA E TIPO ============================================================================= -->
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
	Quantidade Adquirida:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Quantidade a Entregar:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Data Emiss&atilde;o:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Data Vencimento:</div>
	<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$quantidade_adquirida $unidade_print</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$quant_entregar_print $unidade_print</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$data_contrato_2</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$vencimento_2</div></div>
	<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>
</div>










<div id='centro' style='float:left; height:90px; width:1080px; color:#00F; text-align:center; border:0px solid #000'>

	<div id='centro' style='float:left; height:80px; width:355px; color:#00F; text-align:center; border:0px solid #000'>
	</div>

	<div id='centro' style='float:left; height:80px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/compras/contrato_futuro/index_contrato_futuro.php' >
		<img src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' /></a>
	</div>

	<div id='centro' style='float:left; height:80px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_contrato' value='$numero_contrato' />
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir.png' border='0' id='ok' alt='Imprimir' border='0' />
	</div>

</div>";
}

?>







</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>