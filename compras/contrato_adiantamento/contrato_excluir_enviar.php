<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "contrato_excluir_evniar";
$titulo = "Contrato de Adiantamento";
$modulo = "compras";
$menu = "contratos";
// ========================================================================================================


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


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;
$modulo_mae = $_POST["modulo_mae"];
$menu_mae = $_POST["menu_mae"];
$pagina_mae = $_POST["pagina_mae"];
$pagina_filha = $_POST["pagina_filha"];

$numero_contrato = $_POST["numero_contrato"];
$motivo_exclusao = $_POST["motivo_exclusao"];
$motivo_obrigatorio = "NAO";

$data_inicial_busca = $_POST["data_inicial_busca"];
$data_final_busca = $_POST["data_final_busca"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$filial_busca = $_POST["filial_busca"];
$status_busca = $_POST["status_busca"];

$usuario_exclusao = $nome_usuario_print;
$data_exclusao = date('Y-m-d', time());
$hora_exclusao = date('G:i:s', time());
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_contrato = mysqli_query($conexao, "SELECT * FROM contrato_adiantamento WHERE numero_contrato='$numero_contrato'");
$linha_contrato = mysqli_num_rows ($busca_contrato);
$aux_contrato = mysqli_fetch_row($busca_contrato);
// ================================================================================================================


// ====== DADOS DO CADASTRO ============================================================================
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
$estado_registro_w = $aux_contrato[12];
$pendencia_assinatura_w = $aux_contrato[13];


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
$motivo_exclusao_w = $aux_contrato[23];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


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
{$cpf_cnpj_print = "CPF: $cpf_pessoa";}
else
{$cpf_cnpj_print = "CNPJ: $cnpj_pessoa";}
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


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ============================================================================================================


// ====== BLOQUEIO PARA EXCLUIR ===========================================================================
if ($permissao[144] == "S" and $estado_registro_w == "ATIVO")
{$permite_excluir = "SIM";}
else
{$permite_excluir = "NAO";}
// ========================================================================================================


// ====== EXCLUI CONTRATO E MONTA MENSAGEM ===================================================================

if ($botao == "EXCLUIR")
{
	if ($motivo_obrigatorio == "SIM" and ($motivo_exclusao == "" or $motivo_exclusao == " "))
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Motivo da exclus&atilde;o &eacute; obrigat&oacute;rio.</div>";
	$msg_titulo = "<div style='color:#009900'>Exclus&atilde;o de Contrato</div>";
	}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Contrato Exclu&iacute;do com Sucesso!</div>";
	

$excluir_contrato = mysqli_query ($conexao, "UPDATE contrato_adiantamento SET estado_registro='EXCLUIDO', usuario_exclusao='$usuario_exclusao', data_exclusao='$data_exclusao', hora_exclusao='$hora_exclusao', motivo_exclusao='$motivo_exclusao' WHERE numero_contrato='$numero_contrato'");

	}
}

// ======================================================================================================


// ========================================================================================================
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
<?php include ("../../includes/submenu_compras_contratos.php"); ?>
</div>





<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$msg_titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
    <?php echo"N&ordm; $numero_contrato"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_right">
    <?php //echo"$data_hoje_br"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:390px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
	Fornecedor:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:495px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:485px; height:16px; color:#003466; text-align:left; overflow:hidden'><b>$nome_pessoa</b></div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj")
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cnpj_pessoa</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$cpf_pessoa</div></div>";
    }
    ?>
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
	<?php echo"$telefone_pessoa" ?></div></div>
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
	$cidade_uf = $cidade_pessoa . "/" . $estado_pessoa;
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
<div style="width:338px; height:60px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:336px; height:17px; border:1px solid transparent; float:left">
    Produto:
    </div>

    <div style="width:336px; height:34px; float:left; border:1px solid transparent">
        <div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:0px; background-color:#EEE; float:left">
            <div style="width:60px; height:25px; margin-top:4px; margin-left:0px; float:left; font-size:12px; color:#003466">
                <?php echo"$link_imagem_produto" ?>
            </div>
        
            <div style="width:177px; height:20px; margin-top:9px; margin-left:0px; float:left; font-size:12px; color:#003466; overflow:hidden">
                <?php echo"<b>$produto_print</b>" ?>
            </div>
        </div>
    </div>
</div>
<!-- ================================================================================================================ -->



<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; float:left"></div>
<!-- ================================================================================================================ -->


<!-- ======= VALOR ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Valor:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"<b>$valor_print</b>"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA CONTRATO ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Data de Emiss&atilde;o:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$data_contrato_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  DATA VENCIMENTO ======================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Data de Vencimento:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$data_vencimento_print"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  SAFRA ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Safra:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$safra_w"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  FILIAL ================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Filial:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$filial_w"; ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ================================================================================================================ -->
<div style="width:1000px; height:5px; border:1px solid transparent; margin-top:0px; float:left"></div>
<!-- ================================================================================================================ -->


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    Observa&ccedil;&atilde;o:
    </div>
    
    <div style="width:500px; height:25px; float:left; border:1px solid transparent">
    <div style="width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:left; background-color:#EEE">
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; overflow:hidden"><?php echo"$observacao_w" ?></div></div>
    </div>
</div>
<!-- ============================================================================================================== -->



</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->




<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->


<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
echo"
<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/$modulo_mae/$menu_mae/$pagina_mae.php' method='post'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='numero_contrato' value='$numero_contrato'>
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='filial_busca' value='$filial_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================


?>
</div>




<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<div id="centro" style="width:1030px; height:35px; margin:auto">
<!-- ======= DADOS CADASTRO ========================================================================================= -->
<?php
if ($dados_cadastro_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden'><i>$dados_cadastro_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= DADOS EDIÇÃO =========================================================================================== -->
<?php
if ($dados_alteracao_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden'><i>$dados_alteracao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= DADOS EXCLUSÃO ========================================================================================= -->
<?php
if ($usuario_exclusao_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden' title='Motivo da Exclus&atilde;o: $motivo_exclusao_w'>
		<i>$dados_exclusao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->
</div>


<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->










</div>
<!-- ====== FIM DIV CT ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>