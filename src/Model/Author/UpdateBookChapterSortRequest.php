<?php

namespace App\Model\Author;

use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Constraints\Positive;

#[AtLeastOneRequired(['nextId', 'previousId'])]
class UpdateBookChapterSortRequest
{
    #[Positive]
    private ?int $nextId = null;

    #[Positive]
    private ?int $previousId = null;

    public function getNextId(): ?int
    {
        return $this->nextId;
    }

    public function setNextId(?int $nextId): static
    {
        $this->nextId = $nextId;

        return $this;
    }

    public function getPreviousId(): ?int
    {
        return $this->previousId;
    }

    public function setPreviousId(?int $previousId): static
    {
        $this->previousId = $previousId;

        return $this;
    }
}
