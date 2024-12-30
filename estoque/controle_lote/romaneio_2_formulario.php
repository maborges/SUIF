<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'romaneio_2_formulario';
$titulo = 'Movimenta&ccedil;&atilde;o de Estoque - Romaneio';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$numero_romaneio_form = $_POST["numero_romaneio_form"];
$numero_movimentacao = $_POST["numero_movimentacao"];
$data_hoje = date('d/m/Y');
$lote_form = $_POST["lote_form"];
$tipo_prod_form = $_POST["tipo_prod_form"];
$umidade_form = $_POST["umidade_form"];
$densidade_form = $_POST["densidade_form"];
$impureza_form = $_POST["impureza_form"];
$broca_form = $_POST["broca_form"];
$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND numero_romaneio='$numero_romaneio_form' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);

for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);
}

$fornecedor = $aux_romaneio[2];
$data = $aux_romaneio[3];
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
$unidade_print = $aux_romaneio[11];
$t_sacaria = $aux_romaneio[12];
$movimentacao = $aux_romaneio[13];
$situacao = $aux_romaneio[14];
$sit_romaneio = $aux_romaneio[15];
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
$cod_lote = $aux_romaneio[48];
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
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


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];

if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}

if ($linhas_fornecedor == 0)
{$cidade_uf_fornecedor = "";}
else
{$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";}
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($numero_romaneio_form == "" or $linha_romaneio == 0)
{$erro = 1;
$msg = "<div style='color:#FF0000'>Informe o n&uacute;mero do romaneio</div>";}
else
{$erro = 0;
$msg = "";}
// ======================================================================================================


// =============================================================================
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
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    <?php echo "$titulo $numero_romaneio_form"; ?>
    </div>


	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    N&ordm; <?php echo"$numero_movimentacao"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
    <?php echo"$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:150px; width:1080px; border:0px solid #0000FF; margin:auto">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/romaneio_3_enviar.php" method="post">
<input type="hidden" name="botao" value="NOVA_MOVIMENTACAO" />
<input type="hidden" name="cod_produto_form" value="<?php echo"$cod_produto"; ?>" />
<input type="hidden" name="numero_movimentacao" value="<?php echo "$numero_movimentacao"; ?>" />
<input type="hidden" name="movimentacao_form" value="<?php echo "$movimentacao"; ?>" />
<input type="hidden" name="peso_form" value="<?php echo "$quantidade"; ?>" />
<input type="hidden" name="numero_romaneio_form" value="<?php echo "$numero_romaneio_form"; ?>" />
<input type="hidden" name="fornecedor_form" value="<?php echo "$fornecedor"; ?>" />


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:0px">
	<div style="margin-top:0px; margin-left:55px; color:#003466">Fornecedor</div></div>
		<div id="centro" style="width:1030px; height:50px; border:1px solid #999; color:#003466; border-radius:0px; overflow:hidden; margin-left:25px; background-color:#EEE">

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>
			
			<div style="width:670px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Nome:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div></div>
			<div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; color:#003466">
			<div style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div></div>

			<div style="width:1025px; height:5px; border:0px solid #000; float:left; font-size:12px"></div>

			<div style="width:670px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Cidade:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$cidade_uf_fornecedor</b>" ?></div></div>
			<div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; color:#003466">
			<div style="margin-top:3px; margin-left:5px; float:left">Telefone:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div></div>

		</div>



<!-- ================================================= PRODUTO ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:8px">
	<div style="margin-top:0px; margin-left:55px; color:#003466">Produto</div></div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:14px; margin-left:25px; color:#003466">
        <div style="margin-top:7px; margin-left:5px; float:left"><?php echo"<b>$produto_print_2</b>" ?></div></div>
    </div>
<!--
    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>

    <div style="width:240px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:21px; background-color:#EEE; float:left">
        <div style="width:300px; height:15px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; color:#003466">
        <div style="margin-top:9px; margin-left:5px; float:left"><?php // echo"<b>$produto_print</b>" ?></div></div>
    </div>
-->

</div>
<!-- ============================================================================================================= -->


<!-- ======================================= FORMULARIO ========================================================== -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Lote:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Movimenta&ccedil;&atilde;o:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Peso (Kg):</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Tipo:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Umidade:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Impureza:</div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="lote_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
    <?php
	$busca_lote_list = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE estado_registro='ATIVO' AND cod_produto='$cod_produto' ORDER BY nome_lote");
	$linhas_lote_list = mysqli_num_rows ($busca_lote_list);

	for ($l=1 ; $l<=$linhas_lote_list ; $l++)
	{
		$aux_lote_list = mysqli_fetch_row ($busca_lote_list);	
		if ($aux_lote_list[1] == $lote_form)
		{echo "<option selected='selected' value='$aux_lote_list[1]'>$aux_lote_list[2]</option>";}
		else
		{echo "<option value='$aux_lote_list[1]'>$aux_lote_list[2]</option>";}
	}
    ?>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
	<input type="text" name="movimentacao_form_disabled" class="form_input" maxlength="11" 
    onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:150px; text-align:left" value="<?php echo" $movimentacao"; ?>" disabled="disabled" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
	<input type="text" name="peso_form_disabled" class="form_input" maxlength="11" 
    onkeydown="if (getKey(event) == 13) return false;" style="background-color:#EEE; width:150px; text-align:left" value="<?php echo" $quantidade_print"; ?>" disabled="disabled" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="tipo_prod_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
    <?php
	$busca_tipo_prod_list = mysqli_query ($conexao, "SELECT * FROM cad_tipo_producao WHERE estado_registro='ATIVO' AND cod_produto='$cod_produto' ORDER BY nome_tipo");
	$linhas_tipo_prod_list = mysqli_num_rows ($busca_tipo_prod_list);

	for ($t=1 ; $t<=$linhas_tipo_prod_list ; $t++)
	{
		$aux_tipo_prod_list = mysqli_fetch_row ($busca_tipo_prod_list);	
		if ($aux_tipo_prod_list[1] == $tipo_prod_form)
		{echo "<option selected='selected' value='$aux_tipo_prod_list[1]'>$aux_tipo_prod_list[2]</option>";}
		else
		{echo "<option value='$aux_tipo_prod_list[1]'>$aux_tipo_prod_list[2]</option>";}
	}
    ?>
    </select>
    </div>


	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="umidade_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
	<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
		if ($aux_porcentagem[1] == $umidade_form)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="impureza_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
	<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
		if ($aux_porcentagem[1] == $impureza_form)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>

</div>
<!-- ============================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ======================================= FORMULARIO ========================================================== -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Densidade:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Broca:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- Quantidade de Bag: --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="densidade_form" class="form_input" maxlength="11" onkeypress="mascara(this,m_quantidade)"
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$densidade_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="broca_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
	<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
		if ($aux_porcentagem[1] == $broca_form)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
    }
    ?>
    </select>
    </div>

<!--
	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="quant_bag_form" class="form_input" maxlength="11" onkeypress="mascara(this,m_quantidade)"  
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php // echo"$quant_bag_form"; ?>" />
    </div>
-->

</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_25"></div>




<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	
	<?php
	if ($erro == 1)
	{echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/estoque/controle_lote/romaneio_1_selec_produto.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
	</div>";}
	
	else
	{echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/controle_lote/index_controle_lote.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</a>
	</div>";}

	?>
</div>














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