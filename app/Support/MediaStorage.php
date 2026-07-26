<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorage
{
    /**
     * Store only a generated filename in the database; the directory remains
     * an application concern and is never accepted from the browser.
     */
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $extension = strtolower($file->extension() ?: $file->getClientOriginalExtension());
        $fileName = Str::uuid() . ($extension ? '.' . $extension : '');

        $stored = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        if ($stored === false) {
            throw new \RuntimeException('Unable to store uploaded media.');
        }

        return $fileName;
    }

    public static function replace(UploadedFile $file, string $directory, ?string $oldPath = null, ?string $legacyDirectory = null): string
    {
        $fileName = self::store($file, $directory);
        self::delete($oldPath, $directory, $legacyDirectory);

        return $fileName;
    }

    public static function delete(?string $path, string $directory = '', ?string $legacyDirectory = null): void
    {
        if (!$path || str_starts_with($path, 'feb/')) {
            return;
        }

        $storagePath = self::storagePath($path, $directory);
        Storage::disk('public')->delete($storagePath);

        $legacyPath = public_path('uploads/' . self::storagePath($path, $legacyDirectory ?? $directory));
        if (is_file($legacyPath)) {
            @unlink($legacyPath);
        }

        $oldRootPath = base_path('uploads/' . self::storagePath($path, $legacyDirectory ?? $directory));
        if (is_file($oldRootPath)) {
            @unlink($oldRootPath);
        }
    }

    public static function url(?string $path, string $directory = '', ?string $legacyDirectory = null): string
    {
        if (!$path) {
            return asset('uploads/blank.png');
        }

        if (str_starts_with($path, 'feb/')) {
            return asset($path);
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $storagePath = self::storagePath($path, $directory);
        if (Storage::disk('public')->exists($storagePath)) {
            return asset('storage/' . $storagePath);
        }

        return asset('uploads/' . self::storagePath($path, $legacyDirectory ?? $directory));
    }

    public static function exists(?string $path, string $directory = '', ?string $legacyDirectory = null): bool
    {
        if (!$path) {
            return false;
        }

        if (str_starts_with($path, 'feb/') || filter_var($path, FILTER_VALIDATE_URL)) {
            return true;
        }

        $storagePath = self::storagePath($path, $directory);
        $legacyPath = self::storagePath($path, $legacyDirectory ?? $directory);

        return Storage::disk('public')->exists($storagePath)
            || is_file(public_path('uploads/' . $legacyPath))
            || is_file(base_path('uploads/' . $legacyPath));
    }

    private static function storagePath(string $path, string $directory): string
    {
        $path = ltrim($path, '/');
        $directory = trim($directory, '/');

        if (str_contains($path, '..') || str_contains($directory, '..')) {
            throw new \InvalidArgumentException('Invalid media path.');
        }

        if ($directory === '' || str_starts_with($path, $directory . '/')) {
            return $path;
        }

        return $directory . '/' . $path;
    }
}
