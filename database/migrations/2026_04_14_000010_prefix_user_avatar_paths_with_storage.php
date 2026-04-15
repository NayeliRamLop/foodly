<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->select('id', 'avatar')
            ->whereNotNull('avatar')
            ->orderBy('id')
            ->get()
            ->each(function ($user) {
                $normalized = $this->prefixStorage($user->avatar);

                if ($normalized !== $user->avatar) {
                    DB::table('users')->where('id', $user->id)->update([
                        'avatar' => $normalized,
                    ]);
                }
            });
    }

    public function down(): void
    {
        DB::table('users')
            ->select('id', 'avatar')
            ->whereNotNull('avatar')
            ->orderBy('id')
            ->get()
            ->each(function ($user) {
                $avatar = ltrim($user->avatar, '/');

                if (str_starts_with($avatar, 'storage/')) {
                    DB::table('users')->where('id', $user->id)->update([
                        'avatar' => substr($avatar, 8),
                    ]);
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
