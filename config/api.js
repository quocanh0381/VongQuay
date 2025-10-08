/**
 * API Configuration
 * Cấu hình các API endpoints cho vongquay_db
 */

export const apiEndpoints = {
    // Base URL
    baseUrl: 'http://localhost/vongquay/api',

    // Health check
    health: '/health',

    // User endpoints
    users: {
        list: '/users',
        get: '/users/:id',
        create: '/users',
        update: '/users/:id',
        delete: '/users/:id',
        login: '/auth/login',
        register: '/auth/register',
        profile: '/users/profile',
        stats: '/users/:id/stats'
    },

    // Room endpoints
    rooms: {
        list: '/rooms',
        get: '/rooms/:id',
        getByCode: '/rooms/code/:code',
        create: '/rooms',
        update: '/rooms/:id',
        delete: '/rooms/:id',
        join: '/rooms/join',
        leave: '/rooms/:id/leave',
        participants: '/rooms/:id/participants',
        stats: '/rooms/:id/stats'
    },

    // Match endpoints
    matches: {
        list: '/matches',
        get: '/matches/:id',
        getByRoom: '/matches/room/:roomId',
        create: '/matches',
        update: '/matches/:id',
        delete: '/matches/:id',
        start: '/matches/:id/start',
        end: '/matches/:id/end',
        stats: '/matches/:id/stats'
    },

    // Player endpoints
    players: {
        list: '/players',
        get: '/players/:id',
        getByMatch: '/players/match/:matchId',
        create: '/players',
        update: '/players/:id',
        delete: '/players/:id',
        stats: '/players/:id/stats'
    },

    // Team endpoints
    teams: {
        createSkill: '/teams/skill/:matchId',
        createRandom: '/teams/random/:matchId',
        get: '/teams/:matchId',
        update: '/teams/:matchId',
        delete: '/teams/:matchId'
    },

    // Statistics endpoints
    stats: {
        overall: '/stats/overall',
        user: '/stats/user/:userId',
        room: '/stats/room/:roomId',
        match: '/stats/match/:matchId'
    }
};

// HTTP Methods
export const httpMethods = {
    GET: 'GET',
    POST: 'POST',
    PUT: 'PUT',
    PATCH: 'PATCH',
    DELETE: 'DELETE'
};

// Response status codes
export const statusCodes = {
    OK: 200,
    CREATED: 201,
    NO_CONTENT: 204,
    BAD_REQUEST: 400,
    UNAUTHORIZED: 401,
    FORBIDDEN: 403,
    NOT_FOUND: 404,
    CONFLICT: 409,
    UNPROCESSABLE_ENTITY: 422,
    INTERNAL_SERVER_ERROR: 500
};

// API Configuration
export const apiConfig = {
    // Request timeout
    timeout: 30000,

    // Retry configuration
    retry: {
        attempts: 3,
        delay: 1000,
        backoff: 2
    },

    // Headers
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },

    // Authentication
    auth: {
        tokenKey: 'vongquay_token',
        refreshTokenKey: 'vongquay_refresh_token',
        tokenExpiry: 3600000, // 1 hour
        refreshTokenExpiry: 86400000 // 24 hours
    },

    // Cache configuration
    cache: {
        enabled: true,
        ttl: 300000, // 5 minutes
        maxSize: 1000
    },

    // Error handling
    errorHandling: {
        showUserFriendlyMessages: true,
        logErrors: true,
        retryOnError: true
    }
};

// Helper functions
export function buildUrl(endpoint, params = {}) {
    let url = endpoint;
    
    // Replace URL parameters
    Object.keys(params).forEach(key => {
        url = url.replace(`:${key}`, params[key]);
    });
    
    return url;
}

export function getFullUrl(endpoint, params = {}) {
    const url = buildUrl(endpoint, params);
    return `${apiEndpoints.baseUrl}${url}`;
}

// Request configuration
export function getRequestConfig(method = httpMethods.GET, data = null, headers = {}) {
    const config = {
        method,
        headers: {
            ...apiConfig.headers,
            ...headers
        }
    };

    if (data && (method === httpMethods.POST || method === httpMethods.PUT || method === httpMethods.PATCH)) {
        config.body = JSON.stringify(data);
    }

    return config;
}

export default apiEndpoints;
