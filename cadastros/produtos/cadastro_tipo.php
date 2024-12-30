<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_tipo";
$titulo = "Cadastro de Tipo de Produto (Compra e Venda)";
$modulo = "cadastros";
$menu = "cadastro_produtos";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"] ?? '';
$nome_tipo_form = $_POST["nome_tipo_form"] ?? '';
$cod_produto_form = $_POST["cod_produto_form"] ?? '';
$id_w = $_POST["id_w"] ?? '';
$bloqueio_w = $_POST["bloqueio_w"] ?? '';


$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $nome_tipo_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite o nome do tipo do produto</div>";}

elseif ($botao == "CADASTRAR" and $cod_produto_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";}

elseif ($botao == "EDITAR" and $nome_tipo_form == "")
{$erro = 3;
$msg = "<div style='color:#FF0000'>Digite o nome do tipo do produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_produto_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
$id_w = "";
}

elseif ($botao == "EXCLUSAO")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir este tipo?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVO TIPO =======================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[103] == "S")
{
// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO select_tipo_produto (codigo, descricao, estado_registro, cod_produto, usuario_cadastro, data_cadastro, hora_cadastro, bloqueio) VALUES (NULL, '$nome_tipo_form', 'ATIVO', '$cod_produto_form', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', 'NAO')");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo de produto cadastrado com sucesso!</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

elseif ($botao == "CADASTRAR" and $permissao[103] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar tipo de produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR TIPO ===============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[104] == "S")
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE select_tipo_produto SET descricao='$nome_tipo_form', cod_produto='$cod_produto_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo de produto editado com sucesso!</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

elseif ($botao == "EDITAR" and $permissao[104] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar tipo de produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR TIPO =================================================================================
if ($botao == "ATIVAR" and $permissao[104] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE select_tipo_produto SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[104] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE select_tipo_produto SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo inativado com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[104] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar tipo de produto</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR TIPO ==============================================================================================
if ($botao == "EXCLUIR" and $permissao[105] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE select_tipo_produto SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE codigo='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo exclu&iacute;do com sucesso!</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

elseif ($botao == "EXCLUIR" and $permissao[105] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir tipo de produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE estado_registro!='EXCLUIDO' ORDER BY cod_produto, codigo");
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
<?php include ("../../includes/submenu_cadastro_produtos.php"); ?>
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
	<?php 
    if ($linha_registro == 1)
    {echo "<i>$linha_registro Tipo cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Tipos cadastrados</i>";}
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
<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/produtos/cadastro_tipo.php" method="post" />
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


<!-- ======= NOME DO TIPO =========================================================================================== -->
<div style="width:320px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:315px; height:17px; border:1px solid transparent; float:left">
    Nome do Tipo:
    </div>
    
    <div style="width:315px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_tipo_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$nome_tipo_form"; ?>" />
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
	<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post' />
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
<td width='350px'>Tipo do Produto</td>
<td width='200px'>Produto</td>
<td width='60px'>Editar</td>
<td width='60px'>Inativar</td>
<td width='60px'>Excluir</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";



// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO SERVIÇO =============================================================================
$id_w = $aux_registro[0];
$nome_tipo_w = $aux_registro[1];
$codigo_produto_w = $aux_registro[4];
$estado_registro_w = $aux_registro[3];
$bloqueio_w = $aux_registro[14];


$usuario_cadastro_w = $aux_registro[5];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[6]));
$hora_cadastro_w = $aux_registro[7];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_registro[8];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[9]));
$hora_alteracao_w = $aux_registro[10];
$dados_alteracao_w = "Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$codigo_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[1];
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
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $id_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $id_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_tipo_w</div></td>
<td width='200px' align='center'>$produto_print</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='nome_tipo_form' value='$nome_tipo_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/produtos/cadastro_tipo.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='nome_tipo_form' value='$nome_tipo_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
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
<i>Nenhum tipo cadastrado.</i></div>";}
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