<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\GetProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StockGeneral;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponser;

    function index(Request $request)
    {
        $user = User::find($request->user()->id)->with('store');

        $products = Product::where('store_id', $user->store->id)->with('images')->with('generalStock')->with('eventStock')->get();

        return $this->success($products);
    }

    function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'sale_price' => ['required', 'numeric'],
            'cost_price' => ['required', 'numeric'],
            'amount' => ['required', 'integer'],
            'image' => ['required', 'image']
        ]);

        $user = User::find($request->user()->id)->with('store');

        $product = Product::create([
            'user_id' => $user->id,
            'store_id' => $user->store->id,
            'name' => $request->name,
            'description' => $request->description,
            'sale_price' => $request->sale_price,
            'cost_price' => $request->cost_price
        ]);

        $path = $request->file('image')->storeAs('products', $product->id.'.png');
        $url = Storage::url($path);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => $path,
            'url' => $url,
            'order' => 0
        ]);

        StockGeneral::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => $request->amount,
        ]);

        $product = Product::find($product->id)->with('images')->with('generalStock');

        return $this->success($product, 'Produto criado com sucesso');
    }

    function show(getProductRequest $request)
    {
        $product = Product::findOrFail($request->id)->with('images')->with('generalStock')->with('eventStock');

        return $this->success($product);
    }

    function update(UpdateProductRequest $request)
    {
        $product = Product::findOrFail($request->id);

        $product->name = $request->name ?: $product->name;
        $product->description = $request->description ?: $product->description;
        $product->sale_price = $request->sale_price ?: $product->sale_price;
        $product->cost_price = $request->cost_price ?: $product->cost_price;

        if($request->file('image')){

            $path = $request->file('image')->storeAs('products', $product->id.'.png');
            $url = Storage::url($path);

            $product->images()->delete();

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'url' => $url,
                'order' => 0
            ]);
        }

        $product->save();

        $product = Product::find($product->id)->with('images');

        return $this->success($product, 'Produto criado com sucesso');
    }

    function destroy(DeleteProductRequest $request)
    {
        $product = Product::findOrFail($request->id);

        $product->images()->delete();
        $product->generalStock()->delete();
        $product->eventStock()->delete();
        $product->delete();

        return $this->success(null, 'Produto deletado com sucesso');
    }

}
