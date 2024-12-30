<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_enviar_3';
$titulo = 'Entrada';
$menu = 'ficha_produtor';
$modulo = 'compras';
// ========================================================================================================


// ====== CONTADOR NÚMERO COMPRA ==========================================================================
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra = $aux_bnc[7];

$contador_num_compra = $numero_compra + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ========================================================================================================


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

<?php include ('../../includes/sub_menu_compras_ficha_produtor.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php

// =================================================================================================================
	$filial = $filial_usuario;

	$numero_romaneio = $_POST["numero_romaneio"];
	$num_romaneio_manual = $_POST["num_romaneio_manual"];
	$produto = $_POST["produto_list"];
	$produto_list = $_POST["produto_list"];
	$data_compra = date('Y/m/d', time());
	$fornecedor = $_POST["representante"];
	$quantidade_kg = $_POST["peso_inicial"];
	$desconto_aux = $_POST["desconto"];
		if (!is_numeric($desconto_aux) or $desconto_aux < 0)
		{$desconto = 0;}
		else
		{$desconto = $desconto_aux;}
	$movimentacao = "ENTRADA";
	$obs = $_POST["observacao"];
	$observacao = "(ENT_DIR_3) " . $obs;
	$tipo = $_POST["tipo"];
	$placa_veiculo = $_POST["placa_veiculo"];
	$movimenta_estoque = $_POST["movimenta_estoque"];
	
	$usuario_cadastro = $nome_usuario_print;
	$hora_cadastro = date('G:i:s', time());
	$data_cadastro = date('Y/m/d', time());
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// CALCULA QUANTIDADE  ==========================================================================================
	if ($produto == "CAFE")
	{$quantidade = (($quantidade_kg - $desconto)/ 60);
	$desconto_2 = ($desconto / 60);
	$quantidade_estoque = $quantidade_kg;}
	
	else
	{$quantidade = ($quantidade_kg - $desconto);
	$desconto_2 = $desconto;
	$quantidade_estoque = $quantidade_kg;}

	$quantidade_print = number_format($quantidade,2,",",".");


	


// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";}
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";}
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";}
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";}
	else
	{$produto_print = "-";}


// UNIDADE  ==========================================================================================
	if ($produto == "CAFE")
	{$unidade = "SC";}
	elseif ($produto == "PIMENTA")
	{$unidade = "KG";}
	elseif ($produto == "CACAU")
	{$unidade = "KG";}
	elseif ($produto == "CRAVO")
	{$unidade = "KG";}
	else
	{$unidade = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
}
// =================================================================================================================

// BUSCA NUMERO DE COMPRA  =========================================================================================
	$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' and numero_compra='$numero_compra'");
	$achou_num_compra = mysqli_num_rows ($busca_num_compra);
// =================================================================================================================


// BUSCA NUMERO DE ROMANEIO MANUAL  ========================================================================================
	$busca_num_romaneio_manual = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND num_romaneio_manual='$num_romaneio_manual'");
	$achou_num_romaneio_manual = mysqli_num_rows ($busca_num_romaneio_manual);
// ==================================================================================================================


// BUSCA NUMERO DE ROMANEIO  ========================================================================================
	$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND num_romaneio_manual='$num_romaneio_manual'");
	$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
	for ($r=1 ; $r<=$achou_num_romaneio ; $r++)
	{
	$aux_romaneio = mysqli_fetch_row($busca_num_romaneio);
	$aux_romaneio_estoque = $aux_romaneio[1];
	}
// ==================================================================================================================




if ($fornecedor == '' or $produto == '' or $tipo == '')
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
<b>Produtor</b>, <b>Tipo</b> e <b>Produto</b> s&atilde;o obrigat&oacute;rios para o cadastro.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
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
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
</div>";
}
/*
elseif ($achou_num_romaneio_manual >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
J&aacute; existe um cadastro de entrada com este n&uacute;mero de romaneio.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
</div>";
}


elseif ($achou_num_romaneio >= 1)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
J&aacute; existe um romaneio da balan&ccedil;a para esta entrada. <b>N&ordm; $aux_romaneio_estoque</b></div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
</div>";
}

*/

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
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
</div>";
}



elseif ($linha_pessoa == 0)
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
Produtor inexistente.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/entrada_cadastro_3.php' method='post'>
		<input type='hidden' name='aux_cod_produtor' value='$aux_cod_produtor'>
		<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
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
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' border='0' /></form>
	</div>";
	}
	else
	{
		$inserir = mysqli_query ($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_romaneio, desconto_quantidade, num_romaneio_manual) VALUES (NULL, '$numero_compra', '$fornecedor', '$produto', '$data_compra', '$quantidade', '$unidade', '$tipo', '$observacao', '$movimentacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_romaneio', '$desconto_2', '$num_romaneio_manual')");


if ($movimenta_estoque == "SIM")
{
		$inserir_estoque = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, quantidade, unidade, movimentacao, situacao, situacao_romaneio, placa_veiculo, observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, num_romaneio_manual) VALUES (NULL, '$numero_romaneio', '$fornecedor', '$data_compra', '$produto', '$quantidade_estoque', '$quantidade_estoque', 'KG', 'ENTRADA', 'ENTRADA_DIRETA', 'FECHADO', '$placa_veiculo', '$observacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$filial', 'ATIVO', '$num_romaneio_manual')");
}
else
{}


// ==================================================================
// ATUALIZA SALDO ===================================================
$cod_forne = $fornecedor;

include ('../../includes/busca_saldo_armaz.php');

$saldo = $saldo_produtor + $quantidade;

include ('../../includes/atualisa_saldo_armaz.php');
// ==================================================================
// ==================================================================



		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Entrada realizada com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; Registro: $numero_compra</br>
			N&ordm; Romaneio: $numero_romaneio</br>
			Produtor: $fornecedor_print</br>
			Produto: $produto_print</br>
			Quantidade: $quantidade_print $unidade</br>
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
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='representante' value='$fornecedor'>
		<input type='hidden' name='botao' value='seleciona'>
		<input type='hidden' name='produto_list' value='$produto'>
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