<?php
declare(strict_types=1);

namespace Api\DataTransformer;

use App\Entity\Book;
use Api\Dto\BookOutput;

final class BookOutputDataTransformer extends DtoTransformer
{
    /**
     * @param Book $data
     * @return BookOutput
     */
    public function transform($data)
    {
        $host =  $this->request->getHttpHost();

        $output = new BookOutput();
        $output->name = $data->getName();
        $output->author = $data->getAuthor();
        $output->cover = $data->getCover() ? $host . $this->bookCover->getFileRelativeDirectory() . $data->getCover() : null;
        $output->file = $data->getFile() ? $host . $this->bookFile->getFileRelativeDirectory() . $data->getFile() : null;
        $output->last_read_datetime = $data->getLastReadDatetime();
        $output->is_downloadable = $data->getIsDownloadable();

        return $output;
    }
}

