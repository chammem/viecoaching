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
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;

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
        $rubrique->setDateCreation(new \DateTime());
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $latestCommentaire = $em->getRepository(Commentaire::class)->findOneBy([], ['dateCreation' => 'DESC']);
        if ($latestCommentaire instanceof Commentaire) {
            $rubrique->setDatePublication($latestCommentaire->getDateCreation());
        }
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($rubrique);
            $em->flush();
            return $this->redirectToRoute('show_rubrique');
        }
        return $this->renderForm('rubrique/addRubrique.html.twig', [
            'form' => $form ,
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
        $rubrique = $rubriqueRepository->find($id);
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($rubrique);
            $em->flush();
            return $this->redirectToRoute('show_rubrique');
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
        return $this->redirectToRoute('show_rubrique');
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


}
