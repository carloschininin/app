import exportData from './export_data';

const listFilter = function (urlExport=null, filenameExport=''){
    const formFilter = document.querySelector('#form_filter') || document.querySelector('[name="form_filter"]');
    const inputPage = document.querySelector("#form_filter_page");
    const inputLimit = document.querySelector("#form_filter_limit");
    const filterSize = document.querySelector("#filter_size");
    const btnEnviar = document.querySelector("#btn_apply");
    const btnReset = document.querySelector("#btn_reset");

    filterSize.addEventListener('change', function(e) {
        e.preventDefault();
        inputPage.value = 1;
        inputLimit.value = filterSize.value;
        formFilter.submit();
    })

    btnEnviar.addEventListener('click', function(e) {
        e.preventDefault();
        inputPage.value = 1;
        formFilter.submit();
    })

    btnReset.addEventListener('click', function(e) {
        e.preventDefault();
        formFilter.reset();

        formFilter.querySelectorAll('select').forEach(select => {
            const emptyOption = select.querySelector('option[value=""]');
            if (emptyOption) {
                select.value = '';
            }
        });

        window.location.href = btnReset.dataset.url;
    })

    document.querySelectorAll('.page-link').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            inputPage.value = this.dataset.page;
            formFilter.submit();
        });
    });

    // Display main-options div
    document.addEventListener('DOMContentLoaded', () => {
        const mainOptions = document.getElementById("main_options");
        if (!mainOptions) {
            return;
        }

        if (mainOptions.style.display === 'none') {
            setTimeout(function () {
                mainOptions.style.display = 'block';
            }, 100);
        }
    })

    // Download data export
    document.addEventListener('DOMContentLoaded', () => {
        const btnExports = document.querySelectorAll('.btn-export');

        btnExports.forEach(element => {
            element.addEventListener('click', event => {
                event.preventDefault();
                exportData(urlExport, formFilter, filenameExport).then();
                event.stopPropagation();
            });
        });
    });
}

window.listFilter = listFilter;