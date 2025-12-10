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
        return  Category::all();
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
