<?php

namespace App\Controller;

use App\Entity\SearchTerm;
use App\Repository\SearchTermPopularityScoreRepository;
use App\Service\SearchPopularityScore;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
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
     * @Route("/score", name="score")
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        if ($score = $this->popularityScoreRepository->findOneBy(['term' => $request->query->get('term')])) {
            return new JsonResponse(json_decode($serializer->serialize($score, 'json')));
        }

        $score = $this->searchPopularityScore->search($request->query->get('term'));

        $this->entityManager->persist($score);
        $this->entityManager->flush();

        return new JsonResponse(json_decode($serializer->serialize($score, 'json')));
    }
}
