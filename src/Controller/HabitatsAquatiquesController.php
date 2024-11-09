<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatsAquatiquesController extends AbstractController
{
    #[Route('/habitats/aquatiques', name: 'app_habitats_aquatiques')]
    public function index(): Response
    {
        return $this->render('habitats_aquatiques/index.html.twig', [
            'controller_name' => 'HabitatsAquatiquesController',
        ]);
    }
}
