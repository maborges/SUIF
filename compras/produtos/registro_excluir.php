<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "registro_excluir";
$titulo = "Excluir";
$modulo = "compras";
$menu = "compras";


include ("../../includes/head.php"); 
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
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
<?php include ("../../includes/submenu_compras_compras.php"); ?>
</div>




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:460px; width:1080px; border:0px solid #000; margin:auto">

<?php
// ============================================== CONVERTE DATA ====================================================	
// Função para converter a data de formato nacional para formato americano. Muito útil para inserir data no mysql

function ConverteData($data){

	if (strstr($data, "/"))//verifica se tem a barra
	{
	$d = explode ("/", $data);//tira a barra
	$rstData = "$d[2]-$d[1]-$d[0]";//separa as datas $d[2] = ano $d[1] = mes etc...
	return $rstData;
	}
}
//echo ConverteData($data_emissao);
// =================================================================================================================


// ============================================== CONVERTE VALOR ====================================================	
function ConverteValor($valor){

	$valor_1 = str_replace(".", "", $valor);
	$valor_2 = str_replace(",", ".", $valor_1);
	return $valor_2;
}
// =================================================================================================================
	$filial = $filial_usuario;
	$numero_compra = $_POST["numero_compra"];
	$numero_compra_aux = $_POST["numero_compra_aux"];
	
	$pagina_mae = $_POST["pagina_mae"];
	$pagina_filha = $_POST["pagina_filha"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
	$produto_list = $_POST["produto_list"];
	$produtor_ficha = $_POST["produtor_ficha"];
	$cod_produto = $_POST["cod_produto"];
	$cod_tipo = $_POST["cod_tipo"];
	$fornecedor = $_POST["fornecedor"];
	$monstra_situacao = $_POST["monstra_situacao"];
	$movimentacao = $_POST["movimentacao"];
	
	$usuario_alteracao = $nome_usuario_print;
	$hora_alteracao = date('G:i:s', time());
	$data_alteracao = date('Y/m/d', time());

// =============================================================================================================
// =============================================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);

for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$cod_tipo = $aux_compra[41];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$tipo_movimentacao = $aux_compra[16];
$situacao = $aux_compra[17];
$observacao = $aux_compra[13];
$numero_romaneio = $aux_compra[28];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));



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

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================

		echo "<div id='centro' style='float:left; height:5px; width:1050px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:1045px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:25px; width:1045px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Deseja realmente excluir este registro?</div>
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
	
			<!-- =========  MOVIMENTACAO ============================================================================= -->
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Tipo de registro:</div>
			<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:681px; height:20px; border:1px solid #999; float:left; color:#009900; text-align:left; font-size:14px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'><b>$tipo_movimentacao</b></div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			
			<!-- =========  NUMERO DA COMPRA E FORNECEDOR ============================================================================= -->
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			N&uacute;mero do Registro:</div>
			<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Fornecedor:</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$numero_compra</div></div>
			<div style='width:504px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$fornecedor_print</div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			
			<!-- =========  PRODUTO, QUANTIDADE, USUARIO, DATA ============================================================================= -->
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Produto:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Quantidade:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Usu&aacute;rio Cadastro:</div>
			<div style='width:150px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Data Cadastro:</div>
			<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$produto_print</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$quantidade $unidade_print</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$usuario_cadastro</div></div>
			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:25px'><div style='margin-left:10px; margin-top:2px'>$data_cadastro</div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			<!-- =========  MOTIVO EXCLUSAO ============================================================================= -->
			<div style='width:680px; height:14px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Motivo da exclus&atilde;o:</div>
			<div style='width:120px; height:14px; border:1px solid #FFF; float:left; margin-left:25px'></div>


			<div style='width:680px; height:20px; border:1px solid #FFF; float:left; text-align:left; margin-left:0px'>
			<form action='$servidor/$diretorio_servidor/compras/produtos/registro_excluir_enviar.php' method='post'>
			<input type='text' name='motivo_exclusao' id='ok' maxlength='150' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:680px' />
			</div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

		</div>
		
		<div id='centro' style='float:left; height:70px; width:168px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='produto_list' value='$produto_list'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='produtor_ficha' value='$produtor_ficha'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='quantidade' value='$quantidade'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='produto_print' value='$produto_print'>
		<input type='hidden' name='unidade_print' value='$unidade_print'>
		<input type='hidden' name='tipo' value='$tipo'>
		<input type='hidden' name='cod_tipo' value='$cod_tipo'>
		<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Excluir</button>
		</form>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>";

		if ($pagina_mae == "movimentacao_produtor")
		{
		echo "
			<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/$pagina_mae.php' method='post'>
			<input type='hidden' name='numero_compra' value='$numero_compra'>
			<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
			<input type='hidden' name='botao' value='$botao'>
			<input type='hidden' name='data_inicial' value='$data_inicial'>
			<input type='hidden' name='data_final' value='$data_final'>
			<input type='hidden' name='cod_produto' value='$cod_produto'>
			<input type='hidden' name='cod_tipo' value='$cod_tipo'>
			<input type='hidden' name='fornecedor' value='$fornecedor'>
			<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='pagina_filha' value='$pagina_filha'>
			<input type='hidden' name='num_romaneio_manual' value='$num_romaneio_manual'>
			<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
			</form>";
		}
		else
		{
		echo "
			<form action='$servidor/$diretorio_servidor/compras/produtos/$pagina_mae.php' method='post'>
			<input type='hidden' name='numero_compra' value='$numero_compra'>
			<input type='hidden' name='numero_compra_aux' value='$numero_compra_aux'>
			<input type='hidden' name='botao' value='$botao'>
			<input type='hidden' name='data_inicial' value='$data_inicial'>
			<input type='hidden' name='data_final' value='$data_final'>
			<input type='hidden' name='cod_produto' value='$cod_produto'>
			<input type='hidden' name='produto_list' value='$produto_list'>
			<input type='hidden' name='cod_tipo' value='$cod_tipo'>
			<input type='hidden' name='fornecedor' value='$fornecedor'>
			<input type='hidden' name='representante' value='$produtor_ficha'>
			<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
			<input type='hidden' name='pagina_mae' value='$pagina_mae'>
			<input type='hidden' name='pagina_filha' value='$pagina_filha'>
			<button type='submit' class='botao_2' style='margin-left:20px; width:120px'>Voltar</button>
			</form>";
		}
		echo "
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";



?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>