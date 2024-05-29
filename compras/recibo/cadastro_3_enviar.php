<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");
$pagina = "cadastro_3_enviar";
$titulo = "Recibo";
$modulo = "compras";
$menu = "contratos";
// ========================================================================================================

// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;

$numero_recibo = $_POST["numero_recibo"];

$valor_form = $_POST["valor_form"];
$valor_2 = Helpers::ConverteValor($_POST["valor_form"]);
$data_recibo_form = $_POST["data_recibo_form"];
$data_recibo_aux = Helpers::ConverteData($data_recibo_form);
$cod_produto_form = $_POST["cod_produto_form"];
$nome_emissor_form = $_POST["nome_emissor_form"];
$telefone_emissor_form = $_POST["telefone_emissor_form"];
$cpf_cnpj_emissor_form = $_POST["cpf_cnpj_emissor_form"];
$cidade_emissor_form = $_POST["cidade_emissor_form"];
$nome_pagador_form = $_POST["nome_pagador_form"];
$cpf_cnpj_pagador_form = $_POST["cpf_cnpj_pagador_form"];
$cidade_pagador_form = $_POST["cidade_pagador_form"];
$referente_form = $_POST["referente_form"];
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$nome_imagem_produto = $aux_bp[28];
$usuario_alteracao_prod = $aux_bp[16];
$data_alteracao_prod = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
$plano_conta = $aux_bp[35];
if ($nome_imagem_produto == "")
{$link_imagem_produto = "";}
else
{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>";}
// ======================================================================================================


// ====== MONTA MENSAGEM ================================================================================
if ($botao == "NOVO_RECIBO")
{
	if ($nome_emissor_form == "")
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Informe o  emissor.</div>";
	$msg_titulo = "<div style='color:#009900'>Recibo</div>";}

	elseif ($nome_pagador_form == "")
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Informe o pagador.</div>";
	$msg_titulo = "<div style='color:#009900'>Recibo</div>";}

	elseif (!is_numeric($valor_2) or $valor_2 <= 0)
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Valor inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Recibo</div>";}

	elseif ($data_recibo_form == "" or $data_recibo_aux < "2000-01-01")
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Data de emiss&atilde;o inv&aacute;lida.</div>";
	$msg_titulo = "<div style='color:#009900'>Recibo</div>";}

	elseif ($referente_form == "")
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Informe a que se refere o recibo.</div>";
	$msg_titulo = "<div style='color:#009900'>Recibo</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Recibo Emitido com Sucesso!</div>";
	


	}
}
// ======================================================================================================


// ========================================================================================================
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
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>





<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$msg_titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
    <?php echo"N&ordm; $numero_recibo"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php //echo "$data_hoje_br"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:300px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME DO EMISSOR ======================================================================================= -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Nome do Emissor:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_emissor_form</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CPF / CNPJ EMISSOR ==================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    CPF / CNPJ do Emissor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_cnpj_emissor_form</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  TELEFONE EMISSOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_emissor_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE EMISSOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_emissor_form</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  NOME DO PAGADOR ======================================================================================= -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Nome do Pagador:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pagador_form</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CPF / CNPJ PAGADOR ==================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    CPF / CNPJ do Pagador:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_cnpj_pagador_form</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE PAGADOR ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade do Pagador:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_cnpj_pagador_form</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ======= VALOR ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Valor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"<b>$valor_form</b>"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA EMISSÃO =========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Data de Emiss&atilde;o:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$data_recibo_form"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  REFERENTE A ============================================================================================ -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Referente &agrave;:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <div style="width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; overflow:hidden"><?php echo"$referente_form $produto_print_2" ?></div></div>
    </div>
</div>
<!-- ============================================================================================================== -->



</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->




<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:135px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/recibo/cadastro_1_selec_fornecedor.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Novo Recibo</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== IMPRIMIR RECIBO ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/recibo/recibo_impressao_a5.php' method='post' target='_blank'>
		<input type='hidden' name='numero_recibo' value='$numero_recibo' />
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
		<input type='hidden' name='valor_form' value='$valor_form' />
		<input type='hidden' name='data_recibo_form' value='$data_recibo_form' />
		<input type='hidden' name='nome_emissor_form' value='$nome_emissor_form' />
		<input type='hidden' name='telefone_emissor_form' value='$telefone_emissor_form' />
		<input type='hidden' name='cpf_cnpj_emissor_form' value='$cpf_cnpj_emissor_form' />
		<input type='hidden' name='cidade_emissor_form' value='$cidade_emissor_form' />
		<input type='hidden' name='nome_pagador_form' value='$nome_pagador_form' />
		<input type='hidden' name='cpf_cnpj_pagador_form' value='$cpf_cnpj_pagador_form' />
		<input type='hidden' name='cidade_pagador_form' value='$cidade_pagador_form' />
		<input type='hidden' name='referente_form' value='$referente_form' />
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Recibo A5</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== IMPRIMIR RECIBO ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/recibo/recibo_impressao_a4.php' method='post' target='_blank'>
		<input type='hidden' name='numero_recibo' value='$numero_recibo' />
		<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
		<input type='hidden' name='valor_form' value='$valor_form' />
		<input type='hidden' name='data_recibo_form' value='$data_recibo_form' />
		<input type='hidden' name='nome_emissor_form' value='$nome_emissor_form' />
		<input type='hidden' name='telefone_emissor_form' value='$telefone_emissor_form' />
		<input type='hidden' name='cpf_cnpj_emissor_form' value='$cpf_cnpj_emissor_form' />
		<input type='hidden' name='cidade_emissor_form' value='$cidade_emissor_form' />
		<input type='hidden' name='nome_pagador_form' value='$nome_pagador_form' />
		<input type='hidden' name='cpf_cnpj_pagador_form' value='$cpf_cnpj_pagador_form' />
		<input type='hidden' name='cidade_pagador_form' value='$cidade_pagador_form' />
		<input type='hidden' name='referente_form' value='$referente_form' />
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Recibo A4</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO SAIR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/index.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Sair</button>
		</form>
    </div>";
// =============================================================================================================================
}

else
{
// ====== BOTAO VOLTAR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/recibo/cadastro_2_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_recibo' value='$numero_recibo' />
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form' />
	<input type='hidden' name='valor_form' value='$valor_form' />
	<input type='hidden' name='data_recibo_form' value='$data_recibo_form' />
	<input type='hidden' name='nome_emissor_form' value='$nome_emissor_form' />
	<input type='hidden' name='telefone_emissor_form' value='$telefone_emissor_form' />
	<input type='hidden' name='cpf_cnpj_emissor_form' value='$cpf_cnpj_emissor_form' />
	<input type='hidden' name='cidade_emissor_form' value='$cidade_emissor_form' />
	<input type='hidden' name='nome_pagador_form' value='$nome_pagador_form' />
	<input type='hidden' name='cpf_cnpj_pagador_form' value='$cpf_cnpj_pagador_form' />
	<input type='hidden' name='cidade_pagador_form' value='$cidade_pagador_form' />
	<input type='hidden' name='referente_form' value='$referente_form' />
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
// =============================================================================================================================
}

?>
</div>




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