<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventCategoryResource;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::paginate(config("app.pagination"));

        return Count($categories) > 0
            ? EventCategoryResource::collection($categories->load('child'))
            : new ErrorResource('No categories found');
    }


    protected function show($id)
    {
        $category = EventCategory::find($id);

        return $category
            ? new EventCategoryResource($category)
            : new ErrorResource('Category not found');
    }
}
