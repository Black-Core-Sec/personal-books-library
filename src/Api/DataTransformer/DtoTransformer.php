<?php
declare(strict_types=1);

namespace Api\DataTransformer;

use App\Entity\BookFile;
use App\Entity\BookCover;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class DtoTransformer
{
    protected $bookFile;
    protected $bookCover;
    protected $request;

    abstract function transform($data);

    public function __construct(BookFile $bookFile, BookCover $bookCover, RequestStack $request)
    {
        $this->bookFile = $bookFile;
        $this->bookCover = $bookCover;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * @param array $data
     * @return array
     */
    public function transformArray(array $data): array
    {
        $dto = [];
        foreach ($data as $book) {
            $dto[] = $this->transform($book);
        }

        return $dto;
    }
}
