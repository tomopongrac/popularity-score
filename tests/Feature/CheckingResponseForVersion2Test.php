<?php

namespace App\Tests\Feature;

use App\Entity\SearchResult;
use App\Entity\SearchTermPopularityScore;
use App\Service\GithubProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckingResponseForVersion2Test extends WebTestCase
{
    /** @test */
    public function it_returns_json_response_in_jsonapi(): void
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

        $dm = $this->getEntityManagerAndPrepareDatabase();

        // Request a specific page
        $crawler = $client->request('GET', '/score?version=2&term='.$searchTerm);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('php', $responseData['data']['term']);
        $this->assertEquals(4, $responseData['data']['score']);
    }

    /**
     * @return object|null
     */
    public function getEntityManagerAndPrepareDatabase(): EntityManagerInterface
    {
        $dm = self::getContainer()->get('doctrine.orm.default_entity_manager');

        $connection = $dm->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('search_term_popularity_score', true /* whether to cascade */));
        return $dm;
    }
}