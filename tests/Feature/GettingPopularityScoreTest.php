<?php

namespace App\Tests\Feature;

use App\Entity\SearchResult;
use App\Service\GithubProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GettingPopularityScoreTest extends WebTestCase
{
    /** @test */
    public function it_returns_json_response(): void
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

        $client = static::createClient();

        self::getContainer()->set('App\Service\Provider', $provider);

        // Request a specific page
        $crawler = $client->request('GET', '/score?term='.$searchTerm);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('php', $responseData['term']);
        $this->assertEquals(4, $responseData['score']);
    }
}