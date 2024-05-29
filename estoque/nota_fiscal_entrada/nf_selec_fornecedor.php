<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "nf_selec_fornecedor";
$titulo = "Relat&oacute;rio de Notas Fiscais de Entrada";
$modulo = "estoque";
$menu = "relatorios";


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$titulo_mae = $_POST["titulo_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('d/m/Y');
$nome_form = $_POST["nome_form"];
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = Helpers::ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = Helpers::ConverteData($_POST["data_final_busca"]);
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$seleciona_pessoa = $_POST["seleciona_pessoa"];
$filial_busca = $_POST["filial_busca"];
// ========================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if (!empty($nome_form))
{
include ("../../includes/conecta_bd.php");

$busca_pessoa_geral = mysqli_query ($conexao,
"SELECT
	codigo,
	nome,
	tipo,
	cpf,
	cnpj,
	cidade,
	estado,
	telefone_1,
	codigo_pessoa
FROM
	cadastro_pessoa
WHERE
	estado_registro='ATIVO' AND
	nome LIKE '%$nome_form%'
ORDER BY
	nome");

include ("../../includes/desconecta_bd.php");

$linha_pessoa_geral = mysqli_num_rows ($busca_pessoa_geral);
}
// ========================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo $titulo; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_estoque.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_estoque_relatorios.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right">
	<?php 
    if ($linha_pessoa_geral == 1)
    {echo "$linha_pessoa_geral Cadastro";}
    elseif ($linha_pessoa_geral > 1)
    {echo "$linha_pessoa_geral Cadastros";}
    else
    {echo "";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	Selecione um Fornecedor
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">


<!-- ============================================================================================================= -->
<div class="pqa_caixa">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/nota_fiscal_entrada/nf_selec_fornecedor.php" method="post" />
<input type="hidden" name="botao" value="SELECIONAR" />
<input type="hidden" name="pagina_mae" value="<?php echo"$pagina_mae"; ?>" />
<input type="hidden" name="titulo_mae" value="<?php echo"$titulo_mae"; ?>" />
<input type="hidden" name="fornecedor_pesquisa" value="<?php echo"$fornecedor_pesquisa"; ?>" />
<input type="hidden" name="nome_fornecedor" value="<?php echo"$nome_fornecedor"; ?>" />
<input type="hidden" name="data_inicial_busca" value="<?php echo"$data_inicial_br"; ?>" />
<input type="hidden" name="data_final_busca" value="<?php echo"$data_final_br"; ?>" />
<input type="hidden" name="cod_produto_busca" value="<?php echo"$cod_produto_busca"; ?>" />
<input type="hidden" name="seleciona_pessoa" value="<?php echo"$seleciona_pessoa"; ?>" />
<input type="hidden" name="filial_busca" value="<?php echo"$filial_busca"; ?>" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    Nome:
    </div>
    
    <div class="pqa_campo">
    <input type="text" name="nome_form" class="pqa_input" id="ok" value="<?php echo $nome_form; ?>" style="width:400px" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div class="pqa_caixa">
    <div class="pqa_rotulo">
    </div>

    <div class="pqa_campo">
    <button type='submit' class='botao_1' style='float:left'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->

    
</div>
<!-- ====== FIM DIV PQA ============================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:20px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_pessoa_geral == 0)
{echo "
<div class='espacamento' style='height:200px'>
<div class='espacamento' style='height:30px'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento' style='height:10px'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='150px'>C&oacute;digo</td>
<td width='450px'>Nome</td>
<td width='200px'>CPF/CNPJ</td>
<td width='200px'>Telefone</td>
<td width='300px'>Cidade/UF</td>
</tr>
</table>";}


echo "<table class='tabela_geral'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pessoa_geral ; $x++)
{
$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

// ====== DADOS DO CADASTRO ============================================================================
$codigo_w = $aux_pessoa_geral[0];
$nome_pessoa_w = $aux_pessoa_geral[1];
$tipo_pessoa_w = $aux_pessoa_geral[2];
$cpf_pessoa_w = $aux_pessoa_geral[3];
$cnpj_pessoa_w = $aux_pessoa_geral[4];
$cidade_pessoa_w = $aux_pessoa_geral[5];
$estado_pessoa_w = $aux_pessoa_geral[6];
$telefone_pessoa_w = $aux_pessoa_geral[7];
$codigo_pessoa_w = $aux_pessoa_geral[8];


if ($tipo_pessoa_w == "PF" or $tipo_pessoa_w == "pf")
{$cpf_cnpj_w = $cpf_pessoa_w;}
else
{$cpf_cnpj_w = $cnpj_pessoa_w;}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	echo "
	<tr class='tabela_1'>
	<td width='150px' align='left'><div style='margin-left:15px'>$codigo_w</div></td>
	<td width='450px' height='24px' align='left'>
		<div style='margin-left:10px'>
		<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_entrada/$pagina_mae.php' method='post'>
		<input type='hidden' name='botao' value='BUSCAR' />
		<input type='hidden' name='fornecedor_pesquisa' value='$codigo_w' />
		<input type='hidden' name='nome_fornecedor' value='$nome_pessoa_w' />
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
		<input type='hidden' name='data_final_busca' value='$data_final_br' />
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca' />
		<input type='hidden' name='seleciona_pessoa' value='$seleciona_pessoa' />
		<input type='hidden' name='filial_busca' value='$filial_busca' />
		<input class='tabela_1' type='submit' style='width:430px; height:22px; text-align:left; border:0px solid #000; background-color:transparent' value='$nome_pessoa_w'>
		</form>
		</div>
	</td>
	<td width='200px' align='center'>$cpf_cnpj_w</td>
	<td width='200px' align='center'>$telefone_pessoa_w</td>
	<td width='300px' align='center'>$cidade_pessoa_w/$estado_pessoa_w</td>
	</tr>";

}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
if ($linha_pessoa_geral == 0 and $botao == "SELECIONAR")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro encontrado.</i></div>";}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>