<?php

namespace Database\Seeders;

use App\Models\VisitorTracking;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitorTrackingSeeder extends Seeder
{
    public function run()
    {
        $devices = ['Desktop', 'Mobile', 'Tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
        $platforms = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
        $countries = ['Indonesia', 'Malaysia', 'Singapore', 'Thailand', 'Vietnam', 'Philippines'];
        $cities = [
            'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar',
            'Kuala Lumpur', 'Penang', 'Johor Bahru',
            'Singapore',
            'Bangkok', 'Phuket', 'Chiang Mai',
            'Ho Chi Minh', 'Hanoi', 'Da Nang',
            'Manila', 'Cebu', 'Davao'
        ];

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($i = 0; $i < 500; $i++) {
            $date = $this->randomDate($startDate, $endDate);
            
            VisitorTracking::create([
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => 'Mozilla/5.0 (' . $platforms[array_rand($platforms)] . ') AppleWebKit/537.36 (KHTML, like Gecko) ' . $browsers[array_rand($browsers)] . '/100.0',
                'page_visited' => $this->getRandomPage(),
                'referrer' => rand(0, 1) ? 'https://www.google.com' : null,
                'device_type' => $devices[array_rand($devices)],
                'browser' => $browsers[array_rand($browsers)],
                'platform' => $platforms[array_rand($platforms)],
                'country' => $countries[array_rand($countries)],
                'city' => $cities[array_rand($cities)],
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function randomDate($startDate, $endDate)
    {
        $randomTimestamp = mt_rand($startDate->timestamp, $endDate->timestamp);
        return Carbon::createFromTimestamp($randomTimestamp);
    }

    private function generateRandomIP()
    {
        return mt_rand(1, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
    }

    private function getRandomPage()
    {
        $pages = [
            '/',
            '/destinasi',
            '/destinasi/1',
            '/destinasi/2',
            '/tentang',
            '/kontak',
            '/auth/login',
            '/auth/register',
        ];
        return $pages[array_rand($pages)];
    }
}
