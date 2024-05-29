<?php
	include ('../../includes/config.php');
	include ('../../includes/conecta_bd.php');
	include ('../../includes/valida_cookies.php');
	$pagina = 'futuro_excluir';
	$titulo = 'Excluir Contrato Futuro';
	$menu = 'contratos';
	$modulo = 'compras';
	
	include ('../../includes/head.php'); 
?>

<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>

<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>

<!-- =============================================   I N Í C I O   =============================================== -->
<body onload="javascript:foco('ok');">

<!-- =============================================    T O P O    ================================================= -->
<div id="topo_geral">
<?php  include ('../../includes/topo.php'); ?>
</div>




<!-- =============================================    M E N U    ================================================= -->
<div id="menu_geral">
<?php include ('../../includes/menu_compras.php'); ?>

<?php include ('../../includes/sub_menu_compras_contratos.php'); ?>
</div> <!-- FIM menu_geral -->




<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:460px; width:1080px; border:0px solid #000; margin:auto">

<?php

	$filial = $filial_usuario;
	$codigo_contrato = $_POST["codigo_contrato"];
	$codigo_contrato_aux = $_POST["codigo_contrato_aux"];

	$pagina_mae = $_POST["pagina_mae"];
	$pagina_filha = $_POST["pagina_filha"];
	$botao = $_POST["botao"];
	$data_inicial = $_POST["data_inicial"];
	$data_final = $_POST["data_final"];
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
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND codigo='$codigo_contrato' ORDER BY codigo");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);

$num_contrato_print = $aux_contrato[17];
$fornecedor = $aux_contrato[1];
$cod_produto = $aux_contrato[31];
$data_contrato = date('d/m/Y', strtotime($aux_contrato[2]));
$data_contrato_aux = date('Y-m-d', strtotime($aux_contrato[2]));
$quantidade = $aux_contrato[4];
$quantidade_adquirida = $aux_contrato[5];
$unidade_print = $aux_contrato[6];
$descricao = $aux_contrato[7];
$vencimento = date('d/m/Y', strtotime($aux_contrato[8]));	
$codigo_fiador_1 = $aux_contrato[9];
$codigo_fiador_2 = $aux_contrato[10];
$codigo_fiador_3 = $aux_contrato[30];
$observacao = $aux_contrato[11];
$situacao_contrato = $aux_contrato[15];
$quant_aux = number_format($quantidade,0,"","");
$quantidade_quilo = $aux_contrato[13];
$quant_quilo_aux = number_format($quantidade_quilo,0,"","");
$quantidade_a_entregar = $aux_contrato[16];
$preco_produto = $aux_contrato[27];
$multa = $aux_contrato[28];
$multa_print = number_format($multa,0,",",".");
$valor_total = $quantidade_a_entregar * $preco_produto;
$preco_produto_print = number_format($preco_produto,2,",",".");
$valor_total_print = number_format($valor_total,2,",",".");
$usuario_cadastro = $aux_contrato[18];
$data_cadastro = date('d/m/Y', strtotime($aux_contrato[20]));

$meses = array ("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$data_aux = explode("-", $data_contrato_aux);
$dia = $data_aux[2];
$mes = $data_aux[1];
$ano = $data_aux[0];
// =======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$quant_kg_saca = $aux_bp[27];
// ======================================================================================================


// ====== BUSCA POR FORNECEDOR ==========================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];

if ($aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}

if ($aux_pessoa[8] == "")
{$endereco_fornecedor = "";}
else
{$endereco_fornecedor = $aux_pessoa[8];}

if ($aux_pessoa[25] == "")
{$num_res_fornecedor = "";}
else
{$num_res_fornecedor = ", " . $aux_pessoa[25];}

if ($aux_pessoa[9] == "")
{$bairro_fornecedor = "";}
else
{$bairro_fornecedor = ", " . $aux_pessoa[9];}

if ($aux_pessoa[11] == "")
{$cep_fornecedor = "";}
else
{$cep_fornecedor = ", CEP: " . $aux_pessoa[11];}

$endereco_completo = $endereco_fornecedor . $num_res_fornecedor . $bairro_fornecedor . $cep_fornecedor;
// ======================================================================================================


// ====== BUSCA POR AVALISTAS ==========================================================================
// ====== FIADOR 1 ======
$busca_fiador_1 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$codigo_fiador_1' AND estado_registro!='EXCLUIDO'");
$aux_fiador_1 = mysqli_fetch_row($busca_fiador_1);
$linha_fiador_1 = mysqli_num_rows ($busca_fiador_1);

$fiador_1_print = $aux_fiador_1[1];
if ($aux_fiador_1[2] == "pf")
{$cpf_cnpj_fiador_1 = $aux_fiador_1[3];}
else
{$cpf_cnpj_fiador_1 = $aux_fiador_1[4];}

// ====== FIADOR 2 ======
$busca_fiador_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$codigo_fiador_2' AND estado_registro!='EXCLUIDO'");
$aux_fiador_2 = mysqli_fetch_row($busca_fiador_2);
$linha_fiador_2 = mysqli_num_rows ($busca_fiador_2);

$fiador_2_print = $aux_fiador_2[1];
if ($aux_fiador_2[2] == "pf")
{$cpf_cnpj_fiador_2 = $aux_fiador_2[3];}
else
{$cpf_cnpj_fiador_2 = $aux_fiador_2[4];}

// ====== FIADOR 3 ======
$busca_fiador_3 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$codigo_fiador_3' AND estado_registro!='EXCLUIDO'");
$aux_fiador_3 = mysqli_fetch_row($busca_fiador_3);
$linha_fiador_3 = mysqli_num_rows ($busca_fiador_3);

$fiador_3_print = $aux_fiador_3[1];
if ($aux_fiador_3[2] == "pf")
{$cpf_cnpj_fiador_3 = $aux_fiador_3[3];}
else
{$cpf_cnpj_fiador_3 = $aux_fiador_3[4];}
// ===========================================================================================================


// ===========================================================================================================
		echo "<div id='centro' style='float:left; height:5px; width:1050px; border:0px solid #000'></div>
		<div id='centro' style='float:left; height:90px; width:1045px; text-align:center; border:0px solid #000'>
		<img src='$servidor/$diretorio_servidor/imagens/icones/erro.png' border='0' /></div>
		<div id='centro' style='float:left; height:25px; width:1045px; color:#F00; text-align:center; border:0px solid #000; font-size:12px'>
		Deseja realmente excluir este contrato?</div>
		<div id='centro' style='float:left; height:200px; width:1045px; color:#00F; text-align:center; border:0px solid #000'>
			<div style='float:left; width:200px; height:180px; color:#00F; text-align:left; border:0px solid #000; font-size:12px'></div>
	
			<!-- =========  MOVIMENTACAO ============================================================================= -->
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			Tipo de contrato:</div>
			<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:681px; height:20px; border:1px solid #999; float:left; color:#009900; text-align:left; font-size:14px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'><b>Contrato Futuro</b></div></div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:750px; height:8px; border:0px solid #000; float:left; color:#00F; text-align:left; font-size:12px'></div>

			
			<!-- =========  NUMERO DA COMPRA E FORNECEDOR ============================================================================= -->
			<div style='width:150px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:0px'>
			N&uacute;mero do contrato:</div>
			<div style='width:504px; height:15px; border:1px solid #FFF; float:left; color:#666; text-align:left; font-size:10px; margin-left:25px'>
			Fornecedor:</div>
			<div style='width:120px; height:15px; border:1px solid #FFF; float:left; margin-left:25px'></div>

			<div style='width:150px; height:20px; border:1px solid #999; float:left; color:#00F; text-align:left; font-size:12px; 
			border-radius:3px; background-color:#EEE; margin-left:0px'><div style='margin-left:10px; margin-top:2px'>$num_contrato_print</div></div>
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
			<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/futuro_excluir_enviar.php' method='post'>
			<input type='text' name='motivo_exclusao' id='ok' maxlength='150' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; font-size:12px; width:680px' />
			</div>
			<div style='width:120px; height:20px; border:1px solid #FFF; float:left; margin-left:25px'></div>

		</div>
		
		<div id='centro' style='float:left; height:70px; width:168px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<input type='hidden' name='codigo_contrato' value='$codigo_contrato'>
		<input type='hidden' name='codigo_contrato_aux' value='$codigo_contrato_aux'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/botao_excluir_2.jpg' border='0' />
		</form>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_futuro/$pagina_mae.php' method='post'>
		<input type='hidden' name='codigo_contrato' value='$codigo_contrato'>
		<input type='hidden' name='codigo_contrato_aux' value='$codigo_contrato_aux'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial' value='$data_inicial'>
		<input type='hidden' name='data_final' value='$data_final'>
		<input type='hidden' name='cod_produto' value='$cod_produto'>
		<input type='hidden' name='monstra_situacao' value='$monstra_situacao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='fornecedor' value='$fornecedor'>
		<input type='hidden' name='fornecedor_print' value='$fornecedor_print'>
		<input type='image' src='$servidor/$diretorio_servidor/imagens/botoes/voltar_2.jpg' border='0' /></form>
		</div>

		<div id='centro' style='float:left; height:70px; width:185px; color:#00F; text-align:center; border:0px solid #000'>
		</div>";



?>




</div>
</div><!-- FIM DIV CENTRO GERAL -->




<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>