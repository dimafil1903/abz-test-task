<?php

namespace App\Http\Services;

use App\Exceptions\ImageException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use function Tinify\setKey;
use function Tinify\fromFile;

class ImageService
{
    /**
     * @throws ImageException
     */
    public function __construct()
    {
        if (!config('tiny-image.api_key')) {
            throw new ImageException('TinyPNG API key is missing.');
        }
        setKey(config('tiny-image.api_key'));
    }

    /**
     * @throws \Exception
     */
    public function processImage($image): string
    {
        $imageDimensions = getimagesize($image);
        if ($imageDimensions[0] < 70 || $imageDimensions[1] < 70) {
            throw new ImageException('Minimum size of photo is 70x70px.');
        }

        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $img = Image::read($image->getRealPath());
        $img->scale(70, 70);

        $img->save(Storage::disk('public')->path('images/' . $imageName), 75, 'jpg');

        $source = fromFile(Storage::disk('public')->path('images/' . $imageName));
        $source->toFile(Storage::disk('public')->path('images/' . $imageName));

        return url('images/' . $imageName);
    }
}
