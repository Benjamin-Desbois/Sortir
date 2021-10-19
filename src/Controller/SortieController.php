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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function detail($id, SortieRepository $sortieRepo): Response
    {
        $sortie = $sortieRepo->find($id);
        $lieu = $sortie->getLieuxNoLieu();
        $ville = $lieu->getVillesNoVille();

        return $this->render('sortie/detail.html.twig', ['sortie' => $sortie,
            'lieu' => $lieu,
            'ville' => $ville]);
    }

    /** @Route ("/deleteSortie/{id}", name="app_deleteSortie", requirements={"id":"\d+"}) */
    public function deleteSortie($id, SortieRepository $repo) : Response
    {

        $repo->deleteSortieDQL($id);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/getLieuxByVille/{idville}", name="getLieuxByVille")
     */
    public function getLieuxByVille(LieuRepository $lieuRepo, VilleRepository $villeRepo, $idville=1): Response
    {
        $lieux = $lieuRepo->findBy(['villes_no_ville'=>$idville]);
        $listeLieux = array();

        foreach ($lieux as $lieu) {
            $listeLieux[] = array(
                'id'=>$lieu->getId(),
                'nom_lieu'=>$lieu->getNomLieu(),
                'rue'=>$lieu->getRue(),
                'latitude'=>$lieu->getLatitude(),
                'longitude'=>$lieu->getLongitude(),
                'ville'=>$lieu->getVillesNoVille()
            );
        }
        return new JsonResponse($listeLieux);
    }

    /**
     * @Route("/getCodePostal/{idville}", name="getCodePostal")
     */
    public function getCodePostal(VilleRepository $villeRepo, $idville=1): Response
    {
        $ville = $villeRepo->findOneBy(['id'=>$idville]);
            $villeSelect[] = array(
                'id'=>$ville->getId(),
                'nom_ville'=>$ville->getNomVille(),
                'code_postal'=>$ville->getCodePostal()
            );
        return new JsonResponse($villeSelect);
    }

    /**
     * @Route("/getLieu/{idlieu}", name="getLieu")
     */
    public function getLieu(LieuRepository $lieuRepo, $idlieu=1): Response
    {
        $lieu = $lieuRepo->findOneBy(['id'=>$idlieu]);
            $lieuSelect[] = array(
                'id'=>$lieu->getId(),
                'nom_lieu'=>$lieu->getNomLieu(),
                'rue'=>$lieu->getRue(),
                'latitude'=>$lieu->getLatitude(),
                'longitude'=>$lieu->getLongitude(),
                'ville'=>$lieu->getVillesNoVille()
            );
        return new JsonResponse($lieuSelect);
    }

    /**
     * @Route("/sortie/modifier", name="modifier_sortie")
     */
    public function modifierSortie(): Response
    {
        return $this->render('sortie/modifierSortie.html.twig', []);
    }

    /**
     * @Route("/sortie/annuler", name="annuler_sortie")
     */
    public function annulerSortie(): Response
    {
        return $this->render('sortie/annuler.html.twig', []);
    }
}
