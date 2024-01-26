<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_sacaria";
$titulo = "Cadastro de Sacaria";
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ====== CONVERTE PESO ==========================================================================================
function ConvertePeso($peso_x){
	$peso_1 = str_replace(".", "", $peso_x);
	$peso_2 = str_replace(",", ".", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$descricao_sacaria_form = $_POST["descricao_sacaria_form"];
$movimentacao_sacaria_form = $_POST["movimentacao_sacaria_form"];
$peso_sacaria_form = ConvertePeso($_POST["peso_sacaria_form"]);
$capacidade_sacaria_form = ConvertePeso($_POST["capacidade_sacaria_form"]);
$codigo_w = $_POST["codigo_w"];
$bloqueio_w = $_POST["bloqueio_w"];

if ($botao == "EDICAO")
{
$peso_sacaria_form = $_POST["peso_sacaria_form"];
$capacidade_sacaria_form = $_POST["capacidade_sacaria_form"];
}
else
{
$peso_sacaria_form = ConvertePeso($_POST["peso_sacaria_form"]);
$capacidade_sacaria_form = ConvertePeso($_POST["capacidade_sacaria_form"]);
}

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $descricao_sacaria_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite um nome para a sacaria</div>";}

elseif ($botao == "CADASTRAR" and $movimentacao_sacaria_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Informe se a sacaria ser&aacute; usada para romaneio de ENTRADA ou romaneio de SA&Iacute;DA</div>";}

elseif ($botao == "CADASTRAR" and ($peso_sacaria_form == "" or $peso_sacaria_form <= 0))
{$erro = 3;
$msg = "<div style='color:#FF0000'>Informe o peso da sacaria</div>";}

elseif ($botao == "EDITAR" and $descricao_sacaria_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Digite um nome para a sacaria</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
$codigo_w = "";
}

elseif ($botao == "EDITAR" and $movimentacao_sacaria_form == "")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Informe se a sacaria ser&aacute; usada para romaneio de ENTRADA ou romaneio de SA&Iacute;DA</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
$codigo_w = "";
}

elseif ($botao == "EDITAR" and ($peso_sacaria_form == "" or $peso_sacaria_form <= 0))
{$erro = 6;
$msg = "<div style='color:#FF0000'>Informe o peso da sacaria</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
$codigo_w = "";
}

elseif ($botao == "EXCLUSAO")
{$erro = 7;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir esta sacaria?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVA SACARIA ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[97] == "S")
{
// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO select_tipo_sacaria (codigo, descricao, peso, movimentacao, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro, capacidade, bloqueio) VALUES (NULL, '$descricao_sacaria_form', '$peso_sacaria_form',  '$movimentacao_sacaria_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', '$capacidade_sacaria_form', 'NAO')");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria cadastrada com sucesso!</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

elseif ($botao == "CADASTRAR" and $permissao[97] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar sacaria</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR SACARIA ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[98] == "S")
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET descricao='$descricao_sacaria_form', peso='$peso_sacaria_form', movimentacao='$movimentacao_sacaria_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form', capacidade='$capacidade_sacaria_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria editada com sucesso!</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

elseif ($botao == "EDITAR" and $permissao[98] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar sacaria</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR SACARIA =================================================================================
if ($botao == "ATIVAR" and $permissao[98] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria ativada com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[98] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria inativada com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[98] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar sacaria</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR SACARIA ============================================================================================
if ($botao == "EXCLUIR" and $permissao[99] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria exclu&iacute;da com sucesso!</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

elseif ($botao == "EXCLUIR" and $permissao[99] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir sacaria</div>";
$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE estado_registro!='EXCLUIDO' ORDER BY movimentacao, peso");
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
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php 
    if ($linha_registro == 1)
    {echo "<i>$linha_registro Sacaria cadastrada</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Sacarias cadastradas</i>";}
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
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/cadastro_sacaria.php" method="post" />
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


<!-- ======= NOME SACARIA =========================================================================================== -->
<div style="width:320px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:315px; height:17px; border:1px solid transparent; float:left">
    Nome da Sacaria:
    </div>
    
    <div style="width:315px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="descricao_sacaria_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$descricao_sacaria_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PESO TARA ============================================================================================== -->
<div style="width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:175px; height:17px; border:1px solid transparent; float:left">
    Peso de Tara (Kg):
    </div>
    
    <div style="width:175px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="peso_sacaria_form" class="form_input" maxlength="9" onkeypress="mascara(this,m_quantidade_kg)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:left; padding-left:5px" value="<?php echo"$peso_sacaria_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CAPACIDADE ============================================================================================= -->
<div style="width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:175px; height:17px; border:1px solid transparent; float:left">
    Capacidade (Kg):
    </div>
    
    <div style="width:175px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="capacidade_sacaria_form" class="form_input" maxlength="9" onkeypress="mascara(this,m_quantidade)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:left" value="<?php echo"$capacidade_sacaria_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= MOVIMENTAÇÃO ============================================================================================= -->
<div style="width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:175px; height:17px; border:1px solid transparent; float:left">
    Movimenta&ccedil;&atilde;o:
    </div>
    
    <div style="width:175px; height:25px; float:left; border:1px solid transparent">
    <select name="movimentacao_sacaria_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
	<?php
    if ($movimentacao_sacaria_form == "ENTRADA")
    {echo "
		<option></option>
		<option selected='selected' value='ENTRADA'>ENTRADA</option>
		<option value='SAIDA'>SA&Iacute;DA</option>
		<option value='ARMAZENAGEM'>ARMAZENAGEM</option>
	";}
    elseif ($movimentacao_sacaria_form == "SAIDA")
    {echo "
		<option></option>
		<option value='ENTRADA'>ENTRADA</option>
		<option selected='selected' value='SAIDA'>SA&Iacute;DA</option>
		<option value='ARMAZENAGEM'>ARMAZENAGEM</option>
	";}
    elseif ($movimentacao_sacaria_form == "ARMAZENAGEM")
    {echo "
		<option></option>
		<option value='ENTRADA'>ENTRADA</option>
		<option value='SAIDA'>SA&Iacute;DA</option>
		<option selected='selected' value='ARMAZENAGEM'>ARMAZENAGEM</option>
	";}

	else
    {echo "
		<option></option>
		<option value='ENTRADA'>ENTRADA</option>
		<option value='SAIDA'>SA&Iacute;DA</option>
		<option value='ARMAZENAGEM'>ARMAZENAGEM</option>
	";}
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
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post' />
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
<td width='350px'>Sacaria</td>
<td width='160px'>Peso de Tara</td>
<td width='160px'>Capacidade</td>
<td width='160px'>Movimenta&ccedil;&atilde;o</td>
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

// ====== DADOS DO ROMANEIO ============================================================================
$codigo_w = $aux_registro[0];
$descricao_w = $aux_registro[1];
$peso_w = number_format($aux_registro[2],3,",",".");
$capacidade_w = number_format($aux_registro[14],0,",",".");
$movimentacao_w = $aux_registro[3];
$estado_registro_w = $aux_registro[4];
$bloqueio_w = $aux_registro[15];

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
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$descricao_w</div></td>
<td width='160px' align='center'>$peso_w Kg</td>
<td width='160px' align='center'>$capacidade_w Kg</td>
<td width='160px' align='center'>$movimentacao_w</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='descricao_sacaria_form' value='$descricao_w'>
		<input type='hidden' name='movimentacao_sacaria_form' value='$movimentacao_w'>
		<input type='hidden' name='peso_sacaria_form' value='$peso_w'>
		<input type='hidden' name='capacidade_sacaria_form' value='$capacidade_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='descricao_sacaria_form' value='$descricao_w'>
		<input type='hidden' name='movimentacao_sacaria_form' value='$movimentacao_w'>
		<input type='hidden' name='peso_sacaria_form' value='$peso_w'>
		<input type='hidden' name='capacidade_sacaria_form' value='$capacidade_w'>
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
<i>Nenhuma sacaria cadastrada.</i></div>";}
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