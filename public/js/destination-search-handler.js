class DestinationSearchHandler {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.searchResults = document.getElementById('searchResults');
        this.searchForm = document.getElementById('searchForm');
        this.filters = document.getElementById('filters');
        this.resultsContainer = document.getElementById('resultsContainer');
        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.priceRange = document.getElementById('priceRange');
        this.priceMin = document.getElementById('priceMin');
        this.priceMax = document.getElementById('priceMax');
        this.cityFilter = document.getElementById('cityFilter');
        this.provinceFilter = document.getElementById('provinceFilter');
        this.ratingFilter = document.getElementById('ratingFilter');
        this.sortBy = document.getElementById('sortBy');
        this.applyFiltersBtn = document.getElementById('applyFilters');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        
        this.debounceTimer = null;
        this.currentPage = 1;
        this.perPage = 12;
        this.filtersData = {
            minPrice: 0,
            maxPrice: 1000000,
            city: '',
            province: '',
            minRating: 0,
            sort: 'newest',
            query: ''
        };
        
        this.initialize();
    }
    
    initialize() {
        if (!this.searchForm) return;
        
        this.setupEventListeners();
        this.loadFilterOptions();
        this.loadInitialResults();
    }
    
    setupEventListeners() {
        // Search input with debounce
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(this.debounceTimer);
                this.filtersData.query = e.target.value.trim();
                
                if (this.filtersData.query.length >= 2 || this.filtersData.query.length === 0) {
                    this.debounceTimer = setTimeout(() => {
                        this.fetchAutocompleteResults(this.filtersData.query);
                    }, 300);
                } else {
                    this.hideResults();
                }
            });
            
            this.searchInput.addEventListener('focus', () => {
                if (this.filtersData.query.length >= 2) {
                    this.fetchAutocompleteResults(this.filtersData.query);
                }
            });
        }
        
        // Form submission
        if (this.searchForm) {
            this.searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.performSearch();
            });
        }
        
        // Price range slider
        if (this.priceRange) {
            noUiSlider.create(this.priceRange, {
                start: [0, 1000000],
                connect: true,
                range: {
                    'min': 0,
                    'max': 1000000
                },
                step: 100000,
                format: {
                    to: value => Math.round(value),
                    from: value => parseFloat(value)
                }
            });
            
            this.priceRange.noUiSlider.on('update', (values, handle) => {
                const [min, max] = values.map(val => parseInt(val));
                this.priceMin.textContent = this.formatCurrency(min);
                this.priceMax.textContent = this.formatCurrency(max);
                this.filtersData.minPrice = min;
                this.filtersData.maxPrice = max;
            });
        }
        
        // Filter change events
        if (this.cityFilter) {
            this.cityFilter.addEventListener('change', (e) => {
                this.filtersData.city = e.target.value;
                this.performSearch();
            });
        }
        
        if (this.provinceFilter) {
            this.provinceFilter.addEventListener('change', (e) => {
                this.filtersData.province = e.target.value;
                this.performSearch();
            });
        }
        
        if (this.ratingFilter) {
            this.ratingFilter.addEventListener('change', (e) => {
                this.filtersData.minRating = parseFloat(e.target.value) || 0;
                this.performSearch();
            });
        }
        
        if (this.sortBy) {
            this.sortBy.addEventListener('change', (e) => {
                this.filtersData.sort = e.target.value;
                this.performSearch();
            });
        }
        
        // Apply filters button
        if (this.applyFiltersBtn) {
            this.applyFiltersBtn.addEventListener('click', () => {
                this.performSearch();
            });
        }
        
        // Clear filters button
        if (this.clearFiltersBtn) {
            this.clearFiltersBtn.addEventListener('click', () => {
                this.clearFilters();
            });
        }
        
        // Handle click outside to close results
        document.addEventListener('click', (e) => {
            if (this.searchResults && !this.searchInput?.contains(e.target) && !this.searchResults.contains(e.target)) {
                this.hideResults();
            }
        });
    }
    
    async loadFilterOptions() {
        try {
            const response = await fetch('/api/search/filter-options');
            const data = await response.json();
            
            if (data.cities && this.cityFilter) {
                this.populateSelect(this.cityFilter, data.cities, 'Pilih Kota');
            }
            
            if (data.provinces && this.provinceFilter) {
                this.populateSelect(this.provinceFilter, data.provinces, 'Pilih Provinsi');
            }
            
            if (data.price_range && this.priceRange) {
                this.priceRange.noUiSlider.updateOptions({
                    range: {
                        'min': data.price_range.min,
                        'max': data.price_range.max
                    },
                    start: [data.price_range.min, data.price_range.max]
                });
                
                this.filtersData.minPrice = data.price_range.min;
                this.filtersData.maxPrice = data.price_range.max;
            }
        } catch (error) {
            console.error('Error loading filter options:', error);
        }
    }
    
    populateSelect(selectElement, options, placeholder) {
        if (!selectElement) return;
        
        // Clear existing options except the first one (placeholder)
        while (selectElement.options.length > 1) {
            selectElement.remove(1);
        }
        
        // Add new options
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.textContent = option;
            selectElement.appendChild(optionElement);
        });
    }
    
    async fetchAutocompleteResults(query) {
        try {
            const response = await fetch(`/api/search/autocomplete?query=${encodeURIComponent(query)}`);
            const results = await response.json();
            this.displayAutocompleteResults(results);
        } catch (error) {
            console.error('Error fetching autocomplete results:', error);
        }
    }
    
    displayAutocompleteResults(results) {
        if (!this.searchResults) return;
        
        if (results.length === 0) {
            this.hideResults();
            return;
        }
        
        this.searchResults.innerHTML = results.map(result => `
            <div class="autocomplete-item p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200" 
                 data-id="${result.id}"
                 data-name="${result.name}">
                <div class="flex items-center">
                    ${result.thumbnail ? `
                        <img src="${result.thumbnail}" alt="${result.name}" 
                             class="w-10 h-10 rounded-md object-cover mr-3">
                    ` : ''}
                    <div>
                        <div class="font-medium">${result.name}</div>
                        <div class="text-sm text-gray-500">
                            ${result.city}, ${result.province}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Add click event listeners to each result item
        document.querySelectorAll('.autocomplete-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const id = item.getAttribute('data-id');
                const name = item.getAttribute('data-name');
                this.searchInput.value = name;
                this.filtersData.query = name;
                this.hideResults();
                this.performSearch();
            });
        });
        
        this.searchResults.classList.remove('hidden');
    }
    
    hideResults() {
        if (this.searchResults) {
            this.searchResults.classList.add('hidden');
        }
    }
    
    async performSearch() {
        this.showLoading(true);
        this.hideResults();
        
        try {
            const queryParams = new URLSearchParams();
            
            if (this.filtersData.query) queryParams.append('q', this.filtersData.query);
            if (this.filtersData.minPrice !== null) queryParams.append('min_price', this.filtersData.minPrice);
            if (this.filtersData.maxPrice !== null) queryParams.append('max_price', this.filtersData.maxPrice);
            if (this.filtersData.city) queryParams.append('city', this.filtersData.city);
            if (this.filtersData.province) queryParams.append('province', this.filtersData.province);
            if (this.filtersData.minRating > 0) queryParams.append('min_rating', this.filtersData.minRating);
            if (this.filtersData.sort) queryParams.append('sort', this.filtersData.sort);
            
            queryParams.append('page', this.currentPage);
            queryParams.append('per_page', this.perPage);
            
            const response = await fetch(`/api/search?${queryParams.toString()}`);
            const data = await response.json();
            
            this.displaySearchResults(data);
        } catch (error) {
            console.error('Error performing search:', error);
            this.showError('Terjadi kesalahan saat melakukan pencarian. Silakan coba lagi.');
        } finally {
            this.showLoading(false);
        }
    }
    
    displaySearchResults(data) {
        if (!this.resultsContainer) return;
        
        if (!data || data.data.length === 0) {
            this.resultsContainer.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">Tidak ada hasil yang ditemukan untuk pencarian Anda.</p>
                    <button onclick="window.location.href='{{ route('search') }}'" 
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Reset Pencarian
                    </button>
                </div>
            `;
            return;
        }
        
        this.resultsContainer.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${data.data.map(destination => this.renderDestinationCard(destination)).join('')}
            </div>
            ${this.renderPagination(data)}
        `;
    }
    
    renderDestinationCard(destination) {
        const imageUrl = destination.primary_image?.url || '/images/placeholder.jpg';
        const rating = parseFloat(destination.avg_rating) || 0;
        const reviewCount = destination.reviews_count || 0;
        const price = parseFloat(destination.price) || 0;
        
        return `
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative h-48 overflow-hidden">
                    <img src="${imageUrl}" 
                         alt="${destination.name}" 
                         class="w-full h-full object-cover">
                    ${destination.is_featured ? `
                        <span class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>
                    ` : ''}
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1 text-gray-900">${destination.name}</h3>
                    <p class="text-gray-600 text-sm mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                        ${destination.city}, ${destination.province}
                    </p>
                    
                    <div class="flex items-center mb-3">
                        <div class="flex text-yellow-400">
                            ${this.renderStarRating(rating)}
                        </div>
                        <span class="text-gray-600 text-sm ml-2">
                            ${rating.toFixed(1)} (${reviewCount} ulasan)
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                        <div>
                            <span class="text-xs text-gray-500">Mulai dari</span>
                            <p class="font-bold text-lg text-blue-600">${this.formatCurrency(price)}</p>
                        </div>
                        <a href="/destinasi/${destination.slug}" 
                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
    
    renderStarRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let stars = '';
        
        // Full stars
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        
        // Half star
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }
        
        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star"></i>';
        }
        
        return stars;
    }
    
    renderPagination(data) {
        if (data.last_page <= 1) return '';
        
        let pagination = '<div class="mt-8 flex justify-center"><div class="join">';
        
        // Previous button
        if (data.current_page > 1) {
            pagination += `
                <button onclick="searchHandler.changePage(${data.current_page - 1})" 
                        class="join-item btn btn-outline">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </button>
            `;
        }
        
        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                pagination += `<button class="join-item btn btn-active">${i}</button>`;
            } else if (
                i === 1 || 
                i === data.last_page || 
                (i >= data.current_page - 1 && i <= data.current_page + 1)
            ) {
                pagination += `
                    <button onclick="searchHandler.changePage(${i})" 
                            class="join-item btn btn-outline">
                        ${i}
                    </button>
                `;
            } else if (
                (i === 2 && data.current_page > 3) ||
                (i === data.last_page - 1 && data.current_page < data.last_page - 2)
            ) {
                pagination += '<span class="join-item btn btn-disabled">...</span>';
            }
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            pagination += `
                <button onclick="searchHandler.changePage(${data.current_page + 1})" 
                        class="join-item btn btn-outline">
                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                </button>
            `;
        }
        
        pagination += '</div></div>';
        return pagination;
    }
    
    changePage(page) {
        this.currentPage = page;
        this.performSearch();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    clearFilters() {
        // Reset form elements
        if (this.searchInput) this.searchInput.value = '';
        if (this.cityFilter) this.cityFilter.value = '';
        if (this.provinceFilter) this.provinceFilter.value = '';
        if (this.ratingFilter) this.ratingFilter.value = '';
        if (this.sortBy) this.sortBy.value = 'newest';
        if (this.priceRange) {
            this.priceRange.noUiSlider.updateOptions({
                start: [0, 1000000]
            });
        }
        
        // Reset filters data
        this.filtersData = {
            minPrice: 0,
            maxPrice: 1000000,
            city: '',
            province: '',
            minRating: 0,
            sort: 'newest',
            query: ''
        };
        
        // Reset pagination
        this.currentPage = 1;
        
        // Perform new search
        this.performSearch();
    }
    
    showLoading(show) {
        if (this.loadingSpinner) {
            this.loadingSpinner.style.display = show ? 'block' : 'none';
        }
        
        if (this.searchButton) {
            const icon = this.searchButton.querySelector('i');
            const text = this.searchButton.querySelector('span');
            
            if (show) {
                icon.className = 'fas fa-spinner fa-spin';
                text.textContent = 'Mencari...';
                this.searchButton.disabled = true;
            } else {
                icon.className = 'fas fa-search';
                text.textContent = 'Cari';
                this.searchButton.disabled = false;
            }
        }
    }
    
    showError(message) {
        if (!this.resultsContainer) return;
        
        this.resultsContainer.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            ${message}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
}

// Initialize the search handler when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Make searchHandler globally available for pagination
    window.searchHandler = new DestinationSearchHandler();
    
    // Initialize noUiSlider if it exists on the page
    if (document.getElementById('priceRange')) {
        noUiSlider.cssClasses.target += ' w-full mt-2';
    }
});
