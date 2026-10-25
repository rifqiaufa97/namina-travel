<?php

namespace App\Http\Controllers\Admin;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends BaseController
{
    public function index()
    {
        $packages = Destination::latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'is_featured' => 'boolean',
        ], [
            'image.required' => 'Foto sampul utama wajib diunggah.',
            'image.image' => 'File foto sampul harus berupa gambar valid.',
            'image.max' => 'Ukuran foto sampul utama maksimal 10MB.',
            'images.*.image' => 'File foto galeri harus berupa gambar valid.',
            'images.*.max' => 'Setiap foto galeri maksimal berukuran 10MB.',
        ]);

        $imagePath = $request->file('image')->store('destinations', 'public');
        
        // Generate unique slug
        $slug = Str::slug($validated['name']);
        $count = 1;
        $originalSlug = $slug;
        
        while (Destination::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $package = Destination::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'includes' => $validated['includes'] ?? null,
            'excludes' => $validated['excludes'] ?? null,
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'address' => $validated['address'],
            'location' => $validated['location'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'created_by' => auth()->id(),
        ]);

        // Simpan gambar utama ke destination_images sebagai is_primary
        $package->images()->create([
            'path' => $imagePath,
            'is_primary' => true,
            'order' => 0,
        ]);

        // Simpan gambar galeri tambahan
        if ($request->hasFile('images')) {
            $order = 1;
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('destinations', 'public');
                $package->images()->create([
                    'path' => $imgPath,
                    'is_primary' => false,
                    'order' => $order++,
                ]);
            }
        }

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket wisata berhasil ditambahkan');
    }

    public function edit(Destination $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Destination $package)
    {
        \Log::info('Update request data:', $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'is_featured' => 'boolean',
        ], [
            'image.image' => 'File foto sampul harus berupa gambar valid.',
            'image.max' => 'Ukuran foto sampul utama maksimal 10MB.',
            'images.*.image' => 'File foto galeri harus berupa gambar valid.',
            'images.*.max' => 'Setiap foto galeri maksimal berukuran 10MB.',
        ]);

        $data = [];
        
        // Only update slug if name has changed
        if ($package->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $count = 1;
            $originalSlug = $slug;
            
            while (Destination::where('slug', $slug)->where('id', '!=', $package->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            $data['slug'] = $slug;
        }
        
        // Merge other data
        $data = array_merge($data, [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'includes' => $validated['includes'] ?? null,
            'excludes' => $validated['excludes'] ?? null,
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'address' => $validated['address'],
            'location' => $validated['location'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'is_featured' => $request->has('is_featured'),
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }
            $data['image'] = $request->file('image')->store('destinations', 'public');
            // Update primary image in destination_images
            $primary = $package->images()->where('is_primary', true)->first();
            if ($primary) {
                $primary->update(['path' => $data['image']]);
            } else {
                $package->images()->create([
                    'path' => $data['image'],
                    'is_primary' => true,
                    'order' => 0,
                ]);
            }
        }

        // Simpan gambar galeri tambahan
        if ($request->hasFile('images')) {
            $maxOrder = $package->images()->max('order');
            $order = ($maxOrder !== null ? (int)$maxOrder : 0) + 1;
            foreach ($request->file('images') as $img) {
                if ($img && $img->isValid()) {
                    $imgPath = $img->store('destinations', 'public');
                    $package->images()->create([
                        'path' => $imgPath,
                        'is_primary' => false,
                        'order' => $order++,
                    ]);
                }
            }
        }

        $package->update($data);
        \Log::info('Updated package data:', $package->toArray());

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket wisata berhasil diperbarui');
    }

    public function destroy(Destination $package)
    {
        if ($package->image && Storage::disk('public')->exists($package->image)) {
            Storage::disk('public')->delete($package->image);
        }
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket wisata berhasil dihapus');
    }

    public function deleteImage(Destination $package, $image)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Aksi ini hanya dapat dilakukan oleh Admin.'
            ], 403);
        }
        
        $imageId = is_object($image) ? $image->id : $image;
        $imageModel = $package->images()->where('id', $imageId)->first();
        
        if (!$imageModel) {
            return response()->json([
                'success' => false,
                'message' => 'Foto galeri tidak ditemukan.'
            ], 404);
        }
        
        // Jangan hapus primary image lewat sini
        if ($imageModel->is_primary) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus gambar utama dari galeri.'
            ], 400);
        }
        
        try {
            // Hapus file dari storage jika ada
            if ($imageModel->path && Storage::disk('public')->exists($imageModel->path)) {
                Storage::disk('public')->delete($imageModel->path);
            }
            
            // Hapus record dari database
            $imageModel->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus dari galeri.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}
