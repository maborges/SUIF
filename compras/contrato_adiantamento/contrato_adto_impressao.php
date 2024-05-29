<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "contrato_adto_impressao";
$titulo = "Contrato de Adiantamento";
$modulo = "compras";
$menu = "contratos";
// ================================================================================================================

// ======= RECEBENDO POST =========================================================================================
$numero_contrato = $_POST["numero_contrato"];
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
$busca_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_adiantamento WHERE numero_contrato='$numero_contrato'");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);

$id_w = $aux_contrato[0];
$numero_contrato_w = $aux_contrato[1];
$data_contrato_w = $aux_contrato[2];
$data_contrato_print = date('d/m/Y', strtotime($aux_contrato[2]));
$data_vencimento_w = $aux_contrato[3];
$data_vencimento_print = date('d/m/Y', strtotime($aux_contrato[3]));
$cod_fornecedor_w = $aux_contrato[4];
$nome_fornecedor_w = $aux_contrato[5];
$cod_produto_w = $aux_contrato[6];
$nome_produto_w = $aux_contrato[7];
$valor_w = $aux_contrato[8];
$valor_print = "R$ " . number_format($aux_contrato[8],2,",",".");
$safra_w = $aux_contrato[9];
$filial_w = $aux_contrato[10];
$observacao_w = $aux_contrato[11];
$estado_registo_w = $aux_contrato[12];
$pendencia_assinatura_w = $aux_contrato[13];

$rt = $_POST["rt"] ?? '';


$usuario_cadastro_w = $aux_contrato[14];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_contrato[15]));
$hora_cadastro_w = $aux_contrato[16];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_contrato[17];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_contrato[18]));
$hora_alteracao_w = $aux_contrato[19];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_contrato[20];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_contrato[21]));
$hora_exclusao_w = $aux_contrato[22];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ================================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$cod_fornecedor_w'");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
$linha_pessoa = mysqli_num_rows ($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];

if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{$cpf_cnpj_print = "$cpf_pessoa";}
else
{$cpf_cnpj_print = "$cnpj_pessoa";}
// ======================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_w'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ============================================================================================================


// ================================================================================================================
$meses = array ("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
$data_aux = explode("-", $data_contrato_w);
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
// ==================================================================================================================================================	


// ================================================================================================================
$valor_extenso = valorPorExtenso($valor_w);
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head_impressao.php"); 
?>


<!-- ====== T�TULO DA P�GINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== IN�CIO ================================================================================================ -->
<body onLoad="imprimir()">

<div id="centro" style="width:750px; border:0px solid #000; float:left">

<?php
?>


<div id="centro" style="width:640px; height:40px; border:0px solid #000; margin-top:60px; margin-left:80px; font-size:17px" align="center">

<b>CONTRATO DE ADIANTAMENTO FINANCEIRO<br />
COM PAGAMENTO EM PRODUTO "<?php echo"$produto_print"; ?>"
</b>
</div>

<div id="centro" style="width:640px; height:30px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">

<?php
if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{echo "
<b>$nome_pessoa</b>, brasileiro(a), produtoror(a) rural, residente e domiciliado(a) em $cidade_pessoa - $estado_pessoa, portador do CPF n&ordm; <b>$cpf_cnpj_print</b>, doravante denominada <b>COMPROMITENTE RECEBEDOR</b> ";}

else
{echo "
<b>$nome_pessoa</b>, com sua sede situada em $cidade_pessoa - $estado_pessoa, com CNPJ n&ordm; <b>$cpf_cnpj_print</b>, doravante denominada <b>COMPROMITENTE RECEBEDOR</b> ";}

?>

e Grancaf&eacute; Com&eacute;rcio Importa&ccedil;&atilde;o e Exporta&ccedil;&atilde;o de Caf&eacute; Ltda, com sede na Av. Prefeito Samuel Batista Cruz, N. 8481, Bairro Nova Bet&acirc;nia - Linhares-ES, CEP: 29.907-515, inscrita no CNPJ sob o n&ordm; 02.239.346/0001-72, neste ato representada pelo seu Diretor Josemar Moro, brasileiro, casado, Empres&aacute;rio, portador da C.I.R.G. n&ordm; 1.262.923 SSP/ES e do CPF n&ordm; 031.894.117-19, doravante denominada <b>COMPROMITENTE CEDENTE</b> celebram entre si o presente contrato de adiantamento financeiro com pagamento em esp&eacute;cie, nos seguintes termos:</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O COMPROMITENTE CEDENTE disponibiliza ao COMPROMITENTE RECEBEDOR o numer&aacute;rio de <b><?php echo "$valor_print"; ?></b> <?php echo "($valor_extenso )"; ?> l&iacute;quidos na data de <b><?php echo "$data_contrato_print" ?></b>, &agrave; t&iacute;tulo de adiantamento de compra de <?php echo "$produto_print"; ?>, safra <?php echo "$safra_w"; ?>, montante este repassado atrav&eacute;s de deposito banc&aacute;rio em nome do produtor, cheque nominal ou &agrave; seu rogo.</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
Dever&aacute; o COMPROMITENTE RECEBEDOR quitar esta quantia adiantadamente recebida at&eacute; o dia <b><?php echo "$data_vencimento_print"; ?></b>, em produto
<?php
if ($cod_produto_w == "2" or $cod_produto_w == "10") //CAF�
{ echo "caf&eacute; beneficiado, cr&uacute; em gr&atilde;o, safra $safra_w, tipo 7, bebida neutra, padr&atilde;o, bica corrida acondicionados em sacaria de juta em bom estado, com capacidade de 60 (sessenta) quilogramas l&iacute;quidos cada, sendo feito a convers&atilde;o para designar a quantidade de sacas pelo mercado deste produto ao dia do vencimento, sendo que n&atilde;o sofrer&aacute; nenhum ajuste positivo ou negativo  em raz&atilde;o do pre&ccedil;o m&eacute;dio de mercado do dia da entrega, devendo o mesmo se responsabilizar pela entrega que se dar&aacute; por sua conta e ordem, no armaz&eacute;m da Grancaf&eacute;, situado na Av. Prefeito Samuel Batista Cruz, N. 8481, Bairro Nova Bet&acirc;nia - Linhares-ES, CEP: 29.907-515, sem qualquer despesa por parte da COMPROMITENTE CEDENTE.";}

elseif ($cod_produto_w == "3") //PIMENTA
{ echo "pimenta do reino, safra $safra_w, acondicionados em sacaria em bom estado, com capacidade de 50 (cinquenta) quilogramas l&iacute;quidos cada, sendo feito a convers&atilde;o para designar a quantidade de sacas pelo mercado deste produto ao dia do vencimento, sendo que n&atilde;o sofrer&aacute; nenhum ajuste positivo ou negativo em raz&atilde;o do pre&ccedil;o m&eacute;dio de mercado do dia da entrega, devendo o mesmo se responsabilizar pela entrega que se dar&aacute; por sua conta e ordem, no armaz&eacute;m da Grancaf&eacute;, situado na Av. Prefeito Samuel Batista Cruz, N. 8481, Bairro Nova Bet&acirc;nia - Linhares-ES, CEP: 29.907-515, sem qualquer despesa por parte da COMPROMITENTE CEDENTE.";}

elseif ($cod_produto_w == "4") //CACAU
{ echo "cacau em am&ecirc;ndoas, safra $safra_w, acondicionados em sacaria em bom estado, com capacidade de 60 (sessenta) quilogramas l&iacute;quidos cada, sendo feito a convers&atilde;o para designar a quantidade de sacas pelo mercado deste produto ao dia do vencimento, sendo que n&atilde;o sofrer&aacute; nenhum ajuste positivo ou negativo em raz&atilde;o do pre&ccedil;o m&eacute;dio de mercado do dia da entrega, devendo o mesmo se responsabilizar pela entrega que se dar&aacute; por sua conta e ordem, no armaz&eacute;m da Grancaf&eacute;, situado na Av. Prefeito Samuel Batista Cruz, N. 8481, Bairro Nova Bet&acirc;nia - Linhares-ES, CEP: 29.907-515, sem qualquer despesa por parte da COMPROMITENTE CEDENTE.";}

else
{ echo "_________________, safra $safra_w, acondicionados em sacaria em bom estado, sendo feito a convers&atilde;o para designar a quantidade de sacas pelo mercado deste produto ao dia do vencimento, sendo que n&atilde;o sofrer&aacute; nenhum ajuste positivo ou negativo em raz&atilde;o do pre&ccedil;o m&eacute;dio de mercado do dia da entrega, devendo o mesmo se responsabilizar pela entrega que se dar&aacute; por sua conta e ordem, no armaz&eacute;m da Grancaf&eacute;, situado na Av. Prefeito Samuel Batista Cruz, N. 8481, Bairro Nova Bet&acirc;nia - Linhares-ES, CEP: 29.907-515, sem qualquer despesa por parte da COMPROMITENTE CEDENTE.";}

?>

</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
Ao momento da entrega, ap&oacute;s a COMPROMITENTE CEDENTE conferir, determinar a quantidade de sacas referente ao adiantamento, bem como suas especifica&ccedil;&otilde;es t&eacute;cnicas, a mesma dar&aacute;, ap&oacute;s emitida a competente Nota Fiscal em favor da COMPROMITENTE CEDENTE, esta dar&aacute; a mais ampla, geral e irrevog&aacute;vel quita&ccedil;&atilde;o, para n&atilde;o mais repetir.
</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
O presente contrato &eacute; celebrado em car&aacute;ter irrevog&aacute;vel e irretrat&aacute;vel, obrigando as partes, seus herdeiros ou sucessores, a bem e fielmente cumpri-lo.
</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
A eventual inadimpl&ecirc;ncia do COMPROMITENTE RECEBEDOR , consistente na recusa da entrega do produto at&eacute; a data estipulada no contrato, dar&aacute; a COMPROMITENTE CEDENTE o direito de ingressar em ju&iacute;zo, a receber o produto, al&eacute;m de responder o COMPROMITENTE RECEBEDOR por perdas e danos, custas e honor&aacute;rios advocat&iacute;cios, al&eacute;m da multa equivalente a 10%
<?php
if ($cod_produto_w == "2" or $cod_produto_w == "10") //CAF�
{ echo "em sacas de caf&eacute; do mesmo padr&atilde;o e qualidade ora comercializadas.";}
elseif ($cod_produto_w == "3") //PIMENTA
{ echo "em quilogramas de pimenta do reino do mesmo padr&atilde;o e qualidade ora comercializadas.";}
elseif ($cod_produto_w == "4") //CACAU
{ echo "em sacas de cacau do mesmo padr&atilde;o e qualidade ora comercializadas.";}
else
{ echo "em sacas de _____________ do mesmo padr&atilde;o e qualidade ora comercializadas.";}
?>

</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
Para dirimir quaisquer quest&otilde;es que direta ou indiretamente decorrerem deste contrato, as partes elegem o Foro desta Comarca de Linhares, Estado do Esp&iacute;rito Santo, com ren&uacute;ncia expressa de qualquer outro, por mais privilegiado que seja.
</div>

<div id="centro" style="width:640px; height:10px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="justify">
E, por estarem assim ajustados, assinam o presente instrumento em duas vias de igual forma e teor, na presen&ccedil;a de duas testemunhas abaixo.
</div>


<div id="centro" style="width:640px; height:40px; border:0px solid #000; margin-left:80px;"></div>

<div id="centro" style="width:640px; height:50px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px" align="center">
<?php echo "$filial_usuario, " . $dia . " de " . $meses [$mes-1] . " de " . $ano; ?>
</div>

<div id="centro" style="width:310px; height:100px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px; float:left" align="justify">
_______________________________________<br />
Compromitente Recebedor:<br />
<b><?php echo "$nome_pessoa"; ?></b><br />
<b>
<?php
if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{echo"CPF: $cpf_cnpj_print";}
else
{echo"CNPJ: $cpf_cnpj_print";} ?></b>
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:18px; font-size:13px; float:left" align="justify">
_______________________________________<br />
Compromitente Cedente:<br />
<b>Grancaf&eacute; Com&eacute;rcio Imp. e Exp. de Caf&eacute; Ltda</b><br />
<b>CNPJ: 02.239.346/0001-72</b>
</div>


<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:13px; float:left" align="justify">
_______________________________________<br />
Testemunha:
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:18px; font-size:13px; float:left" align="justify">
_______________________________________<br />
Testemunha: 
</div>

<div id="centro" style="width:310px; height:60px; border:0px solid #000; margin-top:0px; margin-left:80px; font-size:11px; float:left" align="justify">
<?php echo "N&deg; $numero_contrato"; ?>
</div>


</div>

</body>
</html>
<!-- ==================================================   FIM   ================================================= -->