<?php
declare(strict_types=1);

namespace Api\Controller;

use Api\Dto\ApiResponse;
use Api\Dto\BookInput;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Api\DataTransformer\BookOutputDataTransformer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Http\Requests\BookRequest;

/**
 * @Route("/api/v2/books")
 */
class BookController extends AbstractController
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @Route("/", name="api_book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository,
                          BookOutputDataTransformer $bookOutputDataTransformer): ApiResponse
    {
        $books = $bookRepository->getAllSortingByLastReadDatetime();
        $booksDtoArray = $bookOutputDataTransformer->transformArray($books);
        $serializedBooks = $this->serializer->serialize($booksDtoArray, 'json');
        return new ApiResponse($serializedBooks, ApiResponse::HTTP_OK, [], true);
    }

    /**
     * @Route("/add", name="api_book_add", methods={"POST"})
     */
    public function add(Request $request, BookRepository $bookRepository, ValidatorInterface $validator): ApiResponse
    {
        try {
            $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json');
            if ($book instanceof Book) {
                /**
                 * @TODO Need to change logic
                 * Cover and File fields read only for API
                 */
                $book->setCover(null);
                $book->setFile(null);
                // Validation
                $validationErrors = $validator->validate($book);
                if (count($validationErrors) > 0) {
                    $errorsString = (string) $validationErrors;
                    return new ApiResponse($this->serializer->serialize($errorsString, 'json'), ApiResponse::HTTP_UNPROCESSABLE_ENTITY);
                }
                $bookRepository->add($book);
                return new ApiResponse('New book added.', ApiResponse::HTTP_OK);

            } else {
                return new ApiResponse('Error on adding book.', ApiResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch(\Throwable $exception) {
            return new ApiResponse('Error on adding book.', ApiResponse::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @Route("/{id}/edit", name="api_book_edit", methods={"POST"})
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository, ValidatorInterface $validator): ApiResponse
    {
        try {
            $bookDto = $this->serializer->deserialize($request->getContent(), BookInput::class, 'json');
            !$bookDto->name ?: $book->setName($bookDto->name);
            !$bookDto->author ?: $book->setAuthor($bookDto->author);
            !$bookDto->last_read_datetime ?: $book->setLastReadDatetime($bookDto->last_read_datetime);
            !$bookDto->is_downloadable ?: $book->setIsDownloadable($bookDto->is_downloadable);

            $validationErrors = $validator->validate($book);
            if (count($validationErrors) > 0) {
                $errorsString = (string) $validationErrors;
                return new ApiResponse($this->serializer->serialize($errorsString, 'json'), ApiResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $bookRepository->add($book);
            return new ApiResponse('Book updated.', ApiResponse::HTTP_OK);
        } catch (\Throwable $exception) {
            return new ApiResponse('Error on updating book.', ApiResponse::HTTP_NOT_MODIFIED);
        }
    }
}
