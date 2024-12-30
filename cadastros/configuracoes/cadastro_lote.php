<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "cadastro_lote";
$titulo = "Cadastro de Lote";	
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$id_w = $_POST["id_w"];
$codigo_lote_w = $_POST["codigo_lote_w"];
$nome_lote_form = $_POST["nome_lote_form"];
$cod_armazem_form = $_POST["cod_armazem_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$cod_sacaria_form = $_POST["cod_sacaria_form"];
$bloqueio_w = $_POST["bloqueio_w"];

if ($botao == "EDICAO")
{$capacidade_max_form = $_POST["capacidade_max_form"];}
else
{$capacidade_max_form = Helpers::ConvertePeso($_POST["capacidade_max_form"], 'N');}

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $nome_lote_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite um n&uacute;mero ou nome para o lote</div>";}

elseif ($botao == "CADASTRAR" and $cod_armazem_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Informe o armaz&eacute;m do lote</div>";}

elseif ($botao == "CADASTRAR" and $cod_produto_form == "")
{$erro = 3;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";}

elseif ($botao == "CADASTRAR" and $cod_sacaria_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Informe o tipo de sacaria</div>";}

elseif ($botao == "CADASTRAR" and ($capacidade_max_form == "" or $capacidade_max_form <= 0))
{$erro = 5;
$msg = "<div style='color:#FF0000'>Informe a capacidade m&aacute;xima que o lote suporta</div>";}


elseif ($botao == "EDITAR" and $nome_lote_form == "")
{$erro = 6;
$msg = "<div style='color:#FF0000'>Digite um n&uacute;mero ou nome para o lote</div>";
$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_armazem_form == "")
{$erro = 7;
$msg = "<div style='color:#FF0000'>Informe o armaz&eacute;m do lote</div>";
$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_produto_form == "")
{$erro = 8;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";
$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and ($capacidade_max_form == "" or $capacidade_max_form <= 0))
{$erro = 9;
$msg = "<div style='color:#FF0000'>Informe a capacidade m&aacute;xima que o lote suporta</div>";
$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_sacaria_form == "")
{$erro = 10;
$msg = "<div style='color:#FF0000'>Informe o tipo de sacaria</div>";}

elseif ($botao == "EXCLUSAO")
{$erro = 11;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir este armaz&eacute;m?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVO LOTE ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[91] == "S")
{

// CONTADOR CÓDIGO LOTE
$busca_codigo_lote = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bcl = mysqli_fetch_row($busca_codigo_lote);
$codigo_lote = $aux_bcl[22];
$contador_codigo_lote = $codigo_lote + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_codigo_lote='$contador_codigo_lote'");

// BUSCA ARMAZEM
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE codigo_armazem='$cod_armazem_form'");
$aux_ba = mysqli_fetch_row($busca_registro);

$nome_armazem_form = $aux_ba[2];
$filial_lote_form = $aux_ba[3];

// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO cadastro_lote (id, codigo_lote, nome_lote, cod_armazem, cod_produto, quant_maxima, filial, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro, cod_sacaria, bloqueio) VALUES (NULL, '$codigo_lote', '$nome_lote_form', '$cod_armazem_form', '$cod_produto_form', '$capacidade_max_form', '$filial_lote_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', '$cod_sacaria_form', 'NAO')");

$inserir_armazenado = mysqli_query ($conexao, "INSERT INTO saldo_armazenado_lote (id, cod_armazem, cod_lote, nome_lote, saldo, cod_produto, filial, estado_registro) VALUES (NULL, '$cod_armazem_form', '$codigo_lote', '$nome_lote_form', '0', '$cod_produto_form', '$filial_lote_form', 'ATIVO')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Lote cadastrado com sucesso!</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
}

elseif ($botao == "CADASTRAR" and $permissao[91] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar lote</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR LOTE ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[92] == "S")
{
// BUSCA ARMAZEM
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE codigo_armazem='$cod_armazem_form'");
$aux_ba = mysqli_fetch_row($busca_registro);

$nome_armazem_form = $aux_ba[2];
$filial_lote_form = $aux_ba[3];

// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cadastro_lote SET nome_lote='$nome_lote_form', cod_armazem='$cod_armazem_form', cod_produto='$cod_produto_form', quant_maxima='$capacidade_max_form', filial='$filial_lote_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form', cod_sacaria='$cod_sacaria_form' WHERE id='$id_w'");

$editar_armazenado = mysqli_query ($conexao, "UPDATE saldo_armazenado_lote SET cod_armazem='$cod_armazem_form', nome_lote='$nome_lote_form', cod_produto='$cod_produto_form', filial='$filial_lote_form' WHERE cod_lote='$codigo_lote_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro editado com sucesso!</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
}

elseif ($botao == "EDITAR" and $permissao[92] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar lote</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR LOTE =================================================================================
if ($botao == "ATIVAR" and $permissao[92] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cadastro_lote SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

$ativar_armazenado = mysqli_query ($conexao, "UPDATE saldo_armazenado_lote SET estado_registro='ATIVO' WHERE cod_lote='$codigo_lote_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Lote ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[92] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cadastro_lote SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

$inativar_armazenado = mysqli_query ($conexao, "UPDATE saldo_armazenado_lote SET estado_registro='INATIVO' WHERE cod_lote='$codigo_lote_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Lote inativado com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[92] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar lote</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR LOTE ============================================================================================
if ($botao == "EXCLUIR" and $permissao[93] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cadastro_lote SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");

$excluir_armazenado = mysqli_query ($conexao, "UPDATE saldo_armazenado_lote SET estado_registro='EXCLUIDO' WHERE cod_lote='$codigo_lote_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Lote exclu&iacute;do com sucesso!</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
$id_w = "";
}

elseif ($botao == "EXCLUIR" and $permissao[93] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir lote</div>";

$nome_lote_form = "";
$cod_armazem_form = "";
$cod_produto_form = "";
$filial_lote_form = "";
$capacidade_max_form = "";
$cod_sacaria_form = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE estado_registro!='EXCLUIDO' ORDER BY id");
$linha_registro = mysqli_num_rows ($busca_registro);
// ==================================================================================================================


// =================================================================================================================
include ("../../includes/head.php"); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 4000); // 4 Segundos

</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_config.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo"$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_registro == 1)
    {echo "<i>$linha_registro Lote Cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Lotes Cadastrados</i>";}
    ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
    <?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<div class="pqa" style="height:63px">
<!-- ======================================= FORMULARIO ========================================================== -->


<!-- ======= ESPAÇAMENTO ============================================================================================ -->
<div style="width:20px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/cadastro_lote.php" method="post" />
    <?php
	if ($botao == "EDICAO")
	{echo "
	<input type='hidden' name='botao' value='EDITAR' />
	<input type='hidden' name='id_w' value='$id_w' />";}
	
	elseif ($botao == "EXCLUSAO")
	{echo "
	<input type='hidden' name='botao' value='EXCLUIR' />
	<input type='hidden' name='id_w' value='$id_w' />";}
	
	else
	{echo "<input type='hidden' name='botao' value='CADASTRAR' />";}
	?>
</div>
<!-- ================================================================================================================ -->


<!-- ======= NOME ARMAZÉM =========================================================================================== -->
<div style="width:220px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:215px; height:17px; border:1px solid transparent; float:left">
    N&deg; / Nome do Lote:
    </div>
    
    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_lote_form" class="form_input" maxlength="30" id="ok" onBlur='alteraMaiusculo(this)' 
    onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px" value="<?php echo"$nome_lote_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= ARMAZÉM ================================================================================================ -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Armaz&eacute;m:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_armazem_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:180px" />
    <option></option>
	<?php
    $busca_armazem_lote = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE estado_registro='ATIVO' ORDER BY nome_armazem");
    $linhas_armazem_lote = mysqli_num_rows ($busca_armazem_lote);
    
    for ($a=1 ; $a<=$linhas_armazem_lote ; $a++)
    {
		$aux_armazem_lote = mysqli_fetch_row($busca_armazem_lote);	
	
		if ($aux_armazem_lote[1] == $cod_armazem_form)
		{echo "<option selected='selected' value='$aux_armazem_lote[1]'>$aux_armazem_lote[2]</option>";}
		else
		{echo "<option value='$aux_armazem_lote[1]'>$aux_armazem_lote[2]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PRODUTO ================================================================================================ -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_produto_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:180px" />
    <option></option>
	<?php
	$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows ($busca_produto_list);

	for ($j=1 ; $j<=$linhas_produto_list ; $j++)
	{
		$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
		if ($aux_produto_list[0] == $cod_produto_form)
		{echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
		else
		{echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";}
	}
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO SACARIA =========================================================================================== -->
<div style="width:200px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:195px; height:17px; border:1px solid transparent; float:left">
    Tipo Sacaria:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <select name="cod_sacaria_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:180px" />
    <option></option>
	<?php
    $busca_tipo_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE movimentacao='ARMAZENAGEM' AND estado_registro='ATIVO' ORDER BY codigo");
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
</div>
<!-- ================================================================================================================ -->


<!-- ======= CAPACIDADE MAXIMA ====================================================================================== -->
<div style="width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:175px; height:17px; border:1px solid transparent; float:left">
    Capacidade M&aacute;x. (Bag):
    </div>
    
    <div style="width:175px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="capacidade_max_form" class="form_input" maxlength="13" onkeypress="mascara(this,m_quantidade)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:165px; text-align:left; padding-left:5px" value="<?php echo"$capacidade_max_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO ================================================================================================== -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($botao == "EDICAO")
	{echo "<button type='submit' class='botao_1'>Salvar</button>";}

	elseif ($botao == "EXCLUSAO")
	{echo "<button type='submit' class='botao_1'>Excluir</button>";}

	else
	{echo "<button type='submit' class='botao_1'>Cadastrar</button>";}
	?>
    </form>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BOTAO CANCELAR ========================================================================================= -->
<div style="width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
    <!-- Botão: -->
    </div>
    
    <div style="width:95px; height:25px; float:left; border:1px solid transparent">
	<?php
	if ($botao == "EDICAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	else
	{}
	?>
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
if ($linha_registro == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='255px'>Lote</td>
<td width='180px'>Armaz&eacute;m</td>
<td width='180px'>Produto</td>
<td width='180px'>Sacaria</td>
<td width='180px'>Capacidade (Bag)</td>
<td width='60px'>Editar</td>
<td width='60px'>Ativar</td>
<td width='60px'>Excluir</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO ARMAZÉM ============================================================================
$id_w = $aux_registro[0];
$codigo_lote_w = $aux_registro[1];
$nome_lote_w = $aux_registro[2];
$endereco_lote_w = $aux_registro[3];
$codigo_armazem_w = $aux_registro[4];
$codigo_produto_w = $aux_registro[5];
$codigo_sacaria_w = $aux_registro[20];
$quant_minima_w = number_format($aux_registro[6],0,",",".");
$quant_maxima_w = number_format($aux_registro[7],0,",",".");
$filial_w = $aux_registro[8];
$estado_registro_w = $aux_registro[9];
$bloqueio_w = $aux_registro[30];

$usuario_cadastro_w = $aux_registro[10];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[11]));
$hora_cadastro_w = $aux_registro[12];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BUSCA ARMAZEM ===================================================================================
$busca_armazem_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE codigo_armazem='$codigo_armazem_w'");
$aux_ba_2 = mysqli_fetch_row($busca_armazem_2);

$nome_armazem_print = $aux_ba_2[2];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$codigo_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[1];
// ======================================================================================================


// ====== BUSCA SACARIA ===================================================================================
$busca_sacaria_2 = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$codigo_sacaria_w'");
$aux_bs_2 = mysqli_fetch_row($busca_sacaria_2);

$nome_sacaria_print = $aux_bs_2[1];
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
$permite_ativar = "SIM";
/*
if ($bloqueio_w != "SIM")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
*/
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $codigo_lote_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $codigo_lote_w &#13; $dados_cadastro_w'>";}

echo "
<td width='255px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_lote_w</div></td>
<td width='180px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_armazem_print</div></td>
<td width='180px' align='center'>$produto_print</td>
<td width='180px' align='center'>$nome_sacaria_print</td>
<td width='180px' align='center'>$quant_maxima_w</td>";


// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_lote_w' value='$codigo_lote_w'>
		<input type='hidden' name='nome_lote_form' value='$nome_lote_w'>
		<input type='hidden' name='cod_armazem_form' value='$codigo_armazem_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
		<input type='hidden' name='cod_sacaria_form' value='$codigo_sacaria_w'>
		<input type='hidden' name='capacidade_max_form' value='$quant_maxima_w'>
		<input type='hidden' name='bloqueio_w' value='$bloqueio_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/editar.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO ATIVAR / INATIVAR ==================================================================================
	if ($permite_ativar == "SIM" and $estado_registro_w == "INATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_lote_w' value='$codigo_lote_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_lote_w' value='$codigo_lote_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/ativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO EXCLUIR ===================================================================================================
	if ($permite_excluir == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_lote.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_lote_w' value='$codigo_lote_w'>
		<input type='hidden' name='nome_lote_form' value='$nome_lote_w'>
		<input type='hidden' name='cod_armazem_form' value='$codigo_armazem_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
		<input type='hidden' name='cod_sacaria_form' value='$codigo_sacaria_w'>
		<input type='hidden' name='capacidade_max_form' value='$quant_maxima_w'>
		<input type='hidden' name='bloqueio_w' value='$bloqueio_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/excluir.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='60px' align='center'></td>";
	}
// =================================================================================================================


}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_registro == 0)
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum lote cadastrado.</i></div>";}
// =================================================================================================================
?>




<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
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