<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commentaire;
use App\Entity\Rubrique;
use App\Form\CommentaireType;
use App\Repository\RubriqueRepository;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
    #[Route('/showCommentaire/{id}', name: 'show_commentaire')]
    public function showCommentaire($id, RubriqueRepository $rubriqueRepository, CommentaireRepository $commentaireRepository): Response
    {
        $rubrique = $rubriqueRepository->find($id);
        $commentaire = $commentaireRepository->findBy(['rubrique' => $rubrique]);
    
        return $this->render('commentaire/showCommentaire.html.twig', [
            'rubrique' => $rubrique,
            'commentaire' => $commentaire,
        ]);
    }    
    #[Route('/addCommentaire/{id}', name: 'add_commentaire')]
    public function addCommentaire($id,ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $commentaire = new Commentaire();
        $commentaire->setDateCreation(new \DateTime());
        $rubrique = $em->getRepository(Rubrique::class)->find($id);
$commentaire->setRubrique($rubrique);

$form = $this->createForm(CommentaireType::class, $commentaire);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('show_commentaire', ['id' => $commentaire->getRubrique()->getId()]);
        }
        return $this->renderForm('commentaire/addCommentaire.html.twig', [
            'form' => $form 
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

        return $this->renderForm('commentaire/editCommentaire1.html.twig', [
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