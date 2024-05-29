<?php
include("../../includes/config.php");
include("../../includes/conecta_bd.php");
include("../../includes/valida_cookies.php");

$pagina = "situacao_compra_produtor";
$titulo = "Manutenção Situação Compra";
$modulo = "compras";
$menu = "compras";
$conta = 0;

// ======= RECEBENDO POST ===========================================================================
$botao = $_POST["botao"] ?? '';
$botao_2 = $_POST["botao_2"] ?? '';
$id_w = $_POST["id_w"] ?? '';
$pagina_mae = $_POST["pagina_mae"] ?? '';
$msg = $_POST["msg"] ?? '';

$pesquisar_por_busca = $_POST["pesquisar_por_busca"] ?? '';
$pesquisar_situacao  = $_POST["pesquisarSituacao"] ?? '';
$nome_busca = $_POST["nome_busca"] ?? '';
$cpf_busca = $_POST["cpf_busca"] ?? '';
$cnpj_busca = $_POST["cnpj_busca"] ?? '';
$fantasia_busca = $_POST["fantasia_busca"] ?? '';

$nome_w = $_POST["nome_w"] ?? '';
$cpf_cnpj_w = $_POST["cpf_cnpj_w"] ?? '';
$telefone_1_w = $_POST["telefone_1_w"] ?? '';
$cidade_w = $_POST["cidade_w"] ?? '';

$usuario_alteracao = $nome_usuario_print;
$hora_alteracao = date('G:i:s', time());
$data_alteracao = date('Y-m-d', time());
$motivo_exclusao = $_POST["motivo_exclusao"] ?? '';

$permite_alterar = $permissao[147] == 'S';

// Indexa campos da query pessoa
$fld_id 			= "codigo";
$fld_nome 			= "nome";
$fld_tipo 			= "tipo";
$fld_cpf 			= "cpf";
$fld_cnpj 			= "cnpj";
$fld_cidade 		= "cidade";
$fld_estado        	= "estado";
$fld_telefone      	= "telefone_1";
$fld_classificacao	= "classificacao_1";
$fld_situacao      	= "situacao_compra";

// ====================================================================================================


// ======= MYSQL FILTRO DE BUSCA ==================================================================================
if ($pesquisar_por_busca == "CPF") {
	$mysql_busca = "cpf='$cpf_busca' ORDER BY nome";
} elseif ($pesquisar_por_busca == "CNPJ") {
	$mysql_busca = "cnpj='$cnpj_busca' ORDER BY nome";
} elseif ($pesquisar_por_busca == "FANTASIA") {
	$mysql_busca = "nome_fantasia LIKE '%$fantasia_busca%' ORDER BY nome";
} else {
	$pesquisar_por_busca = "NOME";
	$mysql_busca = "nome LIKE '%$nome_busca%' ORDER BY nome";
}

if ($pesquisar_situacao != 9) {
	$mysql_busca = ' situacao_compra = ' . $pesquisar_situacao . ' AND ' . $mysql_busca;
}

// ====== BUSCA CADASTRO ===================================================================
if ($botao == "BUSCAR" and ($nome_busca != "" or $cpf_busca != "" or $cnpj_busca != "" or $fantasia_busca != "")) {
	$busca_pessoa = mysqli_query(
		$conexao,
		"SELECT codigo, nome, tipo, cpf, cnpj, cidade, estado, telefone_1, classificacao_1, situacao_compra 
		   FROM cadastro_pessoa 
		  WHERE estado_registro='ATIVO' 
			AND $mysql_busca"
	);
	$linha_pessoa = mysqli_num_rows($busca_pessoa);
} else {
	$linha_pessoa = 0;
}

include("../../includes/head.php");
?>

<title><?php echo "$titulo"; ?></title>

<!-- ====== MÁSCARAS JQUERY ====== -->
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/includes/js/jquery.maskedinput-1.3.min.js"></script>

<script>
	jQuery(function($) {
		// MASK
		$("#cpf").mask("999.999.999-99");
		$("#cnpj").mask("99.999.999/9999-99");
	});
</script>

<style>
	dialog {
		padding: 30px;
		border: 0px;
		border-radius: 7px;
		box-shadow: 0 0 7px black;
		flex-direction: column;
	}

	dialog::blackdrop {
		background-color: rgba(0, 0, 0, 0.9);
	}
</style>

</head>

<body onload="javascript:foco('ok');">

	<!-- CONSULTA O HISTÓRICO -->
	<dialog id="dlgHistorico" class="dialog">
		<div class="modal-body"></div>
		<div>
			<button id="btnFecharHistorico" class="botao_1" onclick="fechaHistorico()">Fechar</button>
		</div>
	</dialog>

	<!-- Altera Situação -->
	<dialog id="dlgAlteraSituacao" class="dialog">
		<form id="frmAlteraSituacao">
			<input type="hidden" id="produtorid">
			<input type="hidden" id="situacaoid">
			<div>
				<h5>Altera Situação</h5>
				<hr>
			</div>
			<label style="font-size:12px">Situação:</label>
			<br>
			<select id='selectSituacao' style='color:#0000FF; width:149px; font-size:12px; text-align:left' data-userid=$userid>
				<option value=0>LIBERADA</option>
				<option value=1>ANÁLISE</option>
				<option value=2>BLOQUEADA</option>
			</select>

			<br>
			<br>

			<label for="fname" style="font-size:12px">Motivo:</label>
			<br>
			<textarea id="descricaoMotivo" name="descricaoMotivo" placeholder="Motivo" maxlength="100" rows=2 cols=50></textarea>
			<hr>

			<div>
				<button id="btnSalvaAlteracao" class="botao_1" formmethod="dialog">Confirmar</button>
				<button id="btnCancelaAlteracao" style="margin: 0 30px;" class="botao_1" formmethod="dialog">Cancelar</button>
			</div>
		</form>
	</dialog>


	<!-- ====== TOPO ================================================================================== -->
	<div class="topo">
		<?php include("../../includes/topo.php"); ?>
	</div>

	<!-- ====== MENU ===================================================================================== -->
	<div class="menu">
		<?php include("../../includes/menu_compras.php"); ?>
	</div>

	<div class="submenu">
		<?php include("../../includes/submenu_compras_compras.php"); ?>
	</div>


	<!-- ====== CENTRO ======================================================================= -->
	<div class="ct_auto">
		<div class="espacamento_15"></div>

		<div class="ct_topo_1">
			<div class="ct_titulo_1">
				<?php echo "$titulo"; ?>
			</div>

			<div class="ct_subtitulo_right" style="margin-top:8px">
				<?php
				if ($linha_pessoa == 1) {
					echo "$linha_pessoa Cadastro";
				} elseif ($linha_pessoa > 1) {
					echo "$linha_pessoa Cadastros";
				} else {
					echo "";
				}
				?>
			</div>
		</div>
		<div class="ct_topo_2">
			<div class="ct_subtitulo_left">
				<?php echo "$msg"; ?>
			</div>

			<div class="ct_subtitulo_right">
			</div>
		</div>

		<div class="pqa" style="height:63px">

			<div style="width:50px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<form name="tipo_pesquisa" action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/compras/situacao_compra_produtor.php" method="post" />
				<input type="hidden" name="botao" value="TIPO_PESQUISA" />
			</div>
			<div style="width:190px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:188px; height:17px; border:1px solid transparent; float:left">
					Pesquisar por:
				</div>

				<div style="width:188px; height:25px; float:left; border:1px solid transparent">
					<select name="pesquisar_por_busca" class="form_select" onkeydown="if (getKey(event) == 13) return false;" onchange="document.tipo_pesquisa.submit()" style="width:160px" />
					<?php
					if ($pesquisar_por_busca == "NOME") {
						echo "<option value='NOME' selected='selected'>Nome / Raz&atilde;o Social</option>";
					} else {
						echo "<option value='NOME'>Nome / Raz&atilde;o Social</option>";
					}

					if ($pesquisar_por_busca == "CPF") {
						echo "<option value='CPF' selected='selected'>CPF</option>";
					} else {
						echo "<option value='CPF'>CPF</option>";
					}

					if ($pesquisar_por_busca == "CNPJ") {
						echo "<option value='CNPJ' selected='selected'>CNPJ</option>";
					} else {
						echo "<option value='CNPJ'>CNPJ</option>";
					}

					if ($pesquisar_por_busca == "FANTASIA") {
						echo "<option value='FANTASIA' selected='selected'>Nome Fantasia</option>";
					} else {
						echo "<option value='FANTASIA'>Nome Fantasia</option>";
					}

					?>
					</select>
					</form>
				</div>
			</div>

			<form action="<?php echo "$servidor/$diretorio_servidor"; ?>/compras/compras/situacao_compra_produtor.php" method="post" />
			<input type='hidden' name='botao' value='BUSCAR' />
			<input type='hidden' name='pesquisar_por_busca' value='<?php echo "$pesquisar_por_busca"; ?>' />

			<?php
			if ($pesquisar_por_busca == "NOME") {
				echo "
				<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
					Nome / Raz&atilde;o Social:
					</div>
					
					<div style='width:328px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='nome_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
					style='width:300px; text-align:left; padding-left:5px' value='$nome_busca' />
					</div>
				</div>";
			} elseif ($pesquisar_por_busca == "CPF") {
				echo "
				<div style='width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:178px; height:17px; border:1px solid transparent; float:left'>
					CPF:
					</div>
					
					<div style='width:178px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='cpf_busca' class='form_input' maxlength='14' id='cpf' 
					style='width:150px; text-align:left; padding-left:5px' value='$cpf_busca' />
					</div>
				</div>";
			} elseif ($pesquisar_por_busca == "CNPJ") {
				echo "
				<div style='width:180px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:178px; height:17px; border:1px solid transparent; float:left'>
					CNPJ:
					</div>
					
					<div style='width:178px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='cnpj_busca' class='form_input' maxlength='18' id='cnpj' 
					style='width:150px; text-align:left; padding-left:5px' value='$cnpj_busca' />
					</div>
				</div>";
			} elseif ($pesquisar_por_busca == "FANTASIA") {
				echo "
				<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
					Nome Fantasia:
					</div>
					
					<div style='width:328px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='fantasia_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
					style='width:300px; text-align:left; padding-left:5px' value='$fantasia_busca' />
					</div>
				</div>";
			} else {
				echo "
				<div style='width:330px; height:50px; border:1px solid transparent; margin-top:6px; float:left'>
					<div class='form_rotulo' style='width:328px; height:17px; border:1px solid transparent; float:left'>
					Nome / Raz&atilde;o Social:
					</div>
					
					<div style='width:328px; height:25px; float:left; border:1px solid transparent'>
					<input type='text' name='nome_busca' class='form_input' id='ok' onBlur='alteraMaiusculo(this)' 
					style='width:300px; text-align:left; padding-left:5px' value='$nome_busca' />
					</div>
				</div>";
			}

			?>

			<div style="width:190px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:188px; height:17px; border:1px solid transparent; float:left">
					Situação:
				</div>

				<div style="width:188px; height:25px; float:left; border:1px solid transparent">
					<select name="pesquisarSituacao" class="form_select"  style="width:160px" />
					<option value='9'>TODAS</option>
					<option value='0'>LIBERADA</option>
					<option value='1'>ANÁLISE</option>
					<option value='2'>BLOQUEADA</option>

					</select>
					
				</div>
			</div>

			<div style=" width:100px; height:50px; border:1px solid transparent; margin-top:6px; float:left">
				<div class="form_rotulo" style="width:95px; height:17px; border:1px solid transparent; float:left">
					<!-- Botão: -->
				</div>

				<div style="width:95px; height:25px; float:left; border:1px solid transparent">
					<button type='submit' class='botao_1'>Buscar</button>
					</form>
				</div>
			</div>
		</div>
		<div class="espacamento_20"></div>
		<?php

		if ($linha_pessoa > 0) {
			$tamanhoDiv = $permite_alterar ? '1000px' : '940px';
			echo
			"<div style='font-style:normal; height:auto; width: $tamanhoDiv; margin:auto; border:1px solid gray; border-radius:5px; padding: 10px'>";
		?>
			<!--
			<div style='font-style:normal; height:auto; width: 1000px; margin:auto; border:1px solid gray; border-radius:5px; padding: 10px'>
		-->
			<table id="gridProdutor" class='tabela_geral'>
				<thead>
					<tr style="font-size: 10px; color: #FFF; margin: auto; height: 24px; background-color:#006699; text-align:center; border:1px solid #FFF;">
						<td style="width: 70px">Código</td>
						<td style="width: 210px">Nome</td>
						<td style="width: 120px">CPF/CNPJ</td>
						<td style="width: 130px">Telefone</td>
						<td style="width: 220px">Cidade/UF</td>
						<td style="width: 100px">Situação</td>
						<td style="width: 60px">Histórico</td>
						<?php
						if ($permite_alterar) {
							echo "<td style='width: 60px'>Alterar</td>";
						}
						?>
					</tr>
				</thead>
				<tbody style="font-size:12px">
					<?php
					$situacao_compra_w = 'LIBERADA';
					$color_bg_w = "#7FFF00";

					// Lê todos os registros
					foreach ($busca_pessoa as $pessoa) {
						switch ($pessoa[$fld_situacao]) {
							case 0:
								$situacao_compra_w = 'LIBERADA';
								$color_bg_w = "#7FFF00";
								break;
							case 1:
								$situacao_compra_w = 'ANÁLISE';
								$color_bg_w = "#FFFF00";
								break;
							case 2:
								$situacao_compra_w = 'BLOQUEADA';
								$color_bg_w = "#FF0000";
								break;
						}

						$cpf_cnpj_print = (strtoupper($pessoa[$fld_tipo]) == "PF") ? $pessoa[$fld_cpf] : $pessoa[$fld_cnpj];

					?>
						<tr class='tabela_1'>
							<td width='70px' text-align='left'>
								<div style='margin-left: 7px'><?= $pessoa[$fld_id] ?></div>
							</td>
							<td width='210px' text-align='left'>
								<div style='margin-left:7px; overflow:hidden'><?= $pessoa[$fld_nome] ?></div>
							</td>
							<td style="width: 120px; text-align: center"><?= $cpf_cnpj_print ?></td>
							<td style="width: 130px; text-align: center"><?= $pessoa[$fld_telefone] ?></td>
							<td style="width: 220px; text-align: center"><?php echo "$pessoa[$fld_cidade] / $pessoa[$fld_estado]"; ?></td>
							<?php
							echo "<td class='dscSituacao' id='$pessoa[$fld_id]' style='width: 100px; text-align: center; background-color: $color_bg_w'>$situacao_compra_w</td>";
							?>

							<td width='60px' align='center'>
								<button data-id=<?= $pessoa[$fld_id] ?> data-situacao=<?= $pessoa[$fld_situacao] ?> value="<?= $pessoa[$fld_id] ?>" class='btnShowHistorico' style='background: transparent; border-width:0px'> <img src=<?= "$servidor/$diretorio_servidor/imagens/botoes/doc_3.png" ?> height='20px' border='none' /></button>
							</td>

							<?php
							if ($permite_alterar) {
								echo
								"<td width='60px' align='center'>
									<button data-id=$pessoa[$fld_id] data-situacao=$pessoa[$fld_situacao] value=$pessoa[$fld_id] class='btnAlteraSituacao' style='background: transparent; border-width:0px'> <img src=$servidor/$diretorio_servidor/imagens/botoes/editar.png height='20px' border='none' /></button>
									</td>";
							}
							?>
						</tr>

					<?php
					}
					?>
				</tbody>
			</table>
	</div>
<?php
		}
?>

<?php
if ($linha_pessoa == 0 and $botao == "BUSCAR") {
	echo "
			<div class='espacamento_30'></div>
			<div style='height:30px; width:880px; border:0px solid #000; color:#F00; font-size:14px; margin:auto; text-align:center'>
			<i>Nenhum cadastro encontrado.</i></div>";
}

?>
</div>

<div class="espacamento_40"></div>

<div class="contador">
	<div class="ct_subtitulo_left" style="width:1000px; float:left; margin-left:25px; text-align:left; font-size:12px">
	</div>
</div>

<div class="espacamento_10"></div>

<!-- ====== RODAPÉ ================================================================================== -->
<div class="rdp_1">
	<?php include("../../includes/rodape.php"); ?>
</div>

<!-- ====== FIM ===================================================================================== -->
<?php include("../../includes/desconecta_bd.php"); ?>

</body>



<!-- Mostra o popup de histórico -->
<script type='text/javascript'>
	// Polyfill para a tag <dialog> em navegadores que não suportam nativamente
	(function() {
		var dialog = document.getElementById('dlgAlteraSituacao');
		if (!dialog.showModal) {
			dialogPolyfill.registerDialog(dialog);
		}
	})();

	function fechaHistorico() {
		document.getElementById("dlgHistorico").close();
	}

	$(document).ready(function() {
		// Botão histórico do grid
		$('.btnShowHistorico').click(function() {
			var userid = $(this).data('id');
			$.ajax({
				url: 'situacao_compra_produtor_ajax_h.php',
				type: 'post',
				data: {
					userid: userid
				},
				success: function(response) {
					$('.modal-body').html(response);
					document.getElementById("dlgHistorico").showModal();
				}
			});
		});

		// Botão Alterar do grid
		$(".btnAlteraSituacao").on("click", function() {
			// Grava id e situacao no dialog
			var produtorId = $(this).data('id');
			var situacaoid = $(this).data('situacao');

			$("#produtorid").val(produtorId);
			$("#situacaoid").val(situacaoid);

			var dialog = document.getElementById('dlgAlteraSituacao');
			dialog.showModal();
		});

		$("#btnCancelaAlteracao").on("click", function() {
			var dialog = document.getElementById('dlgAlteraSituacao');
			dialog.close();
		});

		$('#btnSalvaAlteracao').on("click", function() {
			var prodoturid = $("#produtorid").val();
			var situacaoid = $("#selectSituacao").val();
			var descricao = $("#descricaoMotivo").val();

			$.ajax({
				url: 'situacao_compra_produtor_ajax_a.php',
				type: 'POST',
				data: {
					produtorid: prodoturid,
					situacaoid: situacaoid,
					descricao: descricao
				},
				success: function(response) {
					const objResponse = JSON.parse(response);
					console.log(objResponse.situacaotexto);

					const linha = document.getElementById(prodoturid);
					linha.setAttribute("style", "text-align: center; background: " + objResponse.background);
					linha.innerHTML = objResponse.situacaotexto;
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

	});
</script>


</html>