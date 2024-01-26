<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_sacaria';
$titulo = 'Cadastro de Sacaria';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data_x){
	if (strstr($data_x, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data_x);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor_x){
	$valor_1 = str_replace(".", "", $valor_x);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
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

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVA SACARIA ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[97] == "S")
{

// CONTADOR CÓDIGO SACARIA
/*
$busca_codigo_sacaria = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bcs = mysqli_fetch_row($busca_codigo_sacaria);
$codigo_sacaria = $aux_bcs[26];
$contador_codigo_sacaria = $codigo_sacaria + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_codigo_sacaria='$contador_codigo_sacaria'");
*/

// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO select_tipo_sacaria (codigo, descricao, peso, movimentacao, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro, capacidade) VALUES (NULL, '$descricao_sacaria_form', '$peso_sacaria_form',  '$movimentacao_sacaria_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form', '$capacidade_sacaria_form')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria cadastrada com sucesso!</div>";

$descricao_sacaria_form = "";
$movimentacao_sacaria_form = "";
$peso_sacaria_form = "";
$peso_sacaria_print = "";
$capacidade_sacaria_form = "";
$capacidade_sacaria_print = "";
}

elseif ($permissao[97] != "S")
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
if ($botao == "EDITAR" and $erro == 0)
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

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR SACARIA =================================================================================
if ($botao == "ATIVAR")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria ativada com sucesso!</div>";
}

elseif ($botao == "INATIVAR")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE codigo='$codigo_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria inativada com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR SACARIA ============================================================================================
if ($botao == "EXCLUIR" and $erro == 0)
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE select_tipo_sacaria SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE codigo='$codigo_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Sacaria exclu&iacute;da com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE estado_registro!='EXCLUIDO' ORDER BY movimentacao, peso");
$linha_sacaria = mysqli_num_rows ($busca_sacaria);
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
    Cadastro de Sacaria
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:700px; float:left; text-align:left">
	<?php echo"$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:390px; float:right; text-align:right">
    <!-- <a href="<?php //echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a> -->
    </div>
</div>
<!-- ============================================================================================================= -->

<div class="pqa" style="height:63px">
<!-- ======================================= FORMULARIO ========================================================== -->
<div class="form" style="width:1240px; height:17px; border:1px solid transparent; margin-left:5px; margin-top:5px">
	<div class="form_rotulo" style="width:72px; height:15px; border:1px solid transparent"></div>
    <div class="form_rotulo" style="width:319px; height:15px; border:1px solid transparent">Nome da Sacaria</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Peso de Tara (Kg)</div>
	<div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Capacidade (Kg)</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Movimenta&ccedil;&atilde;o</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- BOTAO --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="width:1250px; height:28px; border:1px solid transparent">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/producao/cadastro_sacaria.php" method="post" />
    <?php
	if ($botao == "EDICAO")
	{echo "
	<input type='hidden' name='botao' value='EDITAR' />
	<input type='hidden' name='codigo_w' value='$codigo_w' />";}
	else
	{echo "<input type='hidden' name='botao' value='CADASTRAR' />";}
	?>
    

	<div class="form_rotulo" style="width:72px; height:26px; border:1px solid transparent"></div>

	<div style="width:319px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="descricao_sacaria_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$descricao_sacaria_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="peso_sacaria_form" class="form_input" maxlength="9" onkeypress="mascara(this,m_quantidade_kg)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$peso_sacaria_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="capacidade_sacaria_form" class="form_input" maxlength="9" onkeypress="mascara(this,m_quantidade)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$capacidade_sacaria_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
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
	</div>
	
	<div class="contador_text" style="width:400px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_sacaria == 0)
        {echo"";}
        elseif ($linha_sacaria == 1)
        {echo"$linha_sacaria Sacaria cadastrada";}
        else
        {echo"$linha_sacaria Sacarias cadastradas";}
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
if ($linha_sacaria == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_sacaria == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='350px' height='20px' align='center' bgcolor='#006699'>Sacaria</td>
<td width='160px' align='center' bgcolor='#006699'>Peso de Tara</td>
<td width='160px' align='center' bgcolor='#006699'>Capacidade</td>
<td width='160px' align='center' bgcolor='#006699'>Movimenta&ccedil;&atilde;o</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Ativar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_sacaria ; $x++)
{
$aux_sacaria = mysqli_fetch_row($busca_sacaria);

// ====== DADOS DO ROMANEIO ============================================================================
$codigo_w = $aux_sacaria[0];
$descricao_w = $aux_sacaria[1];
$peso_w = number_format($aux_sacaria[2],3,",",".");
$capacidade_w = number_format($aux_sacaria[14],0,",",".");
$movimentacao_w = $aux_sacaria[3];
$estado_registro_w = $aux_sacaria[4];

$usuario_cadastro_w = $aux_sacaria[5];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_sacaria[6]));
$hora_cadastro_w = $aux_sacaria[7];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($permissao[98] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
if ($permissao[98] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($permissao[99] == "S")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' title=' C&oacute;digo: $codigo_w &#13; $dados_cadastro_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$descricao_w</div></td>
<td width='160px' align='center'>$peso_w Kg</td>
<td width='160px' align='center'>$capacidade_w Kg</td>
<td width='160px' align='center'>$movimentacao_w</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='hidden' name='descricao_sacaria_form' value='$descricao_w'>
		<input type='hidden' name='movimentacao_sacaria_form' value='$movimentacao_w'>
		<input type='hidden' name='peso_sacaria_form' value='$peso_w'>
		<input type='hidden' name='capacidade_sacaria_form' value='$capacidade_w'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='ATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_inativo.png' height='20px' border='0' />
		</form>	
		</td>";
	}

	elseif ($permite_ativar == "SIM" and $estado_registro_w == "ATIVO")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='INATIVAR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_sacaria.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EXCLUIR'>
		<input type='hidden' name='codigo_w' value='$codigo_w'>
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