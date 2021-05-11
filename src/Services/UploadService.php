<?php

namespace App\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function uploadFile(UploadedFile $imageFile, $where) : string
    {
        $originalImageFile = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeImageFile = $this->slugger->slug($originalImageFile);
        $newImageFile = $safeImageFile.'-'.uniqid().'.'.$imageFile->guessExtension();

        try {
            $imageFile->move(
                $where,
                $newImageFile
            );
        } catch (FileException $e) {
            throw new Exception("the file did not uploaded, because " . $e->getMessage());
        }

        return $newImageFile;
    }
}
