// import 'select2/dist/css/select2.min.css'
import 'select2/dist/js/select2.full.min'

const btnAddItems = document.querySelectorAll('.add_collection_item');
btnAddItems.forEach(item => {
    item.addEventListener('click', () => {
        setTimeout(() => {
            Select2();
        }, 100);
    })
})

export const Select2 = () => {
    $('.js-select2:not(.js-select2-enabled)').each((index, element) => {
        let el = $(element);

        el.addClass('js-select2-enabled').select2({
            allowClear: true,
            placeholder: el.data('placeholder') || false,
            dropdownAutoWidth: true,
            width: '100%',
            dropdownParent: document.querySelector(
                el.data("container") || "#page-container"
            ),
        });

        // Disparar evento personalizado cuando Select2 cambie
        el.on('select2:select select2:unselect', function(e) {
            const customEvent = new CustomEvent('select2Changed', {
                bubbles: true,
                detail: {
                    value: $(this).val(),
                    text: $(this).select2('data')[0]?.text || null,
                    element: this,
                }
            });

            this.dispatchEvent(customEvent);
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    Select2();
})

window.Select2 = Select2;