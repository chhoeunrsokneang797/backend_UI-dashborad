<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // 1. Fetch data from the database
            $cat = Province::all();
            // 2. ✅ FIX 2: Return a successful JSON response
            // Laravel's json() function automatically handles the 200 OK status.
            return response()->json([
                'data' => $cat,
                'message' => 'categories fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            // 3. Fallback for server error (500 Internal Server Error)
            return response()->json([
                'message' => 'Failed to fetch categories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'nullable|string',
            'distand_from_city' => 'required|numeric',
            'status' => 'required|boolean',
        ]);
        $data = Province::create($validation);
        $data->save();
        return response()->json([
            "data" => $data,
            "message" => "Insert success",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            "data" => Province::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, string $id)
    {
        $validation = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'nullable|string',
            'distand_from_city' => 'required|numeric',
            'status' => 'required|boolean',
        ]);
        $data = Province::create($validation);
            return response()->json([
                "data" => $data,
                "message" =>  "Data delete success"
            ]);       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Province::find($id);
        if (!$data) {
            return response()->json([
                "error" => [
                    "delete" => "data not found!"
                ]
            ]);
        } else {
            $data->delete();
            return response()->json([
                "message" =>  "Data delete success"
            ]);
        }
    }
}
