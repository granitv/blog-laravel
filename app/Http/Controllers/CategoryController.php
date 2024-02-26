<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
        ]);
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json([
            "message" => 'Category '.$category->title.' was created successfully',
            "success" => true
        ]);
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
        try {
            $thisCategory = Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => 'Category was not found',
                "success" => false
            ]);
        }
        $validated = $request->validate([
            'name' => 'max:100',
        ]);
        $thisCategory->update($request->all());

        return response()->json([
            "message" => 'Category was updated successfully',
            "success" => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
