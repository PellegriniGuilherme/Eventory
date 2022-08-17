<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CloseSale;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CloseSaleController extends Controller
{
    use ApiResponser;

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'payment_id' => ['required', 'exists:payment_methods,id'],
            'discount' => ['nullable', 'integer'],
            'discount_reason' => ['nullable', 'string']
        ]);

        $user = User::find($request->user()->id)->with('store');
        $sale = Sale::find($request->sale_id)->with('products');
        $payment = PaymentMethod::find($request->payment_id);

        $total = 0;
        $totalCost = 0;
        foreach($sale->products as $product){
            $total += $product->amount * $product->sale_price;
            $totalCost += $product->amount * $product->cost_price;
        }

        $totalTax = $total * ($payment->tax / 100);
        $totalProfit = $total - $totalCost;

        CloseSale::create([
            'user_id' => $user->id,
            'sale_id' => $request->sale_id,
            'payment_id' => $request->payment_id,
            'total' => $total,
            'total_tax' => $totalTax,
            'total_profit' => $totalProfit,
            'discount' => $request->discount ?: null,
            'discount_reason' => $request->discount_reason ?: null
        ]);

        return $this->success(null, 'Venda fechada com sucesso');
    }

}
