<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'classificacao_qualidade_2';
$titulo = 'Classifica&ccedil;&atilde;o da Qualidade';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$mes_atras = date ('Y-m-d', strtotime('-30 days'));

$filial = $filial_usuario;
$filial_origem = $_POST["filial_origem"];
$romaneio_classificado = $_POST["romaneio_classificado"];
$botao = $_POST["botao"];
$botao_class = $_POST["botao_class"];
$numero_romaneio_form = $_POST["numero_romaneio_form"];
$quantidade_desconto = $_POST["quantidade_desconto"];
$quantidade_desconto_aux = $_POST["quantidade_desconto"];

if ($botao == "1" or $botao_class == "SIM")
{
	$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);
}
else
{
	$data_inicial_aux = $data_hoje_aux;
	$data_inicial = $data_hoje;
	$data_final_aux = $data_hoje_aux;
	$data_final = $data_hoje;
}


if ($_POST["monstra_situacao"] == "")
{$monstra_situacao = "TODOS";}
else
{$monstra_situacao = $_POST["monstra_situacao"];}
// ================================================================================================================


// =================================================================================================================
if ($botao_class == "SIM")
{
$alterar = mysqli_query ($conexao, "UPDATE estoque SET classificacao='SIM', quant_quebra_previsto='$quantidade_desconto', usuario_alteracao='$usuario_alteracao', hora_alteracao='$hora_alteracao', data_alteracao='$data_alteracao' WHERE numero_romaneio='$numero_romaneio_form'");
}
else
{}
// =================================================================================================================


// =================================================================================================================
if ($romaneio_classificado == "SIM")
{
	// TODOS  =================
	if ($filial_origem == "")
	{	
		$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND fornecedor!='100' AND data>='$data_inicial' AND data<='$data_final' AND filial='$filial' AND classificacao='SIM' ORDER BY codigo");
		$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	}
	
	// FILTRO POR FILIAL  ========
	else
	{	
		$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND fornecedor!='100' AND data>='$data_inicial' AND data<='$data_final' AND filial='$filial' AND filial_origem='$filial_origem' AND classificacao='SIM' ORDER BY codigo");
		$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	}
}	

// ================================================================================================================
else
{
	// TODOS  =================
	if ($filial_origem == "")
	{	
		$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND fornecedor!='100' AND data>='$data_inicial' AND data<='$data_final' AND filial='$filial' ORDER BY codigo");
		$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	}
	
	// FILTRO POR FILIAL  ========
	else
	{	
		$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND fornecedor!='100' AND data>='$data_inicial' AND data<='$data_final' AND filial='$filial' AND filial_origem='$filial_origem' ORDER BY codigo");
		$linha_romaneio = mysqli_num_rows ($busca_romaneio);
	}
}	
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
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Classifica&ccedil;&atilde;o da Qualidade
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/movimentacao/classificacao_qualidade_2.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Data inicial:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:80px; text-align:center" value="<?php echo"$data_inicial_br"; ?>" />
	</div>

	<div class="pqa_rotulo" style="height:20px; width:85px; border:0px solid #000">Data final:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:80px; text-align:center" value="<?php echo"$data_final_br"; ?>" />
	</div>

    <div class="pqa_rotulo" style="height:20px; width:90px; border:0px solid #000">Produto:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_produto_pesq" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option value="TODOS">(TODOS)</option>
    <?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_pesq)
		{
		echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
		else
		{
		echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		}
	}
    ?>
    </select>
    </div>

    <div class="pqa_rotulo" style="height:20px; width:80px; border:0px solid #000">Filial Origem:</div>

	<div style="height:34px; width:140px; border:0px solid #999; float:left">
    <select class="pqa_select" name="filial_origem_pesq" onkeydown="if (getKey(event) == 13) return false;" style="width:135px" />
    <option value="">(TODAS AS FILIAIS)</option>
    <?php
        $busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
        $linhas_filial_origem = mysqli_num_rows ($busca_filial_origem);
    
    for ($f=1 ; $f<=$linhas_filial_origem ; $f++)
    {
    $aux_filial_origem = mysqli_fetch_row($busca_filial_origem);
        if ($filial_origem_pesq == $aux_filial_origem[1])
        {echo "<option selected='selected' value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
        else
        {echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
    }
    ?>
    </select>
	</div>

    <div class="pqa_rotulo" style="height:20px; width:150px; border:0px solid #000">Situa&ccedil;&atilde;o do Romaneio:</div>

	<div style="height:34px; width:115px; border:0px solid #999; float:left">
    <select class="pqa_select" name="classificacao_romaneio_pesq" onkeydown="if (getKey(event) == 13) return false;" style="width:110px" />
    <?php
    if ($classificacao_romaneio_pesq == "GERAL")
    {echo "<option value='GERAL' selected='selected'>(GERAL)</option>";}
    else
    {echo "<option value='GERAL'>(GERAL)</option>";}

    if ($classificacao_romaneio_pesq == "SIM")
    {echo "<option value='SIM' selected='selected'>Classificado</option>";}
    else
    {echo "<option value='SIM'>Classificado</option>";}

    if ($classificacao_romaneio_pesq == "NAO")
    {echo "<option value='NAO' selected='selected'>N&atilde;o Classificado</option>";}
    else
    {echo "<option value='NAO'>N&atilde;o Classificado</option>";}
    ?>
    </select>
	</div>


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
	<?php
  if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='data_inicial' value='$data_inicial_br'>
	<input type='hidden' name='data_final' value='$data_final_br'>
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='numero_romaneio_form' value='$numero_romaneio_form'>
	<input type='hidden' name='situacao_romaneio_form' value='$situacao_romaneio_form'>
	<input type='hidden' name='forma_pesagem_form' value='$forma_pesagem_form'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php 
        if ($linha_romaneio == 0)
        {}
        elseif ($linha_romaneio == 1)
        {echo"$linha_romaneio Romaneio";}
        else
        {echo"$linha_romaneio Romaneios";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_romaneio >= 1)
        {echo"Total de Entrada: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
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
<td width='80px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='300px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='60px' align='center' bgcolor='#006699'>N&ordm;</td>
<td width='130px' align='center' bgcolor='#006699'>Produto</td>
<td width='120px' align='center' bgcolor='#006699'>Filial Origem</td>
<td width='120px' align='center' bgcolor='#006699'>Quantidade (Kg)</td>
<td width='120px' align='center' bgcolor='#006699'>Desconto Realizado (Kg)</td>
<td width='120px' align='center' bgcolor='#006699'>Desconto Previsto (Kg)</td>
<td width='64px' align='center' bgcolor='#006699'>Classificar</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ======================================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO ROMANEIO ============================================================================
$num_romaneio_print = $aux_romaneio[1];
$produto = $aux_romaneio[4];
$cod_produto = $aux_romaneio[44];
$data = $aux_romaneio[3];
$data_print = date('d/m/Y', strtotime($aux_romaneio[3]));
$unidade = $aux_romaneio[11];
$unidade_print = "Kg";
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
$filial_origem_print = $aux_romaneio[34];
$classificacao = $aux_romaneio[35];
$desconto_realizado = $aux_romaneio[37];
$desconto_previsto = $aux_romaneio[36];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$quant_kg_saca = $aux_bp[27];
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




// BUSCA NUMERO DE ROMANEIO  ==========================================================================================
$busca_num_romaneio = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND movimentacao='ENTRADA' AND numero_romaneio='$aux_romaneio[1]'");
$achou_num_romaneio = mysqli_num_rows ($busca_num_romaneio);
// =================================================================================================================


// SITUAÇÃO PRINT  ==========================================================================================
if ($achou_num_romaneio >= 1)
{$situacao_print = "Baixado";}
else
{$situacao_print = "Pendente";}
// =================================================================================================================



// RELATORIO =======================================================================================================
if ($situacao == "ENTRADA_DIRETA")
{}

else
{
	
	if ($classificacao == "SIM")
	{echo "<tr class='tabela_3' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	elseif ($situacao_romaneio != "FECHADO")
	{echo "<tr class='tabela_3' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	else
	{echo "<tr class='tabela_3' title=' CPF/CNPJ: $cpf_cnpj &#13; Peso Inicial: $peso_inicial_print Kg &#13; Desconto Sacaria: $desconto_sacaria_print Kg &#13; Outros Descontos: $desconto_print Kg &#13; Peso Final: $peso_final_print Kg &#13; Peso L&iacute;quido: $quantidade_print Kg &#13; Tipo Sacaria: $tipo_sacaria &#13; Placa Ve&iacute;culo: $placa_veiculo &#13; Motorista: $motorista &#13; Observa&ccedil;&atilde;o: $observacao'>";}
	
	echo "
	<td width='80px' align='left'>&#160;$data_print</td>";
	if ($situacao == "ENTRADA_DIRETA")
	{echo "<td width='300px' align='left'>&#160;$fornecedor_print (BP*)</td>";}
	else
	{echo "<td width='300px' align='left'>&#160;$fornecedor_print</td>";}	
	echo "
	<td width='60px' align='center'>$num_romaneio_print</td>
	<td width='130px' align='center'>$produto_print</td>
	<td width='120px' align='center'>$filial_origem_print</td>
	<td width='120px' align='center'>$quantidade_print Kg</td>
	<td width='120px' align='center'>$desconto_realizado</td>
	<td width='120px' align='center'>";
	if ($situacao_romaneio != "FECHADO")
	{echo "</td>";}
	else
	{echo "
		<form action='$servidor/$diretorio_servidor/estoque/movimentacao/classificacao_qualidade.php' method='post'>
		<input type='hidden' name='numero_romaneio_form' value='$num_romaneio_print'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao_class' value='SIM'>
		<input type='hidden' name='data_inicial' value='$data_inicial_aux'>
		<input type='hidden' name='data_final' value='$data_final_aux'>
		<input type='hidden' name='filial_origem' value='$filial_origem'>
		<input type='hidden' name='romaneio_classificado' value='$romaneio_classificado'>
		<input type='hidden' name='quant_entregar_aux' value='$quant_entregar_aux'>
		<input type='text' name='quantidade_desconto' maxlength='15' style='color:#0000FF; width:90px; font-size:10px; text-align:center' value='$desconto_previsto' onkeypress='troca(this)' /></td>";}
		
	if ($situacao_romaneio != "FECHADO")
	{echo "<td width='64px' align='center'></td>";}
	else
	{echo "<td width='64px' align='center'><input type='image' src='$servidor/$diretorio_servidor/imagens/icones/autorizar.png' border='0' /></form></td>";}

	echo "</tr>";
	
	
if ($produto == "CAFE")
{$total_cafe = $total_cafe + $quantidade;}
elseif ($produto == "PIMENTA")
{$total_pimenta = $total_pimenta + $quantidade;}
elseif ($produto == "CRAVO")
{$total_cravo = $total_cravo + $quantidade;}
elseif ($produto == "CACAU")
{$total_cacau = $total_cacau + $quantidade;}
else
{}	



}






}


$total_cafe_sacas = $total_cafe / 60;
$total_cafe_print = number_format($total_cafe_sacas,2,",",".");
$total_pimenta_print = number_format($total_pimenta,2,",",".");
$total_cravo_print = number_format($total_cravo,2,",",".");
$total_cacau_print = number_format($total_cacau,2,",",".");



// =================================================================================================================

?>
</table>

<?php
if ($linha_romaneio == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum romaneio encontrado.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->



<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto; font-size:12px; color:#666666">
<div id='centro' style='height:25px; width:30px; border:0px solid #000; margin:auto; float:left'></div>
	<?php
	if ($total_cafe == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:260px; border:0px solid #000; margin:auto; float:left'>
	Caf&eacute; Conilon: $total_cafe_print Sacas
	</div>";}

	if ($total_pimenta == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:260px; border:0px solid #000; margin:auto; float:left'>
	Pimenta do Reino: $total_pimenta_print Kg
	</div>";}

	if ($total_cacau == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:260px; border:0px solid #000; margin:auto; float:left'>
	Cacau: $total_cacau_print Kg
	</div>";}

	if ($total_cravo == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:25px; width:260px; border:0px solid #000; margin:auto; float:left'>
	Cravo da &Iacute;ndia: $total_cravo_print Kg
	</div>";}


	?>
</div>



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