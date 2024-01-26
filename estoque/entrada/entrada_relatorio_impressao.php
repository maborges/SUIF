<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'entrada_relatorio_impressao';
$titulo = 'Estoque - Relat&oacute;rio de Entradas';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================


// ================================================================================================================
include ('include_comando.php'); 
// ================================================================================================================


// ==================================================================================================================
include ('../../includes/head_impressao.php');
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onLoad="imprimir()">

<div id="centro" style="width:770px; border:0px solid #F00">

<?php
// #################################################################################################################################
// ####### Determina-se aqui nesse "FOR" "limite_registros" a quantidade de linhas que aparecerá em cada página de impressão #######
// #######           É importante sempre testar antes para ver quantas linhas são necessárias             					 #######
// #################################################################################################################################
$limite_registros = 48;
$numero_paginas = ceil($linha_romaneio / $limite_registros);


for ($x_principal=1 ; $x_principal<=$numero_paginas ; $x_principal++)
{
	
echo "
<div id='centro' style='width:768px; height:1080px; border:0px solid #000; page-break-after:always'>




<!-- =================================================================================================================== -->
<div style='width:710px; height:80px; border:0px solid #D85; float:left; margin-top:25px; margin-left:40px; font-size:17px' align='center'>

<!-- ====================== -->
	<div style='width:200px; height:40px; border:0px solid #000; font-size:16px; float:left' align='left'>
	<img src='$servidor/$diretorio_servidor/imagens/logomarca_pb.png' border='0' width='160px' /></div>

	<div style='width:315px; height:40px; border:0px solid #000; font-size:16px; float:left' align='center'>
	<div style='margin-top:5px'>ESTOQUE - ROMANEIOS DE ENTRADA<br /></div></div>

	<div style='width:190px; height:40px; border:0px solid #000; font-size:11px; float:left' align='right'>
	<div style='margin-top:5px'>$data_atual<br />$hora_atual</div></div>

<!-- ====================== -->
	<div style='width:200px; height:18px; border:0px solid #000; font-size:11px; float:left' align='left'></div>

	<div style='width:315px; height:18px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$prod_print</div></div>

	<div style='width:190px; height:18px; border:0px solid #000; font-size:11px; float:left' align='right'></div>

<!-- ====================== -->
	<div style='width:200px; height:16px; border:0px solid #000; font-size:11px; float:left' align='left'>$print_periodo</div>

	<div style='width:315px; height:16px; border:0px solid #000; font-size:11px; float:left' align='center'>
	<div style='height:14px; overflow:hidden'>$print_fornecedor</div></div>

	<div style='width:190px; height:16px; border:0px solid #000; font-size:11px; float:left' align='right'>$print_quant_reg</div>

</div>



<!-- =================================================================================================================== -->

<div style='width:710px; height:auto; border:0px solid #00E; margin-top:2px; margin-left:40px; float:left'>

<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:63px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Data</div></div>
	<div style='width:204px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Fornecedor</div></div>
	<div style='width:48px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>N&ordm;</div></div>
	<div style='width:100px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Produto</div></div>
	<div style='width:69px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Peso Inicial (Kg)</div></div>
	<div style='width:69px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Peso Final (Kg)</div></div>
	<div style='width:69px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>Sacaria (Kg)</div></div>
	<div style='width:70px; height:15px; border:1px solid #000; float:left; background-color:#666'><div style='margin-top:2px'>P. L&iacute;quido (Kg)</div></div>
</div>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$limite_registros ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);


// ====== DADOS DO ROMANEIO ============================================================================
$num_romaneio_print = $aux_romaneio[1];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$tipo = $aux_romaneio[5];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],2,",",".");
$peso_bruto = ($peso_inicial - $peso_final);
$peso_bruto_print = number_format($peso_bruto,2,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],2,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],2,",",".");
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial_w = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],2,",",".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$usuario_cadastro = $aux_romaneio[19];
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
$usuario_alteracao = $aux_romaneio[22];
if ($usuario_alteracao == "")
{$dados_alteracao = "";}
else
{
$data_alteracao = date('d/m/Y', strtotime($aux_romaneio[24]));
$hora_alteracao = $aux_romaneio[23];
$dados_alteracao = "Editado por: $usuario_alteracao $data_alteracao $hora_alteracao";
}
// ======================================================================================================


// ====== BUSCA SACARIA ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descrisao_sacaria = "(Sem sacaria)";}
else
{$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$quant_kg_saca = $aux_bp[27];
// ======================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
if ($quant_kg_saca == 0)
{$quantidade_real_print = "";}
else
{
$quantidade_real = ($quantidade / $quant_kg_saca);
$quantidade_real_print = number_format($quantidade_real,2,",",".");
}
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================


// ====== SITUAÇÃO PRINT ===================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($situacao_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($situacao_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
	if ($aux_romaneio[0] == "")
	{$contador_vazio = $contador_vazio + 1;}
	
	else
	{
	echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:9px'>

		<div style='width:63px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='margin-left:6px'>$data_print</div></div>";
		
		if ($situacao == "ENTRADA_DIRETA")
		{echo "
		<div style='width:204px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:14px; margin-left:6px; overflow:hidden'>$fornecedor_print <b>(ED)</b></div></div>";}
		else
		{echo "
		<div style='width:204px; height:15px; border:1px solid #FFF; float:left; text-align:left; background-color:#FFF'>
		<div style='height:11px; margin-left:6px; overflow:hidden'>$fornecedor_print</div></div>";}
		
		echo "
		<div style='width:48px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		$num_romaneio_print</div>
		
		<div style='width:100px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF; font-size:8px'>
		$produto_print</div>

		<div style='width:69px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'>$peso_inicial_print</div></div>

		<div style='width:69px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'>$peso_final_print</div></div>

		<div style='width:69px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'>$desconto_sacaria_print</div></div>
		
		<div style='width:70px; height:15px; border:1px solid #FFF; float:left; text-align:right; background-color:#FFF'>
		<div style='margin-right:6px'><b>$quantidade_print</b></div></div>
		
	</div>";
	}
// ======================================================================================================

$y = $x;
// ======================================================================================================
}



// =============================
$x = ($x + $limite_registros);
// =============================



if ($linha_romaneio == 0)
{echo "
	<div style='width:708px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px'>
		<div style='width:705px; height:15px; border:1px solid #FFF; float:left; text-align:center; background-color:#FFF'>
		<i>Nenhum romaneio encontrado.</i></div>
	</div>";}
else
{}


// ====== TOTALIZADOR =====================================================================================
if ($x_principal == $numero_paginas)
{
	echo "
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>
	<div style='width:708px; height:15px; border:0px solid #000; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'>
	TOTAL DE ENTRADA: <b>$soma_romaneio_print Kg</b>
	</div>";

for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='ENTRADA' AND $mysql_filtro_data AND $mysql_situacao_romaneio AND $mysql_forma_pesagem AND $mysql_fornecedor 
AND $mysql_cod_produto AND cod_produto='$aux_bp_geral[0]' AND filial='$filial'"));
$soma_quant_print = number_format($soma_quant_produto[0],2,",",".");


	if ($soma_quant_produto[0] == 0)
	{}
	else
	{
	$linhas_t_aux = $linhas_t_aux + 1;
	
	
	echo "
	<div style='width:300px; height:27px; border:0px solid #999; margin-top:5px; margin-left:204px; float:left; background-color:#FFF; font-size:11px'>
	<div style='height:26px; width:290px; border:1px solid #000; border-radius:3px; background-color:#FFF; margin-left:0px'>
		<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:10px; margin-top:6px'>
		<b>$aux_bp_geral[22]</b></div>
		
		<div style='width:130px; color:#000; border:0px solid #000; float:left; margin-left:10px; margin-top:6px; text-align:left'>
		<b>$soma_quant_print</b> Kg</div>
		
	</div>
	</div>";
	}

}
$linhas_totalizador = 2 + ($linhas_t_aux * 2);
$vazio = $contador_vazio - $linhas_totalizador;
}

else
{}
// ========================================================================================================



// ========================================================================================================
if ($x_principal == $numero_paginas and $vazio >= 1)
{
	for ($v=1 ; $v<=$vazio ; $v++)
	{
echo "<div style='width:708px; height:15px; border:1px solid #FFF; margin-top:1px; float:left; color:#000; font-size:11px; text-align:center'></div>";
	}
}

else
{}



echo "</div>";
// ========================================================================================================






echo "
<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:10px; border:0px solid #000; margin-left:40px; margin-top:20px; float:left' align='center'>
<hr />
</div>


<!-- =============================================================================================== -->
<div id='centro' style='width:710px; height:15px; border:0px solid #f85; float:left; margin-left:40px; font-size:17px' align='center'>
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='left'>
	&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m</div>
	
	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:left' align='center'>FILIAL: $filial</div>

	<div id='centro' style='width:233px; height:15px; border:0px solid #000; font-size:9px; float:right' align='right'>
	P&Aacute;GINA $x_principal/$numero_paginas</div>
</div>
<!-- =============================================================================================== -->



<!-- =============================================================================================== -->";
echo "</div>"; // quebra de página
} // fim do primeiro "FOR"
?>




</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->