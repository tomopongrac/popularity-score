<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NegativeSearchTerm;
use App\Entity\PositiveSearchTerm;
use App\Entity\SearchTermPopularityScore;

class SearchPopularityScore
{
    private $provider;
    private $termPopularityCalculator;

    public function __construct(Provider $provider, TermPopularityCalculator $termPopularityCalculator)
    {
        $this->provider = $provider;
        $this->termPopularityCalculator = $termPopularityCalculator;
    }

    public function search(string $searchTerm): SearchTermPopularityScore
    {
        $positiveSearchTerm = new PositiveSearchTerm($searchTerm);
        $negativeSearchTerm = new NegativeSearchTerm($searchTerm);

        $positiveSearchResult = $this->provider->handle($positiveSearchTerm);
        $negativeSearchResult = $this->provider->handle($negativeSearchTerm);

        $score = $this->termPopularityCalculator->calculateTermPopularity($positiveSearchResult, $negativeSearchResult);

        $searchTermPopularityScore = new SearchTermPopularityScore();
        $searchTermPopularityScore->setTerm($searchTerm);
        $searchTermPopularityScore->setScore($score->getScore());

        return $searchTermPopularityScore;
    }
}