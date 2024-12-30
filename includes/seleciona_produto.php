<?php
// ======= RECEBENDO POST ===================================================================================
$numero_romaneio = $_POST["numero_romaneio"] ?? '';
$numero_compra = $_POST["numero_compra"] ?? '';
$numero_venda = $_POST["numero_venda"] ?? '';
$botao = $_POST["botao"] ?? '';

$pagina_mae = $_POST["pagina_mae"] ?? '';
$pagina_filha = $_POST["pagina_filha"] ?? '';
$data_inicial_busca = $_POST["data_inicial_busca"] ?? '';
$data_final_busca = $_POST["data_final_busca"] ?? '';
$fornecedor_busca = $_POST["fornecedor_busca"] ?? '';
$cod_produto_busca = $_POST["cod_produto_busca"] ?? '';
$cod_seleciona_produto = $_POST["cod_seleciona_produto"] ?? '';
$numero_romaneio_busca = $_POST["numero_romaneio_busca"] ?? '';
$situacao_romaneio_busca = $_POST["situacao_romaneio_busca"] ?? '';
$forma_pesagem_busca = $_POST["forma_pesagem_busca"] ?? '';
$preco_max_cafe_print = '';
$preco_max_pimenta_print = '';
$preco_max_cacau_print = '';
$mostra_preco = $_POST['mostra_preco'] ?? '';

if ($botao == "ALTERAR_PRODUTO") {
	$pagina_destino = "editar_3_formulario";
} else {
	$pagina_destino = "cadastro_2_selec_fornecedor";
}
// ==========================================================================================================

// ====== MOSTRA PREÇO ======================================================================================
if ($filial_config[5] == "S" and $mostra_preco != "NAO") {
	$mostra_preco = "SIM";
} else {
	$mostra_preco = "NAO";
}
// ==========================================================================================================


include('../../includes/conecta_bd.php');


// ====== BUSCA PREÇO CAFE ===================================================================================
$busca_cafe = mysqli_query($conexao, "SELECT codigo, preco_compra_maximo, produto_print FROM cadastro_produto WHERE codigo='2'");
$aux_cafe = mysqli_fetch_row($busca_cafe);

$cod_cafe = $aux_cafe[0];
$cafe_print = $aux_cafe[2];
$preco_max_cafe = $aux_cafe[1];
if ($mostra_preco == "SIM" and $modulo == "compras") {
	$preco_max_cafe_print = "R$ " . number_format($aux_cafe[1], 2, ",", ".");
} else {
	$preco_max_cafe_print = "";
}
// ======================================================================================================


// ====== BUSCA PREÇO PIMENTA ===================================================================================
$busca_pimenta = mysqli_query($conexao, "SELECT codigo, preco_compra_maximo, produto_print FROM cadastro_produto WHERE codigo='3'");
$aux_pimenta = mysqli_fetch_row($busca_pimenta);

$cod_pimenta = $aux_pimenta[0];
$pimenta_print = $aux_pimenta[2];
$preco_max_pimenta = $aux_pimenta[1];
if ($mostra_preco == "SIM" and $modulo == "compras") {
	$preco_max_pimenta_print = "R$ " . number_format($aux_pimenta[1], 2, ",", ".");
} else {
	$preco_max_pimenta_print = "";
}
// ======================================================================================================


// ====== BUSCA PREÇO CACAU ===================================================================================
$busca_cacau = mysqli_query($conexao, "SELECT codigo, preco_compra_maximo, produto_print FROM cadastro_produto WHERE codigo='4'");
$aux_cacau = mysqli_fetch_row($busca_cacau);

$cod_cacau = $aux_cacau[0];
$cacau_print = $aux_cacau[2];
$preco_max_cacau = $aux_cacau[1];
if ($mostra_preco == "SIM" and $modulo == "compras") {
	$preco_max_cacau_print = "R$ " . number_format($aux_cacau[1], 2, ",", ".");
} else {
	$preco_max_cacau_print = "";
}
// ======================================================================================================



include("../../includes/desconecta_bd.php");






if ($filial_config[4] == "CAFE")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cafe' />
    <input type='hidden' name='cod_produto_form' value='$cod_cafe' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cafe' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cafe_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

</div>
";
}
// ======================================================================================================





elseif ($filial_config[4] == "PIMENTA")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_pimenta' />
    <input type='hidden' name='cod_produto_form' value='$cod_pimenta' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_pimenta' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$pimenta_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>

</div>
";
}
// ======================================================================================================





elseif ($filial_config[4] == "CACAU")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:495px; border:0px solid #0000FF; float:left'></div>

 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cacau' />
    <input type='hidden' name='cod_produto_form' value='$cod_cacau' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cacau' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cacau_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>

</div>
";
}
// ======================================================================================================





elseif ($filial_config[4] == "CAFE_PIMENTA")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:330px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cafe' />
    <input type='hidden' name='cod_produto_form' value='$cod_cafe' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cafe' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cafe_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_pimenta' />
    <input type='hidden' name='cod_produto_form' value='$cod_pimenta' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_pimenta' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$pimenta_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>
 

</div>
";
}
// ======================================================================================================




elseif ($filial_config[4] == "PIMENTA_CACAU")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:330px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_pimenta' />
    <input type='hidden' name='cod_produto_form' value='$cod_pimenta' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_pimenta' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$pimenta_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>

 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cacau' />
    <input type='hidden' name='cod_produto_form' value='$cod_cacau' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cacau' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cacau_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>
 

</div>
";
}
// ======================================================================================================





elseif ($filial_config[4] == "CAFE_PIMENTA_CACAU")
// ======================================================================================================
{
	echo "
<div style='height:272px; width:1270px; border:0px solid #0000FF; margin:auto'>

    <div style='height:250px; width:165px; border:0px solid #0000FF; float:left'></div>

	<script>function enviar_cafe(){document.produto_cafe.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cafe' />
    <input type='hidden' name='cod_produto_form' value='$cod_cafe' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cafe' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cafe()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:0px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cafe.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cafe_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cafe_print
    	</div>
    </div>
    </a>

	<script>function enviar_pimenta(){document.produto_pimenta.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_pimenta' />
    <input type='hidden' name='cod_produto_form' value='$cod_pimenta' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_pimenta' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_pimenta()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_pimenta.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$pimenta_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_pimenta_print
    	</div>
    </div>
    </a>
 
 	<script>function enviar_cacau(){document.produto_cacau.submit()}</script>
    <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produto_cacau' />
    <input type='hidden' name='cod_produto_form' value='$cod_cacau' />
	<input type='hidden' name='cod_seleciona_produto' value='$cod_cacau' />
	<input type='hidden' name='numero_compra' value='$numero_compra'>
	<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
	<input type='hidden' name='botao' value='$botao'>
	<input type='hidden' name='pagina_mae' value='$pagina_mae'>
	<input type='hidden' name='pagina_filha' value='$pagina_filha'>
	<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
	<input type='hidden' name='data_final_busca' value='$data_final_busca'>
	<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
	<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
	<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
	<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
	<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
    </form>
	<a href='javascript:enviar_cacau()'>
    <div class='produto' style='height:220px; width:280px; float:left; margin-left:50px; margin-top:26px'>
	    <div style='height:100px; width:240px; border:0px solid #999; float:left; margin-left:20px; margin-top:20px'>
    	<img src='$servidor/$diretorio_servidor/imagens/produto_cacau.png' style='width:240px'>
    	</div>
	    <div style='height:30px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:22px; text-align:center; color:#003466'>
    	<b>$cacau_print</b>
    	</div>
	    <div style='height:20px; width:240px; float:left; margin-left:20px; margin-top:20px; font-size:14px; text-align:center; color:#999'>
		$preco_max_cacau_print
    	</div>
    </div>
    </a>


</div>
";
}
// ======================================================================================================



else {
}

?>

<!-- ============================================================================================================= -->
<div class="espacamento_20"></div>
<!-- ============================================================================================================= -->



<?php

if ($filial_config[6] == "S")
// ======================================================================================================
{
	echo "
<div style='height:36px; width:1270px; border:0px solid #0000FF; margin:auto'>

	<div style='height:36px; width:525px; border:0px solid #000; float:left'></div>

	<div style='height:34px; width:250px; border:0px solid #999; float:left'>
        <div style='margin-top:0px; margin-left:0px'>
        <form action='$servidor/$diretorio_servidor/$modulo/$menu/$pagina_destino.php' method='post' name='produtos' />
		<input type='hidden' name='numero_compra' value='$numero_compra'>
		<input type='hidden' name='numero_romaneio' value='$numero_romaneio'>
		<input type='hidden' name='botao' value='$botao'>
		<input type='hidden' name='pagina_mae' value='$pagina_mae'>
		<input type='hidden' name='pagina_filha' value='$pagina_filha'>
		<input type='hidden' name='data_inicial_busca' value='$data_inicial_busca'>
		<input type='hidden' name='data_final_busca' value='$data_final_busca'>
		<input type='hidden' name='cod_produto_busca' value='$cod_produto_busca'>
		<input type='hidden' name='fornecedor_busca' value='$fornecedor_busca'>
		<input type='hidden' name='numero_romaneio_busca' value='$numero_romaneio_busca'>
		<input type='hidden' name='situacao_romaneio_busca' value='$situacao_romaneio_busca'>
		<input type='hidden' name='forma_pesagem_busca' value='$forma_pesagem_busca'>
        
        <select name='cod_produto_form' class='produto_2' onchange='document.produtos.submit()' 
        onkeydown='if (getKey(event) == 13) return false;' style='width:220px; height:32px; font-size:14px; text-align:center' />
        <option>(Outros Produtos)</option>";

	include('../../includes/conecta_bd.php');
	$busca_produto_list = mysqli_query($conexao, "SELECT codigo, descricao FROM cadastro_produto WHERE estado_registro='ATIVO' ORDER BY descricao");
	$linhas_produto_list = mysqli_num_rows($busca_produto_list);
	include("../../includes/desconecta_bd.php");

	for ($j = 1; $j <= $linhas_produto_list; $j++) {
		$aux_produto_list = mysqli_fetch_row($busca_produto_list);
		if ($aux_produto_list[0] == $cod_produto_form) {
			echo "<option selected='selected' value='$aux_produto_list[0]'>$aux_produto_list[1]</option>";
		} else {
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

else {
}

?>