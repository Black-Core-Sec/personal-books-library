<?php
declare(strict_types=1);

namespace Api\Controller;

use Api\Dto\BookInput;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Api\DataTransformer\BookOutputDataTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                          BookOutputDataTransformer $bookOutputDataTransformer): JsonResponse
    {
        $books = $bookRepository->getAllSortingByLastReadDatetime();
        $booksDtoArray = $bookOutputDataTransformer->transformArray($books);
        $serializedBooks = $this->serializer->serialize($booksDtoArray, 'json');
        return new JsonResponse($serializedBooks, 200, [], true);
    }

    /**
     * @Route("/add", name="api_book_add", methods={"POST"})
     */
    public function add(Request $request, BookRepository $bookRepository, ValidatorInterface $validator)
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
                    return new JsonResponse($this->serializer->serialize($errorsString, 'json'), 500);
                }
                $bookRepository->add($book);

                return new JsonResponse('New book added.', 200);
            } else {
                return new JsonResponse('Error on adding book.', 500);
            }
        } catch(\Exception $exception) {
            return new JsonResponse('Error on adding book.', 500);
        }
    }


    /**
     * @Route("/{id}/edit", name="api_book_edit", methods={"POST"})
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository, ValidatorInterface $validator)
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
                return new JsonResponse($this->serializer->serialize($errorsString, 'json'), 500);
            }

            $bookRepository->add($book);
            return new JsonResponse('Book updated.', 200);
        } catch (\Exception $exception) {
            return new JsonResponse('Error on updating book.', 500);
        }
    }
}
