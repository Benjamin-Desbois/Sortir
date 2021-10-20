<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/ville", name="app_ville")
     */
    public function ville(): Response
    {
        return $this->render('admin/ville.html.twig', []);
    }

    /**
     * @Route("/site", name="app_site")
     */
    public function site(): Response
    {
        return $this->render('admin/site.html.twig', []);
    }
}
