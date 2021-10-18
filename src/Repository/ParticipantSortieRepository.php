<?php

namespace App\Repository;

use App\Entity\ParticipantSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysqli;

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

    public function addPartSortie($user,$sorties){
        $servername="localhost";
        $username = "root";
        $dbname = "sortie";
        $password = '';

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "INSERT INTO participant_sortie VALUES participant.id,sortie.id";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $conn->close();

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
