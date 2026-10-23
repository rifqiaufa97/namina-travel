<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the about page
     *
     * @return \Illuminate\View\View
     */
    public function tentang()
    {
        $teamMembers = [
            [
                'name' => 'Nina',
                'role' => 'Founder & CEO',
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'bio' => 'Pecinta traveling dengan pengalaman lebih dari 10 tahun menjelajahi berbagai destinasi di Indonesia dan mancanegara.'
            ],
            [
                'name' => 'Rifqi',
                'role' => 'Tour Guide Expert',
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'bio' => 'Berkecimpung di dunia pariwisata selama 8 tahun dan menguasai berbagai destinasi wisata di Indonesia.'
            ]
        ];

        $stats = [
            'destinations' => \App\Models\Destination::count(),
            'happy_customers' => 1250,
            'tours' => 45,
            'years_experience' => 5
        ];

        return view('pages.tentang', compact('teamMembers', 'stats'));
    }

    /**
     * Display the contact page
     *
     * @return \Illuminate\View\View
     */
    public function kontak()
    {
        $contactInfo = [
            'alamat' => 'Tangerang Selatan, Bumi Serpong Residence, Blok B 34',
            'telepon' => '081282069087',
            'whatsapp' => '081282069087',
            'email' => 'rifqiaufa163@gmail.com',
            'jam_operasional' => 'Senin - Minggu, 08:00 - 17:00 WIB'
        ];

        $socialMedia = [
            ['icon' => 'fab fa-facebook', 'url' => '#', 'name' => 'Facebook'],
            ['icon' => 'fab fa-instagram', 'url' => '#', 'name' => 'Instagram'],
            ['icon' => 'fab fa-twitter', 'url' => '#', 'name' => 'Twitter'],
            ['icon' => 'fab fa-youtube', 'url' => '#', 'name' => 'YouTube']
        ];

        return view('pages.kontak', compact('contactInfo', 'socialMedia'));
    }
    
    /**
     * Handle contact form submission
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you would typically send an email or save the message
        // For now, we'll just return a success message
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda kembali.'
        ]);
    }
}
