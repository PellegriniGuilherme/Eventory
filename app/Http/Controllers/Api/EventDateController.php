<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventDateRequest;
use App\Http\Requests\EventDateStoreRequest;
use App\Models\Event;
use App\Models\EventDate;
use Illuminate\Http\Request;

class EventDateController extends Controller
{
    public function store(EventDateStoreRequest $request)
    {
        EventDate::create([
            'event_id' => $request->event_id,
            'event_date' => $request->date,
        ]);

        $event = Event::find($request->event_id)->with('dates')->with('store')->with('stocks');

        return $this->success($event, 'Data adicionada com sucesso');
    }

    public function destroy(EventDateRequest $request)
    {
        $eventDate = EventDate::find($request->id);
        $eventDate->delete();

        $event = Event::find($request->event_id)->with('dates')->with('store')->with('stocks');
        return $this->success($event, 'Data removida com sucesso');
    }
}
