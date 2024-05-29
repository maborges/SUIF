<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_5_enviar";
$titulo = "Transfer&ecirc;ncia entre Produtores";
$modulo = "compras";
$menu = "ficha_produtor";
// ========================================================================================================

// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];

$numero_transferencia = $_POST["numero_transferencia"];
$fornecedor_origem = $_POST["fornecedor_origem"];
$fornecedor_destino = $_POST["fornecedor_destino"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];
$quantidade_form = $_POST["quantidade_form"];
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$obs_form = $_POST["obs_form"];

if ($quantidade_form == "")
{$quantidade = 0;}
else
{$quantidade = $quantidade_form;}

$usuario_cadastro = $nome_usuario_print;
$data_cadastro = date('Y-m-d', time());
$hora_cadastro = date('G:i:s', time());
// ================================================================================================================


// ====== BLOQUEIO PARA NOVA TRANSFERENCIA =================================================================
if ($permissao[33] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_seleciona_produto'");
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


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ======================================================================================================


// ====== BUSCA TIPO PRODUTO ==================================================================================
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo_produto_form'");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA PESSOA ENTRADA  ==============================================================================
$busca_pessoa_e = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_destino' ORDER BY nome");
$aux_pessoa_e = mysqli_fetch_row($busca_pessoa_e);

$nome_pessoa_e = $aux_pessoa_e[1];
$tipo_pessoa_e = $aux_pessoa_e[2];
$cpf_pessoa_e = $aux_pessoa_e[3];
$cnpj_pessoa_e = $aux_pessoa_e[4];
$cidade_pessoa_e = $aux_pessoa_e[10];
$estado_pessoa_e = $aux_pessoa_e[12];
$telefone_pessoa_e = $aux_pessoa_e[14];
$codigo_pessoa_e = $aux_pessoa_e[35];
// ===========================================================================================================


// ====== BUSCA PESSOA SAIDA  ================================================================================
$busca_pessoa_s = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_origem' ORDER BY nome");
$aux_pessoa_s = mysqli_fetch_row($busca_pessoa_s);

$nome_pessoa_s = $aux_pessoa_s[1];
$tipo_pessoa_s = $aux_pessoa_s[2];
$cpf_pessoa_s = $aux_pessoa_s[3];
$cnpj_pessoa_s = $aux_pessoa_s[4];
$cidade_pessoa_s = $aux_pessoa_s[10];
$estado_pessoa_s = $aux_pessoa_s[12];
$telefone_pessoa_s = $aux_pessoa_s[14];
$codigo_pessoa_s = $aux_pessoa_s[35];
// ========================================================================================================


// ====== BUSCA NUMERO DE TRANSFERÊNCIA (DUPLICIDADE) ===========================================================
$busca_num_transf = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro='ATIVO' and numero_transferencia='$numero_transferencia'");
$achou_transf_duplicidade = mysqli_num_rows ($busca_num_transf);
// =======================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "NOVA_TRANSFERENCIA" and $permite_novo == "SIM")
{
	if ($fornecedor_origem == "" or $fornecedor_destino == "")
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um fornecedor.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Transfer&ecirc;ncia</div>";}

	elseif ($cod_seleciona_produto == "" or $linhas_bp == 0)
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Transfer&ecirc;ncia</div>";}

	elseif (!is_numeric($quantidade) or $quantidade <= 0)
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Quantidade inv&aacute;lida.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Transfer&ecirc;ncia</div>";}

	elseif ($cod_tipo_produto_form == "")
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Selecione o tipo do produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Transfer&ecirc;ncia</div>";}

	elseif ($achou_transf_duplicidade >= 1)
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Transfer&ecirc;ncia em duplicidade. Verifique no relatório de transfer&ecirc;ncias.</div>";
	$msg_titulo = "<div style='color:#009900'>Nova Transfer&ecirc;ncia</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Transfer&ecirc;ncia Cadastrada com Sucesso!</div>";
	


// ------ ATUALIZA SALDO ARMAZENADO DESTINO ----------------------------------------
$fornecedor_pesquisa = $fornecedor_destino;
$cod_seleciona_produto = $cod_seleciona_produto;
$cod_tipo_produto_form = $cod_tipo_produto_form;
$nome_pessoa = $nome_pessoa_e;

include ('../../includes/saldo_armazenado_busca.php');
$saldo = $saldo_produtor + $quantidade;

include ('../../includes/saldo_armazenado_atualiza.php');
// ---------------------------------------------------------------------------------


// ------ ATUALIZA SALDO ARMAZENADO ORIGEM -----------------------------------------
$fornecedor_pesquisa = $fornecedor_origem;
$cod_seleciona_produto = $cod_seleciona_produto;
$cod_tipo_produto_form = $cod_tipo_produto_form;
$nome_pessoa = $nome_pessoa_s;

include ('../../includes/saldo_armazenado_busca.php');
$saldo = $saldo_produtor - $quantidade;
include ('../../includes/saldo_armazenado_atualiza.php');
// ---------------------------------------------------------------------------------
 
 
// ------ CONTADOR NÚMERO COMPRA ---------------------------------------------------
$busca_num_compra = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_num_compra);
$numero_compra_1 = $aux_bnc[7];
$numero_compra_2 = $numero_compra_1 + 1;

$contador_num_compra = $numero_compra_2 + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_compra='$contador_num_compra'");
// ----------------------------------------------------------------------------------

 
$inserir_s = mysqli_query($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_transferencia, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_compra_1', '$fornecedor_origem', '$produto_print', '$data_hoje', '$quantidade', '$unidade_abreviacao', '$tipo_print', '$obs_form', 'TRANSFERENCIA_SAIDA', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_transferencia', '$cod_seleciona_produto', '$cod_unidade', '$cod_tipo_produto_form', '$nome_pessoa_s')");

$inserir_e = mysqli_query($conexao, "INSERT INTO compras (codigo, numero_compra, fornecedor, produto, data_compra, quantidade, unidade, tipo, observacao, movimentacao, usuario_cadastro, hora_cadastro, data_cadastro, estado_registro, filial, numero_transferencia, cod_produto, cod_unidade, cod_tipo, fornecedor_print) VALUES (NULL, '$numero_compra_2', '$fornecedor_destino', '$produto_print', '$data_hoje', '$quantidade', '$unidade_abreviacao', '$tipo_print', '$obs_form', 'TRANSFERENCIA_ENTRADA', '$usuario_cadastro', '$hora_cadastro', '$data_cadastro', 'ATIVO', '$filial', '$numero_transferencia', '$cod_seleciona_produto', '$cod_unidade', '$cod_tipo_produto_form', '$nome_pessoa_e')");
	
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
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
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
    <?php echo"N&ordm; $numero_transferencia"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php echo"$data_hoje_br"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:390px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Produtor Origem:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa_s</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa_s</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa_s</div></div>";
    }
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_pessoa_s" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE / UF ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	$cidade_uf_s = $cidade_pessoa_s . "/" . $estado_pessoa_s;
	$conta_caracter_s = strlen($cidade_uf_s);
	if ($conta_caracter_s <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_s</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_s</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Produtor Destino:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa_e</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_e == "PJ" or $tipo_pessoa_e == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa_e == "PJ" or $tipo_pessoa_e == "pj")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa_e</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa_e</div></div>";
    }
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo"$telefone_pessoa_e" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE / UF ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	$cidade_uf_e = $cidade_pessoa_e . "/" . $estado_pessoa_e;
	$conta_caracter_e = strlen($cidade_uf_e);
	if ($conta_caracter_e <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_e</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf_e</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->




<!-- ======= PRODUTO ============================================================================================= -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:336px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:336px; height:34px; float:left; border:1px solid transparent">
        <div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo"$link_imagem_produto" ?>
            </div>
        
            <div style="width:177px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#003466; overflow:hidden">
                <?php echo"<b>$produto_print</b>" ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ======= QUANTIDADE ============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Quantidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"<b>$quantidade_form $unidade_abreviacao</b>"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; margin-top:0px; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Observa&ccedil;&atilde;o:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <div style="width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; overflow:hidden"><?php echo"$obs_form" ?></div></div>
    </div>
</div>
<!-- ============================================================================================================== -->


<!-- ======= TIPO ===================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Tipo:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$tipo_print" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->



<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:235px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/transferencias/cadastro_1_selec_produto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nova Transfer&ecirc;ncia</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== FICHA DO PRODUTOR ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='fornecedor' value='$fornecedor_origem'>
		<input type='hidden' name='cod_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='botao' value='seleciona'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>F. Produtor Origem</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== FICHA DO PRODUTOR ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='fornecedor' value='$fornecedor_destino'>
		<input type='hidden' name='cod_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='botao' value='seleciona'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>F. Produtor Destino</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO SAIR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/relatorios/relatorio_transferencia.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Sair</button>
		</form>
    </div>";
// =============================================================================================================================
}

elseif ($erro == 5)
{
// ====== BOTAO SAIR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/relatorios/relatorio_transferencia.php' method='post'>
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
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/transferencias/cadastro_4_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_transferencia' value='$numero_transferencia' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_origem' />
	<input type='hidden' name='fornecedor_2_pesquisa' value='$fornecedor_destino' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto' />
	<input type='hidden' name='quantidade_form' value='$quantidade_form' />
	<input type='hidden' name='cod_tipo_produto_form' value='$cod_tipo_produto_form' />
	<input type='hidden' name='obs_form' value='$obs_form' />
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