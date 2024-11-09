<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Form\AnimalFormType;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/animal')]
class AnimalController extends AbstractController
{
    #[Route('', name: 'app_admin_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $repository): Response
    {
        $animals = $repository->findAll();

        return $this->render('admin/animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animal' => $animals,
        ]); 
    }

    #[Route('/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalFormType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $file */
            $file = $form->get('image')->getData();

        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/assets/uploads/animals',
                    $filename
                );
                $animal->setImage($filename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                return $this->render('animal/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        $manager->persist($animal);
        $manager->flush();

        return $this->redirectToRoute('app_admin_habitat_index', ['id' => $animal->getId()]);
        }

        return $this->render('admin_animal/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_animal_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Animal $animal, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AnimalFormType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($animal);
            $manager->flush();

            return $this->redirectToRoute('app_admin_habitat_index', ['id' => $animal->getId()]);
        }

        return $this->render('admin_animal/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_animal_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('admin_habitat/index.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_animal_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    public function delete(Animal $animal, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();

            $this->addFlash('success', 'Animal supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression de l\'animal.');
        }

        return $this->redirectToRoute('app_admin_habitat_index'); // Redirection vers la liste des habitats ou autre page
    }

    #[Route('/{id}/add_view_count', name: 'app_admin_animal_add_view_count', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function addViewCount(Animal $animal, EntityManagerInterface $manager): Response
    {
        $animal->addViewCount();
        $manager->persist($animal);
        $manager->flush();

        return new JsonResponse([]);
    }
}
