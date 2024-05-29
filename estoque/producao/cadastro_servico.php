<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");

$pagina = 'cadastro_servico';
$titulo = 'Cadastro de Servi&ccedil;os';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================

// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$nome_servico_form = $_POST["nome_servico_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$codigo_servico_w = $_POST["codigo_servico_w"];
$id_w = $_POST["id_w"];

if ($botao == "EDICAO")
{$valor_servico_form = $_POST["valor_servico_form"];}
else
{$valor_servico_form = Helpers::ConverteValor($_POST["valor_servico_form"]);}

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
$inserir = mysqli_query ($conexao, "INSERT INTO cad_servico_producao (id, codigo_servico, nome_servico, cod_produto, valor, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro) VALUES (NULL, '$codigo_servico', '$nome_servico_form', '$cod_produto_form', '$valor_servico_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o cadastrado com sucesso!</div>";

$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
}

elseif ($permissao[100] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar novo servi&ccedil;o</div>";

$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
}


else
{}
// ==================================================================================================================


// ====== EDITAR SERVIÇO ============================================================================================
if ($botao == "EDITAR" and $erro == 0)
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET nome_servico='$nome_servico_form', cod_produto='$cod_produto_form', valor='$valor_servico_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro de servi&ccedil;o editado com sucesso!</div>";

$nome_servico_form = "";
$cod_produto_form = "";
$valor_servico_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR SERVIÇO =================================================================================
if ($botao == "ATIVAR")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o inativado com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR SERVIÇO ============================================================================================
if ($botao == "EXCLUIR" and $erro == 0)
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cad_servico_producao SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Servi&ccedil;o exclu&iacute;do com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_servico = mysqli_query ($conexao, "SELECT * FROM cad_servico_producao WHERE estado_registro!='EXCLUIDO' ORDER BY cod_produto, nome_servico");
$linha_servico = mysqli_num_rows ($busca_servico);
// ==================================================================================================================


// =================================================================================================================
include ('../../includes/head.php'); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>

// Função oculta DIV depois de alguns segundos
setTimeout(function() {
   $('#oculta').fadeOut('fast');
}, 3000); // 3 Segundos

</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div id="menu_geral">
<?php include ('../../includes/menu_estoque.php'); ?>
<?php include ('../../includes/sub_menu_estoque_movimentacao.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Cadastro de Servi&ccedil;os
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
    <!-- <a href="<?php //echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a> -->
    </div>
</div>
<!-- ============================================================================================================= -->

<div class="pqa" style="height:63px">
<!-- ======================================= FORMULARIO ========================================================== -->
<div class="form" style="width:1240px; height:17px; border:1px solid transparent; margin-left:5px; margin-top:5px">
	<div class="form_rotulo" style="width:72px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:319px; height:15px; border:1px solid transparent">Nome do Servi&ccedil;o</div>
    <div class="form_rotulo" style="width:200px; height:15px; border:1px solid transparent">Produto</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Valor (R$)</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- BOTAO --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="width:1250px; height:28px; border:1px solid transparent">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/producao/cadastro_servico.php" method="post" />
    <?php
	if ($botao == "EDICAO")
	{echo "
	<input type='hidden' name='botao' value='EDITAR' />
	<input type='hidden' name='id_w' value='$id_w' />";}
	else
	{echo "<input type='hidden' name='botao' value='CADASTRAR' />";}
	?>
    

	<div class="form_rotulo" style="width:72px; height:26px; border:1px solid transparent"></div>

	<div style="width:319px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="nome_servico_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$nome_servico_form"; ?>" />
    </div>

	<div style="width:200px; height:auto; float:left; border:1px solid transparent">
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

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="valor_servico_form" class="form_input" maxlength="13" onkeypress="mascara(this,mvalor)"  
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$valor_servico_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
	<?php
	if ($botao == "EDICAO")
	{echo "<button type='submit' class='botao_1'>Salvar</button>";}
	else
	{echo "<button type='submit' class='botao_1'>Cadastrar</button>";}
	?>
    </form>
    </div>

</div>
<!-- ============================================================================================================= -->

</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:400px; float:left; margin-left:25px; text-align:left">
	<?php
	/*
	if ($linha_servico >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/entrada/entrada_relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='numero_romaneio_w' value='$numero_romaneio_w'>
	<input type='hidden' name='data_inicial' value='$data_inicial_br'>
	<input type='hidden' name='data_final' value='$data_final_br'>
	<input type='hidden' name='cod_produto_form' value='$cod_produto_form'>
	<input type='hidden' name='fornecedor_form' value='$fornecedor_form'>
	<input type='hidden' name='numero_romaneio_form' value='$numero_romaneio_form'>
	<input type='hidden' name='situacao_romaneio_form' value='$situacao_romaneio_form'>
	<input type='hidden' name='forma_pesagem_form' value='$forma_pesagem_form'>
	<button type='submit' class='botao_1' style='margin-left:0px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
	*/
	?>
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php
		/*
        if ($linha_servico == 0)
        {}
        elseif ($linha_servico == 1)
        {echo"$linha_servico Servi&ccedil;o cadastrado";}
        else
        {echo"$linha_servico Armaz&eacute;ns cadastrados";}
        */
		?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_servico == 0)
        {}
        elseif ($linha_servico == 1)
        {echo"$linha_servico Servi&ccedil;o cadastrado";}
        else
        {echo"$linha_servico Servi&ccedil;os cadastrados";}

		/*
        if ($linha_servico >= 1)
        {echo"Total de Entrada: <b>$soma_romaneio_print Kg</b>";}
        else
        {}
		*/
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
// ======================================================================================================
if ($linha_servico == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_servico == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='350px' height='20px' align='center' bgcolor='#006699'>Servi&ccedil;o</td>
<td width='180px' align='center' bgcolor='#006699'>Produto</td>
<td width='180px' align='center' bgcolor='#006699'>Valor</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Ativar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_servico ; $x++)
{
$aux_servico = mysqli_fetch_row($busca_servico);

// ====== DADOS DO ROMANEIO ============================================================================
$id_w = $aux_servico[0];
$codigo_servico_w = $aux_servico[1];
$nome_servico_w = $aux_servico[2];
$codigo_produto_w = $aux_servico[3];
$valor_servico_w = number_format($aux_servico[4],2,",",".");

$estado_registro_w = $aux_servico[7];

$usuario_cadastro_w = $aux_servico[8];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_servico[9]));
$hora_cadastro_w = $aux_servico[10];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$codigo_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[1];
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($permissao[101] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
if ($permissao[101] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($permissao[102] == "S")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' C&oacute;digo: $codigo_servico_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' title=' C&oacute;digo: $codigo_servico_w &#13; $dados_cadastro_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_servico_w</div></td>
<td width='180px' align='center'>$produto_print</td>
<td width='180px' align='center'>R$ $valor_servico_w</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_servico_w' value='$codigo_servico_w'>
		<input type='hidden' name='nome_servico_form' value='$nome_servico_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
		<input type='hidden' name='valor_servico_form' value='$valor_servico_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_editar_2.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO ATIVAR / INATIVAR ==================================================================================
	if ($permite_ativar == "SIM" and $estado_registro_w == "INATIVO")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_ativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


// ====== BOTAO EXCLUIR ===================================================================================================
	if ($permite_excluir == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_servico.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUIR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_excluir.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	else
	{
		echo "
		<td width='54px' align='center'></td>";
	}
// =================================================================================================================


}

echo "</table>";
// =================================================================================================================


// =================================================================================================================
echo "
<div id='centro' style='height:20px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_4 -->
<div id='centro' style='height:30px; width:1250px; border:0px solid #000; margin:auto'></div>
</div>		<!-- FIM DIV centro_3 -->";
// =================================================================================================================

?>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<div class="espacamento_40"></div>
<div class="espacamento_40"></div>
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->




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