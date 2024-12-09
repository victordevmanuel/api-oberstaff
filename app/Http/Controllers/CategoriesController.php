<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; // Ensure that the Product class exists in this namespace


class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->with('products')->get(['id', 'name', 'status']);
        return response()->json($categories);
    }
    
    public function select()
    {
        $categories = Category::where('status', 1)->get(['id', 'name']);
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // Add other fields here if necessary
        ]);

        $existingCategory = Category::where('name', $request->name)->first();

        if ($existingCategory) {
            return response()->json(['message' => 'La categoria ya existe'], 400);
        }

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::where('status', 1)->with('products')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }

        return response()->json($category);
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category || $category->status != 1) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }

        $category->update($request->all());
        return response()->json($category, 200);
    }



    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category || $category->status != 1) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }

        $category->status = 0;
        $category->save();
        return response()->json(['message' => 'Categoria eliminada'], 200);
    }
  
}
