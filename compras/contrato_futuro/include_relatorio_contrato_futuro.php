<?php
// ======================================================================================================
if ($linha_cont_futuro == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_cont_futuro == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='275px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='50px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='70px' align='center' bgcolor='#006699'>Vencimento</td>
<td width='115px' align='center' bgcolor='#006699'>Produto</td>
<td width='80px' align='center' bgcolor='#006699'>Valor Un.</td>
<td width='95px' align='center' bgcolor='#006699'>Quant. Adquirida</td>
<td width='95px' align='center' bgcolor='#006699'>Quant. Entregar</td>
<td width='60px' align='center' bgcolor='#006699'>Juros</td>
<td width='50px' align='center' bgcolor='#006699'>Unidade</td>
<td width='52px' align='center' bgcolor='#006699'>2&ordf; Via</td>
<td width='52px' align='center' bgcolor='#006699'>Baixar</td>
<td width='52px' align='center' bgcolor='#006699'>Estornar</td>
<td width='52px' align='center' bgcolor='#006699'>Excluir</td>
<!-- <td width='52px' align='center' bgcolor='#006699'>Pend&ecirc;ncia</td> -->
</tr>
</table>";}

echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:10px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($w=1 ; $w<=$linha_cont_futuro ; $w++)
{
$aux_contrato = mysqli_fetch_row($busca_cont_futuro);

// ====== DADOS DO CONTRATO ============================================================================
$fornecedor_w = $aux_contrato[1];
$cod_produto_w = $aux_contrato[31];
$data_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[2]));
$produto_w = $aux_contrato[3];
$quantidade_w = $aux_contrato[4];
$quantidade_adquirida_w = $aux_contrato[5];
$quant_adquirida_print_w = number_format($aux_contrato[5],2,",",".");
$unidade_w = $aux_contrato[6];
$cod_unidade_w = $aux_contrato[32];
$tipo_w = $aux_contrato[26];
$cod_tipo_w = $aux_contrato[33];
$desc_produto_w = $aux_contrato[7];
$venc_contrato_print_w = date('d/m/Y', strtotime($aux_contrato[8]));
$fiador_1_w = $aux_contrato[9];
$fiador_2_w = $aux_contrato[10];
$observacao_w = $aux_contrato[11];
$estado_registro_w = $aux_contrato[12];
$quantidade_fracao_w = $aux_contrato[13];
$porcentagem_juros_w = $aux_contrato[14];
$situacao_contrato_w = $aux_contrato[15];
$quantidade_a_entregar_w = $aux_contrato[16];
$quant_entregar_print_w = number_format($aux_contrato[16],2,",",".");
$numero_contrato_w = $aux_contrato[17];
$usuario_cadastro_w = $aux_contrato[18];
$hora_cadastro_w = $aux_contrato[19];
$data_cadastro_w = $aux_contrato[20];
$filial_w = $aux_contrato[24];
$preco_produto_w = number_format($aux_contrato[27],2,",",".");
$porc_juros_print_w = number_format($aux_contrato[14],0,",",".") . "%";
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print_w = $aux_bp[1];
$produto_print_2_w = $aux_bp[22];
$produto_apelido_w = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_w' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print_w = $aux_pessoa[1];
$codigo_pessoa_w = $aux_pessoa[35];
$cidade_fornecedor_w = $aux_pessoa[10];
$estado_fornecedor_w = $aux_pessoa[12];
$telefone_fornecedor_w = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf" or $aux_pessoa[2] == "PF")
{$cpf_cnpj_w = $aux_pessoa[3];}
else
{$cpf_cnpj_w = $aux_pessoa[4];}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	if ($situacao_contrato_w == "EM_ABERTO")
	{echo "<tr style='color:#00F' title='N&ordm; Contrato: $numero_contrato_w&#013;Observa&ccedil;&atilde;o: $observacao_w'>";}
	else
	{echo "<tr style='color:#333' title='N&ordm; Contrato: $numero_contrato_w&#013;Observa&ccedil;&atilde;o: $observacao_w'>";}

	echo "
	<td width='70px' height='24px' align='center'>$data_contrato_print_w</td>
	<td width='275px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print_w</div></td>
	<td width='50px' align='center'>$numero_contrato_w</td>
	<td width='70px' align='center'>$venc_contrato_print_w</td>
	<td width='115px' align='center'>$produto_print_2_w</td>
	<td width='80px' align='center'>$preco_produto_w</td>
	<td width='95px' align='center'>$quant_adquirida_print_w</td>
	<td width='95px' align='center'>$quant_entregar_print_w</td>
	<td width='60px' align='center'>$porc_juros_print_w</td>
	<td width='50px' align='center'>$unidade_w</td>";

	if ($permissao[47] == 'S')
	{echo "	
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/contrato_futuro_impressao.php' method='post' target='_blank'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/imprimir.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}
	
	if ($situacao_contrato_w == "EM_ABERTO" and $permissao[48] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/$pagina.php' method='post'>
	<input type='hidden' name='botao' value='BAIXAR'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='codigo_contrato_w' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato_w' value='$aux_contrato[17]'>
	<input type='hidden' name='fornecedor_w' value='$fornecedor_w'>
	<input type='hidden' name='fornecedor_print_w' value='$fornecedor_print_w'>
	<input type='hidden' name='produto_w' value='$produto_w'>
	<input type='hidden' name='cod_produto_w' value='$cod_produto_w'>
	<input type='hidden' name='unidade_w' value='$unidade_w'>
	<input type='hidden' name='cod_unidade_w' value='$cod_unidade_w'>
	<input type='hidden' name='tipo_w' value='$tipo_w'>
	<input type='hidden' name='cod_tipo_w' value='$cod_tipo_w'>
	<input type='hidden' name='quantidade_a_entregar_w' value='$quantidade_a_entregar_w'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='situacao_contrato' value='$situacao_contrato'>
	<input type='hidden' name='filtro_data' value='$filtro_data'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ok.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}


	if ($situacao_contrato_w == "PAGO" and $permissao[49] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/$pagina.php' method='post'>
	<input type='hidden' name='botao' value='ESTORNAR'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='codigo_contrato_w' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato_w' value='$aux_contrato[17]'>
	<input type='hidden' name='fornecedor_w' value='$fornecedor_w'>
	<input type='hidden' name='fornecedor_print_w' value='$fornecedor_print_w'>
	<input type='hidden' name='produto_w' value='$produto_w'>
	<input type='hidden' name='cod_produto_w' value='$cod_produto_w'>
	<input type='hidden' name='unidade_w' value='$unidade_w'>
	<input type='hidden' name='cod_unidade_w' value='$cod_unidade_w'>
	<input type='hidden' name='tipo_w' value='$tipo_w'>
	<input type='hidden' name='cod_tipo_w' value='$cod_tipo_w'>
	<input type='hidden' name='quantidade_a_entregar_w' value='$quantidade_a_entregar_w'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='situacao_contrato' value='$situacao_contrato'>
	<input type='hidden' name='filtro_data' value='$filtro_data'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}

	
	if ($situacao_contrato_w == "EM_ABERTO" and $permissao[50] == 'S')
	{echo "
	<td width='52px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/futuro_excluir.php' method='post'>
	<input type='hidden' name='botao' value='EXCLUIR'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='codigo_contrato' value='$aux_contrato[0]'>
	<input type='hidden' name='numero_contrato' value='$aux_contrato[17]'>
	<input type='hidden' name='fornecedor' value='$fornecedor_w'>
	<input type='hidden' name='fornecedor_print' value='$fornecedor_print_w'>
	<input type='hidden' name='produto' value='$produto_w'>
	<input type='hidden' name='cod_produto' value='$cod_produto_w'>
	<input type='hidden' name='unidade' value='$unidade_w'>
	<input type='hidden' name='cod_unidade' value='$cod_unidade_w'>
	<input type='hidden' name='tipo' value='$tipo_w'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo_w'>
	<input type='hidden' name='quantidade_a_entregar' value='$quantidade_a_entregar_w'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='situacao_contrato' value='$situacao_contrato'>
	<input type='hidden' name='filtro_data' value='$filtro_data'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' /></form>	
	</td>";}
	else
	{echo "<td width='52px' align='center'></td>";}

	
	echo "</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_cont_futuro == 0 and $botao == "BUSCAR")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum contrato encontrado.</i></div>";}
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