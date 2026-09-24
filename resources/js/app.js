import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

window.DevDoko = {
    init() {
        this.initDarkMode();
        this.initHighlight();
    },

    // Toast notification system
    toast(message, type = 'success', duration = 4000) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-circle-fill',
            info: 'bi-info-circle-fill',
        };

        const bgClasses = {
            success: 'bg-success',
            error: 'bg-danger',
            warning: 'bg-warning text-dark',
            info: 'bg-primary',
        };

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white ${bgClasses[type] || 'bg-primary'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${icons[type] || 'bi-info-circle-fill'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        container.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: duration });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    },

    // Copy to clipboard with feedback
    copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            if (btn) {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = orig;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            } else {
                this.toast('Copied to clipboard!', 'success');
            }
        }).catch(() => {
            this.toast('Failed to copy', 'error');
        });
    },

    // Confirm dialog
    async confirm(message = 'Are you sure?') {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            if (!modal) {
                resolve(confirm(message));
                return;
            }
            modal.querySelector('.confirm-message').textContent = message;
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            modal.querySelector('.btn-confirm-yes').onclick = () => {
                bsModal.hide();
                resolve(true);
            };
            modal.querySelector('.btn-confirm-no').onclick = () => {
                bsModal.hide();
                resolve(false);
            };
            modal.addEventListener('hidden.bs.modal', () => resolve(false), { once: true });
        });
    },

    // AJAX helper
    async fetch(url, options = {}) {
        const defaultHeaders = {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Accept': 'application/json',
        };

        const config = {
            headers: { ...defaultHeaders, ...options.headers },
            ...options,
        };

        if (config.body && typeof config.body === 'object' && !(config.body instanceof FormData)) {
            config.body = JSON.stringify(config.body);
            config.headers['Content-Type'] = 'application/json';
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw { status: response.status, data };
            }

            return data;
        } catch (error) {
            if (error.status) throw error;
            throw { status: 0, data: { error: 'Network error' } };
        }
    },

    // Dark mode
    initDarkMode() {
        const saved = localStorage.getItem('devdoko-theme');
        const theme = saved || 'dark';
        this.setTheme(theme);
    },

    setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('devdoko-theme', theme);
        const icon = theme === 'dark' ? 'bi-sun-fill' : 'bi-moon-fill';
        const label = theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
        document.querySelectorAll('[id^="darkModeToggle"]').forEach(el => {
            el.querySelector('i')?.className && (el.querySelector('i').className = icon + ' fs-6');
            el.setAttribute('aria-label', label);
        });
    },

    toggleTheme() {
        const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
        this.setTheme(current === 'dark' ? 'light' : 'dark');
    },

    initHighlight() {
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }
    },

    // Loading state for buttons
    setLoading(btn, loading = true) {
        if (!btn) return;
        if (loading) {
            btn._origHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
            btn.disabled = true;
        } else {
            btn.innerHTML = btn._origHtml || btn.innerHTML;
            btn.disabled = false;
        }
    },
};

document.addEventListener('DOMContentLoaded', () => {
    window.DevDoko.init();

    // Close alerts automatically
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert?.getInstance(alert);
            if (bsAlert) bsAlert.close();
            else alert.remove();
        }, 5000);
    });
});
