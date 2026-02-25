<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventGalleryController extends Controller
{
    /**
     * Get event gallery images
     */
    public function index(Request $request, $eventId): JsonResponse
    {
        try {
            // Convert eventId to integer
            $eventId = (int) $eventId;
            
            $event = Event::findOrFail($eventId);
            
            $gallery = $event->media()
                ->where('type', 'gallery')
                ->orderBy('order', 'asc')
                ->get()
                ->map(function ($media) {
                    return [
                        'id'                    => $media->id,
                        'name'                  => $media->name,
                        'description'           => $media->description,
                        'path'                  => $media->path,
                        'url'                   => asset('storage/' . $media->path),
                        'type'                  => $media->type,
                        'order'                 => $media->order,
                        'is_main'               => (bool) $media->is_main,
                        'size'                  => $media->size,
                        'created_at'            => $media->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'success'                       => true,
                'data'                          => $gallery,
                'message'                       => 'Gallery retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching event gallery: ' . $e->getMessage());
            
            return response()->json([
                'success'                       => false,
                'message'                       => 'Failed to fetch gallery images'
            ], 500);
        }
    }

    /**
     * Store new gallery image
     */
    public function store(Request $request, $eventId): JsonResponse
    {
        try {
            // Convert eventId to integer
            $eventId = (int) $eventId;
            
            $request->validate([
                'images'                        => 'required|array|min:1|max:10',
                'images.*'                      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
                'descriptions'                  => 'nullable|array',
                'descriptions.*'                => 'nullable|string|max:500',
            ]);

            $event = Event::findOrFail($eventId);
            $uploadedImages = [];

            foreach ($request->file('images') as $index => $image) {
                // Generate unique filename
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('events/gallery', $filename, 'public');

                // Get next order number
                $nextOrder = $event->media()->where('type', 'gallery')->max('order') + 1;

                // Create media record
                $media = $event->media()->create([
                    'name'                          => $image->getClientOriginalName(),
                    'description'                   => $request->descriptions[$index] ?? null,
                    'path'                          => $path,
                    'type'                          => 'gallery',
                    'order'                         => $nextOrder,
                    'size'                          => $image->getSize(),
                    'is_main'                       => false,
                ]);

                $uploadedImages[] = [
                    'id'                            => $media->id,
                    'name'                          => $media->name,
                    'description'                   => $media->description,
                    'path'                          => $media->path,
                    'url'                           => asset('storage/' . $media->path),
                    'type'                          => $media->type,
                    'order'                         => $media->order,
                    'is_main'                       => (bool) $media->is_main,
                    'size'                          => $media->size,
                ];
            }

            return response()->json([
                'success'                           => true,
                'data'                              => $uploadedImages,
                'message'                           => 'Images uploaded successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'                   => false,
                'message'                   => 'Validation failed',
                'errors'                    => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error uploading gallery images: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images'
            ], 500);
        }
    }

    /**
     * Get single gallery image
     */
    public function show($eventId, $mediaId): JsonResponse
    {
        try {
            // Convert to integers
            $eventId = (int) $eventId;
            $mediaId = (int) $mediaId;
            
            $event = Event::findOrFail($eventId);
            $media = $event->media()->where('id', $mediaId)->where('type', 'gallery')->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'description' => $media->description,
                    'path' => $media->path,
                    'url' => asset('storage/' . $media->path),
                    'type' => $media->type,
                    'order' => $media->order,
                    'is_main' => (bool) $media->is_main,
                    'size' => $media->size,
                ],
                'message' => 'Image retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching gallery image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch image'
            ], 500);
        }
    }

    /**
     * Update gallery image
     */
    public function update(Request $request, $eventId, $mediaId): JsonResponse
    {
        try {
            // Convert to integers
            $eventId = (int) $eventId;
            $mediaId = (int) $mediaId;
            
            $request->validate([
                'name'                      => 'nullable|string|max:255',
                'description'               => 'nullable|string|max:500',
                'order'                     => 'nullable|integer|min:0',
                'is_main'                   => 'nullable|in:0,1,true,false,on,off',
                'new_image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            ]);

            $event = Event::findOrFail($eventId);
            $media = $event->media()->where('id', $mediaId)->where('type', 'gallery')->firstOrFail();

            $updateData = $request->only(['name', 'description', 'order']);
            
            // Handle is_main field properly
            if ($request->has('is_main')) {
                $isMain = $request->input('is_main');
                $updateData['is_main'] = in_array($isMain, ['1', 'true', 'on'], true);
            }

            // Handle new image upload if provided
            if ($request->hasFile('new_image')) {
                $newImage = $request->file('new_image');
                
                // Delete old image file
                if (Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }
                
                // Upload new image
                $filename = Str::uuid() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('events/gallery', $filename, 'public');
                
                $updateData['path'] = $path;
                $updateData['size'] = $newImage->getSize();
            }

            // If setting as main image, unset other main images
            if (isset($updateData['is_main']) && $updateData['is_main']) {
                $event->media()->where('type', 'gallery')->update(['is_main' => false]);
            }

            $media->update($updateData);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'description' => $media->description,
                    'path' => $media->path,
                    'url' => asset('storage/' . $media->path),
                    'type' => $media->type,
                    'order' => $media->order,
                    'is_main' => (bool) $media->is_main,
                    'size' => $media->size,
                ],
                'message' => 'Image updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating gallery image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image'
            ], 500);
        }
    }

    /**
     * Delete gallery image
     */
    public function destroy($eventId, $mediaId): JsonResponse
    {
        try {
            // Convert to integers
            $eventId = (int) $eventId;
            $mediaId = (int) $mediaId;
            
            $event = Event::findOrFail($eventId);
            $media = $event->media()->where('id', $mediaId)->where('type', 'gallery')->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }

            // Delete media record
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting gallery image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    /**
     * Reorder gallery images
     */
    public function reorder(Request $request, $eventId): JsonResponse
    {
        try {
            // Convert eventId to integer
            $eventId = (int) $eventId;
            
            $request->validate([
                'images' => 'required|array',
                'images.*.id' => 'required|integer|exists:media,id',
                'images.*.order' => 'required|integer|min:0',
            ]);

            $event = Event::findOrFail($eventId);

            foreach ($request->images as $imageData) {
                $event->media()
                    ->where('id', $imageData['id'])
                    ->where('type', 'gallery')
                    ->update(['order' => $imageData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Images reordered successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error reordering gallery images: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder images'
            ], 500);
        }
    }

    /**
     * Set main image
     */
    public function setMain($eventId, $mediaId): JsonResponse
    {
        try {
            // Convert to integers
            $eventId = (int) $eventId;
            $mediaId = (int) $mediaId;
            
            $event = Event::findOrFail($eventId);
            $media = $event->media()->where('id', $mediaId)->where('type', 'gallery')->firstOrFail();

            // Unset all other main images
            $event->media()->where('type', 'gallery')->update(['is_main' => false]);

            // Set this image as main
            $media->update(['is_main' => true]);
            
            // Refresh the media instance to get updated data
            $media->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Main image set successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error setting main image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to set main image'
            ], 500);
        }
    }
}
