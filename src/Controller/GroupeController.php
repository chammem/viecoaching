<?php

namespace App\Controller;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\GroupeType;
use App\Entity\Groupe;
use App\Form\SearchgroupType;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier ;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GroupeController extends AbstractController
{
   
    
#[Route('/stat', name: 'stat')]
public function stat(GroupeRepository $groupeRepository): Response
{
    // Récupérer le nombre total de groupes
    $nombreGroupes = $groupeRepository->count([]);

    // Transmettre le nombre de groupes au template Twig
    return $this->render('groupe/stat.html.twig', [
        'nombre_groupes' => $nombreGroupes,
    ]);
}

private $flashy;

public function __construct(FlashyNotifier $flashy)
{
    $this->flashy = $flashy;
}


#[Route('/listegroupe', name:'listegroupe')]
    public function listegroupe(GroupeRepository $x): Response
    {
        $groupe=$x->findAll();
        return $this->renderForm('groupe/listegroupe.html.twig', [
            'groupe'=> $groupe,
        ]);
}







            #[Route('/showgroupe', name: 'showgroupe')]

            public function showgroupe(GroupeRepository $x,Request $req,PaginatorInterface $paginator): Response
            {
               
               $groupe = $x->trie();
               $pagination = $paginator->paginate(
                $groupe
                ,$req->query->getInt('page',1),
                2
            );
              //  $cat = $x->findAll();
              $form=$this->createForm(SearchgroupType::class);
              $form->handleRequest($req);
              if($form->isSubmitted()){
                  $data=$form->get('nom')->getData();
                  $Ref=$x->recherche($data);
                return $this->renderForm('groupe/showgroupe.html.twig', [
                    'groupe'=> $Ref,
                    'groupe'=>$pagination,

                    'form'=> $form,


                ]);
                    
                }
                return $this->renderForm('groupe/showgroupe.html.twig', [
                    'groupe'=> $groupe,
                    'groupe'=>$pagination,

                    'form'=> $form,
       
                    
                ]);}
                    


                #[Route('/creategroupe', name: 'creategroupe')]
                public function createGroupe(Request $request, SluggerInterface $slugger, UtilisateurRepository $utilisateurRepository, MailerInterface $mailer, FlashyNotifier $flashy): Response
                {
                    $groupe = new Groupe();
                    $form = $this->createForm(GroupeType::class, $groupe);
            
                    // Récupérer les utilisateurs en fonction de leur rôle (exemple : ROLE_USER)
                    $utilisateurs = $utilisateurRepository->findByRole('ROLE_USER');
            
                    // Passer les utilisateurs au formulaire
                    $form->get('utilisateur')->setData($utilisateurs);
            
                    $form->handleRequest($request);
            
                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
            
                        // Gérer l'upload de l'image
                        $file = $form->get('image')->getData();
                        if ($file) {
                            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeFilename = $slugger->slug($originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                            try {
                                $file->move(
                                    $this->getParameter('uploads_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // Gérer l'exception, si nécessaire
                            }
            
                            $groupe->setImage($newFilename);
                        }
            
                        // Ajouter les utilisateurs sélectionnés
                        $utilisateursSelectionnes = $form->get('utilisateur')->getData();
                        foreach ($utilisateursSelectionnes as $utilisateur) {
                            $groupe->addUtilisateur($utilisateur);
            
                            // Personnalisez le contenu de l'e-mail pour chaque utilisateur
                            $email = (new Email())
                                ->from('viecoaching@gmail.com')
                                ->to($utilisateur->getEmail())
                                ->subject('Vous avez été ajouté à un groupe')
                                ->html($this->renderView(
                                    'emails/added_to_group.html.twig',
                                    ['utilisateur' => $utilisateur, 'groupe' => $groupe]
                                ));
            
                            // Envoyez l'e-mail à chaque utilisateur
                            $mailer->send($email);
                        }
            
                        $entityManager->persist($groupe);
                        $entityManager->flush();
            
                        $flashy->success('Nouveau groupe créé avec succès');

                        return $this->redirectToRoute('showgroupe');

                    }
            
                    return $this->render('groupe/creategroupe.html.twig', [
                        'form' => $form->createView(),
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