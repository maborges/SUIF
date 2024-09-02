<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ('../../includes/numero_extenso.php');
include ("../../helpers.php");
$pagina = 'contrato_tratado_impressao';
$menu = 'contratos';
$titulo = 'Contrato Tratado';
$modulo = 'compras';


// ====== DADOS PARA BUSCA =================================================================================
$numero_contrato = $_POST["numero_contrato"];
// =========================================================================================================


// ====== BUSCA CONTRATO =================================================================================
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_tratado WHERE estado_registro!='EXCLUIDO' AND numero_contrato='$numero_contrato' ORDER BY codigo");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);

$fornecedor = $aux_contrato[1];
$cod_produto = $aux_contrato[27];
$data_contrato = date('Y-m-d', strtotime($aux_contrato[2]));
$data_contrato_aux = date('Y-m-d', strtotime($aux_contrato[2]));
$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
$produto = $aux_contrato[5];
$quantidade = $aux_contrato[6];
$quantidade_total = number_format($aux_contrato[23],2,",",".");
$quant_aux = number_format($quantidade,0,"","");
$quantidade_quilo = $aux_contrato[7];
$quant_quilo_aux = number_format($quantidade_quilo,0,"","");
$valor_total = number_format($aux_contrato[22],2,",",".");
$valor = number_format($aux_contrato[9],2,",",".");
$valor_2 = number_format($aux_contrato[9],2,".","");
$novo_valor = number_format($aux_contrato[9],2,",",".");
$unidade = $aux_contrato[21];
$unidade_print = $aux_contrato[21];
$descricao = $aux_contrato[8];
$data_entrega_i = date('d/m/Y', strtotime($aux_contrato[3]));
$data_entrega_f = date('d/m/Y', strtotime($aux_contrato[4]));
$codigo_fiador_1 = $aux_contrato[12];
$codigo_fiador_2 = $aux_contrato[13];
$safra = $aux_contrato[10];
$prazo_pgto = $aux_contrato[11];
$observacao = $aux_contrato[14];
$estado_registro = $aux_contrato[15];
$situacao_contrato = $aux_contrato[16];
$numero_contrato = $aux_contrato[20];
$usuario_cadastro = $aux_contrato[24];
$hora_cadastro = $aux_contrato[26];
$data_cadastro = $aux_contrato[25];

$meses = array ("Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$data_aux = explode("-", $data_contrato_aux);
$dia = $data_aux[2];
$mes = $data_aux[1];
$ano = $data_aux[0];
// ======================================================================================================


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

if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf")
{$cpf_cnpj = $aux_pessoa[3];}
else
{$cpf_cnpj = $aux_pessoa[4];}

if ($aux_pessoa[8] == "")
{$endereco_fornecedor = "";}
else
{$endereco_fornecedor = ", no endere&ccedil;o " . $aux_pessoa[8];}

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

if ($aux_pessoa[2] == "PF" or $aux_pessoa[2] == "pf")
{$apresentacao_pessoa = ", brasileiro(a), produtor agr&iacute;cola, portador do CPF n.&ordm; ";
$endereco_completo = " residente na cidade de " . $cidade_fornecedor . "-" . $estado_fornecedor . $endereco_fornecedor . $num_res_fornecedor . $bairro_fornecedor . $cep_fornecedor;}
else
{$apresentacao_pessoa = ", CNPJ n.&ordm; ";
$endereco_completo = " com sede na cidade de " . $cidade_fornecedor . "-" . $estado_fornecedor . $endereco_fornecedor . $num_res_fornecedor . $bairro_fornecedor . $cep_fornecedor;}
// ======================================================================================================


// ====== BUSCA POR AVALISTAS ==========================================================================
// ====== FIADOR 1 ======
$busca_fiador_1 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$codigo_fiador_1' AND estado_registro!='EXCLUIDO'");
$aux_fiador_1 = mysqli_fetch_row($busca_fiador_1);
$linha_fiador_1 = mysqli_num_rows ($busca_fiador_1);

$fiador_1_print = $aux_fiador_1[1];
if ($aux_fiador_1[2] == "PF" or $aux_fiador_1[2] == "pf")
{$cpf_cnpj_fiador_1 = $aux_fiador_1[3];}
else
{$cpf_cnpj_fiador_1 = $aux_fiador_1[4];}

// ====== FIADOR 2 ======
$busca_fiador_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$codigo_fiador_2' AND estado_registro!='EXCLUIDO'");
$aux_fiador_2 = mysqli_fetch_row($busca_fiador_2);
$linha_fiador_2 = mysqli_num_rows ($busca_fiador_2);

$fiador_2_print = $aux_fiador_2[1];
if ($aux_fiador_2[2] == "PF" or $aux_fiador_2[2] == "pf")
{$cpf_cnpj_fiador_2 = $aux_fiador_2[3];}
else
{$cpf_cnpj_fiador_2 = $aux_fiador_2[4];}
// ===========================================================================================================


// =========================================================================================================
	include ('../../includes/head_impressao.php');
?>


<!-- ==================================   T � T U L O   D A   P � G I N A   ====================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N � C I O   =============================================== -->
<body onLoad="imprimir()">

<div id="centro" style="width:750px; border:0px solid #000; float:left">

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:60px; margin-left:80px; font-size:17px" align="left">
<?php echo "<b>N.&ordm; $numero_contrato</b>"; ?>
</div>

<div id="centro" style="width:640px; height:35px; border:0px solid #000; margin-left:80px; font-size:17px" align="center">
<?php
echo "<b>CONTRATO DE COMPRA E VENDA DE $produto_print</br>EM GRAOS CR&Uacute;S COM ENTREGA FUTURA</b>";
?>
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px" align="center">
<?php
if ($situacao_contrato == "PAGO")
{echo "
<div id='centro' style='width:160px; height:24px; border:1px solid #FF0000; margin-top:0px; margin-left:0px; font-size:14px' align='center'>
<font style='color:#FF0000; font-size:20px'><b>&#160;&#160; BAIXADO &#160;&#160;</b></font>
</div>";}
else
{echo "";}
?>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
COMPRADOR(A): <?php echo"$dados_contrato"; ?>

</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
VENDEDOR(A): <?php echo "<b>" . $fornecedor_print . "</b>" . $apresentacao_pessoa . $cpf_cnpj . ", " . $endereco_completo . ";"; ?>
</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
Resolvem as partes celebrarem o presente contrato de compra e venda de <?php echo "$produto_print"; ?> com entrega futura, com base no C&oacute;digo Civil Brasileiro e nas seguintes cl&aacute;usulas e condi&ccedil;&otilde;es:
</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
</div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA PRIMEIRA - DO OBJETO</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O vendedor se compromete a vender &agrave; compradora o total de
<?php
echo "<b>$quantidade</b>";
echo " ( ";
echo GExtenso::numero($quant_aux);
echo " ) $unidade_print";

if ($quantidade_quilo > 0)
	{echo " e $quantidade_quilo ("; 
	echo GExtenso::numero($quant_quilo_aux);
	echo ") Quilogramas";}
else
	{}
	
echo " de " . $produto_print . ", ";

if ($unidade_print == "SC")
	{echo "beneficiadas com ";
	echo GExtenso::numero($quant_kg_saca);
	echo " quilogramas l&iacute;quidos cada, ";}
else
	{}

echo $descricao . ", safra " . $safra . ";";
?>
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA SEGUNDA - DO PRAZO DE ENTREGA</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O objeto do referente contrato dever&aacute; ser entregue no per&iacute;odo entre <b><?php echo "$data_entrega_i"; ?></b> a <b><?php echo "$data_entrega_f"; ?></b> ou no primeiro dia &uacute;til subsequente, posto no armaz&eacute;m do(a) <?php echo"$razao_social_config"; ?>.
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA TERCEIRA - DO PRE&Ccedil;O</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O pre&ccedil;o ser&aacute; equivalente a <b><?php echo "R$ $novo_valor"; ?></b> (<?php echo Helpers::valorPorExtenso($valor_2); ?> ) por

<?php
echo "$unidade_print";

if ($unidade_print == "SC")
	{echo " de ";
	echo GExtenso::numero($quant_kg_saca);
	echo " quilogramas l&iacute;quidos de ";}
else
	{echo " de ";}

echo "$produto_print";
echo ", perfazendo o presente contrato, um pre&ccedil;o total de ";

if ($quantidade_quilo == "")
{
	$quant_decimal = 0;
}
else
{
	if ($unidade_print == "SC")
	{$quant_decimal = $quantidade_quilo / 60;}
	else
	{$quant_decimal = 0;}
}

$total = ($quantidade + $quant_decimal) * $valor_2;
$total_2 = number_format($total,2,",",".");

echo "<b>R$ " . $total_2 . "</b>.";  
?>

</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA QUARTA - DO PRAZO DE PAGAMENTO</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O pagamento do(a) <?php echo "$produto_print"; ?> ser&aacute; efetuado <b><?php echo "$prazo_pgto"; ?> dias</b> ap&oacute;s a entrega.
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA QUINTA - DA MULTA CONTRATUAL</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O vendedor pagar&aacute; a compradora uma multa de 20% (vinte por cento) caso n&atilde;o entregue a mercadoria dentro do prazo previsto no contrato acima, sendo que a multa ser&aacute; aplicada da seguinte forma: Sobre o valor de mercado do dia do vencimento do contrato ou sobre o valor contratado, sendo que ser&aacute; utilizado o maior valor.
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<u>CL&Aacute;USULA SEXTA - DO RESSARCIAMENTO DAS CUSTAS PROCESSUAIS</u>
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
Se a compradora tiver que utilizar-se de meios judiciais ou legais para receber dos vendedores quaisquer valores oriundos do presente instrumento, sob tais valores incidir&atilde;o honor&aacute;rios advocat&iacute;cios de 20% e juros de 12% A.A, independente das perdas e danos que couberem.
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<!-- <u>CL&Aacute;USULA S&Eacute;TIMA � DO FORO</u> -->
</div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
<!-- As partes elegem o foro de <?php // echo"$cidade_config"; ?>-ES, para dirimir quaisquer d&uacute;vidas do presente contrato, renunciado desde j&aacute;, a qualquer outro, por mais privilegiado que seja.-->
</div>

<div id="centro" style="width:640px; height:20px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:50px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<?php echo $cidade_config . "-" . $uf_config . ", " . $dia . " de " . $meses [$mes-1] . " de " . $ano; ?>
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:11px; float:left" align="justify">
_______________________________________<br />
Vendedor(a): <b style="font-size:10px"><?php echo "$fornecedor_print"; ?></b>
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:18px; font-size:11px; float:left" align="justify">
_______________________________________<br />
Comprador(a): <b style="font-size:10px"><?php echo"$razao_social_config"; ?></b>
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:11px; float:left" align="justify">
_______________________________________<br />
Testemunha: <font style="font-size:10px"><?php echo "$fiador_1_print"; ?></font><br />
<?php // echo "CPF: $fiador_1[3]"; ?>
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:18px; font-size:11px; float:left" align="justify">
_______________________________________<br />
Testemunha: <font style="font-size:10px"><?php echo "$fiador_2_print"; ?></font>
<?php // echo "CPF: $fiador_2[3]"; ?>

</div>



</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->