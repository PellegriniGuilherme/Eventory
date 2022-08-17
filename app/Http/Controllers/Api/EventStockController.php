<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Event;
use App\Models\EventStock;
use App\Models\StockAdjustment;
use App\Models\StockGeneral;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EventStockController extends Controller
{
    use ApiResponser;

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'event_id' => ['required', 'exists:events,id'],
            'amount' => ['required', 'number'],
        ]);

        $user = User::find($request->user()->id);
        $store = $user->store()->first();

        $stock = StockGeneral::where('store_id', $store->id)
        ->where('product_id', $request->id)->first();

        $stock->amount = $stock->amount - $request->amount;
        $stock->save();

        $stockEvent = EventStock::where('store_id', $store->id)->where('product_id', $request->id)->first();

        if(!$stockEvent){
            EventStock::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'event_id' => $request->event_id,
                'amount' => $request->amount,
            ]);
        }else{
            $stockEvent->amount = $stockEvent->amount + $request->amount;
            $stockEvent->save();
        }

        StockAdjustment::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'event_stock_id' => $stockEvent->id,
            'amount' => $request->amount,
            'adjustment_reason' => 'Entrada de Produto'
        ]);

        $event = Event::find($request->event_id)->with('dates')->with('store')->with('stocks');

        return $this->success($event, 'Estoque inserido com sucesso');
    }

    public function destroy(StockAdjustmentRequest $request)
    {
        $user = User::find($request->user()->id);

        $stock = EventStock::find($request->id);
        $stock->amount = $stock->amount - $request->amount;
        $stock->save();

        StockAdjustment::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'event_stock_id' => $stock->id,
            'amount' => $request->amount,
            'adjustment_reason' => $request->adjustment_reason
        ]);

        $event = Event::find($request->event_id)->with('dates')->with('store')->with('stocks');

        return $this->success($event, 'Estoque ajustado com sucesso');
    }
}
