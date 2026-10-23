<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Destination;

class UpdateDestinationImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = collect(File::files(public_path('storage/destinations')))
            ->filter(function($f) { return $f->getSize() > 0; })
            ->map(function($f) { return $f->getFilename(); })
            ->values();

        if ($files->isEmpty()) {
            $this->command->warn('Tidak ada file gambar valid di public/storage/destinations!');
            return;
        }

        $i = 0;
        $destinations = Destination::whereNull('image')->orWhere('image', '')->get();
        foreach ($destinations as $dest) {
            $dest->image = $files[$i % $files->count()];
            $dest->save();
            $i++;
        }
        $this->command->info('Field image pada destinasi kosong berhasil diupdate!');
    }
} 