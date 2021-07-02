<?php

namespace App\Tests\Unit\Service;

use App\Entity\PositiveSearchTerm;
use App\Service\GithubProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class GithubProviderTest extends TestCase
{
    /** @test */
    public function it_returns_search_result_when_calling_provider(): void
    {
        $mockResponse = new MockResponse(json_encode(['total_count' => 6]));
        $githubProvider = new GithubProvider(new MockHttpClient($mockResponse));

        $searchTerm = new PositiveSearchTerm('php');
        $searchResult = $githubProvider->handle($searchTerm);

        $this->assertEquals('php', $searchResult->getSearchTerm());
        $this->assertEquals(6, $searchResult->getCountNumber());
    }
}
