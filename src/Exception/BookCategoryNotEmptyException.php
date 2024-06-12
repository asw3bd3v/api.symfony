<?php

namespace App\Exception;

class BookCategoryNotEmptyException extends \RuntimeException
{
    public function __construct(int $count)
    {
        parent::__construct(sprintf('Book category has %d books', $count));
    }
}
