<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/index.html.twig');
    }
    /** @Route("/sortie/add", name="add") */
    public function add(Request $request, EntityManagerInterface $em): Response {
        $sortie = new Sortie();
        $form = $this->createForm(SortieFormType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie '.$sortie->getNom().' a bien été ajouté');
            return $this->redirectToRoute('sortie');
        }

        return $this->render('sortie/add.html.twig', ['formSortie' => $form->createView()]);
    }

}
