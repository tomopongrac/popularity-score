<?php

namespace App\Tests\Unit\Service;

use App\Entity\SearchResult;
use App\Service\TermPopularityCalculator;
use PHPUnit\Framework\TestCase;

class TermPopularityCalculatorTest extends TestCase
{
    /** @test */
    public function it_calculates_term_popularity_and_returs_search_term_popularity_score()
    {
        $termPopularityCalculator = new TermPopularityCalculator();

        $positiveSearchResult = $this->createMock(SearchResult::class);
        $positiveSearchResult->expects($this->any())
            ->method('getCountNumber')
            ->willReturn(20);

        $negativeSearchResult = $this->createMock(SearchResult::class);
        $negativeSearchResult->expects($this->any())
            ->method('getCountNumber')
            ->willReturn(30);

        $popularityScore = $termPopularityCalculator->calculateTermPopularity($positiveSearchResult, $negativeSearchResult);

        $this->assertEquals(4, $popularityScore->getScore());
    }

    /** @test */
    public function it_returns_zero_if_positive_and_negative_search_result_is_zero()
    {
        $termPopularityCalculator = new TermPopularityCalculator();

        $positiveSearchResult = $this->createMock(SearchResult::class);
        $positiveSearchResult->expects($this->any())
            ->method('getCountNumber')
            ->willReturn(0);

        $negativeSearchResult = $this->createMock(SearchResult::class);
        $negativeSearchResult->expects($this->any())
            ->method('getCountNumber')
            ->willReturn(0);

        $popularityScore = $termPopularityCalculator->calculateTermPopularity($positiveSearchResult, $negativeSearchResult);

        $this->assertEquals(0, $popularityScore->getScore());
    }
}
