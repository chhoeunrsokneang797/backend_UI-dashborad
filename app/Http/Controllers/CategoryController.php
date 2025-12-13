<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // 1. Fetch data from the database
            $cat = Category::all();
            // 2. ✅ FIX 2: Return a successful JSON response
            // Laravel's json() function automatically handles the 200 OK status.
            return response()->json([
                'data' => $cat,
                'message' => 'Roles fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            // 3. Fallback for server error (500 Internal Server Error)
            return response()->json([
                'message' => 'Failed to fetch roles.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->input("key_name")
        // $request->input() // get all json data
        $cat = new Category(); //  varible object
        $cat->name = $request->input("name");
        $cat->description = $request->input("description");
        $cat->status = $request->input("status");
        $cat->parent_id = $request->input("parent_id");

        $cat->save();

        // cat::created([

        // ]);
        return  [
            "data" => $cat,
            "message" => "insert successfully"
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Category::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cat = Category::find($id);
        if (!$cat) {
            return [
                "error" => true,
                "message" => "Data not found!"
            ];
        } else {
            $cat->name = $request->input("name");
            $cat->description = $request->input("description");
            $cat->status = $request->input("status");
            $cat->parent_id = $request->input("parent_id");
            $cat->update();
            return [
                "data" => $cat,
                "message" => "insert successfully"
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cat = Category::find($id);
        if (!$cat) {
            return [
                "error" => true,
                "message" => "Data not found!"
            ];
        } else {
            $cat->delete();
            return [
                "data" => $cat,
                "message" => "Data delete successfully"
            ];
        }
    }
}
