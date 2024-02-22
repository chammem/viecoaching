<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commentaire;
use App\Form\CommentaireType;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
    #[Route('/addCommentaire', name: 'add_commentaire')]
    public function addCommentaire(ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('show_commentaire');
        }
        return $this->renderForm('commentaire/addCommentaire.html.twig', [
            'form' => $form ,
        ]);
    }
    #[Route('/showCommentaire', name: 'show_commentaire')]
    public function showCommentaire(CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = $commentaireRepository->findAll();
        return $this->render('commentaire/showCommentaire.html.twig', [
            'commentaire' => $commentaire
        ]);
    }

    #[Route('/editCommentaire/{id}', name: 'edit_commentaire')]
    public function editCommentaire($id, CommentaireRepository $commentaireRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $commentaire = $commentaireRepository->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('show_commentaire');
        }

        return $this->renderForm('commentaire/editCommentaire.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/deleteCommentaire/{id}', name: 'delete_commentaire')]
    public function deleteCommentaire($id, CommentaireRepository $commentaireRepository, ManagerRegistry $managerRegistry): Response {
        $em = $managerRegistry->getManager();
        $commentaire = $commentaireRepository->find($id);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('show_commentaire');
    }
}
