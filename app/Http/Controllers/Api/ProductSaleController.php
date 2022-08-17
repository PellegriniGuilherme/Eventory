<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSaleDestroyRequest;
use App\Http\Requests\ProductSaleRequest;
use App\Models\ProductsSale;
use App\Traits\ApiResponser;

class ProductSaleController extends Controller
{
    use ApiResponser;

    public function store(ProductSaleRequest $request)
    {
        ProductsSale::create([
            'product_id' => $request->product_id,
            'sale_id' => $request->sale_id,
            'amount' => $request->amount
        ]);

        return $this->success(null, 'Produto inserido com sucesso');
    }

    public function update(ProductSaleRequest $request)
    {
        $sale = ProductsSale::where('sale_id', $request->sale_id)
        ->where('product_id', $request->product_id)->first();

        $sale->amount = $request->amount;
        $sale->save();

        return $this->success(null, 'Produto alterado com sucesso');
    }

    public function destroy(ProductSaleDestroyRequest $request)
    {
        $productSale = ProductsSale::find($request->id);
        $productSale->delete();

        return $this->success(null, 'Produto excluído com sucesso');
    }

}
