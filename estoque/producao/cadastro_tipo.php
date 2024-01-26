<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_tipo';
$titulo = 'Cadastro de Tipo (Produ&ccedil;&atilde;o)';
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
	$peso_2 = str_replace(",", "", $peso_1);
	return $peso_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$nome_tipo_form = $_POST["nome_tipo_form"];
$cod_produto_form = $_POST["cod_produto_form"];
$codigo_tipo_w = $_POST["codigo_tipo_w"];
$id_w = $_POST["id_w"];

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
{$erro = 4;
$msg = "<div style='color:#FF0000'>Digite o nome do tipo do produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
$id_w = "";
}

elseif ($botao == "EDITAR" and $cod_produto_form == "")
{$erro = 5;
$msg = "<div style='color:#FF0000'>Informe o produto</div>";
$nome_tipo_form = "";
$cod_produto_form = "";
$id_w = "";
}

else
{$erro = 0;
$msg = "";}
// ==================================================================================================================


// ====== CADASTRAR NOVO TIPO ====================================================================================
if ($botao == "CADASTRAR" and $erro == 0 and $permissao[103] == "S")
{

// CONTADOR CÓDIGO TIPO
$busca_codigo_tipo = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bcs = mysqli_fetch_row($busca_codigo_tipo);
$codigo_tipo = $aux_bcs[26];
$contador_cod_tipo_prod = $codigo_tipo + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_cod_tipo_prod='$contador_cod_tipo_prod'");


// CADASTRO
$inserir = mysqli_query ($conexao, "INSERT INTO cad_tipo_producao (id, codigo_tipo, nome_tipo, cod_produto, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro) VALUES (NULL, '$codigo_tipo', '$nome_tipo_form', '$cod_produto_form', 'ATIVO', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo de produto cadastrado com sucesso!</div>";

$nome_tipo_form = "";
$cod_produto_form = "";
}

elseif ($permissao[103] != "S")
{
// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#FF0000'>Usu&aacute;rio sem autoriza&ccedil;&atilde;o para cadastrar novo tipo</div>";

$nome_tipo_form = "";
$cod_produto_form = "";
}


else
{}
// ==================================================================================================================


// ====== EDITAR TIPO ============================================================================================
if ($botao == "EDITAR" and $erro == 0)
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cad_tipo_producao SET nome_tipo='$nome_tipo_form', cod_produto='$cod_produto_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo editado com sucesso!</div>";

$nome_tipo_form = "";
$cod_produto_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR TIPO =================================================================================
if ($botao == "ATIVAR")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cad_tipo_producao SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cad_tipo_producao SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo inativado com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR TIPO ============================================================================================
if ($botao == "EXCLUIR" and $erro == 0)
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cad_tipo_producao SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Tipo exclu&iacute;do com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_tipo = mysqli_query ($conexao, "SELECT * FROM cad_tipo_producao WHERE estado_registro!='EXCLUIDO' ORDER BY cod_produto, nome_tipo");
$linha_tipo = mysqli_num_rows ($busca_tipo);
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
    Cadastro de Tipo (Produ&ccedil;&atilde;o)
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
    <div class="form_rotulo" style="width:319px; height:15px; border:1px solid transparent">Nome do Tipo</div>
    <div class="form_rotulo" style="width:200px; height:15px; border:1px solid transparent">Produto</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- BOTAO --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="width:1250px; height:28px; border:1px solid transparent">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/producao/cadastro_tipo.php" method="post" />
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
    <input type="text" name="nome_tipo_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$nome_tipo_form"; ?>" />
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
	if ($linha_tipo >= 1)
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
        if ($linha_tipo == 0)
        {}
        elseif ($linha_tipo == 1)
        {echo"$linha_tipo Tipo cadastrado";}
        else
        {echo"$linha_tipo Armaz&eacute;ns cadastrados";}
        */
		?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_tipo == 0)
        {}
        elseif ($linha_tipo == 1)
        {echo"$linha_tipo Tipo cadastrado";}
        else
        {echo"$linha_tipo Tipos cadastrados";}

		/*
        if ($linha_tipo >= 1)
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
if ($linha_tipo == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_tipo == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='350px' height='20px' align='center' bgcolor='#006699'>Tipo</td>
<td width='180px' align='center' bgcolor='#006699'>Produto</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Ativar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_tipo ; $x++)
{
$aux_tipo = mysqli_fetch_row($busca_tipo);

// ====== DADOS DO ROMANEIO ============================================================================
$id_w = $aux_tipo[0];
$codigo_tipo_w = $aux_tipo[1];
$nome_tipo_w = $aux_tipo[2];
$codigo_produto_w = $aux_tipo[3];

$estado_registro_w = $aux_tipo[6];

$usuario_cadastro_w = $aux_tipo[7];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_tipo[8]));
$hora_cadastro_w = $aux_tipo[9];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$codigo_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);

$produto_print = $aux_bp[1];
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($permissao[104] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
if ($permissao[104] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($permissao[105] == "S")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' C&oacute;digo: $codigo_tipo_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' title=' C&oacute;digo: $codigo_tipo_w &#13; $dados_cadastro_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_tipo_w</div></td>
<td width='180px' align='center'>$produto_print</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_tipo.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_tipo_w' value='$codigo_tipo_w'>
		<input type='hidden' name='nome_tipo_form' value='$nome_tipo_w'>
		<input type='hidden' name='cod_produto_form' value='$codigo_produto_w'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_tipo.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_tipo.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_tipo.php' method='post'>
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