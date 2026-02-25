<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\Website\StateResource;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request, $country_id)
    {
        //get country id
        $country = Country::where("id", $country_id)->first();
        if ($country != null) {
            //Get All State by country id
            $states = State::with([
                "data" => function ($query) use ($request) {
                    $query->where("lang", app()->getLocale())->get();
                }
            ])->where("country_id", $country_id)->paginate(config("app.pagination"));
            return Count($states) > 0
                ? StateResource::collection($states)
                : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.states')]));
        }
        return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.country')]));
    }

    public function show(Request $request, $country_id, $id)
    {
        //get country by id
        $country = Country::where("id", $country_id)->first();
        if ($country != null) {
            $state = State::where([
                "country_id" => $country_id,
                "id" => $id
            ])
                ->with([
                    "data" => function ($query) use ($request) {
                        $query->where("lang", app()->getLocale())->get();
                    }
                ])->first();
            return ($state)
                ? (($request->has("simple") && $request->simple == true)
                    ? new StateResource($state)
                    : new StateResource($state->load('cities')))
                : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.state')]));
        } else {
            return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.country')]));
        }
    }

}
