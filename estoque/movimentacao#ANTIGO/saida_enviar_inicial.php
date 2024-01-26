<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_enviar_inicial';
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
	$produto = $_POST["produto_list"];
	$data = date('Y/m/d', time());
	$p_inicial = $_POST["peso_inicial"];
	$movimentacao = "SAIDA";
	$situacao_romaneio = "EM_ABERTO";
	$tipo_sacaria = $_POST["tipo_sacaria"];
	$placa_veiculo = $_POST["placa_veiculo"];
	$motorista = $_POST["motorista"];
	$motorista_cpf = $_POST["motorista_cpf"];
	$confirmacao_negocio = $_POST["confirmacao_negocio"];
	$observacao = $_POST["observacao"];
	$pagina_aux = $_POST["pagina_aux"];
	$filial = $filial_usuario;

	if ($p_inicial == "")
	{$peso_inicial = 0;}
	else
	{$peso_inicial = $p_inicial;}



	$usuario_cadastro = $nome_usuario_print;
	$hora_cadastro = date('G:i:s', time());
	$data_cadastro = date('Y/m/d', time());
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

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



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
}
// =================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE apelido='$produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$cod_produto = $aux_bp[0];
// ======================================================================================================


// BUSCA NUMERO DE ROMANEIO  ==========================================================================================
	$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' and numero_romaneio='$numero_romaneio'");
	$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
// =================================================================================================================


if ($fornecedor == '' or $produto == '')
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
<b>Cliente</b> e <b>Produto</b> s&atilde;o obrigat&oacute;rios para o cadastro.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
</div>";
}

elseif ($achou_num_romaneio >= 1 and $pagina == "entrada_cadastro")
{
echo "<div id='centro' style='float:left; height:40px; width:925px; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
<div id='centro' style='float:left; height:30px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
N&uacute;mero de romaneio j&aacute; existente.</div>
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
Cliente inexistente.</div>
<div id='centro' style='float:left; height:50px; width:925px; color:#F00; text-align:center; border:0px solid #000'></div>
<div id='centro' style='float:left; height:90px; width:925px; color:#F00; text-align:center; border:0px solid #000'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar.png' id='ok' onclick='voltar()' border='0' />
</div>";
}

else
{
	if (!is_numeric($peso_inicial) or $p_inicial < 0)
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
	
		

	$inserir = mysqli_query ($conexao, "INSERT INTO estoque (codigo, numero_romaneio, fornecedor, data, produto, peso_inicial, unidade, tipo_sacaria, movimentacao, situacao_romaneio, placa_veiculo, motorista, observacao, usuario_cadastro, hora_cadastro, data_cadastro, filial, estado_registro, motorista_cpf, confirmacao_negocio, cod_produto, fornecedor_print) VALUES (NULL, '$numero_romaneio', '$fornecedor', '$data', '$produto', '$peso_inicial', '$unidade', '$tipo_sacaria', '$movimentacao', '$situacao_romaneio', '$placa_veiculo', '$motorista', '$observacao', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', '$filial', 'ATIVO', '$motorista_cpf', '$confirmacao_negocio', '$cod_produto', '$fornecedor_print')");


		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:12px'>
		Peso inicial registrado com sucesso!</div>
		<div id='centro' style='float:left; height:150px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:10px; line-height:18px'>
			N&ordm; Romaneio: $numero_romaneio</br>
			Cliente: $fornecedor_print</br>
			Produto: $produto_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>


		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
			<form action='$servidor/$diretorio_servidor/estoque/movimentacao/saida_relatorio_periodo.php' method='post'>
			<input type='hidden' name='data_inicial' value='$data_inicial'>
			<input type='hidden' name='data_final' value='$data_final'>
			<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
			<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' />
			</form>
		</div>

		<div id='centro' style='float:left; height:90px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
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