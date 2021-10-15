<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysqli;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
//    /**
//     * @Route("/sortie/page/{numPage}", name="page", requirements={"numPage":"\d+"})
//     */
//    public function page($numPage, SortieRepository $repository): Response
//    {
//        $sorties = $repository->findAllwithPists(3,$numPage);
//        return $this->render('sortie/index.html.twig', ['sorties'=>$sorties, 'numPage'=>$numPage]);
//    }

    /** @Route("/sortie/add", name="add") */
    public function add(Request $request, EntityManagerInterface $em, EtatRepository $etatRepo, VilleRepository $villeRepo): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);
        $orga = $this->getUser();

        $villes = $villeRepo->findALl();

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setEtatsNoEtat($etatRepo->findOneBy(["id" => 1]));
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            //$this->addFlash('success', 'La sortie '.$sortie->getNom().' a bien été ajouté');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/add.html.twig', ['formSortie' => $form->createView(),
            'orga'=>$orga,
            'villes'=>$villes]);
    }

    /** @Route ("/sortie/{id}", name="detail", requirements={"id":"\d+"}) */
    public function detail($id, SortieRepository $repository, LieuRepository $lieuRepo): Response
    {
        $sortie = $repository->find($id);
        $lieu = $sortie->getLieuxNoLieu();
        $ville = $lieu->getVillesNoVille();

        return $this->render('sortie/detail.html.twig', ['sortie' => $sortie,
            'lieu' => $lieu,
            'ville' => $ville]);
    }

    /** @Route ("/deleteSortie/{id}", name="app_deleteSortie", requirements={"id":"\d+"}) */
    public function deleteSortie($id) : Response {
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

// sql to delete a record
        $sql = "DELETE FROM sortie WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $conn->close();

        return $this->redirectToRoute('app_home');
    }
}
