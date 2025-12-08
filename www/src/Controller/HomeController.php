<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Catalogue;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Doctrine\EntityManager;

class HomeController extends Controller
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(AuthManager $auth, EntityManager $em)
    {
        // Récupérer tous les jeux
        $repository = $em->getRepository(Catalogue::class);
        $catalogues = $repository->findAll(); // Retourne un tableau d'OBJETS Catalogue

        // Récupérer l'utilisateur connecté
        $user = $auth->getUser();
        $isAuthenticated = $auth->check();

        // Envoyer à la vue
        return $this->render('home/index', [
            'title' => 'Accueil - Micromania',
            'message' => 'Découvrez nos derniers jeux',
            'catalogues' => $catalogues,
            'user' => $user,
            'isAuthenticated' => $isAuthenticated
        ]);
    }
}