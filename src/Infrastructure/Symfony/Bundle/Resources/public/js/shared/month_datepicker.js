import "bootstrap-datepicker/dist/js/bootstrap-datepicker.min"
import "bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min"

document.addEventListener('DOMContentLoaded', function() {
    $('.month-datepicker').datepicker({
        // format: "MM-yyyy",
        minViewMode: 1,
        autoclose: true,
        todayHighlight: true,
        disableTouchKeyboard: true
    }).on('changeMonth', function(e) {
        const date = e.date;
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        const formatted = `${monthNames[date.getMonth()]}-${date.getFullYear()}`;
        $(this).val(formatted).trigger('change');
    });
});