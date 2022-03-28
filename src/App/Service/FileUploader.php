<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sunrise\Slugger\Slugger;

class FileUploader
{
    private $directories;
    private $slugger;

    public function __construct($directories, Slugger $slugger)
    {
        $this->directories = $directories;
        $this->slugger = $slugger;
    }

    /**
     * @param UploadedFile $file
     * @param string $target
     * @return string
     * @throws \Exception
     * @throws \Sunrise\Slugger\Exception\ExceptionInterface
     */
    private function upload(UploadedFile $file, string $target): string
    {
        if (!key_exists($target, $this->directories)) {
            throw new \Exception('File directory is not set.');
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slugify($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->directories[$target], $fileName);

        return $fileName;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadBook(UploadedFile $file): string
    {
        return $this->upload($file, 'bookDirectory');
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadCover(UploadedFile $file): string
    {
        return $this->upload($file, 'coverDirectory');
    }
}
