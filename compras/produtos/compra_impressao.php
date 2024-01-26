<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'compra_impressao';
$titulo = 'Impress&atilde;o de Compra';
$modulo = 'compras';
$menu = 'produtos';


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
$numero_compra = $_POST["numero_compra"];
// =======================================================================================================


// ====== BUSCA COMPRAS =================================================================================
$busca_compra = mysqli_query ($conexao, "SELECT * FROM compras WHERE estado_registro!='EXCLUIDO' AND numero_compra='$numero_compra' ORDER BY codigo");
$linha_compra = mysqli_num_rows ($busca_compra);
// =======================================================================================================


// ====== BUSCA POR PRODUTOS GERAL  =======================================================================
$busca_produto_geral = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE estado_registro!='EXCLUIDO' AND codigo='$cod_produto'");
$linhas_bp_geral = mysqli_num_rows ($busca_produto_geral);
// =======================================================================================================


// ===========================================================================================================
include ('../../includes/head_impressao.php'); 
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
<body onLoad="imprimir()">

<?php
for ($x=1 ; $x<=$linha_compra ; $x++)
{
$aux_compra = mysqli_fetch_row($busca_compra);
}

$produto = $aux_compra[3];
$cod_produto = $aux_compra[39];
$data_compra = $aux_compra[4];
$data_compra_print = date('d/m/Y', strtotime($aux_compra[4]));
$unidade = $aux_compra[8];
$unidade_print = $aux_compra[8];
$fornecedor = $aux_compra[2];
$quantidade = $aux_compra[5];
$quantidade_print = number_format($aux_compra[5],2,",",".");
$preco_unitario = $aux_compra[6];
$preco_unitario_print = number_format($aux_compra[6],2,",",".");
$valor_total = $aux_compra[7];
$valor_total_print = number_format($aux_compra[7],2,",",".");
$safra = $aux_compra[9];
$tipo = $aux_compra[10];
$broca = $aux_compra[11];
$umidade = $aux_compra[12];
$situacao = $aux_compra[17];
$impureza = $aux_compra[43];
$observacao = $aux_compra[13];
$motivo_alteracao_quant = $aux_compra[35];
$quantidade_original = number_format($aux_compra[36],2,",",".");
$desconto_quantidade = number_format($aux_compra[29],2,",",".");
$desconto_quantidade_2 = $aux_compra[29];
$valor_total_original = number_format($aux_compra[37],2,",",".");
$desconto_em_valor = ($aux_compra[29] * $aux_compra[6]);
$desc_em_valor_print = number_format($desconto_em_valor,2,",",".");

$usuario_print = $aux_compra[18];
$usuario_cadastro = $aux_compra[18];
$data_cadastro = date('d/m/Y', strtotime($aux_compra[20]));
$hora_cadastro = $aux_compra[19];
$filial_print = $aux_compra[25];

$produtor_ficha = $aux_compra[2];
$produto_list = $aux_compra[3];
$filial = $aux_compra[25];

//$ano_atual_rodape = date(Y);

// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
// ======================================================================================================
	

// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linhas_pessoa = mysqli_num_rows ($busca_pessoa);

$fornecedor_print = $aux_pessoa[1];
$codigo_pessoa = $aux_pessoa[35];
$cidade_fornecedor = $aux_pessoa[10];
$estado_fornecedor = $aux_pessoa[12];
$telefone_fornecedor = $aux_pessoa[14];
if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}
// ======================================================================================================



// ====== INCLUDE DA VERDAO DE IMPRESSAO ================================================================
$versao_impressao_compra = $filial_config[8];


// ====== 1.0 (GRANCAFÉ) ======
if ($versao_impressao_compra == "1.0")
{include ('inc_compra_print_v_1.php');}

// ====== 1.1 (GRANCAFÉ) ======
elseif ($versao_impressao_compra == "1.1")
{include ('inc_compra_print_v_1_1.php');}

// ====== 2.0 (IRMAOS COSME) ======
elseif ($versao_impressao_compra == "2.0")
{include ('inc_compra_print_v_2.php');}

else
{echo "Defina a vers&atilde;o de impress&atilde;o de compra";}
// ======================================================================================================
?>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->