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
<td width='70px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='350px' align='center' bgcolor='#006699'>Cliente</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='125px' align='center' bgcolor='#006699'>Produto</td>
<td width='90px' align='center' bgcolor='#006699'>Peso Inicial</td>
<td width='90px' align='center' bgcolor='#006699'>Peso Final</td>
<td width='80px' align='center' bgcolor='#006699'>Desc. Sacaria</td>
<td width='90px' align='center' bgcolor='#006699'>Peso L&iacute;quido</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>NF</td>
<td width='54px' align='center' bgcolor='#006699'>Imprimir</td>
</tr>
</table>";}

echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO ROMANEIO ============================================================================
$numero_romaneio = $aux_romaneio[1];
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
$filial = $aux_romaneio[25];
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

$usuario_cadastro = $aux_romaneio[19];
if ($usuario_cadastro == "")
{$dados_cadastro = "";}
else
{
$data_cadastro = date('d/m/Y', strtotime($aux_romaneio[21]));
$hora_cadastro = $aux_romaneio[20];
$dados_cadastro = "Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro";
}

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
/*
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$t_sacaria' ORDER BY codigo");
$aux_sacaria = mysqli_fetch_row($busca_sacaria);
$linha_sacaria = mysqli_num_rows ($busca_sacaria);

$tipo_sacaria = $aux_sacaria[1];
$peso_sacaria = $aux_sacaria[2];
if ($linha_sacaria == 0)
{$descrisao_sacaria = "(Sem sacaria)";}
else
{$descrisao_sacaria = "$tipo_sacaria ($peso_sacaria Kg)";}
*/
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
/*
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$quant_kg_saca = $aux_bp[27];
*/
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
$busca_pessoa = mysqli_query ($conexao, "SELECT nome FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[0];
/*
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf" or $aux_pessoa[2] == "PF")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
*/
// ======================================================================================================


// ====== BUSCA ENTRADA =================================================================================
$busca_saida = mysqli_query ($conexao, "SELECT numero_compra FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$aux_busca_saida = mysqli_fetch_row($busca_saida);
$linha_saida = mysqli_num_rows ($busca_saida);

if ($linha_saida == 0)
{$num_registro_saida = "(Romaneio ainda n&atilde;o vinculado a ficha)";}
else
{$num_registro_saida = $aux_busca_saida[0];}
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


// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
if ($permissao[78] == "S" and $estado_registro == "ATIVO")
{$permite_visualizar = "SIM";}
else
{$permite_visualizar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_emissao);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[24] or $permissao[81] == "S")
{$pode_editar = "S";}
else
{$pode_editar = "N";}

//if ($permissao[16] == "S" and $transferencia_filiais != "SIM" and $linha_saida == 0 and $pode_editar == "S" and $estado_registro == "ATIVO")
if ($permissao[16] == "S" and $linha_saida == 0 and $pode_editar == "S" and $estado_registro == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA FINALIZAR ========================================================================
if ($permissao[77] == "S" and $estado_registro == "ATIVO" and $situacao_romaneio == "EM_ABERTO")
{$permite_finalizar = "SIM";}
else
{$permite_finalizar = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA NOTA FISCAL =======================================================================
if ($permissao[82] == "S" and $estado_registro == "ATIVO")
{$permite_nf = "SIM";}
else
{$permite_nf = "NAO";}
// ========================================================================================================

// ====== BLOQUEIO PARA IMPRESSAO =======================================================================
if ($permissao[79] == "S" and $estado_registro == "ATIVO")
{$permite_imprimir = "SIM";}
else
{$permite_imprimir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
	if ($situacao_romaneio == "EM_ABERTO")
	{echo "<tr class='tabela_3' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
	{echo "<tr class='tabela_2' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	else
	{echo "<tr class='tabela_1' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	
	echo "
	<td width='70px' align='center'>$data_print</td>";
	if ($situacao == "ENTRADA_DIRETA")
	{echo "<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>";}
	else
	{echo "<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>";}	
	echo "
	<td width='60px' align='center'>$numero_romaneio</td>
	<td width='125px' align='center'>$produto</td>
	<td width='90px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$peso_inicial_print $unidade_print</div></td>
	<td width='90px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$peso_final_print $unidade_print</div></td>
	<td width='80px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$desconto_sacaria_print $unidade_print</div></td>
	<td width='90px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'><b>$quantidade_print</b> $unidade_print</div></td>";

// ====== BOTAO VISUALIZAR ===================================================================================================
	if ($permite_visualizar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_visualizar.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/buscar.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================

	
// ====== BOTAO EDITAR ===================================================================================================
	if ($situacao_romaneio == "FECHADO" and $permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($situacao_romaneio == "EM_ABERTO" and $permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/editar_3_formulario.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/editar.png' height='20px' border='0' />
		</form>	
		</td>";
	}
	
	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO NOTA FISCAL ===================================================================================================
	if ($permite_nf == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_saida/nota_fiscal.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='$botao'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
		<input type='hidden' name='data_final_busca' value='$data_final_br'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/doc_1.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO IMPRIMIR ===================================================================================================
	if ($permite_imprimir == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
        <form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/imprimir.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


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