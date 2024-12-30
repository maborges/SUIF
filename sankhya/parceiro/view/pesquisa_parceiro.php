<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<dialog id="dlgAlteraSituacao" class="dialog">
	<form id="frmlteraSituacao">

		<h4>Pesquisa Parceiro no Sankhya</h4>
		<table id="gridParceiro" class="display compact" style="width: 100%">
			<thead>
				<tr>
					<th>Id</th>
					<th>Nome</th>
					<th>Razão Social</th>
					<th>CPF/CNPJ</th>
				</tr>
			</thead>

		</table>

		<div>
			<button id="btnSelecionaParceiro" class="botao_1" formmethod="dialog">Confirmar</button>
			<button id="btnCancelaAlteracao" style="margin: 0 30px;" class="botao_1" formmethod="dialog">Cancelar</button>
		</div>
	</form>

</dialog>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo "$servidor/$diretorio_servidor"; ?>/sankhya/parceiro/js/pesquisa_parceiro.js"></script>


<script>
	new DataTable('#gridParceiro', {
		order: [
			[1, 'desc']
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json'
		},
		ajax: '../../sankhya/parceiro/controller/lista_parceiro.php',
		processing: true,
		serverSide: true,
		scrollX: true,
		scrollY: '420px',
		autoWidth: false,
		error: function(xhr, resp, text) {
			console.log(xhr, resp, text);
		}
	});

	const table = new DataTable('#gridParceiro');

	table.on('click', 'tbody tr', (e) => {
		let classList = e.currentTarget.classList;

		if (classList.contains('selected')) {
			classList.remove('selected');
		} else {
			table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
			classList.add('selected');
		}
	});


	document.getElementById('btnSelecionaParceiro').addEventListener('click', function() {
		rowData = table.row('.selected').data();

		$.ajax({
			type: "POST",
			url: "../../sankhya/parceiro/controller/lista_parceiro.php",
			data: {
				AtualizaSUIF: true,
				SankhyaRecord: rowData
			},
			dataType: "json",
			error: function(error) {
				console.log(error);
			}
		});
	});
</script>
<?php

?>