<?php
// ======================================================================================================
if ($linha_romaneio == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_romaneio == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' align='center' bgcolor='#006699'>Data</td>
<td width='330px' align='center' bgcolor='#006699'>Cliente</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='120px' align='center' bgcolor='#006699'>Produto</td>
<td width='92px' align='center' bgcolor='#006699'>Peso Inicial</td>
<td width='92px' align='center' bgcolor='#006699'>Peso Final</td>
<td width='92px' align='center' bgcolor='#006699'>Desc. Sacaria</td>
<td width='92px' align='center' bgcolor='#006699'>Peso L&iacute;quido</td>
<!-- <td width='80px' align='center' bgcolor='#006699'>Status</td> -->
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Finalizar</td>
<td width='54px' align='center' bgcolor='#006699'>NF</td>
</tr>
</table>";}

echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$unidade_print = $aux_romaneio[11];
$fornecedor = $aux_romaneio[2];
$quantidade = $aux_romaneio[10];
$quantidade_print = number_format($aux_romaneio[10],2,",",".");
$tipo = $aux_romaneio[5];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$peso_inicial = $aux_romaneio[6];
$peso_inicial_print = number_format($aux_romaneio[6],2,",",".");
$peso_final = $aux_romaneio[7];
$peso_final_print = number_format($aux_romaneio[7],2,",",".");
$desconto_sacaria = $aux_romaneio[8];
$desconto_sacaria_print = number_format($aux_romaneio[8],2,",",".");
$desconto = $aux_romaneio[9];
$desconto_print = number_format($aux_romaneio[9],2,",",".");
$tipo_sacaria = $aux_romaneio[12];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$observacao = $aux_romaneio[18];
$quantidade_prevista = $aux_romaneio[27];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================


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


// ====== SITUAÇÃO PRINT ================================================================================
if ($situacao_romaneio == "PRE_ROMANEIO")
{$situacao_print = "Pr&eacute;-Romaneio";}
elseif ($situacao_romaneio == "EM_ABERTO")
{$situacao_print = "Em Aberto";}
elseif ($situacao_romaneio == "FECHADO")
{$situacao_print = "Fechado";}
else
{$situacao_print = "-";}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	if ($situacao_romaneio == "EM_ABERTO")
	{echo "<tr style='color:#333' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Status romaneio: $situacao_print &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
	{echo "<tr style='color:#009900' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Status romaneio: $situacao_print &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	else
	{echo "<tr style='color:#00F' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Status romaneio: $situacao_print &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	
	echo "
	<td width='70px' align='left'>&#160;$data_print</td>";
	if ($situacao == "SAIDA_DIRETA")
	{echo "<td width='330px' align='left'>&#160;$fornecedor_print <b>(ED)</b></td>";}
	else
	{echo "<td width='330px' align='left'>&#160;$fornecedor_print</td>";}	
	echo "
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='120px' align='center'>$produto_print</td>
	<td width='92px' align='center'>$peso_inicial_print Kg</td>
	<td width='92px' align='center'>$peso_final_print Kg</td>
	<td width='92px' align='center'>$desconto_sacaria_print Kg</td>
	<td width='92px' align='center'><b>$quantidade_print</b> Kg</td>
	<!-- <td width='80px' align='center'>$situacao_print</td> -->
	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_visualizar.php' method='post'>
	<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='balanca_peq' value='$balanca_peq'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_visualizar.png' border='0' /></form>	
	</td>";

// Bloqueio para edição de um mês atras ==========================
	if ($situacao_romaneio == "FECHADO" and $permissao[16] == 'S'  /*and (strtotime($data) > strtotime($mes_atras))*/)
	{	
	
		echo "
		<td width='54px' align='center'></td>";
/*
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/saida_editar.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='balanca_peq' value='$balanca_peq'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_editar.png' border='0' /></form>	
		</td>";
*/
	}

// Bloqueio para edição de um mês atras ==========================
	elseif ($situacao_romaneio == "EM_ABERTO" and $permissao[16] == 'S'  /*and (strtotime($data) > strtotime($mes_atras))*/)
	{	

		echo "
		<td width='54px' align='center'></td>";

/*
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/saida_editar_2.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='balanca_peq' value='$balanca_peq'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_editar.png' border='0' /></form>	
		</td>";
		
*/		
	}
	
	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}


	if ($situacao_romaneio == "FECHADO" or $permissao[17] == 'N')
		{echo "<td width='54px' align='center'></td>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
		{

		echo "
		<td width='54px' align='center'></td>";
			
/*			
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/saida_cadastro_inicial.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='balanca_peq' value='$balanca_peq'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='botao' value='RELATORIO'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_balanca.png' border='0' /></form>	
		</td>";
*/
		}
	else
		{
		echo "
		<td width='54px' align='center'></td>";
/*
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/saida_cadastro_final.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='balanca_peq' value='$balanca_peq'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='botao' value='RELATORIO'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_balanca.png' border='0' /></form>	
		</td>";
*/
		}
		echo "
		<td width='54px' align='center'></td>";
/*
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_saida/nota_fiscal.php' method='post'>
		<input type='hidden' name='numero_romaneio' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='balanca_peq' value='$balanca_peq'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='botao' value='1'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='num_romaneio_aux' value='$num_romaneio_aux'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/recibo.png' border='0' /></form>	
		</td>";
*/
	echo "</tr>";

}


// =================================================================================================================
echo "</table>";

if ($linha_romaneio == 0 and $botao == "BUSCAR")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum romaneio encontrado.</i></div>";}
else
{}

// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================
?>