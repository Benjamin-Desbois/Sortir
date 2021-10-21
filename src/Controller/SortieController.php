<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Form\UpdateSortieType;
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
    /** @Route("/sortie/add", name="add") */
    public function add(Request $request, EntityManagerInterface $em, EtatRepository $etatRepo, VilleRepository $villeRepo): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);
        $orga = $this->getUser();
        $villes = $villeRepo->findALl();

        if ($form->isSubmitted() && $form->isValid()) {

            if (isset($_POST['publier'])) {
                $sortie->setEtatsNoEtat($etatRepo->findOneBy(["id" => 4]));
            } else {
                $sortie->setEtatsNoEtat($etatRepo->findOneBy(["id" => 5]));
            }
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            //$this->addFlash('success', 'La sortie '.$sortie->getNom().' a bien été ajouté');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/add.html.twig', ['formSortie' => $form->createView(),
            'orga' => $orga,
            'villes' => $villes]);
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

    /**
     * @Route("/getLieuxByVille/{idville}", name="getLieuxByVille")
     */

    public function getLieuxByVille(LieuRepository $lieuRepo, VilleRepository $villeRepo, $idville = 1): Response
    {
        $lieux = $lieuRepo->findBy(['villes_no_ville' => $idville]);
        $listeLieux = array();

        foreach ($lieux as $lieu) {
            $listeLieux[] = array(
                'id' => $lieu->getId(),
                'nom_lieu' => $lieu->getNomLieu(),
                'rue' => $lieu->getRue(),
                'latitude' => $lieu->getLatitude(),
                'longitude' => $lieu->getLongitude(),
                'ville' => $lieu->getVillesNoVille()
            );
        }
        return new JsonResponse($listeLieux);
    }

    /**
     * @Route("/getCodePostal/{idville}", name="getCodePostal")
     */
    public function getCodePostal(VilleRepository $villeRepo, $idville = 1): Response
    {
        $ville = $villeRepo->findOneBy(['id' => $idville]);
        $villeSelect[] = array(
            'id' => $ville->getId(),
            'nom_ville' => $ville->getNomVille(),
            'code_postal' => $ville->getCodePostal()
        );
        return new JsonResponse($villeSelect);
    }

    /**
     * @Route("/getLieu/{idlieu}", name="getLieu")
     */
    public function getLieu(LieuRepository $lieuRepo, $idlieu = 1): Response
    {
        $lieu = $lieuRepo->findOneBy(['id' => $idlieu]);
        $lieuSelect[] = array(
            'id' => $lieu->getId(),
            'nom_lieu' => $lieu->getNomLieu(),
            'rue' => $lieu->getRue(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude(),
            'ville' => $lieu->getVillesNoVille()
        );
        return new JsonResponse($lieuSelect);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="modifier_sortie", requirements={"id":"\d+"}))
     */
    public function modifierSortie($id, Request $request, EntityManagerInterface $em, EtatRepository $etatRepo): Response
    {
        $sortie = new Sortie();

        $form = $this->createForm(UpdateSortieType::class,$sortie);
        $form->handleRequest($request);
//        $orga = $this->getUser();
//        $villes = $villeRepo->findALl();

        if ($form->isSubmitted() && $form->isValid()) {
//            $sortie->setEtatsNoEtat($etatRepo->findOneBy(["id" => 1]));
//            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('modifier_sortie');
        }
        return $this->render('sortie/modifierSortie.html.twig', ['modifierSortie' => $form->createView()]);
    }

    /**
     * @Route("/sortie/annuler/{id}", name="annuler_sortie", requirements={"id":"\d+"})
     */
    public function annulerSortie($id, SortieRepository $sortieRepo): Response
    {
        $sortie = $sortieRepo->findOneBy(['id' => $id]);

        if (isset($_POST['modifier'])) {
            $sortieRepo->deleteSortieDQL($id, $_POST['description']);
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/annuler.html.twig', ['sortie' => $sortie]);
    }

    /**
     * @Route("/sortie/publier/{id}", name="publier_sortie", requirements={"id":"\d+"})
     */
    public function publierSortie($id, EtatRepository $etatRepo): Response
    {
        $etatRepo->publier($id);
        return $this->redirectToRoute('app_home');
    }
}


