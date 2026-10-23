class DestinationSearch {
    constructor() {
        this.searchInput = document.getElementById('destinationSearch');
        this.searchResults = document.getElementById('searchResults');
        this.filters = document.getElementById('searchFilters');
        this.searchForm = document.getElementById('searchForm');
        this.searchButton = document.getElementById('searchButton');
        this.loadingSpinner = document.getElementById('searchLoading');
        this.resultsContainer = document.getElementById('searchResultsContainer');
        
        this.initialize();
    }

    initialize() {
        if (!this.searchInput) return;

        this.setupEventListeners();
        this.loadFilterOptions();
    }

    setupEventListeners() {
        // Search input with debounce
        let debounceTimer;
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();
            
            if (query.length >= 2) {
                debounceTimer = setTimeout(() => {
                    this.fetchAutocompleteResults(query);
                }, 300);
            } else {
                this.hideResults();
            }
        });

        // Handle click outside to close results
        document.addEventListener('click', (e) => {
            if (this.searchResults && !this.searchInput.contains(e.target) && !this.searchResults.contains(e.target)) {
                this.hideResults();
            }
        });

        // Form submission
        if (this.searchForm) {
            this.searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.performSearch();
            });
        }
    }

    async loadFilterOptions() {
        try {
            const response = await fetch('/api/destinations/filter-options');
            const data = await response.json();
            
            if (data) {
                this.renderFilterOptions(data);
            }
        } catch (error) {
            console.error('Error loading filter options:', error);
        }
    }

    renderFilterOptions(options) {
        if (!this.filters) return;

        // Price range slider
        const priceRange = options.price_range || { min: 0, max: 10000000 };
        const priceSlider = `
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Harga</h6>
                </div>
                <div class="card-body">
                    <div id="priceRangeSlider"></div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="price-min">${this.formatPrice(priceRange.min)}</span>
                        <span class="price-max">${this.formatPrice(priceRange.max)}</span>
                    </div>
                    <input type="hidden" name="min_price" id="minPrice" value="${priceRange.min}">
                    <input type="hidden" name="max_price" id="maxPrice" value="${priceRange.max}">
                </div>
            </div>
        `;

        // Location filter
        const locationFilter = `
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Lokasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kota</label>
                        <select class="form-select" name="city">
                            <option value="">Semua Kota</option>
                            ${options.cities ? options.cities.map(city => 
                                `<option value="${city}">${city}</option>`
                            ).join('') : ''}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <select class="form-select" name="province">
                            <option value="">Semua Provinsi</option>
                            ${options.provinces ? options.provinces.map(province => 
                                `<option value="${province}">${province}</option>`
                            ).join('') : ''}
                        </select>
                    </div>
                </div>
            </div>
        `;

        // Rating filter
        const ratingFilter = `
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Rating</h6>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="min_rating" value="4" id="rating4">
                        <label class="form-check-label" for="rating4">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i> &amp; ke atas
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="min_rating" value="3" id="rating3">
                        <label class="form-check-label" for="rating3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i>
                            <i class="far fa-star text-warning"></i> &amp; ke atas
                        </label>
                    </div>
                </div>
            </div>
        `;

        this.filters.innerHTML = priceSlider + locationFilter + ratingFilter;
        this.initializePriceSlider(priceRange);
    }

    initializePriceSlider(range) {
        const slider = document.getElementById('priceRangeSlider');
        if (!slider) return;

        // Check if noUiSlider is available
        if (typeof noUiSlider === 'undefined') {
            console.warn('noUiSlider is not loaded');
            return;
        }

        noUiSlider.create(slider, {
            start: [range.min, range.max],
            connect: true,
            range: {
                'min': range.min,
                'max': range.max
            },
            step: 100000,
            format: {
                to: value => Math.round(value),
                from: value => Number(value)
            }
        });

        slider.noUiSlider.on('update', (values, handle) => {
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            const minPriceDisplay = document.querySelector('.price-min');
            const maxPriceDisplay = document.querySelector('.price-max');
            
            if (!minPrice || !maxPrice || !minPriceDisplay || !maxPriceDisplay) return;
            
            const min = Math.round(values[0]);
            const max = Math.round(values[1]);
            
            minPrice.value = min;
            maxPrice.value = max;
            minPriceDisplay.textContent = this.formatPrice(min);
            maxPriceDisplay.textContent = this.formatPrice(max);
        });
    }

    async fetchAutocompleteResults(query) {
        try {
            const response = await fetch(`/api/destinations/autocomplete?query=${encodeURIComponent(query)}`);
            const results = await response.json();
            this.displayResults(results);
        } catch (error) {
            console.error('Error fetching autocomplete results:', error);
        }
    }

    displayResults(results) {
        if (!this.searchResults) return;
        
        if (!results || results.length === 0) {
            this.hideResults();
            return;
        }

        const items = results.map(result => `
            <div class="autocomplete-item p-2 border-bottom" 
                 data-id="${result.id}"
                 data-name="${result.name}"
                 data-city="${result.city}">
                <div class="d-flex align-items-center">
                    ${result.thumbnail ? `
                        <img src="${result.thumbnail}" alt="${result.name}" 
                             class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                    ` : ''}
                    <div>
                        <div class="fw-bold">${result.name}</div>
                        <small class="text-muted">${result.city}, ${result.province}</small>
                    </div>
                </div>
            </div>
        `).join('');

        this.searchResults.innerHTML = items;
        this.showResults();

        // Add click handlers to autocomplete items
        document.querySelectorAll('.autocomplete-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const name = item.getAttribute('data-name');
                const city = item.getAttribute('data-city');
                if (this.searchInput) {
                    this.searchInput.value = `${name}, ${city}`;
                    this.hideResults();
                }
            });
        });
    }

    showResults() {
        if (this.searchResults) {
            this.searchResults.style.display = 'block';
        }
    }

    hideResults() {
        if (this.searchResults) {
            this.searchResults.style.display = 'none';
        }
    }

    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    }

    async performSearch() {
        if (!this.searchInput || this.searchInput.value.trim() === '') return;

        this.showLoading(true);
        
        try {
            const formData = new FormData(this.searchForm);
            const params = new URLSearchParams();
            
            // Add form data to URL params
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            const response = await fetch(`/api/destinations?${params.toString()}`);
            const data = await response.json();
            
            this.displaySearchResults(data);
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Terjadi kesalahan saat melakukan pencarian');
        } finally {
            this.showLoading(false);
        }
    }

    displaySearchResults(results) {
        if (!this.resultsContainer) return;
        
        if (!results || results.length === 0) {
            this.resultsContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="mb-0">Tidak ada hasil yang ditemukan</p>
                </div>
            `;
            return;
        }

        const items = results.map(item => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="${item.thumbnail ? '/storage/' + item.thumbnail : '/images/placeholder.jpg'}" 
                         class="card-img-top" alt="${item.name}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${item.name}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i> ${item.city}, ${item.province}
                        </p>
                        <div class="mb-2">
                            ${this.renderRating(item.rating || 0)}
                            <span class="ms-1 text-muted">(${item.total_reviews || 0})</span>
                        </div>
                        <p class="card-text text-truncate">${item.description || ''}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">${this.formatPrice(item.price)}</h5>
                            <a href="/destinations/${item.slug}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        this.resultsContainer.innerHTML = `
            <div class="row">
                ${items}
            </div>
            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-primary" id="loadMore">Muat Lebih Banyak</button>
            </div>
        `;
    }

    renderRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let stars = '';
        
        // Full stars
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star text-warning"></i>';
        }
        
        // Half star
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt text-warning"></i>';
        }
        
        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star text-warning"></i>';
        }
        
        return stars;
    }

    showLoading(show) {
        if (this.loadingSpinner) {
            this.loadingSpinner.style.display = show ? 'inline-block' : 'none';
        }
        if (this.searchButton) {
            this.searchButton.disabled = show;
        }
    }

    showError(message) {
        if (this.resultsContainer) {
            this.resultsContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${message}
                </div>
            `;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new DestinationSearch();
});
