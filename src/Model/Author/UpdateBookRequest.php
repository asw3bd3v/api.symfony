<?php

declare(strict_types=1);

namespace App\Model\Author;

class UpdateBookRequest
{
    private ?string $title = null;

    private ?array $authors = [];

    private ?string $isbn = null;

    private ?string $description = null;

    /**
     * @var BookFormatOptions[]|null
     */
    private ?array $formats = [];

    /**
     * @var int[]|null
     */
    private ?array $categories = [];

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function setAuthors(?array $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFormats(): ?array
    {
        return $this->formats;
    }

    public function setFormats(?array $formats): static
    {
        $this->formats = $formats;

        return $this;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }
}
