@extends('admin.layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Pengguna</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $users->total() ?? $users->count() }} Pengguna
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Daftar seluruh akun pelanggan dan admin terdaftar di sistem Namina Travel.</p>
        </div>
        <div class="w-full sm:w-72">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" id="userSearchInput" placeholder="Cari nama atau email..." 
                       class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm">
            </div>
        </div>
    </div>

    <!-- User Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="usersTable">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Tanggal Bergabung</th>
                        <th class="py-3.5 px-4 text-center">Status Akun</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition user-row">
                            <td class="py-3.5 px-4 text-center text-gray-400 font-medium">
                                {{ $loop->iteration + ($users->firstItem() ? $users->firstItem() - 1 : 0) }}
                            </td>

                            <!-- User Info & Avatar -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-gray-900 user-name">{{ $user->name }}</div>
                                        <div class="text-[11px] text-gray-400">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3.5 px-4 text-gray-600 user-email">
                                {{ $user->email }}
                            </td>

                            <!-- Joined Date -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-gray-500">
                                {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }} WIB
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Ditangguhkan
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                <form action="{{ route('admin.users.update-status', $user) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin mengubah status akun {{ $user->name }}?')">
                                    @csrf
                                    @method('PUT')
                                    @if($user->status === 'active')
                                        <input type="hidden" name="status" value="suspended">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 font-bold text-[11px] transition cursor-pointer">
                                            <i class="fas fa-user-slash mr-1.5"></i> Suspend
                                        </button>
                                    @else
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 font-bold text-[11px] transition cursor-pointer">
                                            <i class="fas fa-user-check mr-1.5"></i> Aktifkan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i class="fas fa-users-slash text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm font-semibold text-gray-600">Tidak ada data pengguna.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
            const email = row.querySelector('.user-email')?.textContent.toLowerCase() || '';

            if (name.includes(query) || email.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
