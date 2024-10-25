<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductOptimizedCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct(){
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page') ?? 10;
        $products = Product::query();
        $products = Product::productSearchSortQuery($products,$request);
        return new ProductOptimizedCollection(ProductResource::collection($products->paginate($perPage)));
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
    public function store(StoreProductRequest $request)
    {
        return Product::createProduct($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load([]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
        return Product::changeProductData($request,$product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return Product::deleteProduct($product);
    }
}