<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "cadastro_impressao";
$titulo = "Cadastro de Pessoa";
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ======= RECEBENDO POST =========================================================================================
$botao = $_POST["botao"];
$id_w = $_POST["id_w"];
$pagina_mae = $_POST["pagina_mae"];
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

if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{$cpf_cnpj_print = $cpf_w;}
else
{$cpf_cnpj_print = $cnpj_w;}

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


// ======================================================================================================
if ($estado_registro_form == "EXCLUIDO")
{$estado_registro_print = "<div style='width:230px; height:20px; border:1px solid #F00; font-size:18px; color:#F00; float:right; text-align:center'>
<div style='margin-top:0px'>CADASTRO EXCLU&Iacute;DO</div></div>";}

elseif ($estado_registro_form == "INATIVO")
{$estado_registro_print = "<div style='width:230px; height:20px; border:1px solid #F00; font-size:18px; color:#F00; float:right; text-align:center'>
<div style='margin-top:0px'>CADASTRO INATIVO</div></div>";}

elseif ($estado_registro_form == "ATIVO")
{$estado_registro_print = "<div style='width:230px; height:20px; border:0px solid #F00; font-size:18px; color:#000; float:right' align='right'></div>";}

else
{$estado_registro_print = "<div style='width:230px; height:20px; border:1px solid #F00; font-size:18px; color:#F00; float:right; text-align:center'>
<div style='margin-top:0px'>$estado_registro_print</div></div>";}
// ======================================================================================================


// ======= TIPO DE PESSOA =========================================================================================
if ($tipo_pessoa_form == "PF" or $tipo_pessoa_form == "pf")
{$tipo_pessoa_print = "Pessoa F&iacute;sica";}
elseif ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
{$tipo_pessoa_print = "Pessoa Jur&iacute;dica";}
else
{$tipo_pessoa_print = "";}
// ================================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_form'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ================================================================================================================
include ("../../includes/head_impressao.php");
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
<body onLoad="imprimir()">

<div style="width:745px; border:0px solid #000; float:left">


<div style="width:720px; height:80px; border:0px solid #000; margin-left:25px; margin-top:40px; float:left">

	<!-- ====== LOGOMARCA ====== -->
	<div style="width:310px; height:80px; border:0px solid #000; font-size:18px; float:left; text-align:left">
	<img src="<?php echo"$servidor/$diretorio_servidor"; ?>/imagens/logomarca_pb.png" border="0" height="80px" />
    </div>

	<!-- ====== TÍTULO ========= -->
	<div style="width:400px; height:50px; border:0px solid #000; font-size:26px; margin-top:20px; float:right; text-align:right">
	<?php echo"Cadastro de $tipo_pessoa_print"; ?>
    </div>

</div>


<div style="width:720px; height:50px; border:0px solid #000; margin-left:25px; float:left; text-align:center">

    <div style="width:720px; height:22px; border:0px solid #000; float:left">

        <!-- ====== BLOCO 1 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:left">
        <!-- xxxxxxxxxxxxxxxxxxxxx -->
        </div>

        <!-- ====== BLOCO 2 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:center">
		<!-- xxxxxxxxxxxxxxxxxxxxx -->
        </div>

        <!-- ====== BLOCO 3 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:right" align="right">
		<!-- xxxxxxxxxxxxxxxxxxxxx -->
        </div>
    </div>

    <div style="width:720px; height:22px; border:0px solid #000; float:left">

        <!-- ====== BLOCO 4 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:left">
        <!-- xxxxxxxxxxxxxxxxxxxxx -->
        </div>

        <!-- ====== BLOCO 5 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:left; text-align:center">
        <!-- xxxxxxxxxxxxxxxxxxxxx -->
        </div>

        <!-- ====== BLOCO 6 ====== -->
        <div style="width:235px; height:22px; border:0px solid #000; font-size:18px; float:right" align="right">
        <?php echo"$estado_registro_print"; ?>
        </div>
    </div>

</div>


<!-- ======================================================================================================================================= -->
<div style="width:700px; height:820px; border:0px solid #000; margin-top:5px; margin-left:50px; float:left">




<!-- =======  NOME / RAZAO SOCIAL =================================================================================== -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:500px; height:17px; border:1px solid transparent; float:left">
    <?php
    if ($tipo_pessoa_form == "PJ" or $tipo_pessoa_form == "pj")
    {echo "Raz&atilde;o Social:";}
    else
    {echo "Nome:";}
    ?>
    </div>
    
    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
    <?php
    echo"<div style='width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:317px; height:16px; text-align:left; overflow:hidden'><b>$nome_form</b></div></div>";
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
    echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cnpj_form</div></div>";
    }
    else
    {
    echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cpf_form</div></div>";
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
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$rg_form" ?></div></div>
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
    <div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$data_nascimento_print</div></div>
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
    <div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$sexo_form</div></div>
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
    <div style='width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden'>$nome_fantasia_form</div></div>
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
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_1_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= TELEFONE 2 ============================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Telefone 2:
    </div>
    
    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$telefone_2_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- =======  ENDEREÇO ============================================================================================== -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
    Endere&ccedil;o:
    </div>

    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
    <div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$endereco_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= NUMERO ================================================================================================= -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    N&uacute;mero:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$numero_residencia_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= BAIRRO ==================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    Bairro/Distrito:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$bairro_form" ?></div></div>
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
    echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$estado</div></div>";
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
	$conta_caracter = strlen($cidade);
	if ($conta_caracter <= 16)
    {echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$cidade</div></div>";}
	else
    {echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:9px; text-align:center; background-color:#FFF'>
    <div style='margin-top:2px; margin-left:5px; width:143px; height:23px; text-align:left; overflow:hidden'>$cidade</div></div>";}
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
    <div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$complemento_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= CEP ==================================================================================================== -->
<div style="width:169px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:167px; height:17px; border:1px solid transparent; float:left">
    CEP:
    </div>

    <div style="width:167px; height:25px; float:left; border:1px solid transparent">
    <div style="width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden"><?php echo"$cep_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= E-MAIL ================================================================================================= -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
    E-mail:
    </div>

    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
    <div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$email_form" ?></div></div>
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
    echo"<div style='width:153px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF'>
    <div style='margin-top:6px; margin-left:5px; width:143px; height:16px; text-align:left; overflow:hidden'>$classificacao_print</div></div>";
    ?>
    </div>
</div>
<!-- ================================================================================================================ -->


<!-- ======= OBSERVAÇÃO ============================================================================================= -->
<div style="width:339px; height:50px; border:1px solid transparent; margin-top:10px; float:left">
    <div class="form_rotulo" style="width:334px; height:17px; border:1px solid transparent; float:left">
    Observa&ccedil;&atilde;o:
    </div>

    <div style="width:334px; height:25px; float:left; border:1px solid transparent">
    <div style="width:324px; height:25px; border:1px solid #000; float:left; font-size:12px; text-align:center; background-color:#FFF">
    <div style="margin-top:6px; margin-left:5px; width:314px; height:16px; text-align:left; overflow:hidden"><?php echo"$obs_form" ?></div></div>
    </div>
</div>
<!-- ================================================================================================================ -->








<div style="width:690px; height:370px; border:0px solid #000; float:left"></div>





<!-- ======= DADOS CADASTRO ========================================================================================= -->
<?php
if ($dados_cadastro_w != "")
{echo "
	<div style='width:339px; height:30px; border:1px solid transparent; margin-top:0px; float:left'>
        <div style='width:334px; height:25px; float:left; border:1px solid transparent'>
        <div style='width:324px; height:25px; border:0px solid #000; float:left; color:#999; font-size:10px; text-align:center; background-color:#FFF'>
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
        <div style='width:324px; height:25px; border:0px solid #000; float:left; color:#999; font-size:10px; text-align:center; background-color:#FFF'>
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
        <div style='width:324px; height:25px; border:0px solid #000; float:left; color:#999; font-size:10px; text-align:center; background-color:#FFF'>
        <div style='margin-top:6px; margin-left:7px; width:314px; height:16px; text-align:left; overflow:hidden' title='Motivo da Exclus&atilde;o: $motivo_exclusao_w'>
		<i>$dados_exclusao_w</i></div></div>
        </div>
	</div>";}
?>
<!-- ================================================================================================================ -->









</div>
<!-- ================================================================================================================ -->
















<!-- ======= LINHA ========================================================================================= -->
<div style="width:720px; height:10px; border:0px solid #000; margin-left:25px; margin-top:20px; font-size:17px; float:left"></div>
<div style="width:720px; height:15px; border:0px solid #000; margin-left:25px; font-size:17px; float:left; text-align:center">
<hr style="border:1px solid #999" />
</div>




<!-- ======= RODAPÉ ========================================================================================= -->
<div style="width:720px; height:27px; border:0px solid #000; margin-left:25px; font-size:17px; float:left">
    <div style="width:280px; height:25px; border:0px solid #000; font-size:10px; float:left; text-align:left">
    <?php echo "&copy; $ano_atual_rodape $rodape_slogan_m | $nome_fantasia_m"; ?>
    </div>
    
    <div style="width:140px; height:25px; border:0px solid #000; font-size:10px; float:left; text-align:center">
    <!-- xxxxxxxxxxxxxxxxxxxxx -->
    </div>
    
    <div style="width:140px; height:25px; border:0px solid #000; font-size:10px; float:left; text-align:center">
    <!-- xxxxxxxxxxxxxxxxxxxxx -->
    </div>
    
    <div style="width:150px; height:25px; border:0px solid #000; font-size:10px; float:right; text-align:right">
    <!-- xxxxxxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ========================================================================================================= -->





</div>

</body>
</html>
<!-- ======= FIM ============================================================================================= -->