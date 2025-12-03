<?php

/**
 * ============================================
 * ADMIN CONTROLLER
 * ============================================
 * 
 * Contrôleur pour le back office administrateur
 * Accessible uniquement aux utilisateurs avec le rôle 'admin'
 */

declare(strict_types=1);

namespace App\Controller;

use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Auth\Middleware\AuthMiddleware;
use JulienLinard\Auth\Middleware\RoleMiddleware;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Core\Session\Session;
use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use App\Entity\Todo;
use App\Entity\User;

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
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        // Statistiques globales
        $todoRepo = $this->em->createRepository(TodoRepository::class, Todo::class);
        $userRepo = $this->em->createRepository(UserRepository::class, User::class);

        $stats = [
            'total_todos' => count($todoRepo->findAll()),
            'completed_todos' => count($todoRepo->findBy(['completed' => true])),
            'pending_todos' => count($todoRepo->findBy(['completed' => false])),
            'total_users' => count($userRepo->findAll()),
            'admin_users' => count($userRepo->findBy(['role' => 'admin'])),
        ];

        return $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'stats' => $stats
        ]);
    }

    /**
     * Liste tous les todos
     */
    #[Route(path: '/admin/todos', methods: ['GET'], name: 'admin.todos', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function todos(): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $todoRepo = $this->em->createRepository(TodoRepository::class, Todo::class);
        $todos = $todoRepo->findAll();

        // Charger les médias et les utilisateurs pour chaque todo
        foreach ($todos as $todo) {
            $todoRepo->loadMediaRelations($todo);
            // Charger l'utilisateur si nécessaire
            if ($todo->userId !== null) {
                $userRepo = $this->em->createRepository(UserRepository::class, User::class);
                $todo->user = $userRepo->find($todo->userId);
            }
        }

        return $this->view('admin/todos', [
            'title' => 'Gestion des Todos',
            'todos' => $todos
        ]);
    }

    /**
     * Liste tous les utilisateurs
     */
    #[Route(path: '/admin/users', methods: ['GET'], name: 'admin.users', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function users(): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $userRepo = $this->em->createRepository(UserRepository::class, User::class);
        $users = $userRepo->findAll();

        // Compter les todos pour chaque utilisateur et créer un tableau enrichi
        $todoRepo = $this->em->createRepository(TodoRepository::class, Todo::class);
        $usersWithStats = [];
        
        foreach ($users as $user) {
            $usersWithStats[] = [
                'user' => $user,
                'todos_count' => count($todoRepo->findBy(['user_id' => $user->id]))
            ];
        }

        return $this->view('admin/users', [
            'title' => 'Gestion des Utilisateurs',
            'users' => $usersWithStats
        ]);
    }
}
