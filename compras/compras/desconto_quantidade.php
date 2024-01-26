<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "desconto_quantidade";
$titulo = "Desconto de Quantidade";
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
$numero_compra = $_POST["numero_compra"];
$quantidade_original = $_POST["quantidade_original"];
$valor_total_original = $_POST["valor_total_original"];

$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
// ================================================================================================================


// ====== BUSCA CONFIGURAÇÕES E PERMISSÕES ========================================================================
include ("../../includes/conecta_bd.php");

$busca_config = mysqli_query ($conexao,
"SELECT
	limite_dias_exclusao_reg,
	limite_dias_edi_compra
FROM
configuracoes");

$busca_permissao = mysqli_query ($conexao,
"SELECT
	*
FROM
	usuarios_permissoes
WHERE
	username='$username'");

include ("../../includes/desconecta_bd.php");

$config = mysqli_fetch_row($busca_config);
$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================


// ==================================================================================================================================================
if ($botao == "ESTORNAR")
{
include ("../../includes/conecta_bd.php");

$estornar = mysqli_query ($conexao, 
"UPDATE
	compras
SET
	quantidade='$quantidade_original',
	valor_total='$valor_total_original',
	desconto_quantidade='0',
	motivo_alteracao_quantidade=NULL,
	usuario_altera_quant=NULL,
	data_altera_quant=NULL,
	hora_altera_quant=NULL
WHERE
	numero_compra='$numero_compra'");
	
include ("../../includes/desconecta_bd.php");
}
// ==================================================================================================================================================


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
	compras.movimentacao,
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
$movimentacao_w = $aux_compra[35];
$pessoa_nome_w = $aux_compra[36];
$pessoa_tipo_w = $aux_compra[37];
$pessoa_cpf_w = $aux_compra[38];
$pessoa_cnpj_w = $aux_compra[39];
$pessoa_cidade_w = $aux_compra[40];
$pessoa_estado_w = $aux_compra[41];
$pessoa_telefone_w = $aux_compra[42];
$codigo_pessoa_w = $aux_compra[43];


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


if ($movimentacao_w == "COMPRA")
{
$movimentacao_print_m = "COMPRA";
$movimentacao_print = "Compra";
}
elseif ($movimentacao_w == "ENTRADA")
{
$movimentacao_print_m = "ENTRADA";
$movimentacao_print = "Entrada";
}
elseif ($movimentacao_w == "SAIDA")
{
$movimentacao_print_m = "SA&Iacute;DA";
$movimentacao_print = "Sa&iacute;da";
}
elseif ($movimentacao_w == "TRANSFERENCIA_ENTRADA" or $movimentacao_w == "TRANSFERENCIA_SAIDA")
{
$movimentacao_print_m = "TRANSFER&Ecirc;NCIA";
$movimentacao_print = "Transfer&ecirc;ncia";
}
else
{
$movimentacao_print_m = $movimentacao_w;
$movimentacao_print = $movimentacao_w;
}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}

if (!empty($usuario_altera_quant_w))
{$dados_altera_quant_w = " &#13; Quantidade alterada por: " . $usuario_altera_quant_w . " " . date('d/m/Y', strtotime($data_altera_quant_w)) . " " . $hora_altera_quant_w;}
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
if ($saldo_a_pagar > 0)
{$saldo_a_pagar_print = "<font style='color:#F00'>R$ " . number_format($saldo_a_pagar,2,",",".") . "</font>";}
else
{$saldo_a_pagar_print = "R$ " . number_format($saldo_a_pagar,2,",",".");}
// ======================================================================================================


// ====== CALCULO QUANTIDADE A ENTREGAR =================================================================
$quant_entregar = $saldo_a_pagar / $preco_unitario_w;
//$quant_entregar_aux = $quant_entregar_aux + $quant_entregar;
$quant_entregar_print = number_format($quant_entregar,2,".","");
// ======================================================================================================


// ====== BLOQUEIO PARA EDITAR ==========================================================================
if ($saldo_a_pagar > 0 and $permissao[51] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== MONTA MENSAGEM =================================================================================
if (empty($linha_compra))
{$erro = 1;
$msg = "<div style='color:#FF0000'>REGISTRO N&Atilde;O ENCONTRADO</div>";}

if ($estado_registro_w == "EXCLUIDO")
{$erro = 2;
$msg = "<div style='color:#FF0000'>REGISTRO EXCLU&Iacute;DO</div>";}
// ======================================================================================================


// ======================================================================================================
include ("../../includes/head.php"); 
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


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1" style="width:1030px">
	<div class="ct_titulo_1" style="width:490px; margin-left:0px">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_titulo_2" style="width:490px; margin-right:30px">
    N&ordm; <?php echo $numero_compra; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2" style="width:1030px">
	<div class="ct_subtitulo_left" style="width:490px; margin-left:0px">
    <?php echo $msg; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:490px; margin-right:30px">
    <?php echo $data_compra_print; ?>
    </div>
</div>
<!-- ============================================================================================================= -->





<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:330px; margin:auto; border:1px solid transparent">


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Fornecedor
    </div>
    
    <div class="visualizar_caixa">
	<div class="visualizar_campo" style='width:582px'><div class="visualizar_hidden"><b><?php echo $pessoa_nome_w; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	<?php
    if ($pessoa_tipo_w == "PJ" or $pessoa_tipo_w == "pj")
    {echo "CNPJ";}
    else
    {echo "CPF";}
    ?>
    </div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $pessoa_cpf_cnpj; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Cidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><?php echo $cidade_print; ?></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Produto
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><b><?php echo $produto_print_w; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Tipo
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $tipo_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Quantidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $quantidade_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Pre&ccedil;o Unit&aacute;rio
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $preco_unitario_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Valor Total
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><b><?php echo $total_geral_print; ?></b></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Forma de Entrega
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $forma_entrega_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Umidade
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $umidade_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Broca
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $broca_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Impureza
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $impureza_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Filial
	</div>
    
    <div class="visualizar_caixa">
		<div class="visualizar_campo" style='width:170px'><div class="visualizar_hidden"><?php echo $filial_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	Observa&ccedil;&atilde;o
    </div>
    
    <div class="visualizar_caixa">
	<div class="visualizar_campo" style='width:582px'><div class="visualizar_hidden"><?php echo $observacao_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	</div>
    
    <div class="visualizar_caixa" style='width:170px'>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class="visualizar">
    <div class="visualizar_rotulo">
	</div>
    
    <div class="visualizar_caixa" style='width:170px'>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div class='visualizar'>
    <div class='visualizar_rotulo' style='color:#FF0000'>
	Desconto de Quantidade
	</div>
    
    <div class='visualizar_caixa' title='$desconto_em_valor_print'>
        <form action="<?php echo"$servidor/$diretorio_servidor"; ?>/compras/compras/desconto_quantidade.php" method="post" />
        <input type="hidden" name="botao" value="DESCONTO" />
        <input type="hidden" name="modulo_mae" value="<?php echo"$modulo_mae"; ?>">
        <input type="hidden" name="menu_mae" value="<?php echo"$menu_mae"; ?>">
        <input type="hidden" name="pagina_mae" value="<?php echo"$pagina_mae"; ?>">
        <input type="hidden" name="numero_compra" value="<?php echo"$numero_compra"; ?>" />
        <input type="hidden" name="quantidade_original" value="<?php echo"$quantidade_original_w"; ?>" />
        <input type="hidden" name="valor_total_original" value="<?php echo"$valor_total_original_w"; ?>" />

        <input type="text" name="desconto_quantidade" id="quant" class="form_input" maxlength="12" onkeypress="troca(this)" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:163px; text-align:left; padding-left:5px" value="<?php echo $quant_entregar_print; ?>" />
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class='visualizar'>
    <div class='visualizar_rotulo'>
	Desconto Aplicado
	</div>
    
    <div class='visualizar_caixa'>
		<div class='visualizar_campo' style='width:170px; color:#00F'><div class='visualizar_hidden'><?php echo $desconto_quant_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class='visualizar'>
    <div class='visualizar_rotulo'>
	Quantidade Original
	</div>
    
    <div class='visualizar_caixa'>
		<div class='visualizar_campo' style='width:170px; color:#00F'><div class='visualizar_hidden'><?php echo $quantidade_original_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class='visualizar'>
    <div class='visualizar_rotulo'>
	Valor Original
	</div>
    
    <div class='visualizar_caixa'>
		<div class='visualizar_campo' style='width:170px; color:#00F'><div class='visualizar_hidden'><?php echo $valor_total_original_print; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div class='visualizar'>
    <div class='visualizar_rotulo'>
	Motivo
	</div>
    <div class='visualizar_caixa' title='<?php echo " $motivo_ateracao_quant_w &#13; $dados_altera_quant_w"; ?>'>
		<div class='visualizar_campo' style='width:170px; color:#00F; font-size:10px'><div style='height:22px; margin-left:5px; overflow:hidden'><?php echo $motivo_ateracao_quant_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ======= BOTÕES ================================================================================================= -->
<div style="width:1270px; height:60px; margin:auto; border:1px solid transparent; text-align:center">
<div style="width:35px; height:55px; float:left"></div>
<?php
// ====== BOTAO SALVAR ========================================================================================================
if ($permite_editar == "SIM" and empty($erro) and $movimentacao_w == "COMPRA")
{
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar Desconto</button>
	</form>
</div>";
}

else
{
	echo "
	</form>
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Salvar Desconto</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO ESTORNAR ========================================================================================================
if (!empty($data_altera_quant_w) and empty($erro) and $movimentacao_w == "COMPRA" and $permissao[51] == "S" and $estado_registro_w == "ATIVO")
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/compras/desconto_quantidade.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo_mae'>
	<input type='hidden' name='menu_mae' value='$menu_mae'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='ESTORNAR'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='quantidade_original' value='$quantidade_original_w' />
	<input type='hidden' name='valor_total_original' value='$valor_total_original_w' />

	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Estornar Desconto</button>
	</form>
	</div>";
}

else
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Estornar Desconto</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post' />
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================



// ====== BOTAO PAGAMENTO ======================================================================================================
if ($permite_baixar == "SIM" and empty($erro) and $movimentacao_w == "COMPRA")
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Pagamento</button>
	</div>";

/*
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/forma_pagamento/forma_pagamento.php' method='post' />
	<input type='hidden' name='modulo_mae' value='$modulo_mae'>
	<input type='hidden' name='menu_mae' value='$menu_mae'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='BAIXAR'>
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br'>
	<input type='hidden' name='data_final_busca' value='$data_final_br'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_pesquisa'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_produto_busca'>
	<input type='hidden' name='numero_compra_busca' value='$numero_compra_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca'>
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
	<input type='hidden' name='status_pgto_busca' value='$status_pgto_busca'>
	<input type='hidden' name='ordenar_busca' value='$ordenar_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Pagamento</button>
	</form>
	</div>";
*/
}

else
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Pagamento</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
if (empty($erro) and $movimentacao_w == "COMPRA")
{	
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/compras/compra_impressao_1.php' method='post' target='_blank' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir 1 Via</button>
	</form>
</div>";
}

else
{
echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir 1 Via</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
if (empty($erro) and $movimentacao_w == "COMPRA")
{	
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/compras/compras/compra_impressao_2.php' method='post' target='_blank' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir 2 Vias</button>
	</form>
</div>";
}

else
{
echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Imprimir 2 Vias</button>
	</div>";
}
// =============================================================================================================================


?>
</div>
<!-- ================================================================================================================ -->



<!-- ======================================================================================================================= -->
<div class="espacamento" style="height:40px"></div>
<!-- ======================================================================================================================= -->






<!-- ====== INICIO DO RELATORIO DE PAGAMENTOS =============================================================================== -->
<?php
if ($linha_pgto == 0)
{echo "
<div style='height:50px'>
<div class='espacamento' style='height:10px'></div>";}

else
{echo "
<div class='ct_topo_1' style='width:1160px'>
	<div class='ct_titulo_1' style='width:490px; margin-left:0px'>
    Pagamento
    </div>

	<div class='ct_subtitulo_right' style='width:490px; margin-right:0px'>
    Total Pago: $total_pago_print
    </div>
</div>

<div class='ct_topo_2' style='width:1160px'>
	<div class='ct_subtitulo_left' style='width:490px; margin-left:0px'>
    </div>

	<div class='ct_subtitulo_right' style='width:490px; margin-right:0px'>
	Saldo a Pagar: $saldo_a_pagar_print
    </div>
</div>



<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='90px'>Data</td>
<td width='320px'>Favorecido</td>
<td width='140px'>Forma de Pagamento</td>
<td width='150px'>Banco</td>
<td width='90px'>Ag&ecirc;ncia</td>
<td width='120px'>N&uacute;mero</td>
<td width='100px'>Tipo de Conta</td>
<td width='120px'>Valor</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


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
$valor_print = "<b>" . number_format($valor_z,2,",",".") . "</b>";


if($situacao_pagamento_z == "PAGO")
{$situacao_pagamento_print = "BAIXADO";}
elseif($situacao_pagamento_z == "EM_ABERTO")
{$situacao_pagamento_print = "EM ABERTO";}
else
{$situacao_pagamento_print = "";}


if($tipo_conta_z == "corrente")
{$tipo_conta_aux = "C/C";}
elseif($tipo_conta_z == "poupanca")
{$tipo_conta_aux = "Poupan&ccedil;a";}
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
{$forma_pagamento_print = "TRANSFER&Ecirc;NCIA";
$nome_banco_print = $nome_banco_z;
$agencia_print = $agencia_z;
$num_conta_print = $num_conta_z;
$tipo_conta_print = $tipo_conta_aux;}
elseif($forma_pagamento_z == "CHEQUE")
{$forma_pagamento_print = "CHEQUE";
$nome_banco_print = $banco_cheque_aux;
$agencia_print = "";
$num_conta_print = $numero_cheque_z;
$tipo_conta_print = "";}
else
{$forma_pagamento_print = $forma_pagamento_z;
$nome_banco_print = "";
$agencia_print = "";
$num_conta_print = "";
$tipo_conta_print = "";}


if (!empty($usuario_cadastro_z))
{$dados_cadastro_z = " &#13; Cadastrado por: " . $usuario_cadastro_z . " " . date('d/m/Y', strtotime($data_cadastro_z)) . " " . $hora_cadastro_z;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
if ($situacao_pagamento_w == "EM_ABERTO")
{echo "<tr class='tabela_1' title=' ID: $id_z &#13; C&oacute;digo do Favorecido: $codigo_favorecido_z &#13; CPF/CNPJ: $cpf_cnpj_z &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_z &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_z &#13; Observa&ccedil;&atilde;o: $observacao_z &#13; Filial: $filial_z $dados_cadastro_z'>";}

else
{echo "<tr class='tabela_2' title=' ID: $id_z &#13; C&oacute;digo do Favorecido: $codigo_favorecido_z &#13; CPF/CNPJ: $cpf_cnpj_z &#13; Status do Pagamento: $situacao_pagamento_print &#13; Origem do Pagamento: $origem_pgto_print &#13; Produto: $produto_z &#13; C&oacute;digo do Fornecedor: $codigo_fornecedor_z &#13; Observa&ccedil;&atilde;o: $observacao_z &#13; Filial: $filial_z $dados_cadastro_z'>";}


// =================================================================================================================
echo "
<td width='90px' align='center'>$data_pgto_print</td>
<td width='320px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$favorecido_print</div></td>
<td width='140px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$forma_pagamento_print</div></td>
<td width='150px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$nome_banco_print</div></td>
<td width='90px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$agencia_print</div></td>
<td width='120px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$num_conta_print</div></td>
<td width='100px' align='center'>$tipo_conta_print</td>
<td width='120px' align='right'><div style='height:14px; margin-right:15px'>$valor_print</div></td>";
// =================================================================================================================

echo "</tr>";

}

echo "</table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_pgto == 0 and $movimentacao_w == "COMPRA")
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum pagamento encontrado.</i></div>
</div>";}
// =================================================================================================================
?>
<!-- ================== FIM DO RELATORIO PAGAMENTOS ================= -->



<!-- ======================================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ======================================================================================================================= -->







</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<div style="width:auto; height:20px; border:0px solid #000; margin-top:20px; text-align:center; font-size:12px">
<?php
$complemento_rodape = "$dados_cadastro_w";

if (!empty($usuario_altera_quant_w))
{$complemento_rodape = $complemento_rodape . " | $dados_altera_quant_w";}

if (!empty($usuario_alteracao_w))
{$complemento_rodape = $complemento_rodape . " | $dados_alteracao_w";}

if (!empty($usuario_exclusao_w))
{$complemento_rodape = $complemento_rodape . " | $dados_exclusao_w";}

echo $complemento_rodape
?>
</div>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>