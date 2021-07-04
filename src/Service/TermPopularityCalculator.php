<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTermPopularityScore;

class TermPopularityCalculator
{
    public function calculateTermPopularity(SearchResult $positiveSearchResult, SearchResult $negativeSearchResult): SearchTermPopularityScore
    {
        if ($positiveSearchResult->getCountNumber() === 0 && $negativeSearchResult->getCountNumber() === 0) {
            $calculatedScore = 0;
        } else {
            $calculatedScore = round($positiveSearchResult->getCountNumber() / ($positiveSearchResult->getCountNumber() + $negativeSearchResult->getCountNumber()) * 10, 2);
        }

        $searchTermPopularityScore = new SearchTermPopularityScore();
        $searchTermPopularityScore->setTerm($positiveSearchResult->getSearchTerm());
        $searchTermPopularityScore->setScore($calculatedScore);

        return $searchTermPopularityScore;
    }
}