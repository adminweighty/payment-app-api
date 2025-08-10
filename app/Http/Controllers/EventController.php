<?php

namespace App\Http\Controllers;

use App\Services\EventService;

class EventController extends Controller
{
    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function show($identifier)
    {
        $event = $this->eventService->getEventByIdOrCode($identifier);

        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'event' => $event
        ]);
    }
}
