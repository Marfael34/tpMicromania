<?php

/**
 * ============================================
 * GUEST MIDDLEWARE
 * ============================================
 * 
 * CONCEPT PÉDAGOGIQUE : Middleware pour les routes publiques
 * 
 * Ce middleware est l'inverse d'AuthMiddleware :
 * - Il bloque les utilisateurs DÉJÀ connectés
 * - Il laisse passer les utilisateurs NON connectés (guests)
 * 
 * UTILISATION :
 * Sur les routes de connexion/inscription pour éviter qu'un utilisateur
 * déjà connecté accède à ces pages (il serait redirigé vers le dashboard).
 * 
 * EXEMPLE :
 * Route /login → GuestMiddleware
 * Si l'utilisateur est déjà connecté → redirigé vers /dashboard
 * Si l'utilisateur n'est pas connecté → peut accéder à /login
 */

namespace App\Middleware;

use JulienLinard\Router\Middleware;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use JulienLinard\Core\Application;
use JulienLinard\Auth\AuthManager;

/**
 * Middleware pour rediriger les utilisateurs authentifiés
 * 
 * CONCEPT : Protection inverse
 * Protège les routes publiques contre les utilisateurs déjà connectés
 */
class GuestMiddleware implements Middleware
{
    private AuthManager $auth;

    /**
     * Constructeur : Injection de dépendances
     */
    public function __construct()
    {
        // Récupérer AuthManager depuis le container
        $app = Application::getInstanceOrFail();
        $container = $app->getContainer();
        $this->auth = $container->make(AuthManager::class);
    }

    /**
     * Traite la requête et redirige si l'utilisateur est déjà connecté
     * 
     * CONCEPT PÉDAGOGIQUE : Logique inverse d'AuthMiddleware
     * 
     * Si l'utilisateur est connecté → rediriger vers le dashboard
     * Si l'utilisateur n'est pas connecté → laisser passer
     */
    public function handle(Request $request): ?Response
    {
        //si utilisateur connecté
        if($this->auth->check()){
            $response = new Response(302);
            $response->setHeader('Location', '/');
            return $response;
        }
        return null;
    }
}

