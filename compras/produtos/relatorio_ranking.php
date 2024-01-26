<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "relatorio_ranking";
$titulo = "Ranking de Compras";
$modulo = "compras";
$menu = "relatorios";


// LIMPA TABELA RANKING ======================================
$limpa = mysqli_query ($conexao, "TRUNCATE TABLE ranking_compras");


// ====== CONVERTE DATA ================================================================================	
// Função para converter a data de formato nacional para formato americano. Usado para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ======================================================================================================


// ====== CONVERTE VALOR =================================================================================	
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =======================================================================================================


// ====== DADOS PARA BUSCA =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);
$cod_produto = $_POST["cod_produto"];
$ordem = $_POST["ordem"];
$cidade_busca = $_POST["cidade_busca"];
$filial = $filial_usuario;

/*
$data_hoje = date('Y-m-d', time());
$filial = $filial_usuario;
$data_inicial_aux = $_POST["data_inicial"];
$data_inicial = ConverteData($_POST["data_inicial"]);
$data_final_aux = $_POST["data_final"];
$data_final = ConverteData($_POST["data_final"]);
$cod_produto = $_POST["cod_produto"];
$ordem = $_POST["ordem"];
$botao = $_POST["botao"];
*/


if (empty($data_inicial_br) or empty($data_final_br))
	{$data_inicial_br = $data_hoje_br;
	$data_inicial_busca = $data_hoje;
	$data_final_br = $data_hoje_br;
	$data_final_busca = $data_hoje;}
else
	{$data_inicial_br = $_POST["data_inicial_busca"];
	$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
	$data_final_br = $_POST["data_final_busca"];
	$data_final_busca = ConverteData($_POST["data_final_busca"]);}

$mysql_filtro_data = "data_compra BETWEEN '$data_inicial_busca' AND '$data_final_busca'";

if ($cidade_busca == "" or $cidade_busca == "TODAS")
	{$mysql_filtro_cidade = "cidade IS NOT NULL";
	$cidade_busca = "TODAS";}
else
	{$mysql_filtro_cidade = "cidade='$cidade_busca'";
	$cidade_busca = $_POST["cidade_busca"];}


// =======================================================================================================

$quant_dias = (strtotime($data_final) - strtotime($data_inicial))/86400;;
if ($quant_dias > 370)
{

}

else
{

// ====== BUSCA E SOMA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT DISTINCT fornecedor FROM compras WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

/*
$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial'"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND movimentacao='COMPRA' AND cod_produto='$cod_produto' AND filial='$filial'"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compras[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}
*/
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
$aux_bp_geral = mysqli_fetch_row ($busca_produto_geral);	

$produto_print = $aux_bp_geral[22];
$unidade_print = $aux_bp_geral[26];
// =======================================================================================================



// =======================================================================================================
for ($y=1 ; $y<=$linha_compra ; $y++)
{
$aux = mysqli_fetch_row($busca_compra);

$cod_produto_aux = $_POST["cod_produto"];
$fornecedor_aux = $aux[0];

// FAZ A SOMA POR PRODUTOR ======================================
$soma_vp = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM compras WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND movimentacao='COMPRA' AND cod_produto='$cod_produto_aux' AND filial='$filial' AND fornecedor='$fornecedor_aux'"));
$soma_vp_print = number_format($soma_vp[0],2,",",".");

$soma_qp = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM compras WHERE estado_registro!='EXCLUIDO' AND $mysql_filtro_data AND movimentacao='COMPRA' AND cod_produto='$cod_produto_aux' AND filial='$filial' AND fornecedor='$fornecedor_aux'"));
$soma_qp_print = number_format($soma_qp[0],2,",",".");
// ==============================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor_print = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_aux' AND estado_registro!='EXCLUIDO'");
$aux_fp = mysqli_fetch_row($busca_fornecedor_print);

$fornecedor_print_aux = $aux_fp[1];
$fornecedor_cidade = $aux_fp[10];
if ($aux_fp[2] == "pf" or $aux_fp[2] == "PF")
{$cpf_cnpj_aux = $aux_fp[3];}
else
{$cpf_cnpj_aux = $aux_fp[4];}
// ======================================================================================================


// PREENCHE TABELA RANKING ======================================
$inserir = mysqli_query ($conexao, "INSERT INTO ranking_compras (codigo, fornecedor, fornecedor_print, produto, produto_print, quantidade, unidade, valor_total, cidade, cpf_cnpj) VALUES (NULL, '$fornecedor_aux', '$fornecedor_print_aux', '$cod_produto_aux', '$produto_print', '$soma_qp[0]', '$unidade_print', '$soma_vp[0]', '$fornecedor_cidade', '$cpf_cnpj_aux')");

}
// =================================================================================================================



// ====== BUSCA TABELA RANKING  =======================================================================
if ($ordem == "QUANT")
{
$busca_ranking = mysqli_query ($conexao, "SELECT * FROM ranking_compras WHERE $mysql_filtro_cidade ORDER BY quantidade DESC");
$linhas_ranking = mysqli_num_rows ($busca_ranking);
}
else
{
$busca_ranking = mysqli_query ($conexao, "SELECT * FROM ranking_compras WHERE $mysql_filtro_cidade ORDER BY fornecedor_print");
$linhas_ranking = mysqli_num_rows ($busca_ranking);
}

$soma_compras = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(valor_total) FROM ranking_compras WHERE $mysql_filtro_cidade"));
$soma_compras_print = number_format($soma_compras[0],2,",",".");

$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM ranking_compras WHERE $mysql_filtro_cidade"));
$quant_produto_print = number_format($soma_quant_produto[0],2,",",".");
if ($soma_quant_produto[0] <= 0)
{$media_produto_print = "0,00";}
else
{$media_produto = ($soma_compras[0] / $soma_quant_produto[0]);
$media_produto_print = number_format($media_produto,2,",",".");}

// =================================================================================================================

}// Fim da condição IF (quantidade de dias)


include ("../../includes/head.php");
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
}, 10000); // 10 Segundos

</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onload="javascript:foco('ok');">


<!-- ====== TOPO ================================================================================================== -->
<div class="topo">
<?php include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_relatorios.php"); ?>
</div>



<!-- =============================================   C E N T R O   =============================================== -->


<!-- ======================================================================================================= -->
<div id="centro_geral"><!-- INÍCIO CENTRO GERAL -->
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Ranking de Compras
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:70px; font-size:11px">
	    <div id="oculta">
		<?php
        if ($quant_dias > 370)
        {echo "Intervalo entre datas n&atilde;o pode ser maior que um ano.";}
        else
        {echo "";}
        ?>
        </div>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:35px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="height:25px; width:10px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:30px; width:1060px; border:1px solid #999; color:#666; font-size:11px; border-radius:5px; float:left">
		<div id="centro" style="width:90px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:85px; height:8px; float:left; border:0px solid #999"></div>
		<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/produtos/relatorio_ranking.php" method="post" />
		<input type='hidden' name='botao' value='1' />
		<i>Data inicial:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_inicial_busca" maxlength="10" onkeypress="mascara(this,data)" id="calendario" style="color:#0000FF; width:90px" value="<?php echo"$data_inicial_br"; ?>" />
		</div>

		<div id="centro" style="width:75px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:70px; height:8px; float:left; border:0px solid #999"></div>
		<i>Data final:&#160;</i></div>

		<div id="centro" style="width:100px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:95px; height:3px; float:left; border:0px solid #999"></div>
		<input type="text" name="data_final_busca" maxlength="10" onkeypress="mascara(this,data)" id="calendario_2" style="color:#0000FF; width:90px" value="<?php echo"$data_final_br"; ?>" />
		</div>

		<div id="centro" style="width:65px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:60px; height:8px; float:left; border:0px solid #999"></div>
		<i>Produto:&#160;</i></div>

		<div id="centro" style="width:150px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:145px; height:3px; float:left; border:0px solid #999"></div>
   		<select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:140px; height:21px; font-size:11px; text-align:left" />
		<option></option>
		<?php
			$busca_produto_list = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
			$linhas_produto_list = mysqli_num_rows ($busca_produto_list);
		
			for ($j=1 ; $j<=$linhas_produto_list ; $j++)
			{
				$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
				if ($aux_produto_list[0] == $cod_produto)
				{
				echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
				}
				else
				{
				echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
				}
			}
		?>
		</select>
		</div>


		<div id="centro" style="width:80px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:75px; height:8px; float:left; border:0px solid #999"></div>
		<i>Ordenar por:&#160;</i></div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
   		<select name="ordem" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:110px; height:21px; font-size:11px; text-align:left" />
			<?php
            if ($ordem == "QUANT" or $ordem == "")
            {
            echo "<option selected='selected' value='QUANT'>Quantidade</option>";
            echo "<option value='NOME'>Fornecedor</option>";
            }
            else
            {
            echo "<option value='QUANT'>Quantidade</option>";
            echo "<option selected='selected' value='NOME'>Fornecedor</option>";
            }
            ?>
		</select>
		</div>



		<div id="centro" style="width:65px; float:left; height:20px; color:#666; border:0px solid #999; text-align:right">
		<div id="geral" style="width:60px; height:8px; float:left; border:0px solid #999"></div>
		<i>Cidade:&#160;</i></div>

		<div id="centro" style="width:120px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:115px; height:3px; float:left; border:0px solid #999"></div>
   		<select name="cidade_busca" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:110px; height:21px; font-size:11px; text-align:left" />
        <option value='TODAS'>(TODAS)</option>
			<?php
			$busca_cidade = mysqli_query ($conexao, "SELECT DISTINCT cidade FROM ranking_compras ORDER BY cidade");
			$linhas_cidade = mysqli_num_rows ($busca_cidade);
		
			for ($i=1 ; $i<=$linhas_cidade ; $i++)
			{
				$aux_cidade = mysqli_fetch_row($busca_cidade);
				if ($aux_cidade[1] == $cidade_busca)
				{echo "<option selected='selected' value='$aux_cidade[0]'>$aux_cidade[0]</option>";}
				else
				{echo "<option value='$aux_cidade[0]'>$aux_cidade[0]</option>";}
			}
            ?>
		</select>
		</div>

		

		<div id="centro" style="width:60px; float:left; height:22px; border:0px solid #999; text-align:left">
		<div id="geral" style="width:55px; height:3px; float:left; border:0px solid #999"></div>
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" height="20px" style="float:left" />
		</form>
		</div>
		



		
	</div>
	
</div>

<div id="centro" style="height:10px; width:1080px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:15px; width:1080px; border:0px solid #000; margin:auto"></div>

<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:350px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linhas_ranking >= 1)
	{echo"
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_ranking_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='ordem' value='$ordem'>
	<input type='hidden' name='produto_print' value='$produto_print'>
	<input type='hidden' name='unidade_print' value='$unidade_print'>
	<input type='hidden' name='data_inicial' value='$data_inicial_br'>
	<input type='hidden' name='data_final' value='$data_final_br'>
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' />
	</form>";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:350px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 

	if ($linhas_ranking == 1)
	{echo"<i><b>$linhas_ranking</b> Fornecedor</i>";}
	elseif ($linhas_ranking == 0)
	{echo"";}
	else
	{echo"<i><b>$linhas_ranking</b> Fornecedores</i>";}

	?>
	</div>

	<div id="centro" style="width:350px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linhas_ranking >= 1)
	{echo"TOTAL DE COMPRAS: <b>R$ $soma_compras_print</b>";}
	else
	{ }
	?>
	</div>
</div>
<!-- ====================================================================================== -->
<?php
	if ($soma_compras[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:22px; width:1080px; margin:auto; border:0px solid #999'>
		<div id='centro' style='height:20px; width:1075px; margin:auto; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
			<div id='centro' style='height:15px; width:20px; margin-left:5px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#009900'></div>
			<div id='centro' style='height:15px; width:120px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:left; font-size:11px; color:#009900'>
			<b>$produto_print</b>
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Quant. comprada: $quant_produto_print $unidade_print
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Valor total: R$ $soma_compras_print
			</div>
			<div id='centro' style='height:15px; width:270px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			Pre&ccedil;o m&eacute;dio: R$ $media_produto_print / $unidade_print
			</div>
		</div>
	</div>
	<div id='centro' style='height:6px; width:1080px; margin:auto; border:0px solid #999'></div>
	";}
?>







<div id='centro' style='height:10px; width:1075px; margin:auto; border:0px solid #999'></div>

<!-- ====================================================================================== -->

<?php
if ($linhas_ranking == 0)
{echo "<div id='centro_3'>
<div id='centro' style='height:210px'>";}
else
{echo "<div id='centro_3_relatorio' style='font-style:normal; height:auto; width:1080px; margin:auto; border:0px solid #F0F'>
<div id='centro_4_relatorio' style='font-style:normal; height:auto; width:1075px; margin:auto; border:1px solid #999; border-radius:10px'>";}
?>

<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>

<?php
if ($linhas_ranking == 0)
{echo "";}
else
{echo "
<table border='0' align='center' style='color:#FFF; font-size:9px'>
<tr>
<td width='70px' align='center' bgcolor='#006699'>Classifica&ccedil;&atilde;o</td>
<td width='400px' align='center' bgcolor='#006699'>Fornecedor</td>
<td width='140px' align='center' bgcolor='#006699'>CPF/CNPJ</td>
<td width='140px' align='center' bgcolor='#006699'>Produto</td>
<td width='100px' align='center' bgcolor='#006699'>Quantidade</td>
<td width='100px' align='center' bgcolor='#006699'>Valor Total</td>
</tr>
</table>";}
?>

<table border="0" id="tabela_4" align="center" style="color:#00F; font-size:9px">


<?php
for ($x=1 ; $x<=$linhas_ranking ; $x++)
{
$aux_ranking = mysqli_fetch_row($busca_ranking);

$forne_print = $aux_ranking[2];
$quant_print = number_format($aux_ranking[5],2,",",".");
$un_print = $aux_ranking[6];
$valor_t_print = number_format($aux_ranking[7],2,",",".");
$cpf_cnpj_print = $aux_ranking[9];

// RELATORIO =========================
	echo "
	<tr style='color:#000099'>
	<td width='70px' align='center'>$x &ordm;</td>
	<td width='400px' align='left'><div style='margin-left:5px'>$forne_print</div></td>
	<td width='140px' align='center'>$cpf_cnpj_print</td>
	<td width='140px' align='center'>$produto_print</td>
	<td width='100px' align='right'><div style='margin-right:5px'>$quant_print $un_print</div></td>
	<td width='100px' align='right'><div style='margin-right:5px'>R$ $valor_t_print</div></td>
	</tr>";
}
// =================================================================================================================

?>
</table>

<?php
if ($linhas_ranking == 0 and $botao == "1")
{echo "
<div id='centro' style='height:30px; width:700px; border:0px solid #000; color:#F00; font-size:12px; margin:auto; text-align:center'><i>Nenhuma compra encontrada.</i></div>";}
else
{}
?>



<div id="centro" style="height:20px; width:1075px; border:0px solid #000; margin:auto"></div>
</div><!-- FIM DIV centro_4 -->
<div id="centro" style="height:30px; width:1075px; border:0px solid #000; margin:auto"></div>
<!-- ======================================================================================================== -->
</div><!-- FIM DIV centro_3 -->




<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>

<!-- ====================================================================================== -->
</div><!-- =================== FIM CENTRO GERAL (depois do menu geral) ==================== -->
<!-- ====================================================================================== -->

<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>