import "bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"

import "bootstrap-datepicker/dist/js/bootstrap-datepicker.min"
import "bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min"

const Datepicker = function () {
    $(".js-datepicker:not(.js-datepicker-enabled)")
        .add(".input-daterange:not(.js-datepicker-enabled)")
        .each((index, element) => {
            let el = jQuery(element);

            el.addClass("js-datepicker-enabled").datepicker({
                weekStart: el.data("week-start") || 0,
                autoclose: el.data("autoclose") || true,
                todayHighlight: el.data("today-highlight") || false,
                startDate: el.data("start-date") || false,
                container: el.data("container") || "#page-container",
                orientation: "bottom", // Position issue when using BS5, set it to bottom until officially supported
                language: 'es',
            });
        });
};

document.addEventListener('DOMContentLoaded', () => {
    Datepicker();
})

document.addEventListener('addCollectionItemEvent', () => {
    Datepicker();
})

window.Datepicker = Datepicker;
export default Datepicker;