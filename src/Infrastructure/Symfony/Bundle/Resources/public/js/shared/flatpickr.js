import "flatpickr/dist/flatpickr.min.css"

import "flatpickr/dist/flatpickr.min"
import { Spanish } from "flatpickr/dist/l10n/es";

const Flatpickr = function () {
    $('.js-flatpickr:not(.js-flatpickr-enabled)').each((index, element) => {
        let el = $(element);
        el.addClass('js-flatpickr-enabled');

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
export default Flatpickr;