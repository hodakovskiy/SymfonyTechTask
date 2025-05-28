<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Home page.
     *
     * @return Response 
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('default/homepage.html.twig');
    }
}
