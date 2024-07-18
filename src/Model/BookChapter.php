<?php

namespace App\Model;

class BookChapter
{
    /**
     * @param BookChapter[] $items
     */
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private array $items = [],
    ) {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return BookChapter[] $items
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(BookChapter $chapter): void
    {
        $this->items[] = $chapter;
    }
}
