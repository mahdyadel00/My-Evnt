<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SocialGallery\UpdateSocialGalleryRequest;
use App\Models\SocialGallery;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Class SocialGalleryController
 *
 * Handles CRUD operations for Social Gallery management
 *
 * @package App\Http\Controllers\Backend
 */
class SocialGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update social_gallery', ['only' => ['edit', 'update']]);
    }

    /**
     * Display social gallery management (redirects to edit)
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $socialGallery = SocialGallery::with('media')->first();

        if (!$socialGallery) {
            // Create default record if none exists
            $socialGallery = SocialGallery::create([
                'title'                 => 'Follow us',
                'instagram_handle'      => '@MyEvnt',
                'instagram_link'        => 'https://www.instagram.com',
                'status'                => true,
            ]);
        }

        // Since we only have one record, redirect to edit
        return view('backend.social-gallery.edit', compact('socialGallery'));
    }

    /**
     * Show the form for editing the specified social gallery
     *
     * @param SocialGallery $socialGallery
     * @return View
     */
    public function edit(SocialGallery $socialGallery): View
    {
        $socialGallery->load('media');

        return view('backend.social-gallery.edit', compact('socialGallery'));
    }

    /**
     * Update the specified social gallery
     *
     * @param UpdateSocialGalleryRequest $request
     * @param SocialGallery $socialGallery
     * @return JsonResponse|RedirectResponse
     */
    public function update(UpdateSocialGalleryRequest $request, SocialGallery $socialGallery)
    {   
        try {
            DB::beginTransaction();

            $socialGallery->update($request->safe()->all());

            // Update existing media post URLs
            if ($request->has('existing_post_urls')) {
                foreach ($request->input('existing_post_urls') as $mediaId => $postUrl) {
                    Media::where('id', $mediaId)
                        ->where('mediable_id', $socialGallery->id)
                        ->where('mediable_type', SocialGallery::class)
                        ->update(['post_url' => $postUrl]);
                }
            }

            // Handle new images upload
            if (count($request->files) > 0) {
                $this->saveSocialGalleryMedia($request, $socialGallery);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Social gallery updated successfully',
                    'data' => $socialGallery->load('media'),
                ]);
            }

            return redirect()->route('admin.social_galleries.edit', $socialGallery->id)
                ->with('success', 'Social gallery updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SocialGalleryController@update: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating social gallery'
                ], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Save social gallery media with post URLs
     *
     * @param UpdateSocialGalleryRequest $request
     * @param SocialGallery $socialGallery
     * @return void
     */
    private function saveSocialGalleryMedia(UpdateSocialGalleryRequest $request, SocialGallery $socialGallery): void
    {
        if ($request->hasFile('images')) {
            $postUrls = $request->input('post_urls', []);
            
            foreach ($request->file('images') as $index => $file) {
                // Generate unique filename to avoid conflicts
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Use storeAs instead of putFile for better Windows compatibility
                $path = $file->storeAs(
                    'socialgallery/' . now()->format('Y_m_d'),
                    $filename,
                    'public'
                );

                $socialGallery->media()->create([
                    'name' => 'images',
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'description' => $file->getClientOriginalName(),
                    'post_url' => $postUrls[$index] ?? null,
                    'order' => 0,
                    'is_main' => false,
                ]);
            }
        }
    }


    /**
     * Delete media (image) from social gallery
     *
     * @param Request $request
     * @param int $id Media ID
     * @return JsonResponse|RedirectResponse
     */
    public function destroyMedia(Request $request, int $id)
    {
        try {
            $media = Media::findOrFail($id);

            // Delete file from storage if exists
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }

            // Delete media record
            $media->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Image deleted successfully');
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SocialGalleryController@destroyMedia: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting image: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Delete the specified social gallery
     *
     * @param Request $request
     * @param SocialGallery $socialGallery
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Request $request, SocialGallery $socialGallery)
    {
        try {
            // Delete all associated media files
            foreach ($socialGallery->media as $media) {
                if (Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }
            }

            // Delete the social gallery record
            $socialGallery->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Social gallery deleted successfully'
                ]);
            }

            return redirect()->route('admin.social_galleries.index')
                ->with('success', 'Social gallery deleted successfully');
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SocialGalleryController@destroy: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting social gallery'
                ], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

}
