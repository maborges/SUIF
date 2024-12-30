<?php
include ("../../includes/config.php");
include ("../../includes/valida_cookies.php");
$pagina = "transferencia_visualizar";
$titulo = "Transfer&ecirc;ncia entre Produtores";
$modulo = "compras";
$menu = "ficha_produtor";
// ========================================================================================================

// ====== BUSCA CONFIGURAÇÕES DO SISTEMA =========================================================================
include ("../../includes/conecta_bd.php");
$busca_config = mysqli_query ($conexao, "SELECT * FROM configuracoes");
include ("../../includes/desconecta_bd.php");

$config = mysqli_fetch_row($busca_config);
// ===============================================================================================================


// ====== BUSCA PERMISSÕES DE USUÁRIOS ===========================================================================
include ("../../includes/conecta_bd.php");
$busca_permissao = mysqli_query ($conexao, "SELECT * FROM usuarios_permissoes WHERE username='$username'");
include ("../../includes/desconecta_bd.php");

$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================



// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];

$numero_compra = $_POST["numero_compra"];

$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$numero_compra_busca = $_POST["numero_compra_busca"];
$filial_busca = $_POST["filial_busca"];
$movimentacao_busca = $_POST["movimentacao_busca"];
$status_busca = $_POST["status_busca"];
// ================================================================================================================



// ====== BUSCA CADASTRO ==========================================================================================
include ("../../includes/conecta_bd.php");
$busca_compra_1 = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_compra='$numero_compra' ORDER BY codigo");
include ("../../includes/desconecta_bd.php");

$linha_compra_1 = mysqli_num_rows ($busca_compra_1);
$aux_compra_1 = mysqli_fetch_row($busca_compra_1);
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
$numero_transferencia = $aux_compra_1[30];
$data_compra_w = $aux_compra_1[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra_1[4]));
$estado_registro_w = $aux_compra_1[24];
$observacao_w = $aux_compra_1[13];
$filial_w = $aux_compra_1[25];

$usuario_cadastro_w = $aux_compra_1[18];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_compra_1[20]));
$hora_cadastro_w = $aux_compra_1[19];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_compra_1[21];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_compra_1[23]));
$hora_alteracao_w = $aux_compra_1[22];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_compra_1[31];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_compra_1[32]));
$hora_exclusao_w = $aux_compra_1[33];
$motivo_exclusao_w = $aux_compra_1[34];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// =============================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ===========================================================================
/*
$diferenca_dias = strtotime($data_hoje) - strtotime($data_compra_w);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[20] or $permissao[74] == "S")
{$pode_excluir = "S";}
else
{$pode_excluir = "N";}

if ($permissao[53] == "S" and $pode_excluir == "S" and $estado_registro_w == "ATIVO")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
*/
$permite_excluir = "SIM";
// ========================================================================================================


// ========================================================================================================
include ("../../includes/conecta_bd.php");
$busca_compra_e = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_transferencia='$numero_transferencia' AND movimentacao='TRANSFERENCIA_ENTRADA' ORDER BY codigo");
include ("../../includes/desconecta_bd.php");

$linha_compra_e = mysqli_num_rows ($busca_compra_e);
$aux_compra_e = mysqli_fetch_row($busca_compra_e);

$numero_compra_e = $aux_compra_e[1];
$fornecedor_e = $aux_compra_e[2];
$cod_produto_e = $aux_compra_e[39];
$quantidade = $aux_compra_e[5];
$quantidade_print = number_format($aux_compra_e[5],2,",",".");
// ========================================================================================================


// ========================================================================================================
include ("../../includes/conecta_bd.php");
$busca_compra_s = mysqli_query($conexao, "SELECT * FROM compras WHERE numero_transferencia='$numero_transferencia' AND movimentacao='TRANSFERENCIA_SAIDA' ORDER BY codigo");
include ("../../includes/desconecta_bd.php");

$linha_compra_s = mysqli_num_rows ($busca_compra_s);
$aux_compra_s = mysqli_fetch_row($busca_compra_s);

$numero_compra_s = $aux_compra_s[1];
$fornecedor_s = $aux_compra_s[2];
// ========================================================================================================



// ====== BUSCA PRODUTO ===================================================================================
include ("../../includes/conecta_bd.php");
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_e'");
include ("../../includes/desconecta_bd.php");

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
include ("../../includes/conecta_bd.php");
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade'");
include ("../../includes/desconecta_bd.php");

$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ======================================================================================================


// ====== BUSCA TIPO PRODUTO ==================================================================================
include ("../../includes/conecta_bd.php");
$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE codigo='$cod_tipo_w'");
include ("../../includes/desconecta_bd.php");
$aux_tp = mysqli_fetch_row($busca_tipo_produto);

$tipo_print = $aux_tp[1];
// ===========================================================================================================


// ====== BUSCA PESSOA ENTRADA  ==============================================================================
include ("../../includes/conecta_bd.php");
$busca_pessoa_e = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_e' ORDER BY nome");
include ("../../includes/desconecta_bd.php");
$aux_pessoa_e = mysqli_fetch_row($busca_pessoa_e);

$nome_pessoa_e = $aux_pessoa_e[1];
$tipo_pessoa_e = $aux_pessoa_e[2];
$cpf_pessoa_e = $aux_pessoa_e[3];
$cnpj_pessoa_e = $aux_pessoa_e[4];
$cidade_pessoa_e = $aux_pessoa_e[10];
$estado_pessoa_e = $aux_pessoa_e[12];
$telefone_pessoa_e = $aux_pessoa_e[14];
$codigo_pessoa_e = $aux_pessoa_e[35];

if ($tipo_pessoa_e == "PF" or $tipo_pessoa_e == "pf")
{$pessoa_cpf_cnpj_e = $cpf_pessoa_e;}
else
{$pessoa_cpf_cnpj_e = $cnpj_pessoa_e;}

$cidade_uf_e = $cidade_pessoa_e . "-" . $estado_pessoa_e;
$conta_caracter_e = strlen($cidade_uf_e);
if ($conta_caracter_e <= 18)
{$cidade_print_e = "<div style='font-size:12px; margin-left:5px; margin-top:6px; overflow:hidden'>$cidade_uf_e</div>";}
else
{$cidade_print_e = "<div style='font-size:9px; margin-left:5px; margin-top:2px; overflow:hidden'>$cidade_uf_e</div>";}
// ===========================================================================================================


// ====== BUSCA PESSOA SAIDA  ================================================================================
include ("../../includes/conecta_bd.php");
$busca_pessoa_s = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_s' ORDER BY nome");
include ("../../includes/desconecta_bd.php");
$aux_pessoa_s = mysqli_fetch_row($busca_pessoa_s);

$nome_pessoa_s = $aux_pessoa_s[1];
$tipo_pessoa_s = $aux_pessoa_s[2];
$cpf_pessoa_s = $aux_pessoa_s[3];
$cnpj_pessoa_s = $aux_pessoa_s[4];
$cidade_pessoa_s = $aux_pessoa_s[10];
$estado_pessoa_s = $aux_pessoa_s[12];
$telefone_pessoa_s = $aux_pessoa_s[14];
$codigo_pessoa_s = $aux_pessoa_s[35];

if ($tipo_pessoa_s == "PF" or $tipo_pessoa_s == "pf")
{$pessoa_cpf_cnpj_s = $cpf_pessoa_s;}
else
{$pessoa_cpf_cnpj_s = $cnpj_pessoa_s;}

$cidade_uf_s = $cidade_pessoa_s . "-" . $estado_pessoa_s;
$conta_caracter_s = strlen($cidade_uf_s);
if ($conta_caracter_s <= 18)
{$cidade_print_s = "<div style='font-size:12px; margin-left:5px; margin-top:6px; overflow:hidden'>$cidade_uf_s</div>";}
else
{$cidade_print_s = "<div style='font-size:9px; margin-left:5px; margin-top:2px; overflow:hidden'>$cidade_uf_s</div>";}
// ========================================================================================================


// ====== CRIA MENSAGEM ============================================================================================
if ($linha_compra_1 == 0 or $numero_compra == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>TRANSFER&Ecirc;NCIA N&Atilde;O LOCALIZADA</div>";}

elseif ($estado_registro_w == "EXCLUIDO")
{$erro = 2;
$msg = "<div style='color:#FF0000'>TRANSFER&Ecirc;NCIA EXCLU&Iacute;DA</div>";}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ========================================================================================================
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
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>





<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1" style="width:1030px">
	<div class="ct_titulo_1" style="width:490px; margin-left:0px">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_titulo_2" style="width:490px; margin-right:25px">
    <?php echo "N&ordm; $numero_transferencia"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2" style="width:1030px">
	<div class="ct_subtitulo_left" style="width:490px; margin-left:0px">
    <?php echo $msg; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:490px; margin-right:25px">
    <?php echo $data_compra_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->





<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:330px; margin:auto; border:1px solid transparent">


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Produtor Origem
    </div>
    
    <div class="visualizar_caixa">
	<div class="visualizar_campo" style='width:582px'><div class="visualizar_hidden"><b><?php echo $nome_pessoa_s; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	<?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {echo "CNPJ";}
    else
    {echo "CPF";}
    ?>
    </div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $pessoa_cpf_cnpj_s; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Cidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><?php echo $cidade_print_s; ?></div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Produtor Destino
    </div>
    
    <div class="visualizar_caixa">
	<div class="visualizar_campo" style='width:582px'><div class="visualizar_hidden"><b><?php echo $nome_pessoa_e; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	<?php
    if ($tipo_pessoa_s == "PJ" or $tipo_pessoa_s == "pj")
    {echo "CNPJ";}
    else
    {echo "CPF";}
    ?>
    </div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $pessoa_cpf_cnpj_e; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Cidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><?php echo $cidade_print_e; ?></div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Produto
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><b><?php echo $produto_print; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Quantidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo "$quantidade_print $unidade_abreviacao"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Filial
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $filial_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	N&ordm; Registro
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $numero_compra; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	</div>
    
    <div class="visualizar_caixa" style='width:170px'>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Observa&ccedil;&atilde;o
    </div>
    
    <div class="visualizar_caixa">
	<div class="visualizar_campo" style='width:582px'><div class="visualizar_hidden"><?php echo $observacao_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	</div>
    
    <div class="visualizar_caixa" style='width:170px'>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	</div>
    
    <div class="visualizar_caixa" style='width:170px'>
    </div>
</div>
<!-- ================================================================================================================ -->




</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:20px"></div>
<!-- ============================================================================================================= -->


<div style="width:1270px; height:60px; margin:auto; border:1px solid transparent; text-align:center">
<div style="width:435px; height:55px; float:left"></div>
<?php
// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================


// ====== BOTAO EXCLUIR ========================================================================================================
if ($permite_excluir == "SIM")
{	
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/transferencias/transferencia_excluir_confirmar.php' method='post'>
	<input type='hidden' name='modulo_mae' value='$modulo_mae'>
	<input type='hidden' name='menu_mae' value='$menu_mae'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='EXCLUIR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Excluir</button>
	</form>
</div>";
}

else
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Excluir</button>
	</div>";
}
// =============================================================================================================================

?>
</div>




<!-- ============================================================================================================= -->
<div class="espacamento" style="height:40px"></div>
<!-- ============================================================================================================= -->










</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<div style="width:auto; height:20px; border:0px solid #000; margin-top:20px; text-align:center; font-size:12px">
<?php
$complemento_rodape = "$dados_cadastro_w";

if (!empty($usuario_alteracao_w))
{$complemento_rodape = $complemento_rodape . " | $dados_alteracao_w";}

if (!empty($usuario_exclusao_w))
{$complemento_rodape = $complemento_rodape . " | $dados_exclusao_w";}

echo $complemento_rodape
?>
</div>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>