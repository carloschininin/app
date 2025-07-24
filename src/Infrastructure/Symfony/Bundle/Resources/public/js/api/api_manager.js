// Configuración por defecto
const DEFAULT_CONFIG = {
    timeout: 10000,
    retries: 3,
    retryDelay: 1000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
};

// Tipos de errores específicos
class ApiError extends Error {
    constructor(message, status, response) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.response = response;
    }
}

class NetworkError extends Error {
    constructor(message) {
        super(message);
        this.name = 'NetworkError';
    }
}

class TimeoutError extends Error {
    constructor(message) {
        super(message);
        this.name = 'TimeoutError';
    }
}

// Utilitario para timeout
const withTimeout = (promise, ms) => {
    const timeout = new Promise((_, reject) =>
        setTimeout(() => reject(new TimeoutError(`Request timeout after ${ms}ms`)), ms)
    );
    return Promise.race([promise, timeout]);
};

// Utilitario para reintentos
const withRetry = async (fn, retries, delay) => {
    try {
        return await fn();
    } catch (error) {
        if (retries > 0 && shouldRetry(error)) {
            await sleep(delay);
            return withRetry(fn, retries - 1, delay);
        }
        throw error;
    }
};

const shouldRetry = (error) => {
    return error instanceof NetworkError ||
        (error instanceof ApiError && error.status >= 500);
};

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

// Gestor principal de APIs
export class ApiManager {
    constructor(baseConfig = {}) {
        this.config = { ...DEFAULT_CONFIG, ...baseConfig };
        this.interceptors = {
            request: [],
            response: []
        };
    }

    // Interceptores para transformar requests/responses
    addRequestInterceptor(interceptor) {
        this.interceptors.request.push(interceptor);
    }

    addResponseInterceptor(interceptor) {
        this.interceptors.response.push(interceptor);
    }

    // Aplicar interceptores de request
    async applyRequestInterceptors(config) {
        let processedConfig = config;
        for (const interceptor of this.interceptors.request) {
            processedConfig = await interceptor(processedConfig);
        }
        return processedConfig;
    }

    // Aplicar interceptores de response
    async applyResponseInterceptors(response) {
        let processedResponse = response;
        for (const interceptor of this.interceptors.response) {
            processedResponse = await interceptor(processedResponse);
        }
        return processedResponse;
    }

    // Método principal para hacer requests
    async request(url, options = {}) {
        const requestConfig = await this.prepareRequest(url, options);

        return withRetry(
            () => this.executeRequest(requestConfig),
            this.config.retries,
            this.config.retryDelay
        );
    }

    // Preparar configuración del request
    async prepareRequest(url, options) {
        const config = {
            url: this.buildUrl(url),
            method: options.method || 'GET',
            headers: { ...this.config.headers, ...options.headers },
            body: this.prepareBody(options.body, options.method),
            timeout: options.timeout || this.config.timeout
        };

        return this.applyRequestInterceptors(config);
    }

    // Construir URL completa
    buildUrl(url) {
        if (url.startsWith('http')) return url;
        return this.config.baseURL ? `${this.config.baseURL}${url}` : url;
    }

    // Preparar body del request
    prepareBody(body, method) {
        if (!body || method === 'GET') return undefined;

        if (body instanceof FormData) return body;
        return JSON.stringify(body);
    }

    // Ejecutar el request
    async executeRequest(config) {
        try {
            const fetchOptions = {
                method: config.method,
                headers: config.headers,
                body: config.body
            };

            const response = await withTimeout(
                fetch(config.url, fetchOptions),
                config.timeout
            );

            return this.handleResponse(response);
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Manejar respuesta
    async handleResponse(response) {
        const processedResponse = await this.applyResponseInterceptors(response);

        if (!processedResponse.ok) {
            const errorData = await this.extractErrorData(processedResponse);
            throw new ApiError(
                errorData.message || `HTTP Error ${processedResponse.status}`,
                processedResponse.status,
                errorData
            );
        }

        return this.parseResponse(processedResponse);
    }

    // Extraer datos de error
    async extractErrorData(response) {
        try {
            return await response.json();
        } catch {
            return { message: response.statusText };
        }
    }

    // Parsear respuesta exitosa
    async parseResponse(response) {
        try {
            return await response.json();
        } catch (error) {
            throw new ApiError('Invalid JSON response', response.status, null);
        }
    }

    // Manejar errores
    handleError(error) {
        if (error instanceof TimeoutError || error instanceof ApiError) {
            return error;
        }

        if (error.name === 'TypeError' && error.message.includes('fetch')) {
            return new NetworkError('Network connection failed');
        }

        return new NetworkError(error.message);
    }

    // Métodos de conveniencia
    async get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    }

    async post(url, data, options = {}) {
        return this.request(url, { ...options, method: 'POST', body: data });
    }

    async put(url, data, options = {}) {
        return this.request(url, { ...options, method: 'PUT', body: data });
    }

    async delete(url, options = {}) {
        return this.request(url, { ...options, method: 'DELETE' });
    }

    async patch(url, data, options = {}) {
        return this.request(url, { ...options, method: 'PATCH', body: data });
    }
}

// Instancia global
const apiManager = new ApiManager();
export default apiManager;
window.apiManager = apiManager;