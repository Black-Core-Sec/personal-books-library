<?php
declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Extension for render books data
 */
class BookExtensions implements RuntimeExtensionInterface
{
    private $filePath, $coverPath;

    public function __construct(ContainerInterface $container)
    {
        $this->filePath = $container->getParameter('bookRelativeFilesDirectory');
        $this->coverPath = $container->getParameter('bookRelativeCoversDirectory');
    }

    public function renderBookFileLink(string $name, string $text = 'download'): \Twig\Markup
    {
        return new \Twig\Markup("<a href=" . $this->filePath . $name . ">{$text}</a>", 'UTF-8' );
    }

    public function renderBookCoverImage(?string $name, string $alt_text = ''): \Twig\Markup
    {
        return new \Twig\Markup("<img src=" . $this->coverPath . $name . " alt=\"$alt_text\" width='80px'>", 'UTF-8' );
    }
}
