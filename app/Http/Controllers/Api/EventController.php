<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $user = User::find($request->user()->id)->with('store');

        $events = Event::where('store_id', $user->store->id)->with('sales')->get();

        return $this->success($events);
    }

    public function show(ShowEventRequest $request)
    {
        $event = Event::find($request->id)->with('dates')->with('store')->with('stocks');

        return $this->success($event);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'cost' => ['required', 'numeric'],
            'localization' => ['required', 'string'],
            'organizer_contact' => ['required', 'string'],
            'organizer_name' => ['required', 'string'],
            'event_date.*' => ['required', 'date']
        ]);

        $user = User::find($request->user()->id)->with('store');

        $event = Event::create([
            'user_id' => $user->id,
            'store_id' => $user->store->id,
            'name' => $request->name,
            'cost' => $request->cost,
            'localization' => $request->localization,
            'organizer_contact' => $request->organizer_contact,
            'organizer_name' => $request->organizer_name
        ]);

        foreach($request->event_date as $date){
            EventDate::create([
                'event_id' => $event->id,
                'event_date' => $date,
            ]);
        }

        $event = Event::find($event->id)->with('dates')->with('store')->with('stocks');

        return $this->success($event, 'Evento criado com sucesso');
    }

    public function update(UpdateEventRequest $request)
    {
        $event = Event::find($request->id);

        $event->name = $request->name ?: $event->name;
        $event->cost = $request->cost ?: $event->cost;
        $event->localization = $request->localization ?: $event->localization;
        $event->organizer_contact = $request->organizer_contact ?: $event->organizer_contact;
        $event->organizer_name = $request->organizer_name ?: $event->organizer_name;
        $event->save();

        $event = Event::find($event->id)->with('dates')->with('store')->with('stocks');
        return $this->success($event, 'Evento atualizado com sucesso');
    }

    public function destroy(ShowEventRequest $request)
    {
        $event = Event::find($request->id);
        $event->dates()->delete();
        $event->dates()->stocks();
        $event->delete();

        return $this->success(null, 'Produto deletado com sucesso');
    }
}
