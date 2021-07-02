<?php

namespace App\Tests\Unit\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTermPopularityScore;
use App\Service\GithubProvider;
use App\Service\SearchPopularityScore;
use App\Service\TermPopularityCalculator;
use PHPUnit\Framework\TestCase;

class SearchPopularityScoreTest extends TestCase
{
    /** @test */
    public function it_returns_search_term_popularity_score(): void
    {
        $searchTerm = 'php';

        $provider = $this->createMock(GithubProvider::class);
        $provider->expects($this->any())
            ->method('handle')
            ->will(
                $this->onConsecutiveCalls(
                new SearchResult($searchTerm, 20),
                new SearchResult($searchTerm, 30)
            ));
        $termPopularityCalculator = new TermPopularityCalculator();

        $searchPopularityScore = new SearchPopularityScore($provider, $termPopularityCalculator);

        $searchTermPopularityScore = $searchPopularityScore->search($searchTerm);

        $this->assertInstanceOf(SearchTermPopularityScore::class, $searchTermPopularityScore);
        $this->assertEquals($searchTerm, $searchTermPopularityScore->getTerm());
        $this->assertEquals(4, $searchTermPopularityScore->getScore());
    }
}
