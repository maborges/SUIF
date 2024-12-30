<?php
// ================================================================================================================
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_3_formulario";
$titulo = "Nova Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$numero_compra = $_POST["numero_compra"];
$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];

$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;
$pagina_mae = $_POST["pagina_mae"];

$quantidade_form = $_POST["quantidade"];
$preco_unitario_form = $_POST["preco_unitario"];
$safra_form = $_POST["safra"];
$cod_tipo_form = $_POST["cod_tipo"];
$umidade_form = $_POST["umidade"];
$broca_form = $_POST["broca"];
$impureza_form = $_POST["impureza"];
$data_pagamento_form = $_POST["data_pagamento"];
$cond_entrega_form = $_POST["cond_entrega_form"]; // POSTO ou A_PUXAR ou ARMAZENADO
$data_prev_entrega_form = $_POST["data_pagamento"];
$observacao_form = $_POST["observacao"];
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
$nome_imagem_produto = $aux_bp[28];
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
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_form' AND estado_registro!='EXCLUIDO'");
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


// ====== BUSCA SALDO PRODUTOR ========================================================================
$busca_saldo_arm = mysqli_query ($conexao, "SELECT * FROM saldo_armazenado WHERE cod_fornecedor='$fornecedor_form' AND filial='$filial' AND cod_produto='$cod_produto_form'");
$linhas_saldo_arm = mysqli_num_rows ($busca_saldo_arm);
$aux_saldo_arm = mysqli_fetch_row($busca_saldo_arm);
$saldo_armazenado_print = number_format($aux_saldo_arm[9],2,",",".");
// ======================================================================================================


// ====== BUSCA ULTIMA COMPRA ========================================================================
$busca_ultima_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE fornecedor='$fornecedor_form' AND filial='$filial' AND cod_produto='$cod_produto_form' AND estado_registro='ATIVO' AND movimentacao='COMPRA' ORDER BY codigo DESC LIMIT 1");
$aux_ultima_compra = mysqli_fetch_row($busca_ultima_compra);
$linhas_ultima_compra = mysqli_num_rows ($busca_ultima_compra);

$data_uc = date('d/m/Y', strtotime($aux_ultima_compra[4]));
$quant_uc = number_format($aux_ultima_compra[5],2,",",".");
$preco_uc = number_format($aux_ultima_compra[6],2,",",".");
$valor_uc = number_format($aux_ultima_compra[7],2,",",".");
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($fornecedor_form == "" or $linhas_fornecedor == 0)
{$erro = 1;
$msg = "<div style='color:#FF0000'>Selecione um fornecedor</div>";}
elseif ($cod_produto_form == "" or $linhas_bp == 0)
{$erro = 2;
$msg = "<div style='color:#FF0000'>Selecione um produto</div>";}
else
{$erro = 0;
$msg = "";}
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




<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:460px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Nova Compra
    </div>


	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    N&ordm; <?php echo"$numero_compra"; ?>
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
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/cadastro_4_enviar.php" method="post">
<input type="hidden" name="botao" value="NOVA_COMPRA" />
<input type="hidden" name="fornecedor_form" value="<?php echo"$fornecedor_form"; ?>" />
<input type="hidden" name="cod_produto_form" value="<?php echo"$cod_produto_form"; ?>" />
<input type="hidden" name="numero_compra" value="<?php echo "$numero_compra"; ?>" />


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
	<div id="tabela_2" style="width:1030px; height:20px; border:0px solid #000; font-size:12px; margin-top:0px">
	<div style="margin-top:0px; margin-left:25px; border:1px solid transparent; color:#003466">Fornecedor</div></div>
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
	<div id="tabela_2" style="width:1080px; height:20px; border:0px solid #000; font-size:12px; margin-top:20px">
		<div style="width:241px; height:18px; margin-top:0px; border:1px solid transparent; margin-left:25px; color:#003466; float:left">
        Produto</div>
		<div style="width:241px; height:18px; margin-top:0px; border:1px solid transparent; margin-left:153px; color:#003466; float:left">
        Saldo de armazenado do produtor</div>
		<div style="width:241px; height:18px; margin-top:0px; border:1px solid transparent; margin-right:25px; color:#003466; float:right">
        &Uacute;ltima compra do produtor</div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
		<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
			<?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
		</div>

		<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
			<?php echo"<b>$produto_print_2</b>" ?>
		</div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
		<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
			<?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
		</div>

		<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
			<?php echo"<b>$saldo_armazenado_print $unidade_print</b>" ?>
		</div>
    </div>

    <div style="width:241px; height:32px; border:1px solid #999; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
		<div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
			<?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
		</div>

		<div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
			<?php echo"<b>$quant_uc $unidade_print x $preco_uc = R$ $valor_uc ($data_uc)</b>" ?>
		</div>
    </div>

</div>
<!-- ============================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ======================================= FORMULARIO ========================================================== -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Peso Inicial (Kg):</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Tipo Sacaria:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Filial Origem:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Motorista:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">CPF Motorista:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Placa do Ve&iacute;culo</div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="peso_inicial_form" class="form_input" maxlength="11" onkeypress="mascara(this,m_quantidade)"  
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$peso_inicial_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="cod_sacaria_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
    <option></option>
    <?php
    
    $busca_tipo_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE movimentacao='ENTRADA' AND estado_registro='ATIVO' ORDER BY codigo");
    $linhas_tipo_sacaria = mysqli_num_rows ($busca_tipo_sacaria);
    
    for ($t=1 ; $t<=$linhas_tipo_sacaria ; $t++)
    {
    $aux_tipo_sacaria = mysqli_fetch_row($busca_tipo_sacaria);	
    
    if ($aux_tipo_sacaria[0] == $cod_sacaria_form)
    {echo "<option selected='selected' value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
    else
    {echo "<option value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
    }
    ?>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="filial_origem_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
	<?php
    $busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
    $linhas_filial_origem = mysqli_num_rows ($busca_filial_origem);
    
    for ($f=1 ; $f<=$linhas_filial_origem ; $f++)
    {
    $aux_filial_origem = mysqli_fetch_row($busca_filial_origem);	

    if ($aux_filial_origem[1] == $filial_origem_form)
    {echo "<option selected='selected' value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
    else
    {echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
    }
    ?>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="motorista_form" class="form_input" maxlength="25" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$motorista_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="motorista_cpf_form" class="form_input" maxlength="14" onkeypress="mascara(this,num_cpf)" onBlur="mascara(this,num_cpf)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$motorista_cpf_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="placa_veiculo_form" class="form_input" maxlength="20" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:145px; text-align:left; padding-left:5px" value="<?php echo"$placa_veiculo_form"; ?>" />
    </div>

</div>
<!-- ============================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ============================================================================================================= -->
<div class="form" style="height:17px; border:1px solid transparent">
	<div class="form_rotulo" style="width:115px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">N&ordm; Romaneio Manual:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Observa&ccedil;&atilde;o:</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- xxxxxxxx --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="height:28px; border:1px solid transparent">

	<div class="form_rotulo" style="width:115px; height:26px; border:1px solid transparent"></div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="romaneio_manual_form" class="form_input" maxlength="10" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$romaneio_manual_form"; ?>" />
    </div>

	<div style="width:695px; height:auto; float:left; border:1px solid transparent">
    <input type="text" class="form_input" name="obs_form" maxlength="100"
    onkeydown="if (getKey(event) == 13) return false;" style="width:673px; text-align:left; padding-left:5px" value="<?php echo"$obs_form"; ?>" />
    </div>

</div>
<!-- ============================================= FIM FORMULARIO ================================================ -->


<!-- ============================================================================================================= -->
<div class="espacamento_25"></div>




<div id="centro" style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
	
	<?php
	if ($erro == 1 or $erro == 2)
	{echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/cadastro_2_selec_fornecedor.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:120px'>Voltar</button>
	</form>
	</div>";}

	elseif ($erro == 3)
	{echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/cadastro_1_selec_produto.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:120px'>Voltar</button>
	</form>
	</div>";}

	
	else
	{echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:120px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_produto.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:120px'>Cancelar</button>
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