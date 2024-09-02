<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "cadastro_armazem";
$titulo = "Cadastro de Armaz&eacute;m";	
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$nome_armazem_form = $_POST["nome_armazem_form"];
$filial_armazem_form = $_POST["filial_armazem_form"];
$codigo_armazem_w = $_POST["codigo_armazem_w"];
$id_w = $_POST["id_w"];
$bloqueio_w = $_POST["bloqueio_w"];

if ($botao == "EDICAO")
{$capacidade_max_form = $_POST["capacidade_max_form"];}
else
{$capacidade_max_form = Helpers::ConvertePeso($_POST["capacidade_max_form"],'N');}

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $nome_armazem_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite um n&uacute;mero ou nome para o armaz&eacute;m</div>";}

elseif ($botao == "CADASTRAR" and $filial_armazem_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Informe a filial do armaz&eacute;m</div>";}

elseif ($botao == "CADASTRAR" and ($capacidade_max_form == "" or $capacidade_max_form <= 0))
{$erro = 3;
$msg = "<div style='color:#FF0000'>Informe a capacidade m&aacute;xima que o armaz&eacute;m suporta</div>";}

elseif ($botao == "EDITAR" and $nome_armazem_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Digite um n&uacute;mero ou nome para o armaz&eacute;m</div>";
$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $filial_armazem_form == "")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Informe a filial do armaz&eacute;m</div>";
$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and ($capacidade_max_form == "" or $capacidade_max_form <= 0))
{$erro = 6;
$msg = "<div style='color:#FF0000'>Informe a capacidade m&aacute;xima que o armaz&eacute;m suporta</div>";
$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
$id_w = "";
}

elseif ($botao == "EXCLUSAO")
{$erro = 7;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir este armaz&eacute;m?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVO ARMAZEM ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[94] == "S")
{

// CONTADOR CÓDIGO ARMAZEM
$busca_codigo_armazem = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bca = mysqli_fetch_row($busca_codigo_armazem);
$codigo_armazem = $aux_bca[25];
$contador_codigo_armazem = $codigo_armazem + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_codigo_armazem='$contador_codigo_armazem'");


// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO cadastro_armazem (id, codigo_armazem, nome_armazem, filial, estado_registro, capacidade_maxima, usuario_cadastro, data_cadastro, hora_cadastro, bloqueio) VALUES (NULL, '$codigo_armazem', '$nome_armazem_form', '$filial_armazem_form', 'ATIVO', '$capacidade_max_form', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', 'NAO')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m cadastrado com sucesso!</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

elseif ($botao == "CADASTRAR" and $permissao[94] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar armaz&eacute;m</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR ARMAZEM ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[95] == "S")
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET nome_armazem='$nome_armazem_form', filial='$filial_armazem_form', capacidade_maxima='$capacidade_max_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro editado com sucesso!</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

elseif ($botao == "EDITAR" and $permissao[95] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar armaz&eacute;m</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR ARMAZEM =================================================================================
if ($botao == "ATIVAR" and $permissao[95] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[95] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m inativado com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[95] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar armaz&eacute;m</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR ARMAZEM ============================================================================================
if ($botao == "EXCLUIR" and $permissao[96] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m exclu&iacute;do com sucesso!</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

elseif ($botao == "EXCLUIR" and $permissao[96] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir armaz&eacute;m</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE estado_registro!='EXCLUIDO' ORDER BY id");
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
    {echo "<i>$linha_registro Armaz&eacute;m Cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Armaz&eacute;ns Cadastrados</i>";}
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
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/cadastro_armazem.php" method="post" />
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
    N&deg; / Nome do Armaz&eacute;m:
    </div>
    
    <div style="width:215px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_armazem_form" class="form_input" maxlength="30" id="ok" onBlur='alteraMaiusculo(this)' 
    onkeydown="if (getKey(event) == 13) return false;" style="width:191px; text-align:left; padding-left:5px" value="<?php echo"$nome_armazem_form"; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= FILIAL ================================================================================================= -->
<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <select name="filial_armazem_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:134px" />
    <option></option>
	<?php
    $busca_filial_usuario = mysqli_query ($conexao, "SELECT * FROM filiais WHERE estado_registro='ATIVO' ORDER BY codigo");
    $linhas_filial_usuario = mysqli_num_rows ($busca_filial_usuario);
    
    for ($f=1 ; $f<=$linhas_filial_usuario ; $f++)
    {
    $aux_filial_usuario = mysqli_fetch_row($busca_filial_usuario);	

    if ($aux_filial_usuario[1] == $filial_armazem_form)
    {echo "<option selected='selected' value='$aux_filial_usuario[1]'>$aux_filial_usuario[2]</option>";}
    else
    {echo "<option value='$aux_filial_usuario[1]'>$aux_filial_usuario[2]</option>";}
    }
    ?>
    </select>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CAPACIDADE MAXIMA ====================================================================================== -->
<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
    Capacidade M&aacute;x. (Kg):
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="capacidade_max_form" class="form_input" maxlength="13" onkeypress="mascara(this,m_quantidade)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo"$capacidade_max_form"; ?>" />
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
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post' />
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
<td width='350px'>Armaz&eacute;m</td>
<td width='160px'>Filial</td>
<td width='160px'>Capacidade</td>
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
$codigo_armazem_w = $aux_registro[1];
$nome_armazem_w = $aux_registro[2];
$filial_armazem_w = $aux_registro[3];
$estado_registro_w = $aux_registro[4];
$capacidade_armazem_w = number_format($aux_registro[5],0,",",".");
$bloqueio_w = $aux_registro[15];

$usuario_cadastro_w = $aux_registro[6];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[7]));
$hora_cadastro_w = $aux_registro[8];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
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
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $codigo_armazem_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $codigo_armazem_w &#13; $dados_cadastro_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_armazem_w</div></td>
<td width='160px' align='center'>$filial_armazem_w</td>
<td width='160px' align='center'>$capacidade_armazem_w Kg</td>";


// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_armazem_w' value='$codigo_armazem_w'>
		<input type='hidden' name='nome_armazem_form' value='$nome_armazem_w'>
		<input type='hidden' name='filial_armazem_form' value='$filial_armazem_w'>
		<input type='hidden' name='capacidade_max_form' value='$capacidade_armazem_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_armazem.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_armazem_w' value='$codigo_armazem_w'>
		<input type='hidden' name='nome_armazem_form' value='$nome_armazem_w'>
		<input type='hidden' name='filial_armazem_form' value='$filial_armazem_w'>
		<input type='hidden' name='capacidade_max_form' value='$capacidade_armazem_w'>
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
<i>Nenhum armaz&eacute;m cadastrado.</i></div>";}
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