<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setActif(false);
            $user->setAdministrateur(false);
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /** @Route ("/profil/{id}", name="profilDetail", requirements={"id":"\d+"}) */
    public function detail($id, ParticipantRepository $repository, SiteRepository  $siteRepo): Response{
        $user = $repository->find($id);
        $site = $user->getSitesNoSite();

        $user->setSitesNoSite($siteRepo->find($user->getSitesNoSite()->getId()));

        //dd($user);
        return $this->render('main/profil.html.twig', ['user'=>$user, 'site'=>$site]);
    }
}
