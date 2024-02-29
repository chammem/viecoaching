<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RubriqueRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentaireRepository;
use App\Entity\Rubrique;
use App\Entity\Commentaire;
use App\Form\RubriqueType;
use App\Form\CommentaireType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class RubriqueController extends AbstractController
{
    #[Route('/rubrique', name: 'app_rubrique')]
    public function index(): Response
    {
        return $this->render('rubrique/index.html.twig', [
            'controller_name' => 'RubriqueController',
        ]);
    }
    #[Route('/showRubrique', name: 'show_rubrique')]
    public function showRubrique( RubriqueRepository $rubriqueRepository): Response
    {
        $rubrique = $rubriqueRepository->findAll();
        return $this->render('rubrique/showRubrique.html.twig', [
            'rubrique' => $rubrique
        ]);
    }
    #[Route('/addRubrique', name: 'add_rubrique')]
    public function addRubrique(SluggerInterface $slugger, ManagerRegistry $managerRegistry, Request $req): Response
    {
        $em = $managerRegistry->getManager();
        $rubrique = new Rubrique();
        $rubrique->setDateCreation(new \DateTime());
    
        $latestCommentaire = $em->getRepository(Commentaire::class)->findOneBy([], ['dateCreation' => 'DESC']);
        if ($latestCommentaire instanceof Commentaire) {
        $rubrique->setDatePublication($latestCommentaire->getDateCreation());} 
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
        if($file){
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $rubrique->setImage($newFilename);


            $em->persist($rubrique);
            $em->flush();
            return $this->redirectToRoute('show_rubrique');
        }
    
        return $this->renderForm('rubrique/addRubrique.html.twig', [
            'form' => $form,
        ]);
    }
}
    
    
    

    #[Route('/editRubrique/{id}', name: 'edit_rubrique')]
    public function editRubrique($id, SluggerInterface $slugger, RubriqueRepository $rubriqueRepository, Request $req, ManagerRegistry $managerRegistry): Response
    {
        $em = $managerRegistry->getManager();
        $rubrique = $rubriqueRepository->find($id);
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
    
                $rubrique->setImage($newFilename);
            }
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
    #[Route('/showRubriqueUtilisateur', name: 'show_rubrique_utilisateur')]
    public function showRubriqueUtilisateur( RubriqueRepository $rubriqueRepository): Response
    {
        $rubrique = $rubriqueRepository->findAll();
        return $this->render('rubrique/showRubriqueUtilisateur.html.twig', [
            'rubrique' => $rubrique
        ]);
    }
    #[Route('/addRubriqueUtilisateur', name: 'add_rubrique_utilisateur')]
public function addRubriqueUtilisateur(ManagerRegistry $managerRegistry, Request $req): Response
{
    $em = $managerRegistry->getManager();
    $rubrique = new Rubrique();
    $rubrique->setDateCreation(new \DateTime());

    $latestCommentaire = $em->getRepository(Commentaire::class)->findOneBy([], ['dateCreation' => 'DESC']);
    if ($latestCommentaire instanceof Commentaire) {
        $rubrique->setDatePublication($latestCommentaire->getDateCreation());
    }

    $form = $this->createForm(RubriqueType::class, $rubrique, ['addImage' => false]);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        // Check if a new image is uploaded
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            // If a new image is uploaded, process it as before
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('images_directory'), // Set your image directory parameter
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if the file cannot be moved
            }

            // Set the image path to your Rubrique entity
            $rubrique->setImage($newFilename);
        }

        $em->persist($rubrique);
        $em->flush();

        return $this->redirectToRoute('show_rubrique_utilisateur');
    }

    return $this->renderForm('rubrique/addRubriqueUtilisateur.html.twig', [
        'form' => $form,
    ]);
}


}