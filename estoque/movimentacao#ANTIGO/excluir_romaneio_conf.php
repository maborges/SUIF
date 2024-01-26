<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'excluir_romaneio_conf';
$titulo = 'Excluir Romaneio';
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
<div id="centro" style="height:450px; width:930px; border:0px solid #000; margin:auto">

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
	$filial = $filial_usuario;
	$numero_romaneio = $_POST["numero_romaneio"];
	
	$pagina_mae = $_POST["pagina_mae"];
	$pagina_filha = $_POST["pagina_filha"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$produto_list = $_POST["produto_list"];
	$produtor_ficha = $_POST["produtor_ficha"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$num_romaneio_aux = $_POST["num_romaneio_aux"];
	$movimentacao = $_POST["movimentacao"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// =============================================================================================================
// =============================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$data_romaneio = $aux_romaneio[3];
$data_romaneio_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$fornecedor = $aux_romaneio[2];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$tipo = $aux_romaneio[5];
$observacao = $aux_romaneio[18];
$estado_registro = $aux_romaneio[26];
$movimentacao = $aux_romaneio[13];
$usuario_cadastro = $aux_romaneio[19];
$hora_cadastro = $aux_romaneio[20];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$usuario_exclusao = $aux_romaneio[40];
$hora_exclusao = $aux_romaneio[41];
$data_exclusao = date('d/m/Y', strtotime($aux_romaneio[42]));
$motivo_exclusao = $aux_romaneio[43];



// PRODUTO PRINT  ==========================================================================================
$busca_produto_print = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' AND apelido='$produto' ORDER BY codigo");
$linha_produto_print = mysqli_num_rows ($busca_produto_print);
	for ($p=1 ; $p<=$linha_produto_print ; $p++)
	{
		$aux_produto_print = mysqli_fetch_row($busca_produto_print);
		if ($linha_produto_print == 0)
		{$produto_print = "-";}
		else
		{$produto_print = $aux_produto_print[22];}
	}

// UNIDADE PRINT  ==========================================================================================
	if ($unidade == "SC")
	{	
		if ($quantidade <= 1)
		{$unidade_print = "Saca";}
		else	
		{$unidade_print = "Sacas";}
	}
	elseif ($unidade == "KG")
	{
		if ($quantidade <= 1)
		{$unidade_print = "Kg";}
		else	
		{$unidade_print = "Kg";}
	}
	elseif ($unidade == "CX")
	{$unidade_print = "Caixa";}
	elseif ($unidade == "UN")
	{$unidade_print = "Unidade";}
	else
	{$unidade_print = "-";}

// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$fornecedor_print = $aux_pessoa[1];
}
// =================================================================================================================


// BUSCA NUMERO DE COMPRA  ==========================================================================================
//	$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' and numero_romaneio='$numero_romaneio'");
//	$achou_num_compra = mysqli_num_rows ($busca_num_compra);
// =================================================================================================================



		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Deseja realmente excluir este romaneio?</div>
		<div id='centro' style='float:left; height:130px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:11px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:11px; line-height:18px'>
			N&ordm; Romaneio: $numero_romaneio</br>
			Fornecedor/Cliente: $fornecedor_print</br>
			Produto: $produto_print</br>
			Quantidade: $quantidade $unidade_print</br>
			Tipo Movimenta&ccedil;&atilde;o: $movimentacao</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:11px'>Motivo da exclus&atilde;o: 
			<form action='$servidor/$diretorio_servidor/estoque/movimentacao/excluir_romaneio_enviar.php' method='post'>
			<input type='text' name='motivo_exclusao' maxlength='150' onkeyup='this.value=retira_acentos(this.value);' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:500px' />
		</div>		
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
			<input type='hidden' name='botao' value='botao'>
			<input type='hidden' name='data_inicial' value='$data_inicial'>
			<input type='hidden' name='data_final' value='$data_final'>
			<input type='hidden' name='produto_list' value='$produto_list'>
			<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='pagina_filha' value='$pagina_filha'>
			<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
			<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
			<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
			<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_excluir_2.jpg' border='0' /></form>
		</div>";
		
echo "
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/excluir_romaneio.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";

		


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