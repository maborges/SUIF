<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "recibo_impressao_a5";
$titulo = "Recibo";
$modulo = "compras";
$menu = "contratos";
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
	$valor_1 = str_replace("R$ ", "", $valor_x); //tira o símbolo
	$valor_2 = str_replace(".", "", $valor_1); //tira o ponto
	$valor_3 = str_replace(",", ".", $valor_2); //troca vírgula por ponto
	return $valor_3;
}
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;

$numero_recibo = $_POST["numero_recibo"];

$valor_form = $_POST["valor_form"];
$valor_2 = ConverteValor($_POST["valor_form"]);
$data_recibo_form = $_POST["data_recibo_form"];
$data_recibo_aux = ConverteData($data_recibo_form);
$cod_produto_form = $_POST["cod_produto_form"];
$nome_emissor_form = $_POST["nome_emissor_form"];
$telefone_emissor_form = $_POST["telefone_emissor_form"];
$cpf_cnpj_emissor_form = $_POST["cpf_cnpj_emissor_form"];
$cidade_emissor_form = $_POST["cidade_emissor_form"];
$nome_pagador_form = $_POST["nome_pagador_form"];
$cpf_cnpj_pagador_form = $_POST["cpf_cnpj_pagador_form"];
$cidade_pagador_form = $_POST["cidade_pagador_form"];
$referente_form = $_POST["referente_form"];
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$nome_imagem_produto = $aux_bp[28];
$usuario_alteracao_prod = $aux_bp[16];
$data_alteracao_prod = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];
$plano_conta = $aux_bp[35];
if ($nome_imagem_produto == "")
{$link_imagem_produto = "";}
else
{$link_imagem_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>";}
// ======================================================================================================


// ================================================================================================================
$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$data_aux = explode("-", $data_recibo_aux);
$dia = $data_aux[2];
$mes = $data_aux[1];
$ano = $data_aux[0];
// ================================================================================================================


// ==================================================================================================================
function valorPorExtenso($valor=0) {
$singular = array("centavo", "real", "mil", "milh&atilde;o", "bilh&atilde;o", "trilh&atilde;o", "quatrilh&atilde;o");
$plural = array("centavos", "reais", "mil", "milh&otilde;es", "bilh&otilde;es", "trilh&otilde;es","quatrilh&otilde;es");
 
$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
$u = array("", "um", "dois", "tr&ecirc;s", "quatro", "cinco", "seis","sete", "oito", "nove");
 
$z=0;
 
$valor = number_format($valor, 2, ".", ".");
$inteiro = explode(".", $valor);
for($i=0;$i<count($inteiro);$i++)
for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
$inteiro[$i] = "0".$inteiro[$i];
 
// $fim identifica onde que deve se dar jun&ccedil;&atilde;o de centenas por "e" ou por "," ;)
$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
for ($i=0;$i<count($inteiro);$i++) {
$valor = $inteiro[$i];
$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
$t = count($inteiro)-1-$i;
$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
if ($valor == "000")$z++; elseif ($z > 0) $z--;
if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
}
 
return($rt ? $rt : "zero");
}
// ================================================================================================================


// ================================================================================================================
$valor_impresso = valorPorExtenso($valor_2);
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head_impressao.php");
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
<body onLoad="imprimir()">

<div style="width:750px; border:0px solid #000; float:left">

<div style="width:720px; height:15px; border:0px solid #000; margin:auto" align="center"></div>

<div style="width:720px; height:500px; border:1px solid #000; border-radius:0px; margin:auto" align="center">


<div style="width:234px; float:left; height:60px; border:0px solid #000; font-size:17px" align="center">
<img src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" width="170px" border="0" />
</div>

<div style="width:234px; float:left; height:60px; border:0px solid #000; font-size:24px" align="center"></div>
<div style="width:234px; float:left; height:60px; border:0px solid #000; font-size:14px" align="right">
<div style="margin-top:25px"><?php echo"N&ordm; $numero_recibo"; ?></div></div>


<div style="width:700px; height:20px; float:left; border:0px solid #000"></div>


<div style="width:718px; float:left; height:40px; border:0px solid #000; font-size:24px" align="center">
<b>Recibo de Pagamento</b>
</div>

<div style="width:234px; float:left; height:40px; border:0px solid #000; font-size:24px" align="center"></div>
<div style="width:234px; float:left; height:40px; border:0px solid #000; font-size:24px" align="center">
</div>
<div style="width:234px; float:left; height:40px; border:0px solid #000; font-size:18px" align="center">
<?php echo "<b>$valor_form</b>"; ?>
</div>



<div style="width:70px; float:left; height:315px; border:0px solid #000; font-size:24px" align="center"></div>

<div style="width:580px; float:left; height:80px; border:0px solid #000; font-size:14px" align="justify">
Recebi de <?php echo "<b>$nome_pagador_form</b> - CPF/CNPJ N&ordm; $cpf_cnpj_pagador_form"; ?>, a import&acirc;ncia de <?php echo"<b>$valor_impresso </b>"; ?>
<?php echo "$referente_form $produto_print_2"; ?>.
</div>

<div style="width:580px; float:left; height:50px; border:0px solid #000; font-size:14px" align="justify">
Para maior clareza firmo o presente recibo para que produza os seus efeitos, dando plena, rasa e irrevog&aacute;vel quita&ccedil;&atilde;o, pelo valor recebido.
</div>


<div style="width:580px; float:left; height:20px; border:0px solid #000; font-size:14px" align="center"></div>

<div style="width:580px; float:left; height:60px; border:0px solid #000; font-size:14px" align="center">
<?php echo "$cidade_pagador_form, " . $dia . " de " . $meses [$mes-1] . " de " . $ano; ?>
</div>


<div style="width:580px; float:left; height:10px; border:0px solid #000; font-size:14px" align="center"></div>


<div style="width:580px; float:left; height:20px; border:0px solid #000; font-size:14px" align="center">
_________________________________
</div>

<div style="width:580px; float:left; height:20px; border:0px solid #000; font-size:14px" align="center">
<?php echo "$nome_emissor_form"; ?>
</div>

<div style="width:580px; float:left; height:20px; border:0px solid #000; font-size:14px" align="center">
<?php echo "CPF/CNPJ: $cpf_cnpj_emissor_form"; ?>
</div>

<div style="width:580px; float:left; height:20px; border:0px solid #000; font-size:14px" align="center">
<?php echo "$telefone_emissor_form"; ?>
</div>

<div style="width:680px; margin-left:20px; float:left; height:16px; border:1px solid #000; font-size:9px" align="center">
<?php echo "$nome_fantasia - $endereco_config - Tel.: $telefone_cliente_1"; ?>
</div>






</div>


</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->