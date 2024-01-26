<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'talao_baixar';
$titulo = 'Baixar Tal&atilde;o';
$menu = 'ficha_produtor';
$modulo = 'compras';
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
<div id="centro" style="height:450px; width:930px; border:0px solid #000; margin:auto">

<?php
// =================================================================================================================
	$filial = $filial_usuario;
	$codigo_talao = $_POST["codigo_talao"];
	$pagina_mae = $_POST["pagina_mae"];
	$botao = $_POST["botao"];
	$taloes_divergentes = $_POST["taloes_divergentes"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// =============================================================================================================
// =============================================================================================================
$busca_talao = mysqli_query($conexao, "SELECT * FROM talao_controle WHERE estado_registro!='EXCLUIDO' AND codigo_talao='$codigo_talao' AND filial='$filial' ORDER BY codigo");
$linha_talao = mysqli_num_rows ($busca_talao);

for ($x=1 ; $x<=$linha_talao ; $x++)
{
$aux_talao = mysqli_fetch_row($busca_talao);

$num_talao = $aux_talao[1];
$produto = $aux_talao[3];
$produto_list = $aux_talao[3];
$data_talao = $aux_talao[6];
$data_talao_print = date('d/m/Y', strtotime($aux_talao[6]));
$hora_impressao = $aux_talao[7];
$unidade = $aux_talao[5];
$fornecedor = $aux_talao[2];
$cod_forne = $aux_talao[2];
$quantidade = $aux_talao[4];
$quantidade_print = number_format($aux_talao[4],2,",",".");
$observacao = $aux_talao[10];
$filial_talao = $aux_talao[13];
$devolvido = $aux_talao[9];
$usuario_impressao = $aux_talao[8];
	if ($aux_talao[13] == "")
	{$data_baixa = "";}
	else
	{$data_baixa = date('d/m/Y', strtotime($aux_talao[13]));}
$usuario_baixa = $aux_talao[14];


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


// DEVOLVIDO PRINT  ==========================================================================================
	if ($devolvido == "S")
	{$devolvido_print = "SIM";}
	elseif ($devolvido == "N")
	{$devolvido_print = "N&Atilde;O";}
	else
	{$devolvido_print = "-";}



// BUSCA PESSOA  ==========================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor' ORDER BY nome");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
	for ($y=1 ; $y<=$linha_pessoa ; $y++)
	{
	$aux_pessoa = mysqli_fetch_row($busca_pessoa);
	$fornecedor_print = $aux_pessoa[1];
		if ($aux_pessoa[2] == "pf")
		{$cpf_cnpj = "CPF: " . $aux_pessoa[3];}
		else
		{$cpf_cnpj = "CNPJ: " . $aux_pessoa[4];}
	}

}

		echo "<div id='centro' style='float:left; height:20px; width:925px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:925px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/ok.png' border='0' /></div>
		<div id='centro' style='float:left; height:40px; width:925px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Deseja realmente baixar este tal&atilde;o?</div>
		<div id='centro' style='float:left; height:130px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:362px; height:148px; color:#00F; text-align:left; border:0px solid #000; font-size:11px'></div>
			<div style='float:left; width:400px; color:#000066; text-align:left; border:0px solid #000; font-size:11px; line-height:18px'>
			N&ordm; do tal&atilde;o: $num_talao</br>
			Produtor: $fornecedor_print</br>
			Produto: $produto_print</br>
			Quantidade: $quantidade_print $unidade_print</br>
			</div>
		</div>
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000; font-size:11px'>
			<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post'>
		</div>		
		
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
			<input type='hidden' name='codigo_talao' value='$codigo_talao'>
			<input type='hidden' name='botao' value='baixar'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='taloes_divergentes' value='$taloes_divergentes'>
			<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_confirmar_2.png' border='0' /></form>
		</div>";
		

echo "
		<div id='centro' style='float:left; height:40px; width:925px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/talao_relatorio.php' method='post'>
			<input type='hidden' name='codigo_talao' value='$codigo_talao'>
			<input type='hidden' name='botao' value='$botao'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='taloes_divergentes' value='$taloes_divergentes'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_3.png' border='0' /></form>
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