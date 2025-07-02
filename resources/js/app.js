import './bootstrap';

// Enable Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Auto-dismiss alerts after 5 seconds
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirm before delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});

// Image preview for file inputs
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).setAttribute('src', e.target.result);
            document.getElementById(previewId).style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize image previews
document.querySelectorAll('.image-preview-input').forEach(input => {
    input.addEventListener('change', function() {
        previewImage(this, this.dataset.previewId);
    });
});

const API_BASE_URL = '/api';

let authToken = localStorage.getItem('token') || null;
let isRefreshing = false;
let refreshSubscribers = [];

// Helper function to handle API requests
async function apiRequest(url, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    };

    if (authToken) {
        headers['Authorization'] = `Bearer ${authToken}`;
    }

    const response = await fetch(`${API_BASE_URL}${url}`, {
        ...options,
        headers,
        credentials: 'include' // For CSRF protection
    });

    // Handle token expiration
    if (response.status === 401) {
        const errorData = await response.json().catch(() => ({}));
        
        if (errorData.code === 'token_expired') {
            return handleTokenRefresh(url, options);
        }
        
        // If not token expiration, redirect to login
        window.location.href = '/login';
        throw new Error('Unauthorized');
    }

    if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.message || 'Request failed');
    }

    return response.json();
}

// Handle token refresh
async function handleTokenRefresh(originalUrl, originalOptions) {
    if (isRefreshing) {
        return new Promise((resolve) => {
            refreshSubscribers.push(() => {
                resolve(apiRequest(originalUrl, originalOptions));
            });
        });
    }

    isRefreshing = true;
    
    try {
        const refreshResponse = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: localStorage.getItem('email'),
                password: localStorage.getItem('password')
            }),
            credentials: 'include'
        });

        if (!refreshResponse.ok) {
            throw new Error('Token refresh failed');
        }

        const data = await refreshResponse.json();
        authToken = data.token;
        localStorage.setItem('token', data.token);

        // Retry original request
        const retryResponse = await apiRequest(originalUrl, originalOptions);
        
        // Process subscribers
        refreshSubscribers.forEach(cb => cb());
        refreshSubscribers = [];
        
        return retryResponse;
    } catch (error) {
        // Clear auth data and redirect to login
        localStorage.removeItem('token');
        localStorage.removeItem('email');
        window.location.href = '/login';
        throw error;
    } finally {
        isRefreshing = false;
    }
}

// API methods
export const api = {
    get: (url) => apiRequest(url),
    post: (url, data) => apiRequest(url, { method: 'POST', body: JSON.stringify(data) }),
    put: (url, data) => apiRequest(url, { method: 'PUT', body: JSON.stringify(data) }),
    patch: (url, data) => apiRequest(url, { method: 'PATCH', body: JSON.stringify(data) }),
    delete: (url) => apiRequest(url, { method: 'DELETE' })
};

// Initialize auth token
export function setAuthToken(token) {
    authToken = token;
    if (token) {
        localStorage.setItem('token', token);
    } else {
        localStorage.removeItem('token');
    }
}

// Login helper
export async function login(email, password) {
    const response = await api.post('/login', { email, password });
    setAuthToken(response.token);
    localStorage.setItem('email', email);
    return response;
}

// Logout helper
export function logout() {
    setAuthToken(null);
    localStorage.removeItem('email');
    return api.post('/logout');
}