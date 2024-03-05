<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(UtilisateurRepository $utilisateurRepository): Response
    {
        $nbUser = $utilisateurRepository->countUsers();
        $nbCoaches = $utilisateurRepository->countCoaches();
        $nbPatients = $utilisateurRepository->countPatients();
        $data = $utilisateurRepository->countUsersByCity();
        $nbFemmes = $utilisateurRepository->countUsersByGenre('femme');
        $nbHommes = $utilisateurRepository->countUsersByGenre('homme');

    

        return $this->render('dashboard/statistique.html.twig', [
            'nbUser' => $nbUser,
            'nbCoaches' => $nbCoaches,
            'nbPatients' => $nbPatients,
            'userData' => $data,
            'nbFemmes' => $nbFemmes,
            'nbHommes' => $nbHommes,

        ]);
    }
}
