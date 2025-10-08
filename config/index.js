/**
 * Configuration Index
 * Export tất cả cấu hình từ thư mục config
 */

// Database configuration
export { databaseConfig, getConfig } from './database.js';

// API configuration
export { 
    apiEndpoints, 
    httpMethods, 
    statusCodes, 
    apiConfig,
    buildUrl,
    getFullUrl,
    getRequestConfig
} from './api.js';

// Environment configuration
export {
    currentEnv,
    environmentConfig,
    getCurrentConfig,
    isDevelopment,
    isProduction,
    isTest,
    getDatabaseConfig,
    getApiConfig,
    getLoggingConfig,
    getCacheConfig,
    envVars,
    validateEnvironment
} from './environment.js';

// Connection configuration
export {
    connectionConfig,
    getConnectionConfig,
    getPoolConfig,
    getSSLConfig,
    buildConnectionString,
    validateConnectionConfig,
    connectionStatus,
    connectionEvents
} from './connection.js';

// Default export
export default {
    database: databaseConfig,
    api: apiEndpoints,
    environment: getCurrentConfig(),
    connection: getConnectionConfig()
};
