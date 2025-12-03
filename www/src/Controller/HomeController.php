<?php

/**
 * ============================================
 * HOME CONTROLLER
 * ============================================
 * 
 * CONCEPT PÉDAGOGIQUE : Controller simple
 * 
 * Ce contrôleur gère la route racine "/" et affiche la page d'accueil.
 */

declare(strict_types=1);

namespace App\Controller;

use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Router\Response;
use JulienLinard\Auth\AuthManager;

class HomeController extends Controller
{
    public function __construct(
        private AuthManager $auth
    ) {}
    
    /**
     * Route racine : affiche la page d'accueil
     * 
     * CONCEPT : Route simple sans middleware
     */
    #[Route(path: '/', methods: ['GET'], name: 'home')]
    public function index(): Response
    {
        $user = $this->auth->user();
        
        return $this->view('home/index', [
            'title' => 'Bienvenue sur Micromania',
            'message' => 'Tous les jeux, sur toute les consoles pour vivre votre passion !',
            'user' => $user,
            'isAuthenticated' => $this->auth->check()
        ]);
    }
}