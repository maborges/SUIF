<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'index_controle_lote';
$titulo = 'Controle de Lote';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$cod_armazem_form = $_POST["cod_armazem_form"];
$id_w = $_POST["id_w"];
$codigo_lote_w = $_POST["codigo_lote_w"];
$filial = $filial_usuario;

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_lote = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE estado_registro!='EXCLUIDO' AND cod_armazem='$cod_armazem_form' AND filial='$filial' ORDER BY id");
$linha_lote = mysqli_num_rows ($busca_lote);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM cadastro_lote WHERE estado_registro!='EXCLUIDO' AND cod_armazem='$cod_armazem_form' AND filial='$filial' ORDER BY id");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

$soma_lote = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(saldo_armazenado) FROM cadastro_lote WHERE estado_registro!='EXCLUIDO' AND cod_armazem='$cod_armazem_form' AND filial='$filial'"));
$soma_lote_print = number_format($soma_lote[0],0,",",".");
// ==================================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  ===============================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// ===============================================================================================================


// ================================================================================================================
include ('../../includes/head.php'); 
?>


<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
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
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; margin-top:8px; border:0px solid #000">
    <!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<!-- xxxxxxxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right">
	<!-- <a href="<?php // echo"$servidor/$diretorio_servidor"; ?>/estoque/entrada/relatorios.php">&#8226; Outros relat&oacute;rios de Entradas</a> -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="pqa">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/index_controle_lote.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR_LOTE' />

	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Armaz&eacute;m:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_armazem_form" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option></option>
    <?php
	$busca_armazem_list = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE estado_registro='ATIVO' ORDER BY nome_armazem");
	$linhas_armazem_list = mysqli_num_rows ($busca_armazem_list);

	for ($a=1 ; $a<=$linhas_armazem_list ; $a++)
	{
		$aux_armazem_list = mysqli_fetch_row ($busca_armazem_list);	
		if ($aux_armazem_list[1] == $cod_armazem_form)
		{
		echo "<option selected='selected' value='$aux_armazem_list[1]'>$aux_armazem_list[2]</option>";
		}
		else
		{
		echo "<option value='$aux_armazem_list[1]'>$aux_armazem_list[2]</option>";
		}
	}
    ?>
    </select>
    </div>


<!--
    <div class="pqa_rotulo" style="height:20px; width:135px; border:0px solid #000">Lote:</div>

	<div style="height:34px; width:160px; border:0px solid #999; float:left">
	<select class="pqa_select" name="cod_armazem_form" onkeydown="if (getKey(event) == 13) return false;" style="width:140px" />
    <option></option>
    <?php
	/*
	$busca_armazem_list = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE estado_registro='ATIVO' ORDER BY nome_armazem");
	$linhas_armazem_list = mysqli_num_rows ($busca_armazem_list);

	for ($a=1 ; $a<=$linhas_armazem_list ; $a++)
	{
		$aux_armazem_list = mysqli_fetch_row ($busca_armazem_list);	
		if ($aux_armazem_list[0] == $cod_armazem_form)
		{
		echo "<option selected='selected' value='$aux_armazem_list[1]'>$aux_armazem_list[2]</option>";
		}
		else
		{
		echo "<option value='$aux_armazem_list[1]'>$aux_armazem_list[2]</option>";
		}
	}
	*/
    ?>
    </select>
    </div>
-->

	<div style="height:34px; width:46px; border:0px solid #999; color:#666; font-size:11px; float:left; margin-left:10px; margin-top:5px">
    <button type='submit' class='botao_1'>Buscar</button>
    </form>
	</div>

</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">

	<div class="contador_text" style="width:600px; float:left; margin-left:25px; text-align:left">
	<?php

	if ($permissao[112] == "S")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/controle_lote/cadastro_1_selec_produto.php'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Movimenta&ccedil;&atilde;o Interna</button></a>";}
	else
	{echo "<button type='submit' class='botao_1' style='color:#BBB; margin-left:10px'>Movimenta&ccedil;&atilde;o Interna</button>";}

	if ($permissao[112] == "S")
	{echo "<a href='$servidor/$diretorio_servidor/estoque/controle_lote/romaneio_1_selec_produto.php'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Movimenta&ccedil;&atilde;o com Romaneio</button></a>";}
	else
	{echo "<button type='submit' class='botao_1' style='color:#BBB; margin-left:10px'>Movimenta&ccedil;&atilde;o com Romaneio</button>";}


/*
	if ($linha_romaneio >= 1)
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
	<button type='submit' class='botao_1' style='margin-left:10px'>Imprimir Relat&oacute;rio</button>
	</form>";}
	else
	{}
*/
	?>
	</div>
	
	<div class="contador_text" style="width:200px; float:left; margin-left:0px; text-align:center">
    	<div class="contador_interno">
		<?php 
        if ($linha_lote == 0)
        {}
        elseif ($linha_lote == 1)
        {echo"$linha_lote Lote";}
        else
        {echo"$linha_lote Lotes";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_lote >= 1)
        {echo"Total Armazenado: <b>$soma_lote_print Kg</b>";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('include_totalizador.php'); ?>
<!-- ============================================================================================================= -->

<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php
// ======================================================================================================
if ($linha_lote == 0)
{echo "<div id='centro_3_relatorio'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1255px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1250px; margin:auto; border:1px solid #999; border-radius:0px'>";}

echo "<div id='centro' style='height:20px; width:1075px; border:0px solid #000; margin:auto'></div>";

if ($linha_lote == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:10px'>
<tr>
<td width='200px' height='20px' align='center' bgcolor='#006699'>Lote</td>
<td width='240px' align='center' bgcolor='#006699'>Produto</td>
<td width='200px' align='center' bgcolor='#006699'>Tipo</td>
<td width='170px' align='center' bgcolor='#006699'>Quantidade Kg</td>
<td width='170px' align='center' bgcolor='#006699'>Quantidade Bags</td>
<td width='170px' align='center' bgcolor='#006699'>Sacaria</td>
<td width='54px' align='center' bgcolor='#006699'>Visualizar</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_lote ; $x++)
{
$aux_lote = mysqli_fetch_row($busca_lote);

// ====== DADOS DO LOTE ============================================================================
$id_w = $aux_lote[0];
$codigo_lote_w = $aux_lote[1];
$nome_lote_w = $aux_lote[2];
$endereco_lote_w = $aux_lote[3];
$codigo_armazem_w = $aux_lote[4];
$codigo_produto_w = $aux_lote[5];
$codigo_sacaria_w = $aux_lote[20];
$cod_tipo_producao_w = $aux_lote[22];
$quant_minima_w = number_format($aux_lote[6],0,",",".");
$quant_maxima_w = number_format($aux_lote[7],0,",",".");
$saldo_armazenado_w = number_format($aux_lote[19],0,",",".");
$saldo_armaz_aux = $aux_lote[19];
$umidade_w = $aux_lote[24];
$densidade_w = $aux_lote[25];
$impureza_w = $aux_lote[26];
$broca_w = $aux_lote[27];
$filial_w = $aux_lote[8];
$estado_registro_w = $aux_lote[9];
$numero_romaneio_w = $aux_lote[28];
$cod_fornecedor_w = $aux_lote[29];

$usuario_cadastro_w = $aux_lote[10];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_lote[11]));
$hora_cadastro_w = $aux_lote[12];
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


// ====== BUSCA TIPO PRODUCAO ===================================================================================
$busca_tipo_producao = mysqli_query ($conexao, "SELECT * FROM cad_tipo_producao WHERE codigo_tipo='$cod_tipo_producao_w'");
$aux_tp = mysqli_fetch_row($busca_tipo_producao);

$tipo_producao_print = $aux_tp[2];
// ======================================================================================================


// ====== BUSCA SACARIA ===================================================================================
$busca_sacaria_2 = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE codigo='$codigo_sacaria_w'");
$aux_bs_2 = mysqli_fetch_row($busca_sacaria_2);

$nome_sacaria_print = $aux_bs_2[1];
$capacidade_sacaria = $aux_bs_2[14];
$saldo_armaz_sacaria = ($saldo_armaz_aux / $capacidade_sacaria);
$saldo_armaz_sc_print = number_format($saldo_armaz_sacaria,0,",",".");
// ======================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' Sacaria: $nome_sacaria_print ($capacidade_sacaria Kg) &#13; Umidade: $umidade_w &#13; Impureza: $impureza_w &#13; Densidade: $densidade_w &#13; Broca: $broca_w &#13; &Uacute;ltimo Romaneio: $numero_romaneio_w &#13; &Uacute;ltimo Fornecedor: '>";}
else
{echo "<tr class='tabela_4' title=' Sacaria: $nome_sacaria_print ($capacidade_sacaria Kg) &#13; Umidade: $umidade_w &#13; Impureza: $impureza_w &#13; Densidade: $densidade_w &#13; Broca: $broca_w &#13; &Uacute;ltimo Romaneio: $numero_romaneio_w &#13; &Uacute;ltimo Fornecedor: '>";}

echo "
<td width='200px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_lote_w</div></td>
<td width='240px' align='center'>$produto_print</td>
<td width='200px' align='center'>$tipo_producao_print</td>
<td width='170px' align='center'>$saldo_armazenado_w Kg</td>
<td width='170px' align='center'>$saldo_armaz_sc_print</td>
<td width='170px' align='center'>$nome_sacaria_print</td>";

// ====== BOTAO VISUALIZAR ===================================================================================================
echo "
<td width='54px' align='center'>
<form action='$servidor/$diretorio_servidor/estoque/controle_lote/relatorio_lote_periodo.php' method='post'>
<input type='hidden' name='pagina_mae' value='$pagina'>
<input type='hidden' name='botao' value='VISUALIZAR'>
<input type='hidden' name='id_w' value='$id_w'>
<input type='hidden' name='codigo_lote_w' value='$codigo_lote_w'>
<input type='hidden' name='cod_armazem_form' value='$codigo_armazem_w'>
<input type='image' src='$servidor/$diretorio_servidor/imagens/icones/icone_buscar.png' height='20px' border='0' />
</form>	
</td>";
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