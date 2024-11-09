<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/service')]
class ServiceController extends AbstractController
{
    #[Route('', name: 'app_admin_service_index', methods: ['GET'])]
    public function index(ServiceRepository $repository): Response
    {
        $services = $repository->findAll();

        return $this->render('admin_service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'services' => $services,
        ]);
    }

    #[Route('/new', name: 'app_admin_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYEE')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
        }

        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $file */
        $file = $form->get('image')->getData();

        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/assets/uploads/services',
                    $filename
                );
                $service->setImage($filename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                return $this->render('admin_service/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        $manager->persist($service);
        $manager->flush();

        return $this->redirectToRoute('app_admin_service_show', ['id' => $service->getId()]);
        }

        return $this->render('admin_service/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_service_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Service $service, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYEE')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
        }

        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($service);
            $manager->flush();

            return $this->redirectToRoute('app_admin_service_show', ['id' => $service->getId()]);
        }

        return $this->render('admin_service/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_service_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('admin_service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_service_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    public function delete(Service $service, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_EMPLOYEE')) {
            throw new AccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
        }

        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();

            $this->addFlash('success', 'Service supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression du service.');
        }

        return $this->redirectToRoute('app_admin_service_index'); // Redirection vers la liste des services ou autre page
    }
}