<?php

namespace App\Model\Author;

use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Constraints\Positive;

#[AtLeastOneRequired('nextId', 'previousId')]
class UpdateChapterSortRequest
{
    #[Positive]
    private int $id;

    #[Positive]
    private ?int $nextId;

    #[Positive]
    private ?int $previousId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

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
