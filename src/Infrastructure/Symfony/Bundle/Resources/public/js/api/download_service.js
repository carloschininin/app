
/**
 * Servicio para manejo de descargas de archivos
 * Implementa principios Clean Code y SOLID
 */
export class DownloadService {
    constructor() {
        this.defaultFileName = 'noname.ext';
    }

    /**
     * Descarga un archivo desde una URL
     * @param {string} path - URL del archivo a descargar
     * @param {Object} params - Parámetros opcionales para la petición
     * @returns {Promise<void>}
     */
    async download(path, params = {}) {
        try {
            const url = this.buildUrl(path, params);
            const response = await this.fetchFile(url);

            if (!response.ok) {
                throw new Error(`Error en la descarga: ${response.status}`);
            }

            const blob = await response.blob();
            const fileName = this.extractFileName(response.headers);

            this.triggerDownload(blob, fileName);
        } catch (error) {
            console.error('Error al descargar archivo:', error);
            throw error;
        }
    }

    /**
     * Construye la URL con parámetros
     * @param {string} path - URL base
     * @param {Object} params - Parámetros a agregar
     * @returns {string} URL completa
     */
    buildUrl(path, params) {
        if (!params || Object.keys(params).length === 0) {
            return path;
        }

        const url = new URL(path, window.location.origin);
        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                url.searchParams.append(key, value);
            }
        });

        return url.toString();
    }

    /**
     * Realiza la petición HTTP
     * @param {string} url - URL a consultar
     * @returns {Promise<Response>}
     */
    async fetchFile(url) {
        return fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
    }

    /**
     * Extrae el nombre del archivo de los headers
     * @param {Headers} headers - Headers de la respuesta
     * @returns {string} Nombre del archivo
     */
    extractFileName(headers) {
        const contentDisposition = headers.get('content-disposition');

        if (!contentDisposition) {
            return this.defaultFileName;
        }

        const disposition = contentDisposition.split(';');

        if (disposition.length < 2) {
            return this.defaultFileName;
        }

        const filenamePart = disposition[1].trim();

        if (!filenamePart.startsWith('filename=')) {
            return this.defaultFileName;
        }

        return filenamePart.substring(9).replace(/"/g, '');
    }

    /**
     * Ejecuta la descarga del archivo
     * @param {Blob} blob - Contenido del archivo
     * @param {string} fileName - Nombre del archivo
     */
    triggerDownload(blob, fileName) {
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName;

        // Agregar al DOM temporalmente para compatibilidad
        document.body.appendChild(link);
        link.click();

        // Limpiar recursos
        document.body.removeChild(link);
        window.URL.revokeObjectURL(link.href);
    }
}