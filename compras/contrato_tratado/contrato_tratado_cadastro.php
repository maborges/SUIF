<?php
include ('../../includes/config.php');
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'contrato_tratado_cadastro';
$titulo = 'Contrato Tratado';
$modulo = 'compras';
$menu = 'contratos';


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$fornecedor = $_POST["fornecedor"];
$cod_produto = $_POST["cod_produto"];
$cod_tipo = $_POST["cod_tipo"];
$data_entrega_i = $_POST["data_entrega_i"];	
$data_entrega_f = $_POST["data_entrega_f"];	
$quantidade = $_POST["quantidade"];
$quantidade_quilo = $_POST["quantidade_quilo"];
$safra = $_POST["safra"];
$valor = $_POST["valor"];
$prazo_pgto = $_POST["prazo_pgto"];
$umidade = $_POST["umidade"];
$broca = $_POST["broca"];
$impureza = $_POST["impureza"];
$fiador_1 = $_POST["fiador_1"];
$fiador_2 = $_POST["fiador_2"];
$observacao = $_POST["observacao"];

if ($botao == "selecionar")
{$data_aux_1 = date('d/m/Y');
$data_aux_2 = date('d/m/Y');}
else
{$data_aux_1 = $data_entrega_i;
$data_aux_2 = $data_entrega_f;}
// ========================================================================================================


// ====== CONTADOR NÚMERO CONTRATO ==========================================================================
$busca_numero_contrato = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnc = mysqli_fetch_row($busca_numero_contrato);
$numero_contrato = $aux_bnc[10];

$contador_num_contrato = $numero_contrato + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_contrato='$contador_num_contrato'");
// ========================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto' AND estado_registro!='EXCLUIDO'");
$aux_bp = mysqli_fetch_row($busca_produto);
$linhas_bp = mysqli_num_rows ($busca_produto);

$produto_print = $aux_bp[1];
$produto_print_2 = $aux_bp[22];
$produto_apelido = $aux_bp[20];
$cod_unidade = $aux_bp[7];
$quantidade_un = $aux_bp[23];
$preco_maximo = $aux_bp[21];
$preco_maximo_print = number_format($aux_bp[21],2,",",".");
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor' AND estado_registro!='EXCLUIDO'");
$aux_forn = mysqli_fetch_row($busca_fornecedor);
$linhas_fornecedor = mysqli_num_rows ($busca_fornecedor);

$fornecedor_print = $aux_forn[1];
$codigo_pessoa = $aux_forn[35];
$cidade_fornecedor = $aux_forn[10];
$estado_fornecedor = $aux_forn[12];
$telefone_fornecedor = $aux_forn[14];
if ($aux_forn[2] == "pf")
{$cpf_cnpj = $aux_forn[3];}
else
{$cpf_cnpj = $aux_forn[4];}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== RETORNA ERRO ===================================================================================
if ($fornecedor == "")
{$erro = 1;
$msg_erro = "<i>* Selecione um fornecedor.</i>";}
elseif ($linhas_fornecedor == 0)
{$erro = 2;
$msg_erro = "<i>* Fornecedor inv&aacute;lido.</i>";}
elseif ($cod_produto == "")
{$erro = 3;
$msg_erro = "<i>* Selecione um produto.</i>";}
elseif ($linhas_bp == 0)
{$erro = 4;
$msg_erro = "<i>* Produto inv&aacute;lido.</i>";}
else
{$erro = 0;
$msg_erro = "";}
// ======================================================================================================


// ========================================================================================================
include ('../../includes/head.php');
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





<!-- =============================================   C E N T R O   =============================================== -->
<div id="centro_geral">
<div id="centro" style="height:440px; width:1080px; border:0px solid #000; margin:auto">
<form name="popup" action='<?php echo"$servidor/$diretorio_servidor"; ?>/compras/contrato_tratado/contrato_tratado_enviar.php' method='post'>
<input type="hidden" name="botao" value="contrato_cadastro" />
<input type="hidden" name="fornecedor" value="<?php echo"$fornecedor"; ?>" />
<input type="hidden" name="cod_produto" value="<?php echo"$cod_produto"; ?>" />
<input type="hidden" name="numero_contrato" value="<?php echo "$numero_contrato"; ?>" />

<div style="width:1080px; height:15px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:30px; float:left; border:0px solid #000">
	<div id="titulo_form_1" style="width:700px; height:30px; float:left; border:0px solid #000; margin-left:185px">
    Contrato Tratado - Cadastro
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:20px; float:left; border:0px solid #000">
	<div id="titulo_form_3" style="width:700px; height:20px; float:left; border:0px solid #000; margin-left:185px">
    <?php echo "$msg_erro"; ?>
    </div>
</div>

<div style="width:1080px; height:10px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    N&uacute;mero do Contrato
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Fornecedor
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= CODIGO E FORNECEDOR =============================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <input type="text" name="numero_contrato_aux" maxlength="30" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; 
    font-size:12px; width:145px" value="<?php echo "$numero_contrato"; ?>" disabled="disabled" />
    </div>

	<div style="width:525px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <input type="text" name="fornecedor_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; 
    font-weight:bold; width:515px" value="<?php echo "$fornecedor_print"; ?>" disabled="disabled" />
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Produto
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Tipo
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Data inicial da entrega
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Data final da entrega
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= PRODUTO, TIPO E DATAS =============================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <input type="text" name="produto_print" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; font-size:12px; 
    width:145px; text-align:left" value="<?php echo"$produto_print"; ?>" disabled="disabled" />
    </div>

	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name="cod_tipo" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; height:21px; font-size:12px; text-align:left" />
    <option></option>
    <?php
	$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto' AND 
	estado_registro='ATIVO' ORDER BY codigo");
	$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);
    
    for ($t=1 ; $t<=$linhas_tipo_produto ; $t++)
    {
    $aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
    
        if ($aux_tipo_produto[0] == $cod_tipo)
        {echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
        else
        {echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
    }
    ?>
    </select>
    </div>
    
	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <input type="text" name="data_entrega_i" maxlength="10" onkeypress="mascara(this,data)" onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo "$data_aux_1"; ?>" id="calendario" />
    </div>

	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <input type="text" name="data_entrega_f" maxlength="10" onkeypress="mascara(this,data)" onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; font-size:12px; width:145px; text-align:center" value="<?php echo "$data_aux_2"; ?>" id="calendario_2" />
    </div>
</div>
<!-- ============================================================================================================= -->




<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Quantidade (<?php echo"$unidade_print"; ?>)
    </div>

	<?php
    if ($unidade_print == "SC")
    {echo "
	<div style='width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666'>
	Quantidade Fra&ccedil;&atilde;o (KG)
	</div>";}
    else
    {echo "
	<div style='width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#D3D3D3'>
	Quantidade Fra&ccedil;&atilde;o (KG)
	</div>";}
    ?>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Safra
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Pre&ccedil;o Tratado (R$)
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= QUANTIDADES E PREÇO =============================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <input type="text" name="quantidade" maxlength="15" onkeypress="troca(this)" onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$quantidade"; ?>" />
    </div>
    
    <?php
	if ($unidade_print == "SC")
	{echo "
	<div style='width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px'>
	<input type='text' name='quantidade_quilo' maxlength='15' onkeypress='troca(this)' onkeydown='if (getKey(event) == 13) return false;' 
	style='color:#0000FF; width:145px; font-size:12px; text-align:center' value='$quantidade_quilo' />
	</div>";}
	else
	{echo "
	<div style='width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px'>
	<input type='text' name='quantidade_quilo' maxlength='15' onkeypress='troca(this)' onkeydown='if (getKey(event) == 13) return false;' 
	style='color:#0000FF; width:145px; font-size:12px; text-align:center' value='$quantidade_quilo' disabled='disabled' />
	</div>";}
	?>

    <div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <input type="text" name="safra" maxlength="12" onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$safra"; ?>" />
    </div>

    <div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <input type="text" name="valor" maxlength="15" onkeypress="mascara(this,mvalor)" onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$valor"; ?>" />
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Prazo de PGTO (dias):
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Umidade
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Broca
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Impureza
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= PGTO, UMIDADE, BROCA E IMPUREZA ================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <input type="text" name="prazo_pgto" maxlength="3" value='2' onkeydown="if (getKey(event) == 13) return false;" 
    style="color:#0000FF; width:145px; font-size:12px; text-align:center" value="<?php echo "$prazo_pgto"; ?>" />
	</div>
    
    <div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name="umidade" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
    <option value="13%">13%</option>
    <?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
        if ($botao == "selecionar")
        {
            if ($aux_porcentagem[1] == "")
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
        else
        {
            if ($aux_porcentagem[1] == $umidade)
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
    }
    ?>
    </select>
    </div>
    
    <div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name="broca" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
    <option value="10%">10%</option>
    <?php
	$busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
	$linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
        if ($botao == "selecionar")
        {
            if ($aux_porcentagem[1] == "")
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
        else
        {
            if ($aux_porcentagem[1] == $broca)
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
    }
    ?>
    </select>
    </div>
    
    <div style="width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name="impureza" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; width:149px; font-size:12px; text-align:left" />
    <option value="1%">1%</option>
    <?php
    $busca_porcentagem = mysqli_query ($conexao, "SELECT * FROM select_porcentagem WHERE estado_registro='ATIVO' ORDER BY codigo");
    $linhas_porcentagem = mysqli_num_rows ($busca_porcentagem);
    
    for ($t=1 ; $t<=$linhas_porcentagem ; $t++)
    {
    $aux_porcentagem = mysqli_fetch_row($busca_porcentagem);	
        if ($botao == "selecionar")
        {
            if ($aux_porcentagem[1] == "")
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
        else
        {
            if ($aux_porcentagem[1] == $impureza)
            {echo "<option selected='selected' value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
            else
            {echo "<option value='$aux_porcentagem[1]'>$aux_porcentagem[1]</option>";}
        }
    }
    ?>
    </select>
	</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Testemunha 1
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    <!-- xxxxxxxxxx -->
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    Testemunha 2
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    <!-- xxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= AVALISTAS ========================================================================================= -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:340px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <select name='fiador_1' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:330px; font-size:12px; text-align:left' />
    <option></option>
    <?php
        $busca_fiador_1 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' ORDER BY nome");
        $linha_fiador_1 = mysqli_num_rows ($busca_fiador_1);
    
        for ($i=1 ; $i<=$linha_fiador_1 ; $i++)
        {
        $fiador_aux_1 = mysqli_fetch_row($busca_fiador_1);	
			if ($fiador_aux_1[0] == $fiador_1)
			{echo "<option selected='selected' value='$fiador_aux_1[0]'>$fiador_aux_1[1]</option>";}
			else
			{echo "<option value='$fiador_aux_1[0]'>$fiador_aux_1[1]</option>";}
        }
    ?>
    </select>
    </div>
    
    <div style="width:340px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    <select name='fiador_2' onkeydown='if (getKey(event) == 13) return false;' style='color:#0000FF; width:330px; font-size:12px; text-align:left' />
    <option></option>
    <?php
        $busca_fiador_2 = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE estado_registro='ATIVO' ORDER BY nome");
        $linha_fiador_2 = mysqli_num_rows ($busca_fiador_2);
    
        for ($i=1 ; $i<=$linha_fiador_2 ; $i++)
        {
        $fiador_aux_2 = mysqli_fetch_row($busca_fiador_2);	
			if ($fiador_aux_2[0] == $fiador_2)
			{echo "<option selected='selected' value='$fiador_aux_2[0]'>$fiador_aux_2[1]</option>";}
			else
			{echo "<option value='$fiador_aux_2[0]'>$fiador_aux_2[1]</option>";}
        }
    ?>
    </select>
    </div>
</div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<div style="width:1080px; height:15px; float:left; border:0px solid #000">
	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:185px; font-size:11px; color:#666">
    Observa&ccedil;&atilde;o
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    <!-- xxxxxxxxxx -->
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    <!-- xxxxxxxxxx -->
    </div>

	<div style="width:155px; height:15px; float:left; border:0px solid #000; margin-left:30px; font-size:11px; color:#666">
    <!-- xxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ========= AVALISTAS ========================================================================================= -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
	<div style="width:340px; height:35px; float:left; border:0px solid #000; margin-left:185px">
    <input type="text" name="observacao" maxlength="100" onkeydown="if (getKey(event) == 13) return false;" style="color:#0000FF; 
    width:330px; font-size:12px" value="<?php echo "$observacao"; ?>" />
    </div>
    
    <div style="width:340px; height:35px; float:left; border:0px solid #000; margin-left:30px">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="width:1080px; height:5px; float:left; border:0px solid #000"></div>
<!-- ============================================================================================================= -->


<!-- ========= BOTAO ======================================================================================== -->
<div style="width:1080px; height:35px; float:left; border:0px solid #000">
<?php
if ($erro == 1 or $erro == 2 or $erro == 3 or $erro == 4)
{echo "
	<div style='width:155px; height:35px; float:left; border:0px solid #000; margin-left:480px'>
	</form>
	<form name='volta' action='$servidor/$diretorio_servidor/compras/contrato_tratado/contrato_tratado_seleciona.php' method='post'>
	<input type='hidden' name='fornecedor' value='$fornecedor' />
	<input type='hidden' name='cod_produto' value='$cod_produto' />
    <button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Voltar</button>
    </form>
    </div>";}

else
{echo "
	<div style='width:155px; height:35px; float:left; border:0px solid #000; margin-left:380px'>
    <button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Confirmar</button>
    </form>
    </div>
    
	<div style='width:155px; height:35px; float:left; border:0px solid #000; margin-left:30px'>
    <a href='$servidor/$diretorio_servidor/compras/contrato_tratado/index_contrato_tratado.php'>
    <button type='submit' class='botao_2' style='margin-left:20px; width:180px'>Cancelar</button></a>
    </div>";}
?>
</div>
<!-- ============================================================================================================= -->



</div><!-- 1º centro -->
</div><!-- centro_geral -->



<!-- =============================================   R O D A P É   =============================================== -->
<div id="rodape_geral">
<?php include ('../../includes/rodape.php'); ?>
</div>

<!-- =============================================   F  I  M   =================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>