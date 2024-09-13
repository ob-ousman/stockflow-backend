<?php

namespace App\Repository;

use App\Entity\LotReception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LotReception>
 *
 * @method LotReception|null find($id, $lockMode = null, $lockVersion = null)
 * @method LotReception|null findOneBy(array $criteria, array $orderBy = null)
 * @method LotReception[]    findAll()
 * @method LotReception[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotReceptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LotReception::class);
    }

    //    /**
    //     * @return LotReception[] Returns an array of LotReception objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LotReception
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
