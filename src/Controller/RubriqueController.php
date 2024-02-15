<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RubriqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Rubrique;
use App\Form\RubriqueType;

class RubriqueController extends AbstractController
{
    #[Route('/rubrique', name: 'app_rubrique')]
    public function index(): Response
    {
        return $this->render('rubrique/index.html.twig', [
            'controller_name' => 'RubriqueController',
        ]);
    }
    #[Route('/addRubrique', name: 'add_rubrique')]
    public function addRubrique(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($rubrique);
            $em->flush();
        }
        return $this->renderForm('rubrique/addRubrique.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/showRubrique', name: 'show_rubrique')]
    public function showRubrique(RubriqueRepository $rubriqueRepository): Response
    {
        $rubrique = $rubriqueRepository->findAll();
        return $this->render('rubrique/showRubrique.html.twig', [
            'rubrique' => $rubrique
        ]);
    }

    #[Route('/editRubrique/{id}', name: 'edit_rubrique')]
    public function editRubrique($id, RubriqueRepository $rubriqueRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $rubrique = $RubriqueRepository->find($id);
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($rubrique);
            $em->flush();
            return $this->redirectToRoute('showRubrique');
        }

        return $this->renderForm('rubrique/editRubrique.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deleteRubrique/{id}', name: 'delete_rubrique')]
    public function deleteRubrique($id, RubriqueRepository $rubriqueRepository, ManagerRegistry $managerRegistry): Response {
        $em = $managerRegistry->getManager();
        $rubrique = $rubriqueRepository->find($id);
        $em->remove($rubrique);
        $em->flush();
        return $this->redirectToRoute('showRubrique');
    }
}
