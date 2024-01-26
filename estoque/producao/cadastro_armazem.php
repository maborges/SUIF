<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_armazem';
$titulo = 'Cadastro de Armaz&eacute;m';
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
$nome_armazem_form = $_POST["nome_armazem_form"];
$filial_armazem_form = $_POST["filial_armazem_form"];
$codigo_armazem_w = $_POST["codigo_armazem_w"];
$id_w = $_POST["id_w"];

if ($botao == "EDICAO")
{$capacidade_max_form = $_POST["capacidade_max_form"];}
else
{$capacidade_max_form = ConvertePeso($_POST["capacidade_max_form"]);}

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
$inserir = mysqli_query ($conexao, "INSERT INTO cadastro_armazem (id, codigo_armazem, nome_armazem, filial, estado_registro, capacidade_maxima, usuario_cadastro, data_cadastro, hora_cadastro) VALUES (NULL, '$codigo_armazem', '$nome_armazem_form', '$filial_armazem_form', 'ATIVO', '$capacidade_max_form', '$usuario_cadastro_form', '$data_cadastro_form', '$hora_cadastro_form')");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m cadastrado com sucesso!</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

elseif ($permissao[94] != "S")
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
if ($botao == "EDITAR" and $erro == 0)
{
// EDIÇÃO
$editar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET nome_armazem='$nome_armazem_form', filial='$filial_armazem_form', capacidade_maxima='$capacidade_max_form', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Cadastro editado com sucesso!</div>";

$nome_armazem_form = "";
$filial_armazem_form = "";
$capacidade_max_form = "";
}

else
{}
// ==================================================================================================================


// ====== ATIVAR / INATIVAR ARMAZEM =================================================================================
if ($botao == "ATIVAR")
{
// ATIVAR
$ativar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='ATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m ativado com sucesso!</div>";
}

elseif ($botao == "INATIVAR")
{
// INATIVAR
$inativar = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='INATIVO', usuario_alteracao='$usuario_cadastro_form', data_alteracao='$data_cadastro_form', hora_alteracao='$hora_cadastro_form' WHERE id='$id_w'");

// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m inativado com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== EXCLUIR ARMAZEM ============================================================================================
if ($botao == "EXCLUIR" and $erro == 0)
{
// EXCLUSAO
$excluir = mysqli_query ($conexao, "UPDATE cadastro_armazem SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_cadastro_form', data_exclusao='$data_cadastro_form', hora_exclusao='$hora_cadastro_form' WHERE id='$id_w'");


// MONTA MENSAGEM
$msg = "<div id='oculta' style='color:#0000FF'>Armaz&eacute;m exclu&iacute;do com sucesso!</div>";
}

else
{}
// ==================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_armazem = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE estado_registro!='EXCLUIDO' ORDER BY id");
$linha_armazem = mysqli_num_rows ($busca_armazem);
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
    Cadastro de Armaz&eacute;m
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
    <div class="form_rotulo" style="width:319px; height:15px; border:1px solid transparent">N&deg; / Nome do Armaz&eacute;m</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Filial</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent">Capacidade M&aacute;xima (Kg)</div>
    <div class="form_rotulo" style="width:174px; height:15px; border:1px solid transparent"><!-- BOTAO --></div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="form" style="width:1250px; height:28px; border:1px solid transparent">
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/producao/cadastro_armazem.php" method="post" />
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
    <input type="text" name="nome_armazem_form" class="form_input" maxlength="30" onBlur="alteraMaiusculo(this)" 
    onkeydown="if (getKey(event) == 13) return false;" style="width:290px; text-align:left; padding-left:5px" value="<?php echo"$nome_armazem_form"; ?>" />
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <select name="filial_armazem_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px" />
    <option></option>
	<?php
    $busca_filial_armazem = mysqli_query ($conexao, "SELECT * FROM filiais WHERE estado_registro!='EXCLUIDO' ORDER BY codigo");
    $linhas_filial_armazem = mysqli_num_rows ($busca_filial_armazem);
    
    for ($f=1 ; $f<=$linhas_filial_armazem ; $f++)
    {
    $aux_filial_armazem = mysqli_fetch_row($busca_filial_armazem);	

    if ($aux_filial_armazem[1] == $filial_armazem_form)
    {echo "<option selected='selected' value='$aux_filial_armazem[1]'>$aux_filial_armazem[2]</option>";}
    else
    {echo "<option value='$aux_filial_armazem[1]'>$aux_filial_armazem[2]</option>";}
    }
    ?>
    </select>
    </div>

	<div style="width:174px; height:auto; float:left; border:1px solid transparent">
    <input type="text" name="capacidade_max_form" class="form_input" maxlength="13" onkeypress="mascara(this,m_quantidade)"  
    onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$capacidade_max_form"; ?>" />
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
	if ($linha_armazem >= 1)
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
        if ($linha_armazem == 0)
        {}
        elseif ($linha_armazem == 1)
        {echo"$linha_armazem Armaz&eacute;m cadastrado";}
        else
        {echo"$linha_armazem Armaz&eacute;ns cadastrados";}
        */
		?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_armazem == 0)
        {}
        elseif ($linha_armazem == 1)
        {echo"$linha_armazem Armaz&eacute;m cadastrado";}
        else
        {echo"$linha_armazem Armaz&eacute;ns cadastrados";}

		/*
        if ($linha_armazem >= 1)
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
if ($linha_armazem == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_armazem == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='350px' height='20px' align='center' bgcolor='#006699'>Armaz&eacute;m</td>
<td width='160px' align='center' bgcolor='#006699'>Filial</td>
<td width='160px' align='center' bgcolor='#006699'>Capacidade</td>
<td width='54px' align='center' bgcolor='#006699'>Editar</td>
<td width='54px' align='center' bgcolor='#006699'>Ativar</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_armazem ; $x++)
{
$aux_armazem = mysqli_fetch_row($busca_armazem);

// ====== DADOS DO ROMANEIO ============================================================================
$id_w = $aux_armazem[0];
$codigo_armazem_w = $aux_armazem[1];
$nome_armazem_w = $aux_armazem[2];
$filial_armazem_w = $aux_armazem[3];
$estado_registro_w = $aux_armazem[4];
$capacidade_armazem_w = number_format($aux_armazem[5],0,",",".");

$usuario_cadastro_w = $aux_armazem[6];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_armazem[7]));
$hora_cadastro_w = $aux_armazem[8];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($permissao[95] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA ATIVAR ========================================================================
if ($permissao[95] == "S")
{$permite_ativar = "SIM";}
else
{$permite_ativar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($permissao[96] == "S")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' C&oacute;digo: $codigo_armazem_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' title=' C&oacute;digo: $codigo_armazem_w &#13; $dados_cadastro_w'>";}

echo "
<td width='350px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_armazem_w</div></td>
<td width='160px' align='center'>$filial_armazem_w</td>
<td width='160px' align='center'>$capacidade_armazem_w Kg</td>";

// ====== BOTAO EDITAR ===================================================================================================
	if ($permite_editar == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_armazem.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='EDICAO'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='codigo_armazem_w' value='$codigo_armazem_w'>
		<input type='hidden' name='nome_armazem_form' value='$nome_armazem_w'>
		<input type='hidden' name='filial_armazem_form' value='$filial_armazem_w'>
		<input type='hidden' name='capacidade_max_form' value='$capacidade_armazem_w'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_armazem.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_armazem.php' method='post'>
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
		<form action='$servidor/$diretorio_servidor/estoque/producao/cadastro_armazem.php' method='post'>
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