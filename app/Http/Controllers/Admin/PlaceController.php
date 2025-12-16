<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PlaceImage;
use App\Models\Admin\Place;
use App\Models\Admin\PlaceImages;
use Illuminate\Http\Request;

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
            $imagePath = null;

            if ($image) {
                $path = $image->image_path;
                $imagePath = str_starts_with($path, 'http')
                    ? $path
                    : asset('storage/' . $path);
            }

            $place->image = $imagePath; // add a new property for Blade
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
            'name' => $request->name,
            'town_name' => $request->town_name,
            'town_code' => $request->town,
            'barangay' => $request->barangay,
            'description' => $request->description,
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
