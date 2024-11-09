<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisFormType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/avis')]
class AvisController extends AbstractController
{
    
    #[Route('', name: 'app_avis', methods: ['GET'])]
    public function index(AvisRepository $repository): Response
    {
        $avis = $repository->findBy(['isVisible' => true]);

        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
            'avis' => $avis,
        ]);
    }

    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisFormType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($avis);
            $manager->flush();

            return $this->redirectToRoute('app_avis', ['id' => $avis->getId()]);
        }

        return $this->render('avis/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avis/delete/{id}', name: 'app_avis_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    #[IsGranted('ROLE_ADMIN')]
   /*  #[IsGranted('ROLE_EMPLOYEE')] */
    public function delete(Avis $avis, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$avis->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avis);
            $entityManager->flush();

            $this->addFlash('success', 'L\'avis a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression de l\'avis.');
        }

        return $this->redirectToRoute('app_avis_index'); // Redirection vers la liste des services ou autre page
    }

    
}

