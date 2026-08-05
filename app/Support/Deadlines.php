<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Every deadline rule in the app lives here, not scattered across models
 * and controllers. When a module's rule changes (or a new jurisdiction
 * needs different numbers), this is the only file to touch.
 */
class Deadlines
{
    public static function sarDeadline(string|CarbonInterface $receivedAt): Carbon
    {
        return Carbon::parse($receivedAt)->addDays(30);
    }

    public static function breachNotifyDeadline(string|CarbonInterface $discoveredAt): Carbon
    {
        return Carbon::parse($discoveredAt)->addHours(72);
    }
}
