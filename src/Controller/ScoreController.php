<?php

namespace App\Controller;

use App\Entity\SearchTerm;
use App\Repository\SearchTermPopularityScoreRepository;
use App\Entity\SearchTermPopularityScore;
use App\Service\SearchPopularityScore;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    private $searchPopularityScore;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SearchTermPopularityScoreRepository
     */
    private $popularityScoreRepository;

    public function __construct(SearchPopularityScore $searchPopularityScore, EntityManagerInterface $entityManager, SearchTermPopularityScoreRepository $popularityScoreRepository)
    {
        $this->searchPopularityScore = $searchPopularityScore;
        $this->entityManager = $entityManager;
        $this->popularityScoreRepository = $popularityScoreRepository;
    }

    /**
     * @Route("/score", name="score", methods={"GET"})
     * @Parameter(
     *     name="term",
     *     in="query",
     *     required=true,
     *     @Schema(
     *      type="string"
     *      ),
     *     description="The field used to enter word for searching"
     * )
     * @Parameter(
     *     name="version",
     *     in="query",
     *     required=false,
     *     @Schema(
     *      type="integer"
     *      ),
     *     description="The field used to enter version of API. Default version is 1."
     * )
     * @\OpenApi\Annotations\Response(
     *     response=200,
     *     description="Returns search term popularity score",
     *     @Model(type=SearchTermPopularityScore::class)
     * )
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        // Validation
        if ($request->query->get('term') === null) {
            return new JsonResponse($this->getErrorMessage('Required Attribute', 'Term is required field.'), 422);
        }
        if ($request->query->get('term') === '') {
            return new JsonResponse($this->getErrorMessage('Invalid Attribute', 'Term cannot be blank.'), 422);
        }

        $version = $this->checkApiVersion($request);

        if ($score = $this->popularityScoreRepository->findOneBy(['term' => $request->query->get('term')])) {
            return new JsonResponse(json_decode($serializer->serialize($score, 'json', SerializationContext::create()->setVersion($version))));
        }

        $score = $this->searchPopularityScore->search($request->query->get('term'));

        $this->entityManager->persist($score);
        $this->entityManager->flush();

        return new JsonResponse(json_decode($serializer->serialize($score, 'json', SerializationContext::create()->setVersion($version))));
    }

    /**
     * @return array[]
     */
    private function getErrorMessage($title, $detail): array
    {
        return [
            'errors' => [
                'status' => '422',
                'source' => [
                    'pointer' => '/data/attributes/term'
                ],
                'title' => $title,
                'detail' => $detail,
            ]
        ];
    }

    /**
     * @param Request $request
     * @return bool|float|int|string|\Symfony\Component\HttpFoundation\InputBag
     */
    private function checkApiVersion(Request $request)
    {
        return $request->query->get('version') ?? 1;
    }
}
