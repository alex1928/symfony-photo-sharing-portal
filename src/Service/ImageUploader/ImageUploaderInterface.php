<?php

namespace App\Service\ImageUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageUploaderInterface
{
    public function upload(UploadedFile $file, string $directory = ""): void;
    public function getFileName(): string;
    public function isSuccessful(): bool;
    public function getErrorMessage(): string;
}

