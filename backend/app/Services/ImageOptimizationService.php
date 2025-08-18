<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizationService
{
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function optimizeAndConvert($imagePath, $outputPath = null, $quality = 80)
    {
        if (!$outputPath) {
            $outputPath = $this->getWebPPath($imagePath);
        }

        $image = $this->manager->read($imagePath);
        
        // Resize if too large
        if ($image->width() > 1200) {
            $image->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Save as WebP
        $image->toWebp($quality)->save($outputPath);
        
        return $outputPath;
    }

    private function getWebPPath($originalPath)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    }

    public function generateResponsiveImages($imagePath, $sizes = [400, 800, 1200])
    {
        $results = [];
        $image = $this->manager->read($imagePath);
        
        foreach ($sizes as $size) {
            $resized = clone $image;
            $resized->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            $outputPath = $this->getResponsivePath($imagePath, $size);
            $resized->toWebp(80)->save($outputPath);
            $results[$size] = $outputPath;
        }
        
        return $results;
    }

    private function getResponsivePath($originalPath, $size)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $size . 'w.webp';
    }
}