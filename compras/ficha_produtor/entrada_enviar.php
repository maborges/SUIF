<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "entrada_enviar";
$titulo = "Entrada de Mercadoria";
$modulo = "compras";
$menu = "ficha_produtor";
// ======================================================================================================

// ======= RECEBENDO POST =================================================================================
$filial = $filial_usuario;

$numero_compra = $_POST["numero_compra"];
$numero_romaneio = $_POST["numero_romaneio"];
$produto = $_POST["produto"];
$cod_produto = $_POST["cod_produto"];
$produto_list = $_POST["produto"];
$data_compra = date('Y/m/d', time());
$fornecedor = $_POST["fornecedor"];
$quantidade_kg = $_POST["quantidade"] ?? 0;
$desconto_aux = $_POST["desconto"] ?? 0;
	if (!is_numeric($desconto_aux) or $desconto_aux < 0)
	{$desconto = 0;}
	else
	{$desconto = $desconto_aux;}
$movimentacao = "ENTRADA";
$observacao = $_POST["observacao"];
$cod_tipo = $_POST["cod_tipo"];

$usuario_cadastro = $nome_usuario_print;
$hora_cadastro = date('G:i:s', time());
$data_cadastro = date('Y/m/d', time());
// =================================================================================================================


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
{$quantidade = (($quantidade_kg / 60) - $desconto);}
else
{$quantidade = ($quantidade_kg - $desconto);}

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
if ($produto == "CAFE" or $produto == "CAFE_ARABICA")
{$desconto_quant_ficha = ($desconto * 60);}
else
{$desconto_quant_ficha = $desconto;}
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
// =================================================================================================================


// ====== BUSCA NUMERO DE ROMANEIO ===================================================================================
$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$numero_romaneio'");
$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
// =================================================================================================================


// ========================================================================================================
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
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php
if ($cod_tipo == '')
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
<b>Tipo</b> &eacute; obrigat&oacute;rios para o cadastro.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro.php' method='post' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}


elseif ($achou_num_compra >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
N&uacute;mero de entrada j&aacute; existente.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro.php' method='post' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
</div>";
}

elseif ($achou_num_romaneio >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro.php' method='post' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
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
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro.php' method='post' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
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
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro.php' method='post' />
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
	<input type='hidden' name='botao' value='erro_enviar' />
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='observacao' value='$observacao' />
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' border='0' id='ok' />
	</form>
	</div>";
	}
	else
	{
	$inserir = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_romaneio, desconto_quantidade, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_compra', '$fornecedor', '$produto', '$data_compra', '$quantidade', '$unidade_print', '$tipo_print', '$observacao', '$movimentacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_romaneio', '$desconto', '$cod_produto', '$cod_unidade', '$cod_tipo', '$fornecedor_print')");


	$editar = mysqli_query ($conexao, "UPDATE estoque SET tipo='$tipo_print', desconto_quant_ficha='$desconto_quant_ficha', numero_compra='$numero_compra', quant_quebra_realizado='$desconto_quant_ficha', pendente='N', cod_tipo='$cod_tipo' WHERE numero_romaneio='$numero_romaneio'");



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
		Entrada de romaneio realizado com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; $numero_compra</br>
			Fornecedor: $fornecedor_print</br>
			Produto: $produto_print_2</br>
			Quantidade: $quantidade_print $unidade_print</br>
			N&uacute;mero Romaneio: <b>$numero_romaneio</b></br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:375px; color:#00F; text-align:center; border:0px solid #000'>
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
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='botao' value='seleciona'>
		<button type='submit' class='botao_2' style='margin-left:480px; width:120px'>Ficha Produtor</button></form>
		</div>";	


	}
}




?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>