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

    /** @Route ("/profil/{id}", name="app_profilDetail", requirements={"id":"\d+"}) */
    public function detail($id, ParticipantRepository $repository): Response
    {
        $user = $repository->find($id);
        $site = $user->getSitesNoSite();
        $pseudo = $this->getUser()->getUserIdentifier();

        return $this->render('main/profil.html.twig', ['user' => $user, 'site' => $site, 'pseudo' => $pseudo]);
    }

    /** @Route ("/profil", name="app_profil") */
    public function monProfil(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $site = $user->getSitesNoSite();
        $pseudo = $this->getUser()->getUserIdentifier();

        return $this->render('main/profil.html.twig', ['user' => $user, 'site' => $site, 'pseudo' => $pseudo]);
    }

    /** @Route ("/modifierProfil", name="app_modifierProfil") */
    public function modifierProfil(ParticipantRepository $pRepo, SiteRepository $repo,UserPasswordHasherInterface $userPasswordHasherInterface, Request $request): Response {


                $user = $this->getUser();
                $site = $user->getSitesNoSite();
                $all = $repo->findAll();
                $allp = $pRepo->findAll();
                $message = null;
                $unique = true;



            if ( isset( $_POST['modifier'] )) {
                $request->request->get('pseudo');
                if ( $_POST['pseudo'] != null) {
                    foreach ($allp as $oui) {
                        if ($oui->getNom() == $_POST['pseudo']) {
                            $unique = false;
                        }
                    }
                    if ($unique) {
                        $user->setPseudo($_POST['pseudo']);
                    } else {
                        $message = 'Le pseudo est déjà attribué';
                    }

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
                if ( $_POST['newpassword'] != null) {
                    if (password_verify( $_POST['password'], $user->getPassword())) {
                        $user->setPassword(
                            $userPasswordHasherInterface->hashPassword(
                                $user,
                                $_POST['password']
                            )
                        );
                   } else {
                        $message = 'Le mot de passe ne correspond pas';
                    }
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            }


            return $this->render('main/modifierProfil.html.twig', ['user'=>$user, 'site'=>$site, 'all'=>$all, 'error'=>$message]);
        }

}
