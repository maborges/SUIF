<?php
include ("../../includes/config.php"); 
include ("../../includes/valida_cookies.php");
$pagina = "ficha_selec_produto";
$titulo = "Ficha do Produtor";
$modulo = "compras";
$menu = "ficha_produtor";
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


// ======= RECEBENDO POST =================================================================================
$botao = $_POST["botao"];
$titulo_mae = $_POST["titulo_mae"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$data_hoje = date('d/m/Y');
$nome_form = $_POST["nome_form"] ?? '';
$data_inicial_br = $_POST["data_inicial_busca"];
$data_inicial_busca = ConverteData($_POST["data_inicial_busca"]);
$data_final_br = $_POST["data_final_busca"];
$data_final_busca = ConverteData($_POST["data_final_busca"]);
$fornecedor_pesquisa = $_POST["fornecedor_pesquisa"];
$nome_fornecedor = $_POST["nome_fornecedor"];
$cod_produto_busca = $_POST["cod_produto_busca"];
$cod_tipo_busca = $_POST["cod_tipo_busca"];
$filial_busca = $_POST["filial_busca"];
$usuario_busca = $_POST["usuario_busca"];
$movimentacao_busca = $_POST["movimentacao_busca"];

$preco_max_cafe_print = '';
$preco_max_pimenta_print = '';
$preco_max_cacau_print = '';
// ========================================================================================================


// ====== BUSCA CONFIGURAÇÕES DE FILIAL ==========================================================================
include ('../../includes/conecta_bd.php');
$busca_filial_config = mysqli_query ($conexao, "SELECT * FROM filiais WHERE descricao='$filial_usuario'");
$filial_config = mysqli_fetch_row($busca_filial_config);
include ('../../includes/desconecta_bd.php');
// ===============================================================================================================


// ====== BUSCA FORNECEDOR ========================================================================================
include ("../../includes/conecta_bd.php");
$busca_fornecedor = mysqli_query ($conexao,
"SELECT
	nome,
	tipo,
	cpf,
	cnpj,
	cidade,
	estado,
	telefone_1,
	codigo_pessoa,
	observacao
FROM
	cadastro_pessoa
WHERE
	codigo='$fornecedor_pesquisa'");

$aux_fornecedor = mysqli_fetch_row($busca_fornecedor);
include ("../../includes/desconecta_bd.php");

$pessoa_nome = $aux_fornecedor[0];
$pessoa_tipo = $aux_fornecedor[1];
$pessoa_cpf = $aux_fornecedor[2];
$pessoa_cnpj = $aux_fornecedor[3];
$pessoa_cidade = $aux_fornecedor[4];
$pessoa_estado = $aux_fornecedor[5];
$pessoa_telefone = $aux_fornecedor[6];
$codigo_pessoa = $aux_fornecedor[7];
$obs_pessoa = $aux_fornecedor[8];

if ($pessoa_tipo == "PF" or $pessoa_tipo == "pf")
{$pessoa_cpf_cnpj = "CPF: " . $pessoa_cpf;}
else
{$pessoa_cpf_cnpj = "CNPJ: " . $pessoa_cnpj;}
// ================================================================================================================


// ====== MOSTRA PREÇO ======================================================================================
$mostra_preco = "NAO";
// ==========================================================================================================


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
<?php include ("../../includes/menu_compras.php"); ?>
</div>

<div class="submenu">
<?php include ("../../includes/submenu_compras_ficha_produtor.php"); ?>
</div>


<!-- ====== CENTRO ================================================================================================= -->
<div class="ct_auto">


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:15px"></div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_1">
	<div class="ct_titulo_1">
    <?php echo $titulo; ?>
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left">
	Selecione um Produto
    </div>

	<div class="ct_subtitulo_right">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="width:700px">
    </div>

	<div class="ct_subtitulo_left" style="width:740px; overflow:hidden">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="ct_topo_2">
	<div class="ct_subtitulo_left" style="width:400px">
    </div>

	<div class="ct_subtitulo_right" style="width:1000px; overflow:hidden">
    </div>
</div>
<!-- ============================================================================================================= -->


<!-- ============================================================================================================= -->
<div class="espacamento" style="height:5px"></div>
<!-- ============================================================================================================= -->



<?php
if ($filial_config[4] == "CAFE")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cafe' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='2' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CAF&Eacute; CONILON
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

</div>
";}
// ======================================================================================================





elseif ($filial_config[4] == "PIMENTA")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_pimenta' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='3' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	PIMENTA DO REINO
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>

</div>
";}
// ======================================================================================================





elseif ($filial_config[4] == "CACAU")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cacau' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='4' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CACAU
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>

</div>
";}
// ======================================================================================================





elseif ($filial_config[4] == "CAFE_PIMENTA")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:330px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cafe' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='2' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CAF&Eacute; CONILON
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_pimenta' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='3' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	PIMENTA DO REINO
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>
 

</div>
";}
// ======================================================================================================




elseif ($filial_config[4] == "PIMENTA_CACAU")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:330px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_pimenta' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='3' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	PIMENTA DO REINO
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>

 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cacau' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='4' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CACAU
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>
 

</div>
";}
// ======================================================================================================





elseif ($filial_config[4] == "CAFE_PIMENTA_CACAU")
// ======================================================================================================
{echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:165px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cafe' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='2' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CAF&Eacute; CONILON
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_pimenta' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='3' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	PIMENTA DO REINO
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>
 
 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produto_cacau' />
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
	<input type='hidden' name='data_final_busca' value='$data_final_br' />
	<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
	<input type='hidden' name='cod_produto_busca' value='4' />
	<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
	<input type='hidden' name='filial_busca' value='$filial_busca' />
	<input type='hidden' name='usuario_busca' value='$usuario_busca'>
	<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	CACAU
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>


</div>
";}
// ======================================================================================================



else
{}
?>

<!-- ============================================================================================================= -->
<div class="espacamento" style="height:20px"></div>
<!-- ============================================================================================================= -->



<?php

if ($filial_config[6] == "S")
// ======================================================================================================
{echo "
<div style='height:36px; width:1270px; border:0px solid #0000FF; margin:auto'>

	<div style='height:36px; width:525px; border:0px solid #000; float:left'></div>

	<div style='height:34px; width:250px; border:0px solid #999; float:left'>
        <div style='margin-top:0px; margin-left:0px'>
        <form action='$servidor/$diretorio_servidor/compras/ficha_produtor/ficha_produtor.php' method='post' name='produtos' />
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_br' />
		<input type='hidden' name='data_final_busca' value='$data_final_br' />
		<input type='hidden' name='fornecedor_pesquisa' value='$fornecedor_pesquisa'>
		<input type='hidden' name='cod_tipo_busca' value='$cod_tipo_busca' />
		<input type='hidden' name='filial_busca' value='$filial_busca' />
		<input type='hidden' name='usuario_busca' value='$usuario_busca'>
		<input type='hidden' name='movimentacao_busca' value='$movimentacao_busca'>
        
        <select name='cod_produto_busca' class='produto_2' onchange='document.produtos.submit()' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:220px; height:32px; font-size:14px; text-align:center' />
        <option>(Outros Produtos)</option>";

		include ('../../includes/conecta_bd.php');
        $busca_produto_list = mysqli_query ($conexao, "SELECT codigo, descricao FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
        $linhas_produto_list = mysqli_num_rows ($busca_produto_list);
		include ("../../includes/desconecta_bd.php");

        for ($j=1 ; $j<=$linhas_produto_list ; $j++)
        {
			$aux_produto_list = mysqli_fetch_row ($busca_produto_list);	
			if ($aux_produto_list[0] == $cod_produto_form)
			{
			echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
			}
			else
			{
			echo "<option value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
			}
        }

	echo "
        </select>
        </form>
        </div>
	</div>

</div>";
}
// ======================================================================================================

else
{}

?>








</div>
<!-- ====== FIM DIV CT ========================================================================================== -->




<!-- ====== RODAPÉ =============================================================================================== -->
<div class="rdp_1">
<?php //include ("../../includes/rodape.php"); ?>
</div>


<!-- ====== FIM ================================================================================================== -->
<?php //include ("../../includes/desconecta_bd.php"); ?>
</body>
</html>