<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "romaneio_visualizar";
$titulo = "Romaneio de Entrada";
$modulo = "estoque";
$menu = "entrada";
// ================================================================================================================


// ====== RECEBE POST =============================================================================================
$botao = $_POST["botao"];
$botao_desconto = $_POST["botao_desconto"];
$data_hoje = date('Y-m-d', time());
$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];

$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$fornecedor_busca = $_POST["fornecedor_busca"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$numero_romaneio_busca = $_POST["numero_romaneio_busca"];
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"];
$forma_pesagem_busca = $_POST["forma_pesagem_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = $_POST["status_busca"];
$seleciona_pessoa = $_POST["seleciona_pessoa"];

$numero_romaneio = $_POST["numero_romaneio"];
$mysql_movimentacao = "estoque.movimentacao='ENTRADA'";

$usuario_alteracao = $username;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
// ================================================================================================================



// ====== BUSCA CADASTROS =========================================================================================
include ("../../includes/conecta_bd.php");
$busca_romaneio = mysqli_query ($conexao, 

"SELECT estoque.codigo, estoque.numero_romaneio, estoque.fornecedor, estoque.data, estoque.produto, estoque.peso_inicial, estoque.peso_final, estoque.desconto_sacaria, estoque.desconto, estoque.quantidade, estoque.unidade, estoque.tipo_sacaria, estoque.movimentacao, estoque.placa_veiculo, estoque.motorista, estoque.observacao, estoque.usuario_cadastro, estoque.hora_cadastro, estoque.data_cadastro, estoque.usuario_alteracao, estoque.hora_alteracao, estoque.data_alteracao, estoque.filial, estoque.estado_registro, estoque.quantidade_prevista, estoque.quantidade_sacaria, estoque.numero_compra, estoque.motorista_cpf, estoque.num_romaneio_manual, estoque.filial_origem, estoque.quant_volume_sacas, estoque.cod_produto, estoque.usuario_exclusao, estoque.hora_exclusao, estoque.data_exclusao, cadastro_pessoa.nome, cadastro_pessoa.tipo, cadastro_pessoa.cpf, cadastro_pessoa.cnpj, cadastro_pessoa.cidade, cadastro_pessoa.estado, cadastro_pessoa.telefone_1, select_tipo_sacaria.descricao, select_tipo_sacaria.peso, estoque.cod_tipo, estoque.quant_quebra_previsto 

FROM estoque, cadastro_pessoa, select_tipo_sacaria 

WHERE (estoque.numero_romaneio='$numero_romaneio' AND $mysql_movimentacao) AND estoque.fornecedor=cadastro_pessoa.codigo AND estoque.tipo_sacaria=select_tipo_sacaria.codigo 

ORDER BY estoque.codigo");

include ("../../includes/desconecta_bd.php");
$linha_romaneio = mysqli_num_rows ($busca_romaneio);
// ========================================================================================================

// ====== BUSCA NOTAS FISCAIS ======================================================================
include ("../../includes/conecta_bd.php");
$busca_nota_fiscal = mysqli_query ($conexao, 
"SELECT nota_fiscal_entrada.codigo, nota_fiscal_entrada.codigo_romaneio, nota_fiscal_entrada.codigo_fornecedor, nota_fiscal_entrada.numero_nf, nota_fiscal_entrada.data_emissao, nota_fiscal_entrada.valor_unitario, nota_fiscal_entrada.unidade, nota_fiscal_entrada.quantidade, nota_fiscal_entrada.valor_total, nota_fiscal_entrada.observacao, nota_fiscal_entrada.usuario_cadastro, nota_fiscal_entrada.hora_cadastro, nota_fiscal_entrada.data_cadastro, nota_fiscal_entrada.usuario_alteracao, nota_fiscal_entrada.hora_alteracao, nota_fiscal_entrada.data_alteracao, nota_fiscal_entrada.estado_registro, nota_fiscal_entrada.filial, nota_fiscal_entrada.natureza_operacao, cadastro_pessoa.nome, cadastro_pessoa.tipo, cadastro_pessoa.cpf, cadastro_pessoa.cnpj 
FROM nota_fiscal_entrada, cadastro_pessoa 
WHERE nota_fiscal_entrada.estado_registro='ATIVO' AND nota_fiscal_entrada.codigo_romaneio='$numero_romaneio' AND nota_fiscal_entrada.codigo_fornecedor=cadastro_pessoa.codigo 
ORDER BY nota_fiscal_entrada.data_emissao");
include ("../../includes/desconecta_bd.php");

$linha_nota_fiscal = mysqli_num_rows ($busca_nota_fiscal);

include ("../../includes/conecta_bd.php");
$soma_nota_fiscal = mysqli_fetch_row(mysqli_query ($conexao, 
"SELECT SUM(valor_total), SUM(quantidade) 
FROM nota_fiscal_entrada 
WHERE estado_registro='ATIVO' AND codigo_romaneio='$numero_romaneio'"));
include ("../../includes/desconecta_bd.php");

$soma_nota_fiscal_print = number_format($soma_nota_fiscal[0],2,",",".");
$soma_quantidade_nf_print = number_format($soma_nota_fiscal[1],0,",",".");

/*
$soma_quantidade_nf = mysqli_fetch_row(mysqli_query ($conexao, "SELECT SUM(quantidade) FROM nota_fiscal_entrada WHERE estado_registro='ATIVO' AND codigo_romaneio='$numero_romaneio'"));
*/
// ======================================================================================================

// ====== BUSCA CONFIGURAÇÕES DO SISTEMA =========================================================================
include ("../../includes/conecta_bd.php");
$busca_config = mysqli_query ($conexao, "SELECT limite_dias_edicao_romaneio FROM configuracoes");
include ("../../includes/desconecta_bd.php");

$config = mysqli_fetch_row($busca_config);
// ===============================================================================================================

// ====== BUSCA PERMISSÕES DE USUÁRIOS ===========================================================================
include ("../../includes/conecta_bd.php");
$busca_permissao = mysqli_query ($conexao, "SELECT romaneio_editar, romaneio_editar_antigo, romaneio_nf_entrada FROM usuarios_permissoes WHERE username='$username'");
include ("../../includes/desconecta_bd.php");
$permissao = mysqli_fetch_row($busca_permissao);
// ===============================================================================================================



// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_romaneio ; $x++)
{
$aux_romaneio = mysqli_fetch_row($busca_romaneio);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_romaneio[0];
$numero_romaneio_w = $aux_romaneio[1];
$fornecedor_w = $aux_romaneio[2];
$data_w = $aux_romaneio[3];
$produto_w = $aux_romaneio[4];
$peso_inicial_w = $aux_romaneio[5];
$peso_final_w = $aux_romaneio[6];
$desconto_sacaria_w = $aux_romaneio[7];
$desconto_w = $aux_romaneio[8];
$quantidade_w = $aux_romaneio[9];
$unidade_w = $aux_romaneio[10];
$tipo_sacaria_w = $aux_romaneio[11];
$movimentacao_w = $aux_romaneio[12];
$placa_veiculo_w = $aux_romaneio[13];
$motorista_w = $aux_romaneio[14];
$observacao_w = $aux_romaneio[15];
$usuario_cadastro_w = $aux_romaneio[16];
$hora_cadastro_w = $aux_romaneio[17];
$data_cadastro_w = $aux_romaneio[18];
$usuario_alteracao_w = $aux_romaneio[19];
$hora_alteracao_w = $aux_romaneio[20];
$data_alteracao_w = $aux_romaneio[21];
$filial_w = $aux_romaneio[22];
$estado_registro_w = $aux_romaneio[23];
$quantidade_prevista_w = $aux_romaneio[24];
$quantidade_sacaria_w = $aux_romaneio[25];
$numero_compra_w = $aux_romaneio[26];
$motorista_cpf_w = $aux_romaneio[27];
$num_romaneio_manual_w = $aux_romaneio[28];
$filial_origem_w = $aux_romaneio[29];
$quant_volume = $aux_romaneio[30];
$cod_produto_w = $aux_romaneio[31];
$usuario_exclusao_w = $aux_romaneio[32];
$hora_exclusao_w = $aux_romaneio[33];
$data_exclusao_w = $aux_romaneio[34];
$pessoa_nome_w = $aux_romaneio[35];
$pessoa_tipo_w = $aux_romaneio[36];
$pessoa_cpf_w = $aux_romaneio[37];
$pessoa_cnpj_w = $aux_romaneio[38];
$pessoa_cidade_w = $aux_romaneio[39];
$pessoa_estado_w = $aux_romaneio[40];
$pessoa_telefone_w = $aux_romaneio[41];
$nome_sacaria_w = $aux_romaneio[42];
$peso_sacaria_w = $aux_romaneio[43];
$cod_tipo_w = $aux_romaneio[44];
$quant_quebra_previsto_w = $aux_romaneio[45];

if ($pessoa_tipo_w == "PF" or $pessoa_tipo_w == "pf")
{$pessoa_cpf_cnpj = $pessoa_cpf_w;}
else
{$pessoa_cpf_cnpj = $pessoa_cnpj_w;}


$peso_bruto = ($peso_inicial_w - $peso_final_w);

$data_print = date('d/m/Y', strtotime($data_w));
$peso_inicial_print = number_format($peso_inicial_w,0,",",".") . " " . $unidade_w;
$peso_final_print = number_format($peso_final_w,0,",",".") . " " . $unidade_w;
$peso_bruto_print = number_format($peso_bruto,0,",",".") . " " . $unidade_w;
$desconto_sacaria_print = number_format($desconto_sacaria_w,0,",",".") . " " . $unidade_w;
$desconto_print = number_format($desconto_w,0,",",".") . " " . $unidade_w;
$quantidade_print = "<b>" . number_format($quantidade_w,0,",",".") . "</b> " . $unidade_w;
$quantidade_sacaria_print = number_format($quantidade_sacaria_w,0,",",".");
$quant_quebra_prev_print = number_format($quant_quebra_previsto_w,0,",",".") . " " . $unidade_w;


if (empty($numero_compra_w))
{$num_registro_entrada = "(Romaneio n&atilde;o vinculado a ficha)";}
else
{$num_registro_entrada = $numero_compra_w;}


if (!empty($usuario_cadastro_w))
{$dados_cadastro_w = " &#13; Cadastrado por: " . $usuario_cadastro_w . " " . date('d/m/Y', strtotime($data_cadastro_w)) . " " . $hora_cadastro_w;}

if (!empty($usuario_alteracao_w))
{$dados_alteracao_w = " &#13; Editado por: " . $usuario_alteracao_w . " " . date('d/m/Y', strtotime($data_alteracao_w)) . " " . $hora_alteracao_w;}

if (!empty($usuario_exclusao_w))
{$dados_exclusao_w = " &#13; Exclu&iacute;do por: " . $usuario_exclusao_w . " " . date('d/m/Y', strtotime($data_exclusao_w)) . " " . $hora_exclusao_w;}

}
// ================================================================================================================


// ====== CADASTRO PRODUTOS =======================================================================================
include ("../../includes/cadastro_produto.php");

for ($p=0 ; $p<=count($cadastro_produto) ; $p++)
{
	if ($cadastro_produto[$p]["codigo"] == $cod_produto_w)
	{
	$cod_produto_p = $cadastro_produto[$p]["codigo"];
	$produto_print_p = $cadastro_produto[$p]["descricao"];
	$nome_produto_p = $cadastro_produto[$p]["produto_print"];
	$unidade_print_p = $cadastro_produto[$p]["unidade_print"];
	$nome_imagem_produto_p = $cadastro_produto[$p]["nome_imagem"];
	$quant_kg_saca_p = $cadastro_produto[$p]["quant_kg_saca"];
	
	if (empty($nome_imagem_produto_p))
	{$link_img_produto = "";}
	else
	{$link_img_produto = "<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto_p.png' style='width:60px'>";}
	}
}
// ================================================================================================================


// ====== CADASTRO TIPO PRODUTOS ==================================================================================
include ("../../includes/select_tipo_produto.php");

for ($t=0 ; $t<=count($select_tipo_produto) ; $t++)
{
	if ($select_tipo_produto[$t]["codigo"] == $cod_tipo_w)
	{$tipo_print_t = $select_tipo_produto[$t]["descricao"];}
}
// ================================================================================================================


// ====== CALCULO QUANTIDADE REAL ==================================================================================
$quantidade_real = ($quantidade_w / $quant_kg_saca_p);
$quantidade_real_print = "<b>" . number_format($quantidade_real,2,",",".") . "</b> SC";
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ============================================================================
$diferenca_dias = strtotime($data_hoje) - strtotime($data_w);
$conta_dias = floor($diferenca_dias / (60 * 60 * 24));
if ($conta_dias < $config[0] or $permissao[1] == "S")
{$pode_editar = "S";}
else
{$pode_editar = "N";}

if ($permissao[0] == "S" and empty($numero_compra_w) and $pode_editar == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ====== BLOQUEIO PARA NOTA FISCAL =======================================================================
if ($permissao[2] == "S" and $estado_registro_w == "ATIVO")
{$permite_nf = "SIM";}
else
{$permite_nf = "NAO";}
// ========================================================================================================


// ================================================================================================================
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
<?php include ("../../includes/menu_estoque.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_estoque_entrada.php"); ?>
</div>

<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1" style="width:1030px">
	<div class="ct_titulo_1" style="width:490px; margin-left:0px">
    Romaneio de Entrada
    </div>

	<div class="ct_titulo_2" style="width:490px; margin-right:20px">
    <?php echo "N&ordm; $numero_romaneio"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2" style="width:1030px">
	<div class="ct_subtitulo_left" style="width:490px; margin-left:0px">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:490px; margin-right:20px">
    <?php echo"$data_print"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:420px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($pessoa_tipo_w == "PJ" or $pessoa_tipo_w == "pj")
    {echo "Raz&atilde;o Social:";}
    else
    {echo "Nome:";}
    ?>
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <div style="width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden">
    <b><?php echo $pessoa_nome_w; ?></b></div></div></div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($pessoa_tipo_w == "PJ" or $pessoa_tipo_w == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
	<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>
    <?php echo $pessoa_cpf_cnpj; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden">
	<?php echo $pessoa_telefone_w; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  CIDADE / UF ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Cidade:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
	$cidade_uf = $pessoa_cidade_w . "/" . $pessoa_estado_w;
	$conta_caracter = strlen($cidade_uf);
	if ($conta_caracter <= 16)
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf</div></div>";}
	else
	{echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:9px; text-align:center; background-color:#EEE'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; color:#003466; text-align:left; overflow:hidden'>$cidade_uf</div></div>";}
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= PRODUTO ============================================================================================= -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:295px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:295px; height:34px; float:left; border:1px solid transparent">
        <div style="width:290px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo "$link_img_produto"; ?>
            </div>
        
            <div style="width:230px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#003466; text-align:center">
                <?php echo "<b>$produto_print_p</b>"; ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:300px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Peso Inicial:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$peso_inicial_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Peso Final:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$peso_final_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Peso Bruto:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$peso_bruto_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Desconto Sacaria:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$desconto_sacaria_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Outros Descontos:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$desconto_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->

<!-- ================================================================================================================ -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Peso L&iacute;quido:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$quantidade_print" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1025px; height:30px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
    <div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Real em Sacas:</div>
    <div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$quantidade_real_print" ?></div>
</div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
    <div style="width:180px; margin-top:3px; margin-left:5px; float:left">Tipo do Produto:</div>
    <div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$tipo_print_t" ?></div>
</div>

<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quant. Volume Sacas:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$quant_volume" ?></div></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Romaneio Manual:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$num_romaneio_manual_w" ?></div></div>


<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Tipo Sacaria:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$nome_sacaria_w" ?></div></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Filial Origem:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$filial_origem_w" ?></div></div>


<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Quantidade Sacaria:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$quantidade_sacaria_print" ?></div></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista_w" ?></div></div>


<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Desc. Previsto (Qualidade):</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$quant_quebra_prev_print" ?></div></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px; background-color:#EEE">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">CPF Motorista:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$motorista_cpf_w" ?></div></div>


<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">N&ordm; Registro de Ficha:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$num_registro_entrada" ?></div></div>

<div style="width:470px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:35px">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Placa do Ve&iacute;culo:</div><div style="margin-top:3px; margin-left:5px; float:left"><?php echo"$placa_veiculo_w" ?></div></div>


<div style="width:1025px; height:3px; border:0px solid #000; float:left; font-size:12px"></div>

<div style="width:975px; height:22px; border:0px solid #000; float:left; font-size:12px; margin-left:25px; background-color:#EEE">
<div style="width:180px; margin-top:3px; margin-left:5px; float:left">Observa&ccedil;&atilde;o:</div>
<div style="margin-top:3px; margin-left:5px; width:650px; height:14px; float:left; border:0px solid #000; overflow:hidden"><?php echo"$observacao_w" ?></div></div>
<!-- ================================================================================================================ -->





</div>
<!-- ======================================================================================================================= -->


<!-- ======================================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ======================================================================================================================= -->





<!-- ======================================================================================================================= -->
<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">
<?php
if ($linha_romaneio == 0)
{
echo"
<div style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================
}


else
{
echo"
<div style='float:left; height:55px; width:235px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================


// ====== BOTAO EDITAR ========================================================================================================
if ($permite_editar == "SIM")
{	
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/editar_3_formulario.php' method='post'>
	<input type='hidden' name='modulo_mae' value='$modulo_mae'>
	<input type='hidden' name='menu_mae' value='$menu_mae'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='EDITAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
	</form>
</div>";
}

else
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Editar</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO NOTA FISCAL ====================================================================================================
if ($permite_nf == "SIM")
{	
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/nota_fiscal_entrada/nota_fiscal.php' method='post'>
	<input type='hidden' name='modulo_mae' value='$modulo_mae'>
	<input type='hidden' name='menu_mae' value='$menu_mae'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='NOTA_FISCAL'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='nome_fornecedor' value='$nome_fornecedor'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Nota Fiscal</button>
	</form>
</div>";
}

else
{
	echo "
	<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Nota Fiscal</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO IMPRIMIR =======================================================================================================
echo "
<div style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/estoque/entrada/romaneio_impressao.php' method='post' target='_blank'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir</button>
	</form>
</div>";
// =============================================================================================================================



}

?>
</div>
<!-- ======================================================================================================================= -->


<!-- ======================================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ======================================================================================================================= -->






<!-- ====== INICIO DO RELATORIO NOTAS FISCAIS =============================================================================== -->
<?php
if ($linha_nota_fiscal == 0)
{echo "
<div style='height:50px'>
<div class='espacamento' style='height:10px'></div>";}

else
{echo "
<div class='ct_topo_1' style='width:1030px'>
	<div class='ct_titulo_1' style='width:490px; margin-left:0px'>
    Notas Fiscais
    </div>

	<div class='ct_subtitulo_right' style='width:490px; margin-right:0px'>
    Quantidade: $soma_quantidade_nf_print $unidade_print_p
    </div>
</div>

<div class='ct_topo_2' style='width:1030px'>
	<div class='ct_subtitulo_left' style='width:490px; margin-left:0px'>
    </div>

	<div class='ct_subtitulo_right' style='width:490px; margin-right:0px'>
	Total: R$ $soma_nota_fiscal_print
    </div>
</div>



<div class='ct_relatorio'>

<table class='tabela_cabecalho'>
<tr>
<td width='90px'>Data Emiss&atilde;o</td>
<td width='300px'>Produtor</td>
<td width='100px'>N&ordm; Nota Fiscal</td>
<td width='150px'>Natureza da Opera&ccedil;&atilde;o</td>
<td width='120px'>Quantidade</td>
<td width='100px'>Valor Unit&aacute;rio</td>
<td width='140px'>Valor Total</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($z=1 ; $z<=$linha_nota_fiscal ; $z++)
{
$aux_nota_fiscal = mysqli_fetch_row($busca_nota_fiscal);

// ====== DADOS DO CADASTRO ============================================================================
$id_z = $aux_nota_fiscal[0];
$numero_romaneio_z = $aux_nota_fiscal[1];
$fornecedor_z = $aux_nota_fiscal[2];
$numero_nf_z = $aux_nota_fiscal[3];
$data_emissao_z = $aux_nota_fiscal[4];
$valor_unitario_z = $aux_nota_fiscal[5];
$unidade_z = $aux_nota_fiscal[6];
$quantidade_z = $aux_nota_fiscal[7];
$valor_total_z = $aux_nota_fiscal[8];
$observacao_z = $aux_nota_fiscal[9];
$usuario_cadastro_z = $aux_nota_fiscal[10];
$hora_cadastro_z = $aux_nota_fiscal[11];
$data_cadastro_z = $aux_nota_fiscal[12];
$usuario_alteracao_z = $aux_nota_fiscal[13];
$hora_alteracao_z = $aux_nota_fiscal[14];
$data_alteracao_z = $aux_nota_fiscal[15];
$estado_registro_z = $aux_nota_fiscal[16];
$filial_z = $aux_nota_fiscal[17];
$natureza_operacao_z = $aux_nota_fiscal[18];
$produtor_nome_z = $aux_nota_fiscal[19];
$produtor_tipo_z = $aux_nota_fiscal[20];
$produtor_cpf_z = $aux_nota_fiscal[21];
$produtor_cnpj_z = $aux_nota_fiscal[22];

if ($produtor_tipo_z == "PF" or $produtor_tipo_z == "pf")
{$produtor_cpf_cnpj = "CPF: " . $produtor_cpf_z;}
else
{$produtor_cpf_cnpj = "CNPJ: " . $produtor_cnpj_z;}

$data_emissao_print = date('d/m/Y', strtotime($data_emissao_z));
$quantidade_print = number_format($quantidade_z,0,",",".") . " " . $unidade_print_p;
$valor_unitario_print = "R$ " . number_format($valor_unitario_z,2,",",".");
$valor_total_print = "R$ " . number_format($valor_total_z,2,",",".");

if (!empty($usuario_cadastro_z))
{$dados_cadastro_z = " &#13; Cadastrado por: " . $usuario_cadastro_z . " " . date('d/m/Y', strtotime($data_cadastro_z)) . " " . $hora_cadastro_z;}

if (!empty($usuario_alteracao_z))
{$dados_alteracao_z = " &#13; Editado por: " . $usuario_alteracao_z . " " . date('d/m/Y', strtotime($data_alteracao_z)) . " " . $hora_alteracao_z;}
// ======================================================================================================


// ====== RELATORIO =======================================================================================
echo "<tr class='tabela_1' title=' ID: $id_z &#13; $produtor_cpf_cnpj &#13; Observa&ccedil;&atilde;o: $observacao_z $dados_cadastro_z $dados_alteracao_z'>";

echo "
<td width='90px' align='center'>$data_emissao_print</td>
<td width='300px' align='left'><div style='height:14px; margin-left:10px; overflow:hidden'>$produtor_nome_z</div></td>
<td width='100px' align='center'>$numero_nf_z</td>
<td width='150px' align='center'>$natureza_operacao_z</td>
<td width='120px' align='right'><div style='height:14px; margin-right:10px; overflow:hidden'>$quantidade_print</div></td>
<td width='100px' align='right'><div style='height:14px; margin-right:10px'>$valor_unitario_print</div></td>
<td width='140px' align='right'><div style='height:14px; margin-right:15px'>$valor_total_print</div></td>";
// =================================================================================================================

}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_nota_fiscal == 0 )
{echo "
<div class='espacamento' style='height:30px'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#999; font-size:14px; margin:auto; text-align:center'>
<i>Nenhuma nota fiscal encontrada.</i></div>
</div>";}
// =================================================================================================================
?>
<!-- ================== FIM DO RELATORIO NOTAS FISCAIS ================= -->



<!-- ============================================================================================================= -->
<div class="espacamento" style="height:30px"></div>
<!-- ============================================================================================================= -->







</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<div style="width:auto; height:20px; border:0px solid #000; margin-top:20px; text-align:center; font-size:12px">
<?php
$complemento_rodape = "$dados_cadastro_w";

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