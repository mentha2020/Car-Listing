<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{
    private const MAX_WIDTH = 1200;
    private const THUMB_WIDTH = 400;
    private const THUMB_HEIGHT = 300;
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    public function uploadCarImages(Car $car, array $files): void
    {
        $sortOrder = $car->images()->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {
            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }

            if ($file->getSize() > self::MAX_FILE_SIZE) {
                continue;
            }

            $this->storeImage($car, $file, $sortOrder + $index, $index === 0 && $car->images()->count() === 0);
        }
    }

    public function storeImage(Car $car, UploadedFile $file, int $sortOrder = 0, bool $isPrimary = false): CarImage
    {
        $filename = uniqid('car_') . '.' . $file->getClientOriginalExtension();
        $directory = "cars/{$car->user_id}/{$car->id}";

        // Store original
        $path = $file->storeAs($directory, $filename, 'public');

        // Create thumbnail
        $thumbPath = $directory . '/thumb_' . $filename;
        $this->createThumbnail($file, $thumbPath);

        return $car->images()->create([
            'path' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
        ]);
    }

    public function createThumbnail(UploadedFile $file, string $path): void
    {
        $image = Image::make($file);
        $image->resize(self::THUMB_WIDTH, self::THUMB_HEIGHT, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save(Storage::disk('public')->path($path));
    }

    public function deleteCarImages(Car $car): void
    {
        $directory = "cars/{$car->user_id}/{$car->id}";
        Storage::disk('public')->deleteDirectory($directory);
    }

    public function deleteImage(CarImage $image): void
    {
        Storage::disk('public')->delete($image->path);

        $thumbPath = dirname($image->path) . '/thumb_' . basename($image->path);
        Storage::disk('public')->delete($thumbPath);

        $image->delete();
    }

    public function setPrimary(Car $car, CarImage $image): void
    {
        $car->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }
}
