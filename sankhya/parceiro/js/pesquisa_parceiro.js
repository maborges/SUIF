
const btnAlteraSituacao = document.getElementById("btnAlteraSituacao");
const dlgAlteraSituacao = document.getElementById("dlgAlteraSituacao");

btnAlteraSituacao.onclick = function () {
    dlgAlteraSituacao.showModal();
};




/*
$(document).ready(function () {
    // Botão Abre Pesquisa
    $(".btnAlteraSituacao").on("click", function () {
        console.log('p2 ###################');
        // Grava id e situacao no dialog
        var produtorId = $(this).data('id');
        var situacaoid = $(this).data('situacao');

        $("#produtorid").val(produtorId);
        $("#situacaoid").val(situacaoid);

        var dialog = document.getElementById('dlgAlteraSituacao');
        console.log('asdasdfa');
        dialog.showModal();
    });

    $("#btnCancelaAlteracao").on("click", function () {
        var dialog = document.getElementById('dlgAlteraSituacao');
        dialog.close();
    });

    $('#btnSalvaAlteracao').on("click", function () {
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
            success: function (response) {
                const objResponse = JSON.parse(response);
                console.log(objResponse.situacaotexto);

                const linha = document.getElementById(prodoturid);
                linha.setAttribute("style", "text-align: center; background: " + objResponse.background);
                linha.innerHTML = objResponse.situacaotexto;
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

});

*/

