<?php

namespace App\Service;

use App\Entity\SearchResult;
use App\Entity\SearchTerm;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubProvider implements Provider
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle(SearchTerm $searchTerm): SearchResult
    {
         $response = $this->client->request(
            'GET',
            'https://api.github.com/search/issues?q='.$searchTerm->getSearchPhrase()
        );

        return new SearchResult($searchTerm->getSearchTerm(), $this->findTotalCount($response));
    }

    private function findTotalCount(ResponseInterface $response)
    {
        $parsedResponse = $response->toArray();

        return $parsedResponse['total_count'];
    }
}