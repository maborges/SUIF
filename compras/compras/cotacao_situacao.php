<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");


$pagina = "cotacao_compra_situacao";
$titulo = "Situação Cotação";
$modulo = "compras";
$menu   = "compras";

// Variáveis de cadastro
$codigo                 = $_POST['codigo'] ?? '';
$descricao              = $_POST['descricao'] ?? '';

$conexao = ConnectDB();

try {
    $stmt = "select codigo, descricao
               from cotacao_produto_situacao 
              order by descricao";

    $stmt = $conexao->prepare($stmt);

    // Executa a consulta
    if ($stmt->execute()) {
        $situacao = $stmt->get_result();
    } else {
        $situacao = [];
    }
} finally {
    DisconnectDB($conexao);
}

include_once("../../includes/head.php");

?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

</head>

<body>
    <div class=" topo">
        <?php include("../../includes/topo.php"); ?>
    </div>

    <div class="menu">
        <?php include("../../includes/menu_compras.php"); ?>
    </div>

    <div class="submenu">
        <?php include("../../includes/submenu_compras_compras.php"); ?>
    </div>

    <div class="container-fluid col-6">
        <div class="row">
            <div class="ct_titulo_1 mt-3 col">
                <?= $titulo ?>
            </div>
        </div>

        <section id="tabela">
            <div class="card mt-4 shadown-sm">
                <div class="card-header bg-light border p-2 rounded">
                    <button class="btn btn-outline-secondary btn-sm" id="btnIncluir">Incluir</button>
                </div>

                <div class="card-body">
                    <table class="display compact" id="tblSituacaoCotacao" style="width: 100%; height: auto">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="crudForm" class="d-none">
            <form id="formCrud">
                <div class="card mt-4 shadown-sm">
                    <div class="card-header bg-light border p-2 rounded">
                        <h5 class="modal-titulo" id="formTitle">Inclui Registro</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="codigo" class="mb-1">Código</label>
                                <input type="text" id="codigo" name="codigo" class="form-control form-control-sm" maxlength="3"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="form-group col-9">
                                <label for="descricao" class="mb-1">Descrição</label>
                                <input type="text" id="descricao" name="descricao" class="form-control form-control-sm" maxlength="20"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Salvar</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnCancelar">Cancelar</button>
                    </div>
            </form>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src=<?= "../../includes/js/moment.min.js" ?>></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js/dist/cleave.min.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        var table = $('#tblSituacaoCotacao').DataTable({
            caption: '',
            deferRender: true,
            fixedHeader: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: '48vh',
            language: {
                url: "../../ajax/pesquisa/pt-BR.json",
            },
            bPaginate: true,
            bProcessing: true,
            serverSide: false,
            ajax: {
                url: "cotacao_situacao_enviar.php",
                method: 'POST',
                data: {
                    action: 'fetch'
                },
                dataSrc: '',
                data: function(d) {
                    d.codigo = $('#codigo').val();
                    d.descricao = $('#descricao').val();
                },
                dataSrc: function(json) {
                    if (json.error) {
                        Toast.fire({
                            icon: "error",
                            title: json.error
                        });

                        return [];
                    }
                    return json.data;
                },
                error: function(xhr, status, error) {
                    var responseJSON = JSON.parse(xhr.responseText);
                    if (responseJSON && responseJSON.message) {
                        Toast.fire({
                            icon: "error",
                            title: jresponseJSON.message
                        });

                    } else {
                        Toast.fire({
                            icon: "error",
                            title: error
                        });
                    }
                    //    $('#tblSituacaoCotacao_processing').hide();
                    //    $('#tblSituacaoCotacao').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columns: [{
                    data: 'codigo'
                },
                {
                    data: 'descricao'
                },
                {
                    data: 'codigo',
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return `<div class='d-sm-flex'> 
                                <a class='btn text-success ms-0 p-0 btnEditar'  data-codigo="${data}"><i class='fa-regular fa-pen-to-square shadow-sm'></i></a>
                                <a class='btn text-danger  ms-0 p-0 btnExcluir' data-codigo="${data}"><i class='fa-regular fa-trash-can shadow-sm'></i></a>
                            </div>`;
                    }
                }

            ]
        });

        // Alternar entre tabela e formulário
        const mostrarTabela = () => {
            $('#crudForm').addClass('d-none');
            $('#tabela').removeClass('d-none');
        };

        const mostrarFormulario = () => {
            $('#tabela').addClass('d-none');
            $('#crudForm').removeClass('d-none');
        };

        // Adicionar registro
        $('#btnIncluir').click(function() {
            $('#formCrud')[0].reset();
            $('#formTitle').text('Incluir Registro');
            $('#codigo').prop('readonly', false);
            mostrarFormulario();
        });

        // Cancelar formulário
        $('#btnCancelar').click(function() {
            mostrarTabela();
        });

        // Submeter formulário
        $('#formCrud').submit(function(e) {
            e.preventDefault();
            const dados = $(this).serialize() + '&action=save';

            $.post('cotacao_situacao_enviar.php', dados, function(res) {
                if (res.success) {
                    table.ajax.reload(null, false);
                    mostrarTabela();
                    Toast.fire({
                        icon: "success",
                        title: "Dados gravados com sucesso!"
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: res.message || 'Erro ao salvar o registro.'
                    });
                }
            }, 'json');
        });

        // Editar registro
        $('#tblSituacaoCotacao').on('click', '.btnEditar', function() {
            const codigo = $(this).data('codigo');

            $.post('cotacao_situacao_enviar.php', {
                action: 'get',
                codigo
            }, function(res) {
                if (res.success) {
                    $('#codigo').val(res.data.codigo).prop('readonly', true);
                    $('#descricao').val(res.data.descricao);
                    $('#formTitle').text('Editar Registro');
                    mostrarFormulario();
                } else {
                    Toast.fire({
                        icon: "error",
                        title: res.message || 'Erro ao salvar o registro.'
                    });
                }
            }, 'json');
        });

        // Excluir registro
        $('#tblSituacaoCotacao').on('click', '.btnExcluir', function() {
            const codigo = $(this).data('codigo');

            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter esta ação!",
                icon: 'warning',
                padding: '.5em',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-outline-danger btn-sm m-2',
                    cancelButton: 'btn btn-outline-secondary btn-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('cotacao_situacao_enviar.php', {
                        action: 'delete',
                        codigo
                    }, function(res) {
                        if (res.success) {
                            Toast.fire({
                                icon: "success",
                                title: "Registro excluído com sucesso."
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Erro ao excluir o registro. Tente novamente mais tarde."
                            });
                        }
                    }, 'json');
                }
            });
        });
    </script>
</body>

</html>