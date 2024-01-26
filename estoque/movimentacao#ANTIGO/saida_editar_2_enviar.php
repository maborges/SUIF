<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_editar_2_enviar';
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
//echo ConverteData($data_emissao);
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){

	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================
	$numero_romaneio = $_POST["numero_romaneio"];
	$pagina_mae = $_POST["pagina_mae"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$botao = $_POST["botao"];
	$fornecedor = $_POST["representante"];
	$p_inicial = $_POST["peso_inicial"];
	$p_final = $_POST["peso_final"];
	$d_sacaria = $_POST["desconto_sacaria"];
	$o_desconto = $_POST["desconto"];
	$produto = $_POST["produto"];
	$t_sacaria = $_POST["tipo_sacaria"];
	$quant_sacaria_aux = $_POST["quant_sacaria"];
	$placa_veiculo = $_POST["placa_veiculo"];
	$motorista = $_POST["motorista"];
	$motorista_cpf = $_POST["motorista_cpf"];
	$confirmacao_negocio = $_POST["confirmacao_negocio"];
	$observacao = $_POST["observacao"];




// =================================================================================================================
	if ($p_inicial == "" or !is_numeric($p_inicial))
	{$peso_inicial = 0;}
	else
	{$peso_inicial = $p_inicial;}
	
	if ($p_final == "" or !is_numeric($p_final))
	{$peso_final = 0;}
	else
	{$peso_final = $p_final;}

	if ($d_sacaria == "" or !is_numeric($d_sacaria))
	{$desconto_sacaria = 0;}
	else
	{$desconto_sacaria = $d_sacaria;}

	if ($o_desconto == "" or !is_numeric($o_desconto))
	{$desconto = 0;}
	else
	{$desconto = $o_desconto;}

	if ($quant_sacaria_aux == "" or !is_numeric($quant_sacaria_aux))
	{$quant_sacaria = 0;}
	else
	{$quant_sacaria = $quant_sacaria_aux;}



	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());



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


// CALCULOS  ==========================================================================================
$desconto_sacaria = ($peso_sacaria * $quant_sacaria);
$quantidade = ($peso_final - $peso_inicial - $desconto_sacaria - $desconto);

// =================================================================================================================



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


	if ($fornecedor == '' or $produto == '' )
	{
	echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
	<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
	<b>Fornecedor</b>, <b>Peso Inical</b> e <b>Produto</b> s&atilde;o obrigat&oacute;rios para o cadastro.</div>
	<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
	</div>";
	}
	
	elseif ($linha_pessoa == 0)
	{
	echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
	<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
	Fornecedor inexistente.</div>
	<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
	</div>";
	}

	elseif (!is_numeric($peso_inicial) or !is_numeric($peso_final) or !is_numeric($desconto_sacaria) or !is_numeric($desconto))
	{
	echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
	<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
	<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
	Peso inv&aacute;lido</div>
	<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
	<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
	</div>";
	}
	else
	{
	


// =============================================================================================================
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





	$editar = mysqli_query ($conexao, "UPDATE estoque SET fornecedor='$fornecedor', produto='$produto', peso_inicial='$peso_inicial', tipo_sacaria='$t_sacaria', placa_veiculo='$placa_veiculo', motorista='$motorista', 
	observacao='$observacao', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao', motorista_cpf='$motorista_cpf', confirmacao_negocio='$confirmacao_negocio' WHERE numero_romaneio='$numero_romaneio'");
	



		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Romaneio editado com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; Romaneio: $numero_romaneio</br>
			Cliente: $fornecedor_print</br>
			Produto: $produto_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:370px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' />
		</form>
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