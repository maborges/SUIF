<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_enviar_final';
$titulo = 'Estoque - Sa&iacute;da';
$modulo = 'estoque';
$menu = 'movimentacao';

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
<?php include ('../../includes/menu_estoque.php'); ?>

<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:410px; width:930px; border:0px solid #000; margin:auto">

<?php
	$numero_romaneio = $_POST["numero_romaneio"];
	$pagina_mae = $_POST["pagina_mae"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$situacao_romaneio = "FECHADO";
	$observacao = $_POST["observacao"];
	$p_final = $_POST["peso_final"];
	$quant_sacaria_aux = $_POST["quant_sacaria"];
	$o_desconto = $_POST["desconto"];
	$t_sacaria = $_POST["tipo_sacaria"];
	$filial = $filial_usuario;

	if ($p_final == "")
	{$peso_final = 0;}
	else
	{$peso_final = $p_final;}

	if ($o_desconto == "")
	{$desconto = 0;}
	else
	{$desconto = $o_desconto;}

	if ($quant_sacaria_aux == "")
	{$quant_sacaria = 0;}
	else
	{$quant_sacaria = $quant_sacaria_aux;}


// BUSCA SACARIA ==============================================================================================
// =============================================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

for ($s=1 ; $s<=$linha_sacaria ; $s++)
{
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
}
$peso_sacaria = $aux_sacaria[2];
$tipo_sacaria = $aux_sacaria[1];


// =============================================================================================================
// =============================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$cod_produto = $aux_romaneio[44];
$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_hoje = date('Y-m-d', time());
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$fornecedor_print_2 = "GRANCAFE COM. IMP. E EXP. LTDA (LINHARES)";

// CALCULOS  ==========================================================================================
$desconto_sacaria = ($peso_sacaria * $quant_sacaria);
$quantidade = ($peso_final - $peso_inicial - $desconto_sacaria - $desconto);

// PRODUTO PRINT  ==========================================================================================
	if ($produto == "CAFE")
	{$produto_print = "Caf&eacute; Conilon";
	$quant_convet = ($quantidade / 60);}
	
	elseif ($produto == "PIMENTA")
	{$produto_print = "Pimenta do Reino";
	$quant_convet = ($quantidade);}
	
	elseif ($produto == "CACAU")
	{$produto_print = "Cacau";
	$quant_convet = ($quantidade);}
	
	elseif ($produto == "CRAVO")
	{$produto_print = "Cravo da &Iacute;ndia";
	$quant_convet = ($quantidade);}
	
	else
	{$produto_print = "-";
	$quant_convet = ($quantidade);}


	if ($produto == "CAFE")
	{$unidade = "SC";}
	else
	{$unidade = "KG";}



	$usuario_cadastro = $nome_usuario_print;
	$hora_cadastro = date('G:i:s', time());
	$data_cadastro = date('Y/m/d', time());
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($y=1 ; $y<=$linha_pessoa ; $y++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
	if ($aux_pessoa[2] == "pf")
	{$cpf_cnpj = $aux_pessoa[3];}
	else
	{$cpf_cnpj = $aux_pessoa[4];}
}




	if (!is_numeric($peso_final) or !is_numeric($quant_sacaria) or !is_numeric($desconto))
	{
	echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
	<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
	Peso ou quantidade inv&aacute;lidos</div>
	<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
	</div>";
	}
	else
	{
	
		

	$editar = mysqli_query ($conexao, "UPDATE estoque SET peso_final='$peso_final', desconto_sacaria='$desconto_sacaria', desconto='$desconto', quantidade='$quantidade', situacao_romaneio='$situacao_romaneio', observacao='$observacao', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', quantidade_sacaria='$quant_sacaria' WHERE numero_romaneio='$numero_romaneio'");


// ====== ROMANEIO DE ENTRADA AUTOMATICO - LINHARES/POLO =======================================================================
	if ($fornecedor == "6856")
	{
	$busca_n_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
	$aux_n_romaneio = mysqli_fetch_row($busca_n_romaneio);
	$n_romaneio = $aux_n_romaneio[8];
	$contador_n_romaneio = $n_romaneio + 1;
	$altera_cont = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_n_romaneio'");

	$inserir_entrada = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, peso_final, desconto_sacaria, desconto, quantidade, unidade, tipo_sacaria, movimentacao, situacao_romaneio, placa_veiculo, motorista, observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, quantidade_sacaria, motorista_cpf, num_romaneio_manual, filial_origem, cod_produto, fornecedor_print) VALUES (NULL, '$n_romaneio', '491', '$data', '$produto', '$peso_final', '$peso_inicial', '$desconto_sacaria', '$desconto', '$quantidade', 'KG', '$t_sacaria', 'ENTRADA', '$situacao_romaneio', '$placa_veiculo', '$motorista', '$observacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'LINHARES_POLO', 'ATIVO', '$quant_sacaria', '$motorista_cpf', '$numero_romaneio', 'LINHARES', '$cod_produto', '$fornecedor_print_2')");
	}

	
	else
	{}
	


		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Peso final registrado com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; Romaneio: $numero_romaneio</br>
			Cliente: $fornecedor_print</br>
			Produto: $produto_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:277px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' />
		</form>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/romaneio_impressao_saida.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_romaneio.jpg' border='0' /></form>
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