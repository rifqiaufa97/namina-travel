<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Welcome Banner & Quick Actions -->
    <div class="bg-gradient-to-r from-primary to-primary-focus rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 rounded-full bg-white/10 pointer-events-none"></div>
        <div class="absolute right-20 -top-10 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="inline-block py-1 px-3 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md mb-2">
                    Overview Panel
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, <?php echo e(auth()->user()->name); ?>! 👋</h1>
                <p class="text-white/80 text-sm mt-1 max-w-xl">
                    Pantau kinerja penjualan paket wisata, verifikasi bukti transfer manual BCA dari pelanggan, dan kelola destinasi liburan secara real-time.
                </p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="<?php echo e(route('admin.packages.create')); ?>" class="btn btn-sm bg-white text-primary hover:bg-gray-100 border-none rounded-xl font-bold px-4 shadow-sm">
                    <i class="fas fa-plus mr-1.5 text-xs"></i> Tambah Paket
                </a>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm bg-white/20 hover:bg-white/30 text-white border-white/20 rounded-xl font-semibold px-4 backdrop-blur-md">
                    <i class="fas fa-receipt mr-1.5 text-xs"></i> Kelola Pesanan
                </a>
                <a href="<?php echo e(route('admin.analytics')); ?>" class="btn btn-sm bg-white/20 hover:bg-white/30 text-white border-white/20 rounded-xl font-semibold px-4 backdrop-blur-md">
                    <i class="fas fa-chart-line mr-1.5 text-xs"></i> Buka Analitik
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="badge badge-ghost text-[10px] font-bold uppercase text-purple-700 bg-purple-50">Lunas</span>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Pendapatan</span>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">
                Rp <?php echo e(number_format($stats['revenue'] ?? $stats['total_revenue'] ?? 0, 0, ',', '.')); ?>

            </div>
            <p class="text-[11px] text-gray-500 mt-2 flex items-center gap-1">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span>Dari transfer BCA terverifikasi</span>
            </p>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span class="badge badge-ghost text-[10px] font-bold uppercase text-amber-700 bg-amber-50">Semua Order</span>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Pesanan</span>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">
                <?php echo e(number_format($stats['total_orders'] ?? $stats['orders'] ?? 0)); ?>

            </div>
            <p class="text-[11px] text-gray-500 mt-2 flex items-center gap-1">
                <i class="fas fa-receipt text-blue-500"></i>
                <span>Seluruh transaksi tercatat</span>
            </p>
        </div>

        <!-- Total Paket Wisata -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
                <span class="badge badge-ghost text-[10px] font-bold uppercase text-emerald-700 bg-emerald-50">Katalog</span>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Paket Wisata</span>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">
                <?php echo e(number_format($stats['total_packages'] ?? $stats['packages'] ?? 0)); ?>

            </div>
            <p class="text-[11px] text-gray-500 mt-2 flex items-center gap-1">
                <i class="fas fa-globe-asia text-emerald-500"></i>
                <span>Tersedia untuk pemesanan</span>
            </p>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fas fa-users"></i>
                </div>
                <span class="badge badge-ghost text-[10px] font-bold uppercase text-blue-700 bg-blue-50">Pelanggan</span>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Pengguna Terdaftar</span>
            <div class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">
                <?php echo e(number_format($stats['total_users'] ?? $stats['users'] ?? 0)); ?>

            </div>
            <p class="text-[11px] text-gray-500 mt-2 flex items-center gap-1">
                <i class="fas fa-user-check text-blue-500"></i>
                <span>Akun aktif dalam platform</span>
            </p>
        </div>
    </div>

    <?php
        $ordersList = $recentOrders ?? $recent_orders ?? collect();
    ?>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clock text-primary"></i>
                    <span>Pesanan & Transaksi Terbaru</span>
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Daftar transaksi terakhir yang masuk dari pelanggan</p>
            </div>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-ghost text-primary font-semibold hover:bg-primary/5 rounded-xl">
                <span>Lihat Semua Pesanan</span>
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[11px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-6 font-bold">Invoice / ID</th>
                        <th class="py-3.5 px-6 font-bold">Pemesan</th>
                        <th class="py-3.5 px-6 font-bold">Destinasi</th>
                        <th class="py-3.5 px-6 font-bold">Tanggal</th>
                        <th class="py-3.5 px-6 font-bold">Total</th>
                        <th class="py-3.5 px-6 font-bold">Status Bayar</th>
                        <th class="py-3.5 px-6 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php $__empty_1 = true; $__currentLoopData = $ordersList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-mono font-bold text-xs text-gray-900 bg-gray-100 px-2 py-1 rounded-md">
                                    <?php echo e($order->invoice_number ?? '#' . $order->id); ?>

                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        <?php echo e(strtoupper(substr($order->user->name ?? ($order->customer_name ?? 'U'), 0, 1))); ?>

                                    </div>
                                    <div class="truncate max-w-xs">
                                        <span class="font-bold text-gray-900 block truncate"><?php echo e($order->customer_name ?: ($order->user->name ?? 'Pelanggan')); ?></span>
                                        <span class="text-xs text-gray-400 truncate block"><?php echo e($order->customer_email ?: ($order->user->email ?? '-')); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-medium text-gray-800">
                                    <?php echo e($order->destination->name ?? 'Destinasi #' . $order->destination_id); ?>

                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-xs text-gray-500">
                                <?php echo e($order->created_at->translatedFormat('d M Y, H:i')); ?>

                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-bold text-primary">
                                    Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?>

                                </span>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <?php if($order->payment_status === 'pending_verification'): ?>
                                    <span class="badge badge-info text-white font-bold text-xs px-2.5 py-1.5">
                                        <i class="fas fa-clock mr-1 text-[10px]"></i>Verifikasi
                                    </span>
                                <?php elseif($order->payment_status === 'paid' || $order->status === 'completed'): ?>
                                    <span class="badge badge-success text-white font-bold text-xs px-2.5 py-1.5">
                                        <i class="fas fa-check-circle mr-1 text-[10px]"></i>Lunas
                                    </span>
                                <?php elseif($order->payment_status === 'rejected'): ?>
                                    <span class="badge badge-error text-white font-bold text-xs px-2.5 py-1.5">
                                        <i class="fas fa-times mr-1 text-[10px]"></i>Ditolak
                                    </span>
                                <?php elseif($order->status === 'cancelled'): ?>
                                    <span class="badge badge-ghost text-xs text-gray-500 px-2.5 py-1.5">
                                        Dibatalkan
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning text-white font-bold text-xs px-2.5 py-1.5">
                                        Menunggu Bayar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-xs btn-outline btn-primary rounded-lg">
                                    <span>Rincian</span>
                                    <i class="fas fa-chevron-right text-[9px] ml-1"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <i class="fas fa-receipt text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-medium">Belum ada pesanan terbaru.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rifqi Aufa Rachmadi\Documents\Penulisan Ilmiah Aufa\nina-travel-2\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>