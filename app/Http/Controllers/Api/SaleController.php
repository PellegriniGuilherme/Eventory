<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleDestroyRequest;
use App\Http\Requests\SaleShowRequest;
use App\Models\Sale;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use ApiResponser;

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id']
        ]);

        $user = User::find($request->user()->id)->with('store');

        $sale = Sale::create([
            'event_id' => $request->event_id,
            'store_id' => $user->store->id,
            'user_id' => $user->id
        ]);

        return $this->success($sale, 'Venda aberta com sucesso');
    }

    public function show(SaleShowRequest $request)
    {
        $sale = Sale::find($request->id)->with('event')->with('closeSale')->with('products');

        return $this->success($sale);
    }

    public function destroy(SaleDestroyRequest $request)
    {
        $sale = Sale::find($request->id);

        $sale->cancellation_reason = $request->cancellation_reason;
        $sale->save();

        $sale->delete();

        return $this->success(null, 'Venda cancelada com sucesso');
    }

}
