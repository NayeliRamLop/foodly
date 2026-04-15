<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('recipes')
            ->select('id', 'image', 'video')
            ->orderBy('id')
            ->get()
            ->each(function ($recipe) {
                $updates = [];

                if (!empty($recipe->image)) {
                    $normalizedImage = $this->prefixStorage($recipe->image);
                    if ($normalizedImage !== $recipe->image) {
                        $updates['image'] = $normalizedImage;
                    }
                }

                if (!empty($recipe->video)) {
                    $normalizedVideo = $this->prefixStorage($recipe->video);
                    if ($normalizedVideo !== $recipe->video) {
                        $updates['video'] = $normalizedVideo;
                    }
                }

                if ($updates !== []) {
                    DB::table('recipes')->where('id', $recipe->id)->update($updates);
                }
            });
    }

    public function down(): void
    {
        DB::table('recipes')
            ->select('id', 'image', 'video')
            ->orderBy('id')
            ->get()
            ->each(function ($recipe) {
                $updates = [];

                if (!empty($recipe->image)) {
                    $image = ltrim($recipe->image, '/');
                    if (str_starts_with($image, 'storage/')) {
                        $updates['image'] = substr($image, 8);
                    }
                }

                if (!empty($recipe->video)) {
                    $video = ltrim($recipe->video, '/');
                    if (str_starts_with($video, 'storage/')) {
                        $updates['video'] = substr($video, 8);
                    }
                }

                if ($updates !== []) {
                    DB::table('recipes')->where('id', $recipe->id)->update($updates);
                }
            });
    }

    private function prefixStorage(string $path): string
    {
        $normalized = ltrim(trim($path), '/');

        if ($normalized === '' || preg_match('#^(https?:)?//#i', $normalized)) {
            return $path;
        }

        return str_starts_with($normalized, 'storage/')
            ? $normalized
            : 'storage/'.$normalized;
    }
};
