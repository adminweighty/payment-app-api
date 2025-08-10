<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    /**
     * Get an event by ID or special code.
     */
    public function getEventByIdOrCode(string $identifier): ?Event
    {
        return Event::where('id', $identifier)
            ->orWhere('special_code', $identifier)
            ->first();
    }
}
