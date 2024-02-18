<?php

namespace App\Controller;

use App\Entity\Ressources;
use App\Form\RessourcesType;
use App\Repository\RessourcesRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File as FileFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class RessourcesController extends AbstractController
{
    #[Route('/ressources', name: 'app_ressources')]
    public function index(): Response
    {
        return $this->render('ressources/index.html.twig', [
            'controller_name' => 'RessourcesController',
        ]);
    }
    //function affiche ressources
    
    #[Route('/afficheRessource', name: 'afficheRessource')]
    public function afficheRessource(RessourcesRepository $x): Response
    {
        $ressource = $x->findAll();
        return $this->render('ressources/afficheRessource.html.twig', [
            'ressource'=> $ressource
        ]);
            
        }
        //
        //affiche partie patient
        #[Route('/afficheResP', name: 'afficheResP')]
        public function afficheResP(RessourcesRepository $x): Response
        {
            $ressource = $x->findAll();
            return $this->render('ressources/afficheResP.html.twig', [
                'ressource'=> $ressource
            ]);
                
            }
        //Ajout ressource 
       //function ajout ressource
       #[Route('/addressource', name: 'addressource')]
       public function addressource(ManagerRegistry $managerRegistry, Request $req): Response
       {
           $em=$managerRegistry->getManager();
           $ressource=new Ressources;
           $form=$this->createForm(RessourcesType::class,$ressource);
           $form->handleRequest($req);
           if($form->isSubmitted() && $form->isValid())
           {  
               $file = $form->get('url')->getData();
               $file->move(
                   $this->getParameter('upload_directory'),
                   $file->getClientOriginalName()
               );
               
               $em->persist($ressource);
               $em->flush();
               return $this->redirectToRoute('afficheRessource');
           }
           return $this->renderForm('ressources/addressource.html.twig', [
               'form' => $form ,
           ]);
       }
   
        //
        #[Route('/download/{id}', name:'download')]
        public function download(Ressources $ressource): Response
        {
        $file = new File($this->getParameter('upload_directory').'/'.$ressource->getTypeR());
        return $this->file($file);
    }
     //modifier un ressource
     #[Route('/editressource/{id}', name: 'editressource')]
     public function editressource($id,RessourcesRepository $ressourcesRepository,ManagerRegistry $managerRegistry,Request $req): Response
     {
         
         $em=$managerRegistry->getManager();
         $dataid=$ressourcesRepository->find($id);
         $form= $this->createForm(RessourcesType::class,$dataid);
         $form->handleRequest($req);
         if($form->isSubmitted() and $form->isValid()){
             $em->persist($dataid);
             $em->flush();
             return $this->redirectToRoute('afficheRessource');
         }
         return $this->renderForm('ressources/editressource.html.twig', [
             'form' => $form 
         ]);
     }
     //supprimer ressource
     #[Route('/deletressource/{id}', name: 'deletressource')]
    public function deletressource($id,RessourcesRepository $ressourcesRepository ,ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $id=$ressourcesRepository->find($id);
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('afficheRessource');
        
    }
    //recherche ressource
   /* #[Route("/recherche", name="rechercher")]
        public function rechercher(Request $request): Response
    {
        $ressource = new Ressources;
        $form = $this->createForm(RechercheRessourceType::class, $ressource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $critere = $form->getData();
            $repository = $this->getDoctrine()->getRepository(Ressource::class);
            $ressources = $repository->findByCritere($critere); 
            return $this->render('ressource/resultats_recherche.html.twig', [
                'ressources' => $ressources,
            ]);
        }
        return $this->render('recherche.html.twig', [
            'form' => $form->createView(),
        ]);
    }*/
}
