<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/message', name: 'app_message')]
class MessageController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    /* #[IsGranted('ROLE_VETO')] */
    #[Route('', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $repository): Response
    {
        $messages = $repository->findAll();

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'messages' => $messages,
        ]);
    }

    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        $manager->persist($message);
        $manager->flush(); 

        return $this->redirectToRoute('app_message_show', ['id' => $message->getId()]);
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_message_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    #[IsGranted('ROLE_EMPLOYEE')]
    public function delete(Message $message, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();

            $this->addFlash('success', 'Rapport supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression du rapport.');
        }

        return $this->redirectToRoute('app_message_index'); // Redirection vers la liste des services ou autre page
    }
}

