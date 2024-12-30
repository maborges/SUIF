<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'nf_relatorio_fornecedor';
$titulo = 'Relat&oacute;rio de Notas Fiscais de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'relatorios';
// ================================================================================================================


// ================================================================================================================
include ('include_comando.php');
// ================================================================================================================


// ================================================================================================================
include ('../../includes/head.php');
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
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php // include ('../../includes/submenu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Relat&oacute;rio de Notas Fiscais de Sa&iacute;da
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:645px; float:left; text-align:left">
	<?php 
		if ($fornecedor_busca == "" or $linhas_forne == 0)
		{echo"<div style='height:28px; width:120px; border:0px solid #000; color:#F00; float:left'>
		<a href='$servidor/$diretorio_servidor/estoque/nota_fiscal_saida/nf_relatorio_fornecedor_seleciona.php'>
		<button type='submit' class='botao_1'>Voltar</button></a>
		</div>
		<div style='height:28px; width:420px; border:0px solid #000; color:#F00; float:left; margin-top:2px'>
		$forne_print</div>";}
		else
		{echo"<div style='height:28px; width:640px; border:0px solid #000; color:#003466; float:left'>Produtor: <b> $forne_print</b>
		</div>";}
	?>
    </div>

	<div class="ct_subtitulo_1" style="width:445px; float:right; text-align:right">
	<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_saida/nf_relatorio_fornecedor_seleciona.php">&#8226; Pesquisar outro produtor</a>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_saida/nf_relatorio_fornecedor.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />
    <input type='hidden' name='fornecedor_busca' value='<?php echo"$fornecedor_busca"; ?>' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Data inicial:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_inicial_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:80px; text-align:center" value="<?php echo"$data_inicial_br"; ?>" />
	</div>

	<div class="pqa_rotulo" style="height:20px; width:85px; border:0px solid #000">Data final:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_final_busca" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:80px; text-align:center" value="<?php echo"$data_final_br"; ?>" />
	</div>
    
    
    <div style="height:20px; width:30px; border:0px solid #999; float:left; text-align:center; margin-top:8px; margin-left:40px">
	<?php
    if ($seleciona_pessoa == "NOTA_FISCAL")
    {echo "<input type='radio' name='seleciona_pessoa' value='NOTA_FISCAL' checked='checked' />";}
    else
    {echo "<input type='radio' name='seleciona_pessoa' value='NOTA_FISCAL' />";}
    ?>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:150px; border:0px solid #000; text-align:left">Produtor (Bloco NF)</div>


    <div style="height:20px; width:30px; border:0px solid #999; float:left; text-align:center; margin-top:8px; margin-left:40px">
	<?php
    if ($seleciona_pessoa == "ROMANEIO")
    {echo "<input type='radio' name='seleciona_pessoa' value='ROMANEIO' checked='checked' />";}
    else
    {echo "<input type='radio' name='seleciona_pessoa' value='ROMANEIO' />";}
    ?>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:150px; border:0px solid #000; text-align:left">Fornecedor Romaneio</div>



<!--
    <div class="pqa_rotulo" style="height:20px; width:90px; border:0px solid #000">Produto:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_produto_busca" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option value="TODOS">(TODOS)</option>
    <?php
	/*
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_busca)
		{
		echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
		else
		{
		echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
	}
	*/
    ?>
    </select>
    </div>
-->

	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>
	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php 
        if ($linha_nf == 0)
        {}
        elseif ($linha_nf == 1)
        {echo"$linha_nf Nota Fiscal";}
        else
        {echo"$linha_nf Notas Fiscais";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
		/*
        if ($linha_nf >= 1)
        {echo"Total de Entrada: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
		*/
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<?php
// ======================================================================================================
if ($linha_nf == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_nf == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='90px' height='20px' align='center' bgcolor='#006699'>Data Emiss&atilde;o</td>
<td width='220px' align='center' bgcolor='#006699'>Produtor (Bloco NF)</td>
<td width='220px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='70px' align='center' bgcolor='#006699'>N&ordm; NF</td>
<td width='70px' align='center' bgcolor='#006699'>N&ordm; Romaneio</td>
<td width='130px' align='center' bgcolor='#006699'>Produto</td>
<td width='90px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='90px' align='center' bgcolor='#006699'>Valor Unit&aacute;rio</td>
<td width='110px' align='center' bgcolor='#006699'>Valor Total</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
</tr>
</table>";}

echo "<table class='tabela_geral'>";
// ====== FUNÇÃO FOR NOTA FISCAL ===========================================================================
for ($n=1 ; $n<=$linha_nf ; $n++)
{
$aux_nf = mysqli_fetch_row($busca_nf);

// ====== DADOS DA NOTA FISCAL ============================================================================
$numero_romaneio = $aux_nf[1];
$cod_produtor = $aux_nf[2];
$data_nf_print_2 = date('d/m/Y', strtotime($aux_nf[4]));		
$numero_nf_print_2 = $aux_nf[3];
$valor_unitario_print_2 = number_format($aux_nf[5],2,",",".");
$valor_total_print_2 = number_format($aux_nf[8],2,",",".");
$unidade_print_2 = $aux_nf[6];
$quantidade_print_2 = number_format($aux_nf[7],0,",",".");
$observacao_print_2 = $aux_nf[9];


	$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' 
	AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
	$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	$aux_romaneio = mysqli_fetch_row($busca_romaneio);
	
	// ====== DADOS DO ROMANEIO ============================================================================
	$num_romaneio_print = $aux_romaneio[1];
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


// ====== BUSCA PESSOA FORNECEDOR ===================================================================================
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


// ====== BUSCA PESSOA PRODUTOR NF =======================================================================
$busca_produtor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$cod_produtor' AND estado_registro!='EXCLUIDO'");
$aux_produtor = mysqli_fetch_row($busca_produtor);
$linhas_produtor = mysqli_num_rows ($busca_produtor);

$produtor_print = $aux_produtor[1];
$codigo_produtor = $aux_produtor[35];
$cidade_produtor = $aux_produtor[10];
$estado_produtor = $aux_produtor[12];
$telefone_produtor = $aux_produtor[14];
if ($aux_produtor[2] == "pf")
{$cpf_cnpj_produtor = $aux_produtor[3];}
else
{$cpf_cnpj_produtor = $aux_produtor[4];}
// ======================================================================================================


// ====== BUSCA ENTRADA =================================================================================
$busca_entrada = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio' ORDER BY codigo");
$aux_busca_entrada = mysqli_fetch_row($busca_entrada);
$linha_entrada = mysqli_num_rows ($busca_entrada);

if ($linha_entrada == 0)
{$num_registro_entrada = "(Romaneio ainda n&atilde;o vinculado a ficha)";}
else
{$num_registro_entrada = $aux_busca_entrada[1];}
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



// ====== RELATORIO ========================================================================================
	if ($situacao_romaneio == "EM_ABERTO")
	{echo "<tr class='tabela_3' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	elseif ($situacao_romaneio == "PRE_ROMANEIO")
	{echo "<tr class='tabela_2' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	else
	{echo "<tr class='tabela_1' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print $unidade_print &#13; Desconto Sacaria: $desconto_sacaria_print $unidade_print &#13; Outros Descontos: $desconto_print $unidade_print &#13; Peso Final: $peso_final_print $unidade_print &#13; Peso L&iacute;quido: $quantidade_print $unidade_print &#13; Status romaneio: $situacao_print &#13; Quant. Sacaria: $quant_sacaria &#13; Tipo Sacaria: $descrisao_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Filial Origem: $filial_origem &#13; Observa&ccedil;&atilde;o: $observacao &#13; $dados_cadastro'>";}
	
	echo "
	<td width='90px' align='center'>$data_nf_print_2</td>
	<td width='220px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$produtor_print</div></td>
	<td width='220px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>
	<td width='70px' align='center'>$numero_nf_print_2</td>
	<td width='70px' align='center'>$num_romaneio_print</td>
	<td width='130px' align='center'>$produto_print</td>
	<td width='90px' align='center'><div style='height:14px; overflow:hidden'>$quantidade_print_2 $unidade_print_2</div></td>
	<td width='90px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$valor_unitario_print_2</div></td>
	<td width='110px' align='right'><div style='height:14px; margin-right:7px; overflow:hidden'>$valor_total_print_2</div></td>";

// ====== BOTAO VISUALIZAR ===================================================================================================
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/saida/romaneio_visualizar.php' method='post' target='_blank'>
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
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_buscar.png' height='20px' border='0' />
		</form>	
		</td>";
// =================================================================================================================

	


	echo "</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_nf == 0 and $botao == "BUSCAR")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma nota fiscal encontrada.</i></div>";}
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
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->




</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>