@extends('layouts.app')

@section('title', 'Wishlist Saya - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Wishlist Saya</h1>
            <p class="text-gray-600">Lihat dan kelola daftar keinginan perjalanan Anda</p>
        </div>

        <!-- Wishlist Content -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($wishlists->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach($wishlists as $destination)
                        <div class="relative" data-destination-id="{{ $destination->id }}">
                            <x-destination-card :destination="$destination" />
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $wishlists->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                        <i class="far fa-heart text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Wishlist Anda Kosong</h3>
                    <p class="text-gray-500 mb-6">Anda belum menambahkan destinasi apapun ke wishlist.</p>
                    <a 
                        href="{{ route('destinations.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Jelajahi Destinasi
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize wishlist buttons
        if (typeof WishlistManager !== 'undefined') {
            const wishlistManager = new WishlistManager();
            
            // Initialize wishlist buttons on page load
            wishlistManager.initializeWishlistButtons();
            
            // Handle wishlist toggle
            document.addEventListener('click', function(e) {
                if (e.target.closest('.wishlist-toggle')) {
                    e.preventDefault();
                    const button = e.target.closest('.wishlist-toggle');
                    const destinationId = button.dataset.destinationId;
                    
                    // Show loading state
                    const icon = button.querySelector('i');
                    const originalIcon = icon.className;
                    icon.className = 'fas fa-spinner fa-spin';
                    
                    // Call the toggle API
                    fetch(`/api/wishlist/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ destination_id: destinationId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // If on wishlist page, remove the item from the DOM
                            if (window.location.pathname.includes('/wishlist')) {
                                const itemElement = button.closest('[data-destination-id]');
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
                            
                            // Show success message
                            if (typeof showToast === 'function') {
                                showToast(data.message, 'success');
                            }
                        } else {
                            throw new Error(data.message || 'Failed to update wishlist');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof showToast === 'function') {
                            showToast(error.message || 'Terjadi kesalahan. Silakan coba lagi nanti.', 'error');
                        }
                    })
                    .finally(() => {
                        // Restore original icon
                        if (icon) {
                            icon.className = originalIcon;
                        }
                    });
                }
            });

            // Handler hapus wishlist
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-wishlist')) {
                    e.preventDefault();
                    const button = e.target.closest('.btn-remove-wishlist');
                    const destinationId = button.dataset.destinationId;
                    const card = button.closest('[data-destination-id]');
                    const icon = button.querySelector('i');
                    const originalIcon = icon.className;
                    icon.className = 'fas fa-spinner fa-spin text-red-500';
                    fetch(`/api/wishlist/${destinationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (card) {
                                card.style.opacity = '0';
                                setTimeout(() => card.remove(), 300);
                            }
                            if (typeof showToast === 'function') {
                                showToast(data.message, 'success');
                            }
                            // Update wishlist count di navbar
                            const wishlistCount = document.querySelector('.wishlist-count');
                            if (wishlistCount) {
                                wishlistCount.textContent = data.data.wishlist_count > 9 ? '9+' : data.data.wishlist_count;
                            }
                        } else {
                            throw new Error(data.message || 'Gagal menghapus wishlist');
                        }
                    })
                    .catch(error => {
                        if (icon) icon.className = originalIcon;
                        if (typeof showToast === 'function') {
                            showToast(error.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                        }
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
