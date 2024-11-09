<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use App\Form\HabitatFormType;
use App\Repository\HabitatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/habitat')]
class HabitatController extends AbstractController
{
    #[Route('', name: 'app_admin_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $repository): Response
    {
        $habitats = $repository->findAll();

        return $this->render('admin_habitat/index.html.twig', [
            'controller_name' => 'HabitatController',
            'habitats' => $habitats,
        ]); 
    }

    #[Route('/new', name: 'app_admin_habitat_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    /* #[IsGranted('ROLE_EMPLOYEE')] */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatFormType::class, $habitat);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $file */
        $file = $form->get('image')->getData();

        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/assets/uploads/habitats',
                    $filename
                );
                $habitat->setImage($filename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                return $this->render('admin_habitat/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        $manager->persist($habitat);
        $manager->flush();

        return $this->redirectToRoute('app_admin_habitat_show', ['id' => $habitat->getId()]);
        }

        return $this->render('admin_habitat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_habitat_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    /* #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EMPLOYEE')] */
    public function edit(Habitat $habitat, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(HabitatFormType::class, $habitat);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($habitat);
            $manager->flush();

            return $this->redirectToRoute('app_admin_habitat_show', ['id' => $habitat->getId()]);
        }

        return $this->render('admin_habitat/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_habitat_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Habitat $habitat): Response
    {
        return $this->render('admin_habitat/show.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_admin_habitat_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    /* #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_EMPLOYEE')] */
    public function delete(Habitat $habitat, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$habitat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($habitat);
            $entityManager->flush();

            $this->addFlash('success', 'Habitat supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression de l\'habitat.');
        }

        return $this->redirectToRoute('app_admin_habitat_index'); // Redirection vers la liste des habitats ou autre page
    }
}