<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Regex;

class SeanceController extends AbstractController
{
    #[Route('/seance', name: 'app_seance')]
    public function index(): Response
    {
        return $this->render('seance/index.html.twig', [
            'controller_name' => 'SeanceController',
        ]);
    }

    #[Route('/showSeance', name: 'app_showSeance')]
    public function showSeance(SeanceRepository $seanceRepository): Response
    {
        $seance=$seanceRepository->findAll();
        
        
        return $this->render('seance/showSeance.html.twig', [
            'seances' => $seance,
        ]);
    }

    #[Route('/AddSeance', name: 'app_AddSeance')]
    public function AddSeance(ManagerRegistry $managerRegistry , HttpFoundationRequest $req): Response
    {

        $em=$managerRegistry->getManager();

        $seance = new Seance();
       $form=$this->createForm(SeanceType::class , $seance);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid()){
        $em->persist($seance);
        $em->flush();
        return $this->redirectToRoute('app_showSeance');
       }

        return $this->renderForm('seance/AddSeance.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/editSeance/{id}', name: 'app_editSeance')]
    public function editSeance($id , SeanceRepository $seanceRepository,
     ManagerRegistry $managerRegistry , HttpFoundationRequest $req)  : Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$seanceRepository->find($id);
    
        $form=$this->createForm(SeanceType::class,$dataid);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($dataid);
            $em->flush();
            return $this->redirectToRoute('app_showSeance');
        }

        return $this->renderForm('seance/editSeance.html.twig', [
            'f' => $form,
            
        ]);
    }

    #[Route('/deleteSeance/{id}', name: 'app_deleteSeance')]
    public function deleteTypeSeance($id , ManagerRegistry $managerRegistry , 
   SeanceRepository $seanceRepository ,HttpFoundationRequest $req ): Response
    {   $em=$managerRegistry->getManager();
        $id=$seanceRepository->find($id);
        $em->remove($id);
        $em->flush();
        
        return $this->redirectToRoute('app_showSeance');
    }
}
