<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function add(Request $request, EntityManagerInterface $em, EtatRepository $etat): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);
        $orga = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setEtatsNoEtat($etat->findOneBy(["id" => 1]));
            $sortie->setOrganisateur($this->getUser());
            $em->persist($sortie);
            $em->flush();
            //$this->addFlash('success', 'La sortie '.$sortie->getNom().' a bien été ajouté');
            return $this->redirectToRoute('app_home', ['id' => $sortie->getId()]);
        }

        return $this->render('sortie/add.html.twig', ['formSortie' => $form->createView(), 'orga' => $orga]);
    }

    /** @Route ("/sortie/{id}", name="detail", requirements={"id":"\d+"}) */
    public function detail($id, SortieRepository $repository, LieuRepository $lieuRepo): Response
    {
        $sortie = $repository->find($id);
        $lieu = $sortie->getLieuxNoLieu();
        $ville = $lieu->getVillesNoVille();


        return $this->render('sortie/detail.html.twig', ['sortie'=>$sortie, 'lieu'=>$lieu, 'ville'=>$ville]);
    }

}
