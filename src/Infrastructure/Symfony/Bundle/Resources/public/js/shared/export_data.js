export async function exportData(urlExport, formFilter, filenameExport='data_export') {
    const formData = new FormData(formFilter);
    if (!urlExport) return;

    try {
        const response = await fetch(urlExport, {
            method: 'POST',
            body: formData,
            mode: 'no-cors',
            referrerPolicy: 'no-referrer',
        });

        if (!response.ok) {
            throw new Error('Error en la exportación de datos');
        }

        const blob = await response.blob();
        const fileName = generateFileName(filenameExport);

        downloadBlob(blob, fileName);
    } catch (error) {
        console.error('Error durante la exportación:', error);
    }
}

function generateFileName(filenameExport) {
    const now = new Date();
    return `${filenameExport}_${now.getFullYear()}${now.getMonth() + 1}${now.getDate()}.xlsx`;
}

function downloadBlob(blob, fileName) {
    const aElement = document.createElement('a');
    const href = URL.createObjectURL(blob);

    aElement.href = href;
    aElement.download = fileName;
    aElement.target = '_blank';
    aElement.click();

    URL.revokeObjectURL(href);
}
