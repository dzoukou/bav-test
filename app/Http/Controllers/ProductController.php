<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $size = $request->query('size', 20);
        $search = $request->query('search');
        $query = Product::with('media')->latest();
        if ($search) {
            $query->whereRaw("name like ? or description like ?", ["%$search%", "%$search%"]);
        }
        $categories = $query->paginate($size);
        return ProductResource::collection($categories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->only(['name', 'description', 'is_service', 'discount', 'published', 'in_stock', 'price']));
        if ($request->hasFile('image')) {
            $product->addMedia($request->file('image'))->toMediaCollection('image');
        }
        return response(new ProductResource($product));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->only(['name', 'description', 'is_service', 'discount', 'published', 'in_stock', 'price']));
        if ($request->hasFile('image')) {
            $product->addMedia($request->file('iamge'))->toMediaCollection('image');
        }
        $product->save();
        return response(new ProductResource($product));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // delete product
        $product->delete();
        // empty response
        return response()->noContent();
    }
}
