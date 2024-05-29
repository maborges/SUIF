<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "pendentes_selec_fornecedor";
$titulo = "Romaneios Pendentes";
$modulo = "estoque";
$menu = "estoque";
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"] ?? '';
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"] ?? 11;
$nome_form = $_POST["nome_form"] ?? 11;
$data_hoje = date('d/m/Y');

$produto_print_2 = $_POST['produto_print_2'] ?? '';
// ========================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($nome_form != "")
{
include ("../../includes/conecta_bd.php");
$busca_pessoa_geral = mysqli_query ($conexao, "SELECT codigo, nome, tipo, cpf, cnpj, cidade, estado, telefone_1 FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome_form%' ORDER BY nome");
include ("../../includes/desconecta_bd.php");

$linha_pessoa_geral = mysqli_num_rows ($busca_pessoa_geral);
}

else
{
$busca_pessoa_geral = 0;
$linha_pessoa_geral = 0;
}
// ========================================================================================================


// ================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
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
<?php include ("../../includes/submenu_estoque_estoque.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
    <?php echo "$produto_print_2"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	Selecione um Favorecido
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa" style="height:63px">


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/relatorios/pendentes_selec_fornecedor.php" method="post" />
<input type="hidden" name="botao" value="SELECIONAR" />
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:380px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:378px; height:17px; border:1px solid transparent; float:left">
    Nome:
    </div>
    
    <div style="width:378px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_form" class="form_input" id="ok" style="width:350px; text-align:left; padding-left:5px" value="<?php echo"$nome_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:190px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:188px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:188px; height:25px; float:left; border:1px solid transparent">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->

    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
if ($linha_pessoa_geral == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='450px'>Nome</td>
<td width='200px'>CPF/CNPJ</td>
<td width='200px'>Telefone</td>
<td width='300px'>Cidade/UF</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_pessoa_geral ; $x++)
{
$aux_pessoa_geral = mysqli_fetch_row($busca_pessoa_geral);

// ====== DADOS DO CADASTRO ============================================================================
$codigo_pessoa_w = $aux_pessoa_geral[0];
$nome_pessoa_w = $aux_pessoa_geral[1];
$tipo_pessoa_w = $aux_pessoa_geral[2];
$cpf_pessoa_w = $aux_pessoa_geral[3];
$cnpj_pessoa_w = $aux_pessoa_geral[4];
$cidade_pessoa_w = $aux_pessoa_geral[5];
$estado_pessoa_w = $aux_pessoa_geral[6];
$telefone_pessoa_w = $aux_pessoa_geral[7];
if ($tipo_pessoa_w == "PF" or $tipo_pessoa_w == "pf")
{$cpf_cnpj_w = $cpf_pessoa_w;}
else
{$cpf_cnpj_w = $cnpj_pessoa_w;}
// ======================================================================================================


// ====== RELATORIO ========================================================================================
	echo "
	<tr class='tabela_1'>
	<td width='450px' height='24px' align='left'>
		<div style='margin-left:10px'>
		<form action='$servidor/$diretorio_servidor/estoque/relatorios/romaneios_pendentes.php' method='post'>
		<input type='hidden' name='botao' value='FORMULARIO' />
		<input type='hidden' name='fornecedor_pesquisa' value='$codigo_pessoa_w' />
		<input type='hidden' name='nome_fornecedor' value='$nome_pessoa_w' />
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
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro encontrado.</i></div>";}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT_RELATORIO ========================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->


</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>