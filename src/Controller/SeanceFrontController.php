<?php

namespace App\Controller;

use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SeanceFrontController extends AbstractController
{
    #[Route('/seance/front', name: 'app_seance_front')]
    public function index(): Response
    {
        return $this->render('seance_front/index.html.twig', [
            'controller_name' => 'SeanceFrontController',
        ]);
    }
    #[Route('/showSeancefront', name: 'app_showSeancefront')]
    public function showSeance(SeanceRepository $seanceRepository): Response
    {
        $seance=$seanceRepository->findAll();
      
            return $this->render('seance/showSeancefront.html.twig', [
                'seances' => $seance,
            ]);
        
      
    }

    #[Route('/reserverseance', name: 'app_reserverseance')]
    public function index1(): Response
    {
        return $this->render('reservation/ReserverSeance.html.twig', [
            'controller_name' => 'SeanceFrontController',
        ]);
    }
}
