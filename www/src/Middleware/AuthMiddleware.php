<?php

/**
 * ============================================
 * AUTH MIDDLEWARE
 * ============================================
 * 
 * CONCEPT PÉDAGOGIQUE : Middleware d'authentification
 * 
 * Ce middleware protège les routes qui nécessitent une authentification.
 * 
 * FONCTIONNEMENT :
 * 1. Vérifie si l'utilisateur est connecté (via AuthManager::check())
 * 2. Si non connecté :
 *    - Pour les requêtes GET → redirige vers /login
 *    - Pour les requêtes POST/AJAX → retourne une erreur JSON 401
 * 3. Si connecté → laisse passer la requête vers le Controller
 * 
 * UTILISATION :
 * Ajouter AuthMiddleware::class dans l'attribut #[Route]
 * Exemple : #[Route(..., middleware: [AuthMiddleware::class])]
 */

namespace App\Middleware;

use JulienLinard\Router\Middleware;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use JulienLinard\Core\Application;
use JulienLinard\Auth\AuthManager;

/**
 * Middleware pour vérifier l'authentification
 * 
 * CONCEPT : Protection des routes
 * Toutes les routes protégées doivent avoir ce middleware
 */
class AuthMiddleware implements Middleware
{
    private AuthManager $auth;

    /**
     * Constructeur : Injection de dépendances
     * 
     * CONCEPT : Récupération du service depuis le container DI
     */
    public function __construct()
    {
        // Récupérer AuthManager depuis le container
        // CONCEPT : Service Locator Pattern
        $app = Application::getInstanceOrFail();
        $container = $app->getContainer();
        $this->auth = $container->make(AuthManager::class);
    }

    /**
     * Traite la requête et vérifie l'authentification
     * 
     * CONCEPT PÉDAGOGIQUE : Méthode handle() du Middleware
     * 
     * Cette méthode est appelée automatiquement par le Router
     * AVANT l'exécution du Controller.
     * 
     * Si l'utilisateur n'est pas authentifié, la requête est bloquée ici.
     */
    public function handle(Request $request): ?Response
    {
        // Vérifier si l'utilisateur est connecté
        // CONCEPT : AuthManager::check() vérifie la session
        if (!$this->auth->check()) {
            // Utilisateur non connecté → bloquer l'accès
            
            // Pour les requêtes GET (pages web) → rediriger vers la page de connexion
            // CONCEPT : Redirection HTTP 302
            // L'utilisateur est redirigé vers /login pour se connecter
            if ($request->getMethod() === 'GET') {
                $response = new Response(302);
                $response->setHeader('Location', '/login');
                return $response;
            }
            
            // Pour les requêtes POST/AJAX → retourner une erreur JSON
            // CONCEPT : Réponse JSON pour les API
            // Code HTTP 401 = Unauthorized (non authentifié)
           return Response::json([
                'error' => 'Non authentifié',
                'message' => 'Vous devez être connecté pour accéder à cette ressource.'
            ], 401);
        }
        
        // Si on arrive ici, l'utilisateur est authentifié
        // Le middleware laisse passer la requête vers le Controller
        return null;
    }
}

