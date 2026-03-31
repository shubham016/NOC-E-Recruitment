<?php

use Carbon\Carbon;

if (!function_exists('adToBS')) {
    function adToBS($date)
    {
        return Carbon::parse($date)->format('Y-m-d'); // temporary fallback
    }
}