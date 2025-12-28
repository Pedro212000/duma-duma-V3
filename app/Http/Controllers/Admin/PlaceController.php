<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Place;
use App\Models\Admin\PlaceImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    public function index()
    {
        $places = Place::select(
            'id',
            'place_code',
            'name',
            'barangay',
            'town_name',
            'town_code',
            'description',
            'status'
        )
            ->with([
                'images' => function ($q) {
                    $q->select('id', 'place_id', 'image_path')
                        ->orderBy('id')
                        ->limit(1);
                }
            ])
            ->orderByDesc('id')
            ->paginate(2); // ✅ paginate 2 items per page

        // Map each place to include the first image as a URL
        $places->getCollection()->transform(function ($place) {
            $image = $place->images->first();

            $place->image = $image
                ? '/storage/' . ltrim($image->image_path, '/')
                : null;

            return $place;
        });

        return view('admin.place_management.index', compact('places'));
    }


    public function images(Place $place)
    {
        $images = $place->images()
            ->select('image_path')
            ->get()
            ->map(function ($img) {
                return str_starts_with($img->image_path, 'http')
                    ? $img->image_path
                    : asset('storage/' . $img->image_path);
            });

        return response()->json($images);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.place_management.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'town_name' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'description' => 'required|string',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024', // 1MB per image
        ]);
        $place = Place::create([
            'name' => ucwords($request->name),
            'town_name' => $request->town_name,
            'town_code' => $request->town,
            'barangay' => $request->barangay,
            'description' => ucfirst($request->description),
            'status' => 'Approved'
        ]);

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $path = $file->store('places', 'public');
                PlaceImages::create([
                    'place_id' => $place->id,
                    'image_path' => $path,
                ]);
            }
        }
        return redirect()->route('place_management.index')->with('success', 'Place created successfully!');
    }

    public function deleteImage($id)
    {
        $image = PlaceImages::findOrFail($id);

        // delete file from storage
        if (\Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }

        // delete DB record
        $image->delete();

        return back()->with('status', 'Image deleted successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place_management)
    {
        $place_detail = $place_management;
        return view("admin.place_management.edit", compact('place_detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'town' => 'required|string',
            'town_name' => 'required|string',
            'barangay' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|array',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:1024', // 1MB
        ]);

        // Existing images count
        $existingImagesCount = $place->images()->count();

        // New images count
        $newImagesCount = $request->hasFile('image')
            ? count($request->file('image'))
            : 0;

        if (($existingImagesCount + $newImagesCount) > 7) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Maximum of 7 images allowed. You currently have {$existingImagesCount}.");
        }

        // Update place details
        $place->update([
            'name' => ucwords($request->name),
            'town_code' => $request->town,
            'town_name' => $request->town_name,
            'barangay' => $request->barangay,
            'description' => ucfirst($request->description),
        ]);

        // Save new images (if any)
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('places', 'public');

                $place->images()->create([
                    'image_path' => $path
                ]);
            }
        }

        return redirect()
            ->route('place_management.edit', $place->id)
            ->with('status', 'Place updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $place = Place::with('images')->findOrFail($id);

        // Delete image files first
        foreach ($place->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        // Delete place (cascade deletes DB image records)
        $place->delete();

        return redirect()
            ->route('place_management.index')
            ->with('status', 'Place deleted successfully.');
    }
}
