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
    public function detail($id, ParticipantRepository $repository): Response
    {
        $user = $repository->find($id);
        $site = $user->getSitesNoSite();

        return $this->render('main/profil.html.twig', ['user' => $user, 'site' => $site]);
    }

    /** @Route ("/profil", name="app_profil") */
    public function monProfil(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $site = $user->getSitesNoSite();

        return $this->render('main/profil.html.twig', ['user' => $user, 'site' => $site]);
    }

    /** @Route ("/modifierProfil", name="app_modifierProfil") */
    public function modifierProfil(SiteRepository $repo): Response
    {
        {
                $user = $this->getUser();
                $site = $user->getSitesNoSite();
                $all = $repo->findAll();

            if ( isset( $_POST['modifier'] )) {

                if ( $_POST['pseudo'] != null) {
                    $user->setPseudo($_POST['pseudo']);
                }
                if ( $_POST['nom'] != null) {
                    $user->setNom($_POST['nom']);
                }
                if ( $_POST['prenom'] != null) {
                    $user->setPrenom($_POST['prenom']);
                }
                if ( $_POST['numTel'] != null) {
                    $user->setTelephone($_POST['numTel']);
                }
                if ( $_POST['email'] != null) {
                    $user->setMail($_POST['email']);
                }
                if ( $_POST['site'] != null) {
                    $noSite = $repo->findOneBy(array('nom'=>$_POST['site']));
                    $user->setSitesNoSite($noSite);
                }
                $user = $this->getUser();
                $site = $user->getSitesNoSite();
                $all = $repo->findAll();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }


            return $this->render('main/modifierProfil.html.twig', ['user' => $user, 'site' => $site, 'all' => $all]);
        }
    }
}
