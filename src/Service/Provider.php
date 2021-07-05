<?php

namespace App\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTerm;

interface Provider
{
    public function handle(SearchTerm $searchTerm): SearchResult;
}