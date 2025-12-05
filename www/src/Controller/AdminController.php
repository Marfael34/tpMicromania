<?php

/**
 * ============================================
 * ADMIN CONTROLLER
 * ============================================
 * * Contrôleur pour le back office administrateur
 * Accessible uniquement aux utilisateurs avec le rôle 'admin'
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Catalogue;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\UserRepository;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Session\Session;
use App\Repository\CatalogueRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;
use JulienLinard\Auth\Middleware\RoleMiddleware;
use JulienLinard\Core\View\ViewHelper; // Assure-toi d'importer ceci si tu l'utilises


class AdminController extends Controller
{
    private AuthManager $auth;
    private EntityManager $em;

    public function __construct(AuthManager $auth, EntityManager $em)
    {
        $this->auth = $auth;
        $this->em = $em;
    }

    /**
     * Dashboard admin - Page d'accueil du back office
     */
    #[Route(path: '/admin', methods: ['GET'], name: 'admin.dashboard', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function dashboard(): Response
    {
        // Statistiques globales
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $userRepo = $this->em->createRepository(UserRepository::class, User::class);

        $stats = [
            'total_items' => count($catalogueRepo->findAll()), // Nombre total d'articles
            'total_users' => count($userRepo->findAll()),
            'admin_users' => count($userRepo->findBy(['role' => 'admin'])),
        ];

        return $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'user'  => $this->auth->user()
        ]);
    }

    /**
     * Liste tous les éléments du catalogue
     */
    #[Route(path: '/admin/catalogue', methods: ['GET'], name: 'admin.catalogue', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function catalogue(): Response
    {
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $items = $catalogueRepo->findAll();

        // Charger les relations (Media, User) pour chaque item
        foreach ($items as $item) {
            // Si ta méthode s'appelle loadMediaRelations dans CatalogueRepository
            if (method_exists($catalogueRepo, 'loadMediaRelations')) {
                $catalogueRepo->loadMediaRelations($item);
            }

            // Charger l'utilisateur créateur si nécessaire
            if (isset($item->userId) && $item->userId !== null) {
                $userRepo = $this->em->createRepository(UserRepository::class, User::class);
                $item->user = $userRepo->find($item->userId);
            }
        }

        return $this->view('admin/catalogue', [
            'title' => 'Gestion du Catalogue',
            'items' => $items, 
            'user'  => $this->auth->user()
        ]);
    }

    /**
     * Liste tous les utilisateurs
     */
    #[Route(path: '/admin/users', methods: ['GET'], name: 'admin.users', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function users(): Response
    {
        $userRepo = $this->em->createRepository(UserRepository::class, User::class);
        $users = $userRepo->findAll();

        // Compter les articles du catalogue pour chaque utilisateur
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $usersWithStats = [];
        
        foreach ($users as $user) {
            // Attention : Vérifie bien le nom de la colonne dans ta BDD (user_id ou userId)
            $userItems = $catalogueRepo->findBy(['user_id' => $user->id]);
            
            $usersWithStats[] = [
                'user' => $user,
                'items_count' => count($userItems)
            ];
        }

        return $this->view('admin/users', [
            'title' => 'Gestion des Utilisateurs',
            'users' => $usersWithStats,
            'connected_user' => $this->auth->user()
        ]);
    }

    /**
     * Page de création d'un nouvel élément (Accès Admin)
     */
    #[Route(path: "/admin/catalogue/create", name: "admin.catalogue.create", methods: ["GET"], middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function createForm(): Response
    {
        // Utilisation de ViewHelper::csrfToken() suppose que tu as cette classe importée
        $csrfToken = class_exists(ViewHelper::class) ? ViewHelper::csrfToken() : $_SESSION['csrf_token'] ?? '';

        $plateformes = $this->em->getRepository(\App\Entity\Plateforme::class)->findAll();
        $genres = $this->em->getRepository(\App\Entity\Genre::class)->findAll();

        return $this->view("admin/create_catalogue", [
            "csrf_token" => $csrfToken,
            "user" => $this->auth->user(),
            "title" => "Ajouter un article",
            "plateformes" => $plateformes, // On passe la variable à la vue
            "genres" => $genres
        ]);
    }
    public function create(): Response
    {
        // On récupère toutes les plateformes (et les genres)
        $plateformes = $this->em->getRepository(\App\Entity\Plateforme::class)->findAll();
        $genres = $this->em->getRepository(\App\Entity\Genre::class)->findAll();

        return $this->view('admin/create_catalogue', [
            'title' => 'Ajouter un jeu',
            'plateformes' => $plateformes, // On passe la variable à la vue
            'genres' => $genres
        ]);
    }

    #[Route(path: '/admin/catalogue/edit', methods: ['GET'], name: 'admin.edit', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function edit(Request $request): Response
    {
        // 1. Récupérer l'ID depuis l'URL (ex: ?id=12)
        $id = $request->getQueryParam('id');

        if (!$id) {
            Session::flash('error', 'Identifiant du jeu manquant.');
            return $this->redirect('/admin/catalogue');
        }

        // 2. Initialiser le repository
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        
        // 3. Récupérer LE jeu spécifique (objet)
        $catalogue = $catalogueRepo->findById((int)$id);
        
        if (!$catalogue) {
            Session::flash('error', 'Jeu introuvable.');
            return $this->redirect('/admin/catalogue');
        }

        // 4. Récupérer les listes pour les checkbox
        $plateformes = $this->em->getRepository(\App\Entity\Plateforme::class)->findAll();
        $genres = $this->em->getRepository(\App\Entity\Genre::class)->findAll();
        
        // 5. Envoyer à la vue
        return $this->view('admin/edit', [
            'title' => 'Modifier le jeu',
            'catalogue' => $catalogue,     // C'est maintenant un Objet unique
            'plateformes' => $plateformes, // Nécessaire pour afficher les choix
            'genres' => $genres,           // Nécessaire pour afficher les choix
            'errors' => []
        ]);
    }    
}
