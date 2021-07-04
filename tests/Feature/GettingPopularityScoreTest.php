<?php

namespace App\Tests\Feature;

use App\Entity\SearchResult;
use App\Entity\SearchTermPopularityScore;
use App\Service\GithubProvider;
use Doctrine\ORM\EntityManagerInterface;
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

        $dm = $this->getEntityManagerAndPrepareDatabase();

        // Request a specific page
        $crawler = $client->request('GET', '/score?term='.$searchTerm);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('php', $responseData['term']);
        $this->assertEquals(4, $responseData['score']);
    }

    /** @test */
    public function it_saves_term_popularity_to_database()
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
        $crawler = $client->request('GET', '/score?term=' . $searchTerm);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $searchTermPopularityScore = $dm->getRepository(SearchTermPopularityScore::class)
            ->findOneBy([
                'term' => $searchTerm,
            ]);
        $this->assertNotNull($searchTermPopularityScore, 'Search term popularity score is not saved.');
    }

    /** @test */
    public function it_load_term_data_from_database_if_row_exists()
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

        $searchTermPopularityScoreForDatabase = new SearchTermPopularityScore();
        $searchTermPopularityScoreForDatabase->setTerm('php');
        $searchTermPopularityScoreForDatabase->setScore(6.23);
        $dm->persist($searchTermPopularityScoreForDatabase);
        $dm->flush();

        // Request a specific page
        $crawler = $client->request('GET', '/score?term=' . $searchTerm);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('php', $responseData['term']);
        $this->assertEquals(6.23, $responseData['score']);

        // Check that there is only one row in database
        $searchTermPopularityScoreCount = $dm->getRepository(SearchTermPopularityScore::class)
            ->getCountAllRows();
        $this->assertEquals(1, $searchTermPopularityScoreCount);
    }

    /** @test */
    public function term_is_required_property()
    {
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/score');

        // Validate a successful response and some content
        $this->assertResponseStatusCodeSame(422);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Required Attribute', $responseData['errors']['title']);
    }


    /** @test */
    public function term_cannot_be_blank()
    {
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/score?term=');

        // Validate a successful response and some content
        $this->assertResponseStatusCodeSame(422);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Invalid Attribute', $responseData['errors']['title']);
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