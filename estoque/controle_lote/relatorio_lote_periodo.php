<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ("../../helpers.php");
$pagina = 'relatorio_lote_periodo';
$titulo = 'Relat&oacute;rio de Lote';
$modulo = 'estoque';
$menu = 'movimentacao';
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$botao = $_POST["botao"];
$cod_armazem_form = $_POST["cod_armazem_form"];
$id_w = $_POST["id_w"];
$codigo_lote_w = $_POST["codigo_lote_w"];
$filial = $filial_usuario;

$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial"];
$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
$data_final_br = $_POST["data_final"];
$data_final = Helpers::ConverteData($_POST["data_final"]);
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$mes_atras_br = date ('d/m/Y', strtotime('-30 days'));

if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $mes_atras_br;
	$data_inicial = $mes_atras;
	$data_final_br = $data_hoje_br;
	$data_final = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial"];
	$data_inicial = Helpers::ConverteData($_POST["data_inicial"]);
	$data_final_br = $_POST["data_final"];
	$data_final = Helpers::ConverteData($_POST["data_final"]);}

$mysql_filtro_data = "data BETWEEN '$data_inicial' AND '$data_final'";

$usuario_cadastro_form = $nome_usuario_print;
$data_cadastro_form = date('Y-m-d', time());
$hora_cadastro_form = date('G:i:s', time());
// =================================================================================================================


// ====== BUSCA CADASTROS ==========================================================================================
$busca_lote = mysqli_query ($conexao, "SELECT * FROM estoque_lote WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND cod_lote='$codigo_lote_w' AND filial='$filial' ORDER BY id");
$linha_lote = mysqli_num_rows ($busca_lote);

$busca_produto_distinct = mysqli_query ($conexao, "SELECT DISTINCT cod_produto FROM estoque_lote WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND cod_lote='$codigo_lote_w' AND filial='$filial' ORDER BY id");
$linha_produto_distinct = mysqli_num_rows ($busca_produto_distinct);

/*
$soma_lote = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(peso) FROM estoque_lote WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND cod_lote='$codigo_lote_w' AND filial='$filial'"));
$soma_lote_print = number_format($soma_lote[0],0,",",".");
*/
// ==================================================================================================================


// ====== BUSCA DADOS LOTE ==========================================================================================
$busca_dados_lote = mysqli_query ($conexao, "SELECT * FROM cadastro_lote WHERE cod_armazem='$cod_armazem_form' AND codigo_lote='$codigo_lote_w' AND filial='$filial'");
$linhas_dl = mysqli_num_rows ($busca_dados_lote);
$aux_dl = mysqli_fetch_row($busca_dados_lote);

$nome_lote = $aux_dl[2];
$cod_produto_lote = $aux_dl[5];

if ($linhas_dl == 0)
{$saldo_lote = 0;}
else
{$saldo_lote = $aux_dl[19];}

if ($cod_produto_lote == 2)
{$saldo_lote_sc = ($saldo_lote / 60);
$saldo_lote_sc_print = " (" . number_format($saldo_lote_sc,0,",",".") . " Sc) ";}
else
{$saldo_lote_sc_print = "";}

$saldo_lote_print = number_format($saldo_lote,0,",",".");
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

	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    <div style='color:#0000FF'><?php echo "$nome_lote"; ?></div>
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
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/controle_lote/relatorio_lote_periodo.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR_LOTE'>
    <input type='hidden' name='cod_armazem_form' value='<?php echo"$cod_armazem_form"; ?>'>
	<input type='hidden' name='id_w' value='<?php echo"$id_w"; ?>'>
	<input type='hidden' name='codigo_lote_w' value='<?php echo"$codigo_lote_w"; ?>'>


	<div style="height:36px; width:40px; border:0px solid #000; float:left"></div>

    <div class="pqa_rotulo" style="height:20px; width:75px; border:0px solid #000">Data inicial:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario" style="width:80px; text-align:center" value="<?php echo"$data_inicial_br"; ?>" />
	</div>

	<div class="pqa_rotulo" style="height:20px; width:85px; border:0px solid #000">Data final:</div>

	<div style="height:34px; width:90px; border:0px solid #999; float:left">
    <input class="pqa_input" type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" 
    id="calendario_2" style="width:80px; text-align:center" value="<?php echo"$data_final_br"; ?>" />
	</div>

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

	echo"
	<form action='$servidor/$diretorio_servidor/estoque/controle_lote/index_controle_lote.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR_LOTE'>
	<input type='hidden' name='cod_armazem_form' value='$cod_armazem_form'>
	<button type='submit' class='botao_1' style='margin-left:10px'>Voltar</button>
	</form>";
/*
	if ($linha_romaneio >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/estoque/controle_lote/index_controle_lote.php' target='_blank' method='post'>
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
        {echo"$linha_lote Movimenta&ccedil;&atilde;o";}
        else
        {echo"$linha_lote Movimenta&ccedil;&otilde;es";}
        ?>
        </div>
	</div>

	<div class="contador_text" style="width:400px; float:right; margin-right:25px; text-align:right">
        <div class="contador_interno">
        <?php
        if ($linha_lote >= 1)
        {echo"Saldo atual do Lote: <b>$saldo_lote_print Kg</b> $saldo_lote_sc_print";}
        else
        {}
        ?>
        </div>
	</div>
    
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<?php include ('include_totalizador_lote.php'); ?>
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
<td width='80px' height='20px' align='center' bgcolor='#006699'>Data</td>
<td width='100px' align='center' bgcolor='#006699'>Movimenta&ccedil;&atilde;o</td>
<td width='80px' align='center' bgcolor='#006699'>N&ordm; Romaneio</td>
<td width='300px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='130px' align='center' bgcolor='#006699'>Tipo</td>
<td width='100px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='100px' align='center' bgcolor='#006699'>Peso por Bag</td>
<td width='130px' align='center' bgcolor='#006699'>Peso Total</td>
<td width='54px' align='center' bgcolor='#006699'>Excluir</td>
</tr>
</table>";}

echo "<table class='tabela_geral' style='font-size:11px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_lote ; $x++)
{
$aux_lote = mysqli_fetch_row($busca_lote);

// ====== DADOS DO LOTE ============================================================================
$id_w = $aux_lote[0];
$numero_movimentacao_w = $aux_lote[1];
$tipo_movimentacao_w = $aux_lote[2];
$data_w = $aux_lote[3];
$data_print_w = date('d/m/Y', strtotime($aux_lote[3]));
$cod_lote_w = $aux_lote[4];
$nome_lote_w = $aux_lote[5];
$peso_total_w = $aux_lote[6];
$peso_total_print_w = number_format($aux_lote[6],0,",",".") . " Kg";
$quant_bag_w = $aux_lote[16];
$peso_bag_w = $aux_lote[33];
$un_medida_w = $aux_lote[7];
$numero_romaneio_w = $aux_lote[8];
$cod_produto_w = $aux_lote[9];
$cod_tipo_producao_w = $aux_lote[11];
$cod_fornecedor_w = $aux_lote[13];
$cod_armazem_w = $aux_lote[15];
$codigo_sacaria_w = $aux_lote[17];
$umidade_w = $aux_lote[18];
$densidade_w = $aux_lote[19];
$impureza_w = $aux_lote[20];
$broca_w = $aux_lote[21];
$filial_w = $aux_lote[22];
$estado_registro_w = $aux_lote[32];

$usuario_cadastro_w = $aux_lote[23];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_lote[24]));
$hora_cadastro_w = $aux_lote[25];
$dados_cadastro_w = "Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}
// ======================================================================================================


// ====== BUSCA ARMAZEM ===================================================================================
$busca_armazem_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_armazem WHERE codigo_armazem='$cod_armazem_w'");
$aux_ba_2 = mysqli_fetch_row($busca_armazem_2);

$nome_armazem_print = $aux_ba_2[2];
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w'");
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
$saldo_armaz_sacaria = ($peso_w / $capacidade_sacaria);
$saldo_armaz_sc_print = number_format($saldo_armaz_sacaria,0,",",".");
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$cod_fornecedor_w'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
// ======================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ========================================================================
if ($permissao[93] == "S")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "ATIVO")
{echo "<tr class='tabela_1' title=' N&ordm; Movimenta&ccedil;&atilde;o: $numero_movimentacao_w &#13; Sacaria: $nome_sacaria_print ($capacidade_sacaria Kg) &#13; Densidade: $densidade_w &#13; Umidade: $umidade_w &#13; Impureza: $impureza_w &#13; Broca: $broca_w &#13; $dados_cadastro_w'>";}
else
{echo "<tr class='tabela_4' title=' N&ordm; Movimenta&ccedil;&atilde;o: $numero_movimentacao_w &#13; Sacaria: $nome_sacaria_print ($capacidade_sacaria Kg) &#13; Densidade: $densidade_w &#13; Umidade: $umidade_w &#13; Impureza: $impureza_w &#13; Broca: $broca_w &#13; $dados_cadastro_w'>";}

echo "
<td width='80px' align='center'>$data_print_w</td>
<td width='100px' align='center'><div style='height:14px; margin-left:0px; overflow:hidden'>$tipo_movimentacao_w</div></td>
<td width='80px' align='center'>$numero_romaneio_w</td>
<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$fornecedor_print</div></td>
<td width='130px' align='center'><div style='height:14px; margin-left:0px; overflow:hidden'>$tipo_producao_print</div></td>
<td width='100px' align='center'>$quant_bag_w</td>
<td width='100px' align='center'>$peso_bag_w Kg</td>
<td width='130px' align='center'>$peso_total_print_w</td>";

// ====== BOTAO EXCLUIR ===================================================================================================
	if ($permite_excluir == "SIM")
	{	
		echo "
		<td width='54px' align='center'>
		<form action='$servidor/$diretorio_servidor/estoque/controle_lote/excluir_registro.php' method='post'>
		<input type='hidden' name='pagina_mae' value='$pagina'>
		<input type='hidden' name='botao' value='DESEJA_EXCLUIR'>
		<input type='hidden' name='id_w' value='$id_w'>
		<input type='hidden' name='numero_movimentacao_w' value='$numero_movimentacao_w'>
		<input type='hidden' name='cod_armazem_form' value='$cod_armazem_form' />
		<input type='hidden' name='data_inicial' value='$data_inicial_br'>
		<input type='hidden' name='data_final' value='$data_final_br'>
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