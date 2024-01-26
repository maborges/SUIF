<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_relatorio_fornecedor_seleciona';
$titulo = 'Estoque - Relat&oacute;rio de Sa&iacute;das';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$fornecedor_busca = $_POST["fornecedor_busca"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$nome_busca = $_POST["nome_busca"];
// ========================================================================================================


// ===== BUSCA CADASTRO PESSOAS =============================================================================================
if ($nome_busca != "")
{
$busca_pessoa_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' AND nome LIKE '%$nome_busca%' ORDER BY nome");
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
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Estoque - Relat&oacute;rio de Sa&iacute;das
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	Selecione um fornecedor
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<a href="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/relatorios.php">&#8226; Outros relat&oacute;rios de Sa&iacute;das</a>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/saida_relatorio_fornecedor_seleciona.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR_PESSOA' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Nome:</div>

	<div style="height:34px; width:400px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="nome_busca" id="ok" maxlength="50" style="width:395px" value="<?php echo"$nome_busca"; ?>" />
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
		<form action='$servidor/$diretorio_servidor/estoque/saida/saida_relatorio_fornecedor.php' method='post'>
		<input type='hidden' name='fornecedor_busca' value='$aux_pessoa[0]'>
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
if ($linha_pessoa_geral == 0 and ($nome_busca != '' or $cpf != ''))
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