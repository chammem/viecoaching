<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class ZoomController extends AbstractController
{


 #[Route("/ajouter-seance")]
 
public function ajouterSeance(EnvVarProcessorInterface $env)
{
    // Récupérer la clé d'API Zoom à partir des variables d'environnement
    $apiZoomKey = $_ENV['API_ZOOMKEY'];
    // Récupérer la clé secrète Zoom à partir des variables d'environnement
    $secretZoomKey = $_ENV['SECRET_ZOOMKEY'];

    // Créer une nouvelle séance dans votre application Symfony
    // ...

    // Appelez l'API Zoom pour créer une réunion Zoom pour cette séance
    $client = new Client();
    $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
        'headers' => [
            'Authorization' => 'Bearer VOTRE_JETON_D_ACCES_API_ZOOM',
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'topic' => 'Sujet de la Réunion Zoom',
            // Autres paramètres de réunion Zoom
        ],
    ]);

    // Récupérez le lien de réunion Zoom à partir de la réponse de l'API Zoom
    $responseData = json_decode($response->getBody()->getContents(), true);
    $lienZoom = $responseData['join_url'];

    // Enregistrez le lien de réunion Zoom dans votre base de données
    // ...

    // Redirigez l'utilisateur ou affichez un message de réussite
    return new Response('Réunion Zoom créée avec succès. Lien Zoom : ' . $lienZoom);
}

}
