<?php
if ($linha_compra == 0)
{echo "
<div style='height:200px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='60px'>Visualizar</td>
<td width='120px'>Data</td>
<td width='320px'>Fornecedor</td>
<td width='100px'>N&uacute;mero</td>
<td width='180px'>Produto</td>
<td width='120px'>Tipo</td>
<td width='140px'>Quantidade</td>
<td width='100px'>Pre&ccedil;o Unit&aacute;rio</td>
<td width='160px'>Valor Total</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_compra[0];
$numero_compra_w = $aux_compra[1];
$cod_fornecedor_w = $aux_compra[2];
$produto_print_w = $aux_compra[3];
$data_compra_w = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$quantidade_w = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario_w = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$total_geral_w = $aux_compra[7];
$total_geral_print = "R$ " . number_format($aux_compra[7],2,",",".");
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$data_pagamento_w = $aux_compra[11];
$data_pagamento_print = date('d/m/Y', strtotime($aux_compra[11]));
$estado_registro_w = $aux_compra[18];
$filial_w = $aux_compra[19];
$fornecedor_print_w = $aux_compra[20];
$forma_entrega_w = $aux_compra[21];



$usuario_cadastro_w = $aux_compra[12];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_compra[14]));
$hora_cadastro_w = $aux_compra[13];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_compra[15];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_compra[17]));
$hora_alteracao_w = $aux_compra[16];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

/*
$usuario_exclusao_w = $aux_compra[31];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_compra[32]));
$hora_exclusao_w = $aux_compra[33];
$motivo_exclusao_w = $aux_compra[34];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
*/
// ======================================================================================================


// ====== BLOQUEIO PARA VISUALIZAR ========================================================================
$permite_visualizar = "SIM";
// ========================================================================================================


// ====== RELATORIO =======================================================================================
if ($estado_registro_w == "INATIVO")
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

elseif ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_4' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

else
{echo "<tr class='tabela_1' title=' ID: $id_w &#13; Nome: $fornecedor_print_w &#13; C&oacute;digo do Fornecedor: $cod_fornecedor_w &#13; Tipo do Produto: $tipo_w &#13; Forma de Entrega: $forma_entrega_w &#13; Data de Pagamento: $data_pagamento_print &#13; Observa&ccedil;&atilde;o: $observacao_w &#13; Filial: $filial_w &#13; Status Cadastro: $estado_registro_w $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}



// ====== BOTAO VISUALIZAR ==================================================================================
if ($permite_visualizar == "SIM")
{	
	echo "
	<td width='60px' align='center'>
	<div style='height:24px; margin-top:0px; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/produtos/compra_visualizar.php' method='post'>
	<input type='hidden' name='modulo_mae' value='$modulo'>
	<input type='hidden' name='menu_mae' value='$menu'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='VISUALIZAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='numero_compra' value='$numero_compra_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='numero_venda_busca' value='$numero_compra_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/buscar.png' height='18px' style='margin-top:3px' />
	</form>
	</div>
	</td>";
}

else
{
	echo "
	<td width='60px' align='center'></td>";
}
// =================================================================================================================


// =================================================================================================================
echo "
<td width='120px' align='center'>$data_compra_print</td>
<td width='320px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print_w</div></td>
<td width='100px' align='center'>$numero_compra_w</td>
<td width='180px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$produto_print_w</div></td>
<td width='120px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$tipo_w</div></td>
<td width='140px' align='center'>$quantidade_print $unidade_w</td>
<td width='100px' align='center'>$preco_unitario_print</td>
<td width='160px' align='right'><div style='height:14px; margin-right:15px'>$total_geral_print</div></td>";
// =================================================================================================================

}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_compra == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma compra encontrada.</i></div>";}
// =================================================================================================================
?>