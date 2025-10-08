/**
 * Database Configuration
 * Cấu hình kết nối cơ sở dữ liệu vongquay_db
 */

export const databaseConfig = {
    // Cấu hình cơ sở dữ liệu
    database: {
        host: '127.0.0.1',
        port: 3306,
        user: 'root',
        password: '',
        database: 'vongquay_db',
        charset: 'utf8mb4',
        timezone: '+00:00'
    },

    // Cấu hình kết nối
    connection: {
        acquireTimeout: 60000,
        timeout: 60000,
        reconnect: true,
        reconnectInterval: 2000,
        maxReconnectAttempts: 5
    },

    // Cấu hình pool
    pool: {
        min: 0,
        max: 10,
        acquireTimeoutMillis: 60000,
        createTimeoutMillis: 30000,
        destroyTimeoutMillis: 5000,
        idleTimeoutMillis: 30000,
        reapIntervalMillis: 1000,
        createRetryIntervalMillis: 200
    },

    // Cấu hình API
    api: {
        baseUrl: 'http://localhost/vongquay/api',
        timeout: 30000,
        retryAttempts: 3,
        retryDelay: 1000
    },

    // Cấu hình cache
    cache: {
        enabled: true,
        ttl: 300000, // 5 minutes
        maxSize: 1000
    },

    // Cấu hình logging
    logging: {
        enabled: true,
        level: 'info', // debug, info, warn, error
        file: 'logs/database.log'
    }
};

// Cấu hình môi trường
export const environmentConfig = {
    development: {
        ...databaseConfig,
        database: {
            ...databaseConfig.database,
            host: '127.0.0.1',
            port: 3306,
            user: 'root',
            password: '',
            database: 'vongquay_db'
        },
        api: {
            ...databaseConfig.api,
            baseUrl: 'http://localhost/vongquay/api'
        }
    },

    production: {
        ...databaseConfig,
        database: {
            ...databaseConfig.database,
            host: process.env.DB_HOST || '127.0.0.1',
            port: parseInt(process.env.DB_PORT) || 3306,
            user: process.env.DB_USER || 'root',
            password: process.env.DB_PASSWORD || '',
            database: process.env.DB_NAME || 'vongquay_db'
        },
        api: {
            ...databaseConfig.api,
            baseUrl: process.env.API_BASE_URL || 'https://yourdomain.com/api'
        }
    },

    test: {
        ...databaseConfig,
        database: {
            ...databaseConfig.database,
            database: 'vongquay_test_db'
        },
        api: {
            ...databaseConfig.api,
            baseUrl: 'http://localhost/vongquay/api/test'
        }
    }
};

// Lấy cấu hình theo môi trường
export function getConfig(env = 'development') {
    return environmentConfig[env] || environmentConfig.development;
}

// Cấu hình mặc định
export default databaseConfig;
