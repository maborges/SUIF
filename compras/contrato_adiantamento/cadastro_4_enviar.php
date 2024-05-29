<?php
include ("../../includes/config.php");
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
include_once("../../helpers.php");
$pagina = "cadastro_4_enviar";
$titulo = "Contrato de Adiantamento";
$modulo = "compras";
$menu = "contratos";
// ========================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$data_hoje = date('Y-m-d', time());
$data_hoje_br = date('d/m/Y', time());
$filial = $filial_usuario;

$numero_contrato = $_POST["numero_contrato"];
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$cod_seleciona_produto = $_POST["cod_seleciona_produto"];
$data_contrato_form = $_POST["data_contrato_form"];
$data_contrato_aux = Helpers::ConverteData($data_contrato_form);
$data_vencimento_form = $_POST["data_vencimento_form"];
$data_vencimento_aux = Helpers::ConverteData($data_vencimento_form);
$valor_print = $_POST["valor_form"];
$valor_form = Helpers::ConverteValor($_POST["valor_form"]);
$safra_form = $_POST["safra_form"];
$obs_form = $_POST["obs_form"];

$usuario_cadastro = $nome_usuario_print;
$data_cadastro = date('Y-m-d', time());
$hora_cadastro = date('G:i:s', time());
// ================================================================================================================


// ====== BLOQUEIO PARA NOVO CONTRATO ADTO ========================================================================
if ($permissao[141] == "S")
{$permite_novo = "SIM";}
else
{$permite_novo = "NAO";}
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_seleciona_produto'");
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
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$unidade_descricao = $aux_un_med[1];
$unidade_abreviacao = $aux_un_med[2];
$unidade_apelido = $aux_un_med[3];
// ==================================================================================================================


// ====== BUSCA PESSOA ==============================================================================================
$busca_pessoa = mysqli_query($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro!='EXCLUIDO' AND codigo='$fornecedor_pesquisa' ORDER BY nome");
$aux_pessoa = mysqli_fetch_row($busca_pessoa);

$nome_pessoa = $aux_pessoa[1];
$tipo_pessoa = $aux_pessoa[2];
$cpf_pessoa = $aux_pessoa[3];
$cnpj_pessoa = $aux_pessoa[4];
$cidade_pessoa = $aux_pessoa[10];
$estado_pessoa = $aux_pessoa[12];
$telefone_pessoa = $aux_pessoa[14];
$codigo_pessoa = $aux_pessoa[35];
// ===========================================================================================================


// ====== BUSCA NUMERO DE CONTRATO (DUPLICIDADE) =============================================================
$busca_num_contrato = mysqli_query ($conexao, "SELECT * FROM contrato_adiantamento WHERE estado_registro='ATIVO' and numero_contrato='$numero_contrato'");
$achou_contrato_duplicidade = mysqli_num_rows ($busca_num_contrato);
// =======================================================================================================


// ====== ENVIA CADASTRO P/ BD E MONTA MENSAGEM =========================================================
if ($botao == "NOVO_CONTRATO" and $permite_novo == "SIM")
{
	if ($fornecedor_pesquisa == "" or $fornecedor_pesquisa == "")
	{$erro = 1;
	$msg = "<div style='color:#FF0000'>Selecione um fornecedor.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	elseif ($cod_seleciona_produto == "" or $linhas_bp == 0)
	{$erro = 2;
	$msg = "<div style='color:#FF0000'>Selecione um produto.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	elseif (!is_numeric($valor_form) or $valor_form <= 0)
	{$erro = 3;
	$msg = "<div style='color:#FF0000'>Valor inv&aacute;lido.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	elseif ($data_contrato_form == "" or $data_contrato_aux < "2000-01-01")
	{$erro = 4;
	$msg = "<div style='color:#FF0000'>Data de Emiss&atilde;o inv&aacute;lida.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	elseif ($data_vencimento_form == "" or $data_vencimento_aux < "2000-01-01")
	{$erro = 5;
	$msg = "<div style='color:#FF0000'>Data de Vencimento inv&aacute;lida.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	elseif ($achou_contrato_duplicidade >= 1)
	{$erro = 6;
	$msg = "<div style='color:#FF0000'>Contrato em duplicidade. Verifique no relatório de Contratos.</div>";
	$msg_titulo = "<div style='color:#009900'>Contrato de Adiantamento</div>";}

	else
	{$erro = 0;
	$msg = "";
	$msg_titulo = "<div style='color:#0000FF'>Contrato de Adiantamento Cadastrado com Sucesso!</div>";
	


$inserir = mysqli_query($conexao, "INSERT INTO contrato_adiantamento (codigo, numero_contrato, data_contrato, data_vencimento, cod_fornecedor, nome_fornecedor, cod_produto, nome_produto, valor, safra, filial, observacao, estado_registro, usuario_cadastro, data_cadastro, hora_cadastro) VALUES (NULL, '$numero_contrato', '$data_contrato_aux', '$data_vencimento_aux', '$fornecedor_pesquisa', '$nome_pessoa', '$cod_seleciona_produto', '$produto_print', '$valor_form', '$safra_form', '$filial', '$obs_form', 'ATIVO', '$usuario_cadastro', '$data_cadastro', '$hora_cadastro')");

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
    <?php echo"$data_hoje_br"; ?>
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
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$data_contrato_form"; ?></div></div>
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
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$data_vencimento_form"; ?></div></div>
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
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'><?php echo"$safra_form"; ?></div></div>
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
    <div style="margin-top:6px; margin-left:5px; width:485px; height:16px; overflow:hidden"><?php echo"$obs_form" ?></div></div>
    </div>
</div>
<!-- ============================================================================================================== -->



</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->




<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
if ($erro == 0)
{
	echo"
	<div id='centro' style='float:left; height:55px; width:235px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO NOVO ========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/cadastro_1_selec_produto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Novo Contrato</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== FICHA DO PRODUTOR ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/ficha_produtor/movimentacao_produtor.php' method='post' target='_blank'>
		<input type='hidden' name='fornecedor' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_produto' value='$cod_seleciona_produto'>
		<input type='hidden' name='botao' value='seleciona'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Ficha do Produtor</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== IMPRIMIR CONTRATO ====================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/contrato_adto_impressao.php' method='post' target='_blank'>
		<input type='hidden' name='numero_contrato' value='$numero_contrato' />
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Imprimir Contrato</button>
		</form>
    </div>";
// =============================================================================================================================


// ====== BOTAO SAIR =========================================================================================================
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
		<form action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/index_adto.php' method='post'>
		<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Sair</button>
		</form>
    </div>";
// =============================================================================================================================
}

elseif ($erro == 6)
{
// ====== BOTAO SAIR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/index_adto.php' method='post'>
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Sair</button>
    </form>
    </div>";
// =============================================================================================================================
}

else
{
// ====== BOTAO VOLTAR =========================================================================================================
	echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>
	
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form name='voltar' action='$servidor/$diretorio_servidor/compras/contrato_adiantamento/cadastro_3_formulario.php' method='post'>
	<input type='hidden' name='botao' value='ERRO' />
	<input type='hidden' name='numero_contrato' value='$numero_contrato' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_seleciona_produto' />
	<input type='hidden' name='valor_form' value='$valor_form' />
	<input type='hidden' name='data_contrato_form' value='$data_contrato_form' />
	<input type='hidden' name='data_vencimento_form' value='$data_vencimento_form' />
	<input type='hidden' name='safra_form' value='$safra_form' />
	<input type='hidden' name='obs_form' value='$obs_form' />
    <button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
    </form>
    </div>";
// =============================================================================================================================
}

?>
</div>




<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
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