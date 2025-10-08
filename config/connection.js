/**
 * Database Connection Configuration
 * Cấu hình kết nối cơ sở dữ liệu
 */

import { getDatabaseConfig, getCurrentConfig } from './environment.js';

// Cấu hình kết nối database
export const connectionConfig = {
    // Cấu hình cơ bản
    host: getDatabaseConfig().host,
    port: getDatabaseConfig().port,
    user: getDatabaseConfig().user,
    password: getDatabaseConfig().password,
    database: getDatabaseConfig().database,
    charset: getDatabaseConfig().charset,

    // Cấu hình kết nối
    acquireTimeout: 60000,
    timeout: 60000,
    reconnect: true,
    reconnectInterval: 2000,
    maxReconnectAttempts: 5,

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

    // Cấu hình SSL (nếu cần)
    ssl: {
        enabled: false,
        rejectUnauthorized: true
    },

    // Cấu hình logging
    logging: {
        enabled: getCurrentConfig().debug,
        level: getCurrentConfig().logging.level
    }
};

// Cấu hình kết nối theo môi trường
export function getConnectionConfig() {
    const config = getCurrentConfig();
    
    return {
        ...connectionConfig,
        host: config.database.host,
        port: config.database.port,
        user: config.database.user,
        password: config.database.password,
        database: config.database.database,
        charset: config.database.charset
    };
}

// Cấu hình pool theo môi trường
export function getPoolConfig() {
    const config = getCurrentConfig();
    
    if (config.name === 'production') {
        return {
            ...connectionConfig.pool,
            min: 2,
            max: 20,
            acquireTimeoutMillis: 120000,
            createTimeoutMillis: 60000
        };
    } else if (config.name === 'test') {
        return {
            ...connectionConfig.pool,
            min: 0,
            max: 5,
            acquireTimeoutMillis: 30000,
            createTimeoutMillis: 15000
        };
    }
    
    return connectionConfig.pool;
}

// Cấu hình SSL theo môi trường
export function getSSLConfig() {
    const config = getCurrentConfig();
    
    if (config.name === 'production') {
        return {
            enabled: true,
            rejectUnauthorized: true
        };
    }
    
    return {
        enabled: false,
        rejectUnauthorized: false
    };
}

// Helper functions
export function buildConnectionString() {
    const config = getConnectionConfig();
    return `mysql://${config.user}:${config.password}@${config.host}:${config.port}/${config.database}`;
}

export function validateConnectionConfig() {
    const config = getConnectionConfig();
    const errors = [];
    
    if (!config.host) {
        errors.push('Database host is required');
    }
    if (!config.port) {
        errors.push('Database port is required');
    }
    if (!config.user) {
        errors.push('Database user is required');
    }
    if (!config.database) {
        errors.push('Database name is required');
    }
    
    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

// Connection status
export const connectionStatus = {
    DISCONNECTED: 'disconnected',
    CONNECTING: 'connecting',
    CONNECTED: 'connected',
    ERROR: 'error'
};

// Connection events
export const connectionEvents = {
    CONNECT: 'connect',
    DISCONNECT: 'disconnect',
    ERROR: 'error',
    RECONNECT: 'reconnect'
};

export default connectionConfig;
