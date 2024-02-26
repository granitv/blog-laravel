<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allArticles = Article::all()->load('category');


        return response()->json([
            "articles" => $allArticles
        ]);
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
            'title' => 'required|max:100',
            'content' => 'required',
            'image' => 'required|file|image',
            'category_id' => 'required|numeric|exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('public/images');
        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
        ]);
        return response()->json([
            "message" => 'Article was created successfully',
            "success" => true
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            "article" => Article::find($id)
        ]);
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
            $thisArticle = Article::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => 'Article was not found',
                "success" => false
            ]);
        }
        $validated = $request->validate([
            'title' => 'max:100',
            'image' => 'file|image',
            'category_id' => 'numeric|exists:categories,id',
        ]);
        $oldImage = $thisArticle->image;
        $thisArticle->update($request->all());
        if ($request->file('image')) {
            Storage::delete($oldImage);
            $thisArticle->image = $request->file('image')->store('public/images');
            $thisArticle->save();
        };

        return response()->json([
            "message" => 'Article was updated successfully',
            "success" => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $thisArticle = Article::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "message" => 'Article was not found',
                "success" => false
            ]);
        }
        $oldImage = $thisArticle->image;
        Storage::delete($oldImage);
        $thisArticle->delete();

        return response()->json([
            "message" => 'Article was daleted successfully',
            "success" => true
        ]);
    }
}
