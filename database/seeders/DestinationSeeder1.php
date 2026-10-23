<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@nina-travel.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        $destinations = [
            [
                'name' => 'Paket Wisata Bromo 3D2N',
                'slug' => 'paket-wisata-bromo-3d2n',
                'description' => 'Nikmati pengalaman tak terlupakan mengunjungi Gunung Bromo dengan paket 3 hari 2 malam. Anda akan diajak menyaksikan sunrise spektakuler di Penanjakan, menjelajahi lautan pasir, dan merasakan kesejukan udara pegunungan. Termasuk mengunjungi Bukit Teletubbies yang hijau dan Pura Luhur Poten di kaki Gunung Bromo.',
                'location' => 'Taman Nasional Bromo Tengger Semeru, Jawa Timur',
                'province' => 'Jawa Timur',
                'city' => 'Malang',
                'address' => 'Jl. Raya Bromo No.1, Podokoyo, Tosari, Pasuruan',
                'image' => null,
                'price' => 2500000,
                'rating' => 4.8,
                'total_reviews' => 1280,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Liburan Bali 4D3N',
                'slug' => 'paket-liburan-bali-4d3n',
                'description' => 'Jelajahi keindahan Pulau Dewata dengan paket lengkap 4 hari 3 malam. Kunjungi tempat wisata ikonik seperti Tanah Lot, Ubud, Uluwatu, dan Pantai Kuta. Nikmati pengalaman berbelanja di pasar seni, menikmati tarian tradisional Kecak, dan bersantai di pantai-pantai indah Bali.',
                'location' => 'Bali, Indonesia',
                'province' => 'Bali',
                'city' => 'Denpasar',
                'address' => 'Jl. Raya Kuta No. 1, Kuta, Badung',
                'image' => null,
                'price' => 4500000,
                'rating' => 4.9,
                'total_reviews' => 2150,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Wisata Bromo 2D1N',
                'slug' => 'paket-wisata-bromo-2d1n',
                'description' => 'Nikmati keindahan Gunung Bromo dalam paket 2 hari 1 malam. Menyaksikan sunrise di Penanjakan, menjelajahi lautan pasir, dan melihat kawah Bromo dari dekat.',
                'location' => 'Taman Nasional Bromo Tengger Semeru, Jawa Timur',
                'province' => 'Jawa Timur',
                'city' => 'Probolinggo',
                'address' => 'Jl. Raya Bromo, Desa Ngadisari, Sukapura',
                'image' => null,
                'price' => 1500000,
                'rating' => 4.7,
                'total_reviews' => 980,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Liburan Bali 3D2N',
                'slug' => 'paket-liburan-bali-3d2n',
                'description' => 'Jelajahi keindahan Bali dalam 3 hari 2 malam. Kunjungi tempat wisata ikonik seperti Tanah Lot, Ubud, dan Pantai Kuta.',
                'location' => 'Bali, Indonesia',
                'province' => 'Bali',
                'city' => 'Denpasar',
                'address' => 'Jl. Raya Kuta No. 1, Kuta',
                'image' => null,
                'price' => 3500000,
                'rating' => 4.8,
                'total_reviews' => 1250,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wisata Banyuwangi 3D2N',
                'slug' => 'wisata-banyuwangi-3d2n',
                'description' => 'Jelajahi keindahan Banyuwangi dengan mengunjungi Kawah Ijen, Pantai Pulau Merah, dan Taman Nasional Alas Purwo.',
                'location' => 'Banyuwangi, Jawa Timur',
                'province' => 'Jawa Timur',
                'city' => 'Banyuwangi',
                'address' => 'Jl. Jenderal Ahmad Yani No. 78, Banyuwangi',
                'image' => null,
                'price' => 2800000,
                'rating' => 4.6,
                'total_reviews' => 750,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liburan Ke Bandung 3D2N',
                'slug' => 'liburan-ke-bandung-3d2n',
                'description' => 'Nikmati liburan seru di Bandung dengan mengunjungi tempat wisata populer seperti Tangkuban Perahu, Kawah Putih, dan Lembang.',
                'location' => 'Bandung, Jawa Barat',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'address' => 'Jl. Braga No. 1, Braga, Bandung',
                'image' => null,
                'price' => 3200000,
                'rating' => 4.5,
                'total_reviews' => 1100,
                'is_featured' => 1,
                'is_active' => 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
