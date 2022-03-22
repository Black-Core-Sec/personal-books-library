<?php

namespace App\Entity;

use JMS\Serializer\Annotation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
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
 *
 * @Annotation\ExclusionPolicy("none")
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
     * @Assert\NotBlank
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Annotation\Expose()
     * @Annotation\Type("string")
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cover;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Annotation\Expose()
     * @Annotation\Type("DateTime<'d-m-Y\Th:i:s'>")
     */
    private $last_read_datetime;

    /**
     * @ORM\Column(type="boolean")
     * @Annotation\Expose()
     * @Annotation\Type("boolean")
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

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
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
