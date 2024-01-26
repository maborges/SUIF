<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'contrato_tratado_enviar';
$menu = 'contratos';
$titulo = 'Contrato Tratado';
$modulo = 'compras';
	
	
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
$numero_contrato = $_POST["numero_contrato"];
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$data_contrato = date('Y/m/d', time());
$data_entrega_i = ConverteData($_POST["data_entrega_i"]);
$data_entrega_i_2 = $_POST["data_entrega_i"];	
$data_entrega_f = ConverteData($_POST["data_entrega_f"]);
$data_entrega_f_2 = $_POST["data_entrega_f"];	
$quantidade = intval($_POST["quantidade"]);
$quant_quilo_aux = intval($_POST["quantidade_quilo"]);
$valor = ConverteValor($_POST["valor"]);
$valor_print = $_POST["valor"];
$safra = $_POST["safra"];
$prazo_pgto = $_POST["prazo_pgto"];		
$cod_tipo = $_POST["cod_tipo"];
$umidade = $_POST["umidade"];
$broca = $_POST["broca"];
$impureza = $_POST["impureza"];
$fiador_1 = $_POST["fiador_1"];
$fiador_2 = $_POST["fiador_2"];	
$observacao = $_POST["observacao"];
$filial = $filial_usuario;

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

if ($_POST["fiador_1"] == "")
{$codigo_fiador_1 = 0;}
else
{$codigo_fiador_1 = $_POST["fiador_1"];}

if ($_POST["fiador_2"] == "")
{$codigo_fiador_2 = 0;}
else
{$codigo_fiador_2 = $_POST["fiador_2"];}

if ($_POST["fiador_3"] == "")
{$codigo_fiador_3 = 0;}
else
{$codigo_fiador_3 = $_POST["fiador_3"];}



if ($quant_quilo_aux == "")
{$quantidade_quilo = 0;}
else
{$quantidade_quilo = $quant_quilo_aux;}

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
if ($umidade == "" or $umidade == "N/A")
{$umidade_desc = ", COM AT&Eacute; 0% DE UMIDADE";}
else
{$umidade_desc = ", COM AT&Eacute; " . $umidade . " DE UMIDADE";}

if ($broca == "" or $broca == "N/A")
{$broca_desc = "";}
else
{$broca_desc = ", " . $broca . " DE BROCA";}

if ($impureza == "" or $impureza == "N/A")
{$impureza_desc = "";}
else
{$impureza_desc = ", " . $impureza . " DE IMPUREZA";}

$descricao = "TIPO " . $tipo_print . $umidade_desc . $broca_desc . $impureza_desc;
// ===========================================================================================================


// ====== CALCULA VALOR TOTAL ==========================================================================
if ($quantidade_quilo == 0 or $quantidade_quilo == "")
{
	$quant_total = $quantidade;
	$valor_total = $quant_total * $valor;
}
else
{
	$quant_aux = $quantidade_quilo / 60;
	$quant_total = $quantidade + $quant_aux;
	$valor_total = $quant_total * $valor;
}
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

<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->

<?php
if ($fornecedor == '' or $cod_produto == '' or $quantidade == '' or $valor == '' or $cod_tipo == '' or $data_entrega_i == '' or $data_entrega_f == '' or $prazo_pgto == '')
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Formul&aacute;rio incompleto.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Campos obrigat&oacute;rios: Tipo, Data de Entrega, Quantidade, Preço do Produto e Prazo de PGTO.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_entrega_i' value='$data_entrega_i_2' />
	<input type='hidden' name='data_entrega_f' value='$data_entrega_f_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='valor' value='$valor_print' />
	<input type='hidden' name='prazo_pgto' value='$prazo_pgto' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='safra' value='$safra' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='observacao' value='$observacao' />
	<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
	</form>
</div>";
}

elseif (!is_numeric($quantidade) or !is_numeric($quantidade_quilo) or !is_int($quantidade) or !is_int($quantidade_quilo))
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
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_entrega_i' value='$data_entrega_i_2' />
	<input type='hidden' name='data_entrega_f' value='$data_entrega_f_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='valor' value='$valor_print' />
	<input type='hidden' name='prazo_pgto' value='$prazo_pgto' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='safra' value='$safra' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='observacao' value='$observacao' />
	<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
	</form>
</div>";
}

elseif ($quantidade <= 0 or $quantidade_quilo < 0)
{
echo "<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' alt='erro' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:1080px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade inv&aacute;lida.</div>
<div id='centro' style='float:left; height:50px; width:1080px; color:#666; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade n&atilde;o podem ser menor ou igual a zero.
</div>
<div id='centro' style='float:left; height:90px; width:1080px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_entrega_i' value='$data_entrega_i_2' />
	<input type='hidden' name='data_entrega_f' value='$data_entrega_f_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='valor' value='$valor_print' />
	<input type='hidden' name='prazo_pgto' value='$prazo_pgto' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='safra' value='$safra' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='observacao' value='$observacao' />
	<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
	</form>
</div>";
}

elseif ($valor <= 0)
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
	<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_cadastro.php' method='post' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='data_entrega_i' value='$data_entrega_i_2' />
	<input type='hidden' name='data_entrega_f' value='$data_entrega_f_2' />
	<input type='hidden' name='quantidade' value='$quantidade' />
	<input type='hidden' name='quantidade_quilo' value='$quantidade_quilo' />
	<input type='hidden' name='valor' value='$valor_print' />
	<input type='hidden' name='prazo_pgto' value='$prazo_pgto' />
	<input type='hidden' name='umidade' value='$umidade' />
	<input type='hidden' name='broca' value='$broca' />
	<input type='hidden' name='impureza' value='$impureza' />
	<input type='hidden' name='safra' value='$safra' />
	<input type='hidden' name='fiador_1' value='$fiador_1' />
	<input type='hidden' name='fiador_2' value='$fiador_2' />
	<input type='hidden' name='observacao' value='$observacao' />
	<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
	</form>
</div>";
}


else
{
	$inserir = mysqli_query ($conexao, "INSERT INTO contrato_tratado (codigo, produtor, data, data_entrega_i, data_entrega_f, produto, quantidade, quantidade_fracao, descricao_produto, valor, safra, prazo_pgto, fiador_1, fiador_2, observacao, estado_registro, situacao_contrato, filial, nome_produtor, numero_contrato, unidade, valor_total, quantidade_total, usuario_cadastro, data_cadastro, hora_cadastro, cod_produto, cod_unidade, cod_tipo) VALUES (NULL, '$fornecedor', '$data_contrato', '$data_entrega_i', '$data_entrega_f', '$produto_apelido', '$quantidade', '$quantidade_quilo', '$descricao', '$valor', '$safra', '$prazo_pgto', '$codigo_fiador_1', '$codigo_fiador_2', '$observacao', 'ATIVO', 'EM_ABERTO', '$filial', '$nome_produtor', '$numero_contrato', '$unidade', '$valor_total', '$quant_total', '$usuario_cadastro', '$data_cadastro', '$hora_cadastro', '$cod_produto', '$cod_unidade', '$cod_tipo')");
		
		
echo "
<div id='centro' style='float:left; height:5px; width:1080px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:1080px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
<div id='centro' style='float:left; height:25px; width:1080px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
Cadastro de contrato tratado realizado com sucesso!
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
	Quantidade Tratada:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Pre&ccedil;o do Produto:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Data inicial da entrega:</div>
	<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
	Data final da entrega:</div>
	<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$quant_total $unidade_print</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>R$ $valor_print</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$data_entrega_i_2</div></div>
	<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
	border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$data_entrega_f_2</div></div>
	<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>
	
	<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>
</div>










<div id='centro' style='float:left; height:90px; width:1080px; color:#00F; text-align:center; border:0px solid #000'>

	<div id='centro' style='float:left; height:80px; width:355px; color:#00F; text-align:center; border:0px solid #000'>
	</div>

	<div id='centro' style='float:left; height:80px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/compras/contrato_tratado/index_contrato_tratado.php' >
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button></a>
	</div>

	<div id='centro' style='float:left; height:80px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_contrato' value='$numero_contrato' />
		<button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Imprimir</button>
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