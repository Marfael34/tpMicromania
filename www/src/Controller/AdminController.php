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
use App\Entity\Genre;
use App\Entity\Catalogue;
use App\Entity\Plateforme;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\UserRepository;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;
use App\Repository\CatalogueRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;
use JulienLinard\Auth\Middleware\RoleMiddleware;


class AdminController extends Controller
{
    private AuthManager $auth;
    private EntityManager $em;
    private FileUploadService $fileUploadService;

    public function __construct(AuthManager $auth, EntityManager $em)
    {
        $this->auth = $auth;
        $this->em = $em;
        $this->fileUploadService = new FileUploadService();
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

        $plateformes = $this->em->getRepository(Plateforme::class)->findAll();
        $genres = $this->em->getRepository(Genre::class)->findAll();
        return $this->view("admin/create_catalogue", [
            "csrf_token" => $csrfToken,
            "user" => $this->auth->user(),
            "title" => "Ajouter un article",
            "plateformes" => $plateformes, // On passe la variable à la vue
            "genres" => $genres
        ]);
    }
     public function create(Request $request): Response
    {
        // 1. Récupération des données du formulaire
        $title = trim($request->getPost('title') ?? '');
        $description = trim($request->getPost('description') ?? '');
        $price = (float) ($request->getPost('price') ?? 0);
        $stock = (int) ($request->getPost('stock') ?? 0);

        // Récupération des IDs des cases cochées (tableaux)
        $genreIds = $request->getPost('genres') ?? [];
        $plateformeIds = $request->getPost('plateformes') ?? [];

        // 2. Validation basique
        if (empty($title)) {
            Session::flash('error', 'Le titre est obligatoire');
            return $this->redirect('/admin/catalogue/create');
        }

        try {
            // 3. Création et hydratation de l'entité Catalogue
            $catalogue = new Catalogue();
            $catalogue->setTitle($title)
                      ->setDescription($description)
                      ->setPrice($price)
                      ->setStock($stock);

            // 4. Gestion de l'upload d'image (via ton service existant)
            if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
                $result = $this->fileUploadService->upload($_FILES['media']);
                if ($result->isSuccess()) {
                    $data = $result->getData();
                    $catalogue->setMediaPath($data['path']);
                } else {
                    Session::flash('error', 'Erreur upload : ' . $result->getError());
                    return $this->redirect('/admin/catalogue/create');
                }
            }

            // 5. Sauvegarde du Jeu (Catalogue) en premier
            // C'est CRUCIAL : il faut persist et flush pour générer l'ID du jeu
            $this->em->persist($catalogue);
            $this->em->flush(); 

            // À ce stade, $catalogue->getId() contient l'ID généré par la BDD (ex: 42)

            // 6. Sauvegarde des relations (Genres et Plateformes)
            /** @var CatalogueRepository $catalogueRepo */
            $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
            
            // On appelle ta méthode magique du Repository
            $catalogueRepo->saveManyToManyRelations(
                $catalogue->getId(), // L'ID qu'on vient de créer
                $genreIds,           // Les IDs des genres cochés
                $plateformeIds       // Les IDs des plateformes cochées
            );

            Session::flash('success', 'Jeu créé avec succès et relations sauvegardées !');
            return $this->redirect('/admin/catalogue');

        } catch (\Exception $e) {
            Session::flash('error', 'Erreur lors de la création : ' . $e->getMessage());
            return $this->redirect('/admin/catalogue/create');
        }
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

        $catalogueRepo->loadRelations($catalogue);

        // 4. Récupérer les listes pour les checkbox
        $plateformes = $this->em->getRepository(Plateforme::class)->findAll();
        $genres = $this->em->getRepository(Genre::class)->findAll();

        
        // 5. Envoyer à la vue
        return $this->view('admin/edit', [
            'title' => 'Modifier le jeu',
            'catalogue' => $catalogue,     // C'est maintenant un Objet unique
            'plateformes' => $plateformes, // Nécessaire pour afficher les choix
            'genres' => $genres,           // Nécessaire pour afficher les choix
            'errors' => []
        ]);
    }    

    #[Route(path: '/admin/catalogue/update', methods: ['POST'], name: 'admin.catalogue.update', middleware: [new AuthMiddleware('/login'), new RoleMiddleware('admin', '/')])]
    public function update(Request $request): Response
    {
        // 1. Récupération de l'ID via POST (formulaire)
        $id = $request->getPost('id'); // REMPLACÉ: getBodyParam -> getPost
        
        if (!$id) {
            Session::flash('error', 'Identifiant du jeu manquant.');
            return $this->redirect('/admin/catalogue');
        }

        // 2. Récupération du jeu
        /** @var CatalogueRepository $catalogueRepo */
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $catalogue = $catalogueRepo->findById((int)$id);

        if (!$catalogue) {
            Session::flash('error', 'Jeu introuvable.');
            return $this->redirect('/admin/catalogue');
        }

        $this->em->persist($catalogue);

        // 3. Récupération des données du formulaire
        $title = trim($request->getPost('title') ?? '');
        $description = trim($request->getPost('description') ?? '');
        $price = (int) ($request->getPost('price') ?? 0);
        $stock = (int) ($request->getPost('stock') ?? 0);
        
        // Récupération des relations (checkboxes)
        $genreIds = $request->getPost('genres') ?? [];
        $plateformeIds = $request->getPost('plateformes') ?? [];

        // 4. Validation
        $errors = [];
        if (empty($title)) {
            $errors['title'] = 'Le titre est requis';
        } elseif (strlen($title) > 255) {
            $errors['title'] = 'Le titre ne doit pas dépasser 255 caractères';
        }

        // En cas d'erreur, on réaffiche le formulaire
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            // On doit recharger les listes pour la vue
            $plateformes = $this->em->getRepository(Plateforme::class)->findAll();
            $genres = $this->em->getRepository(Genre::class)->findAll();
            // On recharge les relations actuelles pour ne pas perdre les cases cochées
            $catalogueRepo->loadRelations($catalogue);

            return $this->view('admin/edit', [
                'title' => 'Modifier le jeu',
                'catalogue' => $catalogue,
                'plateformes' => $plateformes,
                'genres' => $genres,
                'errors' => $errors
            ]);
        }

        try {
            // 5. Mise à jour des champs scalaires
            $catalogue->setTitle($title)
                      ->setDescription($description ?: null)
                      ->setPrice($price)
                      ->setStock($stock);

            // 6. Gestion de l'upload d'image (remplacement)
            // On vérifie si un fichier a été envoyé via $_FILES
            if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
                
                // Upload du nouveau fichier
                $result = $this->fileUploadService->upload($_FILES['media']);

                if ($result->isSuccess()) {
                    $data = $result->getData();
                    $newFilename = $data['path']; // '/uploads/media_xyz.jpg'

                    // Suppression de l'ancien fichier s'il existe et n'est pas vide
                if ($catalogue->media_path) {
                    // FileUploadService->delete attend juste le nom du fichier, on utilise basename
                    $oldFilename = basename($catalogue->media_path);
                    
                    // CORRECTION ICI (retirez le 'p' en trop) :
                    // AVANT : $this->fileUplpoadService->delete($oldFilename);
                    $this->fileUploadService->delete($oldFilename);
                }

                    // Mise à jour de l'entité
                    $catalogue->setMediaPath($newFilename);
                } else {
                    // Erreur d'upload
                    Session::flash('error', 'Erreur upload : ' . $result->getError());
                    return $this->redirect("/admin/catalogue/edit?id={$id}");
                }
            }

            // 7. Persistance des changements sur l'entité Catalogue
            $this->em->persist($catalogue);
            $this->em->flush();

            // 8. Sauvegarde des relations Many-to-Many (Genres et Plateformes)
            // Utilisation de la méthode créée dans CatalogueRepository
            $catalogueRepo->saveManyToManyRelations($catalogue->getId(), $genreIds, $plateformeIds);

            Session::flash('success', 'Jeu modifié avec succès !');
            return $this->redirect('/admin/catalogue');

        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            return $this->redirect("/admin/catalogue/edit?id={$id}");
        }
    }

     #[Route(path: '/admin/catalogue/{id}/delete', methods: ['POST'], name: 'admin.delete', middleware: [new AuthMiddleware()])]
    public function delete(int $id): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }
        
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $catalogue = $catalogueRepo->findByIdAndUser($id, $user->getAuthIdentifier());
        
        if (!$catalogue) {
            Session::flash('error', 'jeux introuvable');
            return $this->redirect('/admin/catalogue');
        }
        
        try {
            // Supprimer les médias associés (optionnel - vous pouvez garder les médias)
            // Pour cet exemple, on ne supprime pas les médias, juste la relation
            
            $this->em->remove($catalogue);
            $this->em->flush();
            
            Session::flash('success', 'Jeux supprimé avec succès !');
        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue lors de la suppression du jeux');
        }
        
        return $this->redirect('/admin/catalogue');
    }
}
