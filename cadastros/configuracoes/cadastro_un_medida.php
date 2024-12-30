<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_un_medida";
$titulo = "Cadastro Unidade de Medida";	
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$codigo_w = $_POST["codigo_w"];
$bloqueio_w = $_POST["bloqueio_w"];
$descricao_form = $_POST["descricao_form"];
$abreviacao_form = $_POST["abreviacao_form"];

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $descricao_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite uma descri&ccedil;&atilde;o para a unidade de medida</div>";}

if ($botao == "CADASTRAR" and $abreviacao_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Digite uma abrevia&ccedil;&atilde;o para a unidade de medida</div>";}

elseif ($botao == "EDITAR" and $descricao_form == "")
{$erro = 3;
$msg = "<div style='color:#FF0000'>Digite uma descri&ccedil;&atilde;o para a unidade de medida</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

elseif ($botao == "EDITAR" and $abreviacao_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Digite uma abrevia&ccedil;&atilde;o para a unidade de medida</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

elseif ($botao == "EXCLUSAO")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir esta unidade de medida?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVA UNIDADE DE MEDIDA ===========================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[133] == "S")
{
// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO unidade_produto (codigo, descricao, abreviacao, bloqueio, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro) VALUES (NULL, '$descricao_form', '$abreviacao_form', 'NAO', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form')");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Unidade de medida cadastrada com sucesso!</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

elseif ($botao == "CADASTRAR" and $permissao[133] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar unidade de medida</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR UNIDADE DE MEDIDA ===================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[133] == "S")
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE unidade_produto SET descricao='$descricao_form', abreviacao='$abreviacao_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Unidade de medida editada com sucesso!</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

elseif ($botao == "EDITAR" and $permissao[133] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar unidade de medida</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR UNIDADE DE MEDIDA ========================================================================
if ($botao == "ATIVAR" and $permissao[133] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE unidade_produto SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Unidade de medida ativada com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[133] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE unidade_produto SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Unidade de medida inativada com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[133] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar unidade de medida</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR UNIDADE DE MEDIDA ==================================================================================
if ($botao == "EXCLUIR" and $permissao[133] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE unidade_produto SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Unidade de medida exclu&iacute;da com sucesso!</div>";
}

elseif ($botao == "EXCLUIR" and $permissao[133] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir unidade de medida</div>";
$descricao_form = "";
$abreviacao_form = "";
$codigo_w = "";
$bloqueio_w = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE estado_registro!='EXCLUIDO' ORDER BY descricao");
$linha_registro = mysqli_num_rows ($busca_registro);
// ==================================================================================================================


// ================================================================================================================
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
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_registro == 1)
    {echo "<i>$linha_registro Cadastro</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Cadastros</i>";}
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
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/cadastro_un_medida.php" method="post" />
    <?php
	if ($botao == "EDICAO")
	{echo "
	<input type='hidden' name='botao' value='EDITAR' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";}
	
	elseif ($botao == "EXCLUSAO")
	{echo "
	<input type='hidden' name='botao' value='EXCLUIR' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";}
	
	else
	{echo "<input type='hidden' name='botao' value='CADASTRAR' />";}
	?>
</div>
<!-- ================================================================================================================ -->


<!-- ======= DESCRIÇÃO ============================================================================================== -->
<div style="width:230px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:228px; height:17px; border:1px solid transparent; float:left">
    Descri&ccedil;&atilde;o:
    </div>
    
    <div style="width:228px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="descricao_form" class="form_input" id="ok" maxlength="20" onBlur="alteraMaiusculo(this)"
    onkeydown="if (getKey(event) == 13) return false;" style="width:200px; text-align:left; padding-left:5px" value="<?php echo"$descricao_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= ABREVIAÇÃO ============================================================================================== -->
<div style="width:150px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:148px; height:17px; border:1px solid transparent; float:left">
    Abrevia&ccedil;&atilde;o:
    </div>
    
    <div style="width:148px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="abreviacao_form" class="form_input" maxlength="3" onBlur="alteraMaiusculo(this)"
    onkeydown="if (getKey(event) == 13) return false;" style="width:120px; text-align:left; padding-left:5px" value="<?php echo"$abreviacao_form"; ?>" />
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
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post' />
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
<td width='300px'>Descri&ccedil;&atilde;o</td>
<td width='150px'>Abrevia&ccedil;&atilde;o</td>
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

// ====== DADOS DO CADASTRO ============================================================================
$codigo_w = $aux_registro[0];
$descricao_w = $aux_registro[1];
$abreviacao_w = $aux_registro[2];
$bloqueio_w = $aux_registro[5];
$estado_registro_w = $aux_registro[6];


$usuario_cadastro_w = $aux_registro[7];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[8]));
$hora_cadastro_w = $aux_registro[9];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_registro[10];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[11]));
$hora_alteracao_w = $aux_registro[12];
$dados_alteracao_w = "Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ==========================================================================
if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ======================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
$permite_ativar = "SIM";
/*
if ($bloqueio_w != "SIM")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
*/
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR =========================================================================
if ($estado_registro_w == "ATIVO" and $bloqueio_w != "SIM")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ======================================================================================================


// ====== RELATORIO =====================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}

echo "
<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$descricao_w</div></td>
<td width='150px' align='center'>$abreviacao_w</td>";


// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='descricao_form' value='$descricao_w'>
		<input type='hidden' name='abreviacao_form' value='$abreviacao_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_un_medida.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='descricao_form' value='$descricao_w'>
		<input type='hidden' name='abreviacao_form' value='$abreviacao_w'>
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
<i>Nenhuma unidade de medida cadastrada.</i></div>";}
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