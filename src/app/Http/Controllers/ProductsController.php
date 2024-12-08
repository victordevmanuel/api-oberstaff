<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sold;



class ProductsController extends Controller
{

    public function index()
    {
        $products = Product::where('status', 1)
            ->with('category:id,name')
            ->get(['id', 'name', 'price', 'description', 'category_id', 'amount']);
        return response()->json($products);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'category_id' => 'required|numeric|exists:categories,id',
            'amount' => 'required|numeric',
        ]);

        $existingProduct = Product::where('name', $request->name)->first();

        if ($existingProduct) {
            return response()->json(['message' => 'El producto ya existe'], 400);
        }

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }
    public function show($id)
    {
        $product = Product::where('status', 1)
            ->with('category:id,name')
            ->find($id, ['name', 'price', 'description', 'category_id', 'amount']);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrada'], 404);
        }

        return response()->json($product);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product || $product->status != 1) {
            return response()->json(['message' => 'Producto no encontrada'], 404);
        }

        $product->update($request->all());
        return response()->json($product, 200);
    }
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product || $product->status != 1) {
            return response()->json(['message' => 'Producto no encontrada'], 404);
        }

        $product->status = 0;
        $product->save();
        return response()->json(['message' => 'Producto eliminada'], 200);
    }
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $product = Product::find($id);

        if (!$product || $product->status != 1) {
            return response()->json(['message' => 'Producto no encontrada'], 404);
        }

        $product->amount += $request->amount;
        $product->save();

        return response()->json($product, 200);
    }
    public function soldStok(Request $request, $id)
    {
        $request->validate([
            'sold' => 'required|numeric',
        ]);
    
        $sold = new Sold([
            'product_id' => $id,
            'sold' => $request->sold,
        ]);
        $sold->save();
    
        $product = Product::find($id);
    
        if ($product && $product->status == 1) {
            $product->amount -= $request->sold;
            $product->save();
        }
    
        return response()->json($sold, 200);
    }


    public function graphic()
    {
        $sales = Sold::selectRaw('product_id, COUNT(*) as sales_count, SUM(sold) as total_sold')
            ->groupBy('product_id')
            ->get();
    
        $summary = $sales->map(function ($sale) {
            $product = Product::find($sale->product_id, ['name']);
            $individualSales = Sold::where('product_id', $sale->product_id)
                ->get(['created_at', 'sold']);
    
            return [
                'product_id' => $sale->product_id,
                'product_name' => $product ? $product->name : 'Producto no encontrado',
                'sales_count' => $sale->sales_count,
                'total_sold' => $sale->total_sold,
                'individual_sales' => $individualSales,
            ];
        });
    
        // Ordenar por total_sold de mayor a menor
        $sortedSummary = $summary->sortByDesc('total_sold')->values();
    
        return response()->json($sortedSummary);
    }

}
