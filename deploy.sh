#!/bin/bash
set -e

echo "=========================================="
echo "🚀 [Deploy] Memulai deployment Namina Travel"
echo "=========================================="

# Masuk ke direktori project di server
cd /home/naminatr/namina-travel || exit 1

# 1. Update kode dari repository GitHub
echo "📥 [1/5] Mengambil kode terbaru dari branch master..."
git fetch origin master
git reset --hard origin/master

# 2. Update dependencies PHP via Composer
echo "📦 [2/5] Memperbarui Composer dependencies..."
if command -v composer >/dev/null 2>&1; then
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
else
    echo "⚠️ Perintah composer tidak ditemukan di PATH, melewati composer install."
fi

# 3. Jalankan migrasi database
echo "🗄️ [3/5] Menjalankan migrasi database..."
php artisan migrate --force

# 4. Refresh & optimalkan cache Laravel
echo "🧹 [4/5] Membersihkan dan mengoptimalkan cache aplikasi..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Build frontend assets jika npm tersedia di server
echo "🎨 [5/5] Memeriksa asset frontend..."
if command -v npm >/dev/null 2>&1; then
    echo "⚙️ Menjalankan build frontend (Vite)..."
    npm install --no-audit --no-fund
    npm run build
fi

# Pastikan symbolic link storage terhubung
php artisan storage:link || true

echo "=========================================="
echo "✅ [Deploy] Deployment berhasil selesai!"
echo "=========================================="
