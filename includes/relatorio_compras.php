<?php
// ======================================================================================================
if ($linha_compra == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_compra == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='76px' align='center' bgcolor='#006699'>Data</td>
<td width='300px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='100px' align='center' bgcolor='#006699'>Produto</td>
<td width='85px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='85px' align='center' bgcolor='#006699'>Pre&ccedil;o Un</td>
<td width='95px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
<td width='54px' align='center' bgcolor='#006699'>Pgto</td>
</tr>
</table>";}

echo "<table border='0' id='tabela_4' align='center' style='color:#00F; font-size:9px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

$numero_compra = $aux_compra[1];
$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $aux_compra[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$situacao_pgto = $aux_compra[15];
$observacao = $aux_compra[13];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$usuario_alteracao = $aux_compra[21];
if ($aux_compra[23] == "")
{$data_alteracao = "";}
else
{$data_alteracao = date('d/m/Y', strtotime($aux_compra[23]));}
$hora_alteracao = $aux_compra[22];
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


// ====== BUSCA PAGAMENTO  ===============================================================================
$acha_pagamento = mysqli_query ($conexao, "SELECT * FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' ORDER BY codigo");
$linha_acha_pagamento = mysqli_num_rows ($acha_pagamento);
$soma_pagamentos = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor) FROM favorecidos_pgto WHERE estado_registro!='EXCLUIDO' AND codigo_compra='$aux_compra[1]' AND situacao_pagamento='PAGO'"));
$soma_pagamentos_print = number_format($soma_pagamentos[0],2,",",".");
// ======================================================================================================


// ====== CALCULO SALDO A PAGAR  ==========================================================================
$saldo_a_pagar_aux = $valor_total - $soma_pagamentos[0];
$saldo_a_pagar = number_format($saldo_a_pagar_aux,2,",",".");
$saldo_pagar_total = $saldo_pagar_total + $saldo_a_pagar_aux;
$saldo_pagar_total_print = number_format($saldo_pagar_total,2,",",".");
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	if ($soma_pagamentos[0] < $valor_total)
	{echo "<tr style='color:#000099' title='Tipo do produto: $tipo&#013;Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";}
	else
	{echo "<tr style='color:#000099' title='Tipo do produto: $tipo&#013;Total Pago: R$ $soma_pagamentos_print&#013;Saldo a Pagar: R$ $saldo_a_pagar&#013;Observa&ccedil;&atilde;o: $observacao&#013;Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro&#013;Editado por: $usuario_alteracao $data_alteracao $hora_alteracao'>";}
	
	echo "
	<td width='76px' align='left'><div style='margin-left:3px'>$data_compra_print</div></td>
	<td width='300px' align='left'><div style='margin-left:3px'>$fornecedor_print</div></td>
	<td width='60px' align='center'><div style='margin-left:0px'>$numero_compra</div></td>
	<td width='100px' align='center'><div style='margin-left:0px'>$produto_print_2</div></td>
	<td width='85px' align='center'><div style='margin-left:0px'>$quantidade_print $unidade_print</div></td>
	<td width='85px' align='right'><div style='margin-right:3px'>$preco_unitario_print</div></td>
	<td width='95px' align='right'><div style='margin-right:3px'>$valor_total_print</div></td>
	
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_visualizar.php' method='post'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='1'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='20px' /></form>	
	</td>";
	
	if ($permissao[65] == 'S')
		{echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/compra_editar.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0' /></form>	
		</td>";}
	else
		{echo "<td width='54px' align='center'></td>";}

	if ($linha_acha_pagamento == 0 and $permissao[30] == 'S')
		{echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir.php' method='post'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_relatorio' value='relatorio'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' border='0' /></form>	
		</td>";}
	else
		{echo "<td width='54px' align='center'></td>";}

	echo "
	<td width='54px' align='center'>
	<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao_relatorio' value='relatorio'>
	<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
	<input type='hidden' name='data_final' value='$data_final_aux'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/financeiro_2.png' height='20px' border='0' /></form>	
	</td>
	
	</tr>";
}


// =================================================================================================================
echo "</table>";


// =================================================================================================================
if ($pagina == "relatorio_hoje")
{
	if ($linha_compra == 0)
	{echo "
	<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'>
	<i>Nenhuma compra realizada hoje.</i></div>";}
	else
	{}
}
else
{
	if ($linha_compra == 0 and $botao == "1")
	{echo "
	<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'>
	<i>Nenhuma compra encontrada.</i></div>";}
	else
	{}
}
// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1075px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================
?>
