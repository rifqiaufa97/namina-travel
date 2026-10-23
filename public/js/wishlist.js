/**
 * Wishlist functionality handler
 * 
 * This script provides client-side functionality for managing wishlist items
 * including adding, removing, and toggling items in the wishlist.
 */

class WishlistManager {
    constructor(options = {}) {
        // Default options
        this.options = {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            apiBaseUrl: '', // Kosongkan agar pakai web route
            toastOptions: {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            },
            ...options
        };
        
        // Initialize
        this.initializeEventListeners();
    }
    
    /**
     * Initialize event listeners for wishlist buttons
     */
    initializeEventListeners() {
        // Delegate click events for all wishlist buttons
        document.addEventListener('click', (e) => {
            // Handle add to wishlist buttons
            if (e.target.closest('.btn-add-to-wishlist') || e.target.closest('[data-wishlist-action="add"]')) {
                e.preventDefault();
                const button = e.target.closest('.btn-add-to-wishlist') || e.target.closest('[data-wishlist-action="add"]');
                const destinationId = button.dataset.destinationId || button.closest('[data-destination-id]')?.dataset.destinationId;
                if (destinationId) {
                    this.addToWishlist(destinationId, button);
                }
            }
            
            // Handle remove from wishlist buttons
            if (e.target.closest('.btn-remove-from-wishlist') || e.target.closest('[data-wishlist-action="remove"]')) {
                e.preventDefault();
                const button = e.target.closest('.btn-remove-from-wishlist') || e.target.closest('[data-wishlist-action="remove"]');
                const destinationId = button.dataset.destinationId || button.closest('[data-destination-id]')?.dataset.destinationId;
                if (destinationId) {
                    this.removeFromWishlist(destinationId, button);
                }
            }
            
            // Handle toggle wishlist buttons
            if (e.target.closest('.btn-toggle-wishlist') || e.target.closest('[data-wishlist-action="toggle"]')) {
                e.preventDefault();
                const button = e.target.closest('.btn-toggle-wishlist') || e.target.closest('[data-wishlist-action="toggle"]');
                const destinationId = button.dataset.destinationId || button.closest('[data-destination-id]')?.dataset.destinationId;
                if (destinationId) {
                    this.toggleWishlist(destinationId, button);
                }
            }
        });
    }
    
    /**
     * Show a toast notification
     * @param {string} type - Type of notification (success, error, warning, info)
     * @param {string} message - Message to display
     */
    showToast(type, message) {
        const toastOptions = {
            ...this.options.toastOptions,
            icon: type,
            title: message
        };
        
        // Use SweetAlert2 if available, otherwise fall back to browser alert
        if (typeof Swal !== 'undefined') {
            Swal.fire(toastOptions);
        } else {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    }
    
    /**
     * Show login required modal
     * @param {string} redirectUrl - URL to redirect to after login
     */
    showLoginRequired(redirectUrl = window.location.href) {
        // Check if we're using Bootstrap modals
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            const loginForm = document.querySelector('#loginModal form');
            
            // Update the redirect URL in the login form if it exists
            if (loginForm) {
                let redirectInput = loginForm.querySelector('input[name="redirect"]');
                if (!redirectInput) {
                    redirectInput = document.createElement('input');
                    redirectInput.type = 'hidden';
                    redirectInput.name = 'redirect';
                    loginForm.appendChild(redirectInput);
                }
                redirectInput.value = redirectUrl;
            }
            
            loginModal.show();
        } else {
            // Fallback to alert and redirect to login page
            if (confirm('You need to be logged in to add items to your wishlist. Would you like to log in now?')) {
                window.location.href = `/login?redirect=${encodeURIComponent(redirectUrl)}`;
            }
        }
    }
    
    /**
     * Update the wishlist count in the UI
     * @param {number} count - The new wishlist count
     */
    updateWishlistCount(count) {
        // Update all wishlist count elements
        document.querySelectorAll('.wishlist-count').forEach(el => {
            el.textContent = count;
            el.style.display = count > 0 ? 'inline-block' : 'none';
        });
        
        // Dispatch a custom event that other scripts can listen for
        document.dispatchEvent(new CustomEvent('wishlist:updated', { 
            detail: { count } 
        }));
    }
    
    /**
     * Update the wishlist button state
     * @param {HTMLElement} button - The button element to update
     * @param {boolean} isInWishlist - Whether the item is in the wishlist
     */
    updateButtonState(button, isInWishlist) {
        if (!button) return;
        
        // Toggle active class
        button.classList.toggle('active', isInWishlist);
        button.classList.toggle('text-danger', isInWishlist);
        
        // Update ARIA attributes
        button.setAttribute('aria-pressed', isInWishlist);
        
        // Update tooltip if Bootstrap tooltips are enabled
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltip = bootstrap.Tooltip.getInstance(button);
            if (tooltip) {
                tooltip.dispose();
                const newTitle = isInWishlist 
                    ? (button.dataset.removeText || 'Remove from wishlist')
                    : (button.dataset.addText || 'Add to wishlist');
                button.setAttribute('title', newTitle);
                new bootstrap.Tooltip(button);
            }
        }
        
        // Update icon if using Font Awesome
        const icon = button.querySelector('i');
        if (icon) {
            if (isInWishlist) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        }
    }
    
    /**
     * Add an item to the wishlist
     * @param {string} destinationId - ID of the destination to add
     * @param {HTMLElement} button - The button that triggered the action
     */
    async addToWishlist(destinationId, button) {
        try {
            const response = await fetch(`${this.options.apiBaseUrl}wishlist`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.options.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ destination_id: destinationId })
            });
            
            const data = await response.json();
            
            if (response.status === 401) {
                // Unauthorized - show login modal
                this.showLoginRequired();
                return;
            }
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to add to wishlist');
            }
            
            // Update UI
            this.updateButtonState(button, true);
            this.updateWishlistCount(data.data.wishlist_count);
            this.showToast('success', data.message || 'Added to wishlist');
            
            // Dispatch custom event
            document.dispatchEvent(new CustomEvent('wishlist:added', { 
                detail: { 
                    destinationId,
                    wishlistCount: data.data.wishlist_count
                } 
            }));
            
            return data;
            
        } catch (error) {
            console.error('Error adding to wishlist:', error);
            this.showToast('error', error.message || 'Failed to add to wishlist');
            throw error;
        }
    }
    
    /**
     * Remove an item from the wishlist
     * @param {string} destinationId - ID of the destination to remove
     * @param {HTMLElement} button - The button that triggered the action
     */
    async removeFromWishlist(destinationId, button) {
        try {
            const response = await fetch(`${this.options.apiBaseUrl}wishlist/${destinationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.options.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (response.status === 401) {
                // Unauthorized - show login modal
                this.showLoginRequired();
                return;
            }
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to remove from wishlist');
            }
            
            // Update UI
            this.updateButtonState(button, false);
            this.updateWishlistCount(data.data.wishlist_count);
            this.showToast('success', data.message || 'Removed from wishlist');
            
            // If we're on the wishlist page, remove the item from the DOM
            if (window.location.pathname.includes('/wishlist')) {
                const itemElement = button.closest('[data-destination-id]') || button.closest('.wishlist-item');
                if (itemElement) {
                    itemElement.style.opacity = '0';
                    setTimeout(() => {
                        itemElement.remove();
                        
                        // If no more items, show empty state
                        const container = document.querySelector('.wishlist-container');
                        const emptyState = document.querySelector('.wishlist-empty');
                        const itemsList = document.querySelector('.wishlist-items');
                        
                        if (itemsList && itemsList.children.length === 0 && emptyState) {
                            itemsList.style.display = 'none';
                            emptyState.style.display = 'block';
                        }
                    }, 300);
                }
            }
            
            // Dispatch custom event
            document.dispatchEvent(new CustomEvent('wishlist:removed', { 
                detail: { 
                    destinationId,
                    wishlistCount: data.data.wishlist_count
                } 
            }));
            
            return data;
            
        } catch (error) {
            console.error('Error removing from wishlist:', error);
            this.showToast('error', error.message || 'Failed to remove from wishlist');
            throw error;
        }
    }
    
    /**
     * Toggle an item in the wishlist (add if not present, remove if present)
     * @param {string} destinationId - ID of the destination to toggle
     * @param {HTMLElement} button - The button that triggered the action
     */
    async toggleWishlist(destinationId, button) {
        try {
            const response = await fetch(`${this.options.apiBaseUrl}wishlist/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.options.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ destination_id: destinationId })
            });
            
            const data = await response.json();
            
            if (response.status === 401) {
                // Unauthorized - show login modal
                this.showLoginRequired();
                return;
            }
            
            if (!response.ok) {
                throw new Error(data.message || 'Failed to update wishlist');
            }
            
            // Update UI
            const isInWishlist = data.data.action === 'added';
            this.updateButtonState(button, isInWishlist);
            this.updateWishlistCount(data.data.wishlist_count);
            
            // Show appropriate message
            const message = isInWishlist 
                ? 'Added to wishlist' 
                : 'Removed from wishlist';
            this.showToast('success', data.message || message);
            
            // Dispatch appropriate custom event
            const eventName = isInWishlist ? 'wishlist:added' : 'wishlist:removed';
            document.dispatchEvent(new CustomEvent(eventName, { 
                detail: { 
                    destinationId,
                    wishlistCount: data.data.wishlist_count
                } 
            }));
            
            return data;
            
        } catch (error) {
            console.error('Error toggling wishlist:', error);
            this.showToast('error', error.message || 'Failed to update wishlist');
            throw error;
        }
    }
    
    /**
     * Check if an item is in the wishlist
     * @param {string} destinationId - ID of the destination to check
     * @returns {Promise<boolean>} - Whether the item is in the wishlist
     */
    async checkWishlistStatus(destinationId) {
        try {
            const response = await fetch(`${this.options.apiBaseUrl}/wishlist/check/${destinationId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.status === 401) {
                // Unauthorized - treat as not in wishlist
                return false;
            }
            
            if (!response.ok) {
                throw new Error('Failed to check wishlist status');
            }
            
            const data = await response.json();
            return data.data.in_wishlist || false;
            
        } catch (error) {
            console.error('Error checking wishlist status:', error);
            return false;
        }
    }
    
    /**
     * Initialize wishlist buttons on page load
     * This checks the status of all wishlist buttons and updates their state
     */
    async initializeWishlistButtons() {
        const buttons = document.querySelectorAll('[data-wishlist-action]');
        if (buttons.length === 0) return;
        
        // Get unique destination IDs from all buttons
        const destinationIds = Array.from(buttons)
            .map(button => button.dataset.destinationId || button.closest('[data-destination-id]')?.dataset.destinationId)
            .filter((id, index, self) => id && self.indexOf(id) === index);
        
        if (destinationIds.length === 0) return;
        
        try {
            // Batch check all destinations at once
            const checkPromises = destinationIds.map(id => this.checkWishlistStatus(id));
            const results = await Promise.all(checkPromises);
            
            // Create a map of destination ID to wishlist status
            const statusMap = {};
            destinationIds.forEach((id, index) => {
                statusMap[id] = results[index];
            });
            
            // Update all buttons
            buttons.forEach(button => {
                const destinationId = button.dataset.destinationId || button.closest('[data-destination-id]')?.dataset.destinationId;
                if (destinationId && statusMap.hasOwnProperty(destinationId)) {
                    this.updateButtonState(button, statusMap[destinationId]);
                }
            });
            
        } catch (error) {
            console.error('Error initializing wishlist buttons:', error);
        }
    }
}

// Auto-initialize if this script is loaded in the browser
if (typeof window !== 'undefined') {
    // Create a global instance
    window.WishlistManager = new WishlistManager();
    
    // Initialize when the DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => window.WishlistManager.initializeWishlistButtons());
    } else {
        window.WishlistManager.initializeWishlistButtons();
    }
}
