<?php

namespace App\Model;

class BookChapterTreeResponse
{
    /**
     * @param BookChapter[] $items
     */
    private function __construct(
        private array $items = [],
    ) {
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
