<?php

namespace App\Service;


class Rating
{
    public function __construct(
        private int $total,
        private float $rating,
    ) {
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getRating()
    {
        return $this->rating;
    }
}
