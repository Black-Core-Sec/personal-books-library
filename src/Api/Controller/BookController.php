<?php
declare(strict_types=1);

namespace Api\Controller;

use App\Entity\Book;
use Api\Controller\ApiController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/v2/books")
 */
class BookController extends ApiController
{
    /**
     * @Route("/", name="api_book_index", methods={"GET"})
     */
    public function index()
    {
        return $this->response('');
    }
}
