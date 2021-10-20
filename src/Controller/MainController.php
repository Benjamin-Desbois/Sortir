<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Participant;
use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function home(SortieRepository $sortieRepo, SiteRepository $siteRepo, ParticipantRepository $participantRepo, InscriptionRepository $inscriptionRepo): Response

    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        } else {
            $user = $this->getUser();
            $orga = $this->getUser();
            $inscritpion = $inscriptionRepo->findAll();
            $sorties = $sortieRepo->findALl();
            $sites = $siteRepo->findAll();
            $participant = $participantRepo ->findALl();

            return $this->render('main/index.html.twig', ['user'=>$user, 'sorties'=>$sorties, 'sites'=>$sites, 'participant'=>$participant,'orga'=>$orga, 'inscription'=>$inscritpion]);
        }
    }

    /**
     * @Route("/profil", name="app_profil")
     */
    public function profil(): Response
    {
        return $this->render('main/profil.html.twig', []);
    }


    /** @Route ("/inscriptionSortie/{id}", name="app_inscriptionSortie", requirements={"id":"\d+"}) */
    public function inscriptionSortie($id,SortieRepository $sortieRepo, InscriptionRepository $inscriptionRepo, ParticipantRepository $participantRepo) : Response
    {
        $user = $this->getUser();
        $pseudo = $user->getUserIdentifier();
        $participant = $participantRepo->findOneBy(['pseudo'=>$pseudo]);
        $participantId = $participant->getId();
        $sortieRepo->find($id);
        $inscriptionRepo->inscriptionDQL($participantId,$id);
        return $this->redirectToRoute("app_home");
    }

}
