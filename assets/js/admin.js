// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle for mobile
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const sidebarClose = document.querySelector('.sidebar-close');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    if (sidebarClose && sidebar) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (sidebar && sidebar.classList.contains('active')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        }
    });
    
    // Navigation dropdown functionality
    const navDropdowns = document.querySelectorAll('.nav-dropdown');
    
    navDropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Close other dropdowns
            navDropdowns.forEach(other => {
                if (other !== dropdown) {
                    other.classList.remove('active');
                    const otherMenu = other.nextElementSibling;
                    if (otherMenu && otherMenu.classList.contains('dropdown-menu')) {
                        otherMenu.classList.remove('active');
                    }
                }
            });
            
            // Toggle current dropdown
            this.classList.toggle('active');
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                dropdownMenu.classList.toggle('active');
            }
        });
    });
    
    // Auto-save form data
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        const formId = form.id || 'admin-form';
        
        // Load saved data
        const savedData = localStorage.getItem(`${formId}-data`);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'checkbox') {
                            field.checked = data[key];
                        } else {
                            field.value = data[key];
                        }
                    }
                });
            } catch (e) {
                console.error('Error loading saved form data:', e);
            }
        }
        
        // Save data on input
        const saveFormData = debounce(() => {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type === 'checkbox') {
                    data[key] = field.checked;
                } else {
                    data[key] = value;
                }
            }
            
            localStorage.setItem(`${formId}-data`, JSON.stringify(data));
        }, 1000);
        
        form.addEventListener('input', saveFormData);
        form.addEventListener('change', saveFormData);
        
        // Clear saved data on successful submit
        form.addEventListener('submit', function() {
            localStorage.removeItem(`${formId}-data`);
        });
    });
    
    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const confirmMessage = this.dataset.confirm || 'Are you sure you want to delete this item?';
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
    
    // Image preview for file uploads
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Remove existing preview
                    const existingPreview = input.parentNode.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Create new preview
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.style.cssText = `
                        margin-top: 1rem;
                        border: 1px solid var(--border-color);
                        border-radius: var(--border-radius);
                        overflow: hidden;
                        max-width: 300px;
                    `;
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = `
                        width: 100%;
                        height: auto;
                        display: block;
                    `;
                    
                    preview.appendChild(img);
                    input.parentNode.appendChild(preview);
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
    
    // Character counter for textareas
    const textareas = document.querySelectorAll('textarea[maxlength]');
    
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        
        // Create counter element
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.cssText = `
            text-align: right;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        `;
        
        const updateCounter = () => {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length}/${maxLength}`;
            
            if (remaining < 50) {
                counter.style.color = 'var(--warning-color)';
            } else if (remaining < 10) {
                counter.style.color = 'var(--error-color)';
            } else {
                counter.style.color = 'var(--text-muted)';
            }
        };
        
        textarea.parentNode.appendChild(counter);
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
    });
    
    // Sortable tables
    const sortableTables = document.querySelectorAll('.admin-table.sortable');
    
    sortableTables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const column = this.dataset.sort;
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                
                // Determine sort direction
                const currentDirection = this.dataset.direction || 'asc';
                const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
                
                // Clear all direction indicators
                headers.forEach(h => {
                    h.removeAttribute('data-direction');
                    h.classList.remove('sort-asc', 'sort-desc');
                });
                
                // Set new direction
                this.dataset.direction = newDirection;
                this.classList.add(`sort-${newDirection}`);
                
                // Sort rows
                rows.sort((a, b) => {
                    const aValue = a.children[Array.from(this.parentNode.children).indexOf(this)].textContent.trim();
                    const bValue = b.children[Array.from(this.parentNode.children).indexOf(this)].textContent.trim();
                    
                    let comparison = 0;
                    if (aValue > bValue) comparison = 1;
                    if (aValue < bValue) comparison = -1;
                    
                    return newDirection === 'desc' ? comparison * -1 : comparison;
                });
                
                // Reorder DOM
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
    
    // Auto-resize textareas
    const autoResizeTextareas = document.querySelectorAll('textarea.auto-resize');
    
    autoResizeTextareas.forEach(textarea => {
        const resize = () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        };
        
        textarea.addEventListener('input', resize);
        resize(); // Initial resize
    });
    
    // Bulk actions for tables
    const bulkActionForms = document.querySelectorAll('.bulk-actions');
    
    bulkActionForms.forEach(form => {
        const selectAll = form.querySelector('.select-all');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]:not(.select-all)');
        const actionSelect = form.querySelector('.bulk-action-select');
        const applyButton = form.querySelector('.apply-bulk-action');
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButton);
        });
        
        function updateBulkActionButton() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            
            if (applyButton) {
                applyButton.disabled = checkedCount === 0;
                applyButton.textContent = checkedCount > 0 ? 
                    `Apply to ${checkedCount} item${checkedCount > 1 ? 's' : ''}` : 
                    'Apply';
            }
            
            if (selectAll) {
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
                selectAll.checked = checkedCount === checkboxes.length;
            }
        }
        
        if (applyButton) {
            applyButton.addEventListener('click', function(e) {
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                const action = actionSelect ? actionSelect.value : '';
                
                if (selectedIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one item.');
                    return;
                }
                
                if (!action) {
                    e.preventDefault();
                    alert('Please select an action.');
                    return;
                }
                
                if (action === 'delete') {
                    if (!confirm(`Are you sure you want to delete ${selectedIds.length} item${selectedIds.length > 1 ? 's' : ''}?`)) {
                        e.preventDefault();
                    }
                }
            });
        }
    });
    
    // Toast notifications
    function showToast(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: var(--box-shadow);
            z-index: 10000;
            max-width: 300px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-weight: bold; color: var(--${type === 'success' ? 'success' : type === 'error' ? 'error' : 'info'}-color);">${icon}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="margin-left: auto; background: none; border: none; color: var(--text-muted); cursor: pointer;">×</button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, duration);
    }
    
    // Expose toast function globally
    window.showToast = showToast;
    
    // Show success/error messages from PHP
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const type = alert.classList.contains('alert-success') ? 'success' : 
                    alert.classList.contains('alert-danger') ? 'error' : 'info';
        
        showToast(alert.textContent.trim(), type);
        alert.remove();
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save form
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const form = document.querySelector('.admin-form');
            if (form) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.click();
                }
            }
        }
        
        // Escape to close modals/dropdowns
        if (e.key === 'Escape') {
            // Close mobile sidebar
            if (sidebar && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
            
            // Close dropdowns
            navDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
                const menu = dropdown.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.remove('active');
                }
            });
        }
    });
    
    // Initialize tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip-popup';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.cssText = `
                position: absolute;
                background-color: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: var(--border-radius);
                padding: 0.5rem;
                font-size: 0.8rem;
                z-index: 10000;
                pointer-events: none;
                white-space: nowrap;
                box-shadow: var(--box-shadow);
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip-popup');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export for use in other scripts
window.adminUtils = {
    showToast: window.showToast,
    debounce
};
