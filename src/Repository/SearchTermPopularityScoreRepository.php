<?php

namespace App\Repository;

use App\Entity\SearchTermPopularityScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SearchTermPopularityScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchTermPopularityScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchTermPopularityScore[]    findAll()
 * @method SearchTermPopularityScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchTermPopularityScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchTermPopularityScore::class);
    }

    public function getCountAllRows()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
