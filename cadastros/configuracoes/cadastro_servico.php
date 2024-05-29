<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include ("../../helpers.php");

$pagina = "cadastro_servico";
$titulo = "Cadastro de Servi&ccedil;os (Produ&ccedil;&atilde;o)";
$modulo = "cadastros";
$menu = "config";
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$nome_servico_form = $_POST["nome_servico_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$codigo_servico_w = $_POST["codigo_servico_w"];
$id_w = $_POST["id_w"];
$valor_servico_form = Helpers::ConverteValor($_POST["valor_servico_form"]);
$valor_servico_print = $_POST["valor_servico_form"];
$bloqueio_w = $_POST["bloqueio_w"];


$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== CRIA MENSAGEM =============================================================================================
if ($botao == "CADASTRAR" and $nome_servico_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Digite o nome do servi&ccedil;o</div>";}

elseif ($botao == "CADASTRAR" and $cod_produto_form == "")
{$erro = 2;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";}

elseif ($botao == "CADASTRAR" and ($valor_servico_form == "" or $valor_servico_form < 0))
{$erro = 3;
$msg = "<div style='color:#FF0000'>Informe o valor do servi&ccedil;o</div>";}

elseif ($botao == "EDITAR" and $nome_servico_form == "")
{$erro = 4;
$msg = "<div style='color:#FF0000'>Digite o nome do servi&ccedil;o</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_produto_form == "")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and ($valor_servico_form == "" or $valor_servico_form < 0))
{$erro = 6;
$msg = "<div style='color:#FF0000'>Informe o valor do servi&ccedil;o</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$id_w = "";
}

elseif ($botao == "EXCLUSAO")
{$erro = 7;
$msg = "<div style='color:#FF0000'>Deseja realmente excluir este servi&ccedil;o?</div>";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVO SERVIÇO ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[100] == "S")
{
// CONTADOR CÓDIGO SERVIÇO
$busca_codigo_servico = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bcs = mysqli_fetch_row($busca_codigo_servico);
$codigo_servico = $aux_bcs[19];
$contador_codigo_servico = $codigo_servico + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_codigo_servico='$contador_codigo_servico'");

// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO cad_servico_producao (id, codigo_servico, nome_servico, cod_produto, valor, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro, bloqueio) VALUES (NULL, '$codigo_servico', '$nome_servico_form', '$cod_produto_form', '$valor_servico_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', 'NAO')");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o cadastrado com sucesso!</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

elseif ($botao == "CADASTRAR" and $permissao[100] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar novo servi&ccedil;o</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

else
{}
// ==================================================================================================================


// ====== EDITAR SERVIÇO ============================================================================================
if ($botao == "EDITAR" and $erro == 0 and $permissao[101] == "S")
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET nome_servico='$nome_servico_form', cod_produto='$cod_produto_form', valor='$valor_servico_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro de servi&ccedil;o editado com sucesso!</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

elseif ($botao == "EDITAR" and $permissao[101] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar servi&ccedil;o</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR SERVIÇO =================================================================================
if ($botao == "ATIVAR" and $permissao[101] == "S")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR" and $permissao[101] == "S")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o inativado com sucesso!</div>";
}

elseif (($botao == "INATIVAR" or $botao == "ATIVAR") and $permissao[101] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para editar servi&ccedil;o</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR SERVIÇO ============================================================================================
if ($botao == "EXCLUIR" and $permissao[102] == "S")
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o exclu&iacute;do com sucesso!</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

elseif ($botao == "EXCLUIR" and $permissao[102] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para excluir servi&ccedil;o</div>";
$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
$valor_servico_print = "";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cad_servico_producao WHERE estado_registro!='EXCLUIDO' ORDER BY nome_servico");
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

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo"$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskMoney.js"></script>

<script>
jQuery(function($){

	// VALOR MONETÁRIO (R$ 8.888,88)
	$("#valor_money").maskMoney({
		symbol:'R$ ', //Símbolo a ser usado antes de os valores do usuário. padrão: ‘EUA $’
		showSymbol:true, //definir se o símbolo deve ser exibida ou não. padrão: false
		thousands:'.', //Separador de milhares. padrão: ‘,’
		decimal:',', //Separador do decimal. padrão: ‘.’
		precision:2, //Quantas casas decimais são permitidas. Padrão: 2
		symbolStay:true //definir se o símbolo vai ficar no campo após o usuário existe no campo. padrão: false
	});

});
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
    {echo "<i>$linha_registro Servi&ccedil;o cadastrado</i>";}
    elseif ($linha_registro == 0)
    {echo "";}
    else
    {echo "<i>$linha_registro Servi&ccedil;os cadastrados</i>";}
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
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/cadastros/configuracoes/cadastro_servico.php" method="post" />
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


<!-- ======= NOME DO SERVIÇO ======================================================================================== -->
<div style="width:320px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:315px; height:17px; border:1px solid transparent; float:left">
    Nome do Servi&ccedil;o:
    </div>
    
    <div style="width:315px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="nome_servico_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$nome_servico_form"; ?>" />
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


<!-- ======= VALOR DO SERVIÇO ======================================================================================= -->
<div style="width:154px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
    <div class="form_rotulo" style="width:150px; height:17px; border:1px solid transparent; float:left">
    Valor do Servi&ccedil;o:
    </div>
    
    <div style="width:150px; height:25px; float:left; border:1px solid transparent">
    <input type="text" name="valor_servico_form" class="form_input" maxlength="12" id='valor_money' 
    onkeydown="if (getKey(event) == 13) return false;" style="width:125px; text-align:left; padding-left:5px" value="<?php echo"$valor_servico_print"; ?>" />
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
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post' />
	<button type='submit' class='botao_1'>Cancelar</button>
	</form>";}

	elseif ($botao == "EXCLUSAO")
	{echo "
	<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post' />
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
<td width='350px'>Servi&ccedil;o</td>
<td width='180px'>Produto</td>
<td width='180px'>Valor</td>
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
$codigo_servico_w = $aux_registro[1];
$nome_servico_w = $aux_registro[2];
$codigo_produto_w = $aux_registro[3];
$valor_servico_w = "R$ " . number_format($aux_registro[4],2,",",".");
$bloqueio_w = $aux_registro[17];

$estado_registro_w = $aux_registro[7];

$usuario_cadastro_w = $aux_registro[8];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[9]));
$hora_cadastro_w = $aux_registro[10];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_registro[11];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[12]));
$hora_alteracao_w = $aux_registro[13];
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
{echo "<tr class='tabela_1' height='34px' title=' C&oacute;digo: $codigo_servico_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}
else
{echo "<tr class='tabela_4' height='34px' title=' C&oacute;digo: $codigo_servico_w &#13; $dados_cadastro_w &#13; $dados_alteracao_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_servico_w</div></td>
<td width='180px' align='center'>$produto_print</td>
<td width='180px' align='center'>$valor_servico_w</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='60px' align='center'>
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_servico_w' value='$codigo_servico_w'>
		<input type='hidden' name='nome_servico_form' value='$nome_servico_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
		<input type='hidden' name='valor_servico_form' value='$valor_servico_w'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/cadastros/configuracoes/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUSAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_servico_w' value='$codigo_servico_w'>
		<input type='hidden' name='nome_servico_form' value='$nome_servico_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
		<input type='hidden' name='valor_servico_form' value='$valor_servico_w'>
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
<i>Nenhum servi&ccedil;o cadastrado.</i></div>";}
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