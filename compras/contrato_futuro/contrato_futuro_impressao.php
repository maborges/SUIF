<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
include ('../../includes/numero_extenso.php');
include ("../../helpers.php");

$pagina = 'contrato_futuro_impressao';
$menu = 'contratos';
$titulo = 'Contrato Futuro';
$modulo = 'compras';


// ====== DADOS PARA BUSCA =================================================================================
$numero_contrato = $_POST["numero_contrato"];
// =========================================================================================================



// ====== BUSCA CONTRATO =================================================================================
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_futuro WHERE estado_registro!='EXCLUIDO' AND numero_contrato='$numero_contrato' ORDER BY codigo");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);

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

$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
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
{$endereco_fornecedor = ", " . $aux_pessoa[8];}

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

if ($aux_pessoa[2] == "pf")
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

<div id="centro" style="width:750px; border:0px solid #000">
<!-- ================================================================================================================================================== -->
<!-- ===================== INICIO DA PRIMEIRA PAGINA (CONTRATO) ======================================================================================= -->
<div id='centro' style='width:750px; height:1700px; border:0px solid #000; page-break-after:always'>


<div id="centro" style="width:570px; height:40px; border:0px solid #000; margin-top:80px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
<b>INSTRUMENTO PARTICULAR DE CONTRATO DE VENDA E COMPRA DE PRODUTO AGR&Iacute;COLA PARA ENTREGA FUTURA, na forma abaixo:</b>
</div>

<div id="centro" style="width:150px; height:40px; border:0px solid #000; margin-top:1px; margin-left:80px; font-size:9px; float:left" align="left">
<?php // echo "$numero_contrato"; ?>
</div>

<div id="centro" style="width:270px; height:40px; border:0px solid #000; margin-top:0px; margin-left:0px; font-size:14px; float:left" align="center">
<?php // echo "$produto_print"; ?>
</div>

<div id="centro" style="width:150px; height:40px; border:0px solid #000; margin-top:0px; margin-left:0px; font-size:14px; float:left" align="right">
<?php
if ($situacao_contrato == "PAGO")
{echo "
<div id='centro' style='width:auto; height:auto; border:1px solid #FF0000; margin-top:0px; margin-left:0px; font-size:14px; float:left' align='center'>
<font style='color:#FF0000; font-size:20px'><b>&#160;&#160; LIQUIDADO &#160;&#160;</b></font>
</div>";}
else
{echo "";}
?>
</div>

<div id="centro" style="width:748px; height:5px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>I. COMPRADORA.</b> <?php echo "$dados_contrato"; ?>
	</div>
</div>


<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>II. VENDEDOR(A).</b> <?php echo "<b>" . $fornecedor_print . "</b>" . $apresentacao_pessoa . $cpf_cnpj . ", " . $endereco_completo . ";"; ?>
	</div>
</div>



<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>III. OBJETO. </b>
	<?php
    echo "$quantidade";
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
	
	echo "$descricao" . ";";
	?>
	</div>
</div>


<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>IV. AVEN&Ccedil;A.</b> Na melhor forma de direito as partes ora contratantes convencionam a venda e compra do produto agr&iacute;cola descrito no item anterior, mediante as cl&aacute;usulas abaixo, que reciprocamente outorgam em car&aacute;ter irrevog&aacute;vel e irretrat&aacute;vel:
	</div>
</div>



<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>Cl&aacute;usula 1.</b> O pre&ccedil;o certo e ajustado por cada <?php echo "$unidade_print"; ?> de <?php echo "$produto_print"; ?> &eacute; de <?php echo "R$ $preco_produto_print"; ?> (<?php echo Helpers::valorPorExtenso($preco_produto); ?> ), totalizando <?php echo "R$ $valor_total_print"; ?> (<?php echo Helpers::valorPorExtenso($valor_total); ?> ), que a compradora paga neste ato, por op&ccedil;&atilde;o do vendedor, conforme recibo firmado (cheque ou comprovante banc&aacute;rio) que passa a compor o presente contrato; 
	</div>
</div>





<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>Cl&aacute;usula 2.</b> O prazo para entrega pelo vendedor &eacute; at&eacute; o dia <?php echo "$vencimento"; ?>, livre de frete, com direito &agrave; devolu&ccedil;&atilde;o da sacaria, cumprindo-lhe o aviso por qualquer meio, &agrave; compradora, que proceder&aacute; a retirada do produto na fazenda do mesmo, situada no endere&ccedil;o acima;
	</div>
</div>





<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>Cl&aacute;usula 3.</b> O presente contrato tornar-se-&aacute; antecipadamente vencido, e plenamente exig&iacute;vel, caso o vendedor incorra em insolv&ecirc;ncia decorrente de outros compromissos, podendo a compradora, desde logo, adotar as medidas judiciais inclusive cautelares admitidas em direito, objetivando a garantia complementar da obriga&ccedil;&atilde;o ora pactuada;
	</div>
</div>




<div id="centro" style="width:748px; height:220px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>Cl&aacute;usula 4.</b> A t&iacute;tulo de cl&aacute;usula penal, fica convencionada a multa proporcional equivalente a 
	<?php
	if ($multa == NULL or $multa == "" or $multa <= 0)
	{echo "_____";}
	else
	{echo "$multa_print % (" . GExtenso::numero($multa_print) . " por cento) ";}
//echo GExtenso::numero($multa_print);
/*	
	{echo "$multa_print %";
	echo " (";
	echo GExtenso::numero($multa); 
	echo " por cento) ";}
*/	
	?> 
	sobre o valor deste contrato, em caso de inadimplemento de qualquer das cl&aacute;usulas ora aven&ccedil;adas, sem preju&iacute;zo da repara&ccedil;&atilde;o por perdas e danos;
	</div>
</div>





<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	Sub cl&aacute;usula 4.1. O atraso na entrega pelo vendedor autorizar&aacute;, independente de pr&eacute;via notifica&ccedil;&atilde;o, ao registro nos &oacute;rg&atilde;os de prote&ccedil;&atilde;o ao cr&eacute;dito, tais como, Serasa e SPC;
	</div>
</div>






<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	Sub cl&aacute;usula 4.2. De igual modo, o atraso ultrapassar trinta dias implicar&aacute; na transfer&ecirc;ncia do caso para o &acirc;mbito jurisdicional, com imediato aforamento da a&ccedil;&atilde;o ou medidas judiciais cab&iacute;veis;
	</div>
</div>





<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<b>Cl&aacute;usula 5.</b> As partes respondem por si, seus herdeiros e sucessores, para cumprimento deste compromisso;
	</div>
</div>




<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	Lido por todos e achado conforme, firmam o presente contrato em duas vias, &agrave; presen&ccedil;a das testemunhas abaixo assinadas e qualificadas.
	</div>
</div>







<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	<?php echo $cidade_config . "-" . $uf_config . ", " . $dia . " de " . $meses [$mes-1] . " de " . $ano; ?>
	</div>
</div>





<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Compradora: <b><?php echo "$razao_social_config"; ?></b>
	</div>
</div>




<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Vendedor(a): <b><?php echo "$fornecedor_print"; ?></b>
	</div>
</div>




<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Avalista:
	<?php
	if ($linha_fiador_1 == 0)
	{echo "";}
	else
	{echo "$fiador_1_print (CPF: $cpf_cnpj_fiador_1)";}
	?>
	</div>
</div>




<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Avalista:
	<?php
	if ($linha_fiador_2 == 0)
	{echo "";}
	else
	{echo "$fiador_2_print (CPF: $cpf_cnpj_fiador_2)";}
	?>
	</div>
</div>



<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Avalista:
	<?php
	if ($linha_fiador_3 == 0)
	{echo "";}
	else
	{echo "$fiador_3_print (CPF: $cpf_cnpj_fiador_3)";}
	?>
	</div>
</div>






<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:17px; line-height: 25px" align="justify">
	_______________________________________<br />
	Testemunhas
	</div>
</div>



<div id="centro" style="width:748px; height:17px; border:0px solid #000; margin-left:0px; float:left"></div>

<div id="centro" style="width:748px; height:auto; border:0px solid #000; margin-left:0px; float:left">
	<div id="centro" style="width:570px; border:0px solid #000; margin-top:0px; margin-left:124px; font-size:9px" align="justify">
	(N&ordm; Contrato: <?php echo "$numero_contrato"; ?>)
	</div>
</div>





</div>
<!-- ===================== FIM PRIMEIRA PAGINA (CONTRATO) ========================================================================================= -->
<!-- ================================================================================================================================================= -->












</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->