<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'editar_2_selec_fornecedor';
$titulo = 'Editar Romaneio de Entrada';
$modulo = 'estoque';
$menu = 'entrada';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$numero_romaneio = $_POST["numero_romaneio"];
$numero_compra = $_POST["numero_compra"] ?? '';
$botao = $_POST["botao"];

$fornecedor_form = $_POST["fornecedor_form"] ?? '';
$cod_produto_form = $_POST["cod_produto_form"] ?? '';
$nome_form = $_POST["nome_form"] ?? '';
$data_hoje = date('d/m/Y');

$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$data_inicial_busca = $_POST["data_inicial_busca"] ?? '';
$data_final_busca = $_POST["data_final_busca"] ?? '';
$fornecedor_busca = $_POST["fornecedor_busca"] ?? '';
$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
$numero_romaneio_busca = $_POST["numero_romaneio_busca"] ?? '';
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"] ?? '';
$forma_pesagem_busca = $_POST["forma_pesagem_busca"] ?? '' ;
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($nome_form != "")
{
$busca_pessoa_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome_form%' ORDER BY nome");
$linha_pessoa_geral = mysqli_num_rows ($busca_pessoa_geral);
}
else
{
$busca_pessoa_geral = 0;
$linha_pessoa_geral = 0;
}
// ========================================================================================================


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
<?php include ('../../includes/submenu_estoque_entrada.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Novo Romaneio de Entrada
    </div>

	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; margin-top:0px; border:0px solid #000">
    <?php echo"$produto_print_2"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Selecione um fornecedor
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/editar_2_selec_fornecedor.php" method="post" />
    <input type="hidden" name="botao" value="<?php echo"$botao"; ?>" />
    <input type="hidden" name="numero_romaneio" value="<?php echo"$numero_romaneio"; ?>" />
	<input type="hidden" name="cod_produto_form" value="<?php echo"$cod_produto_form"; ?>" />
	<input type="hidden" name="pagina_mae" value="<?php echo"$pagina_mae"; ?>" />
	<input type="hidden" name="pagina_filha" value="<?php echo"$pagina_filha"; ?>" />
	<input type="hidden" name="numero_romaneio" value="<?php echo"$numero_romaneio"; ?>" />
	<input type="hidden" name="data_inicial_busca" value="<?php echo"$data_inicial_busca"; ?>" />
	<input type="hidden" name="data_final_busca" value="<?php echo"$data_final_busca"; ?>" />
	<input type="hidden" name="cod_produto_busca" value="<?php echo"$cod_produto_busca"; ?>" />
	<input type="hidden" name="fornecedor_busca" value="<?php echo"$fornecedor_busca"; ?>" />
	<input type="hidden" name="numero_romaneio_busca" value="<?php echo"$numero_romaneio_busca"; ?>" />
	<input type="hidden" name="situacao_romaneio_busca" value="<?php echo"$situacao_romaneio_busca"; ?>" />
	<input type="hidden" name="forma_pesagem_busca" value="<?php echo"$forma_pesagem_busca"; ?>" />
    
	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Nome:</div>

	<div style="height:34px; width:400px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="nome_form" id="ok" maxlength="50" style="width:395px" value="<?php echo"$nome_form"; ?>" />
	</div>

	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>
	
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->





<?php
// ======================================================================================================
if ($linha_pessoa_geral == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:242px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:5px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_pessoa_geral == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:11px'>
<tr>
<td width='450px' height='24px' align='center' bgcolor='#006699'>Nome</td>
<td width='200px' align='center' bgcolor='#006699'>CPF/CNPJ</td>
<td width='200px' align='center' bgcolor='#006699'>Telefone</td>
<td width='300px' align='center' bgcolor='#006699'>Cidade/UF</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pessoa_geral ; $x++)
{
$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

// ====== DADOS DO CONTRATO ============================================================================
$fornecedor_x = $aux_pessoa_geral[0];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_x' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print_x = $aux_pessoa[1];
$codigo_pessoa_x = $aux_pessoa[35];
$cidade_fornecedor_x = $aux_pessoa[10];
$estado_fornecedor_x = $aux_pessoa[12];
$telefone_fornecedor_x = $aux_pessoa[14];
if ($aux_pessoa[2] == "pf")
{$cpf_cnpj_x = $aux_pessoa[3];}
else
{$cpf_cnpj_x = $aux_pessoa[4];}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	echo "
	<tr class='tabela_1'>
	<td width='450px' height='24px' align='left'>
		<div style='margin-left:10px'>
		<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
		<input type='hidden' name='botao' value='$botao' />
		<input type='hidden' name='fornecedor_form' value='$aux_pessoa[0]' />
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio' />
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
		<input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>

		<input class='tabela_1' type='submit' style='width:430px; height:22px; text-align:left; border:0px solid #000; background-color:transparent' value='$fornecedor_print_x'>
		</form>
		</div>
	</td>
	<td width='200px' align='center'>$cpf_cnpj_x</td>
	<td width='200px' align='center'>$telefone_fornecedor_x</td>
	<td width='300px' align='center'>$cidade_fornecedor_x/$estado_fornecedor_x</td>
	</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_pessoa_geral == 0 and ($nome_form != '' or $cpf != ''))
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhum fornecedor encontrado.</i></div>";}
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
<div class="espacamento_30"></div>
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