<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatsAeriensController extends AbstractController
{
    #[Route('/habitats/aeriens', name: 'app_habitats_aeriens')]
    public function index(): Response
    {
        return $this->render('habitats_aeriens/index.html.twig', [
            'controller_name' => 'HabitatsAeriensController',
        ]);
    }
}
