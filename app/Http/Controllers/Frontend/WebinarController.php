<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class WebinarController
 *
 * Handles frontend webinar page display
 *
 * @package App\Http\Controllers\Frontend
 */
class WebinarController extends Controller
{
    /**
     * Display the webinar page
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $setting = Setting::first();
        $webinar = Webinar::where('slug', $slug)
            ->where('status', true)
            ->with([
                'media',
                'aboutwebinars',
                'speakers',
                'cards'
            ])
            ->firstOrFail();

        return view('Frontend.webinar.show', compact('webinar', 'setting'));
    }
}
