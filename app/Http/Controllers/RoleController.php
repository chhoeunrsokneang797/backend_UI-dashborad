<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  Role::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->input("key_name")
        // $request->input() // get all json data
        $role = new Role(); //  varible object
        $role->name = $request->input("name");
        $role->code = $request->input("code");
        $role->description = $request->input("description");
        $role->status = $request->input("status");
        $role->save();

        // Role::created([

        // ]);
        return  [
            "data" => $role,
            "message" => "insert successfully"
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Role::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return [
                "error" => true,
                "message" => "Data not found!"
            ];
        } else {
            $role->name = $request->input("name");
            $role->code = $request->input("code");
            $role->description = $request->input("description");
            $role->status = $request->input("status");
            $role->update();
            return [
                "data" => $role,
                "message" => "insert successfully"
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return [
                "error" => true,
                "message" => "Data not found!"
            ];
        } else {
            $role->delete();
            return [
                "data" => $role,
                "message" => "Data delete successfully"
            ];
        }
    }

    public function chageStatus(Request $request, $id)
    {
        // 1. Find the role by ID
        $role = Role::find($id);

        if (!$role) {
            return [
                "error" => true,
                "message" => "Data not found!"
            ];
        } else {
            // 2. Assign the new status value
            $role->status = $request->input("status");

            // 3. CRITICAL FIX: Save the changes to the database
            $role->save();

            return [
                // Note: The variable name "Data delete successfully" is confusing for an update function.
                "data" => $role,
                "message" => "Role status updated successfully! Status: " . $role->status
            ];
        }
    }
}
