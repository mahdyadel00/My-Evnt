<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Http\Resources\Api\v1\Website\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Event;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function index(Request $request, $country_id, $state_id)
    {
        //get Country by id
        $country = Country::where("id", $country_id)->first();
        if ($country) {
            $state = State::where(["country_id" => $country_id, "id" => $state_id])->first();

            if ($state) {
                $cities = City::where("state_id", $state_id)->with([
                    "data" => function ($query) use ($request) {
                        $query->where("lang", app()->getLocale())->get();
                    },
                ])->paginate(config("app.pagination"));

                if ($cities) {
                    return  CityResource::collection($cities);
                }

                return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.city')]));
            }

            return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.state')]));
        }

        return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.country')]));
    }
    public function show(Request $request, $country_id, $state_id,  $id)
    {
        $country = Country::where("id", $country_id)->first();
        if ($country) {
            $state = State::where(["id" => $state_id, "country_id" => $country->id])->first();

            if ($state) {
                $city = City::where(["id" => $id, "state_id" => $state_id])
                    ->with([
                        "data" => function ($query) use ($request) {
                            $query->where("lang", app()->getLocale())->get();
                        }
                    ])->first();

                if ($city) {
                    return new CityResource($city);
                }

                return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.city')]));
            }

            return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.state')]));
        }

        return new ErrorResource(__('website.not_found', ['attribute' => __('attributes.country')]));
    }

    public function events()
    {
        try{
            DB::beginTransaction();
            $events = Event::get();

            DB::commit();
            return SuccessResource::make([
                'message'       => __('Events Retrived Successfully'),
                'events'        => EventResource::collection($events),
            ])->withWrappData();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AuthController@events: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource(__('Events Retrived Failed'));
        }
    }
}
