<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservType;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/showreservation', name: 'app_showreservation')]
    public function showreservation(ReservationRepository $reservationRepository): Response
    {
        $reservation=$reservationRepository->findAll();
      
            return $this->render('reservation/showreservation.html.twig', [
                'reservations' => $reservation,
            ]);
        
      
    }

    #[Route('/Addreservation', name: 'app_Addreservation')]
    public function Addreservation(ManagerRegistry $managerRegistry , Request $req): Response
    {

        $em=$managerRegistry->getManager();

        $reservation = new Reservation();
       $form=$this->createForm(ReservType::class , $reservation);
       $form->handleRequest($req);
       if ($form->isSubmitted() and $form->isValid()){
        $em->persist($reservation);
        $em->flush();
        return $this->redirectToRoute('app_showreservation');
       }
        return $this->renderForm('reservation/Addreservation.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/accepter-reservation/{id}', name: 'accepter_reservation')]
    public function accepterReservation($id): Response
    {
        // Redirection vers une autre page après avoir accepté la réservation
        return $this->redirectToRoute('liste_des_reservations');
    }

    #[Route('/refuser-reservation/{id}', name: 'refuser_reservation')]
    public function refuserReservation($id): Response
    {
        // Redirection vers une autre page après avoir refusé la réservation
        return $this->redirectToRoute('liste_des_reservations');
    }



}
