// On ready
$(function () {
    // Modal
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
});

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}