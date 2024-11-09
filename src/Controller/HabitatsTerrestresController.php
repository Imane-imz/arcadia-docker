<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatsTerrestresController extends AbstractController
{
    #[Route('/habitats/terrestres', name: 'app_habitats_terrestres')]
    public function index(): Response
    {
        return $this->render('habitats_terrestres/index.html.twig', [
            'controller_name' => 'HabitatsTerrestresController',
        ]);
    }
}
