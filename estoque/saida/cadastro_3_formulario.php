<?php
// ================================================================================================================
include ('../../includes/config.php'); 
include ('../../includes/conecta_bd.php');
include ('../../includes/valida_cookies.php');
$pagina = 'cadastro_3_formulario';
$titulo = 'Novo Romaneio de Sa&iacute;da';
$modulo = 'estoque';
$menu = 'saida';
// ================================================================================================================


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$data_hoje = date('d/m/Y');
$filial = $filial_usuario;

$fornecedor_form = $_POST["fornecedor_form"];
$cod_produto_form = $_POST["cod_produto_form"];

$peso_form = $_POST["peso_form"];
$peso_inicial_form = $_POST["peso_inicial_form"];
$peso_final_form = $_POST["peso_final_form"];
$cod_sacaria_form = $_POST["cod_sacaria_form"];
$quant_sacaria_form = $_POST["quant_sacaria_form"];
$desconto_form = $_POST["desconto_form"];
$quant_volume_form = $_POST["quant_volume_form"];
$cod_tipo_produto_form = $_POST["cod_tipo_produto_form"];
$romaneio_manual_form = $_POST["romaneio_manual_form"];
$filial_origem_form = $_POST["filial_origem_form"];
$motorista_form = $_POST["motorista_form"];
$motorista_cpf_form = $_POST["motorista_cpf_form"];
$placa_veiculo_form = $_POST["placa_veiculo_form"];
$obs_form = $_POST["obs_form"];
// ========================================================================================================


// ====== CONTADOR NÚMERO ROMANEIO ==========================================================================
if ($botao == "FORMULARIO")
{
$busca_numero_romaneio = mysqli_query ($conexao, "SELECT * FROM configuracoes");
$aux_bnr = mysqli_fetch_row($busca_numero_romaneio);
$numero_romaneio = $aux_bnr[8];

$contador_num_romaneio = $numero_romaneio + 1;
$altera_contador = mysqli_query ($conexao, "UPDATE configuracoes SET contador_numero_romaneio='$contador_num_romaneio'");
}

elseif ($botao == "ERRO")
{
$numero_romaneio = $_POST["numero_romaneio"];
}
// ================================================================================================================


// ====== BUSCA PRODUTO ===================================================================================
$busca_produto = mysqli_query ($conexao, "SELECT * FROM cadastro_produto WHERE codigo='$cod_produto_form' AND estado_registro!='EXCLUIDO'");
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
$usuario_alteracao = $aux_bp[16];
$data_alteracao = date('d/m/Y', strtotime($aux_bp[18]));
$cod_tipo_preferencial = $aux_bp[29];
$umidade_preferencial = $aux_bp[30];
$broca_preferencial = $aux_bp[31];
$impureza_preferencial = $aux_bp[32];
$densidade_preferencial = $aux_bp[33];

if ($botao == "FORMULARIO")
{$cod_tipo_produto_form = $cod_tipo_preferencial;}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_fornecedor = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$fornecedor_form' AND estado_registro!='EXCLUIDO'");
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

if ($linhas_fornecedor == 0)
{$cidade_uf_fornecedor = "";}
else
{$cidade_uf_fornecedor = "$cidade_fornecedor/$estado_fornecedor";}
// ======================================================================================================


// ====== BUSCA UNIDADE DE MEDIDA ===================================================================================
$busca_un_med = mysqli_query ($conexao, "SELECT * FROM unidade_produto WHERE codigo='$cod_unidade' AND estado_registro!='EXCLUIDO'");
$aux_un_med = mysqli_fetch_row($busca_un_med);

$un_descricao = $aux_un_med[1];
$unidade_print = $aux_un_med[2];
// ======================================================================================================


// ====== MONTA MENSAGEM ===================================================================================
if ($fornecedor_form == "")
{$erro = 1;
$msg = "<div style='color:#FF0000'>Selecione um fornecedor</div>";}
elseif ($linhas_fornecedor == 0)
{$erro = 2;
$msg = "<div style='color:#FF0000'>Fornecedor inv&aacute;lido</div>";}
elseif ($cod_produto_form == "" or $linhas_bp == 0)
{$erro = 3;
$msg = "<div style='color:#FF0000'>Selecione um produto</div>";}
else
{$erro = 0;
$msg = "";}
// ======================================================================================================


// =============================================================================
include ('../../includes/head.php'); 
?>

<!-- ====== TÍTULO DA PÁGINA ====================================================================================== -->
<title>
<?php echo "$titulo"; ?>
</title>


<!-- ====== JAVASCRIPT ============================================================================================ -->
<script type="text/javascript">
<?php include ('../../includes/javascript.php'); ?>
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
<?php include ("../../includes/submenu_estoque_saida.php"); ?>
</div>




<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_1" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1" style="width:545px; float:left; text-align:left; border:0px solid #000">
    Novo Romaneio de Sa&iacute;da
    </div>


	<div class="ct_titulo_1" style="width:545px; float:right; text-align:right; border:0px solid #000">
    N&ordm; <?php echo"$numero_romaneio"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_1" style="width:545px; float:left; text-align:left">
	<?php echo "$msg"; ?>
    </div>

	<div class="ct_subtitulo_1" style="width:545px; float:right; text-align:right; font-style:normal">
    <?php echo"$data_hoje"; ?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div style="height:160px; width:1080px; border:0px solid #0000FF; margin:auto">
<form action="<?php echo"$servidor/$diretorio_servidor"; ?>/estoque/saida/cadastro_4_enviar.php" method="post" />
<input type="hidden" name="botao" value="NOVO_ROMANEIO" />
<input type="hidden" name="fornecedor_form" value="<?php echo"$fornecedor_form"; ?>" />
<input type="hidden" name="cod_produto_form" value="<?php echo"$cod_produto_form"; ?>" />
<input type="hidden" name="numero_romaneio" value="<?php echo "$numero_romaneio"; ?>" />


<!-- ===================== DADOS DO FORNECEDOR ============================================================================= -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px">
    <div class="form_rotulo" style="width:1030px; height:20px; border:1px solid transparent; float:left">
    Cliente:
    </div>
</div>

<div style="width:1030px; height:50px; border:1px solid #009900; color:#003466; margin-left:25px; background-color:#EEE">

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>
    
	<div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Nome:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$fornecedor_print</b>" ?></div>
	</div>
    
	<div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
    	<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">CPF/CNPJ:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cpf_cnpj</b>" ?></div>
	</div>

	<div style="width:1030px; height:5px; border:0px solid #000; float:left"></div>

    <div style="width:700px; height:15px; border:0px solid #000; margin-left:10px; float:left">
        <div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Cidade:</div>
        <div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$cidade_uf_fornecedor</b>" ?></div>
    </div>
    
    <div style="width:300px; height:15px; border:0px solid #000; margin-left:10px; float:left">
		<div class="form_rotulo" style="margin-top:3px; margin-left:5px; float:left">Telefone:</div>
		<div style="margin-top:3px; margin-left:5px; font-size:12px; color:#003466; float:left"><?php echo"<b>$telefone_fornecedor</b>" ?></div>
	</div>

</div>
<!-- ======================================================================================================================= -->


<!-- ===================== DADOS DO PRODUTO ================================================================================ -->
<div style="width:1030px; height:20px; border:0px solid #000; margin-top:0px; margin-left:25px; margin-top:20px">
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:left">
    Produto:
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; margin-left:153px; float:left">
    <!-- xxxxxxxxxxxxxx: -->
    </div>
    <div class="form_rotulo" style="width:241px; height:20px; border:1px solid transparent; float:right">
	<!-- xxxxxxxxxxxxxx: -->
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:25px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<!--
<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-left:153px; background-color:#EEE; float:left">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>

<div style="width:241px; height:32px; border:1px solid #009900; color:#003466; overflow:hidden; margin-right:25px; background-color:#EEE; float:right">
    <div style="width:60px; height:25px; margin-top:4px; margin-left:5px; float:left; font-size:14px; color:#003466">
        <?php //echo"<img src='$servidor/$diretorio_servidor/imagens/$nome_imagem_produto.png' style='width:60px'>" ?>
    </div>

    <div style="width:170px; height:20px; margin-top:7px; margin-left:0px; float:left; font-size:14px; color:#003466; overflow:hidden">
        <?php //echo"<b>$produto_print_2</b>" ?>
    </div>
</div>
-->
<!-- ======================================================================================================================= -->


</div>
<!-- ======================================================================================================================= -->


<div class="espacamento_10"></div>


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:195px; margin:auto; border:1px solid transparent">


<?php
if ($filial_config[9] == "S")
{echo"
<!-- =======  PESO INICIAL =========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Inicial (Kg):
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='peso_inicial_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_inicial_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  PESO FINAL =========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso Final (Kg):
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='peso_final_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_final_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";}

else
{echo"
<!-- =======  PESO ================================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Peso (Kg):
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='peso_form' id='ok' class='form_input' maxlength='12' onkeypress='$config[30]' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:150px; text-align:center' value='$peso_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";}

?>





<!-- =======  TIPO SACARIA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Tipo Sacaria:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <select name="cod_sacaria_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
        <option></option>
        <?php
        
        $busca_tipo_sacaria = mysqli_query ($conexao, "SELECT * FROM select_tipo_sacaria WHERE movimentacao='SAIDA' AND estado_registro='ATIVO' ORDER BY codigo");
        $linhas_tipo_sacaria = mysqli_num_rows ($busca_tipo_sacaria);
        
        for ($t=1 ; $t<=$linhas_tipo_sacaria ; $t++)
        {
        $aux_tipo_sacaria = mysqli_fetch_row($busca_tipo_sacaria);	
        
        if ($aux_tipo_sacaria[0] == $cod_sacaria_form)
        {echo "<option selected='selected' value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
        else
        {echo "<option value='$aux_tipo_sacaria[0]'>$aux_tipo_sacaria[1]</option>";}
        }
        ?>
        </select>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= QUANTIDADE SACARIA =========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Quant. de Sacaria (Un):"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="quant_sacaria_form" class="form_input" maxlength="12" onkeypress="<?php echo"$config[30]"; ?>"  
        onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$quant_sacaria_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= OUTROS DESCONTOS =========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Outros Descontos (Kg):"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="desconto_form" class="form_input" maxlength="12" onkeypress="<?php echo"$config[30]"; ?>"  
        onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$desconto_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


    <!-- ======= QUANTIDADE VOLUME SACAS ================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Quant. Volume de Sacas:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="quant_volume_form" class="form_input" maxlength="12" onkeypress="<?php echo"$config[30]"; ?>"
        onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$quant_volume_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  TIPO PRODUTO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Tipo do Produto:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <select name="cod_tipo_produto_form" class="form_select" onkeydown="if (getKey(event) == 13) return false;" style="width:154px; font-size:12px" />
        <option></option>
        <?php
		$busca_tipo_produto = mysqli_query ($conexao, "SELECT * FROM select_tipo_produto WHERE cod_produto='$cod_produto_form' AND estado_registro='ATIVO' ORDER BY codigo");
		$linhas_tipo_produto = mysqli_num_rows ($busca_tipo_produto);
        
        for ($tp=1 ; $tp<=$linhas_tipo_produto ; $tp++)
        {
        $aux_tipo_produto = mysqli_fetch_row($busca_tipo_produto);	
        
        if ($aux_tipo_produto[0] == $cod_tipo_produto_form)
        {echo "<option selected='selected' value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
        else
        {echo "<option value='$aux_tipo_produto[0]'>$aux_tipo_produto[1]</option>";}
        }
        ?>
        </select>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO ROMANEIO MANUAL ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php echo"N&ordm; Romaneio Manual:"; ?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="romaneio_manual_form" class="form_input" maxlength="10" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:150px; text-align:center" value="<?php echo"$romaneio_manual_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->


<?php
if ($config[34] == "S")
{echo"
<!-- ======= MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='motorista_form' class='form_input' maxlength='25' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$motorista_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[35] == "S")
{echo"
<!-- ======= CPF MOTORISTA ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        CPF Motorista:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='motorista_cpf_form' class='form_input' maxlength='14' onkeypress='mascara(this,num_cpf)' onBlur='mascara(this,num_cpf)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$motorista_cpf_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[36] == "S")
{echo"
<!-- ======= PLACA VEICULO ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Placa do Ve&iacute;culo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <input type='text' name='placa_veiculo_form' class='form_input' maxlength='20' onBlur='alteraMaiusculo(this)' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:145px; text-align:left; padding-left:5px' value='$placa_veiculo_form' />
        </div>
	</div>
<!-- ================================================================================================================ -->
";}




if ($config[33] == "S")
{echo"
<!-- =======  FILIAL ORIGEM ========================================================================================== -->
	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Filial Origem:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
        <select name='filial_origem_form' class='form_select' onkeydown='if (getKey(event) == 13) return false;' style='width:154px' />
        <option></option>";
        $busca_filial_origem = mysqli_query ($conexao, "SELECT * FROM filiais ORDER BY codigo");
        $linhas_filial_origem = mysqli_num_rows ($busca_filial_origem);
        
        for ($f=1 ; $f<=$linhas_filial_origem ; $f++)
        {
        $aux_filial_origem = mysqli_fetch_row($busca_filial_origem);	
    
        if ($aux_filial_origem[1] == $filial_origem_form)
        {echo "<option selected='selected' value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
        else
        {echo "<option value='$aux_filial_origem[1]'>$aux_filial_origem[2]</option>";}
        }
		echo "
        </select>
        </div>
	</div>
<!-- ================================================================================================================ -->
";}
?>


<!-- =======  OBSERVAÇÃO ============================================================================================ -->
	<div style="width:682px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:674px; height:17px; border:1px solid transparent; float:left">
        <?php echo"Observa&ccedil;&atilde;o:"; ?>
        </div>
        
        <div style="width:674px; height:25px; float:left; border:1px solid transparent">
        <input type="text" name="obs_form" class="form_input" maxlength="150" 
        onkeydown="if (getKey(event) == 13) return false;" style="width:663px; text-align:left" value="<?php echo"$obs_form"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->




</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:1px solid transparent; margin:auto; text-align:center">
	
	<?php
	if ($erro != 0)
	{echo"
	<div id='centro' style='float:left; height:55px; width:535px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	</form>
	<form action='$servidor/$diretorio_servidor/estoque/saida/cadastro_1_selec_produto.php' method='post'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
	</div>";}


	else
	{echo"
	<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Salvar</button>
	</form>
	</div>

	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<a href='$servidor/$diretorio_servidor/estoque/saida/saida_relatorio_produto.php'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Cancelar</button>
	</a>
	</div>";}

	?>
</div>














</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ('../../includes/rodape.php'); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ('../../includes/desconecta_bd.php'); ?>
</body>
</html>