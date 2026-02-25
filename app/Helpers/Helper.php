<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

function saveMedia($request, $model): bool
{
    return DB::transaction(function () use ($request, $model) {
        $files = [];
        $mediaNamesToDelete = []; 

        // collect the media from the request
        foreach ($request->files->all() as $key => $media) {
            // store the media name for deletion later
            $mediaNamesToDelete[] = $key;

            if (is_array($media) && count($media) > 1) {
                foreach ($media as $index => $file) {
                    $file = $request->file($key)[$index] ?? $request->file($key)[$index]['file'];

                    $stored = storeOptimizedMedia($file, $model);

                    $files[] = [
                        'name' => $request->input("$key.$index.name") ?? $key,
                        'path' => $stored['path'],
                        'type' => $stored['type'],
                        'size' => $stored['size'],
                        'description' => $request->input("$key.$index.description") ?? $file->getClientOriginalName(),
                        'order' => $request->input("$key.$index.order") ?? 0,
                        'is_main' => $request->input("$key.$index.is_main") ?? 0,
                    ];
                }
            } elseif (is_array($media) && count($media) == 1) {
                $media = $request->file($key)[0] ?? $request->file($key)[0];

                $stored = storeOptimizedMedia($media, $model);

                $files[] = [
                    'name' => $request->input("$key.0.name") ?? $key,
                    'path' => $stored['path'],
                    'type' => $stored['type'],
                    'size' => $stored['size'],
                    'description' => $request->input("$key.0.description") ?? $media->getClientOriginalName(),
                    'order' => $request->input("$key.0.order") ?? 0,
                    'is_main' => $request->input("$key.0.is_main") ?? 0,
                ];
            } else {
                $media = $request->file($key);

                $stored = storeOptimizedMedia($media, $model);

                $files[] = [
                    'name' => $key,
                    'path' => $stored['path'],
                    'type' => $stored['type'],
                    'size' => $stored['size'],
                    'description' => $request->input("$key.description") ?? $media->getClientOriginalName(),
                    'order' => $request->input("$key.order") ?? 0,
                    'is_main' => $request->input("$key.is_main") ?? 0,
                ];
            }
        }

        // delete the old media based on the names in the request (like poster or banner)
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            if (!empty($mediaNamesToDelete)) {
                // delete the old media that matches the names of the new media
                $model->media()->whereIn('name', $mediaNamesToDelete)->delete();
            }
        }

        // create the new media
        $model->media()->createMany($files);

        return count($files) > 0;
    });
}

/**
 * Store uploaded media to the public disk with basic image optimisation.
 *
 * - Images are auto-rotated, resized to a max of 1920x1920, and recompressed at ~80% quality.
 * - Non-image files are stored as-is using the standard Storage::putFile behaviour.
 *
 * @param UploadedFile $file
 * @param mixed        $model
 *
 * @return array{path:string,type:string,size:int}
 */
function storeOptimizedMedia(UploadedFile $file, $model): array
{
    $disk = Storage::disk('public');
    $directory = strtolower(class_basename($model)) . '/' . now()->format('Y_m_d');

    $mime = $file->getMimeType() ?? 'application/octet-stream';

    if (str_starts_with($mime, 'image/')) {
        // Ensure the target directory exists before writing (fixes "Directory does not exist" error)
        $disk->makeDirectory($directory);

        // Optimise image files using Intervention Image v3 API
        $extension = $file->getClientOriginalExtension() ?: 'jpg';

        $image = Image::read($file->getRealPath())
            ->orient()
            ->scaleDown(1920, 1920);

        $filename = uniqid('', true) . '.' . $extension;
        $path = $directory . '/' . $filename;

        // Save optimised image directly to the storage path with ~80% quality
        $fullPath = $disk->path($path);
        $image->save($fullPath, quality: 80);

        return [
            'path' => $path,
            'type' => $mime,
            'size' => $disk->size($path),
        ];
    }

    // Fallback: store non-image files as-is
    $path = $disk->putFile($directory, $file);

    return [
        'path' => $path,
        'type' => $mime,
        'size' => $file->getSize(),
    ];
}
