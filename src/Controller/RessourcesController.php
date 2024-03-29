<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Ressources;
use App\Form\RechercheResourceType;
use App\Form\RessourcesType;
use App\Repository\RessourcesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
/*resbycat
    #[Route('/resources/{categorySlug}', name:'resourcesbycategory')]
    public function resourcesbycategory($categorySlug): Response
    {
        $category = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // Récupérez les ressources liées à cette catégorie
        $resources = $category->getResources();

        return $this->render('afficheCatP.html.twig', [
            'category' => $category,
            'resources' => $resources,
        ]);
    }*/


    
    //function affiche ressources
    
    #[Route('/afficheRessource', name: 'afficheRessource')]
    public function afficheRessource(RessourcesRepository $x ,Request $request,  PaginatorInterface $paginator): Response
    {
       
        $resources = $x->trie();
        $pagination = $paginator->paginate(
        $resources, 
        $request->query->getInt('page',1),
        2
    );
    $form=$this->createForm(RechercheResourceType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data=$form->get('TitreR')->getData();
            $Ref=$x->recherche($data);
            return $this->renderForm('ressources/afficheRessource.html.twig', [
                'ressources'=> $Ref,
                'form'=> $form,
            ]);
        }
        return $this->renderForm('ressources/afficheRessource.html.twig', [
            'ressources' => $pagination ,
            'ressources' => $resources ,
             'form'=> $form,

        ]);
            
        }
    
        //affiche partie patient
        #[Route('/afficheResP', name: 'afficheResP')]
        public function afficheResP(Request $request, RessourcesRepository $x, PaginatorInterface $paginator): Response
        {
    
            $resources=$x->findAll();
            $pagination = $paginator->paginate(
                $resources, 
                $request->query->getInt('page',1),
                2
            );
            return $this->render('ressources/afficheResP.html.twig', [
                'ressources' => $pagination 
            ]);
        }
        //Ajout ressource 
        #[Route('/addressource', name: 'addressource')]
        public function addressource(ManagerRegistry $managerRegistry, Request $req,SluggerInterface $slugger): Response
        {
            $em=$managerRegistry->getManager();
            $ressource=new Ressources;
            $form=$this->createForm(RessourcesType::class,$ressource);
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid())
            {  
                $file = $form->get('url')->getData();
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
    
                    $ressource->setUrl($newFilename);

                } 
                $em->persist($ressource);
                $em->flush();
                return $this->redirectToRoute('afficheRessource');
            }
            return $this->renderForm('ressources/addressource.html.twig', [
                'form' => $form ,
            ]);
        } 

         
 //ferr       
/* #[Route('/addressource', name: 'addressource')]
public function addressource(ManagerRegistry $managerRegistry, Request $req, SluggerInterface $slugger): Response
{
    $em = $managerRegistry->getManager();
    $ressource = new Ressources;
    $form = $this->createForm(RessourcesType::class, $ressource);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('url')->getData();
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
                // Handle file upload error
                return new JsonResponse(['error' => 'Error uploading file'], 500);
            }

            $ressource->setUrl($newFilename);
        }

        $em->persist($ressource);
        $em->flush();

        // Return success message
        return new JsonResponse(['message' => 'Resource added successfully'], 200);
    }

    // Return validation errors if form is invalid
    return new JsonResponse(['error' => 'Form validation failed'], 422);
} */

    
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

   
    #[Route('/statistic', name: 'statistic')]
    public function statistic(RessourcesRepository $r): Response
    {
        $nombreR = $r->count([]);
        return $this->render('ressources/statistic.html.twig', [
            'nbr' => $nombreR,
        ]);
    }
}
