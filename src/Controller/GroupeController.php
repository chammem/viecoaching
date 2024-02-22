<?php

namespace App\Controller;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\GroupeType;
use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GroupeController extends AbstractController
{
    #[Route('/groupe', name:'app_groupe')]
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }
    #[Route('/showgroupe', name:'showgroupe')]
    public function showgroupe(GroupeRepository $x): Response
    {
        $groupe=$x->findAll();
        return $this->renderForm('groupe/showgroupe.html.twig', [
            'groupe'=> $groupe,
        ]);
}
#[Route('/listegroupe', name:'listegroupe')]
    public function listegroupe(GroupeRepository $x): Response
    {
        $groupe=$x->findAll();
        return $this->renderForm('groupe/listegroupe.html.twig', [
            'groupe'=> $groupe,
        ]);
}

#[Route('/creategroupe', name: 'creategroupe')]

 public function createtypegroupe(ManagerRegistry $m ,Request $req,SluggerInterface $slugger): Response
{
    $em=$m->getManager();
   $groupe = new groupe();
   $form = $this->createForm(groupeType::class, $groupe);
   $form->handleRequest($req);

   if ($form->isSubmitted() && $form->isValid()) 
    {  
        $file = $form->get('image')->getData();
        if($file){
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }

            $groupe->setImage($newFilename);

        } 
       $em->persist($groupe);
       $em->flush();
       return $this->redirectToRoute('showgroupe');


   }

   return $this->renderForm('groupe/creategroupe.html.twig', [
       'form' =>$form
   ]);

} 

/*
#[Route('/creategroupe', name: 'creategroupe')]

public function creategroupe(ManagerRegistry $m ,Request $req): Response
{
    $em=$m->getManager();
    $groupe = new Groupe;
    $form = $this->createForm(GroupeType::class, $groupe);
    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        //$file = $groupe->getImageFile();

        // Generate a unique name for the file before saving it
        //$fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where images are stored
        //try {
        //    $file->move(
        //        $this->getParameter('images_directory'),
        //        $fileName
                
        //    );
        //} catch (FileException $e) {
            // ... handle exception if something happens during file upload
        //}

        // updates the 'image' property to store the image file name
        // instead of its contents
      //  $groupe->setImage($fileName);

        //$em->persist($groupe);
        //$em->flush();
        //return $this->redirectToRoute('showgroupe');
    ///}
    //return $this->renderForm('groupe/creategroupe.html.twig', [
   //     'form' =>$form
   // ]);

    


   #[Route('/editgroupep/{id}', name: 'editgroupep')]
   public function editgroupep($id, ManagerRegistry $m, GroupeRepository $groupeRepository, Request $req, SluggerInterface $slugger): Response
   {
       $em = $m->getManager();
       $dataid = $groupeRepository->find($id);
       $form = $this->createForm(GroupeType::class, $dataid);
       $form->handleRequest($req);
   
       if ($form->isSubmitted() && $form->isValid()) {
           $file = $form->get('image')->getData();
           if ($file) {
               $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
               $safeFilename = $slugger->slug($originalFilename);
               $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
               try {
                   $file->move(
                       $this->getParameter('uploads_directory'),
                       $newFilename
                   );
               } catch (FileException $e) {
               }
   
               $dataid->setImage($newFilename);
           }
   
           $em->persist($dataid);
           $em->flush();
   
           return $this->redirectToRoute('showgroupe');
       }
   
       return $this->renderForm('groupe/editgroupep.html.twig', [
           'form' => $form
       ]);
   }
   

    
    #[Route('/deletegroupe/{id}', name: 'deletegroupe')]
    public function deletegroupe($id,ManagerRegistry $m, GroupeRepository $groupeRepository): Response
    {
        $em=$m->getManager();
        $id=$groupeRepository->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('showgroupe');
    }
}