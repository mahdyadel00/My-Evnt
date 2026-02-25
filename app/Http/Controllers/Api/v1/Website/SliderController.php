<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\Website\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected function index()
    {
        $sliders = Slider::paginate(config("app.pagination"));

        return Count($sliders) > 0
            ? SliderResource::collection($sliders)
            : new ErrorResource('No sliders found');
    }
}
