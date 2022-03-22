<?php
declare(strict_types=1);

namespace Api\DataTransformer;

use \stdClass;

abstract class DtoTransformer
{
    abstract function transform($data);

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
