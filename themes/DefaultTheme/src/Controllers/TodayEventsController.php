<?php

namespace Themes\DefaultTheme\src\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Morilog\Jalali\Jalalian;

class TodayEventsController extends Controller
{
    public function index()
    {
        $today = Jalalian::now(); // تاریخ امروز شمسی
        $year = $today->getYear();
        $month = str_pad($today->getMonth(), 2, '0', STR_PAD_LEFT);
        $day = str_pad($today->getDay(), 2, '0', STR_PAD_LEFT);

        $response = Http::withoutVerifying()->get("https://holidayapi.ir/jalali/{$year}/{$month}/{$day}");

        $events = [];
        if ($response->ok()) {
            $events = $response->json('data') ?? [];
        }

        return view('widgets.today-events', [
            'today' => $today->format('%A %d %B %Y'),
            'events' => $events,
        ]);
    }
}
