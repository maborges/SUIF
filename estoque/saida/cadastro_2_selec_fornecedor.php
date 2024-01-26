<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_2_selec_fornecedor';
$titulo = 'Novo Romaneio de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$nome_form = $_POST["nome_form"];
$data_hoje = date('d/m/Y');
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
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_estoque.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_estoque_saida.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Novo Romaneio de Sa&iacute;da
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
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/cadastro_2_selec_fornecedor.php" method="post" />
    <input type="hidden" name="botao" value="SELECIONAR" />
	<input type="hidden" name="cod_produto_form" value="<?php echo"$cod_produto_form"; ?>" />
    
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
		<form action='$servidor/$diretorio_servidor/estoque/saida/cadastro_3_formulario.php' method='post'>
		<input type='hidden' name='botao' value='FORMULARIO' />
		<input type='hidden' name='fornecedor_form' value='$codigo_pessoa_w' />
		<input type='hidden' name='nome_fornecedor' value='$nome_pessoa_w' />
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
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