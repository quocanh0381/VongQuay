/**
 * Environment Configuration
 * Cấu hình môi trường cho ứng dụng
 */

// Lấy môi trường hiện tại
export const currentEnv = process.env.NODE_ENV || 'development';

// Cấu hình môi trường
export const environmentConfig = {
    development: {
        name: 'development',
        debug: true,
        database: {
            host: '127.0.0.1',
            port: 3306,
            user: 'root',
            password: '',
            database: 'vongquay_db',
            charset: 'utf8mb4'
        },
        api: {
            baseUrl: 'http://localhost/vongquay/api',
            timeout: 30000
        },
        logging: {
            level: 'debug',
            console: true,
            file: true
        },
        cache: {
            enabled: true,
            ttl: 300000
        }
    },

    production: {
        name: 'production',
        debug: false,
        database: {
            host: process.env.DB_HOST || '127.0.0.1',
            port: parseInt(process.env.DB_PORT) || 3306,
            user: process.env.DB_USER || 'root',
            password: process.env.DB_PASSWORD || '',
            database: process.env.DB_NAME || 'vongquay_db',
            charset: 'utf8mb4'
        },
        api: {
            baseUrl: process.env.API_BASE_URL || 'https://yourdomain.com/api',
            timeout: 30000
        },
        logging: {
            level: 'error',
            console: false,
            file: true
        },
        cache: {
            enabled: true,
            ttl: 600000
        }
    },

    test: {
        name: 'test',
        debug: true,
        database: {
            host: '127.0.0.1',
            port: 3306,
            user: 'root',
            password: '',
            database: 'vongquay_test_db',
            charset: 'utf8mb4'
        },
        api: {
            baseUrl: 'http://localhost/vongquay/api/test',
            timeout: 10000
        },
        logging: {
            level: 'debug',
            console: true,
            file: false
        },
        cache: {
            enabled: false,
            ttl: 0
        }
    }
};

// Lấy cấu hình hiện tại
export function getCurrentConfig() {
    return environmentConfig[currentEnv] || environmentConfig.development;
}

// Kiểm tra môi trường
export function isDevelopment() {
    return currentEnv === 'development';
}

export function isProduction() {
    return currentEnv === 'production';
}

export function isTest() {
    return currentEnv === 'test';
}

// Cấu hình database theo môi trường
export function getDatabaseConfig() {
    const config = getCurrentConfig();
    return config.database;
}

// Cấu hình API theo môi trường
export function getApiConfig() {
    const config = getCurrentConfig();
    return config.api;
}

// Cấu hình logging theo môi trường
export function getLoggingConfig() {
    const config = getCurrentConfig();
    return config.logging;
}

// Cấu hình cache theo môi trường
export function getCacheConfig() {
    const config = getCurrentConfig();
    return config.cache;
}

// Environment variables
export const envVars = {
    NODE_ENV: process.env.NODE_ENV,
    DB_HOST: process.env.DB_HOST,
    DB_PORT: process.env.DB_PORT,
    DB_USER: process.env.DB_USER,
    DB_PASSWORD: process.env.DB_PASSWORD,
    DB_NAME: process.env.DB_NAME,
    API_BASE_URL: process.env.API_BASE_URL,
    DEBUG: process.env.DEBUG,
    LOG_LEVEL: process.env.LOG_LEVEL
};

// Validation
export function validateEnvironment() {
    const errors = [];
    const config = getCurrentConfig();

    // Kiểm tra database config
    if (!config.database.host) {
        errors.push('Database host is required');
    }
    if (!config.database.user) {
        errors.push('Database user is required');
    }
    if (!config.database.database) {
        errors.push('Database name is required');
    }

    // Kiểm tra API config
    if (!config.api.baseUrl) {
        errors.push('API base URL is required');
    }

    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

export default getCurrentConfig();
