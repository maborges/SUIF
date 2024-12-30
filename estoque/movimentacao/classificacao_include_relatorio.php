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
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='80px' height='25px' align='center' bgcolor='#006699'>Data</td>
<td width='320px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='130px' align='center' bgcolor='#006699'>Produto</td>
<td width='95px' align='center' bgcolor='#006699'>Filial</td>
<td width='95px' align='center' bgcolor='#006699'>Filial Origem</td>
<td width='95px' align='center' bgcolor='#006699'>Peso L&iacute;quido</td>
<td width='130px' align='center' bgcolor='#006699'>Desconto Realizado</td>
<td width='130px' align='center' bgcolor='#006699'>Desconto Previsto (Kg)</td>
<td width='64px' align='center' bgcolor='#006699'>Classificar</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO ROMANEIO ============================================================================
$num_romaneio_print = $aux_romaneio[1];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor = $aux_romaneio[2];
$data_emissao = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
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
$unidade_print = "Kg";
$t_sacaria = $aux_romaneio[12];
$situacao = $aux_romaneio[14];
$situacao_romaneio = $aux_romaneio[15];
$placa_veiculo = $aux_romaneio[16];
$motorista = $aux_romaneio[17];
$motorista_cpf = $aux_romaneio[31];
$observacao = $aux_romaneio[18];
$filial_cadastro = $aux_romaneio[25];
$estado_registro = $aux_romaneio[26];
$quantidade_prevista = $aux_romaneio[27];
$quant_sacaria = number_format($aux_romaneio[28],0,",",".");
$numero_compra = $aux_romaneio[29];
$num_romaneio_manual = $aux_romaneio[33];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
$filial_origem = $aux_romaneio[34];
$quant_volume = $aux_romaneio[39];
$transferencia_filiais = $aux_romaneio[53];
$desconto_previsto = $aux_romaneio[36];

if ($cod_produto == "2")
{$desconto_realizado = 60 * $aux_romaneio[37];}
else
{$desconto_realizado = $aux_romaneio[37];}


$usuario_cadastro = $aux_romaneio[19];
if ($usuario_cadastro == "")
{$dados_cadastro = "";}
else
{
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
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
$quantidade_real = ($quantidade / $quant_kg_saca);
$quantidade_real_print = number_format($quantidade_real,2,",",".");
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


// BUSCA NUMERO DE ROMANEIO  ==========================================================================================
$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$aux_romaneio[1]'");
$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
// =================================================================================================================


// RELATORIO =======================================================================================================
if ($classificacao == "SIM")
{echo "<tr class='tabela_6' title=' Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_romaneio &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Cadastro: $filial_cadastro &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}

else
{echo "<tr class='tabela_3' title=' Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_romaneio &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Cadastro: $filial_cadastro &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	
	echo "
	<td width='80px' align='left'>&#160;$data_print</td>";
	if ($situacao == "ENTRADA_DIRETA")
	{echo "<td width='320px' align='left'>&#160;$fornecedor_print <b>(ED)</b></td>";}
	else
	{echo "<td width='320px' align='left'>&#160;$fornecedor_print</td>";}	
	echo "
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='130px' align='center'>$produto_print</td>
	<td width='95px' align='center'>$filial_cadastro</td>
	<td width='95px' align='center'>$filial_origem</td>
	<td width='95px' align='center'>$quantidade_print Kg</td>
	<td width='130px' align='center'>$desconto_realizado Kg</td>
	<td width='130px' align='center'>";
	if ($situacao_romaneio != "FECHADO")
	{echo "</td>";}
	else
	{echo "
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/classificacao_qualidade.php' method='post'>
		<input type='hidden' name='numero_romaneio_form' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_class' value='SIM'>
		<input type='hidden' name='data_inicial' value='$data_inicial_br'>
		<input type='hidden' name='data_final' value='$data_final_br'>
		<input type='hidden' name='cod_produto_pesq' value='$cod_produto_pesq'>
		<input type='hidden' name='filial_origem_pesq' value='$filial_origem_pesq'>
		<input type='hidden' name='tipo_filial_pesq' value='$tipo_filial_pesq'>
		<input type='hidden' name='classificacao_romaneio_pesq' value='$classificacao_romaneio_pesq'>
		<input type='hidden' name='quant_entregar_aux' value='$quant_entregar_aux'>
		<input type='text' name='quantidade_desconto_form' class='form_input' style='width:90px; text-align:center; maxlength='11' value='$desconto_previsto' onkeypress='mascara(this,m_quantidade)' />
		</td>";}
	
	echo "<td width='64px' align='center'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ok.png' height='20px' border='0' /></form></td>";

	echo "</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
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