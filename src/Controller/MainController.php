<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    
    public function index(ReservationRepository $reservationRepository)
    {
        $events = $reservationRepository->findAll();
        $reserv = [];

        foreach($events as $event){
            $reserv[] = [
                'id' => $event->getId(),
                'title' => $event->getSujet(),
                'start' => $event->getDate()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }
        $data = json_encode($reserv);

        return $this->render('main/index.html.twig',compact('data'));
    }
}
