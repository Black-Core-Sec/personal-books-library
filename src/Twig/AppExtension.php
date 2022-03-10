<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\TwigFunction;
use App\Twig\BookExtensions;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('bookCoverImg', [BookExtensions::class, 'renderBookCoverImage']),
            new TwigFunction('bookFileLink', [BookExtensions::class, 'renderBookFileLink']),
        ];
    }
}
