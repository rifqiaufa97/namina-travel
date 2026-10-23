@extends('layouts.app')

@section('title', 'Hubungi Kami - Namina Travel')

@section('content')
<!-- Hero Section -->
<section class="relative py-16 bg-gradient-to-r from-primary to-secondary text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Hubungi Kami</h1>
            <p class="text-xl mb-8">Kami siap membantu Anda dengan segala pertanyaan atau kebutuhan perjalanan Anda</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                <!-- Contact Form -->
                <div class="bg-white p-8 rounded-lg shadow-md border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Kirim Pesan</h2>
                    
                    <div id="formMessage" class="mb-6 hidden">
                        <div id="messageContainer" class="p-4 rounded-lg"></div>
                    </div>
                    
                    <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required class="input input-bordered w-full" placeholder="Masukkan nama lengkap Anda">
                            <span id="name-error" class="text-red-500 text-xs mt-1 block"></span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" required class="input input-bordered w-full" placeholder="nama@email.com">
                                <span id="email-error" class="text-red-500 text-xs mt-1 block"></span>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP / WhatsApp</label>
                                <input type="text" id="phone" name="phone" required class="input input-bordered w-full" placeholder="Contoh: 081282069087">
                                <span id="phone-error" class="text-red-500 text-xs mt-1 block"></span>
                            </div>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                            <input type="text" id="subject" name="subject" required class="input input-bordered w-full" placeholder="Subjek pesan Anda">
                            <span id="subject-error" class="text-red-500 text-xs mt-1 block"></span>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                            <textarea id="message" name="message" rows="5" required class="textarea textarea-bordered w-full h-32" placeholder="Tuliskan pesan Anda di sini..."></textarea>
                            <span id="message-error" class="text-red-500 text-xs mt-1 block"></span>
                        </div>
                        
                        <button type="submit" id="submitBtn" class="btn btn-primary w-full text-white">
                            <span id="submitText">Kirim Pesan</span>
                            <span id="loadingSpinner" class="loading loading-spinner hidden"></span>
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-md h-full">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Informasi Kontak</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-full text-primary mr-4">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Alamat</h3>
                                <p class="text-gray-600">{{ $contactInfo['alamat'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-full text-primary mr-4">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Telepon</h3>
                                <p class="text-gray-600">{{ $contactInfo['telepon'] }}</p>
                                <p class="text-gray-600">WhatsApp: {{ $contactInfo['whatsapp'] }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-full text-primary mr-4">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Email</h3>
                                <a href="mailto:{{ $contactInfo['email'] }}" class="text-primary hover:underline">{{ $contactInfo['email'] }}</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-full text-primary mr-4">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Jam Operasional</h3>
                                <p class="text-gray-600">{{ $contactInfo['jam_operasional'] }}</p>
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <h3 class="font-semibold text-lg mb-3">Ikuti Kami</h3>
                            <div class="flex space-x-4">
                                @foreach($socialMedia as $social)
                                <a href="{{ $social['url'] }}" class="bg-gray-200 hover:bg-primary hover:text-white text-gray-700 p-3 rounded-full transition-colors duration-300" aria-label="{{ $social['name'] }}">
                                    <i class="{{ $social['icon'] }} text-xl"></i>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Lokasi Kantor Kami</h2>
            <div class="rounded-xl overflow-hidden shadow-xl">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8234567890123!2d106.6634567890123!3d-6.2987654321098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fb7e8b1234567%3A0x1234567890abcdef!2sBumi%20Serpong%20Residence%2C%20Blok%20B%2034%2C%20Tangerang%20Selatan%2C%20Banten!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"
                    class="w-full"
                ></iframe>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Butuh Bantuan Cepat?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Hubungi kami melalui WhatsApp untuk respon yang lebih cepat</p>
        <a href="https://wa.me/6281282069087" target="_blank" class="btn btn-secondary btn-lg">
            <i class="fab fa-whatsapp mr-2"></i> Chat via WhatsApp
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const formMessage = document.getElementById('formMessage');
        const messageContainer = document.getElementById('messageContainer');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            document.querySelectorAll('[id$="-error"]').forEach(el => el.textContent = '');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    // Handle validation errors
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    });
                    
                    // Show error message
                    showMessage('Mohon periksa kembali form Anda.', 'error');
                } else {
                    // Show success message
                    showMessage(data.message, 'success');
                    form.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Terjadi kesalahan. Silakan coba lagi nanti.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
            });
        });
        
        function showMessage(message, type) {
            messageContainer.textContent = message;
            messageContainer.className = 'p-4 rounded-lg';
            
            if (type === 'success') {
                messageContainer.classList.add('bg-green-100', 'text-green-700');
            } else {
                messageContainer.classList.add('bg-red-100', 'text-red-700');
            }
            
            formMessage.classList.remove('hidden');
            
            // Scroll to message
            formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
