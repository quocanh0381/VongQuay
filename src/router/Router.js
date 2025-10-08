/**
 * Router - Xử lý điều hướng trong ứng dụng
 */
export class Router {
    constructor() {
        this.routes = new Map();
        this.currentRoute = '/';
    }

    addRoute(path, handler) {
        this.routes.set(path, handler);
    }

    navigate(path) {
        if (this.routes.has(path)) {
            this.currentRoute = path;
            history.pushState({ route: path }, '', path);
            this.executeRoute(path);
        } else {
            console.warn(`Route ${path} not found`);
            this.navigate('/'); // Fallback to home
        }
    }

    executeRoute(path) {
        const handler = this.routes.get(path);
        if (handler) {
            // Clear current content
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                mainContent.innerHTML = '';
            }
            
            // Update active navigation
            this.updateActiveNavigation(path);
            
            // Execute route handler
            handler();
        }
    }

    updateActiveNavigation(path) {
        // Remove active class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to current route
        const activeLink = document.querySelector(`[data-route="${path}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }

    handlePopState(event) {
        const path = event.state?.route || window.location.pathname;
        this.currentRoute = path;
        this.executeRoute(path);
    }

    init() {
        // Load initial route
        const initialPath = window.location.pathname || '/';
        this.navigate(initialPath);
    }

    getCurrentRoute() {
        return this.currentRoute;
    }
}
