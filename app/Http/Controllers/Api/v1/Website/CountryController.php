<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\Website\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
       $countries = Country::with([
           'data' => function($query){
               $query->where('lang' , app()->getLocale())->get();
           },
       ])->paginate(config("app.pagination"));

        return Count($countries) > 0
            ? CountryResource::collection($countries)
            : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.country')]));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Country $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $country = Country::with([
            "data" => function ($query) use ($request) {
                $query->where("lang", app()->getLocale())->get();
            },
        ])->where("id", $id)->first();
        return ($country)
            ? (($request->has("simple") && $request->simple == true)
                ? new CountryResource($country)
                : new CountryResource($country->load('states.cities')))
            : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.country')]));
    }
}
