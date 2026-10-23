<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TravelManagementController extends Controller
{
    protected $openWeatherApiKey = 'YOUR_OPENWEATHER_API_KEY'; // Replace with your actual API key

    /**
     * Display the travel management dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.travel-management.index');
    }

    /**
     * Generate travel itinerary based on user input
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateItinerary(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'interests' => 'required|array|min:1',
                'interests.*' => 'string',
                'budget' => 'nullable|numeric|min:0',
            ]);

            // Calculate number of days
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $numberOfDays = $endDate->diffInDays($startDate) + 1;

            // Sample activities based on interests
            $activities = [
                'sejarah' => [
                    'Mengunjungi museum bersejarah',
                    'Melihat monumen bersejarah',
                    'Mempelajari budaya lokal',
                    'Mengunjungi situs warisan dunia'
                ],
                'alam' => [
                    'Mendaki gunung',
                    'Menikmati pantai',
                    'Mengunjungi air terjun',
                    'Berjalan-jalan di taman nasional'
                ],
                'kuliner' => [
                    'Mencicipi makanan khas',
                    'Mengikuti tur kuliner',
                    'Belanja di pasar tradisional',
                    'Mengikuti kelas memasak'
                ]
            ];

            $itinerary = [];
            $interestsList = $validated['interests'];
            
            for ($i = 1; $i <= $numberOfDays; $i++) {
                $dailyActivities = [];
                $currentDate = $startDate->copy()->addDays($i-1);
                
                // Morning activity
                $morningInterest = $interestsList[array_rand($interestsList)];
                $dailyActivities[] = [
                    'time' => '09:00',
                    'activity' => $activities[$morningInterest][array_rand($activities[$morningInterest])] . ' di ' . $validated['destination'],
                    'description' => 'Aktivitas pagi dengan tema ' . $morningInterest
                ];
                
                // Lunch
                $dailyActivities[] = [
                    'time' => '12:00',
                    'activity' => 'Makan siang di restoran lokal',
                    'description' => 'Mencicipi hidangan khas ' . $validated['destination']
                ];
                
                // Afternoon activity
                $afternoonInterest = $interestsList[array_rand($interestsList)];
                $dailyActivities[] = [
                    'time' => '14:00',
                    'activity' => $activities[$afternoonInterest][array_rand($activities[$afternoonInterest])] . ' di ' . $validated['destination'],
                    'description' => 'Aktivitas sore dengan tema ' . $afternoonInterest
                ];
                
                $itinerary[] = [
                    'day' => $i,
                    'date' => $currentDate->format('Y-m-d'),
                    'day_name' => $currentDate->isoFormat('dddd'),
                    'activities' => $dailyActivities
                ];
            }

            return response()->json([
                'success' => true,
                'itinerary' => $itinerary,
                'destination' => $validated['destination'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_days' => $numberOfDays,
                'interests' => $interestsList
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating itinerary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat itinerary. Silakan coba lagi.'
            ], 500);
        }
    }

    // Method untuk menghasilkan daftar persiapan perjalanan
    public function getTravelChecklist(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination' => 'required|string|max:255',
                'trip_duration' => 'required|integer|min:1',
                'purpose' => 'required|in:business,leisure,adventure,family',
            ]);

            // Daftar item checklist berdasarkan kategori
            $checklist = [
                'dokumen' => [
                    'Tiket pesawat/kereta',
                    'Hotel/villa booking confirmation',
                    'Paspor/KTP/SIM',
                    'Asuransi perjalanan',
                ],
                'pakaian' => [
                    'Pakaian sesuai durasi',
                    'Pakaian dalam',
                    'Jaket/sweater',
                    'Pakaian renang',
                    'Topi dan kacamata hitam'
                ],
                'kesehatan' => [
                    'Obat-obatan pribadi',
                    'Masker cadangan',
                    'Hand sanitizer',
                    'Tisu basah',
                    'Sunblock'
                ],
                'elektronik' => [
                    'Charger ponsel',
                    'Power bank',
                    'Kabel data',
                    'Adapter listrik',
                    'Kamera'
                ],
                'lainnya' => [
                    'Tas kecil untuk sehari-hari',
                    'Botol minum',
                    'Payung/jas hujan',
                    'Buku catatan dan pulpen',
                    'Snack ringan'
                ]
            ];

            // Menyesuaikan checklist berdasarkan tujuan perjalanan
            switch ($validated['purpose']) {
                case 'business':
                    $checklist['pakaian'][] = 'Pakaian formal';
                    $checklist['pakaian'][] = 'Sepatu formal';
                    $checklist['elektronik'][] = 'Laptop';
                    $checklist['elektronik'][] = 'Kartu nama';
                    break;
                    
                case 'adventure':
                    $checklist['pakaian'][] = 'Sepatu gunung/sandal gunung';
                    $checklist['pakaian'][] = 'Tas carrier';
                    $checklist['kesehatan'][] = 'P3K lengkap';
                    $checklist['kesehatan'][] = 'Obat nyamuk';
                    $checklist['lainnya'][] = 'Senter/headlamp';
                    break;
                    
                case 'family':
                    $checklist['pakaian'][] = 'Pakaian ganti ekstra';
                    $checklist['kesehatan'][] = 'Obat-obatan anak';
                    $checklist['lainnya'][] = 'Mainan anak';
                    $checklist['lainnya'][] = 'Snack anak';
                    break;
            }

            // Menambahkan item khusus berdasarkan durasi
            if ($validated['trip_duration'] > 7) {
                $checklist['pakaian'][] = 'Laundry bag';
                $checklist['kesehatan'][] = 'Obat-obatan tambahan';
            }

            return response()->json([
                'success' => true,
                'checklist' => $checklist,
                'destination' => $validated['destination'],
                'trip_duration' => $validated['trip_duration'],
                'purpose' => $validated['purpose']
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating travel checklist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat daftar persiapan. Silakan coba lagi.'
            ], 500);
        }
    }

    private function getWeatherForecast($destination, $date)
    {
        try {
            $response = Http::get("http://api.openweathermap.org/data/2.5/forecast", [
                'q' => $destination,
                'appid' => $this->openWeatherApiKey,
                'units' => 'metric',
                'cnt' => 40 // 5-day forecast with 3-hour intervals
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            
            return ['error' => 'Tidak dapat mengambil data cuaca'];
            
        } catch (\Exception $e) {
            return ['error' => 'Layanan cuaca tidak tersedia'];
        }
    }
}
