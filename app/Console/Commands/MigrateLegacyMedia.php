<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class MigrateLegacyMedia extends Command
{
    protected $signature = 'media:migrate-legacy {--dry-run : Show the migration plan without changing files}';

    protected $description = 'Move legacy uploaded images and videos to Laravel public-disk storage';

    private const MEDIA_EXTENSIONS = [
        'avif', 'gif', 'jpeg', 'jpg', 'm4v', 'mov', 'mp4', 'png', 'svg', 'webm', 'webp',
    ];

    private const PUBLIC_FALLBACK_FILES = [
        'blank.png',
        'logo.svg',
        'noimage.jpg',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $sources = array_filter([
            public_path('uploads'),
            base_path('uploads'),
        ], 'is_dir');

        $moved = 0;
        $duplicates = 0;
        $skipped = 0;
        $conflicts = 0;

        foreach ($sources as $sourceRoot) {
            foreach ($this->mediaFiles($sourceRoot) as $sourceFile) {
                $relativePath = $this->relativePath($sourceRoot, $sourceFile->getPathname());
                $targetPath = $this->targetPath($relativePath);

                if ($targetPath === null) {
                    $skipped++;
                    continue;
                }

                $targetAbsolutePath = Storage::disk('public')->path($targetPath);

                if (is_file($targetAbsolutePath)) {
                    if (hash_file('sha256', $sourceFile->getPathname()) === hash_file('sha256', $targetAbsolutePath)) {
                        if (!$dryRun) {
                            unlink($sourceFile->getPathname());
                        }
                        $duplicates++;
                        continue;
                    }

                    $this->error("Conflict: {$relativePath} -> {$targetPath}");
                    $conflicts++;
                    continue;
                }

                if ($dryRun) {
                    $this->line("Move: {$relativePath} -> {$targetPath}");
                    $moved++;
                    continue;
                }

                $targetDirectory = dirname($targetAbsolutePath);
                if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
                    $this->error("Could not create directory: {$targetDirectory}");
                    $conflicts++;
                    continue;
                }

                if (!copy($sourceFile->getPathname(), $targetAbsolutePath)) {
                    $this->error("Could not copy: {$relativePath}");
                    $conflicts++;
                    continue;
                }

                if (hash_file('sha256', $sourceFile->getPathname()) !== hash_file('sha256', $targetAbsolutePath)) {
                    unlink($targetAbsolutePath);
                    $this->error("Verification failed: {$relativePath}");
                    $conflicts++;
                    continue;
                }

                unlink($sourceFile->getPathname());
                $moved++;
            }
        }

        $this->newLine();
        $this->table(
            ['Result', 'Files'],
            [
                [$dryRun ? 'Planned moves' : 'Moved', $moved],
                [$dryRun ? 'Existing identical files' : 'Duplicate sources removed', $duplicates],
                ['Public fallback files kept', $skipped],
                ['Conflicts', $conflicts],
            ]
        );

        if ($dryRun) {
            $this->warn('Dry run only; no files were changed.');
        }

        return $conflicts === 0 ? self::SUCCESS : self::FAILURE;
    }

    /** @return iterable<SplFileInfo> */
    private function mediaFiles(string $sourceRoot): iterable
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceRoot, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            if (in_array(strtolower($file->getExtension()), self::MEDIA_EXTENSIONS, true)) {
                yield $file;
            }
        }
    }

    private function relativePath(string $sourceRoot, string $path): string
    {
        return str_replace('\\', '/', substr($path, strlen(rtrim($sourceRoot, DIRECTORY_SEPARATOR)) + 1));
    }

    private function targetPath(string $relativePath): ?string
    {
        if (!str_contains($relativePath, '/')) {
            if (in_array(strtolower($relativePath), self::PUBLIC_FALLBACK_FILES, true)) {
                return null;
            }

            return 'settings/' . $relativePath;
        }

        return $relativePath;
    }
}
