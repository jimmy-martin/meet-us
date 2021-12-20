<?php

namespace App\Service;

class ApiImageUploader
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function uploadBase64Image(string $originalName, string $imageValue, string $subDirectory)
    {
        $base64Content = UploadedBase64File::extractBase64String($imageValue);
        $imageFile = new UploadedBase64File($base64Content, $originalName);

        return $this->fileUploader->upload($imageFile, $subDirectory);
    }
}