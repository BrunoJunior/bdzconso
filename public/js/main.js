// On ready
$(function () {
    // Confirm modal
    var confirmModal = $("#confirmModal");
    $('#main').on('click', 'a.btn[data-confirm]', function (event) {
        var confirm = $(this).data('confirm');
        if (!!confirm) {
            var confirmMessage = $('#confirmationMessage');
            if (typeof confirm === 'string') {
                confirmMessage.text(confirm);
            } else {
                confirmMessage.text(confirmMessage.data('default'));
            }
            confirmModal.find('#confirmModalYes').attr('href', $(this).attr("href"));
            confirmModal.modal("show");
            event.preventDefault();
        }
    });
    $("#confirmModalNo").click(function(e) {
        confirmModal.find('#confirmModalYes').attr('href', '#');
        confirmModal.modal("hide");
    });

    // Date picker
    $('.js-datepicker').datepicker();

    // Charts with data in json
    $('canvas.chart').each(function() {
        var canvas = $(this);
        new Chart(canvas, canvas.data('canvas'));
    });

    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();
});