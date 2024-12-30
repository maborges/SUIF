<?php
include_once("../../includes/config.php");
include_once("../../includes/conecta_bd.php");
include_once("../../includes/valida_cookies.php");


$pagina = "cotacao_compra";
$titulo = "Cotação de Compras";
$modulo = "compras";
$menu   = "compras";

// Variáveis de pesquisa
$codigoPesquisa             = $_POST['codigoPesquisa'] ?? '';
$nomeContatoPesquisa        = $_POST['nomeContatoPesquisa'] ?? '';
$nomeProdutorPesquisa       = $_POST['nomeProdutorPesquisa'] ?? '';
$telefonePesquisa           = $_POST['telefonePesquisa'] ?? '';

// Variáveis de cadastro
$codigo                 = $_POST['codigo'] ?? '';
$nome                   = $_POST['nome'] ?? '';
$telefone               = $_POST['telefone'] ?? '';
$email                  = $_POST['email'] ?? '';
$produtor               = $_POST['produtor'] ?? '';
$nomeProdutor           = $_POST['nomeProdutor'] ?? '';
$classificacaoPessoa    = $_POST['classificacaoPessoa'] ?? 'NCD'; // Não cadastrado
$produto                = $_POST['produto'] ?? '';
$situacao               = $_POST['situacao'] ?? 'INI';
$observacao             = $_POST['observacao'] ?? '';

$nomeProduto            = $_POST['nomeProduto'] ?? '';
$quantidade             = $_POST['quantidade'] ?? '';
$valorUnitarioPedido    = $_POST['valorUnitarioPedido'] ?? '';
$valorUnitarioOferecido = $_POST['valorUnitarioOferecido'] ?? '';
$precoCompraMaximo      = $_POST['precoCompraMaximo'] ?? '';

$conexao = ConnectDB();

try {
    $stmt = "select codigo, CONCAT(descricao, ' (', unidade_print, ')') as descricao, preco_compra_maximo
               from cadastro_produto 
              where estado_registro = 'ATIVO'
             order by descricao";

    $stmt = $conexao->prepare($stmt);

    // Executa a consulta
    if ($stmt->execute()) {
        $produtos = $stmt->get_result();
    } else {
        $produtos = [];
    }

    $stmt = "select codigo, descricao
               from cotacao_produto_situacao
             order by descricao";

    $stmt = $conexao->prepare($stmt);

    // Executa a consulta
    if ($stmt->execute()) {
        $cotacaoSituacao = $stmt->get_result();
    } else {
        $cotacaoSituacao = [];
    }
} finally {
    DisconnectDB($conexao);
}

include_once("../../includes/head.php");

?>

<link rel="stylesheet" type="text/css" href="<?php echo "$servidor/$diretorio_servidor"; ?>/padrao.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />


<style>
    /* Estilo simples para o modal */
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

    .ui-autocomplete {
        z-index: 1051 !important;
    }
</style>

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

    <div class="container-fluid col-10">
        <div class="row">
            <div class="ct_titulo_1 mt-3 col">
                <?= $titulo ?>
            </div>
        </div>

        <div class="col mt-4 bg-light border p-2 rounded">
            <form method="post" id="frmCotacaoCompra">
                <input type="hidden" name="codigoPesquisa" id="codigoPesquisa" value="<?= $codigoPesquisa ?>">

                <div class="row pr-2">
                    <div class="form-group col-8">
                        <label for="nomeContatoPesquisa" class="mb-1">Produtor</label>
                        <input type="search" class="form-control form-control-sm ui-autocomplete-input" name="nomeContatoPesquisa" id="nomeContatoPesquisa" placeholder="Nome do produtor/favorecido/contato"
                            maxlength="50" value="<?= $nomeContatoPesquisa ?>">
                    </div>

                    <div class="form-group col-3">
                        <label for="telefonePesquisa" class="mb-1">Telefone</label>
                        <input type="search" class="form-control form-control-sm ui-autocomplete-input" name="telefonePesquisa" id="telefonePesquisa"
                            value="<?= $telefonePesquisa ?>">
                    </div>

                    <div class="form-group col-1">
                        <br>
                        <button type="submit" name="btnBuscar" class="btn btn-outline-secondary btn-sm">Buscar</button>
                    </div>
                </div>

            </form>
            <div class="row pr-0">
                <div class="form-group col-1 ms-0">
                    <button name="btnIncluir" id="btnIncluir" class="btn btn-outline-secondary btn-sm">Incluir</button>
                </div>
            </div>
        </div>

        <div class="col mt-4 border p-3 rounded shadow-sm">
            <table class="display compact" id="tblContato" style="width: 100%; height: auto">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Produto</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Cadastro</th>
                        <th>...</th>
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
        <form method="get" id="frmRegistro">
            <input type="hidden" name="codigo" id="codigo" value="<?= $codigo ?>">
            <input type="hidden" name="produtor" id="produtor" value="<?= $produtor ?>">
            <input type="hidden" name="produto" id="produto" value="<?= $produto ?>">
            <input type="hidden" name="classificacaoPessoa" id="classificacaoPessoa" value="<?= $classificacaoPessoa ?>">

            <div class="row pr-0">
                <div class="form-group col-6">
                    <label for="nomeProdutor" class="mb-1">Produtor</label>
                    <input type="search" class="form-control form-control-sm ui-autocomplete-input" name="nomeProdutor" id="nomeProdutor" placeholder="Nome do produtor"
                        maxlength="50" value="<?= $nomeProdutor ?>"
                        <?php if ($codigo): ?>
                        readonly
                        <?php endif; ?>>
                </div>

                <div class="form-group col-4">
                    <label for="email" class="mb-1">Email</label>
                    <input type="email" class="form-control form-control-sm" name="email" id="email" placeholder="Email do contado"
                        maxlength="100" value="<?= $email ?>">
                </div>

                <div class="form-group col-2">
                    <label for="telefone" class="mb-1">Telefone</label>
                    <input type="text" class="form-control form-control-sm" name="telefone" id="telefone" placeholder="(00) 0000-0000"
                        value="<?= $telefone ?>">
                </div>
            </div>


            <div class="row pr-0">
                <div class="form-group col-4">
                    <label for="produto1" class="mb-1">Produto</label>
                    <select class="form-control form-control-sm form-select" name="produto1" id="produto1" onchange="atualizarPreco(this)">
                        <option value="">Selecione um produto</option>

                        <?php foreach ($produtos as $produtoItem): ?>
                            <option value="<?= $produtoItem['codigo'] ?>" data-preco="<?= $produtoItem['preco_compra_maximo'] ?>"
                                <?= ($produto == $produtoItem['codigo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($produtoItem['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-2">
                    <label for="precoCompraMaximo" class="mb-1">Preço Máximo</label>
                    <input type="text" class="form-control form-control-sm" name="precoCompraMaximo" id="precoCompraMaximo"
                        value="<?= $precoCompraMaximo ?>" readonly>
                </div>

                <div class="form-group col-2">
                    <label for="quantidade" class="mb-1">Quantidade</label>
                    <input type="number" class="form-control form-control-sm" name="quantidade" id="quantidade"
                        value="<?= $quantidade ?>">
                </div>

                <div class="form-group col-2">
                    <label for="valorUnitarioPedido" class="mb-1">Valor Pedido</label>
                    <input type="number" class="form-control form-control-sm" name="valorUnitarioPedido" id="valorUnitarioPedido"
                        value="<?= $valorUnitarioPedido ?>">
                </div>

                <div class="form-group col-2">
                    <label for="valorUnitarioOferecido" class="mb-1">Valor Oferecido</label>
                    <input type="number" class="form-control form-control-sm" name="valorUnitarioOferecido" id="valorUnitarioOferecido"
                        value="<?= $valorUnitarioOferecido ?>">
                </div>

            </div>

            <div class="row pr-0">
                <div class="form-group col-4">
                    <label for="situacao" class="mb-1">Situação da Negociação</label>
                    <select class="form-control form-control-sm form-select" name="situacao" id="situacao">
                        <?php foreach ($cotacaoSituacao as $situacaoItem): ?>
                            <option value="<?= $situacaoItem['codigo'] ?>"
                                <?= ($situacao == $situacaoItem['codigo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($situacaoItem['descricao']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group col-8">
                    <label for="observacao" class="mb-1">Observações</label>
                    <textarea rows="3" class="form-control form-control-sm" name="observacao" id="observacao"
                        value="<?= $observacao ?>">
                    </textarea>
                </div>
            </div>

            <div class="row pr-0">
                <div class="form-group col-3">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Salvar</button>
                    <button type="button" id="btnCancelar" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                </div>
            </div>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap4.min.js"></script>
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

        new Cleave('#precoCompraMaximo', {
            numeral: true,
            numeralDecimalMark: ',',
            delimiter: '.',
        });

        function atualizarPreco(select) {
            const produtoSelecionado = select.options[select.selectedIndex];
            const precoCompraMaximo = produtoSelecionado.dataset.preco

            // Atualizar o texto do preço e do código do produto selecionado
            const precoProduto = document.getElementById('precoCompraMaximo');
            precoProduto.value = precoCompraMaximo ? precoCompraMaximo : '0';
            $('#produto').val(produtoSelecionado.value || '');
        }

        // Funções para abrir e fechar o modal
        function abrirModal(titulo, dados = {}) {
            $('#modal-titulo').text(titulo);
            $('#codigo').val(dados.codigo || '');
            $('#nome').val(dados.nome || '');
            $('#telefone').val(dados.telefone || '');
            $('#email').val(dados.email || '');
            $('#produtor').val(dados.produtor || '');
            $('#classificacaoPessoa').val(dados.classificacaoPessoa || 'NCD');
            $('#nomeProdutor').val(dados.nomeProdutor || '');
            $('#produto').val(dados.produto || '');
            $('#produto1').val(dados.produto || '');
            $('#nomeProduto').val(dados.nomeProduto || '');
            $('#precoCompraMaximo').val(dados.precoCompraMaximo || '');
            $('#quantidade').val(dados.quantidade || '');
            $('#valorUnitarioPedido').val(dados.valorUnitarioPedido || '');
            $('#valorUnitarioOferecido').val(dados.valorUnitarioOferecido || '');
            $('#situacao').val(dados.situacao || 'INI');
            $('#observacao').val(dados.observacao || '');
            $('#criadoEm').val(dados.criadoEm || '');
            $('#criadoPor').val(dados.criadoPor || '');
            $('#alteradoEm').val(dados.alteradoEm || '');
            $('#alteradoPor').val(dados.alteradoPor || '');
            $('#excluidoEm').val(dados.excluidoEm || '');

            $('#nomeProdutor').prop('readonly', dados.codigo != null);
            $('#produto1').prop('disabled', dados.codigo != null);

            $('#modal-overlay, #modal-registro').show();
        }

        function fecharModal() {
            $('#modal-overlay').hide();
            $('#modal-registro').hide();
        }

        // Botão "Adicionar Novo Registro"
        $('#btnIncluir').on('click', function() {
            $('#frmRegistro')[0].reset(); // Limpar formulário
            $('#codigo').val(''); // Limpar ID
            abrirModal('Incluir Cotação');
        });

        // Botão "Cancelar" fecha o modal
        $('#btnCancelar').on('click', function() {
            fecharModal();
        });

        var table = $('#tblContato').DataTable({
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
                url: "../../ajax/pesquisa/cotacao_contato.php",
                method: 'GET',
                dataType: 'json',
                data: function(d) {
                    d.nome = $('#nomeContatoPesquisa').val();
                    d.telefone = $('#telefonePesquisa').val();
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
                    $('#tblContato_processing').hide();
                    $('#tblContato').DataTable().clear().draw();
                }
            },
            pageLength: 25,
            columns: [{
                    data: 'codigo',
                    visible: false
                },
                {
                    data: 'nome'
                },
                {
                    data: 'classificacaoPessoa',
                    "render": function(data, type, row) {
                        if (data == 'PDT') {
                            return '<span class="badge badge-warning">Produtor</span>';
                        } else if (data == 'FVR') {
                            return '<span class="badge badge-success">Favorecido</span>'
                        } else {
                            return '<span class="badge badge-info">Sem Cadastro</span>';
                        }
                    }
                },
                {
                    data: 'nomeProduto'
                },
                {
                    data: 'telefone'
                },
                {
                    data: 'email'
                },
                {
                    data: 'criadoEm',
                    "render": function(data, type, row) {
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return `<div class='d-sm-flex'> 
                                <a class='btn text-success ms-0 p-0 btnEditar'  data-codigo="${data.codigo}"><i class='fa-regular fa-pen-to-square shadow-sm'></i></a>
                                <a class='btn text-danger  ms-0 p-0 btnExcluir' data-codigo="${data.codigo}"><i class='fa-regular fa-trash-can shadow-sm'></i></a>
                            </div>`;
                    }
                }

            ],
            columnDefs: [{
                targets: [2, 7], // Índice da coluna (começando em 0)
                className: 'dt-center' // Classe para centralizar
            }]
        });

        // Recarrega a tabela ao clicar no botão
        $('#btnBuscar').on('click', function() {
            table.ajax.reload(null, false); // O 'false' mantém a página de paginação atual
        });

        $(document).ready(function() {

            // Botão para edição de registro existente
            $('#tblContato tbody').on('click', '.btnEditar', function() {
                var data = table.row($(this).parents('tr')).data();
                abrirModal('Editar Cotação', data);
            });


            // Botão para exclusão de registro existente
            $('#tblContato tbody').on('click', '.btnExcluir', function() {
                var codigo = $(this).data('codigo');
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
                        $.ajax({
                            url: 'cotacao_compra_atualiza.php',
                            type: 'POST',
                            data: {
                                codigo: codigo,
                                excluiRegistro: true
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "Registro excluído com sucesso."
                                });

                                table.ajax.reload(null, false);
                            },
                            error: function() {
                                Toast.fire({
                                    icon: "error",
                                    title: "Erro ao excluir o registro. Tente novamente mais tarde."
                                });
                            }
                        });
                    }
                });
            });
        });

        function setSearchCotacaoCompra(inputSelector, fieldName, selectCallback) {
            $(inputSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "../../ajax/pesquisa/cotacao_contato.php",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            value: request.term,
                            fieldName: fieldName
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.value,
                                    codigo: item.codigo,
                                    nome: item.nome,
                                    telefone: item.telefone,
                                    email: item.email,
                                    criadoEm: item.criadoEm
                                };
                            }));
                        }
                    });
                },
                select: selectCallback,
                minLength: 2
            });

            // Evento para limpar campos relacionados quando o input estiver vazio
            $(inputSelector).on('change keyup', function() {
                if ($(this).val().trim() === '') {
                    $(`#codigoPesquisa`).val('');
                }
            });
        }

        // Função para tratar o evento de seleção (select)
        function handleSelectCotacaoCompra(event, ui) {
            $(`#codigoPesquisa`).val(ui.item.codigo);
            $(`#nomeContatoPesquisa`).val(ui.item.nome);
            $(`#telefonePesquisa`).val(ui.item.telefone);
        }

        // Configurando para múltiplos elementos
        setSearchCotacaoCompra("#nomeContatoPesquisa", 'nome', handleSelectCotacaoCompra);
        setSearchCotacaoCompra("#telefonePesquisa", 'telefone', handleSelectCotacaoCompra);

        function setSearchProdutor(inputSelector, fieldName, selectCallback) {
            $(inputSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "../../ajax/pesquisa/produtor.php",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            value: request.term,
                            fieldName: fieldName
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.value,
                                    codigo: item.codigo,
                                    nome: item.nome,
                                    cpfcnpj: item.cpfcnpj,
                                    telefone: item.telefone,
                                    email: item.email,
                                    embargado: item.embargado,
                                    classificacaoPessoa: item.classificacaoPessoa
                                };
                            }));
                        }
                    });
                },
                select: selectCallback,
                minLength: 2
            });

            // Evento para limpar campos relacionados quando o input estiver vazio
            $(inputSelector).on('change keyup', function() {
                if ($(this).val().trim() === '') {
                    $(`#produtor`).val('');
                }
            });
        }

        // Função para tratar o evento de seleção (select)
        function handleSelectProdutor(event, ui) {
            console.log(ui.item);
            $(`#produtor`).val(ui.item.codigo);
            $(`#nome`).val(ui.item.nome);
            $(`#email`).val(ui.item.email);
            $(`#telefone`).val(ui.item.telefone);
            $(`#classificacaoPessoa`).val(ui.item.classificacaoPessoa);
        }

        setSearchProdutor("#nomeProdutor", 'nome', handleSelectProdutor);

        // ######################################
        function setSearchProduto(inputSelector, fieldName, selectCallback) {
            $(inputSelector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "../../ajax/pesquisa/produto.php",
                        type: "GET",
                        dataType: 'json',
                        data: {
                            value: request.term,
                            fieldName: fieldName
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.value,
                                    codigo: item.codigo,
                                    unidade: item.unidade,
                                    descricao: item.descricao,
                                    quantidadeUnidade: item.quantidadeUnidade,
                                    precoCompraMaximo: item.precoCompraMaximo,
                                };
                            }));
                        }
                    });
                },
                select: selectCallback,
                minLength: 2
            });

            // Evento para limpar campos relacionados quando o input estiver vazio
            $(inputSelector).on('change keyup', function() {
                if ($(this).val().trim() === '') {
                    $(`#produto`).val('');
                }
            });
        }

        // Função para tratar o evento de seleção (select)
        function handleSelectProduto(event, ui) {
            $(`#produto`).val(ui.item.codigo);
            $(`#nomeProduto`).val(ui.item.descricao);
            $(`#precoCompraMaximo`).val(ui.item.precoCompraMaximo);
            $(`#valorUnitarioOferecido`).val(ui.item.precoCompraMaximo);
        }

        setSearchProduto("#nomeProduto", 'descricao', handleSelectProduto);


        $('#frmRegistro').on('submit', function(event) {
            event.preventDefault(); // Evita o comportamento padrão de submissão do formulário

            const formData = $(this).serialize(); // Serializa os dados do formulário

            $.ajax({
                url: 'cotacao_compra_atualiza.php', // URL do backend que recebe os dados
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        // Exibe mensagens de erro no modal
                        Toast.fire({
                            icon: "error",
                            title: response.message
                        });
                    } else {
                        // Fecha o modal e atualiza a tabela de contatos
                        fecharModal();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            icon: "success",
                            title: "Dados gravados com sucesso!"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    if (error) {
                        Toast.fire({
                            icon: "error",
                            title: `Erro ao gravar os dados: ${error}`
                        });

                    }
                }
            });
        });
    </script>
</body>

</html>