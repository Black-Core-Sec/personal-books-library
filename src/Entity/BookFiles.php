<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Filesystem\Filesystem;

abstract class BookFiles
{
    /**
     * @var string
     */
    private $fileDirectory;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem, string $fileDirectory)
    {
        $this->filesystem = $filesystem;
        $this->fileDirectory = $fileDirectory;
    }

    public function remove(string $filename)
    {
        $fullName = $this->fileDirectory . $filename;
        if ($this->filesystem->exists($fullName)) {
            $this->filesystem->remove($fullName);
        }
    }
}
