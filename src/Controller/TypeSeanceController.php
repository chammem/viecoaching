<?php

namespace App\Controller;

use App\Entity\TypeSeance;
use App\Form\TypeSeanceType;
use App\Repository\TypeSeanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeSeanceController extends AbstractController
{
    #[Route('/type/seance', name: 'app_type_seance')]
    public function index(): Response
    {
        return $this->render('type_seance/index.html.twig', [
            'controller_name' => 'TypeSeanceController',
        ]);
    }

    #[Route('/showTypeSeance', name: 'app_showTypeSeance')]
    public function showTypeSeance(TypeSeanceRepository $typeSeanceRepository): Response
    {
        $typeS=$typeSeanceRepository->findAll();
        
        
        return $this->render('type_seance/showTypeSeance.html.twig', [
            'typeS' => $typeS,
            
        ]);
    }

    

    #[Route('/AddTypeSeance', name: 'app_AddTypeSeance')]
    public function AddTypeSeance(ManagerRegistry $managerRegistry , Request $req): Response
    {

        $em=$managerRegistry->getManager();

        $typeS = new TypeSeance();
       $form=$this->createForm(TypeSeanceType::class , $typeS);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid()){
        $em->persist($typeS);
        $em->flush();
        return $this->redirectToRoute('app_showTypeSeance');
       }

        return $this->renderForm('type_seance/AddTypeSeance.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/editTypeSeance/{id}', name: 'app_editTypeSeance')]
    public function editTypeSeance($id , TypeSeanceRepository $typeSeanceRepository ,
     ManagerRegistry $managerRegistry , Request $req)  : Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$typeSeanceRepository->find($id);
    
        $form=$this->createForm(TypeSeanceType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_showTypeSeance');
        }

        return $this->renderForm('type_seance/editTypeSeance.html.twig', [
            'form' => $form,
            
        ]);
    }

    #[Route('/deleteTypeSeance/{id}', name: 'app_deleteTypeSeance')]
    public function deleteTypeSeance($id , ManagerRegistry $managerRegistry , 
   TypeSeanceRepository $typeSeanceRepository ,Request $req ): Response
    {   $em=$managerRegistry->getManager();
        $id=$typeSeanceRepository->find($id);
        $em->remove($id);
        $em->flush();
        
        return $this->redirectToRoute('app_showTypeSeance');
    }







}
