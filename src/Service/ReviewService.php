<?php

namespace App\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;

class ReviewService
{
    private const PAGE_LIMIT = 5;

    public function __construct(
        private ReviewRepository $reviewRepository,
        private RatingService $ratingService,
    ) {
    }

    public function getReviewPageByBookId(int $id, int $page): ReviewPage
    {
        $items = [];
        $paginator = $this->reviewRepository->getPageByBookId(
            $id,
            PaginationUtils::calcOffset($page, self::PAGE_LIMIT),
            self::PAGE_LIMIT
        );

        foreach ($paginator as $item) {
            $items[] = $this->map($item);
        }

        $rating = $this->ratingService->calcReviewRatingForBook($id);
        $total = $rating->getTotal();

        return (new ReviewPage())
            ->setRating($rating->getRating())
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(PaginationUtils::calcPages($total, self::PAGE_LIMIT))
            ->setItems($items);
    }

    private function map(Review $review): ReviewModel
    {
        return (new ReviewModel())
            ->setId($review->getId())
            ->setRating($review->getRating())
            ->setCreatedAt($review->getCreatedAt()->getTimestamp())
            ->setAuthor($review->getAuthor())
            ->setContent($review->getContent());
    }
}
