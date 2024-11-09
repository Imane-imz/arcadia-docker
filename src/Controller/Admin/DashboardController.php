<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Avis;
use App\Entity\Message;
use App\Entity\Nourriture;
use App\Entity\RapportVeterinaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'app_dashboard')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {

        $rapportveterinaires = $this->entityManager->getRepository(RapportVeterinaire::class)->findAll();
        $animaux = $this->entityManager->getRepository(Animal::class)->findAll();
        $nourritures = $this->entityManager->getRepository(Nourriture::class)->findAll();
        $avis = $this->entityManager->getRepository(Avis::class)->findBy(['isVisible' => false]);
        $messages = $this->entityManager->getRepository(Message::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'rapportveterinaires' => $rapportveterinaires,
            'animaux' => $animaux,
            'nourritures' => $nourritures,
            'avis' => $avis,
            'messages' => $messages
        ]);
    }
}
