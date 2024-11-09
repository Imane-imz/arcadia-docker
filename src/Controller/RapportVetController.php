<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use App\Form\RapportFormType;
use App\Repository\RapportVeterinaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rapport-vet')]
class RapportVetController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    
    #[Route('', name: 'app_admin_rapport_index', methods: ['GET'])]
    public function index(RapportVeterinaireRepository $repository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_VETO')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
        }

        $rapportveterinaires = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('rapport_vet/index.html.twig', [
            'controller_name' => 'RapportVetController',
            'rapportveterinaires' => $rapportveterinaires,
        ]);
    }

    #[Route('/new', name: 'app_admin_rapport_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_VETO')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->security->getUser();

        $rapportveterinaire = new RapportVeterinaire();
        $rapportveterinaire->setUser($user);
        $form = $this->createForm(RapportFormType::class, $rapportveterinaire);
    

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        $manager->persist($rapportveterinaire);
        $manager->flush(); 

        return $this->redirectToRoute('app_admin_rapport_index', ['id' => $rapportveterinaire->getId()]);
        }

        return $this->render('rapport_vet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_rapport_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    #[IsGranted('ROLE_VETO')]
    public function delete(RapportVeterinaire $rapportveterinaire, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$rapportveterinaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rapportveterinaire);
            $entityManager->flush();

            $this->addFlash('success', 'Rapport supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression du rapport.');
        }

        return $this->redirectToRoute('app_admin_rapport_index'); // Redirection vers la liste des services ou autre page
    }
}

