<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "visualizar_cadastro";
$titulo = "Cadastro de Favorecido";
$modulo = "cadastros";
$menu = "cadastro_favorecidos";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$id_w = $_POST["id_w"];
$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$pagina_mae = $_POST["pagina_mae"];

$nome_busca = $_POST["nome_busca"];
$status_busca = $_POST["status_busca"];
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_favorecido = mysqli_query ($conexao, "SELECT * FROM cadastro_favorecido WHERE codigo='$id_w'");
$linha_favorecido = mysqli_num_rows ($busca_favorecido);
// ================================================================================================================


// ====== FUNÇÃO FOR ==============================================================================================
for ($x=1 ; $x<=$linha_favorecido ; $x++)
{
$aux_favorecido = mysqli_fetch_row($busca_favorecido);
}
// ================================================================================================================


// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_favorecido[0];
$codigo_pessoa_w = $aux_favorecido[1];
$banco_w = $aux_favorecido[2];
$agencia_w = $aux_favorecido[3];
$conta_w = $aux_favorecido[4];
$tipo_conta_w = $aux_favorecido[5];
$observacao_w = $aux_favorecido[12];
$estado_registro_w = $aux_favorecido[13];
$nome_w = $aux_favorecido[14];
$conta_conjunta_w = $aux_favorecido[15];
$tipo_chave_pix_w = $aux_favorecido[19];
$chave_pix_w = $aux_favorecido[20];

if ($conta_conjunta_w == "SIM" and $agencia_w!="")
{$conta_conjunta_print = "SIM";}
elseif ($conta_conjunta_w != "SIM" and $agencia_w!="")
{$conta_conjunta_print = "N&Atilde;O";}
else
{$conta_conjunta_print = "";}

$usuario_cadastro_w = $aux_favorecido[6];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_favorecido[8]));
$hora_cadastro_w = $aux_favorecido[7];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_favorecido[9];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_favorecido[11]));
$hora_alteracao_w = $aux_favorecido[10];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_favorecido[16];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_favorecido[17]));
$hora_exclusao_w = $aux_favorecido[18];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ====== BUSCA PESSOA ===================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo_pessoa='$codigo_pessoa_w'");
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
// ======================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa == "PF" or $tipo_pessoa == "pf")
{$tipo_pessoa_print = "PESSOA F&Iacute;SICA";}
elseif ($tipo_pessoa == "PJ" or $tipo_pessoa == "pj")
{$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";}
else
{$tipo_pessoa_print = "";}
// ================================================================================================================


// ====== BUSCA BANCO ===================================================================================
$busca_banco = mysqli_query ($conexao, "SELECT * FROM cadastro_banco WHERE numero='$banco_w'");
$aux_banco = mysqli_fetch_row($busca_banco);

$apelido_banco = $aux_banco[3];
$logomarca_banco = $aux_banco[4];

if (empty($logomarca_banco) and $conta_w!="")
{$logo_banco = "
<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid #999; color:#999; text-align:center'>
<div style='margin-top:15px; margin-left:0px; width:297px; height:40px; border:1px solid transparent; font-size:16px; text-align:center'>
<i>$apelido_banco</i></div></div>";}
elseif ($logomarca_banco!="" and $conta_w!="")
{$logo_banco = "
<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid transparent; color:#999; text-align:center'>
<img src='$servidor/$diretorio_servidor/imagens/$logomarca_banco' style='height:68px' /></div>";}
else
{$logo_banco = "
<div style='margin-top:20px; margin-left:20px; width:299px; height:70px; border:1px solid #999; color:#999; text-align:center'>
<div style='margin-top:15px; margin-left:0px; width:297px; height:40px; border:1px solid transparent; font-size:16px; text-align:center'>
<i>Dados banc&aacute;rios</br>n&atilde;o cadastrados</i></div></div>";}
// ======================================================================================================


// ====== TIPO DE CONTA =================================================================================
if ($tipo_conta_w == "corrente")
{$tipo_conta_print = "Corrente";}
elseif ($tipo_conta_w == "poupanca")
{$tipo_conta_print = "Poupan&ccedil;a";}
elseif ($tipo_conta_w == "salario")
{$tipo_conta_print = "Sal&aacute;rio";}
elseif ($tipo_conta_w == "aplicacao")
{$tipo_conta_print = "Aplica&ccedil;&atilde;o";}
else
{$tipo_conta_print = "";}
// ======================================================================================================


// ======= TIPO DE CHAVE PIX ======================================================================================
if ($tipo_chave_pix_w == "cpf_cnpj")
{$tipo_chave_pix_print = "CPF/CNPJ";}
elseif ($tipo_chave_pix_w == "celular")
{$tipo_chave_pix_print = "Celular";}
elseif ($tipo_chave_pix_w == "email")
{$tipo_chave_pix_print = "E-mail";}
elseif ($tipo_chave_pix_w == "aleatoria")
{$tipo_chave_pix_print = "Chave Aleat&oacute;ria";}
else
{$tipo_chave_pix_print = "";}
// ================================================================================================================


// ====== BLOQUEIO PARA EDITAR ========================================================================
if ($permissao[35] == "S" and $estado_registro_w == "ATIVO")
{$permite_editar = "SIM";}
else
{$permite_editar = "NAO";}
// ========================================================================================================


// ======================================================================================================
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
<?php  include ("../../includes/topo.php"); ?>
</div>


<!-- ====== MENU ================================================================================================== -->
<div class="menu">
<?php include ("../../includes/menu_cadastro.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_cadastro_favorecidos.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_fixo" style="height:560px">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
	<?php echo"$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:400px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pf")
    {echo "Raz&atilde;o Social:";}
    else
    {echo "Nome:";}
    ?>
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
    if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pf")
    {echo "CNPJ:";}
    else
    {echo "CPF:";}
    ?>
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    if ($tipo_pessoa == "PJ" or $tipo_pessoa == "pf")
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
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$telefone_pessoa" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  TIPO PESSOA ========================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Tipo de Pessoa:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:153px; height:25px; border:1px solid #009900; float:left; font-size:12px; text-align:center; background-color:#EEE'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  BANCO ============================================================================================== -->
	<div style="width:339px; height:200px; border:1px solid transparent; margin-top:10px; float:left; text-align:center">
   	<?php echo"$logo_banco" ?>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= AGÊNCIA ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Ag&ecirc;ncia:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$agencia_w" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO CONTA =========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&ordm; da Conta:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$conta_w" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO DE CONTA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Conta:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$tipo_conta_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CONTA CONJUNTA ========================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Conta Conjunta:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$conta_conjunta_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TIPO DE CHAVE PIX ====================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Tipo de Chave Pix:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$tipo_chave_pix_print" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CHAVE PIX ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Pix:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$chave_pix_w" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= OBSERVAÇÃO ============================================================================================= -->
	<div style="width:680px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:668px; height:17px; border:1px solid transparent; float:left">
		Observa&ccedil;&atilde;o:
        </div>
        
        <div style="width:668px; height:25px; float:left; border:1px solid transparent">
		<div style="width:666px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
		<div style="margin-top:6px; margin-left:5px; width:654px; height:16px; color:#003466; text-align:left; overflow:hidden"><?php echo"$observacao_w" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ============================================================================================================= -->
<div style="width:1020px; height:30px; float:left"></div>
<!-- ============================================================================================================= -->


<!-- ======= DADOS CADASTRO ========================================================================================= -->
<?php
if ($dados_cadastro_w != "")
{echo "
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		<!-- Cadastrado por: -->
        </div>

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
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		<!-- Editado por: -->
        </div>

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
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		<!-- Exclu&iacute;do por: -->
        </div>

        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #999; float:left; color:#999; font-size:10px; text-align:center; background-color:#EEE'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden' title='Motivo da Exclus&atilde;o: $motivo_exclusao_w'>
		<i>$dados_exclusao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->



</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
echo"
<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO EDITAR ========================================================================================================
if ($permite_editar == "SIM")
{	
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/editar_1_formulario.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='EDITAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Editar</button>
	</form>
</div>";
}

else
{
	echo "
	<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px; color:#BBB'>Editar</button>
	</div>";
}
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/cadastros/favorecidos/$pagina_mae.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='BUSCAR'>
	<input type='hidden' name='botao_2' value='VOLTAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='status_busca' value='$status_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Voltar</button>
	</form>
</div>";
// =============================================================================================================================
?>
</div>








</div>
<!-- ====== FIM DIV CT_1 ========================================================================================= -->



<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>