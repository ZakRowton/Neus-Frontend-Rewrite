/**
 * NEUS Frontend Rewrite - Main Application JavaScript
 * SPA Router, State Management, API Client, UI Interactions
 */

// === Global State ===
const NeusApp = {
    state: {
        user: null,
        wallet: null,
        isAuthenticated: false,
        theme: 'dark',
        sidebarOpen: false,
        mobileMenuOpen: false,
        userMenuOpen: false,
        notifications: [],
        loading: false,
    },
    
    config: {
        apiBase: '/api',
        debounceDelay: 300,
        toastDuration: 5000,
    },
    
    init() {
        this.loadTheme();
        this.bindEvents();
        this.checkAuth();
        this.initNavigation();
        this.initAnimations();
    },
    
    // === Theme ===
    loadTheme() {
        const saved = localStorage.getItem('neus-theme') || 'dark';
        this.setTheme(saved);
    },
    
    setTheme(theme) {
        this.state.theme = theme;
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('neus-theme', theme);
        
        const darkIcon = document.querySelector('.dark-icon');
        const lightIcon = document.querySelector('.light-icon');
        if (darkIcon && lightIcon) {
            darkIcon.classList.toggle('hidden', theme !== 'dark');
            lightIcon.classList.toggle('hidden', theme === 'dark');
        }
    },
    
    toggleTheme() {
        this.setTheme(this.state.theme === 'dark' ? 'light' : 'dark');
    },
    
    // === Auth ===
    async checkAuth() {
        try {
            const response = await this.api('/auth/me');
            if (response.success && response.data?.authenticated) {
                this.state.user = response.data;
                this.state.isAuthenticated = true;
                if (response.data.wallet) {
                    this.state.wallet = response.data.wallet;
                }
            }
        } catch (e) {
            console.log('Auth check failed:', e);
        }
    },
    
    setAuth(user, token) {
        this.state.user = user;
        this.state.isAuthenticated = true;
        if (token) {
            localStorage.setItem('neus_token', token);
        }
    },
    
    clearAuth() {
        this.state.user = null;
        this.state.wallet = null;
        this.state.isAuthenticated = false;
        localStorage.removeItem('neus_token');
    },
    
    getToken() {
        return localStorage.getItem('neus_token') || this.getCookie('neus_auth');
    },
    
    getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    },
    
    // === API Client ===
    async api(endpoint, options = {}) {
        const url = this.config.apiBase + '/' + endpoint.replace(/^\//, '');
        
        const defaults = {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        };
        
        const token = this.getToken();
        if (token) {
            defaults.headers['Authorization'] = 'Bearer ' + token;
            defaults.headers['X-Auth-Token'] = token;
        }
        
        const config = { ...defaults, ...options };
        if (options.headers) {
            config.headers = { ...defaults.headers, ...options.headers };
        }
        
        if (config.body && typeof config.body === 'object') {
            config.body = JSON.stringify(config.body);
        }
        
        try {
            const response = await fetch(url, config);
            const data = await response.json().catch(() => null);
            
            return {
                success: response.ok,
                status: response.status,
                data: data,
                raw: data,
            };
        } catch (error) {
            return {
                success: false,
                error: error.message,
                status: 0,
            };
        }
    },
    
    // === Event Binding ===
    bindEvents() {
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
        
        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                this.state.mobileMenuOpen = !mobileMenu.classList.contains('hidden');
            });
        }
        
        // User menu
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
                this.state.userMenuOpen = !userDropdown.classList.contains('hidden');
            });
            
            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                    this.state.userMenuOpen = false;
                }
            });
        }
        
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                this.state.sidebarOpen = !sidebar.classList.contains('-translate-x-full');
            });
        }
        
        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && sidebar && !sidebar.contains(e.target) && !sidebarToggle?.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
                this.state.sidebarOpen = false;
            }
        });
        
        // Header scroll effect
        window.addEventListener('scroll', this.debounce(() => {
            const header = document.getElementById('neus-header');
            if (header) {
                if (window.scrollY > 50) {
                    header.querySelector('.header-glass')?.classList.add('bg-neus-black/95');
                } else {
                    header.querySelector('.header-glass')?.classList.remove('bg-neus-black/95');
                }
            }
        }, 100));
    },
    
    // === Navigation (SPA-style) ===
    initNavigation() {
        // Intercept all internal link clicks for SPA feel
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="/"]');
            if (!link) return;
            if (link.hasAttribute('data-external')) return;
            if (link.target === '_blank') return;
            
            e.preventDefault();
            this.navigate(link.getAttribute('href'));
        });
    },
    
    navigate(url) {
        // Show loading
        this.showLoading();
        
        // Navigate
        window.location.href = url;
    },
    
    // === UI Helpers ===
    showLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }
        this.state.loading = true;
    },
    
    hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
        this.state.loading = false;
    },
    
    toast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, this.config.toastDuration);
    },
    
    // === Animations ===
    initAnimations() {
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px',
        });
        
        document.querySelectorAll('[data-animate]').forEach(el => {
            observer.observe(el);
        });
    },
    
    // === Utilities ===
    debounce(fn, delay) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    },
    
    formatAddress(address) {
        if (!address || address.length < 10) return address;
        return address.slice(0, 6) + '...' + address.slice(-4);
    },
    
    formatDate(date) {
        if (!date) return 'N/A';
        const d = new Date(date);
        return d.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        });
    },
    
    timeAgo(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        if (seconds < 60) return 'Just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + 'm ago';
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return hours + 'h ago';
        const days = Math.floor(hours / 24);
        if (days < 7) return days + 'd ago';
        return this.formatDate(date);
    },
    
    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.toast('Copied to clipboard', 'success');
        }).catch(() => {
            this.toast('Failed to copy', 'error');
        });
    },
    
    // === Form Helpers ===
    serializeForm(form) {
        const data = new FormData(form);
        const obj = {};
        data.forEach((value, key) => {
            if (obj[key]) {
                if (Array.isArray(obj[key])) {
                    obj[key].push(value);
                } else {
                    obj[key] = [obj[key], value];
                }
            } else {
                obj[key] = value;
            }
        });
        return obj;
    },
    
    validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    validateWallet(address) {
        return /^0x[a-fA-F0-9]{40}$/.test(address);
    },
    
    // === Wallet Integration ===
    async connectWallet() {
        if (!window.ethereum) {
            this.toast('Please install MetaMask or another Web3 wallet', 'warning');
            return null;
        }
        
        try {
            const accounts = await window.ethereum.request({
                method: 'eth_requestAccounts',
            });
            
            if (accounts.length > 0) {
                this.state.wallet = accounts[0];
                return accounts[0];
            }
        } catch (error) {
            this.toast('Wallet connection failed: ' + error.message, 'error');
            return null;
        }
    },
    
    async signMessage(message) {
        if (!this.state.wallet || !window.ethereum) {
            this.toast('Wallet not connected', 'error');
            return null;
        }
        
        try {
            const signature = await window.ethereum.request({
                method: 'personal_sign',
                params: [message, this.state.wallet],
            });
            return signature;
        } catch (error) {
            this.toast('Signing failed: ' + error.message, 'error');
            return null;
        }
    },
    
    // === Real-time Updates ===
    initRealtime() {
        // WebSocket or SSE connection for real-time updates
        if (typeof EventSource !== 'undefined') {
            const token = this.getToken();
            if (token) {
                // Connect to SSE endpoint if available
                // const es = new EventSource('/api/events?token=' + token);
                // es.onmessage = (e) => this.handleRealtimeMessage(JSON.parse(e.data));
            }
        }
    },
    
    handleRealtimeMessage(data) {
        if (data.type === 'notification') {
            this.toast(data.message, data.level || 'info');
        }
        // Handle other realtime message types
    },
};

// === Initialize on DOM Ready ===
document.addEventListener('DOMContentLoaded', () => {
    NeusApp.init();
});

// === Expose globally ===
window.NeusApp = NeusApp;
