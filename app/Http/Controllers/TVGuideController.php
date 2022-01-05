<?php

namespace App\Http\Controllers;

use App\Models\BroadcastCategory;
use Carbon\CarbonInterval;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use function __;
use function now;
use const PHP_VERSION;

class TVGuideController extends Controller
{
    public function index(Request $request): Response
    {
        $request->validate(['date' => 'date']);

        return Inertia::render('TVGuide', [
            'translations' => [
                'title' => __('TV Guide'),
                'category' => __('Category'),
            ],

            'date' => [
                'selected' => Date::parse($request->date('date'))->format('j F'),
                'options' => null
            ],

            'category' => [
                'selected' => null,
                'options' => Cache::remember(
                    key: 'TVGuide::categories',
                    ttl: CarbonInterval::minutes(15),
                    callback: static fn () => BroadcastCategory::all(['id', 'name'])
                )
            ]
        ]);
    }
}
