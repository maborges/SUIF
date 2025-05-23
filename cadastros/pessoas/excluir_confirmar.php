<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "excluir_confirmar";
$titulo = "Excluir Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$botao_2 = $_POST["botao_2"];
$id_w = $_POST["id_w"];
$codigo_pessoa_w = $_POST["codigo_pessoa_w"];
$pagina_mae = $_POST["pagina_mae"];

$pesquisar_por_busca = $_POST["pesquisar_por_busca"];
$nome_busca = $_POST["nome_busca"];
$cpf_busca = $_POST["cpf_busca"];
$cnpj_busca = $_POST["cnpj_busca"];
$fantasia_busca = $_POST["fantasia_busca"];
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_pessoa = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa WHERE codigo='$id_w'");
$linha_pessoa = mysqli_num_rows ($busca_pessoa);
// ================================================================================================================


// ====== FUNÇÃO FOR ==============================================================================================
for ($x=1 ; $x<=$linha_pessoa ; $x++)
{
$aux_pessoa = mysqli_fetch_row($busca_pessoa);
}
// ================================================================================================================


// ====== DADOS DO CADASTRO =======================================================================================
$nome_form = $aux_pessoa[1];
$tipo_pessoa_form = $aux_pessoa[2];
$cpf_form = $aux_pessoa[3];
$cnpj_form = $aux_pessoa[4];
$rg_form = $aux_pessoa[5];
$sexo_form = $aux_pessoa[6];
$data_nascimento_form = $aux_pessoa[7];
$endereco_form = $aux_pessoa[8];
$bairro_form = $aux_pessoa[9];
$cidade = $aux_pessoa[10];
$cep_form = $aux_pessoa[11];
$estado = $aux_pessoa[12];
$telefone_1_form = $aux_pessoa[14];
$telefone_2_form = $aux_pessoa[15];
$email_form = $aux_pessoa[17];
$classificacao_1_form = $aux_pessoa[18];
$obs_form = $aux_pessoa[22];
$nome_fantasia_form = $aux_pessoa[24];
$numero_residencia_form = $aux_pessoa[25];
$complemento_form = $aux_pessoa[26];
$estado_registro_form = $aux_pessoa[34];
$codigo_pessoa_form = $aux_pessoa[35];

if ($data_nascimento_form == "1900-01-01" or $data_nascimento_form == "" or empty($data_nascimento_form))
{$data_nascimento_print = "";}
else
{$data_nascimento_print = date('d/m/Y', strtotime($data_nascimento_form));}

$usuario_cadastro_w = $aux_pessoa[28];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_pessoa[30]));
$hora_cadastro_w = $aux_pessoa[29];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_pessoa[31];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_pessoa[33]));
$hora_alteracao_w = $aux_pessoa[32];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_pessoa[36];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_pessoa[37]));
$hora_exclusao_w = $aux_pessoa[38];
$motivo_exclusao_w = $aux_pessoa[39];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{$tipo_pessoa_print = "PESSOA F&Iacute;SICA";}
elseif ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
{$tipo_pessoa_print = "PESSOA JUR&Iacute;DICA";}
else
{$tipo_pessoa_print = "";}
// ================================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_form'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


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
<?php include ("../../includes/submenu_cadastro_pessoas.php"); ?>
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
	<div class="ct_subtitulo_left" style="width:900px">
	<?php echo"<div style='color:#FF0000'>Deseja realmente excluir este cadastro?</div>"; ?>
    </div>

	<div class="ct_subtitulo_right" style="width:300px">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ===========  INÍCIO DO FORMULÁRIO =========== -->
<div style="width:1030px; height:330px; margin:auto; border:1px solid transparent; color:#003466">


<!-- =======  TIPO PESSOA ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        Tipo de Pessoa:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$tipo_pessoa_print</div></div>";
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
	<div style="width:511px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "Raz&atilde;o Social:";}
		else
        {echo "Nome:";}
		?>
        </div>
        
        <div style="width:500px; height:25px; float:left; border:1px solid transparent">
        <?php
		echo"<div style='width:495px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:485px; height:16px; text-align:left; overflow:hidden'><b>$nome_form</b></div></div>";
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CPF / CNPJ ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "CNPJ:";}
		else
        {echo "CPF:";}
		?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
		{
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cnpj_form</div></div>";
		}
		else
		{
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cpf_form</div></div>";
		}
        ?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= RG / IE ================================================================================================ -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
        <?php
		if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
        {echo "IE:";}
		else
        {echo "RG:";}
		?>
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$rg_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= DATA NASCIMENTO ======================================================================================== -->
<?php
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{echo"

	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Data de Nascimento:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$data_nascimento_print</div></div>
		</div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= SEXO =================================================================================================== -->
<?php
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{echo"

	<div style='width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:167px; height:17px; border:1px solid transparent; float:left'>
        Sexo:
        </div>
        
        <div style='width:167px; height:25px; float:left; border:1px solid transparent'>
		<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; width:150px; height:16px; text-align:center; overflow:hidden'>$sexo_form</div></div>
		</div>
	</div>";}
?>
<!-- ================================================================================================================ -->


<!-- ======= NOME FANTASIA ========================================================================================== -->
	<?php
    if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
    {echo "
	<div style='width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left'>
        <div class='form_rotulo' style='width:334px; height:17px; border:1px solid transparent; float:left'>
		Nome Fantasia:
        </div>

        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
        <div style='margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden'>$nome_fantasia_form</div></div>
        </div>
	</div>";}
    ?>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 1 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 1:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_1_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Telefone 2:
        </div>
        
        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_2_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Endere&ccedil;o:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$endereco_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO ================================================================================================= -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		N&uacute;mero:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$numero_residencia_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= BAIRRO ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Bairro/Distrito:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$bairro_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= ESTADO =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Estado:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$estado</div></div>";
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CIDADE =================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Cidade:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cidade</div></div>";
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- =======  COMPLEMENTO ============================================================================================== -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Complemento:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$complemento_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CEP ==================================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		CEP:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
        <div style="width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$cep_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= E-MAIL ================================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		E-mail:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$email_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= OBSERVAÇÃO ============================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Observa&ccedil;&atilde;o:
        </div>

        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <div style="width:324px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE">
        <div style="margin-top:4px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$obs_form" ?></div></div>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= CLASSIFICACAO ========================================================================================== -->
	<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
		Classifica&ccedil;&atilde;o:
        </div>

        <div style="width:167px; height:25px; float:left; border:1px solid transparent">
		<?php
		echo"<div style='width:153px; height:25px; border:1px solid #999; float:left; font-size:12px; text-align:center; background-color:#EEE'>
		<div style='margin-top:4px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$classificacao_print</div></div>";
		?>
        </div>
	</div>
<!-- ================================================================================================================ -->


<!-- ======= Motivo Exclusão ============================================================================================= -->
	<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
        <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
		Motivo da Exclus&atilde;o:
        </div>
        
        <div style="width:334px; height:25px; float:left; border:1px solid transparent">
        <form action="<?php echo"$servidor/$diretorio_servidor/cadastros/pessoas/$pagina_mae.php"; ?>" method="post">
        <input type="text" name="motivo_exclusao" class="form_input" maxlength="150" id='ok' 
        onkeydown="if (getKey(event) == 13) return false;" style="width:320px; text-align:left; padding-left:5px" value="<?php echo"$motivo_exclusao"; ?>" />
        </div>
	</div>
<!-- ================================================================================================================ -->




</div>
<!-- ===========  FIM DO FORMULÁRIO =========== -->





<!-- ============================================================================================================= -->
<div style="height:60px; width:1270px; border:0px solid #999; margin:auto; text-align:center">
<?php
echo"
<div id='centro' style='float:left; height:55px; width:435px; text-align:center; border:0px solid #000'></div>";

// ====== BOTAO EXCLUIR ========================================================================================================
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='botao_2' value='$botao_2'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
	<button type='submit' class='botao_2' style='margin-left:10px; width:180px'>Excluir</button>
	</form>
</div>";
// =============================================================================================================================


// ====== BOTAO VOLTAR =========================================================================================================
echo "
<div id='centro' style='float:left; height:55px; width:200px; color:#00F; text-align:center; border:0px solid #000'>
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/$pagina_mae.php' method='post'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='botao_2' value='VOLTAR'>
	<input type='hidden' name='id_w' value='$id_w'>
	<input type='hidden' name='codigo_pessoa_w' value='$codigo_pessoa_w'>
	<input type='hidden' name='pesquisar_por_busca' value='$pesquisar_por_busca'>
	<input type='hidden' name='nome_busca' value='$nome_busca'>
	<input type='hidden' name='cpf_busca' value='$cpf_busca'>
	<input type='hidden' name='cnpj_busca' value='$cnpj_busca'>
	<input type='hidden' name='fantasia_busca' value='$fantasia_busca'>
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