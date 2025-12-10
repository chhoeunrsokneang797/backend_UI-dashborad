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
        return response()->json([
            "list" => Province::all()
        ]);
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
