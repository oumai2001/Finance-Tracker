
const body = document.body;
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const closeMobileNav = document.getElementById('closeMobileNav');
const mobileNav = document.getElementById('mobileNav');
const mobileOverlay = document.getElementById('mobileOverlay');
const themeToggle = document.getElementById('themeToggle');
const scrollBtn = document.getElementById('scrollToTop');
const userProfile = document.querySelector('.user-profile');
const notificationBtn = document.getElementById('notificationBtn');

function toggleMobileMenu() {
    const isActive = mobileNav.classList.contains('active');
    
    if (isActive) {
        closeMobileMenu();
    } else {
        openMobileMenu();
    }
}

function openMobileMenu() {
    mobileNav.classList.add('active');
    mobileOverlay.classList.add('active');
    body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    mobileNav.classList.remove('active');
    mobileOverlay.classList.remove('active');
    body.style.overflow = '';
}

// Event Listeners pour le menu mobile
if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);
}

if (closeMobileNav) {
    closeMobileNav.addEventListener('click', closeMobileMenu);
}

if (mobileOverlay) {
    mobileOverlay.addEventListener('click', closeMobileMenu);
}

// Fermer le menu mobile lors du redimensionnement
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth > 1024 && mobileNav.classList.contains('active')) {
            closeMobileMenu();
        }
    }, 250);
});

function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        enableDarkTheme();
    }
}

function enableDarkTheme() {
    body.classList.add('dark-theme');
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }
}

function enableLightTheme() {
    body.classList.remove('dark-theme');
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }
}

function toggleTheme() {
    const isDark = body.classList.contains('dark-theme');
    
    if (isDark) {
        enableLightTheme();
        localStorage.setItem('theme', 'light');
    } else {
        enableDarkTheme();
        localStorage.setItem('theme', 'dark');
    }
    
    // Animation de transition
    body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
}

if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
}

function updateScrollButton() {
    if (window.pageYOffset > 300) {
        scrollBtn?.classList.add('visible');
    } else {
        scrollBtn?.classList.remove('visible');
    }
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

window.addEventListener('scroll', updateScrollButton);

if (scrollBtn) {
    scrollBtn.addEventListener('click', scrollToTop);
}

function toggleProfileDropdown(e) {
    e.stopPropagation();
    userProfile.classList.toggle('active');
    
    // Fermer les autres dropdowns
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        if (dropdown !== userProfile) {
            dropdown.classList.remove('active');
        }
    });
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown, .user-profile').forEach(dropdown => {
        dropdown.classList.remove('active');
    });
}

if (userProfile) {
    userProfile.addEventListener('click', toggleProfileDropdown);
}

document.addEventListener('click', closeAllDropdowns);

// Empêcher la fermeture lors du clic dans le dropdown
document.querySelectorAll('.profile-dropdown').forEach(dropdown => {
    dropdown.addEventListener('click', (e) => {
        e.stopPropagation();
    });
});


function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}


function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
            showFieldError(field, 'Ce champ est obligatoire');
        } else {
            field.classList.remove('error');
            removeFieldError(field);
        }
    });
    
    return isValid;
}

function showFieldError(field, message) {
    const existingError = field.parentElement.querySelector('.field-error');
    if (existingError) {
        existingError.textContent = message;
        return;
    }
    
    const errorElement = document.createElement('span');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    field.parentElement.appendChild(errorElement);
}

function removeFieldError(field) {
    const errorElement = field.parentElement.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Validation en temps réel
document.querySelectorAll('input[required], textarea[required], select[required]').forEach(field => {
    field.addEventListener('blur', () => {
        if (!field.value.trim()) {
            field.classList.add('error');
            showFieldError(field, 'Ce champ est obligatoire');
        } else {
            field.classList.remove('error');
            removeFieldError(field);
        }
    });
    
    field.addEventListener('input', () => {
        if (field.value.trim()) {
            field.classList.remove('error');
            removeFieldError(field);
        }
    });
});


function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
    return confirm(message);
}

// Attacher aux liens de suppression
document.querySelectorAll('a[href*="delete"], button[data-action="delete"]').forEach(element => {
    element.addEventListener('click', (e) => {
        if (!confirmDelete()) {
            e.preventDefault();
        }
    });
});


function initTableSearch() {
    const searchInput = document.getElementById('tableSearch');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const table = document.querySelector('table tbody');
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}


function animateValue(element, start, end, duration) {
    const startTime = performance.now();
    const difference = end - start;
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = start + (difference * easeOutQuad(progress));
        element.textContent = formatNumber(current);
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

function easeOutQuad(t) {
    return t * (2 - t);
}

function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(num);
}

// Animer les montants au chargement
function animateAmounts() {
    document.querySelectorAll('.amount').forEach(element => {
        const value = parseFloat(element.textContent.replace(/[^0-9.-]+/g, ''));
        if (!isNaN(value)) {
            element.textContent = '0.00';
            setTimeout(() => animateValue(element, 0, value, 1000), 100);
        }
    });
}


function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}


function updateOnlineStatus(isOnline) {
    const statusDiv = document.getElementById('onlineStatus');
    if (!statusDiv) return;
    
    if (isOnline) {
        statusDiv.innerHTML = `
            <div style="background: var(--success); color: white; padding: 0.75rem; text-align: center; animation: slideInFromTop 0.3s ease;">
                <i class="fas fa-check-circle"></i> Connexion rétablie
            </div>
        `;
        setTimeout(() => statusDiv.innerHTML = '', 3000);
    } else {
        statusDiv.innerHTML = `
            <div style="background: var(--danger); color: white; padding: 0.75rem; text-align: center; animation: slideInFromTop 0.3s ease;">
                <i class="fas fa-exclamation-triangle"></i> Mode hors ligne - Fonctionnalités limitées
            </div>
        `;
    }
}

window.addEventListener('online', () => updateOnlineStatus(true));
window.addEventListener('offline', () => updateOnlineStatus(false));


function makeChartsResponsive() {
    const charts = document.querySelectorAll('canvas');
    
    charts.forEach(canvas => {
        const parent = canvas.parentElement;
        if (parent) {
            canvas.style.maxWidth = '100%';
            canvas.style.height = 'auto';
        }
    });
}


function initTooltips() {
    document.querySelectorAll('[title]').forEach(element => {
        element.addEventListener('mouseenter', (e) => {
            const title = e.target.getAttribute('title');
            if (!title) return;
            
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = title;
            document.body.appendChild(tooltip);
            
            const rect = e.target.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
            
            setTimeout(() => tooltip.classList.add('show'), 10);
            
            e.target.setAttribute('data-original-title', title);
            e.target.removeAttribute('title');
        });
        
        element.addEventListener('mouseleave', (e) => {
            const tooltip = document.querySelector('.custom-tooltip');
            if (tooltip) {
                tooltip.classList.remove('show');
                setTimeout(() => tooltip.remove(), 200);
            }
            
            const originalTitle = e.target.getAttribute('data-original-title');
            if (originalTitle) {
                e.target.setAttribute('title', originalTitle);
                e.target.removeAttribute('data-original-title');
            }
        });
    });
}


document.addEventListener('DOMContentLoaded', () => {
    // Initialiser le thème
    initTheme();
    
    // Initialiser les fonctionnalités
    initTableSearch();
    initLazyLoading();
    initTooltips();
    makeChartsResponsive();
    
    // Animer les montants après un court délai
    setTimeout(animateAmounts, 300);
    
    // Afficher un message de bienvenue
    console.log('%c💰 Finance Tracker', 'font-size: 20px; color: #6366f1; font-weight: bold;');
    console.log('%cApplication chargée avec succès!', 'color: #10b981;');
});


window.addEventListener('error', (e) => {
    console.error('Erreur:', e.error);
});

window.addEventListener('unhandledrejection', (e) => {
    console.error('Promise rejetée:', e.reason);
});


window.FinanceTracker = {
    showNotification,
    confirmDelete,
    validateForm,
    toggleTheme,
    animateValue
};