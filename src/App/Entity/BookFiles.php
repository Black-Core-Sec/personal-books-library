<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Filesystem\Filesystem;

abstract class BookFiles
{
    /**
     * @var string
     */
    protected $fileDirectory;
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var string
     */
    protected $fileRelativeDirectory;

    public function __construct(Filesystem $filesystem, string $fileDirectory, string $fileRelativeDirectory)
    {
        $this->filesystem = $filesystem;
        $this->fileDirectory = $fileDirectory;
        $this->fileRelativeDirectory = $fileRelativeDirectory;
    }

    public function remove(string $filename): void
    {
        $fullName = $this->fileDirectory . $filename;
        if ($this->filesystem->exists($fullName)) {
            $this->filesystem->remove($fullName);
        }
    }

    /**
     * @return string
     */
    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    /**
     * @return string
     */
    public function getFileRelativeDirectory(): string
    {
        return $this->fileRelativeDirectory;
    }
}
