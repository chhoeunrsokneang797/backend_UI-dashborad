<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // 1. Fetch data from the database
            $brands = Brand::all();

            // 2. ✅ FIX 2: Return a successful JSON response
            // Laravel's json() function automatically handles the 200 OK status.
            return response()->json([
                'data' => $brands,
                'message' => 'brands fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            // 3. Fallback for server error (500 Internal Server Error)
            return response()->json([
                'message' => 'Failed to fetch brands.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the data and store it in a separate variable
        $request->validate([
            'name' => 'required|string|max:255',
            // FIX: Added a comma before the $id to ignore the current record
            'code' => 'required|string|unique:brands,code',
            'from_country' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Handle the image upload
        $imagePath = null;
        // Fix: Use hasFile() instead of hash_file()
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        // 3. Create the brand using the validated data
        $brand = Brand::create([
            'name'         => $request->name,
            'code'         => $request->code,
            'status'       => $request->status,
            'from_country' => $request->from_country, // Correctly passing the field now
            'image'        => $imagePath,
        ]);

        // 4. Return the response
        return response()->json([
            'data' => $brand,
            'message' => "Data created successfully"
        ], 200); // Fix: Use 201 (Created) instead of 500 (Server Error)
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brands = Brand::all($id);
        return response()->json([
            'message' => $brands,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) // Keep $id here
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Ignore the current ID so it doesn't fail unique check on itself
            'code' => 'required|string|unique:brands,code,' . $id,
            'from_country' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public');
            $brand->image = $imagePath;
        }

        $brand->update([
            'name'         => $request->name,
            'code'         => $request->code,
            'status'       => $request->status,
            'from_country' => $request->from_country,
        ]);

        return response()->json([
            'data' => $brand,
            'message' => "Data updated successfully"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        // Delete the image if it exists
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }
        $brand->delete();
        return response()->json([
            'message' => 'Delete successful',
            'data' => $brand,
        ], 200);
    }
}
