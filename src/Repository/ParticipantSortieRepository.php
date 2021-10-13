<?php

namespace App\Repository;

use App\Entity\ParticipantSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParticipantSortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantSortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantSortie[]    findAll()
 * @method ParticipantSortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantSortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantSortie::class);
    }

    // /**
    //  * @return ParticipantSortie[] Returns an array of ParticipantSortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParticipantSortie
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
