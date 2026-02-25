<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OptimizeMediaImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:optimize-images {--chunk=100 : Number of media records to process per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize existing media images stored in the public disk';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk') ?: 100;

        $this->info('Starting media image optimisation...');

        Media::where('type', 'like', 'image/%')
            ->orderBy('id')
            ->chunkById($chunkSize, function ($medias) {
                /** @var \App\Models\Media $media */
                foreach ($medias as $media) {
                    $path = $media->path;

                    if (! $path || ! Storage::disk('public')->exists($path)) {
                        $this->warn("Skipping media {$media->id}: file not found ({$path})");
                        continue;
                    }

                    try {
                        $fullPath = Storage::disk('public')->path($path);

                        // Skip extremely large files to avoid exhausting memory (e.g. > 10MB)
                        $fileSize = filesize($fullPath);
                        if ($fileSize !== false && $fileSize > 10 * 1024 * 1024) {
                            $this->warn("Skipping media {$media->id}: file too large (" . round($fileSize / 1024 / 1024, 2) . " MB)");
                            continue;
                        }

                        $image = Image::read($fullPath)
                            ->orient()
                            ->scaleDown(1920, 1920);

                        // Save back to the same path with ~80% quality
                        $image->save($fullPath, quality: 80);

                        $this->info("Optimized: {$path}");
                    } catch (\Throwable $e) {
                        $this->error("Failed to optimize media {$media->id}: {$e->getMessage()}");
                    }
                }
            });

        $this->info('Media image optimisation completed.');

        return Command::SUCCESS;
    }
}

