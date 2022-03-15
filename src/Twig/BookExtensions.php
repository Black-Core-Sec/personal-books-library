<?php
declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

/**
 * Extension for render books data
 */
class BookExtensions implements RuntimeExtensionInterface
{
    public function renderBookCoverImage(string $name, string $alt_text = ''): \Twig\Markup
    {
        return new \Twig\Markup("<img src=\"/assets/uploads/book/covers/{$name}\" alt=\"$alt_text\" width='80px'>", 'UTF-8' );
    }

    public function renderBookFileLink(string $name, string $text = 'download'): \Twig\Markup
    {
        return new \Twig\Markup("<a href=\"/assets/uploads/book/files/{$name}\">{$text}</a>", 'UTF-8' );
    }
}
