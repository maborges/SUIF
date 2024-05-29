<?php
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_e_visualizar';
$titulo = 'Estoque - Romaneio de Entrada';
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


<?php
$filial = $filial_usuario;

$numero_romaneio = $_POST["numero_romaneio"];
$num_romaneio_aux = $_POST["num_romaneio_aux"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];


// =============================================================================================================
// =============================================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],0,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],0,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,0,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],0,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],0,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],0,",",".");
$unidade = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$usuario_cadastro = $aux_romaneio[19];
$hora_cadastro = $aux_romaneio[20];
$data_cadastro = $aux_romaneio[21];
$usuario_alteracao = $aux_romaneio[22];
$hora_alteracao = $aux_romaneio[23];
$data_alteracao = $aux_romaneio[24];
$filial = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];


// BUSCA SACARIA ==============================================================================================

	$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
	$linha_sacaria = mysqli_num_rows ($busca_sacaria);
	
	for ($s=1 ; $s<=$linha_sacaria ; $s++)
	{
	$aux_sacaria = mysqli_fetch_row($busca_sacaria);
	}
	$tipo_sacaria = $aux_sacaria[1];


// CALCULO QUANTIDADE REAL  ==========================================================================================
	if ($produto == "CAFE")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "CAFE_ARABICA")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "PIMENTA")
	{$quantidade_real = ($quantidade / 50);}
	elseif ($produto == "CACAU")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "CRAVO")
	{$quantidade_real = ($quantidade / 60);}
	elseif ($produto == "RESIDUO_CACAU")
	{$quantidade_real = ($quantidade / 60);}
	else
	{$quantidade_real = 0;}

	$quantidade_real_print = number_format($quantidade_real,2,",",".");




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
	{$unidade_print = "Sc";}
	elseif ($unidade == "KG")
	{$unidade_print = "Kg";}
	elseif ($unidade == "CX")
	{$unidade_print = "Cx";}
	elseif ($unidade == "UN")
	{$unidade_print = "Un";}
	else
	{$unidade_print = "-";}

// SITUAÇÃO PRINT  ==========================================================================================
	if ($situacao_romaneio == "PRE_ROMANEIO")
	{$situacao_print = "Pr&eacute;-Romaneio";}
	elseif ($situacao_romaneio == "EM_ABERTO")
	{$situacao_print = "Em Aberto";}
	elseif ($situacao_romaneio == "FECHADO")
	{$situacao_print = "Fechado";}
	else
	{$situacao_print = "-";}



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



// ACHA NOTA FISCAL  ==========================================================================================
//$acha_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE estado_registro!='EXCLUIDO' AND codigo='$codigo_favorecido' ORDER BY nome");
//$linha_acha_favorecido = mysqli_num_rows ($acha_favorecido);




// SOMA PAGAMENTOS  ==========================================================================================
//$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE codigo_compra='$numero_compra' AND estado_registro='ATIVO'"));
//$saldo_pagamento = $valor_total - $soma_pagamentos[0];




// =============================================================================================================
// =============================================================================================================
?>



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

<div id="centro" style="height:565px; width:1080px; border:0px solid #0000FF; margin:auto">

<div id="centro" style="height:30px; width:1050px; border:0px solid #000; color:#003466; font-size:12px"></div>

<!-- ============================================================================================================== -->
<div id="centro" style="width:1050px; border:0px solid #000; color:#003466; margin-left:25px; font-size:17px; float:left" align="center">
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:#003466; font-size:12px; float:left" align="left"></div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; font-size:14px; float:left" align="center">
	<?php
	if ($situacao_romaneio == "PRE_ROMANEIO")
	{echo "<b style='color:#FF0000'>Pr&eacute;-Romaneio de Entrada</b>";}
	else
	{echo "<b style='color:#003466'>Romaneio de Entrada</b>";}
	?>
	</div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:003466; font-size:12px; float:left" align="right">N&ordm; <?php echo"$numero_romaneio"; ?></div>
</div>

<div id="centro" style="width:1050px; border:0px solid #000; color:#003466; margin-left:25px; font-size:17px; float:left" align="center">
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:#003466; font-size:12px; float:left" align="left"></div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:003466; font-size:14px; float:left" align="center"></div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:003466; font-size:12px; float:left" align="right"><?php echo"$data_print"; ?></div>
</div>

<div id="centro" style="width:1050px; border:0px solid #000; color:#003466; margin-left:25px; font-size:17px; float:left" align="center">
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:#003466; font-size:12px; float:left" align="left"></div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:003466; font-size:14px; float:left" align="center"></div>
    <div id="centro" style="width:345px; height:20px; border:0px solid #000; color:003466; font-size:12px; float:left" align="right"><?php echo"$hora_alteracao"; ?></div>
</div>


<!-- ========================================================== DADOS DO FORNECEDOR ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:20px">
	<div style="margin-top:3px; margin-left:55px">Dados do Fornecedor:</div></div>
		<div id="centro" style="width:1030px; height:50px; border:1px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:650px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px; color:003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div></div>
			<div style="width:220px; height:15px; border:0px solid #000; float:left; font-size:11px; color:003466">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:650px; height:15px; border:0px solid #000; float:left; font-size:11px; margin-left:25px; color:003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[10] - $aux_pessoa[12]</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px; color:003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$aux_pessoa[14]</b>" ?></div></div>

		</div>

<!-- ========================================================== DADOS DO PRODUTO ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:20px">
	<div style="margin-top:3px; margin-left:55px"><!-- Dados do Produto:--></div></div>
		<div id="centro" style="width:1030px; height:295px; border:0px solid #999; color:#003466; border-radius:5px; overflow:hidden; margin-left:25px">

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:240px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Produto:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$produto_print</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Safra:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$safra</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Tipo:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$tipo</b>" ?></div></div>
			<div style="width:180px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Umidade:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$umidade</b>" ?></div></div>
			<div style="width:220px; height:15px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left"><!--Umidade:--></div><div style="margin-top:3px; margin-left:5px; float:left"><?php // echo"<b>$broca</b>" ?></div></div>

			<div style="width:1025px; height:25px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:6px">
			<div style="margin-top:5px">Peso Inicial</div></div>
			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Final</div></div>
			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso L&iacute;quido</div></div>
			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Desconto Sacaria</div></div>
			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Outros Descontos</div></div>
			<div style="width:150px; height:20px; border:0px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px">Peso Desc. Sacaria</div></div>

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:6px">
			<div style="margin-top:5px"><?php echo"$peso_inicial_print Kg" ?></div></div>
			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_final_print Kg" ?></div></div>
			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$peso_bruto_print Kg" ?></div></div>
			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_sacaria_print Kg" ?></div></div>
			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"$desconto_print Kg" ?></div></div>
			<div style="width:150px; height:25px; border:1px solid #999; border-radius:5px; float:left; font-size:11px; text-align:center; margin-left:20px">
			<div style="margin-top:5px"><?php echo"<b>$quantidade_print</b> Kg" ?></div></div>


			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:1000px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left"></div>
			<div style="margin-top:3px; margin-left:5px; width:900px; height:14px; float:left; border:0px solid #000; overflow:hidden"></div></div>


			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quantidade real em sacas:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$quantidade_real_print Sacas</b>" ?></div></div>

			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$tipo_sacaria" ?></div></div>




			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Situa&ccedil;&atilde;o Romaneio:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$situacao_print" ?></div></div>

			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$placa_veiculo" ?></div></div>


			
			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista" ?></div></div>

			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista_cpf" ?></div></div>
		


			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$num_romaneio_manual" ?></div></div>

			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Desconto Previsto Kg (Qualidade):</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$desconto_previsto" ?></div></div>
		

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>
			
			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Filial Origem:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$filial_origem" ?></div></div>

			<div style="width:500px; height:20px; border:0px solid #000; float:left; font-size:11px">
			<div style="margin-top:3px; margin-left:5px; float:left">Quant. Volume de Sacas:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$quant_volume" ?></div></div>





			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:11px"></div>

			<div style="width:1000px; height:20px; border:0px solid #000; float:left; font-size:11px; margin-left:25px">
			<div style="margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
			<div style="margin-top:3px; margin-left:5px; width:900px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php echo"$observacao" ?></div></div>


		</div>



<!-- ============================================================================================================== -->






<div id="tabela_2" style="width:1030px; height:15px; border:0px solid #000; font-size:9px; margin-top:30px">
<div style="margin-top:3px; margin-left:55px">Notas Fiscais de Entrada:</div></div>



</div>






<!-- ================== INICIO DO RELATORIO ================= -->
<div id="centro" style="height:auto; width:1050px; border:1px solid #999; margin:auto; border-radius:5px;">

<div id="centro" style="height:10px; width:1030px; border:0px solid #999; margin:auto"></div>
<?php
$busca_nota_fiscal = mysqli_query ($conexao, "SELECT * FROM nota_fiscal_entrada WHERE estado_registro!='EXCLUIDO' AND codigo_romaneio='$numero_romaneio' ORDER BY data_emissao");
$linha_nota_fiscal = mysqli_num_rows ($busca_nota_fiscal);


if ($linha_nota_fiscal == 0)
{echo "<div id='centro' style='height:30px; width:1030px; border:0px solid #999; font-size:12px; color:#FF0000; margin-left:30px'><i>N&atilde;o existem notas fiscais para este romaneio.</i></div>";}
else
{echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='90px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='380px' align='center' bgcolor='#006699'>Fornecedor / Produtor</td>
<td width='120px' align='center' bgcolor='#006699'>N&ordm; Nota Fiscal</td>
<td width='122px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='122px' align='center' bgcolor='#006699'>Valor Total</td>
</tr>
</table>
</div>
<div id='centro' style='height:10px; width:1030px; border:0px solid #999; margin:auto'></div>";}

echo "
<div id='centro' style='height:auto; width:1030px; border:0px solid #999; margin:auto'>
<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";

for ($w=1 ; $w<=$linha_nota_fiscal ; $w++)
{
	$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

// DADOS DO FAVORECIDO =========================
	$busca_favorecido_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$aux_nota_fiscal[2]' ORDER BY nome");
	$aux_busca_favorecido_2 = mysqli_fetch_row($busca_favorecido_2);
	$codigo_pessoa_2 = $aux_busca_favorecido_2[35];
	
	$busca_pessoa_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo_pessoa='$codigo_pessoa_2' ORDER BY nome");
	$aux_busca_pessoa_2 = mysqli_fetch_row($busca_pessoa_2);
	$nome_favorecido_2 = $aux_busca_pessoa_2[1];
	$tipo_pessoa_2 = $aux_busca_pessoa_2[2];
		if ($tipo_pessoa_2 == "pf")
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[3];}
		else
		{$cpf_cnpj_2 = $aux_busca_pessoa_2[4];}

	$data_nf_print_2 = date('d/m/Y', strtotime($aux_nota_fiscal[4]));		
	$numero_nf_print_2 = $aux_nota_fiscal[3];
	$valor_unitario_print_2 = number_format($aux_nota_fiscal[5],2,",",".");
	$valor_total_print_2 = number_format($aux_nota_fiscal[8],2,",",".");
	$unidade_print_2 = $aux_nota_fiscal[6];
	$quantidade_print_2 = $aux_nota_fiscal[7];
	$observacao_print_2 = $aux_nota_fiscal[8];
	

// RELATORIO =========================
	echo "
	<tr style='color:#00F' title='CPF/CNPJ: $cpf_cnpj_2'>
	<td width='90px' align='left'>&#160;&#160;$data_nf_print_2</td>
	<td width='380px' align='left'>&#160;&#160;$nome_favorecido_2</td>
	<td width='120px' align='center'>$numero_nf_print_2</td>
	<td width='122px' align='center'>$quantidade_print_2 $unidade_print_2</td>
	<td width='122px' align='right'>$valor_unitario_print_2&#160;&#160;</td>
	<td width='122px' align='right'>&#160;&#160;$valor_total_print_2&#160;&#160;</td>
	</tr>";
}
echo "
</table>
</div>
<div id='centro' style='height:15px; width:1030px; border:0px solid #999; margin:auto'></div>
";


?>




</div>
<!-- ================== FIM DO RELATORIO ================= -->


<div id="centro" style="height:15px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center"></div>
<div id="centro" style="height:60px; width:1030px; border:0px solid #999; margin:auto; border-radius:5px; text-align:center">
<div id='centro' style='float:left; height:55px; width:330px; color:#00F; text-align:center; border:0px solid #000'></div>
<?php

if ($pagina_filha == "movimentacao")
{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='botao' value='botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";
}
else
{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/$pagina_mae.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='representante' value='$produtor_ficha'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>";
}



if ($situacao_romaneio == "PRE_ROMANEIO")
{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/romaneio_pre_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_romaneio.jpg' border='0' /></form>
		</div>";
}
else
{
	echo "
		<div id='centro' style='float:left; height:55px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir_romaneio.jpg' border='0' /></form>
		</div>";
}



?>
</div>

</div> <!-- ================================== FIM DA DIV CENTRO GERAL ======================================= -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>