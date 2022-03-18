<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *    name="book",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="name_author_unique", columns={"name", "author"})
 *    }
 * )
 *
 * @UniqueEntity(
 *     fields={"name", "author"},
 *     message="Duplicated name-author combination"
 * )
 *
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ApiResource()
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $cover;

    /**
     * @ORM\Column(type="text")
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_read_datetime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_downloadable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getLastReadDatetime(): ?\DateTimeInterface
    {
        return $this->last_read_datetime;
    }

    public function setLastReadDatetime(?\DateTimeInterface $last_read_datetime): self
    {
        $this->last_read_datetime = $last_read_datetime;

        return $this;
    }

    public function getIsDownloadable(): ?bool
    {
        return $this->is_downloadable;
    }

    public function setIsDownloadable(bool $is_downloadable): self
    {
        $this->is_downloadable = $is_downloadable;

        return $this;
    }
}
