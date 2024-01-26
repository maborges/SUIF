<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'saida_relatorio_fornecedor';
$titulo = 'Estoque - Relat&oacute;rio de Sa&iacute;das';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql
function ConverteData($data){
	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
// ================================================================================================================


// ====== CONVERTE VALOR ==========================================================================================
function ConverteValor($valor){
	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// ================================================================================================================


// ====== RECEBE POST ==============================================================================================
$data_hoje = date('Y-m-d', time());
$data_hoje_aux = date('d/m/Y', time());
$mes_atras = date ('Y-m-d', strtotime('-30 days'));
$filial = $filial_usuario;
$botao = $_POST["botao"];
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];

if ($botao == "BUSCAR")
{
	$data_inicial_aux = $_POST["data_inicial"];
	$data_inicial = ConverteData($_POST["data_inicial"]);
	$data_final_aux = $_POST["data_final"];
	$data_final = ConverteData($_POST["data_final"]);
	$forma_pesagem = "GERAL";
	$status_romaneio = "GERAL";
//	$forma_pesagem = $_POST["forma_pesagem"];
//	$status_romaneio = $_POST["status_romaneio"];
}
else
{
	$data_inicial_aux = $data_hoje_aux;
	$data_inicial = $data_hoje;
	$data_final_aux = $data_hoje_aux;
	$data_final = $data_hoje;
	$forma_pesagem = "GERAL";
	$status_romaneio = "GERAL";
}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

if ($fornecedor == "")
{$fornecedor_print = "(Necess&aacute;rio selecionar um fornecedor)";}
elseif ($linhas_fornecedor == 0)
{$fornecedor_print = "(Fornecedor n&atilde;o encontrado)";}
else
{$fornecedor_print = $aux_forn[1];}

$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA ROMANEIO ==========================================================================================
if ($cod_produto == "TODOS" or $linhas_bp == 0)
{
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' 
AND data>='$data_inicial' AND data<='$data_final' AND fornecedor='$fornecedor' AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='SAIDA' AND data>='$data_inicial' AND data<='$data_final' AND fornecedor='$fornecedor' AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
}

else
{
$busca_romaneio = mysqli_query ($conexao, "SELECT * FROM estoque WHERE estado_registro!='EXCLUIDO' AND movimentacao='SAIDA' 
AND data>='$data_inicial' AND data<='$data_final' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND filial='$filial' ORDER BY codigo");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
$soma_romaneio = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
AND movimentacao='SAIDA' AND data>='$data_inicial' AND data<='$data_final' AND fornecedor='$fornecedor' AND cod_produto='$cod_produto' AND filial='$filial'"));
$soma_romaneio_print = number_format($soma_romaneio[0],2,",",".");
}
// ================================================================================================================


if ($cod_produto == "TODOS" or $linhas_bp == 0)
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}

else
{
// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================
}


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
<?php include ('../../includes/sub_menu_estoque_saida.php'); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<!-- INÍCIO CENTRO GERAL -->
<div id="centro_geral_relatorio" style="width:1280px; height:auto; margin:auto; background-color:#FFF; border-radius:20px; border:1px solid #999">
<div style="width:1080px; height:15px; border:0px solid #000; margin:auto"></div>


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; border:0px solid #000; margin:auto">
	<div id="titulo_form_1" style="width:460px; height:30px; float:left; border:0px solid #000; margin-left:70px">
    Estoque - Relat&oacute;rio de Sa&iacute;das
    </div>

	<div style="width:460px; height:30px; float:right; border:0px solid #000; text-align:right; font-size:12px; color:#003466; margin-right:70px">
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; border:0px solid #000; margin:auto">
	<div id="titulo_form_2" style="width:900px; height:30px; float:left; border:0px solid #000; margin-left:70px; font-size:14px">
	<?php 
		if ($fornecedor == "" or $linhas_fornecedor == 0)
		{echo"<div id='centro' style='height:28px; width:120px; border:0px solid #000; color:#F00; font-size:14px; float:left'>
		<a href='$servidor/$diretorio_servidor/estoque/saida/saida_relatorio_fornecedor_seleciona.php'>
		<img src='$servidor/$diretorio_servidor/imagens/botoes/voltar_3.png' alt='Voltar' border='0'></a>
		</div>
		<div id='centro' style='height:28px; width:750px; border:0px solid #000; color:#F00; font-size:14px; float:left; margin-top:2px'>
		<i>$fornecedor_print</i></div>";}
		else
		{echo"<div id='centro' style='height:28px; width:750px; border:0px solid #000; color:#003466; font-size:14px; float:left'><i>Fornecedor: <b> $fornecedor_print</b></i></div>";}
	?>
    </div>
</div>

<div style="width:1080px; height:10px; border:0px solid #000; margin:auto"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div id="centro" style="height:36px; width:1250px; border:0px solid #000; margin:auto; background-color:#708090">
 
    <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/saida_relatorio_fornecedor.php" method="post" />
    <input type='hidden' name='botao' value='BUSCAR' />
    <input type='hidden' name='fornecedor' value='<?php echo"$fornecedor"; ?>' />
    <input type='hidden' name='cod_produto' value='<?php echo"$cod_produto"; ?>' />

	<div id="centro" style="height:36px; width:10px; border:0px solid #000; float:left"></div>

	<div id="centro" style="height:20px; width:145px; border:0px solid #999; float:left; margin-top:11px"></div>

    <div id="centro" style="height:20px; width:75px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:left; margin-top:11px">
        <i>Data inicial:&#160;</i>
    </div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
		<input type="text" name="data_inicial" maxlength="10" onkeypress="mascara(this,data)" 
        id="calendario" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_inicial_aux"; ?>" />
	</div>

    <div id="centro" style="height:20px; width:85px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:right; margin-top:11px">
		<i>Data final:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:90px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
		<input type="text" name="data_final" maxlength="10" onkeypress="mascara(this,data)" 
        id="calendario_2" style="height:16px; width:80px; color:#0000FF; font-size:11px" value="<?php echo"$data_final_aux"; ?>" />
	</div>


    <div id="centro" style="height:20px; width:80px; border:0px solid #999; color:#FFF; font-size:11px; float:left; text-align:right; margin-top:11px">
		<i>Produto:&#160;</i>
	</div>

	<div id="centro" style="height:20px; width:140px; border:0px solid #999; float:left; text-align:left; margin-top:7px">
        <select name="cod_produto" onkeydown="if (getKey(event) == 13) return false;" style="height:21px; width:130px; color:#0000FF; font-size:11px" />
        <option value="TODOS">(TODOS)</option>
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



    <div id="centro" style="height:22px; width:46px; border:0px solid #999; color:#FFF; font-size:11px; float:left; margin-left:20px; margin-top:8px">
		<input type="image" src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/icones/icone_visualizar.png" border="0" style="float:left" />
		</form>
	</div>
	
</div>

<div id="centro" style="height:20px; width:1250px; border:0px solid #000; margin:auto"></div>




<div id="centro" style="height:30px; width:1200px; border:0px solid #000; margin:auto">
	<div id="centro" style="width:390px; float:left; height:25px; margin-left:10px; border:0px solid #999">
	<?php 
	if ($linha_romaneio >= 1)
	{echo"
	<!--
	<form action='$servidor/$diretorio_servidor/compras/produtos/relatorio_impressao.php' target='_blank' method='post'>
	<input type='hidden' name='pagina_mae' value='index_contas_pagar'>
	<input type='hidden' name='data_inicial' value='$data_inicial'>
	<input type='hidden' name='data_final' value='$data_final'>
	<input type='hidden' name='botao_1' value='$botao_1'>
	<input type='hidden' name='botao_2' value='$botao_2'>	
	<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_imprimir_1.png' border='0' /></form>
	-->";}
	else
	{echo"";}
	?>
	</div>
	
	<div id="centro" style="width:390px; float:left; height:25px; border:0px solid #999; font-size:11px; color:#666; text-align:center">
    <?php 
	if ($linha_romaneio == 1)
	{echo"<i><b>$linha_romaneio</b> Romaneio</i>";}
	elseif ($linha_romaneio == 0)
	{echo"";}
	else
	{echo"<i><b>$linha_romaneio</b> Romaneios</i>";}
	?>
	</div>

	<div id="centro" style="width:390px; float:right; height:25px; border:0px solid #999; font-size:11px; color:#003466; text-align:right">
    <?php
	if ($linha_romaneio >= 1)
	{echo"TOTAL DE SA&Iacute;DA: <b>$soma_romaneio_print Kg</b>";}
	else
	{ }
	?>
	</div>
</div>




<!-- ====================================================================================== -->
<?php
for ($sc=1 ; $sc<=$linhas_bp_geral ; $sc++)
{
$aux_bp_geral = mysqli_fetch_row($busca_produto_geral);

	$soma_quant_produto = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM estoque WHERE estado_registro!='EXCLUIDO' 
	AND movimentacao='SAIDA' AND data>='$data_inicial' AND data<='$data_final' AND cod_produto='$aux_bp_geral[0]' 
	AND fornecedor='$fornecedor' AND filial='$filial'"));
	$soma_quant_print = number_format($soma_quant_produto[0],2,",",".");


	if ($soma_quant_produto[0] == 0)
	{echo "";}
	else
	{echo "
	<div id='centro' style='height:30px; width:290px; border:0px solid #999; float:left; margin-left:25px'>
		<div id='centro' style='height:20px; width:280px; margin-top:6px; border:1px solid #999; border-radius:7px; background-color:#EEE; float:left'>
			<div id='centro' style='height:15px; width:130px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:right; font-size:11px; color:#009900'>
			<b>$aux_bp_geral[22]</b>	
			</div>
			<div id='centro' style='height:15px; width:130px; margin-left:5px; margin-top:3px; border:0px solid #999; float:left; text-align:center; font-size:10px; color:#666'>
			$soma_quant_print Kg
			</div>
		</div>
	</div>
	";}


}
?>

<div id="centro" style="height:42px; width:1250px; border:0px solid #000; margin:auto"></div>


<!-- ====================================================================================== -->
<?php include ('include_relatorio_estoque_saida.php'); ?>
<!-- ====================================================================================== -->




<!-- ====================================================================================== -->
<div id="centro" style="height:30px; width:1080px; border:0px solid #000; margin:auto"></div>




<!-- ====== RODAPÉ =============================================================================================== -->
<!-- FIM CENTRO GERAL (depois do menu geral) -->
</div>


<!-- ====== RODAPÉ =============================================================================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>