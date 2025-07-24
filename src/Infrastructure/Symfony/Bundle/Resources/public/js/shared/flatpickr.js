// import "flatpickr/dist/flatpickr.min.css"
import "flatpickr/dist/flatpickr.min"
import { Spanish } from "flatpickr/dist/l10n/es";

export let Flatpickr = function () {
    // Init Flatpickr (with .js-flatpickr class)
    $('.js-flatpickr:not(.js-flatpickr-enabled)').each((index, element) => {
        let el = $(element);

        // Add .js-flatpickr-enabled class to tag it as activated
        el.addClass('js-flatpickr-enabled');

        // Init it
        flatpickr(el, {
            locale: Spanish,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true, // optional
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    Flatpickr();
});

window.Flatpickr = Flatpickr;