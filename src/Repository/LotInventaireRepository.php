<?php

namespace App\Repository;

use App\Entity\LotInventaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LotInventaire>
 *
 * @method LotInventaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method LotInventaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method LotInventaire[]    findAll()
 * @method LotInventaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotInventaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LotInventaire::class);
    }

    //    /**
    //     * @return LotInventaire[] Returns an array of LotInventaire objects
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

    //    public function findOneBySomeField($value): ?LotInventaire
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
