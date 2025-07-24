class RouteManager {
    constructor() {
        this.routes = new Map();
        this.initialized = false;
    }

    /**
     * Inicializa el manager con las rutas desde el DOM
     * @throws {Error} Si no se encuentran las rutas
     */
    init() {
        if (this.initialized) {
            return this;
        }

        const routeElement = document.querySelector('[data-routes]');
        if (!routeElement) {
            throw new Error('Routes configuration not found in DOM');
        }

        try {
            const routesData = JSON.parse(routeElement.dataset.routes);
            this.loadRoutes(routesData);
            this.initialized = true;
        } catch (error) {
            throw new Error(`Failed to parse routes configuration: ${error.message}`);
        }

        return this;
    }

    /**
     * Carga las rutas en el Map interno
     * @param {Object} routesData
     */
    loadRoutes(routesData) {
        Object.entries(routesData).forEach(([name, url]) => {
            this.routes.set(name, url);
        });
    }

    /**
     * Obtiene una ruta por nombre
     * @param {string} routeName
     * @param {Object} parameters
     * @returns {string}
     */
    getRoute(routeName, parameters = {}) {
        if (!this.initialized) {
            throw new Error('RouteManager not initialized. Call init() first.');
        }

        const route = this.routes.get(routeName);
        if (!route) {
            throw new Error(`Route '${routeName}' not found`);
        }

        return this.replaceParameters(route, parameters);
    }

    /**
     * Reemplaza parámetros en la URL
     * @param {string} url
     * @param {Object} parameters
     * @returns {string}
     */
    replaceParameters(url, parameters) {
        return Object.entries(parameters).reduce((processedUrl, [key, value]) => {
            return processedUrl.replace(`{${key}}`, encodeURIComponent(value));
        }, url);
    }

    /**
     * Verifica si una ruta existe
     * @param {string} routeName
     * @returns {boolean}
     */
    hasRoute(routeName) {
        return this.routes.has(routeName);
    }

    /**
     * Obtiene todas las rutas disponibles
     * @returns {Array<string>}
     */
    getAvailableRoutes() {
        return Array.from(this.routes.keys());
    }
}

// Singleton instance
const routeManager = new RouteManager();

export default routeManager;