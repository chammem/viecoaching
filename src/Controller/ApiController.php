<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    
    #[Route("/api/{id}/edit", name: "api_event_edit", methods: ["PUT"])]

    public function majEvent(int $id,Request $request,ManagerRegistry $managerRegistry):Response
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());
        $reservation = $managerRegistry->getRepository(Reservation::class)->find($id);
        

        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        )
        {
            $code = 200;

            // On vérifie si l'id existe
            if(!$reservation){
                // On instancie un rendez-vous
                $reservation = new Reservation;
                $code = 201;
            }  
             // On hydrate l'objet avec les données
             $reservation->setSujet($donnees->title);
             $reservation->setDate(new DateTime($donnees->start));
             $reservation->setDatefin(new DateTime($donnees->end));
             $reservation->setDescription($donnees->description);
             $reservation->setBackgroundColor($donnees->backgroundColor);
             $reservation->setBorderColor($donnees->borderColor);
             $reservation->setTextColor($donnees->textColor);
            
        
             $em=$managerRegistry->getManager();
             $em->persist($reservation );
             $em->flush();
 
             // On retourne le code
             return new Response('Ok', $code);
         }else{
             // Les données sont incomplètes
             return new Response('Données incomplètes', 404);
         }
         return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    

    }

}
