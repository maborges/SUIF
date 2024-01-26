<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "compra_editar";
$titulo = "Editar Compra";
$modulo = "compras";
$menu = "compras";


// ======= RECEBENDO POST =================================================================================
$numero_compra = $_POST["numero_compra"];
$numero_compra_aux = $_POST["numero_compra_aux"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];
$botao = $_POST["botao"];
$botao_relatorio = $_POST["botao_relatorio"];
$data_inicial = $_POST["data_inicial"];
$data_final = $_POST["data_final"];
$produto_list = $_POST["produto_list"];
$produtor_ficha = $_POST["produtor_ficha"];
$monstra_situacao = $_POST["monstra_situacao"];

$filial = $filial_usuario;
// ========================================================================================================


// ====== BUSCA COMPRA ===================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra'");
$aux_bc = mysqli_fetch_row($busca_compra);
$linhas_bc = mysqli_num_rows ($busca_compra);

$fornecedor = $aux_bc[2];
$cod_produto = $aux_bc[39];
$quantidade = $aux_bc[5];
$preco_unitario = number_format($aux_bc[6],2,",",".");
$valor_total = number_format($aux_bc[7],2,",",".");
$safra = $aux_bc[9];
$cod_tipo = $aux_bc[41];
$umidade = $aux_bc[12];
$broca = $aux_bc[11];
$impureza = $aux_bc[43];
if ($aux_bc[14] == "" or $aux_bc[14] == "0000-00-00")
{$data_pagamento = "";}
else
{$data_pagamento = date('d/m/Y', strtotime($aux_bc[14]));}
$observacao = $aux_bc[13];
$usuario_cadastro = $aux_bc[18];
$data_cadastro = date('d/m/Y', strtotime($aux_bc[20]));
$hora_cadastro = $aux_bc[19];
$tipo_registro = $aux_bc[16];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
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
if ($aux_forn[2] == "pf" or $aux_forn[2] == "PF")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// =============================================================================
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
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:460px; width:950px; border:0px solid #000; margin:auto">
<form name="compra" action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/compra_editar_enviar.php" method="post" />
<input type="hidden" name="botao" value="compra_editar" />
<input type="hidden" name="numero_compra" value="<?php echo "$numero_compra"; ?>" />
<input type="hidden" name="numero_compra_aux" value="<?php echo "$numero_compra_aux"; ?>" />
<input type="hidden" name="pagina_mae" value="<?php echo "$pagina_mae"; ?>" />
<input type="hidden" name="data_inicial" value="<?php echo "$data_inicial"; ?>" />
<input type="hidden" name="data_final" value="<?php echo "$data_final"; ?>" />
<input type="hidden" name="cod_tipo_anterior" value="<?php echo "$cod_tipo"; ?>" />
<input type="hidden" name="quantidade" value="<?php echo "$quantidade"; ?>" />
<input type="hidden" name="fornecedor" value="<?php echo "$fornecedor"; ?>" />
<input type="hidden" name="fornecedor_print" value="<?php echo "$fornecedor_print"; ?>" />
<input type="hidden" name="cod_produto" value="<?php echo "$cod_produto"; ?>" />
<input type="hidden" name="produto_print" value="<?php echo "$produto_print"; ?>" />
<input type="hidden" name="unidade_print" value="<?php echo "$unidade_print"; ?>" />

<div style="width:950px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:140px; font-size:22px; color:#090">
	Edi&ccedil;&atilde;o de Registro
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_2" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:140px; font-size:11px; color:#003466">
    <?php echo "$tipo_registro - $produto_print"; ?>
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:140px; height:360px; border:0px solid #000; float:left">
</div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>N&uacute;mero:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:600px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:595px; height:5px; border:0px solid #000"></div>Fornecedor:</div>


<!-- =========================================  CODIGO ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="numero_compra_aux" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px" value="<?php echo "$numero_compra"; ?>" disabled="disabled" />
</div>

<!-- =========================================  FORNECEDOR ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:525px; border:0px solid #000">
<!-- ========================================================================================================== -->
<input type="text" name="fornecedor_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; font-weight:bold; width:500px" value="<?php echo "$fornecedor_print"; ?>" disabled="disabled" />

</div>
<!-- ========================================================================================================== -->




<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>
<?php
echo"Quantidade ($unidade_print):";
?>
</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Pre&ccedil;o:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Safra:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Tipo:</div>

<!-- =========================================  QUANTIDADE ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="quantidade" id="ok" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$quantidade"; ?>" disabled="disabled" /></div>

<!-- =========================================  PREÇO UNITARIO ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="preco_unitario" maxlength="15" onkeypress="mascara(this,mvalor)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$preco_unitario"; ?>" disabled="disabled" /></div>

<!-- ========================================= SAFRA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<input type="text" name="safra" maxlength="4" onkeypress="mascara(this,numero)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo "$safra"; ?>" />
</div>

<!-- ========================================= TIPO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND estado_registro='ATIVO' ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);

for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
{
$aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	

	if ($aux_tipo_produto[0] == $cod_tipo)
	{echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
	else
	{echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
}
?>
</select>
</div>


<!-- ====================================================================================== -->
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Umidade:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Broca:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:145px; height:5px; border:0px solid #000"></div>Impureza:</div>

<div id="tabela_1" style="width:30px; height:19px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:235px; height:5px; border:0px solid #000"></div>Data Pagamento:</div>

<!-- =========================================  UMIDADE ====================================== -->
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="umidade" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);

for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
{
$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
	if ($botao == "selecionar")
	{
		if ($aux_porcentagem[1] == "")
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
	else
	{
		if ($aux_porcentagem[1] == $umidade)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
}
?>
</select>
</div>

<!-- =========================================  BROCA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="broca" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);

for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
{
$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
	if ($botao == "selecionar")
	{
		if ($aux_porcentagem[1] == "")
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
	else
	{
		if ($aux_porcentagem[1] == $broca)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
}
?>
</select>
</div>

<!-- ========================================= IMPUREZA  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:150px; border:0px solid #000">
<select name="impureza" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
<option></option>
<?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);

for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
{
$aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
	if ($botao == "selecionar")
	{
		if ($aux_porcentagem[1] == "")
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
	else
	{
		if ($aux_porcentagem[1] == $impureza)
		{echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
		else
		{echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
	}
}
?>
</select>
</div>

<!-- ========================================= DATA PAGAMENTO  ====================================== -->
<div id="tabela_1" style="width:30px; border:0px solid #000"></div>
<div id="tabela_2" style="width:240px; border:0px solid #000">
<input type="text" name="data_pagamento" maxlength="10" onkeypress="mascara(this,data)" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo"$data_pagamento"; ?>" disabled="disabled" />
</div>


<!-- ============================================================================================ -->
<div id="tabela_2" style="width:730px; height:19px; border:0px solid #000">
<div id="espaco_1" style="width:725px; height:5px; border:0px solid #000"></div>Observa&ccedil;&atilde;o:</div>

<!-- =========================================  OBSERVAÇÃO ====================================== -->
<div id="tabela_2" style="width:730px; border:0px solid #000">
<input type="text" name="observacao" maxlength="150" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; width:683px" 
value="<?php echo "$observacao"; ?>" /></div>
<!-- =============================================================================================== -->


<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#006400">
</div>


<div id="geral" style="width:730px; text-align:center; border:0px solid #000; float:left; height:30px">
<?php
echo"
	<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'></div>
	<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>
    <button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Salvar</button>
    </form>
    </div>
	<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>";

	if ($pagina_mae == "movimentacao_produtor")
	{
	echo "
	<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='cod_produto' value='$cod_produto'>
	<input type='hidden' name='fornecedor' value='$fornecedor'>
	<input type='hidden' name='cod_tipo' value='$cod_tipo'>
	<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
	</form>";
	}

	else
	{
	echo "
	<form name='cancelar' action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
	<input type='hidden' name='cod_tipo' value='$cod_tipo' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
	<input type='hidden' name='botao' value='botao'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='produto_list' value='$produto_list'>
	<input type='hidden' name='representante' value='$produtor_ficha'>
	<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
    <button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
    </form>";
	}
	
	
    echo "</div>
	<div id='geral' style='width:180px; height:28px; text-align:center; border:0px solid #000; float:left'>
    </div>
";
?>
</div>

<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#006400">
</div>


<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#006400">
<?php
echo "<div>Valor total: <b>R$ $valor_total</b></div>";
?>
</div>

<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#666666">
<?php
echo "<div>Cadastrado por: $usuario_cadastro $data_cadastro $hora_cadastro</div>";
?>
</div>


<div id="geral" style="width:730px; height:25px; border:0px solid #000; float:left; font-size:11px; color:#666666">

</div>




<div id="geral" style="width:900px; height:20px; border:0px solid #000; float:left; font-size:12px; color:#666666">
</div>


</div>
</div>




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>