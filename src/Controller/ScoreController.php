<?php

namespace App\Controller;

use App\Service\SearchPopularityScore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    private $searchPopularityScore;

    public function __construct(SearchPopularityScore $searchPopularityScore)
    {
        $this->searchPopularityScore = $searchPopularityScore;
    }

    /**
     * @Route("/score", name="score")
     */
    public function index(Request $request): Response
    {
        $score = $this->searchPopularityScore->search($request->query->get('term'));

        return $this->json([
            'term' => $score->getTerm(),
            'score' => $score->getScore(),
        ]);
    }
}
