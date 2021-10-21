<?php

namespace App\Repository;

use App\Entity\Sortie;
use DateInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysqli;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function deleteSortieDQL($id, $description) {

        $servername = "localhost";
        $username = "root";
        $dbname = "sortie";
        $password ='';

// Create connection

        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "UPDATE sortie SET descriptioninfos = '$description', etats_no_etat_id ='2' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $conn->close();
    }

    /**
     * @throws \Exception
     */
    public function verifyDate() {

        $sorties = $this->findAll();

        foreach ($sorties as $sortie) {

            $dateCloture = $sortie->getDatecloture();
            $dateDebut = $sortie->getDatedebut();
            $minutes_to_add = $sortie->getDuree();
            $interval =new DateInterval('PT' . $minutes_to_add . 'M');
            $dateDuree = clone $dateDebut;
            $dateDuree->add($interval);
            $id = $sortie->getId();
            $now = date_create();

            $servername = "localhost";
            $username = "root";
            $dbname = "sortie";
            $password ='';

// Create connection

            $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($dateCloture < $now and $dateDebut > $now) {
                if ($sortie->getEtatsNoEtat()->getId() == '4') {
                    $sql = "UPDATE sortie SET etats_no_etat_id = '6' WHERE id=$id";
                }
            }
            if ($dateDebut < $now and $dateDuree > $now) {
                if ($sortie->getEtatsNoEtat()->getId() == '4' or $sortie->getEtatsNoEtat()->getId() == '3') {
                    $sql = "UPDATE sortie SET etats_no_etat_id = '3' WHERE id=$id";
                }
            }
            if ($dateDuree < $now) {
                if ($sortie->getEtatsNoEtat()->getId() == '4' or $sortie->getEtatsNoEtat()->getId() == '3' or $sortie->getEtatsNoEtat()->getId() == '6') {
                    $sql = "UPDATE sortie SET etats_no_etat_id = '1' WHERE id=$id";
                }
            }

            if (isset($sql)) {
                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }

            $conn->close();

        }

    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
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
    public function findOneBySomeField($value): ?Sortie
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
