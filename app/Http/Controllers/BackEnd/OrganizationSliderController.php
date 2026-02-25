<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrganizationSlider\UpdateOrganizationSliderRequest;
use App\Models\OrganizationSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationSliderController extends Controller
{
    public function edit()
    {
        $organization_slider = OrganizationSlider::first();

        return view('backend.organization_sliders.edit', compact('organization_slider'));
    }

    public function update(UpdateOrganizationSliderRequest $request)
    {
        try {
            DB::beginTransaction();
            $organization_slider = OrganizationSlider::first();

            if (!$organization_slider) {
                return redirect()->back()->with('error', 'Organization Slider not found');
            }
            $video_name_db = null;
            if ($request->hasFile('video')) {
                $path = public_path('uploads/organization_sliders/');
                $video = request('video');
                $video_name = time() . '.' . $video->getClientOriginalExtension();
                $video->move($path, $video_name);
                $video_name_db = 'uploads/organization_sliders/' . $video_name;
            } else {
                $video_name_db = $organization_slider->video;
            }
            if ($request->hasFile('video')) {
                if (file_exists(public_path($organization_slider->video))) {
                    unlink(public_path($organization_slider->video));
                }
            }

            $organization_slider->update([
                'title' => $request->title,
                'description' => $request->description,
                'video' => $video_name_db,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Organization Slider updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Organization Slider update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
