<?php

namespace App\Tests\Service;

use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RatingServiceTest extends AbstractTestCase
{
    private $reviewRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }

    #[DataProvider('provider')]
    public function testCalcReviewRatingForBook(
        int $repositoryRatingSum,
        int $total,
        float $expectedRating
    ): void {
        $this->reviewRepository->expects($this->once())
            ->method("getBookTotalRatingSum")
            ->with(1)
            ->willReturn($repositoryRatingSum);

        $actual = (new RatingService($this->reviewRepository))
            ->calcReviewRatingForBook(1, $total);

        $this->assertEquals($expectedRating, $actual);
    }

    public function testCalcReviewRatingForBookZeroTotal(): void {
        $this->reviewRepository->expects($this->never())
            ->method("getBookTotalRatingSum");

        $actual = (new RatingService($this->reviewRepository))
            ->calcReviewRatingForBook(1, 0);

        $this->assertEquals(0, $actual);
    }

    public static function provider(): array
    {
        return [
            // sum, total, rating
            [25, 20, 1.25],
            [0, 5, 0]
        ];
    }
}
