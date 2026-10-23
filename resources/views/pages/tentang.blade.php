@extends('layouts.app')

@section('title', 'Tentang Kami - Namina Travel')

@section('content')
<!-- Hero Section -->
<section class="relative py-12 md:py-16 bg-gradient-to-r from-primary to-secondary text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 md:mb-6">Tentang Namina Travel</h1>
            <p class="text-lg md:text-xl mb-6 md:mb-8">Menjelajahi keindahan Indonesia dengan pengalaman tak terlupakan</p>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-0">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-10">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" 
                         alt="Tentang Kami" 
                         class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Siapa Kami</h2>
                    <p class="text-gray-700 text-lg mb-4 md:mb-6">
                        Namina Travel adalah perusahaan penyedia jasa perjalanan wisata yang berkomitmen untuk memberikan pengalaman liburan terbaik di berbagai destinasi wisata di Indonesia. 
                        Didirikan pada tahun 2019, kami telah melayani ribuan pelanggan dengan layanan yang profesional dan memuaskan.
                    </p>
                    <p class="text-gray-700 text-lg mb-4 md:mb-6">
                        Kami percaya bahwa setiap perjalanan adalah cerita yang berharga. Itu sebabnya kami berusaha keras untuk menciptakan pengalaman perjalanan yang tak terlupakan 
                        bagi setiap pelanggan kami, mulai dari perencanaan hingga pelaksanaan perjalanan.
                    </p>
                    <div class="flex flex-wrap gap-2 md:gap-4">
                        <div class="badge badge-primary badge-lg px-4 py-2">Profesional</div>
                        <div class="badge badge-secondary badge-lg px-4 py-2">Terpercaya</div>
                        <div class="badge badge-accent badge-lg px-4 py-2">Bersahabat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 md:py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center">
            <div class="p-4 md:p-6 border-b md:border-b-0 md:border-r border-gray-200 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-primary mb-1 md:mb-2">{{ $stats['destinations'] }}+</div>
                <p class="text-gray-600 text-sm md:text-base">Destinasi</p>
            </div>
            <div class="p-4 md:p-6 border-b md:border-b-0 md:border-r border-gray-200 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-primary mb-1 md:mb-2">{{ number_format($stats['happy_customers']) }}+</div>
                <p class="text-gray-600 text-sm md:text-base">Pelanggan Bahagia</p>
            </div>
            <div class="p-4 md:p-6 border-b md:border-b-0 md:border-r border-gray-200 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-primary mb-1 md:mb-2">{{ $stats['tours'] }}+</div>
                <p class="text-gray-600 text-sm md:text-base">Paket Wisata</p>
            </div>
            <div class="p-4 md:p-6">
                <div class="text-3xl md:text-4xl font-bold text-primary mb-1 md:mb-2">{{ $stats['years_experience'] }}+</div>
                <p class="text-gray-600 text-sm md:text-base">Tahun Pengalaman</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 md:mb-16">
            <h2 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4">Tim Kami</h2>
            <div class="w-16 md:w-24 h-1 bg-primary mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto">
            @foreach($teamMembers as $member)
            <div class="card bg-base-100 shadow-xl p-4 md:p-6">
                <figure class="px-6 pt-6">
                    <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="rounded-full w-32 h-32 md:w-48 md:h-48 object-cover mx-auto">
                </figure>
                <div class="card-body items-center text-center p-2 md:p-4">
                    <h3 class="card-title text-lg md:text-xl">{{ $member['name'] }}</h3>
                    <p class="text-primary font-medium text-sm md:text-base">{{ $member['role'] }}</p>
                    <p class="text-gray-600 text-sm md:text-base">{{ $member['bio'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-12 md:py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 md:mb-16">
            <h2 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">Kami berkomitmen untuk memberikan pelayanan terbaik dengan berbagai keunggulan</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <div class="card bg-white p-6 md:p-8 rounded-lg shadow-md text-center">
                <div class="text-4xl md:text-5xl mb-2 md:mb-4 text-primary">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2 md:mb-3">Aman & Terpercaya</h3>
                <p class="text-gray-600 text-sm md:text-base">Perjalanan Anda aman bersama kami dengan layanan terpercaya dan profesional.</p>
            </div>
            <div class="card bg-white p-6 md:p-8 rounded-lg shadow-md text-center">
                <div class="text-4xl md:text-5xl mb-2 md:mb-4 text-primary">
                    <i class="fas fa-tag"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2 md:mb-3">Harga Terjangkau</h3>
                <p class="text-gray-600 text-sm md:text-base">Nikmati pengalaman liburan terbaik dengan harga yang bersaing dan transparan.</p>
            </div>
            <div class="card bg-white p-6 md:p-8 rounded-lg shadow-md text-center">
                <div class="text-4xl md:text-5xl mb-2 md:mb-4 text-primary">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="text-lg md:text-xl font-semibold mb-2 md:mb-3">24/7 Support</h3>
                <p class="text-gray-600 text-sm md:text-base">Tim kami siap membantu Anda kapan saja selama perjalanan Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 md:py-16 bg-primary text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Siap Memulai Petualangan Anda?</h2>
        <p class="text-lg md:text-xl mb-6 md:mb-8 max-w-2xl mx-auto">Temukan destinasi impian Anda dan buat kenangan tak terlupakan bersama Namina Travel</p>
        <a href="{{ route('destinations.index') }}" class="btn btn-secondary btn-lg">Jelajahi Destinasi</a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }
    .hero-content {
        position: relative;
        z-index: 10;
    }
</style>
@endpush
