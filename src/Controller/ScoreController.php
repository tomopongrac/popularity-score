<?php

namespace App\Controller;

use App\Entity\SearchTerm;
use App\Repository\SearchTermPopularityScoreRepository;
use App\Service\SearchPopularityScore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request): Response
    {
        if ($score = $this->popularityScoreRepository->findOneBy(['term' => $request->query->get('term')])) {
            return $this->json([
                'term' => $score->getTerm(),
                'score' => $score->getScore(),
            ]);
        }

        $score = $this->searchPopularityScore->search($request->query->get('term'));

        $this->entityManager->persist($score);
        $this->entityManager->flush();

        return $this->json([
            'term' => $score->getTerm(),
            'score' => $score->getScore(),
        ]);
    }
}
