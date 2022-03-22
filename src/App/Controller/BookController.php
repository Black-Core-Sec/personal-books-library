<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\FileUploader;
use App\Repository\BookRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="app_book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository, CacheInterface $booksCache): Response
    {
        $books = $booksCache->get($bookRepository::LIST_CACHE_KEY, function (ItemInterface $item) use ($bookRepository) {
            $item->expiresAfter(3600);
            return $bookRepository->getAllSortingByLastReadDatetime();
        });

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/new", name="app_book_new", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function new(Request $request, BookRepository $bookRepository, FileUploader $fileUploader): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $bookFile = $form->get('file')->getData();
                if ($bookFile) {
                    $bookFilename = $fileUploader->uploadBook($bookFile);
                    $book->setFile($bookFilename);
                }

                $coverFile = $form->get('cover')->getData();
                if ($coverFile) {
                    $coverFilename = $fileUploader->uploadCover($coverFile);
                    $book->setCover($coverFilename);
                }
            } catch (\Exception $e) {
                 dd($e);
            }

            $bookRepository->add($book);

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_book_edit", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $bookFile = $form->get('file')->getData();
                if ($bookFile) {
                    $bookFilename = $fileUploader->uploadBook($bookFile);
                    $book->setFile($bookFilename);
                }

                $coverFile = $form->get('cover')->getData();
                if ($coverFile) {
                    $coverFilename = $fileUploader->uploadCover($coverFile);
                    $book->setCover($coverFilename);
                }
            } catch (\Exception $e) {
                dd($e);
            }

            $bookRepository->add($book);
            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_book_delete", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $bookRepository->remove($book);
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/file", name="app_book_file_delete", methods={"DELETE"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function removeFile(Request $request, Book $book, BookRepository $bookRepository)
    {
        $book->setFile(null);
        $bookRepository->add($book);
        return $this->json('Success');
    }

    /**
     * @Route("/{id}/cover", name="app_book_cover_delete", methods={"DELETE"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function removeCover(Request $request, Book $book, BookRepository $bookRepository)
    {
        $book->setCover(null);
        $bookRepository->add($book);
        return $this->json('Success');
    }
}
