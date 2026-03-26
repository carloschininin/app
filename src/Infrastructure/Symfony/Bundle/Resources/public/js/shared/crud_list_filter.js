import exportData from './export_data';

function initCore(formFilter, inputPage, inputLimit) {
    const filterSize = document.querySelector("#filter_size");
    const btnEnviar = document.querySelector("#btn_apply");
    const btnReset = document.querySelector("#btn_reset");

    if (filterSize) {
        filterSize.addEventListener('change', function(e) {
            e.preventDefault();
            inputPage.value = 1;
            inputLimit.value = filterSize.value;
            formFilter.submit();
        });
    }

    if (btnEnviar) {
        btnEnviar.addEventListener('click', function(e) {
            e.preventDefault();
            inputPage.value = 1;
            formFilter.submit();
        });
    }

    if (btnReset) {
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
        });
    }

    document.querySelectorAll('.page-link').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            inputPage.value = this.dataset.page;
            formFilter.submit();
        });
    });

    const mainOptions = document.getElementById("main_options");
    if (mainOptions && mainOptions.style.display === 'none') {
        setTimeout(function () {
            mainOptions.style.display = 'block';
        }, 100);
    }
}

function initExport(urlExport, formFilter, filenameExport) {
    if (!urlExport || formFilter._exportInitialized) return;
    formFilter._exportInitialized = true;

    document.querySelectorAll('.btn-export').forEach(element => {
        element.addEventListener('click', event => {
            event.preventDefault();
            exportData(urlExport, formFilter, filenameExport).then();
            event.stopPropagation();
        });
    });
}

// Auto-inicialización — no requiere llamar listFilter() explícitamente.
// El export se configura automáticamente si el form tiene data-export-url.
// Ejemplo: <form id="form_filter" data-export-url="/api/export" data-export-filename="ventas">
document.addEventListener('DOMContentLoaded', () => {
    const formFilter = document.querySelector('#form_filter') || document.querySelector('[name="form_filter"]');
    if (!formFilter) return;

    const inputPage = document.querySelector("#form_filter_page");
    const inputLimit = document.querySelector("#form_filter_limit");
    if (!inputPage) return;

    initCore(formFilter, inputPage, inputLimit);

    const urlExport = formFilter.dataset.exportUrl || null;
    const filenameExport = formFilter.dataset.exportFilename || 'export_data';
    initExport(urlExport, formFilter, filenameExport);
});

// API de navegación por páginas — disponible en cualquier página con form_filter,
// sin necesidad de llamar listFilter() explícitamente.
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#form_filter') || document.querySelector('[name="form_filter"]');
    const inputPage = document.querySelector('#form_filter_page');
    if (!form || !inputPage) return;

    window.goToPage = function (page) {
        if (page == null) return;
        inputPage.value = page;
        form.submit();
    };
});

// Backward compatibility — para sistemas que ya llaman listFilter(urlExport, filenameExport).
// La inicialización core ya ocurrió en DOMContentLoaded; esta función solo configura el export.
/** @deprecated Ya no es necesaria */
const listFilter = function (urlExport = null, filenameExport = 'export_data') {
    const formFilter = document.querySelector('#form_filter') || document.querySelector('[name="form_filter"]');
    if (!formFilter) return;
    initExport(urlExport, formFilter, filenameExport);
};

/** @deprecated Usar Data exportUrl and exportFilename */
window.listFilter = listFilter;