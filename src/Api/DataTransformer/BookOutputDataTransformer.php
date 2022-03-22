<?php
declare(strict_types=1);

namespace Api\DataTransformer;

use App\Entity\Book;
use Api\Dto\BookOutput;
use JMS\Serializer\Annotation;
use Symfony\Component\DependencyInjection\Container;

final class BookOutputDataTransformer extends DtoTransformer
{
    /**
     * @param Book $data
     * @return BookOutput
     */
    public function transform($data)
    {
        $output = new BookOutput();
        $output->name = $data->getName();
        $output->author = $data->getAuthor();
        $output->cover = $data->getCover();
        $output->file = $data->getFile();
        $output->last_read_datetime = $data->getLastReadDatetime();
        $output->is_downloadable = $data->getIsDownloadable();

        return $output;
    }
}

