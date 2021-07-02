<?php

namespace App\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTerm;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface Provider
{
    public function __construct(HttpClientInterface $client);

    public function handle(SearchTerm $searchTerm): SearchResult;
}