<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventCategoryResource;
use App\Models\EventCategory;

class CategoryController extends Controller
{
   public function index()
   {
       $categories = EventCategory::paginate(config("app.pagination"));

       return Count($categories) > 0
           ? EventCategoryResource::collection($categories)
           : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.category')]));
   }


   protected function show($id)
   {
       $category = EventCategory::find($id);

       return $category
           ? new EventCategoryResource($category)
           : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.category')]));
   }
}
