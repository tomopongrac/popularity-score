<?php

namespace App\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTerm;

class TwitterProvider implements Provider
{
    public function handle(SearchTerm $searchTerm): SearchResult
    {
        return new SearchResult($searchTerm->getSearchTerm(), 10);
    }
}