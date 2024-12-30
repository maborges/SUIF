<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotacao de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <style>
        #modal-registro {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            background-color: white;
            transform: translate(-50%, -50%);
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        #modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h3 class="mt-4">Cotacao de Compras</h3>
        <div class="mt-4 bg-light border p-2 rounded">
            <button name="btnIncluir" id="btnIncluir" class="btn btn-outline-secondary btn-sm mb-3">Incluir</button>
            <table class="table display compact" id="tblContato" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div id="modal-overlay"></div>
    <div id="modal-registro" class="col-8 rounded shadow-sm">
        <h5 id="modal-titulo">Titulo</h5>
        <form method="post" id="frmRegistro">
            <input type="hidden" name="codigo" id="codigo">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control form-control-sm" name="nome" id="nome" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control form-control-sm" name="telefone" id="telefone" required>
            </div>
            <div class="form-group">
                <label for="documento">Documento</label>
                <input type="text" class="form-control form-control-sm" name="documento" id="documento" required>
            </div>
            <button type="submit" class="btn btn-outline-secondary btn-sm">Salvar</button>
            <button type="button" id="btnCancelar" class="btn btn-outline-secondary btn-sm">Cancelar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script>
        function abrirModal(titulo, dados = {}) {
            $('#modal-titulo').text(titulo);
            $('#codigo').val(dados.codigo || '');
            $('#nome').val(dados.nome || '');
            $('#telefone').val(dados.telefone || '');
            $('#documento').val(dados.documento || '');
            $('#modal-overlay, #modal-registro').show();
        }

        function fecharModal() {
            $('#modal-overlay, #modal-registro').hide();
        }

        $(document).ready(function() {
            var table = $('#tblContato').DataTable({
                ajax: {
                    url: "../../ajax/pesquisa/cotacao_contato.php",
                    dataSrc: "data"
                },
                columns: [{
                        data: 'codigo'
                    },
                    {
                        data: 'nome'
                    },
                    {
                        data: 'telefone'
                    },
                    {
                        data: 'documento'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'criadoEm',
                        render: function(data) {
                            return new Date(data).toLocaleDateString();
                        }
                    },
                    {
                        data: null,
                        bSortable: false,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary btn-editar" data-codigo="${data.codigo}">Editar</button>
                                <button class="btn btn-sm btn-danger btn-excluir" data-codigo="${data.codigo}">Excluir</button>
                            `;
                        }
                    }
                ]
            });

            // Botão para inclusão de novo registro
            $('#btnIncluir').on('click', function() {
                abrirModal('Incluir Cotacao');
            });

            // Botão para edição de registro existente
            $('#tblContato tbody').on('click', '.btn-editar', function() {
                var data = table.row($(this).parents('tr')).data();
                abrirModal('Editar Cotacao', data);
            });

            // Botão para exclusão de registro existente
            $('#tblContato tbody').on('click', '.btn-excluir', function() {
                var codigo = $(this).data('codigo');
                toastr.warning(
                    `<div>Tem certeza que deseja excluir este registro?</div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-danger btn-sm" id="confirmarExclusao">Excluir</button>
                        <button type="button" class="btn btn-secondary btn-sm ml-2" id="cancelarExclusao">Cancelar</button>
                    </div>`,
                    'Confirmação', {
                        closeButton: false,
                        allowHtml: true,
                        timeOut: 0,
                        extendedTimeOut: 0,
                        tapToDismiss: false,
                        onShown: function() {
                            $('#confirmarExclusao').on('click', function() {
                                $.ajax({
                                    url: 'cotacao_compra_excluir.php',
                                    type: 'POST',
                                    data: {
                                        codigo: codigo
                                    },
                                    success: function(response) {
                                        toastr.success('Registro excluído com sucesso!', 'Sucesso');
                                        table.ajax.reload(null, false);
                                    },
                                    error: function() {
                                        toastr.error('Erro ao excluir o registro. Tente novamente mais tarde.', 'Erro');
                                    }
                                });
                            });
                            $('#cancelarExclusao').on('click', function() {
                                toastr.clear();
                            });
                        }
                    }
                );
            });

            // Botão para cancelar ação e fechar o modal
            $('#btnCancelar').on('click', fecharModal);

            // Formulário de registro (inclusão/edição)
            $('#frmRegistro').on('submit', function(event) {
                event.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: 'cotacao_compra_atualiza.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.message, 'Erro');
                        } else {
                            fecharModal();
                            table.ajax.reload(null, false);
                            toastr.success('Dados gravados com sucesso!', 'Sucesso');
                        }
                    },
                    error: function() {
                        toastr.error('Erro ao gravar os dados. Tente novamente mais tarde.', 'Erro');
                    }
                });
            });
        });
    </script>
</body>

</html>