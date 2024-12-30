<?php
include('../../includes/config.php');
include('../../includes/conecta_bd.php');
include('../../includes/valida_cookies.php');
include('../../includes/desconecta_bd.php');
$pagina = 'busca_pessoa_popup';
$titulo = 'Pesquisa Pessoa';
$menu = 'produtos';
$modulo = 'compras';

include('../../includes/head.php');
?>


<!-- ==================================   T Í T U L O   D A   P Á G I N A   ====================================== -->
<title>
	<?php echo "$titulo"; ?>
</title>


<!-- =======================================   J A V A   S C R I P T   =========================================== -->
<script type="text/javascript">
	<?php include('../../includes/javascript.php'); ?>
</script>
</head>


<!-- =============================================   I N Í C I O   =============================================== -->

<body onload="javascript:foco('ok');">


	<dialog open>
		<!-- =============================================   C E N T R O   =============================================== -->
		<div id="centro" style="width:670px; height:auto; border:1px solid #999; margin:auto; margin-top:15px; 			border-radius:20px; background-color:#FFF">

			<div id="centro" style="width:660px; border:0px solid #000; margin:auto">

				<div id="espaco_2" style="width:650px"></div>

				<div id="centro" style="height:30px; width:650px; border:0px solid #000; color:#003466; font-size:12px">
					<!-- &#160;&#160;&#8226; <b>Pesquisa - Pessoa F&iacute;sica</b> -->
				</div>

				<div id='centro' style='width:650px; height:auto; border:0px solid #999; margin:auto'>
					<div id='centro' style='width:640px; height:auto; border:1px solid #999; border-radius:10px; margin:auto'>

						<?php
						$nome = $_POST["nome"] ?? '';
						$cpf = $_POST["cpf"] ?? '';
						$cnpj = $_POST["cnpj"] ?? '';
						$tipo_pessoa = $_POST["tipo_pessoa"] ?? '';
						$mostra_inativo = $_POST["mostra_inativo"] ?? '';
						?>

						<div id="centro" style="width:660px; height:35px; border:0px solid #999; color:#666; font-size:12px">
							<div id="espaco_1" style="width:650px; float:left; border:0px solid #999"></div>
							<div id="espaco_1" style="width:650px; float:left; border:0px solid #999"></div>
							<div id="centro" style="width:20px; float:left; height:20px; border:0px solid #999"></div>

							<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/forma_pagamento/busca_pessoa_popup.php" method="post" />
							<input type='hidden' name='tipo_pessoa' value='pf' />
							<i>Nome:</i><input type="text" name="nome" id="ok" size="45" maxlength="50" style="color:#0000FF" value="<?php echo "$nome"; ?>" />
							<input type="image" src="<?php echo "$servidor/$diretorio_servidor"; ?>/imagens/botoes/buscar.png" align="absmiddle" height="20px" />

						</div>
					</div>
				</div>



				<div id="centro" style="height:5px; width:650px; border:0px solid #000; color:#666; font-size:12px">
					<div id="centro" style="width:200px; float:left; height:5px; border:0px solid #999">
						</form>
					</div>

					<div id="centro" style="width:300px; float:right; height:5px; border:0px solid #999; text-align:right">
						<?php

						if ($nome != "") {
							include('../../includes/conecta_bd.php');
							$busca_pessoa = mysqli_query($conexao, "SELECT cadastro_favorecido.codigo, cadastro_favorecido.banco, cadastro_favorecido.agencia, cadastro_favorecido.conta, cadastro_favorecido.tipo_conta, cadastro_favorecido.nome, cadastro_favorecido.nome_banco FROM cadastro_favorecido WHERE cadastro_favorecido.estado_registro='ATIVO' AND cadastro_favorecido.nome LIKE '%$nome%' ORDER BY cadastro_favorecido.nome");

							//	$busca_pessoa = mysqli_query ($conexao, "SELECT cadastro_pessoa.nome, cadastro_favorecido.codigo, cadastro_favorecido.agencia, cadastro_favorecido.conta, cadastro_favorecido.tipo_conta, cadastro_banco.apelido FROM cadastro_pessoa, cadastro_favorecido, cadastro_banco WHERE cadastro_pessoa.estado_registro='ATIVO' AND cadastro_pessoa.nome LIKE '%$nome%' AND cadastro_pessoa.codigo_pessoa=cadastro_favorecido.codigo_pessoa AND cadastro_favorecido.banco=cadastro_banco.numero ORDER BY cadastro_pessoa.nome");


							include('../../includes/desconecta_bd.php');
							$linha_pessoa = mysqli_num_rows($busca_pessoa);
						} else {
							$busca_pessoa = 0;
							$linha_pessoa = 0;
						}


						?>
					</div>
				</div>

				<?php
				if ($nome == '' and $cpf == '' and $cnpj == '') {
					echo "<div id='centro' style='height:145px; font-style:normal; width:660px; margin:auto; border:0px solid #999'>
					<div id='centro' style='height:auto; font-style:normal; width:650px; margin:auto; border:0px solid #999; border-radius:10px'>";
				} else {
					echo "<div id='centro' style='height:auto; font-style:normal; width:660px; margin:auto; border:0px solid #999'>
					<div id='centro' style='height:auto; font-style:normal; width:650px; margin:auto; border:0px solid #999; border-radius:10px'>";
				}
				?>




				<div id="centro" style="height:2px; width:650px; border:0px solid #000; color:#666; font-size:14px">
					<!-- &#160;&#160;&#8226; --> <i><!-- Resultado da Busca --></i>
				</div>


				<table id="tabela_4" style="color:#00F; font-size:10px; border: 0px;">

					<script>
						function retorna(retorno) {
							window.opener.document.forma_pagamento.representante.value = retorno;
							window.self.close();
						}
					</script>


					<?php
					for ($x = 1; $x <= $linha_pessoa; $x++) {
						$aux_pessoa = mysqli_fetch_row($busca_pessoa);

						$cod_favorecido_w = $aux_pessoa[0];
						$banco_w = $aux_pessoa[1];
						$agencia_w = $aux_pessoa[2];
						$conta_w = $aux_pessoa[3];
						$tipo_conta_w = $aux_pessoa[4];
						$nome_pessoa_w = $aux_pessoa[5];
						$nome_banco_w = $aux_pessoa[6];



						if ($tipo_conta_w == "poupanca") {
							$tipo_conta_w = "(Poupan&ccedil;a)";
						} else {
							$tipo_conta_w = "";
						}

						if (empty($banco_w)) {
							$dados_bancarios = "";
						} else {
							$dados_bancarios = "&#160;&#160;(" . $banco_w . " " . $nome_banco_w . " ) &#160;&#160;AG:" . $agencia_w . " &#160;&#160;N&ordm;: " . $conta_w . " &#160;&#160;" . $tipo_conta_w;
						}

						echo "<tr style='color:#00F'>
							<td width='640px' align='left'>
							<a href=javascript:retorna('$cod_favorecido_w'); style='text-decoration:none; color:#00F'>&#160;&#160;$cod_favorecido_w - $nome_pessoa_w $dados_bancarios</a></td>";
					}

					if ($linha_pessoa == 0 and ($nome != '' or $cpf != '')) {
						echo "<tr style='color:#F00; font-size:11px'>
							<td width='829px' align='left'>&#160;&#160;<i>Nenhum cadastro encontrado.</i></td></tr>";
					}




					?>


				</table>
				<div id="centro" style="width:650px; height:20px; border:0px solid #039"></div>
			</div>
			<div id="centro" style="width:650px; height:95px; border:0px solid #039; font-size:11px; color:#999">
				<div id="centro" style="width:650px; height:20px; border:0px solid #039; font-size:11px; color:#999"></div>
			</div>
			<!-- ======================================================================================================== -->
		</div><!-- FIM DIV centro_3 -->

		</div></div>

	</dialog>
	<!-- =============================================   F  I  M   =================================================== -->
	<?php include('../../includes/desconecta_bd.php'); ?>
</body>

</html>