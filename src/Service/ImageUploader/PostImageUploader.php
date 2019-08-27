<?php

namespace App\Service\ImageUploader;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PostImageUploader
 * @package App\Service\PhotoUploader
 */
class PostImageUploader implements ImageUploaderInterface
{
    private $defaultDirectory;

    /**
     * @var bool
     */
    private $success = false;
    /**
     * @var
     */
    private $fileName;

    /**
     * @var
     */
    private $errorMessage;

    public function __construct(string $targetDirectory)
    {
        $this->defaultDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @param string $directory
     * @throws Exception
     */
    public function upload(UploadedFile $file, string $directory = ""): void
    {
        $this->success = false;

        if (!$file) {
            $this->errorMessage = "File has not been uploaded.";
            return;
        }

        $dateNow = new \DateTime('now');
        $fileDate = $dateNow->format('d_m_Y_H_i_s');
        $this->fileName = $fileDate.'-'.uniqid().'.'.$file->guessExtension();

        $directory = !empty($directory) ? $directory : $this->defaultDirectory;

        try {
            $file->move(
                $directory,
                $this->fileName
            );
        } catch (FileException $e) {
            $this->errorMessage = "Something goes wrong during upload.";
            return;
        }

        $this->success = true;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}