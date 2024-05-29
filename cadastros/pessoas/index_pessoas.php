<?php
include ("../../includes/config.php"); 
include ("../../includes/conecta_bd.php");
include ("../../includes/valida_cookies.php");
$pagina = "index_pessoas";
$titulo = "Cadastro de Pessoas";	
$modulo = "cadastros";
$menu = "cadastro_pessoas";
// ================================================================================================================


// ====== BUSCA CADASTRO ==========================================================================================
$busca_registro = mysqli_query ($conexao, "SELECT * FROM cadastro_pessoa ORDER BY codigo DESC LIMIT 10");
$linha_registro = mysqli_num_rows ($busca_registro);
// ================================================================================================================


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
<body>


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
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento_15"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo "$titulo"; ?>
    </div>

	<div class="ct_subtitulo_right" style="margin-top:8px">
	<?php
	echo"
	<form action='$servidor/$diretorio_servidor/cadastros/pessoas/cadastro_1_formulario.php' method='post' />
	<button type='submit' class='botao_1' style='float:right'>Novo Cadastro</button>
	</form>";
	?>
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	&Uacute;ltimos cadastros
    </div>

	<div class="ct_subtitulo_right">
	<!-- xxxxxxxxxxxxxxxxx -->
    </div>
</div>
<!-- ============================================================================================================= -->












<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
<!-- ============================================================================================================= -->



<!-- ============================================================================================================= -->
<?php
if ($linha_registro == 0)
{echo "
<div style='height:210px'>
<div class='espacamento_30'></div>";}

else
{echo "
<div class='ct_relatorio'>
<div class='espacamento_10'></div>

<table class='tabela_cabecalho'>
<tr>
<td width='65px'>C&oacute;digo</td>
<td width='65px'>Sankhya</td>
<td width='300px'>Nome</td>
<td width='200px'>CPF/CNPJ</td>
<td width='150px'>Telefone</td>
<td width='250px'>Cidade/UF</td>
<td width='130px'>Status Cadastro</td>
</tr>
</table>";}


echo "<table class='tabela_geral' style='font-size:12px'>";


// ====== FUNÇÃO FOR ===================================================================================
for ($x=1 ; $x<=$linha_registro ; $x++)
{
$aux_registro = mysqli_fetch_row($busca_registro);

// ====== DADOS DO CADASTRO ============================================================================
$id_w = $aux_registro[0];
$idSankhya_w = $aux_registro[41];
$nome_w = $aux_registro[1];
$tipo_w = $aux_registro[2];
$cpf_w = $aux_registro[3];
$cnpj_w = $aux_registro[4];
$rg_w = $aux_registro[5];
$sexo_w = $aux_registro[6];
$data_nascimento_w = $aux_registro[7];
$endereco_w = $aux_registro[8];
$bairro_w = $aux_registro[9];
$cidade_w = $aux_registro[10];
$cep_w = $aux_registro[11];
$estado_w = $aux_registro[12];
$ponto_referencia_w = $aux_registro[13];
$telefone_1_w = $aux_registro[14];
$telefone_2_w = $aux_registro[15];
$email_w = $aux_registro[17];
$classificacao_1_w = $aux_registro[18];
$observacao_w = $aux_registro[22];
$nome_fantasia_w = $aux_registro[24];
$numero_residencia_w = $aux_registro[25];
$complemento_w = $aux_registro[26];
$estado_registro_w = $aux_registro[34];
$codigo_pessoa_w = $aux_registro[35];

if ($tipo_w == "PF" or $tipo_w == "pf")
{$cpf_cnpj_print = $cpf_w;}
else
{$cpf_cnpj_print = $cnpj_w;}

$usuario_cadastro_w = $aux_registro[28];
if ($usuario_cadastro_w == "")
{$dados_cadastro_w = "";}
else
{
$data_cadastro_w = date('d/m/Y', strtotime($aux_registro[30]));
$hora_cadastro_w = $aux_registro[29];
$dados_cadastro_w = " &#13; Cadastrado por: $usuario_cadastro_w $data_cadastro_w $hora_cadastro_w";
}

$usuario_alteracao_w = $aux_registro[31];
if ($usuario_alteracao_w == "")
{$dados_alteracao_w = "";}
else
{
$data_alteracao_w = date('d/m/Y', strtotime($aux_registro[33]));
$hora_alteracao_w = $aux_registro[32];
$dados_alteracao_w = " &#13; Editado por: $usuario_alteracao_w $data_alteracao_w $hora_alteracao_w";
}

$usuario_exclusao_w = $aux_registro[36];
if ($usuario_exclusao_w == "")
{$dados_exclusao_w = "";}
else
{
$data_exclusao_w = date('d/m/Y', strtotime($aux_registro[37]));
$hora_exclusao_w = $aux_registro[38];
$dados_exclusao_w = " &#13; Exclu&iacute;do por: $usuario_exclusao_w $data_exclusao_w $hora_exclusao_w";
}
// ======================================================================================================


// ======= BUSCA CLASSIFICAÇÃO ==================================================================================
$busca_classificacao = mysqli_query ($conexao, "SELECT * FROM classificacao_pessoa WHERE codigo='$classificacao_1_w'");
$aux_bcl = mysqli_fetch_row($busca_classificacao);
$classificacao_print = $aux_bcl[1];
// ================================================================================================================


// ====== RELATORIO ========================================================================================
if ($estado_registro_w == "INATIVO")
{echo "<tr class='tabela_4' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
elseif ($estado_registro_w == "EXCLUIDO")
{echo "<tr class='tabela_5' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}
else
{echo "<tr class='tabela_1' title=' Nome: $nome_w &#13; ID Cadastro: $id_w &#13; Status Cadastro: $estado_registro_w &#13; Classifica&ccedil;&atilde;o: $classificacao_print $dados_cadastro_w $dados_alteracao_w $dados_exclusao_w'>";}

echo "
<td width='65px' align='left'><div style='height:14px; margin-left:7px'>$id_w</div></td>
<td width='65px' align='left'><div style='height:14px; margin-left:7px'>$idSankhya_w</div></td>
<td width='300px' align='left'><div style='height:14px; margin-left:7px; overflow:hidden'>$nome_w</div></td>
<td width='200px' align='center'>$cpf_cnpj_print</td>
<td width='150px' align='center'>$telefone_1_w</td>
<td width='250px' align='center'>$cidade_w/$estado_w</td>
<td width='130px' align='center'>$estado_registro_w</td>";
}

echo "</tr></table>";
// =================================================================================================================



// =================================================================================================================
if ($linha_registro == 0 and $botao == "BUSCAR")
{echo "
<div class='espacamento_30'></div>
<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
<i>Nenhum cadastro encontrado.</i></div>";}
// =================================================================================================================
?>


<!-- ============================================================================================================= -->
<div class="espacamento_30"></div>
<!-- ============================================================================================================= -->



</div>
<!-- ====== FIM DIV CT_RELATORIO =============================================================================== -->



<!-- ============================================================================================================= -->
<div class="espacamento_40"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	<!-- ======== Observações ============= -->
	</div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento_10"></div>
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