<?php $__env->startSection('title', 'Beranda - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="relative min-h-[75vh] flex items-center justify-center bg-cover bg-center" style="background-image: linear-gradient(rgba(15, 23, 42, 0.55), rgba(15, 23, 42, 0.65)), url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');">
        <div class="container mx-auto px-4 py-20 text-center text-white">
            <div class="max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-sm font-medium mb-6">
                    <i class="fas fa-sparkles text-amber-300"></i> Liburan Impian Jadi Nyata
                </span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
                    Temukan Petualangan Indah Berikutnya
                </h1>
                <p class="text-lg sm:text-xl text-gray-200 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Jelajahi berbagai destinasi wisata eksotis di seluruh Indonesia bersama Namina Travel. Pengalaman terkurasi, harga transparan, dan kenangan abadi.
                </p>

                <!-- Quick Search Bar -->
                <form action="<?php echo e(route('destinations.index')); ?>" method="GET" class="bg-white/95 backdrop-blur-md p-2 rounded-2xl shadow-2xl flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto text-left">
                    <div class="flex-1 flex items-center px-4 py-2">
                        <i class="fas fa-search text-gray-400 mr-3 text-lg"></i>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Mau liburan ke mana? (Bali, Lombok, Bromo...)" 
                            class="w-full bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none text-sm sm:text-base font-medium"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary sm:px-8 rounded-xl font-semibold shadow-md">
                        Cari Wisata
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Popular Destinations -->
    <section class="py-20 px-4 bg-base-100">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Destinasi Populer</h2>
                    <p class="text-gray-500 mt-2 text-base">Pilihan favorit para wisatawan untuk liburan yang tak terlupakan</p>
                </div>
                <a href="<?php echo e(route('destinations.index')); ?>" class="hidden md:inline-flex items-center text-primary font-semibold hover:gap-2 transition-all">
                    Lihat Semua Destinasi <i class="fas fa-arrow-right ml-2 text-sm"></i>
                </a>
            </div>

            <?php if($destinations->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginala654b68189fc78604d72908e258b1f1f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala654b68189fc78604d72908e258b1f1f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.destination-card','data' => ['destination' => $destination]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('destination-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['destination' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($destination)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala654b68189fc78604d72908e258b1f1f)): ?>
<?php $attributes = $__attributesOriginala654b68189fc78604d72908e258b1f1f; ?>
<?php unset($__attributesOriginala654b68189fc78604d72908e258b1f1f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala654b68189fc78604d72908e258b1f1f)): ?>
<?php $component = $__componentOriginala654b68189fc78604d72908e258b1f1f; ?>
<?php unset($__componentOriginala654b68189fc78604d72908e258b1f1f); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="text-center mt-12 md:hidden">
                    <a href="<?php echo e(route('destinations.index')); ?>" class="btn btn-primary btn-block">Lihat Semua Destinasi</a>
                </div>
            <?php else: ?>
                <div class="text-center py-16 bg-gray-50 rounded-2xl border border-gray-100 max-w-md mx-auto">
                    <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum ada destinasi tersedia</h3>
                    <p class="text-gray-500 text-sm mt-1">Silakan kunjungi kembali nanti untuk melihat rilis paket destinasi terbaru kami.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-slate-50 border-y border-slate-100">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Mengapa Memilih Namina Travel</h2>
                <p class="text-gray-500 mt-2 text-base">Standar pelayanan unggulan yang menjamin kenyamanan Anda di setiap langkah petualangan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Destinasi Terkurasi</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Setiap lokasi dan rute perjalanan dipilih secara profesional untuk memastikan keindahan dan keamanan maksimal.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Transparan</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Tidak ada biaya tersembunyi. Semua fasilitas, tiket, dan akomodasi tertera jelas sejak awal pemesanan.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mb-6 text-2xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Bantuan CS 24/7</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">Tim representatif kami selalu siap siaga via WhatsApp untuk membantu koordinasi perjalanan Anda kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-base-100">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Apa Kata Wisatawan Kami</h2>
                <p class="text-gray-500 mt-2 text-base">Cerita nyata dari mereka yang telah merasakan kehangatan layanan Namina Travel</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-8 rounded-2xl">
                    <div class="flex items-center gap-1 text-amber-400 mb-4 text-sm">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"Namina Travel membuat perjalanan keluarga kami sangat berkesan! Mulai dari penjemputan, jadwal tour, hingga pilihan hotelnya sangat tepat dan teratur. Pelayanan bintang lima!"</p>
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" alt="Sarah Maharani" class="w-12 h-12 rounded-full object-cover ring-2 ring-primary/20" />
                        <div>
                            <h4 class="font-bold text-gray-900 flex items-center">
                                Sarah Maharani
                                <i class="fas fa-check-circle text-primary text-xs ml-1.5" title="Wisatawan Terverifikasi"></i>
                            </h4>
                            <p class="text-xs text-gray-500">Trip Liburan Keluarga ke Bali</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-8 rounded-2xl">
                    <div class="flex items-center gap-1 text-amber-400 mb-4 text-sm">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"Sistem pembayarannya sangat mudah dan tim CS via WhatsApp ramah sekali saat memverifikasi bukti transfer. Tour guide di lokasi sangat memahami spot foto terbaik!"</p>
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80" alt="Budi Prasetyo" class="w-12 h-12 rounded-full object-cover ring-2 ring-primary/20" />
                        <div>
                            <h4 class="font-bold text-gray-900 flex items-center">
                                Budi Prasetyo
                                <i class="fas fa-check-circle text-primary text-xs ml-1.5" title="Wisatawan Terverifikasi"></i>
                            </h4>
                            <p class="text-xs text-gray-500">Eksplorasi Bromo Sunrise</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 px-4">
        <div class="container mx-auto max-w-5xl">
            <div class="rounded-3xl bg-gradient-to-r from-primary to-indigo-700 p-8 sm:p-14 text-center text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl sm:text-4xl font-extrabold mb-4 tracking-tight">Siap untuk Petualangan Anda Selanjutnya?</h2>
                    <p class="text-base sm:text-lg text-white/90 mb-8 max-w-xl mx-auto leading-relaxed">
                        Pilih destinasi impian Anda, lakukan pemesanan dalam beberapa menit, dan nikmati liburan yang nyaman tanpa rasa khawatir.
                    </p>
                    <a href="<?php echo e(route('destinations.index')); ?>" class="btn btn-secondary px-8 font-bold shadow-lg shadow-black/20 text-base">
                        Jelajahi Paket Wisata
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/pages/home.blade.php ENDPATH**/ ?>