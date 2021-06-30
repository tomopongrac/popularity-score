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

    // /**
    //  * @return SearchTermPopularityScore[] Returns an array of SearchTermPopularityScore objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SearchTermPopularityScore
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
