<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockGeneralAdjustmentRequest;
use App\Models\StockGeneralAdjustment;
use App\Models\StockGeneral;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class StockController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $user = User::find($request->user()->id)->with('store');
        $stocks = StockGeneral::where('store_id', $user->store->id)->with('stockGeneralAdjustment')->get();

        return $this->success($stocks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'amount' => ['required', 'integer']
        ]);

        $user = User::find($request->user()->id)->with('store');

        $stock = StockGeneral::where('store_id', $user->store->id)
        ->where('product_id', $request->id)->first();

        $stock->user_id = $user->id;
        $stock->amount = $stock->amount + $request->amount;
        $stock->save();

        StockGeneralAdjustment::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'stock_general_id' => $stock->id,
            'amount' => $request->amount,
            'adjustment_reason' => 'Entrada de Produto'
        ]);

        return $this->success(null, "Estoque atualizado com sucesso");
    }

    public function destroy(StockGeneralAdjustmentRequest $request)
    {
        $stock = StockGeneral::find($request->id);

        $user = User::find($request->user()->id)->with('store');

        $stock->amount = $stock->amount - $request->amount;
        $stock->save();

        StockGeneralAdjustment::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'stock_general_id' => $stock->id,
            'amount' => $request->amount,
            'adjustment_reason' => $request->adjustment_reason
        ]);

        return $this->success(null, "Estoque atualizado com sucesso");
    }

}
