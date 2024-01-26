<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_enviar_3';
$titulo = 'Entrada de Mercadoria';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ======================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==========================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ========================================================================================================


// =========== CONVERTE DATA ==========================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ======================================================================================================


// ========== CONVERTE VALOR ============================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ========================================================================================================


// ======= RECEBENDO POST =================================================================================
$filial = $filial_usuario;

$numero_romaneio = $_POST["numero_romaneio"];
$num_romaneio_manual = $_POST["num_romaneio_manual"];
$produto = $_POST["produto_list"];
$produto_list = $_POST["produto_list"];
$cod_produto = $_POST["cod_produto"];
$data_compra = date('Y/m/d', time());
$fornecedor = $_POST["fornecedor"];
$quantidade_kg = $_POST["peso_inicial"];
$desconto_aux = $_POST["desconto"];
	if (!is_numeric($desconto_aux) or $desconto_aux < 0)
	{$desconto = 0;}
	else
	{$desconto = $desconto_aux;}
$movimentacao = "ENTRADA";
$obs = $_POST["observacao"];
$observacao = "(ED3 - ROMANEIO MANUAL N&ordm; ". $num_romaneio_manual . ") " . $obs;
$cod_tipo = $_POST["cod_tipo"];
$placa_veiculo = $_POST["placa_veiculo"];
$movimenta_estoque = $_POST["movimenta_estoque"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
// ========================================================================================================


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


// ====== CALCULA QUANTIDADE ===================================================================================
if ($produto_apelido == "CAFE" or $produto_apelido == "CAFE_ARABICA")
{$quantidade = (($quantidade_kg - $desconto)/ 60);
$desconto_2 = ($desconto / 60);
$quantidade_estoque = $quantidade_kg;}
	
else
{$quantidade = ($quantidade_kg - $desconto);
$desconto_2 = $desconto;
$quantidade_estoque = $quantidade_kg;}

$quantidade_print = number_format($quantidade,2,",",".");
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA TIPO PRODUTO ==========================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo' AND estado_registro!='EXCLUIDO'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== DESCONTO QUANTIDADE FICHA ===================================================================================
$desconto_quant_ficha = $desconto;
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


// ====== BUSCA NUMERO DE COMPRA ===================================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' and numero_compra='$numero_compra'");
$achou_num_compra = mysqli_num_rows ($busca_num_compra);
// ==================================================================================================================


// ====== BUSCA ROMANEIO MANUAL - COMPRAS ======================================================================
$busca_rmc = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND num_romaneio_manual='$num_romaneio_manual'");
$linhas_rmc = mysqli_num_rows ($busca_rmc);
$aux_rmc = mysqli_fetch_row($busca_rmc);

$numero_rmc = $aux_rmc[1];
// ==================================================================================================================


// ====== BUSCA ROMANEIO MANUAL - ESTOQUE  ============================================================================
$busca_rme = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND num_romaneio_manual='$num_romaneio_manual'");
$linhas_rme = mysqli_num_rows ($busca_rme);
$aux_rme = mysqli_fetch_row($busca_rme);

$numero_rme = $aux_rme[1];
// ==================================================================================================================


// ==================================================================================================================
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
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php







if ($fornecedor == '' or $cod_produto == '' or $cod_tipo == '')
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
<b>Fornecedor</b>, <b>Tipo</b> e <b>Produto</b> s&atilde;o obrigat&oacute;rios para o cadastro.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}


elseif ($achou_num_compra >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Aten&ccedil;&atilde;o! entrada j&aacute; realizada.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}

elseif ($linhas_rmc >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio manual. (N&ordm; Registro: $numero_rmc)</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}


elseif ($linhas_rme >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
J&aacute; existe um romaneio da balan&ccedil;a para esta entrada. (N&ordm; Romaneio: $numero_rme)</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}



elseif ($quantidade <= 0)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Quantidade inv&aacute;lida.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}



elseif ($linhas_fornecedor == 0)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Fornecedor inexistente.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
</div>";
}

else
{
	if (!is_numeric($desconto_aux))
	{
	echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
	<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
	Desconto inv&aacute;lido</div>
	<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Voltar</button></form>
	</div>";
	}
	else
	{
	$inserir = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, situacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_romaneio, desconto_quantidade, num_romaneio_manual, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_compra', '$fornecedor', '$produto_apelido', '$data_compra', '$quantidade', '$unidade_print', '$tipo_print', '$observacao', '$movimentacao', 'ENTRADA_DIRETA', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_romaneio', '$desconto_2', '$num_romaneio_manual', '$cod_produto', '$cod_unidade', '$cod_tipo', '$fornecedor_print')");

if ($movimenta_estoque == "SIM")
{
	$inserir_estoque = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, tipo, peso_inicial, quantidade, unidade, movimentacao, situacao, situacao_romaneio, placa_veiculo, observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, numero_compra, desconto_quant_ficha, num_romaneio_manual, filial_origem, pendente, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_romaneio', '$fornecedor', '$data_compra', '$produto_apelido', '$tipo_print', '$quantidade_estoque', '$quantidade_estoque', 'KG', 'ENTRADA', 'ENTRADA_DIRETA', 'FECHADO', '$placa_veiculo', '$observacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$filial', 'ATIVO', '$numero_compra', '$desconto_quant_ficha', '$num_romaneio_manual', '$filial', 'N', '$cod_produto', '$cod_unidade', '$cod_tipo', '$fornecedor_print')");
}
else
{}



// =========================================================================================================
// ====== ATUALIZA SALDO ===================================================================================
include ('../../includes/busca_saldo_armaz.php');
$saldo = $saldo_produtor + $quantidade;
include ('../../includes/atualisa_saldo_armaz.php');
// =========================================================================================================
// =========================================================================================================



		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Entrada direta realizada com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm;: $numero_compra</br>
			N&ordm; Romaneio Manual: $num_romaneio_manual</br>
			Fornecedor: $fornecedor_print</br>
			Produto: $produto_print</br>
			Quantidade: $quantidade_print $unidade_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:282px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

<!--
		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<a href='$servidor/$diretorio_servidor/compras/produtos/compra_selecionar.php' id='ok'>
		<img src='$servidor/$diretorio_servidor/imagens/botoes/nova_compra.jpg' border='0' /></a>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/compra_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_compra.jpg' border='0' /></form>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/forma_pagamento.jpg' border='0' /></form>
		</div>
-->

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_seleciona_3.php' method='post'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>	


		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='botao' value='seleciona'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/movimentacao.jpg' border='0' /></form>
		</div>";	


	}
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