<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "compra_impressao_2";
$titulo = "Confirma&ccedil;&atilde;o de Compra";
$modulo = "compras";
$menu = "compras";
// ================================================================================================================


// ====== CONVERTE DATA ===========================================================================================
function ConverteData($data_x){
	if (strstr($data_x, "/"))
	{
	$d = explode ("/", $data_x);
	$rstData = "$d[2]-$d[1]-$d[0]";
	return $rstData;
	}
}
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);

$numero_compra = $_POST["numero_compra"];

$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $_POST["filial_busca"];
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
include ("../../includes/conecta_bd.php");

$busca_compra = mysqli_query ($conexao, 
"SELECT 
	compras.codigo,
	compras.numero_compra,
	compras.fornecedor,
	compras.produto,
	compras.data_compra,
	compras.quantidade,
	compras.preco_unitario,
	compras.valor_total,
	compras.unidade,
	compras.tipo,
	compras.observacao,
	compras.data_pagamento,
	compras.usuario_cadastro,
	compras.hora_cadastro,
	compras.data_cadastro,
	compras.usuario_alteracao,
	compras.hora_alteracao,
	compras.data_alteracao,
	compras.estado_registro,
	compras.filial,
	compras.fornecedor_print,
	compras.forma_entrega,
	compras.usuario_exclusao,
	compras.hora_exclusao,
	compras.data_exclusao,
	compras.umidade,
	compras.broca,
	compras.impureza,
	compras.desconto_quantidade,
	compras.motivo_alteracao_quantidade,
	compras.quantidade_original,
	compras.valor_total_original,
	compras.usuario_altera_quant,
	compras.data_altera_quant,
	compras.hora_altera_quant,
	cadastro_pessoa.nome,
	cadastro_pessoa.tipo,
	cadastro_pessoa.cpf,
	cadastro_pessoa.cnpj,
	cadastro_pessoa.cidade,
	cadastro_pessoa.estado,
	cadastro_pessoa.telefone_1,
	cadastro_pessoa.codigo_pessoa
FROM
	compras, cadastro_pessoa
WHERE
	compras.numero_compra='$numero_compra' AND
	compras.fornecedor=cadastro_pessoa.codigo");

include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_compra = mysqli_num_rows ($busca_compra);
$aux_compra = mysqli_fetch_row($busca_compra);
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
$id_w = $aux_compra[0];
$numero_compra_w = $aux_compra[1];
$cod_fornecedor_w = $aux_compra[2];
$produto_print_w = $aux_compra[3];
$data_compra_w = $aux_compra[4];
$quantidade_w = $aux_compra[5];
$preco_unitario_w = $aux_compra[6];
$total_geral_w = $aux_compra[7];
$unidade_w = $aux_compra[8];
$tipo_w = $aux_compra[9];
$observacao_w = $aux_compra[10];
$data_pagamento_w = $aux_compra[11];
$usuario_cadastro_w = $aux_compra[12];
$hora_cadastro_w = $aux_compra[13];
$data_cadastro_w = $aux_compra[14];
$usuario_alteracao_w = $aux_compra[15];
$hora_alteracao_w = $aux_compra[16];
$data_alteracao_w = $aux_compra[17];
$estado_registro_w = $aux_compra[18];
$filial_w = $aux_compra[19];
$fornecedor_print_w = $aux_compra[20];
$forma_entrega_w = $aux_compra[21];
$usuario_exclusao_w = $aux_compra[22];
$hora_exclusao_w = $aux_compra[23];
$data_exclusao_w = $aux_compra[24];
$umidade_w = $aux_compra[25];
$broca_w = $aux_compra[26];
$impureza_w = $aux_compra[27];
$desconto_quant_w = $aux_compra[28];
$motivo_ateracao_quant_w = $aux_compra[29];
$quantidade_original_w = $aux_compra[30];
$valor_total_original_w = $aux_compra[31];
$usuario_altera_quant_w = $aux_compra[32];
$data_altera_quant_w = $aux_compra[33];
$hora_altera_quant_w = $aux_compra[34];
$pessoa_nome_w = $aux_compra[35];
$pessoa_tipo_w = $aux_compra[36];
$pessoa_cpf_w = $aux_compra[37];
$pessoa_cnpj_w = $aux_compra[38];
$pessoa_cidade_w = $aux_compra[39];
$pessoa_estado_w = $aux_compra[40];
$pessoa_telefone_w = $aux_compra[41];
$codigo_pessoa_w = $aux_compra[42];


if ($pessoa_tipo_w == "PF" or $pessoa_tipo_w == "pf")
{$pessoa_cpf_cnpj = $pessoa_cpf_w;}
else
{$pessoa_cpf_cnpj = $pessoa_cnpj_w;}


$quantidade_print = number_format($quantidade_w,2,",",".") . " " . $unidade_w;
$preco_unitario_print = "R$ " . number_format($preco_unitario_w,2,",",".");
$total_geral_print = "R$ " . number_format($total_geral_w,2,",",".");
$data_pagamento_print = date('d/m/Y', strtotime($data_pagamento_w));


if (!empty($linha_compra))
{
$data_compra_print = date('d/m/Y', strtotime($data_compra_w));

$cidade_uf = $pessoa_cidade_w . "-" . $pessoa_estado_w;
$conta_caracter = strlen($cidade_uf);
if ($conta_caracter <= 18)
{$cidade_print = "<div style='font-size:12px; margin-left:5px; margin-top:6px; overflow:hidden'>$cidade_uf</div>";}
else
{$cidade_print = "<div style='font-size:9px; margin-left:5px; margin-top:2px; overflow:hidden'>$cidade_uf</div>";}
}


if (!empty($data_altera_quant_w))
{
$data_altera_quant_print = date('d/m/Y', strtotime($data_altera_quant_w));
$desconto_quant_print = number_format($desconto_quant_w,2,",",".") . " " . $unidade_w;
$quantidade_original_print = number_format($quantidade_original_w,2,",",".") . " " . $unidade_w;
$valor_total_original_print = "R$ " . number_format($valor_total_original_w,2,",",".");
$desconto_em_valor = ($desconto_quant_w * $preco_unitario_w);
$desconto_em_valor_print = "R$ " . number_format($desconto_em_valor,2,",",".");
}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}
// ======================================================================================================


// ====== BUSCA PAGAMENTOS  ===========================================================================
include ("../../includes/conecta_bd.php");

$busca_pgto = mysqli_query ($conexao, 
"SELECT 
	codigo,
	codigo_compra,
	codigo_favorecido,
	forma_pagamento,
	data_pagamento,
	valor,
	banco_cheque,
	observacao,
	usuario_cadastro,
	hora_cadastro,
	data_cadastro,
	estado_registro,
	situacao_pagamento,
	filial,
	codigo_pessoa,
	numero_cheque,
	banco_ted,
	origem_pgto,
	codigo_fornecedor,
	produto,
	favorecido_print,
	cod_produto,
	agencia,
	num_conta,
	tipo_conta,
	nome_banco,
	cpf_cnpj
FROM 
	favorecidos_pgto
WHERE 
	codigo_compra='$numero_compra' AND
	estado_registro='ATIVO'
ORDER BY 
	codigo");

$soma_pgto = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT 
	SUM(valor) 
FROM 
	favorecidos_pgto 
WHERE
	codigo_compra='$numero_compra' AND
	estado_registro='ATIVO'"));

include ("../../includes/desconecta_bd.php");
// ================================================================================================================


// ================================================================================================================
$linha_pgto = mysqli_num_rows ($busca_pgto);

$saldo_a_pagar = $total_geral_w - $soma_pgto[0];
$total_pago_print = "R$ " . number_format($soma_pgto[0],2,",",".");
$saldo_a_pagar_print = "R$ " . number_format($saldo_a_pagar,2,",",".");
// ======================================================================================================


// ====== MONTA MENSAGEM =================================================================================
if (empty($linha_compra))
{$erro = 1;
$msg = "<div style='width:230px; height:20px; border:1px solid #F00; font-size:14px; color:#F00; float:right; text-align:center'>
<div style='margin-top:0px'>COMPRA N&Atilde;O ENCONTRADA</div></div>";}

if ($estado_registro_w == "EXCLUIDO")
{$erro = 2;
$msg = "<div style='width:230px; height:20px; border:1px solid #F00; font-size:14px; color:#F00; float:right; text-align:center'>
<div style='margin-top:0px'>COMPRA EXCLU&Iacute;DA</div></div>";}
// ======================================================================================================


// ================================================================================================================
include ("../../includes/head_impressao.php");
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo $titulo; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ("../../includes/javascript.php"); ?>
</script>
</head>


<!-- ====== INÍCIO ================================================================================================ -->
<body onLoad="imprimir()">

<div style="width:750px; border:0px solid #000; float:left">


<div style="width:720px; height:80px; border:0px solid #000; margin-left:25px; margin-top:5px; float:left">

	<!-- ====== LOGOMARCA ====== -->
	<div style="width:310px; height:80px; border:0px solid #000; font-size:18px; float:left; text-align:left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" border="0" height="80px" />
    </div>

	<!-- ====== TÍTULO ========= -->
	<div style="width:400px; height:50px; border:0px solid #000; font-size:26px; margin-top:20px; float:right; text-align:right">
	<?php echo $titulo; ?>
    </div>

</div>


<div style="width:720px; height:25px; border:0px solid #000; margin-left:25px; float:left; text-align:center">

    <div style="width:720px; height:22px; border:0px solid #000; float:left">

        <!-- ====== BLOCO 4 ====== -->
        <div style="width:200px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:left">
        <?php echo $data_compra_print; ?>
        </div>

        <!-- ====== BLOCO 5 ====== -->
        <div style="width:305px; height:22px; border:0px solid #000; font-size:18px; float:left; overflow:hidden; text-align:center">
			<div style="height:auto"><?php echo $produto_print_w . " " . $msg; ?></div>
        </div>

        <!-- ====== BLOCO 6 ====== -->
        <div style="width:200px; height:22px; border:0px solid #000; font-size:18px; float:right" align="right">
		N&ordm; <?php echo $numero_compra; ?>
        </div>
    </div>

</div>


<!-- ======================================================================================================================================= -->
<div style="width:700px; height:230px; border:0px solid #00F; margin-top:0px; margin-left:50px; float:left">



<!-- ================================================================================================================ -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:500px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Fornecedor
    </div>
    
    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
	<div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:317px; height:16px; text-align:left; overflow:hidden"><b><?php echo $pessoa_nome_w; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	<?php
    if ($pessoa_tipo_w == "PJ" or $pessoa_tipo_w == "pj")
    {echo "CNPJ";}
    else
    {echo "CPF";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $pessoa_cpf_cnpj; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Cidade
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
	<div style="width:153px; height:25px; border:1px solid #000; float:left; background-color:#FFF"><?php echo $cidade_print; ?></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ======= QUANTIDADE ============================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Tipo
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $tipo_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PREÇO ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Quantidade
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden">
	<?php echo $quantidade_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO =================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Pre&ccedil;o Unit&aacute;rio
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $preco_unitario_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= VALOR TOTAL ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Valor Total
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden">
	<b><?php echo $total_geral_print; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ======= FORMA DE ENTREGA ======================================================================================= -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Forma de Entrega:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $forma_entrega_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA PAGAMENTO ======================================================================================== -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Umidade:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $umidade_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  UMIDADE ============================================================================================== -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Broca:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $broca_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  BROCA ======================================================================================== -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Impureza:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $impureza_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
<div style="width:678px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:627px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:80px; height:14px; margin-left:0px; float:left; text-align:left">Observa&ccedil;&atilde;o:</div>
        <div style="width:545px; height:14px; margin-left:0px; float:left; text-align:left"><?php echo $observacao_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<?php
if ($desconto_quant_w > 0)
{echo "
<div style='width:690px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>

    <div style='width:690px; height:30px; float:left; border:1px solid transparent; margin-left:0px'>
	<div style='width:665px; height:30px; border:1px solid #000; float:left; font-size:10px; text-align:center; background-color:#FFF'>
        <div style='margin-top:3px; margin-left:10px; width:610px; height:12px; text-align:left; overflow:hidden'>
        <b>DESCONTO DE QUANTIDADE: $desconto_quant_print</b> | QUANTIDADE ORIGINAL: $quantidade_original_print | VALOR ORIGINAL: $valor_total_original_print
        </div>
        <div style='margin-top:2px; margin-left:10px; width:610px; height:12px; text-align:left; overflow:hidden'>
        $motivo_ateracao_quant_w
        </div>
    </div>
    </div>
</div>
";}
?>
<!-- ================================================================================================================ -->



</div>
<!-- ======= FIM DIV ================================================================================================ -->





<!-- ========= PAGAMENTOS ========================================================================================== -->
<div style="width:750px; height:25px; border:0px solid #F00; margin-top:0px; margin-left:0px; float:left">

<div style="width:725px; height:20px; border:0px solid #000; float:left; margin-left:22px">
	<div style="width:200px; height:18px; float:left; font-size:14px">Pagamento</div>
	<div style="width:240px; height:18px; float:left; text-align:right; font-size:14px">Total Pago: <?php echo $total_pago_print; ?></div>
	<div style="width:240px; height:18px; float:right; text-align:right; font-size:14px">Saldo a Pagar: <?php echo $saldo_a_pagar_print; ?></div>
</div>

</div>
<!-- ================================================================================================================ -->







<!-- ========= RELATÓRIO DE PAGAMENTOS =========================================================================== -->
<div style="width:750px; height:60px; border:0px solid #000; margin-left:0px; margin-top:0px; float:left; overflow:hidden">
<?php

if ($linha_pgto > 0)
{echo "
<div style='width:730px; border:0px solid #000; margin-top:1px; margin-left:20px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:65px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Data</div></div>
	<div style='width:240px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Favorecido</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Forma de PGTO</div></div>
	<div style='width:150px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Dados Banc&aacute;rios</div></div>
	<div style='width:70px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Quant. Ref.</div></div>
	<div style='width:75px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Valor</div></div>
	<div style='width:20px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>PG</div></div>
</div>";}




// ====== FUNÇÃO FOR ===================================================================================
for ($z=1 ; $z<=$linha_pgto ; $z++)
{
$aux_pgto = mysqli_fetch_row($busca_pgto);

// ====== DADOS DO CADASTRO ============================================================================
$id_z = $aux_pgto[0];
$codigo_compra_z = $aux_pgto[1];
$codigo_favorecido_z = $aux_pgto[2];
$forma_pagamento_z = $aux_pgto[3];
$data_pagamento_z = $aux_pgto[4];
$valor_z = $aux_pgto[5];
$banco_cheque_z = $aux_pgto[6];
$observacao_z = $aux_pgto[7];
$usuario_cadastro_z = $aux_pgto[8];
$hora_cadastro_z = $aux_pgto[9];
$data_cadastro_z = $aux_pgto[10];
$estado_registro_z = $aux_pgto[11];
$situacao_pagamento_z = $aux_pgto[12];
$filial_z = $aux_pgto[13];
$codigo_pessoa_z = $aux_pgto[14];
$numero_cheque_z = $aux_pgto[15];
$banco_ted_z = $aux_pgto[16];
$origem_pgto_z = $aux_pgto[17];
$codigo_fornecedor_z = $aux_pgto[18];
$produto_z = $aux_pgto[19];
$favorecido_print = $aux_pgto[20];
$cod_produto_z = $aux_pgto[21];
$agencia_z = $aux_pgto[22];
$num_conta_z = $aux_pgto[23];
$tipo_conta_z = $aux_pgto[24];
$nome_banco_z = $aux_pgto[25];
$cpf_cnpj_z = $aux_pgto[26];


$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_z));
$valor_print = number_format($valor_z,2,",",".");

$quant_ref = $valor_z / $preco_unitario_w;
$quant_ref_print = number_format($quant_ref,2,",",".") . " " . $unidade_w;


if($situacao_pagamento_z == "PAGO")
{$situacao_pagamento_print = "<b>&#10004;</b>";}
elseif($situacao_pagamento_z == "EM_ABERTO")
{$situacao_pagamento_print = "";}
else
{$situacao_pagamento_print = "";}


if($tipo_conta_z == "corrente")
{$tipo_conta_aux = "C/C";}
elseif($tipo_conta_z == "poupanca")
{$tipo_conta_aux = "C/P";}
else
{$tipo_conta_aux = "";}


if($banco_cheque_z == "SICOOB")
{$banco_cheque_aux = "Sicoob";}
elseif($banco_cheque_z == "BANCO DO BRASIL")
{$banco_cheque_aux = "Banco do Brasil";}
elseif($banco_cheque_z == "BANESTES")
{$banco_cheque_aux = "Banestes";}
else
{$banco_cheque_aux = "";}


if($origem_pgto_z == "SOLICITACAO")
{$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";}
else
{$origem_pgto_print = "COMPRA";
$codigo_compra_print = $codigo_compra_z;}


if($forma_pagamento_z == "TED")
{$forma_pagamento_print = "Transfer&ecirc;ncia";
$nome_banco_print = $nome_banco_z;
$agencia_print = $agencia_z;
$num_conta_print = $num_conta_z;
$tipo_conta_print = $tipo_conta_aux;
$dados_bancarios_print = "AG: " . $agencia_print . " " . $tipo_conta_print . ": " . $num_conta_print;}

elseif($forma_pagamento_z == "CHEQUE")
{$forma_pagamento_print = "Cheque";
$nome_banco_print = $banco_cheque_aux;
$agencia_print = "";
$num_conta_print = $numero_cheque_z;
$tipo_conta_print = "";
$dados_bancarios_print = "N&ordm; Cheque: " . $numero_cheque_z;}

else
{$forma_pagamento_print = $forma_pagamento_z;
$nome_banco_print = "";
$agencia_print = "";
$num_conta_print = "";
$tipo_conta_print = "";
$dados_bancarios_print = "";}


$conta_caracter = strlen($cpf_cnpj_z);
if ($conta_caracter == 14)
{$cpf_cnpj_print = "CPF: " . $cpf_cnpj_z;}
elseif ($conta_caracter > 14)
{$cpf_cnpj_print = "CNPJ: " . $cpf_cnpj_z;}
else
{$cpf_cnpj_print = "";}


if (!empty($usuario_cadastro_z))
{$dados_cadastro_z = " &#13; Cadastrado por: " . $usuario_cadastro_z . " " . date('d/m/Y', strtotime($data_cadastro_z)) . " " . $hora_cadastro_z;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
echo "
<div style='width:730px; height:32px; border:0px solid #FFF; margin-top:4px; margin-left:20px; float:left; color:#000; font-size:10px'>

	<div style='width:65px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px'>$data_pgto_print</div>
	</div>
	
	<div style='width:240px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
	<div style='width:232px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$favorecido_print</div>
	<div style='width:232px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$cpf_cnpj_print</div>
	</div>
	
	<div style='width:80px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$forma_pagamento_print</div></div>

	<div style='width:150px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
	<div style='width:142px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$nome_banco_print</div>
	<div style='width:142px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$dados_bancarios_print</div>		
	</div>

	<div style='width:70px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$quant_ref_print</div></div>

	<div style='width:75px; height:30px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-right:6px; overflow:hidden; margin-top:2px'><b>$valor_print</b></div></div>

	<div style='width:20px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:8px'>$situacao_pagamento_print</div></div>
	
</div>";

}

?>

</div>
<!-- ======= FIM DIV ================================================================================================ -->








<!-- ========= ASSINATURAS ========================================================================================== -->
<div style="width:720px; height:60px; border:0px solid #000; margin-left:25px; margin-top:0px; float:left">

<div style='width:700px; height:38px; border:1px solid #FFF; margin-left:10px; margin-top:0px; font-size:10px; float:left'>
    <div style='width:240px; height:36px; border-bottom:1px solid #000; float:left; text-align:center; margin-left:80px'></div>
    <div style='width:240px; height:36px; border-bottom:1px solid #000; float:left; text-align:center; margin-left:80px'></div>
</div>

<div style='width:700px; height:22px; border:1px solid #FFF; margin-left:10px; margin-top:0px; font-size:10px; float:left'>
    <div style='width:240px; height:20px; border:1px solid #FFF; float:left; text-align:center; margin-left:80px'><?php echo $nome_fantasia_m; ?></div>
    <div style='width:240px; height:20px; border:1px solid #FFF; float:left; text-align:center; margin-left:80px'><?php echo $pessoa_nome_w; ?></div>
</div>

</div>
<!-- ======= FIM DIV ================================================================================================ -->








<!-- ======= LINHA ========================================================================================= -->
<div style="width:720px; height:10px; border:0px solid #000; margin-left:25px; margin-top:0px; font-size:10px; float:left; text-align:right">1&ordf VIA</div>
<div style="width:720px; height:5px; border-bottom:2px solid #999; margin-left:25px; float:left"></div>
<div style="width:720px; height:5px; border:0px solid #000; margin-left:25px; margin-top:0px; font-size:10px; float:left; text-align:right"></div>
<!-- ========================================================================================================= -->




<!-- ======= RODAPÉ ========================================================================================= -->
<div style="width:720px; height:20px; border:0px solid #000; margin-left:25px; float:left">
    <div style="width:200px; height:18px; border:0px solid #000; font-size:10px; float:left; text-align:left">
    <?php echo "&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m"; ?>
    </div>
    
    <div style="width:300px; height:18px; border:0px solid #000; font-size:10px; float:left; text-align:center">
    <?php echo "$dados_cadastro_w"; ?>
    </div>
    
    <div style="width:200px; height:18px; border:0px solid #000; font-size:10px; float:right; text-align:right">
    <?php echo"FILIAL: $filial_w"; ?>
    </div>
</div>
<!-- ========================================================================================================= -->



<!-- ======= FIM 1ª VIA ====================================================================================== -->
<!-- ========================================================================================================= -->




<!-- ======= LINHA PICOTADA =================================================================================== -->
<div style="width:720px; height:5px; border-bottom:1px dashed #000; margin-left:25px; float:left"></div>
<!-- ========================================================================================================= -->














<div style="width:720px; height:80px; border:0px solid #000; margin-left:25px; margin-top:5px; float:left">

	<!-- ====== LOGOMARCA ====== -->
	<div style="width:310px; height:80px; border:0px solid #000; font-size:18px; float:left; text-align:left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" border="0" height="80px" />
    </div>

	<!-- ====== TÍTULO ========= -->
	<div style="width:400px; height:50px; border:0px solid #000; font-size:26px; margin-top:20px; float:right; text-align:right">
	<?php echo $titulo; ?>
    </div>

</div>


<div style="width:720px; height:25px; border:0px solid #000; margin-left:25px; float:left; text-align:center">

    <div style="width:720px; height:22px; border:0px solid #000; float:left">

        <!-- ====== BLOCO 4 ====== -->
        <div style="width:200px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:left">
        <?php echo $data_compra_print; ?>
        </div>

        <!-- ====== BLOCO 5 ====== -->
        <div style="width:305px; height:22px; border:0px solid #000; font-size:18px; float:left; overflow:hidden; text-align:center">
			<div style="height:auto"><?php echo $produto_print_w . " " . $msg; ?></div>
        </div>

        <!-- ====== BLOCO 6 ====== -->
        <div style="width:200px; height:22px; border:0px solid #000; font-size:18px; float:right" align="right">
		N&ordm; <?php echo $numero_compra; ?>
        </div>
    </div>

</div>


<!-- ======================================================================================================================================= -->
<div style="width:700px; height:230px; border:0px solid #00F; margin-top:0px; margin-left:50px; float:left">



<!-- ================================================================================================================ -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:500px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Fornecedor
    </div>
    
    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
	<div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:317px; height:16px; text-align:left; overflow:hidden"><b><?php echo $pessoa_nome_w; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	<?php
    if ($pessoa_tipo_w == "PJ" or $pessoa_tipo_w == "pj")
    {echo "CNPJ";}
    else
    {echo "CPF";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $pessoa_cpf_cnpj; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Cidade
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
	<div style="width:153px; height:25px; border:1px solid #000; float:left; background-color:#FFF"><?php echo $cidade_print; ?></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Tipo
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $tipo_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Quantidade
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden">
	<?php echo $quantidade_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
	Pre&ccedil;o Unit&aacute;rio
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo $preco_unitario_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:167px; height:17px; border:1px solid transparent; float:left; font-size:12px">
    Valor Total
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden">
	<b><?php echo $total_geral_print; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Forma de Entrega:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $forma_entrega_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Umidade:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $umidade_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Broca:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $broca_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:339px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:286px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:140px; height:14px; margin-left:0px; float:left; text-align:left">Impureza:</div>
        <div style="width:140px; height:14px; margin-left:0px; float:right; text-align:left; overflow:hidden"><?php echo $impureza_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:690px; height:5px; border:0px solid #000; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:678px; height:18px; border:1px solid transparent; margin-top:0px; float:left">
    <div style="width:627px; height:16px; border-bottom:1px solid #000; float:left; font-size:12px; margin-left:20px">
    	<div style="width:80px; height:14px; margin-left:0px; float:left; text-align:left">Observa&ccedil;&atilde;o:</div>
        <div style="width:545px; height:14px; margin-left:0px; float:left; text-align:left"><?php echo $observacao_w; ?></div>
	</div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<?php
if ($desconto_quant_w > 0)
{echo "
<div style='width:690px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>

    <div style='width:690px; height:30px; float:left; border:1px solid transparent; margin-left:0px'>
	<div style='width:665px; height:30px; border:1px solid #000; float:left; font-size:10px; text-align:center; background-color:#FFF'>
        <div style='margin-top:3px; margin-left:10px; width:610px; height:12px; text-align:left; overflow:hidden'>
        <b>DESCONTO DE QUANTIDADE: $desconto_quant_print</b> | QUANTIDADE ORIGINAL: $quantidade_original_print | VALOR ORIGINAL: $valor_total_original_print
        </div>
        <div style='margin-top:2px; margin-left:10px; width:610px; height:12px; text-align:left; overflow:hidden'>
        $motivo_ateracao_quant_w
        </div>
    </div>
    </div>
</div>
";}
?>
<!-- ================================================================================================================ -->



</div>
<!-- ======= FIM DIV ================================================================================================ -->





<!-- ========= PAGAMENTOS ========================================================================================== -->
<div style="width:750px; height:25px; border:0px solid #F00; margin-top:0px; margin-left:0px; float:left">

<div style="width:725px; height:20px; border:0px solid #000; float:left; margin-left:22px">
	<div style="width:200px; height:18px; float:left; font-size:14px">Pagamento</div>
	<div style="width:240px; height:18px; float:left; text-align:right; font-size:14px">Total Pago: <?php echo $total_pago_print; ?></div>
	<div style="width:240px; height:18px; float:right; text-align:right; font-size:14px">Saldo a Pagar: <?php echo $saldo_a_pagar_print; ?></div>
</div>

</div>
<!-- ================================================================================================================ -->







<!-- ========= RELATÓRIO DE PAGAMENTOS =========================================================================== -->
<div style="width:750px; height:60px; border:0px solid #000; margin-left:0px; margin-top:0px; float:left; overflow:hidden">
<?php

if ($linha_pgto > 0)
{echo "
<div style='width:730px; border:0px solid #000; margin-top:1px; margin-left:20px; float:left; color:#FFF; font-size:9px; text-align:center'>
	<div style='width:65px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Data</div></div>
	<div style='width:240px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Favorecido</div></div>
	<div style='width:80px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Forma de PGTO</div></div>
	<div style='width:150px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Dados Banc&aacute;rios</div></div>
	<div style='width:70px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Quant. Ref.</div></div>
	<div style='width:75px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>Valor</div></div>
	<div style='width:20px; height:20px; border:1px solid #000; float:left; background-color:#666; margin-left:2px'><div style='margin-top:4px'>PG</div></div>
</div>";}




// ====== FUNÇÃO FOR ===================================================================================
for ($y=1 ; $y<=$linha_pgto ; $y++)
{
$aux_pgto_y = $aux_pgto;

// ====== DADOS DO CADASTRO ============================================================================
$id_y = $aux_pgto_y[0];
$codigo_compra_y = $aux_pgto_y[1];
$codigo_favorecido_y = $aux_pgto_y[2];
$forma_pagamento_y = $aux_pgto_y[3];
$data_pagamento_y = $aux_pgto_y[4];
$valor_y = $aux_pgto_y[5];
$banco_cheque_y = $aux_pgto_y[6];
$observacao_y = $aux_pgto_y[7];
$usuario_cadastro_y = $aux_pgto_y[8];
$hora_cadastro_y = $aux_pgto_y[9];
$data_cadastro_y = $aux_pgto_y[10];
$estado_registro_y = $aux_pgto_y[11];
$situacao_pagamento_y = $aux_pgto_y[12];
$filial_y = $aux_pgto_y[13];
$codigo_pessoa_y = $aux_pgto_y[14];
$numero_cheque_y = $aux_pgto_y[15];
$banco_ted_y = $aux_pgto_y[16];
$origem_pgto_y = $aux_pgto_y[17];
$codigo_fornecedor_y = $aux_pgto_y[18];
$produto_y = $aux_pgto_y[19];
$favorecido_print = $aux_pgto_y[20];
$cod_produto_y = $aux_pgto_y[21];
$agencia_y = $aux_pgto_y[22];
$num_conta_y = $aux_pgto_y[23];
$tipo_conta_y = $aux_pgto_y[24];
$nome_banco_y = $aux_pgto_y[25];
$cpf_cnpj_y = $aux_pgto_y[26];


$data_pgto_print = date('d/m/Y', strtotime($data_pagamento_y));
$valor_print = number_format($valor_y,2,",",".");

$quant_ref = $valor_y / $preco_unitario_w;
$quant_ref_print = number_format($quant_ref,2,",",".") . " " . $unidade_w;


if($situacao_pagamento_y == "PAGO")
{$situacao_pagamento_print = "<b>&#10004;</b>";}
elseif($situacao_pagamento_y == "EM_ABERTO")
{$situacao_pagamento_print = "";}
else
{$situacao_pagamento_print = "";}


if($tipo_conta_y == "corrente")
{$tipo_conta_aux = "C/C";}
elseif($tipo_conta_y == "poupanca")
{$tipo_conta_aux = "C/P";}
else
{$tipo_conta_aux = "";}


if($banco_cheque_y == "SICOOB")
{$banco_cheque_aux = "Sicoob";}
elseif($banco_cheque_y == "BANCO DO BRASIL")
{$banco_cheque_aux = "Banco do Brasil";}
elseif($banco_cheque_y == "BANESTES")
{$banco_cheque_aux = "Banestes";}
else
{$banco_cheque_aux = "";}


if($origem_pgto_y == "SOLICITACAO")
{$origem_pgto_print = "Solicita&ccedil;&atilde;o de Remessa";
$codigo_compra_print = "(Solicita&ccedil;&atilde;o)";}
else
{$origem_pgto_print = "COMPRA";
$codigo_compra_print = $codigo_compra_y;}


if($forma_pagamento_y == "TED")
{$forma_pagamento_print = "Transfer&ecirc;ncia";
$nome_banco_print = $nome_banco_y;
$agencia_print = $agencia_y;
$num_conta_print = $num_conta_y;
$tipo_conta_print = $tipo_conta_aux;
$dados_bancarios_print = "AG: " . $agencia_print . " " . $tipo_conta_print . ": " . $num_conta_print;}

elseif($forma_pagamento_y == "CHEQUE")
{$forma_pagamento_print = "Cheque";
$nome_banco_print = $banco_cheque_aux;
$agencia_print = "";
$num_conta_print = $numero_cheque_y;
$tipo_conta_print = "";
$dados_bancarios_print = "N&ordm; Cheque: " . $numero_cheque_y;}

else
{$forma_pagamento_print = $forma_pagamento_y;
$nome_banco_print = "";
$agencia_print = "";
$num_conta_print = "";
$tipo_conta_print = "";
$dados_bancarios_print = "";}


$conta_caracter = strlen($cpf_cnpj_y);
if ($conta_caracter == 14)
{$cpf_cnpj_print = "CPF: " . $cpf_cnpj_y;}
elseif ($conta_caracter > 14)
{$cpf_cnpj_print = "CNPJ: " . $cpf_cnpj_y;}
else
{$cpf_cnpj_print = "";}


if (!empty($usuario_cadastro_y))
{$dados_cadastro_y = " &#13; Cadastrado por: " . $usuario_cadastro_y . " " . date('d/m/Y', strtotime($data_cadastro_y)) . " " . $hora_cadastro_y;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
echo "
<div style='width:730px; height:32px; border:0px solid #FFF; margin-top:4px; margin-left:20px; float:left; color:#000; font-size:10px'>

	<div style='width:65px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px'>$data_pgto_print</div>
	</div>
	
	<div style='width:240px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
	<div style='width:232px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$favorecido_print</div>
	<div style='width:232px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$cpf_cnpj_print</div>
	</div>
	
	<div style='width:80px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$forma_pagamento_print</div></div>

	<div style='width:150px; height:30px; border:1px solid #000; float:left; text-align:left; background-color:#FFF; margin-left:2px'>
	<div style='width:142px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$nome_banco_print</div>
	<div style='width:142px; height:11px; margin-left:6px; margin-top:2px; overflow:hidden'>$dados_bancarios_print</div>		
	</div>

	<div style='width:70px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:2px; overflow:hidden'>$quant_ref_print</div></div>

	<div style='width:75px; height:30px; border:1px solid #000; float:left; text-align:right; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-right:6px; overflow:hidden; margin-top:2px'><b>$valor_print</b></div></div>

	<div style='width:20px; height:30px; border:1px solid #000; float:left; text-align:center; background-color:#FFF; margin-left:2px'>
	<div style='height:11px; margin-left:0px; margin-top:8px'>$situacao_pagamento_print</div></div>
	
</div>";

}

?>

</div>
<!-- ======= FIM DIV ================================================================================================ -->








<!-- ========= ASSINATURAS ========================================================================================== -->
<div style="width:720px; height:60px; border:0px solid #000; margin-left:25px; margin-top:0px; float:left">

<div style='width:700px; height:38px; border:1px solid #FFF; margin-left:10px; margin-top:0px; font-size:10px; float:left'>
    <div style='width:240px; height:36px; border-bottom:1px solid #000; float:left; text-align:center; margin-left:80px'></div>
    <div style='width:240px; height:36px; border-bottom:1px solid #000; float:left; text-align:center; margin-left:80px'></div>
</div>

<div style='width:700px; height:22px; border:1px solid #FFF; margin-left:10px; margin-top:0px; font-size:10px; float:left'>
    <div style='width:240px; height:20px; border:1px solid #FFF; float:left; text-align:center; margin-left:80px'><?php echo $nome_fantasia_m; ?></div>
    <div style='width:240px; height:20px; border:1px solid #FFF; float:left; text-align:center; margin-left:80px'><?php echo $pessoa_nome_w; ?></div>
</div>

</div>
<!-- ======= FIM DIV ================================================================================================ -->








<!-- ======= LINHA ========================================================================================= -->
<div style="width:720px; height:10px; border:0px solid #000; margin-left:25px; margin-top:0px; font-size:10px; float:left; text-align:right">2&ordf VIA</div>
<div style="width:720px; height:5px; border-bottom:2px solid #999; margin-left:25px; float:left"></div>
<div style="width:720px; height:5px; border:0px solid #000; margin-left:25px; margin-top:0px; font-size:10px; float:left; text-align:right"></div>
<!-- ========================================================================================================= -->




<!-- ======= RODAPÉ ========================================================================================= -->
<div style="width:720px; height:20px; border:0px solid #000; margin-left:25px; float:left">
    <div style="width:200px; height:18px; border:0px solid #000; font-size:10px; float:left; text-align:left">
    <?php echo "&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m"; ?>
    </div>
    
    <div style="width:300px; height:18px; border:0px solid #000; font-size:10px; float:left; text-align:center">
    <?php echo "$dados_cadastro_w"; ?>
    </div>
    
    <div style="width:200px; height:18px; border:0px solid #000; font-size:10px; float:right; text-align:right">
    <?php echo"FILIAL: $filial_w"; ?>
    </div>
</div>
<!-- ========================================================================================================= -->



<!-- ======= FIM 2ª VIA ====================================================================================== -->
<!-- ========================================================================================================= -->
















</div>

</body>
</html>
<!-- ======= FIM ============================================================================================= -->